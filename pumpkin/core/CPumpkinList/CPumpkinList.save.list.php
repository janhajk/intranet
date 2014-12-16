<?
// **************************************************************************************************
	//Lib laden
	include($_SERVER['DOCUMENT_ROOT'].'/config.php');
// **************************************************************************************************
	header('Content-Type: text/html');
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
	$db->query("SET NAMES 'utf8';");  // Damit die Umlaute richtig dargestellt werden
	$r = array();
	// Tabelle, in welche geschrieben wird
	$t = mysql_escape_string($_GET['table']);
	if(canEnterBinary('0.0.0.0.0.0.0.1.1.1')) {	// es darf nur bis Rechtsstufe 8 editiert werden
		// Wenn neuer Eintrag erstellt wird
		if($_GET['act']=='new') {
			$sql = "INSERT INTO ".$t." (`id`) VALUES ('')";
			if(!$db->query($sql)){echo $db->errormessage.'<br />';}
			$id = mysql_insert_id();
			$db->query("UPDATE ".$t." SET `author` = '".$_SESSION['myid']."' WHERE `id` = $id");
			$db->query("UPDATE ".$t." SET `date_created` = NOW() WHERE `id` = $id");
		}
		// Wenn editiert wird
		else {
				$id = mysql_escape_string($_GET['id']);			
		}
		foreach($_GET as $k=>$g) {
			if($k!='act' && $k!='_' && $k!='id' && $k!='table') {
				$sql = "UPDATE ".$t." SET `$k` = '".mysql_escape_string($g)."' WHERE `id` = $id";
				if(!$db->query($sql)){echo $db->errornumber.':'.$db->errormessage.'<br />';}
			}
		}
		// Letzte Änderung speichern (nur wenn vorhanden)
		$db->query("UPDATE ".$t." SET `date_modified` = NOW() WHERE `id` = $id");
		$db->query("UPDATE ".$t." SET `lastchange_user` = '".$_SESSION['myid']."' WHERE `id` = $id");
		
		// ok ausgeben
		print('ok');
	}
	else { print('no right');}
// **************************************************************************************************
	//Lib schliessen
	include(ENDLIB);
// **************************************************************************************************
?>