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




function IsEMail($e) {
	$muster="^[_a-zA-Z0-9-](.{0,1}[_a-zA-Z0-9-])*@([a-zA-Z0-9-]{2,}.){0,}[a-zA-Z0-9-]{3,}(.[a-zA-Z]{2,4}){1,2}$";
	/* if(ereg($muster, $e))  {
		return TRUE;
	}
	return FALSE; */
	return TRUE;
}
?>