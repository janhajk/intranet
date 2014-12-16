<?
// **************************************************************************************************
	//Lib laden
	include($_SERVER['DOCUMENT_ROOT'].'/config.php');
// **************************************************************************************************
	header('Content-Type: text/json');
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
	$r = array();
	//$db->assoc = MYSQL_NUM; // Liste ohne die Spaltentitel
	$sql = '';
	switch($_GET['kind']) {
		case 'v_overview':
			$sql  = "SELECT `id`, 
							`tbva`, 
							`v`, 
							`d`, 
							`kreditor`, 
							`auftrag`, 
							`comment1` 		    
					 FROM `tblEVL` 
					 WHERE `sr` = '0000-00-00' AND `d` != '0000-00-00' AND `v` < 900000 
					 GROUP BY `v`  
					 ORDER BY `v`";
			break;	
		case 'pmklist1':
			$sql  = "SELECT `tblbudget`.`id`, 
							`tblEVL`.`tbva`, 
							`tblEVL`.`v`, 
							`tblEVL`.`d`, 
							`tblEVL`.`kreditor`, 
							`tblEVL`.`auftrag`, 
							`tblbudget`.`b`, 
							`tblbudget`.`y`, 
							`tblbudget`.`comment1` 
					 FROM `tblEVL` 
					 LEFT JOIN `tblbudget` 
					 ON (`tblEVL`.`v`=`tblbudget`.`v`) 
					 WHERE `tblEVL`.`sr` = '0000-00-00' AND `tblEVL`.`d` != '0000-00-00' AND `tblEVL`.`v` < 900000 AND `tblbudget`.`y`=2008  
					 GROUP BY `tblEVL`.`v`  
					 ORDER BY `tblEVL`.`v` ASC";					 
			break;
		case 'pmklist3':
			$sql  = "SELECT `tblbudget`.`id`, 
							`tblEVL`.`tbva`, 
							`tblEVL`.`v`, 
							`tblEVL`.`d`, 
							`tblEVL`.`kreditor`, 
							`tblEVL`.`auftrag`, 
							`tblbudget`.`b`, 
							`tblbudget`.`y`, 
							`tblbudget`.`comment1` 
					 FROM `tblEVL` 
					 LEFT JOIN `tblbudget` 
					 ON (`tblEVL`.`v`=`tblbudget`.`v`) 
					 WHERE `tblEVL`.`sr` = '0000-00-00' AND `tblEVL`.`d` != '0000-00-00' AND `tblEVL`.`v` < 900000 AND `tblbudget`.`y`=2009 
					 GROUP BY `tblEVL`.`v`  
					 ORDER BY `tblEVL`.`v` ASC";					 
			break;
		case 'v_u1':
			$sql  = "SELECT `tblbudget`.`id`,
							`tblvertrag_overview`.`refnr`,
							`tblvertrag_overview`.`kreditor`,
							`tblvertrag_overview`.`auftrag`,
							`tblbudget`.`b`,
							`tblbudget`.`v`,
							`tblbudget`.`u1d`,
							`tblbudget`.`u11`,
							`tblbudget`.`u12`,
							`tblbudget`.`u13`,
							`tblbudget`.`u14`,
							`tblbudget`.`u15`,
							`tblbudget`.`u16`,
							`tblvertrag_overview`.`v_summe`    
					 FROM `tblvertrag_overview` 
					 LEFT JOIN `tblbudget` 
					 ON (`tblvertrag_overview`.`refnr`=`tblbudget`.`v`)
					 WHERE `sr_date` != '0000-00-00' 
					 ORDER BY `refnr`";
			break;
		case 'prov08':
			$sql = "SELECT id,tbva,kreditor,auftrag,y,b,comment1 
					  FROM tblprovisorisch 
					  WHERE `y` = 2008";
			break;
		case 'prov09':
			$sql = "SELECT id,tbva,kreditor,auftrag,y,b,comment1 
					  FROM tblprovisorisch 
					  WHERE `y` = 2009";
			break;
		case 'u1':
			
			$sql  = "SELECT id,y,v,b,u1d,u11,u12,u13,u14    
					 FROM `tblbudget` 
					 WHERE `y` = '2009' 
					 ORDER BY `v`";
			break;
		case 'b09':
			$sql  = "SELECT id,y,v,b    
					 FROM `tblbudget` 
					 WHERE `y` = '2009' 
					 ORDER BY `v`";
			break;
		case 'tblusers':
			$sql  = "SELECT *    
					 FROM `tblusers` 
					 WHERE `rights` < ".$_SESSION['rights']." || `id` = '".$_SESSION['myid']."'    
					 ORDER BY `vorname`";
			break;
		case 'neusteEVLListe':
			$sql = "SELECT list_d FROM tblEVL GROUP BY list_d ORDER BY list_d desc LIMIT 3";
			break;
		default: 
			$sql  = "SELECT *    
					 FROM `".$_GET['kind']."`";
			break;		
	}
	$data = split("_",$_GET['kind']);
	if(count($data)==3) {
		switch($data[0]) {
			case 'UW':
				$d = $data[2];
				// neuste EVL-Liste
				$db->query("SELECT list_d FROM tblEVL ORDER BY list_d desc LIMIT 1");$neusteListe = current($db->results());
				// Query
				$sql  = "SELECT `tblbudget`.`id`,  
								`tblbudget`.`v`, 
								`tblbudget`.`b`, 
								`tblbudget`.`u".$d."1`, 
								`tblbudget`.`u".$d."2`, 
								`tblbudget`.`u".$d."3`, 
								`tblEVL`.`verrechnet`, 
								`tblEVL`.`tbva`, 
							    `tblEVL`.`kreditor` 
						 FROM (SELECT v, SUM(r_betrag) as verrechnet,kreditor,tbva FROM tblEVL WHERE `list_d` = '$neusteListe' AND `sr` = '0000-00-00' AND `v` <900000 GROUP BY v) AS tblEVL 
						 LEFT JOIN (SELECT id,v,b,u".$d."1,u".$d."2,u".$d."3 FROM tblbudget WHERE y='".$data[1]."') AS tblbudget   
						 ON (`tblEVL`.`v`=`tblbudget`.`v`) 
						 ORDER BY `tblbudget`.`v` ASC";
				break;
		}
	}
	$db->query("SET NAMES 'utf8';");  // Damit die Umlaute richtig dargestellt werden
	$db->query($sql);
	while ($entry = $db->results()) {
		$r[] = (is_string($entry))?htmlentities($entry):$entry;
	}
	$output = json_encode($r);
	addlog($_GET['kind'],1);
	print($output);
// **************************************************************************************************
	//Lib schliessen
	include(ENDLIB);
// **************************************************************************************************
?>