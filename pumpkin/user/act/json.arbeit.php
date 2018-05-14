<?php
// **************************************************************************************************
	//Lib laden
	$pmkCaller = '../../../';	//  Because its called from a subfolder
	include_once($pmkCaller.'config.php');
// **************************************************************************************************
	$stunden = array();
	$filter = (canEnterBinary('0.0.0.0.0.0.0.0.0.1'))?'WHERE rap_stunden.date >= \'2009-01-01\'':"WHERE rap_vertrag.uid = '".$_SESSION['myid']."' AND rap_stunden.date >= '2009-01-01'";
	$sql = "SELECT * FROM rap_vertrag 
				RIGHT JOIN rap_stunden 
				ON (rap_vertrag.ID = rap_stunden.vertrag) 
				LEFT JOIN rap_arbeit 
				ON (rap_stunden.arbeit=rap_arbeit.ID) 
				$filter 
				ORDER BY date DESC, von ASC";
	$db->query($sql);
	while($r = $db->results()) {
		$stunden[] = $r;
	}
	$json =    '\'wiki-url\':"http://simile.mit.edu/shelf/", 
				\'wiki-section\':"Simile JFK Timeline", 
				';
	$events = '';
	foreach($stunden as $key=>$val) {
		$tmp = '';
		$datum = explode('-',$val['date']);
		$von = $val['date']."T".$val['von']."Z";
		$bis = $val['date']."T".$val['bis']."Z";
		//$tmp = 'new date('.$datum[0].','.((int)$datum[1]-1).','.(int)$datum[2]	.')';
		$tmp .= "'start':'".date('r',iso86012timestamp($von))."',";
		$tmp .= "'end':'".date('r',iso86012timestamp($bis))."',";
		$tmp .= "'description':'".$val['comment']."',";
		$tmp .= "'title':'".$val['title']."',";
		$tmp .= "'durationEvent':true";
		$events .= "{".$tmp."},\n";
	}
	$events = substr($events,0,strlen($events)-2);
	$json .= "'events': [\n$events]";
	$json = "{".$json."}";
	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json');
	print $json;
// **************************************************************************************************
	//Lib schliessen
	include(current(explode("httpdocs",dirname(__FILE__))).'httpdocs/bin/lib/lib.end.php');
// **************************************************************************************************

?>