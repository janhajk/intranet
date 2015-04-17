<? // Arbeit.Graphs Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.graphs' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
    <div class="container" style="max-width:700px !important;">
        <?php echo $nav_arbeit; ?>
        <div class="title">Einnahmen</div>
        <div>
            <div class="header2">Jan</div>
			<?=getMonthlyOverviewChart(date("Y"),0);?>
            <div class="header2">Raphael</div>
			<?=getMonthlyOverviewChart(date("Y"),2);?>
        </div>
    </div>
    <?=link_back_foot($pages['Arbeit']);?>
<? } ?>