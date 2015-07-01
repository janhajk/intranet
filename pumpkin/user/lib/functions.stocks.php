<?
function stocks_totalFonds($date=false) {
	$k = array();
	$a = array();
	$db = $GLOBALS['db'];
	$sql = "SELECT * FROM stocks_kurse WHERE pid = 1 ORDER BY date DESC LIMIT 1";
	$db->query($sql);
	$kurs = $db->results();
	$k[] = $kurs['kurs'];
	$sql = "SELECT stocks_kurse.*, stocks_besitz.amount FROM stocks_kurse LEFT JOIN stocks_besitz on (stocks_kurse.pid = stocks_besitz.pid) WHERE stocks_kurse.pid = %pid% AND stocks_besitz.id = (SELECT id FROM stocks_besitz WHERE pid = %pid% ORDER BY date DESC LIMIT 0,1) ORDER BY stocks_kurse.date DESC LIMIT 1";
  for ($i=2;$i<5;$i++) {
    $db->query(str_replace('%pid%', $i, $sql));
    $kurs = $db->results();
    $k[] = $kurs['kurs'];$a[] = $kurs['amount'];
  }
	return round($k[1]*$k[0]*$a[0] + $k[2]*$a[1] + $k[3]*$a[2]);
}

/**
 * Aktualisiert alle Kurse
 * 
 */
function stocks_update() {
	$kurse = array();
	$db = $GLOBALS['db'];
	$sql = "SELECT * FROM stocks_names";
	$db->query($sql);
	while($r = $db->results()) {
		$kurse[] = $r['id'];
	}
	foreach ($kurse as $k) {
		echo $k.": ".writeKurs($k)."<br>";
	}
}

/**
 * Holt den aktuellen Kurs von der Webseite und schreibt ihn in die Datenbank
 * ist bereits ein Wert vorhanden, wird dieser überschrieben
 * @param integer $id Kurs-ID
 * @return boolean|string
 */
function writeKurs($id) {
	$kurs = getKurs($id);
	if($kurs) {
		$db = $GLOBALS['db'];
		$sql = "SELECT id FROM stocks_kurse WHERE TO_DAYS(date) = TO_DAYS(NOW()) AND pid = '$id'";
		$db->query($sql);
		if($db->count>0) {
      $id = $db->results();
      $sql = "UPDATE stocks_kurse SET kurs = ".$kurs." WHERE id = ".$id['id'];
    }
    else {
      $sql = "INSERT INTO stocks_kurse (`pid`, `date`, `kurs`) VALUES ('$id', NOW(), '$kurs')";
    }
		$db->query($sql);
		return $kurs;
	}
	else {
		return 'kein Eintrag gefunden';
	}
}

/**
 * Holt einen Kurs von der Kurs-Wbeseite ab
 * @param integer $id Kurs-ID
 * @return integer Der aktuellste Kurs
 */
function getKurs($id) {
	// Stock aus db auswählen
	$db = $GLOBALS['db'];
	$sql = "SELECT * FROM `stocks_names` WHERE `id` = '$id' LIMIT 1";
	$db->query($sql);
	$stock = $db->results();
	// Url wo der Kurs hinterlegt ist
	$URL = $stock['url'];
	// Regular Expression Search String
	$search = $stock['search'];
	$day = 0;
	$kurs = false;
	while(!$kurs) {
		$kurs = searchKurs($search, $URL, $day);
		$day++;
		echo $day.': '.$kurs.'<br />';
		if($day>10)break;
	}
	return $kurs;
}

/**
 * Sucht in einem String nach dem Kurs-Wert 
 */
function searchKurs($search, $url, $day=0) {
	$dY=date("Y");
	$dm=date("m");
	$dd=date("d");	// Today
	$y=time()-60*60*24*$day;
	$dY=date("Y",$y);$dm=date("m",$y);$dd=date("d",$y);	// Tag, an welchem Daten genommen werden
	$search = str_replace("%Y", $dY, $search);
	$search = str_replace("%m", $dm, $search);
	$search = str_replace("%d", $dd, $search);
	echo $search;
	$content = file_get_contents($url);
    $content = str_replace('\'', '', $content); // Tausender Apostroph entfernen
	preg_match($search, $content, $treffer);
	if (count($treffer)>=2) {return $treffer[1];} else { return false; }
}

