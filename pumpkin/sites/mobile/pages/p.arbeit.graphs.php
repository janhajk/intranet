<? // Arbeit.Graphs Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.graphs' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
    <div class="container" style="max-width:700px !important;">
        <?php echo $nav_arbeit; ?>
        <h2>monthly chart</h2>
        <div>
            <h3>Jan</h3>
			<?=getMonthlyOverviewChart(date("Y"),0);?>
        </div>
    </div>
<? } ?>