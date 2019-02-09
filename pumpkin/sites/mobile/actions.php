<?
// **************************************************************************************************
	//Lib laden
	$pmkCaller = '../';	//  Because its called from a subfolder
	include($_SERVER['DOCUMENT_ROOT'].'/config.php');
// **************************************************************************************************
	$msg = '';
    $redirect_page = $_SERVER['HTTP_REFERER'];
	if (isset($_POST['a'])) {
		switch($_POST['a']) {
			case 'writeArbeit':
				if(canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
					$zeit_von = explode(":", $_POST['von']);
					$zeit_bis = explode(":", $_POST['bis']);
					$stunden = (mktime($zeit_bis[0],$zeit_bis[1],0,1,1,2000)-mktime($zeit_von[0],$zeit_von[1],0,1,1,2000))/60/60;
					$sql = "INSERT INTO `rap_stunden` (`date`, `vertrag`, `arbeit`, `comment`, `von`, `bis`, `stunden`, `uid`) VALUES ('".date_german2mysql($_POST['datum'])."','".$_POST['vertrag']."','".$_POST['arbeit']."','".$_POST['comment']."','".$_POST['von'].":00"."','".$_POST['bis'].":00"."','".$stunden."', '".$_SESSION['myid']."')";
					$db->query($sql);
					$msg = "Arbeit erfasst!";
				}
				break;
			case 'editArbeitStunden':
				if(canEnterBinary('0.0.0.0.1.0.0.0.0.1') && canUserEditStunde($_POST['id'])) {
					$zeit_von = explode(":", $_POST['von']);
					$zeit_bis = explode(":", $_POST['bis']);
					$stunden = (mktime($zeit_bis[0],$zeit_bis[1],0,1,1,2000)-mktime($zeit_von[0],$zeit_von[1],0,1,1,2000))/60/60;
					// Instead of overwriting, old entry is deleted, new one is created
					$db->query("DELETE FROM rap_stunden WHERE ID = '".$_POST['id']."'");
					$sql = "INSERT INTO `rap_stunden` (`date`, `vertrag`, `arbeit`, `comment`, `von`, `bis`, `stunden`, `uid`) VALUES ('".date_german2mysql($_POST['datum'])."','".$_POST['vertrag']."','".$_POST['arbeit']."','".$_POST['comment']."','".$_POST['von'].":00"."','".$_POST['bis'].":00"."','".$stunden."', '".$_SESSION['myid']."')";
					$db->query($sql);
					$msg = "Stundenerfassung ge&auml;dert!";
				}
				break;
			case 'addVertrag':
				if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
					$sql = "INSERT INTO rap_vertrag (`ID`) VALUES ('')";
					if(!$db->query($sql)){echo $db->errormessage.'<br />';}
					$id = mysql_insert_id();
					foreach($_POST as $k=>$g) {
						if($k!='a') {
							$sql = "UPDATE rap_vertrag SET `$k` = '".mysql_escape_string($g)."' WHERE `ID` = $id";
							if(!$db->query($sql)){echo $db->errornumber.':'.$db->errormessage.'<br />';}
						}
					}
					$msg = "Vertrag hinzugef&uuml;gt";
				}
				break;
			case 'addArbeit':
				if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
					$sql = "INSERT INTO rap_arbeit (`ID`, `name`) VALUES ('', '".$_POST['name']."')";
					if(!$db->query($sql)){echo $db->errormessage.'<br />'.$sql;}
					$msg = "Arbeit hinzugef&uuml;gt!";
				}
				break;
			case 'makeBill':
				if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
					$von = "2000-01-01";	// default Datum wenn noch keine Rechnung vorhanden
					$db->query("SELECT * FROM rap_bills WHERE vertrag = '".$_POST['v']."' ORDER BY bis DESC LIMIT 1");
					while($l = $db->results()) {
						$von = $l['bis'];  // Datum von letzter Rechnung
					}
					$von = date("Y-m-d", strtotime($von.' + 1 day'));  // Ein Tag mehr als letzte Rechnung
					$sql = "INSERT INTO rap_bills (`id`, `vertrag`, `von`,`bis`, `status`) VALUES ('', '".$_POST['v']."', '$von', NOW(),'0')";
					if(!$db->query($sql)){echo '<br />'.$db->errormessage.'<br />'.$sql;}
					$msg = "Rechnung erstellt!";
					$msg = $sql;
				}
				break;
			case 'updateConfig':
				if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
					foreach($_POST as $k=>$g) {
						if($k!='a') {
							$sql = "UPDATE rap_config SET `value` = '$g' WHERE `name` = '$k'";
							if(!$db->query($sql)){echo $db->errormessage.'<br />'.$sql;}
						}
						$msg = "Eintr&auml;ge ge&auml;ndert!";
					}
				}
				break;
			case 'updateIncludes':
				if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
					pmkUpdateIncludes();
					pmkCssUpdate();
					$msg = 'Include Dateien aktualisiert!';
				}
				break;
            case 'invoiceEdit':
                if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
                    if (preg_match('/^\d\d\d\d\-\d\d\-\d\d$/', $_POST['bis'])) {
					    $sql = "UPDATE rap_bills SET bis = '".$_POST['bis']."' WHERE id = ".((int) $_POST['id']);
                        $db->query($sql);
                        $msg = 'Invoice '.$_POST['id'].' date got changed!';
                        $redirect_page = '/index.php?p=arbeit.rechnungen';
                    }
                    else {
                        $msg = 'Error when trying to update invoice';
                    }
				}
		}
	}
	if(isset($_GET['a'])) {
		switch($_GET['a']) {
			case 'changeBillStatus':
				if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
					$status = 0;
					if($_GET['s']=='0'){$status=1;}
					if($_GET['s']=='1'){$status=2;}
					if($_GET['s']=='2'){$status=0;}
					$sql = "UPDATE rap_bills SET status = $status WHERE id = '".$_GET['b']."'";
					$db->query($sql);
				}
				break;
			case 'xlsstunden':
				if(canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
					$msg = xlsExportStunden($_GET['vertrag']);
				}
				break;
			case 'UpdateHt':
				if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
					$msg = updateHtpasswd();
				}
				break;
			case 'EditArbeitStunde':
				if(canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
				}
				break;
			case 'nextScripture':
				$uid = $_SESSION['myid'];
				$GLOBALS['db']->query("UPDATE lds_study SET sid = (sid+1) WHERE uid = $uid");
				break;
		}
	}
	$_SESSION['msg'] = $msg;
	header("Location: ".$redirect_page);
// **************************************************************************************************
	//Lib schliessen
	endlib();			// from lib/lib.php
// **************************************************************************************************
?>