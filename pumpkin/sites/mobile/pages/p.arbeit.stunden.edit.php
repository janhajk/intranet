<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.stunden.edit' && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
	?>
    <div class="container" style="max-width:700px !important;">
        <?php echo $nav_arbeit; ?>
        <div class="title">Stunden</div>
        <div>
            <div class="header2">edit</div>
			<?=stunden_editform($_GET['id']);?>
        </div>
    </div>
<? } ?>