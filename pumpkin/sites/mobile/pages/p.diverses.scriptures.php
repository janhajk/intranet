<? // diverses.scriptures Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'diverses.scriptures' && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
	?>
    <?=link_back_head($pages['diverses']);?>
    <div class="box">
        <div class="title">Scriptures</div>
        <div>
            <div class="header2">Dein Schriftstenstudium</div>
			<?=getStudyScripture($_SESSION['myid']);?>
			<div><a href="<?=SITE_HTML;?>/actions.php?a=nextScripture">weiter</a></div>
        </div>
    </div>
    <?=link_back_foot($pages['diverses']);?>
<? } ?>