<?

function getRandomScripture($book=0) {
	$s = current(getScripture());
	return '<span style="font-weight:bold">'.$s['title'].'</span><br />'.$s['text'];
}

function getStudyScripture($uid) {
	$GLOBALS['db']->query("SELECT * FROM lds_study WHERE uid = $uid LIMIT 0,1");
	if($GLOBALS['db']->count==0) {$GLOBALS['db']->query("INSERT INTO lds_study (sid, uid) VALUES ('31103','$uid')");$GLOBALS['db']->query("SELECT * FROM lds_study WHERE uid = $uid LIMIT 0,1");}
	$r = $GLOBALS['db']->results();
	$s = current(getScripture($r['sid']));
	return '<span style="font-weight:bold">'.$s['title'].'</span><br />'.$s['text'];
}

/*
 * Gibt eine Schriftstelle
 * direkte Suche, oder zuf�llige
 */
function getScripture($id=false,$LIMIT=1) {
	$v = "lds_scriptures_verses_de";
	$b = "lds_scriptures_books_de";
	$WHERE = (!$id)?' ORDER BY RAND() ':" WHERE $v.verse_id = $id ";
	$sql = "SELECT * FROM $v LEFT JOIN $b ON ($v.book_id = $b.book_id) $WHERE LIMIT 0,$LIMIT;";
  $return = array();
  mysql_connect('localhost','bom','Xh+f7');
  @mysql_select_db('bom') or die("Unable to select database");
  $result = mysql_query($sql);
  while ($r = mysql_fetch_assoc($result)) {
    $return[] = array('id'=>$r['verse_id'],
            'book'	=>$r['book_title'], 
            'chp'	=>$r['chapter'], 
            'verse'=>$r['verse'], 
            'text'	=>$r['verse_scripture'], 
            'title'=>$r['book_title'].' '.$r['chapter'].':'.$r['verse']);
	}
  mysql_close();
	return $return;
}

function SSJ_checkAnswer() {
	if(isset($_POST['postanswer'])) {
		if($_POST['postanswer']==$_SESSION['ssj_correct']) {
			echo 'correct!'.'<br />';
		}
		else {
			echo 'falsch!<br />Korrekte Antwort w&auml;re: '.$_SESSION['correctScripture'][title].'<br />';
		}	
	}
}

function getSSJScripture($count=3) {
	// Get $count scriptures randomly
	$choice = getScripture(false,$count);
	// the correct scripture is automaticly the first one
	$_SESSION['correctScripture'] = $choice[0];

	// Gets the order, in which the scriptures apear
	$order = array();
	for($i=0;$i<$count;$i++) { $order[] = $i; }
	(fisherYatesShuffle($order)); // zB: array(0=>3,1=>2,2=>4,3=>1,4=>5);

	// The Correct scripture-position-key is saved (correct one is key=>"1")
	$_SESSION['ssj_correct'] = array_search('0', $order);	// => 3
	
	// Prints the correct scripture
	echo '<br />'.$_SESSION['correctScripture']['text'].'<br /><br />';
	$i = 1;
	// Prints the possible scripture
	foreach($order as $k=>$o) {
		echo '<form action="" method="post">';	
			echo '<input type="hidden" name="postanswer" value="'.$k.'" />';
			echo '<input type="submit" value="'.($k+1).'" />&nbsp;&nbsp;'.$choice[$o]['title'].'<br />';
		echo '</form>';
	}
}

function Ranking($points) {
	$db = $GLOBALS['db'];
	$sql = "SELECT `title` FROM `ssj_ranks` WHERE points <= $points ORDER BY points DESC LIMIT 0,1";
	$db->query($sql);
	$r = $db->results();
	return 'Ranking: '.$r['title'].'<br />';
}


/*
 * Function, that reads a chapter from LDS.org from the German book of mormon into the SQL Database
 */
function readBook($lds_org, $book_id, $chp, $volume_id=3) {
	$db = $GLOBALS['db'];
	$url = 'http://scriptures.lds.org/de/'.$lds_org.'/'.$_SESSION['chapter'];
	$go = true;
	$s = 1;
	$content = file_get_contents($url);	// Raw Kapitel
	while($go) {		// Vers f�r Vers durchgehen
		$res = preg_match('/&nbsp;&nbsp;'.$s.'(.*?)<\/div>/is',$content,$verse);	// The Verse
		if(!$res) { break; }	// Ende des Kapitels, n�chstes
		$verse = $verse[1];
		$verse = preg_replace('/<sup>\w<\/sup>/is','',$verse);  // alle tiefgestellten Kapit�lchen
		$verse = preg_replace('/<[^>]*>/is','',$verse);			// alle HTML Tags
		$sql = "INSERT INTO lds_scriptures_verses_de (volume_id, book_id, chapter, verse, verse_scripture) VALUES ('$volume_id','$book_id','".$_SESSION['chapter']."','$s','$verse')";
		$db->query($sql);
		echo 'Kp. '.$_SESSION['chapter'].', Vers '.$s.': '.$verse.'<br>';
		$s++;
		
	}
	if($_SESSION['chapter']<$chp)  {
		$_SESSION['chapter']++;
		?>
		<script type="text/javascript">
		<!--
		setTimeout("self.location.href='?p=readbook'",500);
		//-->
		</script>
		<?
	}
	else {
		unset($_SESSION['chapter']);
	}
}

function incectionSpace($scripture) {
	$scripture = str_replace(" ",'<span style="fonz-size=1pt;">&nbsp;</span>&nbsp;',$scripture);
	return $scripture;
}

function ssj_login($user) {
	if(!GetUserId($user['profile']['identifier'])) {
		AttachOpenID($user['profile']['identifier']);
	}
}
?>