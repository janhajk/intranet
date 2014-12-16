<?
// **************************************************************************************************
	//Lib laden
	include(current(split("httpdocs",dirname(__FILE__))).'httpdocs/bin/lib/lib.php');
// **************************************************************************************************
	header('Content-Type: text/html; charset=iso-8859-1');
// **************************************************************************************************
	$state = (int)$_GET['state'];
	$id = (int)$_GET['id'];
	$state = ($state>=2)?0:$state+1;
	$sql = 'UPDATE rap_bills SET status = \''.$state.'\' WHERE id = '.$id;
	//echo $sql;
	$db->query($sql);
	echo $state;
// **************************************************************************************************
	//Lib schliessen
	include(current(split("httpdocs",dirname(__FILE__))).'httpdocs/bin/lib/lib.end.php');
// **************************************************************************************************
?>