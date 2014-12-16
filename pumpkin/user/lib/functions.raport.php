<?

// Gibt alle Arbeiten aus
function getArbeiten() {
	$ans = '';
	$db = $GLOBALS['db'];
	$db->query("SET NAMES 'utf8';");  // Damit die Umlaute richtig dargestellt werden
	$sql = 'SELECT * FROM `rap_arbeit`';
	$db->query($sql);
	while ($res = $db->results()) {
		$ans = $ans."<option value=\"".$res['ID']."\">".$res['name']."</option>";
	}
	return $ans;
}

// Gibt einen bestimmten Vertrag aus
function getVertragTitle($id) {
	$db = $GLOBALS['db'];
	$db->query("SET NAMES 'utf8';");  // Damit die Umlaute richtig dargestellt werden
	$sql = "SELECT title FROM `rap_vertrag` WHERE ID = $id LIMIT 1";
	$db->query($sql);
	$res = $db->results();
	return $res['title'];
}

// Gibt alle Verträge aus
function getVertrag() {
	$ans = '';
	$filter = (canEnterBinary('0.0.0.0.0.0.0.0.0.1'))?'':"WHERE uid = '".$_SESSION['myid']."'"; 
	$db = $GLOBALS['db'];
	$db->query("SET NAMES 'utf8';");  // Damit die Umlaute richtig dargestellt werden
	$sql = "SELECT * FROM `rap_vertrag` $filter ORDER BY ID DESC";
	$db->query($sql);
	while ($res = $db->results()) {
		$ans = $ans."<option value=\"".$res['ID']."\">".$res['title']."</option>";
	}
	return $ans;
}

// Gibt eine Anzahl ArbeitsStunden aus
function stunden_overview($limit) {
	$db = $GLOBALS['db'];
	$stunden = array();
	$db->query(stunden_sql(0,$limit));
	while($r = $db->results()) {
		$stunden[] = $r;
	}
	foreach($stunden as $h) {?>
	<tr><td><?=substr($h['date'],5,5);?></td><td><?=substr($h['von'],0,5);?></td><td><?=substr($h['bis'],0,5);?></td><td><a href="?p=arbeit.stunden.edit&id=<?=$h['id'];?>"><?=htmlspecialchars(substr($h['comment'],0,23));?></a></td></tr>
	<? }
}

function stunden_editform($id) {
	// First checks if user is allowed to edit this entry
	if(!canUserEditStunde($id))return 'you have not enough rights to edit this entry!';
	$db = $GLOBALS['db'];
	$stunde = array();
	$db->query(stunden_sql($id,1));
	while($r = $db->results()) {?>
		<form action="<?=SITE_HTML;?>/actions.php" method="post">
			<select name="vertrag"><option value="<?=$r['vertrag'];?>"><?=$r['title'];?></option><?=getVertrag(); ?></select><br>
			<select name="datum"><option><?=date_mysql2german($r['date']);?></option><? getRecentDates(7); ?></select><br>
			<select name="von"><option><?=substr($r['von'],0,5);?></option><? getTimelist(7); ?></select><br>
			<select name="bis"><option><?=substr($r['bis'],0,5);?></option><? getTimelist(8); ?></select><br>
			<select name="arbeit"><option value="<?=$r['arbeit'];?>"><?=$r['name'];?></option><?=getArbeiten(); ?></select><br>
			<input type="text" name="comment" value="<?=$r['comment'];?>" /><br>
			<input type="submit" value="save" />
			<input type="hidden" name="a" value="editArbeitStunden" />
			<input type="hidden" name="id" value="<?=$id;?>" />
		</form>
	<?
	}	
}

/*
 * Checks, wether a user can edit an entry of the type "stunde"
 * return true if he can, false if he is not allowed
 * admin can edit everything
 */
function canUserEditStunde($id) {
	$db = $GLOBALS['db'];
	$db->query(stunden_sql($id,1));
	while($r = $db->results()) {
		$uid = $r['uid'];
	}
	return (($_SESSION['myid']==$uid || canEnterBinary('0.0.0.0.0.0.0.0.0.1'))?true:false);
}

/*
 * Gibt den SQL-String für die Ausgabe der Stunde(n) incl. Details
 * mit $id kann eine bestimmte Stunde ausgegeben werden
 */
