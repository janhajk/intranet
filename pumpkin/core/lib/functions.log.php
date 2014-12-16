<?php

// Erstellt einen Logeintrag mit der Action-ID; default ist 1
function addlog($v, $a=1, $c='') {
	if($_SESSION['myid']==1)return; // Die Aktivitäten des Superadministrator werden nicht aufgezeichnet
	$db = $GLOBALS['db'];
	//$_SESSION['myid'] = 1;
	$sql = "INSERT INTO `tbllog` (`user`, `action`, `timestamp`, `value`, `content`) VALUES (".$_SESSION['myid'].", $a, NOW(), '$v', '$c')";
    $db->query($sql);
	return $sql;
}

function show_last_log($limit=5,$type=1) {
	$db = $GLOBALS['db'];
	$sql = "SELECT  astra_log.value, 
					astra_log.timestamp, 
					astra_log_actions.a, 
					astra_users.vorname, 
					astra_users.nachname, 
					astra_users.kanton, 
					astra_log.userid, 
					astra_log.action 
			FROM `astra_log` 
			LEFT JOIN astra_log_actions 
			ON (astra_log.action = astra_log_actions.id) 
			LEFT JOIN astra_users 
			ON (astra_users.id = astra_log.userid) ORDER BY `timestamp` DESC LIMIT 0, $limit;";
	$db->query($sql);
	?><ul>Letzte Aktionen<?
	while($entry = $db->results()) {
		if ($type==1) { ?>
			<li style="font-size:8px;"><?=$entry['vorname'];?>&nbsp;<?=$entry['nachname'];?>:&nbsp;<?=$entry['a'];?></li><?
		}
		if ($type==2) { ?>
			<li style="font-size:8px;"><?=$entry['timestamp'];?>:&nbsp;<?=$entry['vorname'];?>&nbsp;<?=$entry['nachname'];?>:&nbsp;<?=$entry['a'];?></li><?
		}			
	}
	?></ul><?
}

// erstellt ein div, welches anzeigt, wer gerade Online ist
function show_ajax_log_stream() {
	// Minuten, nach welchen ein Benutzer als Offline gilt
	$i = 5;
	// Eintritt nur mit Autorenrechte
	if ($_SESSION['rights'] < 10) {return;}
	echo "<div id=\"log_stream\" style=\"padding:5px;text-align:left;margin-left:250px;\"></div>";
	echo "</div>";
	// Ajax objekt erzeugen + Abfrage im Interval
	echo"<script language=\"javascript\">
		<!--
		function show_ajax_log_stream() {
			if(!performing_ajax) {
				performing_ajax = true;
				 if (xmlHttp) {
					 xmlHttp.open('GET', 'act/list.whoisonline.php?spez=log_only', true);
					 xmlHttp.onreadystatechange = function () {
						 if (xmlHttp.readyState == 4) {
						 document.getElementById('log_stream').innerHTML = xmlHttp.responseText;
						 performing_ajax = false;
						 }
					 };
					 xmlHttp.send(null);
				 }
			}
		}
		setInterval('show_ajax_log_stream()', 4000);
		-->
		</script>";
}
?>