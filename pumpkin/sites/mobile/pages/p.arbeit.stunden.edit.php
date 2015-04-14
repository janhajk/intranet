<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.stunden.edit' && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
	?>
    <?=link_back_head($pages['Arbeit'][0]);?>
    <div class="box">
        <div class="title">Stunden</div>
        <div>
            <div class="header2">edit</div>
			<?=stunden_editform($_GET['id']);?>
        </div>
    </div>
    <?=link_back_foot($pages['Arbeit'][0]);?>
<? } ?>