/*
 * Erstellt ein Diagramm mit dem Kurs pid
 *
 * @param integer $pid Die Kurs-Id
 * @return string Die Google Diagramm Url
 */
function diagramFonds($pid) {
    $db = $GLOBALS['db'];
    $sql = "SELECT date, kurs FROM `stocks_kurse` WHERE pid = '$pid' ORDER BY id DESC";
    $db->query($sql);
    $dayvalue = array();
    while($v = $db->results()) {
        $dayvalue[mktime(
            0, 0, 0,
            (int)substr($v['date'],5,2),
            (int)substr($v['date'],8,2),
            (int)substr($v['date'],0,4))*1000] = $v['kurs'];
    }
    $data = array();
    foreach ($dayvalue as $day=>$kurs) {
        $data[] = "[new Date(".$day."), ".$kurs."]";
    }
    return array(implode($data,','), min($dayvalue), max($dayvalue));
}

function getStocksCount($pid) {
    $pid = (int) $pid;
    $db = $GLOBALS['db'];
    $sql = 'SELECT * FROM stocks_besitz WHERE pid = '.$pid.' ORDER BY date DESC LIMIT 0,1';
    $db->query($sql);
    $count = $db->results();
    return $count['amount'];
}





























/**
 * Erstellt ein Diagramm mit dem Kurs pid
 * 
 * @param integer $pid Die Kurs-Id
 * @return string Die Google Diagramm Url
 */
/*
function stockChart($pid) {
	$punkte = 365;
	$db = $GLOBALS['db'];
    $sql = "SELECT date, kurs FROM `stocks_kurse` WHERE pid = '$pid' ORDER BY id DESC LIMIT 0,".$punkte;
	$db->query($sql);
	$dayvalue = array();
	while($v = $db->results()) {
		$jahr  = (int)substr($v['date'],0,4);
		$monat = (int)substr($v['date'],5,2);
		$tag   = (int)substr($v['date'],8,2);
		$day = mktime(0,0,0,$monat,$tag,$jahr);
		$dayvalue[$day] = $v['kurs'];
	}
	// Minimum und Maximum
	asort($dayvalue);
	$MIN_GRAPH = current($dayvalue);
	arsort($dayvalue);
	$MAX_GRAPH = current($dayvalue);
	if(isset($_GET['debug'])){echo 'min:'.$MIN_GRAPH.' max:'.$MAX_GRAPH.'<br />';}
	
	ksort($dayvalue);
	if(isset($_GET['debug'])){print_r($dayvalue).'<br />';}
	
	$datastring = '';
	$range = array($MIN_GRAPH,$MAX_GRAPH);
	foreach ($dayvalue as $v) {
	$datastring .= round(($v-$range[0])/($range[1]-$range[0])*100).',';  // Bsp: (38000-28000)/(38000-28000)*100
	}
	$datastring = substr($datastring,0,-1); // Letztes Kommata abtrennen
	$height = 250;
	$width  = 800;	
	$chart = '';
	$chart .= 'http://chart.apis.google.com/chart?';  // Adresse
	$chart .= 'cht=lc&';  // Type
	$chart .= 'chs='.$width.'x'.$height.'&';  // Grösse (w x h)
	$chart .= 'chd=t:'.$datastring.'&';  // Daten
	$chart .= 'chco=0000FF&';  // Farben
	$chart .= 'chls=1,1,0&';  // Linienart (dicke, länge, gap)
	//$chart .= 'chxt=x,y&'; // Achsen
	$chart .= 'chg='.(100/30).',20.0&';  // Grid - x,y axis step size
	
	return $chart;
}
*/
?>
