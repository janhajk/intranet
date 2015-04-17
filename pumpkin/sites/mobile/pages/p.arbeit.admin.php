<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.admin' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
    <div class="container" style="max-width:700px !important;">
        <?php echo $nav_arbeit; ?>
        <h3>edit</h3>
        <div>
            <h3>neuer Vertrag</h3>
            	<form action="<?=SITE_HTML;?>/actions.php" method="post">
                    <input type="text" name="arbeitstitel" value="Arbeitstitel" /><br />
                    <input type="text" name="nettoansatz" value="Ansatz" /><br />
                    <input type="text" name="title" value="Titel Auswahl" /><br />
                    <input type="text" name="name" value="Bezugsperson" /><br />
                    <input type="text" name="firma" value="Firma" /><br />
                    <input type="text" name="strasse" value="Strasse" /><br />
                    <input type="text" name="ort" value="Ort" /><br />
                    <input type="hidden" name="a" value="addVertrag" />
                    <input type="submit" value="ok" />
                </form>
        </div>
		<div>
            <div class="header2">neue Arbeit</div>
            	<form action="<?=SITE_HTML;?>/actions.php" method="post">
                    <input type="text" name="name" value="" style="width:100%;" /><br />
                    <input type="hidden" name="a" value="addArbeit" />
                    <input type="submit" value="ok" />
                </form>
        </div>
            <? $config = getConfig();?>
		<div>
            <div class="header2">Config</div>
            	<form action="<?=SITE_HTML;?>/actions.php" method="post">
            	<? foreach($config as $k=>$c) { ?>
            	<input type="text" name="<?=$k;?>" value="<?=utf8_encode($c);?>" style="width:100%;" /><br />
                <? } ?>
                <input type="hidden" name="a" value="updateConfig" /><input type="submit" value="ok" /></form>
        </div>
    </div>
<? } ?>