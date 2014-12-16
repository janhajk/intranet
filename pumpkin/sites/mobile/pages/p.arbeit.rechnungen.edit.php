<? // Arbeit.Rechnungen.Edit Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.rechnungen.edit' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
	?>
    <?=link_back_head($pages['Arbeit']);?>
    <div class="box">
        <div class="title">Rechnungen</div>
        <div>
            <div class="header2">edit</div>
        </div>
    </div>
    <?=link_back_foot($pages['Arbeit']);?>
<? } ?>