<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.stunden' && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
	if(isset($_GET['l'])) {$limit = $_GET['l'];}
	?>
    <?=link_back_head($pages['Arbeit'][0]);?>
    <div class="box">
<a href="<?=str_replace("l=10", "l=30", $_SERVER["REQUEST_URI"]);?>">more</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=SITE_HTML;?>/actions.php?a=xlsstunden">download</a>
        <div class="title">Stunden</div>
        <div>
            <div class="header2">Letzte 10</div>
            <table><? stunden_overview($limit); ?></table>
        </div>
    </div>
    <?=link_back_foot($pages['Arbeit'][0]);?>
<? } ?>