function stunden_sql($id=0, $limit=0, $user=0) {
	$user = ($user)?$user:$_SESSION['myid'];
	$filter = (!canEnterBinary('0.0.0.0.0.0.0.0.0.1') || $user)?"WHERE rap_vertrag.uid = '".$user."'":'';
	$where = ($id==0)?'':(($filter=='')?' WHERE':' AND').' rap_stunden.ID = \''.$id.'\'';
	$limit = ($limit==0)?'':'LIMIT '.$limit;
	$sql = "SELECT *, rap_stunden.ID as id FROM rap_vertrag 
				RIGHT JOIN rap_stunden 
				ON (rap_vertrag.ID = rap_stunden.vertrag) 
				LEFT JOIN rap_arbeit 
				ON (rap_stunden.arbeit=rap_arbeit.ID) 
				$filter 
				$where 
				ORDER BY rap_stunden.date DESC, rap_stunden.von ASC 
				$limit";
	return $sql;
}


/*
 * XLS Export Stundenrapport
 * exportiert den Stundenrapport aller Stunden von uid nach Exel
 */
function xlsExportStunden($vertrag=0) {
	$db = $GLOBALS['db'];
	$stunden = array();
	$filter1 = (canEnterBinary('0.0.0.0.0.0.0.0.0.1'))?'':"AND rap_vertrag.uid = '".$_SESSION['myid']."'";
	$filter2 = (!$vertrag)?'':"AND rap_vertrag.ID = '".$vertrag."'";
	$sql = "SELECT * FROM rap_vertrag 
				RIGHT JOIN rap_stunden 
				ON (rap_vertrag.ID = rap_stunden.vertrag) 
				LEFT JOIN rap_arbeit 
				ON (rap_stunden.arbeit=rap_arbeit.ID) 
				WHERE date >= '2008-01-01' 
				$filter1 $filter2 
				ORDER BY date ASC, von ASC";
	$db->query($sql);
	while($r = $db->results()) {
		$stunden[] = $r;
	}
	$xls = new Cxls('Stundenexport', $stunden);
	$xls->setHeaderRow(array('arbeitstitel'=>'Vertrag', 
							 'title'=>'Titel',  
							 'firma'=>'Firma', 
							 'date'=>'Datum', 
							 'stunden'=>'Stunden', 
							 'name'=>'Kategorie',
							 'comment'=>'Arbeit', 
							 'von'=>'Von', 
							 'bis'=>'Bis', 
							 'kostendach'=>'Kostendach'));
	$xls->output();
	return 'xls erfolgreich exportiert!';
}

// gibt die letzten $days Tage aus
function getRecentDates($days) {
	$now = time();
	for($i=$now;$i>=$now-$days*86400;$i=$i-86400) {
		echo "<option>".date("d.m.Y", $i)."</option>";
	}
}

function getTimelist($start) {
	$start = mktime($start,0,0,1,1,2000);
	for($i=$start;$i<=$start+60*60*15;$i=$i+60*60/2) {
		echo "<option>".date("H:i",$i)."</option>";
	}
}

/*
 * Gets a Month overview of the working hours of specific user
 */
function getMonthlyOverviewChart($year, $user) {
	if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
		$db = $GLOBALS['db'];
		$db->query(stunden_sql(0,0,$user));
		$data = array();
		while($r = $db->results()) {
			$data[substr($r['date'],0,7)][] = $r['stunden']*$r['nettoansatz'];
		}
		$hours = array();
		foreach($data as $k=>$d) {
			$hours[$k] = array_sum($d);
		}
		// Takes only this years months
		foreach($hours as $k=>$d) {
			if(substr($k,0,4)!=$year) unset($hours[$k]);
		}
		ksort($hours);
		// Months labels
		$months = '';
		$dataPoints = '&chm=';
		$i = 0;
		foreach($hours as $k=>$d) {
			$months .= '|'.date("M",mysql2timestamp($k.'-01'));
			$dataPoints .= 't'.pmkCurrency($d).',000000,0,'.$i.',11|';
			$i++;
		}
		$hours['9'] = 0;	// To make some extra space on the right
		$dataPoints = substr($dataPoints,0,strlen($dataPoints)-1);
		$barChart = new gGroupedBarChart;
		$barChart->width = 1000;
		$barChart->height = 200;
		$barChart->addDataSet($hours);
		$barChart->valueLabels = array("first");
		$barChart->dataColors = array("ff3344");
		$url = $barChart->getUrl().'&chxt=x,y&chxl=0:'.$months.'|1:|0|'.max($hours).$dataPoints;
		return '<a href="'.$url.'"><img width="100%" src="'.$url.'" /></a>';
	}
}
?>