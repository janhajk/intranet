<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.total' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
    <div class="container" style="max-width:700px !important;">
        <ul class="nav nav-pills" role="tablist">
            <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="?p=2">Business</a></li>
            <li><a href="?p=arbeit.rechnungen">Invoices</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Time Reports <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?=SITE_HTML;?>/actions.php?a=xlsstunden&vertrag=0">xls</a></li>
                    <li><a href="?p=arbeit.timeline&timeline=1">Timeline</a></li>
                    <li><a href="?p=arbeit.graphs">Graphs</a></li>
                </ul>
            </li>
            <li class="active"><a href="?p=arbeit.total">Contracts</a></li>
            <li><a href="?p=arbeit.admin"><span class="glyphicon glyphicon-wrench"></span></a></li>
        </ul>
        <h2>Totals</h2>
        <div>
            <h3>per contract</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Contract</th>
                        <th>total hours</th>
                        <th>CHF/h</th>
                        <th>Sum</th>
                    </tr>
                </thead>
                <? getTotalVertrag(); ?>
            </table>
        </div>
        <div>
            <h3>Annual</h3>
            <table class="table">
                <? for ($i=date('Y');$i>2007;$i--){getTotalJahr($i);} ?>
            </table>
        </div>
    </div>
<? } ?>
<?
function getTotalVertrag() {
	$db = $GLOBALS['db'];
	$db->query("SET NAMES 'utf8';");  // Damit die Umlaute richtig dargestellt werden
	$sql = "SELECT rap_vertrag.title, rap_vertrag.nettoansatz, rap_vertrag.nettoansatz*SUM(rap_stunden.stunden) as c, SUM(rap_stunden.stunden) as t, rap_vertrag.ID FROM rap_stunden LEFT JOIN rap_vertrag ON (rap_stunden.vertrag=rap_vertrag.ID) GROUP BY rap_stunden.vertrag";
	$db->query($sql);
	while($i = $db->results()) { if($i['title']!='')$totals[] = $i; }
	foreach($totals as $t) {
		echo "<tr><td><a href=\"".SITE_HTML."/actions.php?a=xlsstunden&vertrag=".$t['ID']."\">".$t['title']."</a></td><td>".round($t['t'])."h</td><td>".round($t['nettoansatz']).".-</td><td align=\"right\">".number_format(round($t['c']),0,'.','`').".-</td></tr>";
	}
}

function getTotalJahr($year) {
	$total = 0;
	$db = $GLOBALS['db'];
	$sql = "SELECT rap_vertrag.title, rap_vertrag.nettoansatz*SUM(rap_stunden.stunden) as c, SUM(rap_stunden.stunden) as t FROM rap_stunden LEFT JOIN rap_vertrag ON (rap_stunden.vertrag=rap_vertrag.ID) WHERE rap_stunden.date >= '$year-01-01' AND rap_stunden.date < '".($year+1)."-01-01' GROUP BY rap_stunden.vertrag";
	$db->query($sql);
	while($i = $db->results()) { if($i['title']!='')$totals[] = $i; }
	foreach($totals as $t) {
		$total = $total + round($t['c']);
	}
	echo "<tr><td>$year</td><td>".number_format(round($total),0,'.','`').".-</td></tr>";
}
?>