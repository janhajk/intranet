<?
// **************************************************************************************************
	//Lib laden
	include($_SERVER['DOCUMENT_ROOT'].'/config.php');
// **************************************************************************************************
	header('Content-Type: text/html');
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
	
	// Tabelle, in welcher ein Datensatz gelöscht werden soll
	$t = mysql_escape_string($_GET['table']);
	// Die id des Eintrages
	$id = mysql_escape_string($_GET['id']);
	
	// Datensatz löschen
	$sql = "DELETE FROM `$t` WHERE `id` = $id";
	if(!$db->query($sql)){echo $db->errornumber.':'.$db->errormessage.'<br />';}

	// ok ausgeben
	print('ok');
// **************************************************************************************************
	//Lib schliessen
	endlib();
// **************************************************************************************************
?>