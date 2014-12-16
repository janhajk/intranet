<?
function getUserArrayID() {
	$db = $GLOBALS['db'];
	$a = array();
	$sql = "SELECT * FROM `".$GLOBALS['tbl_users']."`";
	$db->query($sql);
	while ($e = $db->results()) {
		foreach($e as $t=>$c) {
			$a[$e['ID']][$t] = $c;
		}
	}
	return $a;
}

// Gibt alle Daten eines Benutzer in einem Array aus;  (Benutzer = $ID)
function getME_DATA($ID) {
	$a = array(); $a = array();
	$db = $GLOBALS['db'];
	$sql = "SELECT * FROM `pmkUsers` WHERE `ID` = $ID LIMIT 1";
	$db->query($sql);
	$e = $db->results();
	foreach($e as $t=>$c) {
		$a[$t] = $c;
	}
	return $a;
}
// Gibt alle Daten eines Benutzer in einem Array aus;  (Benutzer = $email)
function getUserArrayfromEmail($email) {
	$a = array();
	$db = $GLOBALS['db'];
	$sql = "SELECT * FROM `pmkUsers` WHERE `email` LIKE '".$email."' LIMIT 1";
	$db->query($sql);
	return($db->results());
}

// Anzahl User
function getUserCount() {
	$db = $GLOBALS['db'];
	$db->query('SELECT * FROM `pmkUsers`');
	return $db->count;
}


function getUsernameFromID($id) {
	$db = $GLOBALS['db'];
	$db->query('SELECT `vorname`, `nachname` FROM `pmkUsers` WHERE `id` ='.$id);
	$res = $db->results();
	return $res['vorname'].'&nbsp;'.$res['nachname'];
}


// Stempelt last_action
function user_timestamp() {
	$db = $GLOBALS['db'];
	$db->query("UPDATE `pmkUsers` SET `last_action` = NOW() WHERE `id` = ".$_SESSION['myid']);
}


// erstellt ein div, welches anzeigt, wer gerade Online ist
function show_whos_online_div() {
	// Minuten, nach welchen ein Benutzer als Offline gilt
	$i = 5;
	// Eintritt nur mit Autorenrechte
	if ($_SESSION['rights'] < 10) {return;}
	echo "<div style=\"position:absolute;top:20px;left:15px;background:#C66;color:#000;font-size:8pt;font-family:'verdana';font-weight:lighter;\">";
	echo "<div style=\"background:#930;color:#FFF;margin:4px;padding:2px;\">Angemeldete Benutzer</div>";
	echo "<div id=\"whoisonline\" style=\"padding:5px;text-align:left;\"></div>";
	echo "</div>";
	// Ajax objekt erzeugen + Abfrage im Interval
	echo"<script language=\"javascript\">
		<!--
		function whoisonline() {
			if(!performing_ajax) {
				performing_ajax = true;
				 if (xmlHttp) {
					 xmlHttp.open('GET', 'act/list.whoisonline.php?spez=log', true);
					 xmlHttp.onreadystatechange = function () {
						 if (xmlHttp.readyState == 4) {
						 document.getElementById('whoisonline').innerHTML = xmlHttp.responseText;
						 performing_ajax = false;
						 }
					 };
					 xmlHttp.send(null);
				 }
			}
		}
		setInterval('whoisonline()', 2500);
		-->
		</script>";
}


function IsEMail($e) {
	$muster="^[_a-zA-Z0-9-](.{0,1}[_a-zA-Z0-9-])*@([a-zA-Z0-9-]{2,}.){0,}[a-zA-Z0-9-]{3,}(.[a-zA-Z]{2,4}){1,2}$";
	/* if(ereg($muster, $e))  {
		return TRUE;
	}
	return FALSE; */
	return TRUE;
}
?>