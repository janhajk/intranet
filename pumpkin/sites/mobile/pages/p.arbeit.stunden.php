<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.stunden' && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
	if(isset($_GET['l'])) {$limit = (int) $_GET['l'];}
	?>
    <div class="container" style="max-width:700px !important;">
        <a href="index.php?p="<?php echo $pages['Arbeit'][0]; ?> role="button" class="btn btn-default btn-sm btn-block">back</a>
        <h2>Time Sheet</h2>
        <a href="<?=SITE_HTML;?>/actions.php?a=xlsstunden">download</a>
        <div>
            <h3>Last <?php echo $limit; ?></h3>
            <table><? stunden_overview($limit); ?></table>
        </div>
        <a href="<?=str_replace("l=10", "l=30", $_SERVER["REQUEST_URI"]);?>">more</a>
    </div>
<? } ?>