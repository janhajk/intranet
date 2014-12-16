<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.total' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
    <?=link_back_head($pages['Arbeit']);?>
    <div class="box">
        <div class="title">Total Verträge</div>
        <div>
            <div class="header2">nach Vertrag</div>
            <table><? getTotalVertrag(); ?></table>
        </div>
        <div>
            <div class="header2">Total Jahr</div>
            <table>
				<? getTotalJahr(2007); ?>
            	<? getTotalJahr(2008); ?>
				<? getTotalJahr(2009); ?>
            </table>
        </div>
    </div>
    <?=link_back_foot($pages['Arbeit']);?>
<? } ?>
<?
function getTotalVertrag() {
	$db = $GLOBALS['db'];
	$db->query("SET NAMES 'utf8';");  // Damit die Umlaute richtig dargestellt werden
	$sql = "SELECT rap_vertrag.title, rap_vertrag.nettoansatz, rap_vertrag.nettoansatz*SUM(rap_stunden.stunden) as c, SUM(rap_stunden.stunden) as t, rap_vertrag.ID FROM rap_stunden LEFT JOIN rap_vertrag ON (rap_stunden.vertrag=rap_vertrag.ID) GROUP BY rap_stunden.vertrag";
	$db->query($sql);
	while($i = $db->results()) { if($i['title']!='')$totals[] = $i; }
	foreach($totals as $t) {
		echo "<tr><td><a href=\"".SITE_HTML."/actions.php?a=xlsstunden&vertrag=".$t['ID']."\">".$t['title']."</a></td><td>".round($t['t'])."h</td><td>".round($t['nettoansatz']).".-</td><td>".number_format(round($t['c']),0,'.','`').".-</td></tr>";
	}
}

function getTotalJahr($year) {
	$total = 0;
	$db = $GLOBALS['db'];
	$db->query("SET NAMES 'utf8';");  // Damit die Umlaute richtig dargestellt werden
	$sql = "SELECT rap_vertrag.title, rap_vertrag.nettoansatz*SUM(rap_stunden.stunden) as c, SUM(rap_stunden.stunden) as t FROM rap_stunden LEFT JOIN rap_vertrag ON (rap_stunden.vertrag=rap_vertrag.ID) WHERE rap_stunden.date >= '$year-01-01' AND rap_stunden.date < '".($year+1)."-01-01' GROUP BY rap_stunden.vertrag";
	$db->query($sql);
	while($i = $db->results()) { if($i['title']!='')$totals[] = $i; }
	foreach($totals as $t) {
		$total = $total + round($t['c']);
	}
	echo "<tr><td>$year</td><td>".number_format(round($total),0,'.','`').".-</td></tr>";
}
?>