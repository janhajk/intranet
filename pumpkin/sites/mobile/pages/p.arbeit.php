<? // Arbeit Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == $pages['Arbeit'][0] && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) { ?>
    <?=link_back_head('');?>
    <div class="container" style="max-width:700px !important;">
        <h2>Arbeit</h2>
            <h3>Stunden erfassen</h3>
            <form role="form" action="<?=SITE_HTML;?>/actions.php" method="post">
                <select class="form-control" name="vertrag"><option>Vertrag</option><?=getVertrag(); ?></select>
                <select class="form-control" name="datum"><? getRecentDates(70); ?></select>
                <select class="form-control" name="von"><option>von</option><? getTimelist(6); ?></select>
                <select class="form-control" name="bis"><option>bis</option><? getTimelist(8); ?></select>
                <select class="form-control" name="arbeit"><option>Arbeit</option><?=getArbeiten(); ?></select>
                <input type="text" class="form-control" id="comment" placeholder="Arbeitsbeschrieb">
                <input type="hidden" name="a" value="writeArbeit" />
                <button type="submit" class="btn btn-default">Submit</button>
            </form>

            <h3>aktuelle Stunden</h3>
            <div>
                <? stunden_overview(5); ?>
            </div>

            <h3>sonstiges</h3>
			<ul>
				<li><a href="?p=arbeit.rechnungen">Rechnungen</a></li>
				<li><a href="?p=arbeit.stunden&l=10">Stunden&uuml;bersicht</a>&nbsp;&nbsp;&nbsp;<a href="<?=SITE_HTML;?>/actions.php?a=xlsstunden&vertrag=0">xls</a>&nbsp;|&nbsp;<a href="?p=arbeit.timeline&timeline=1">Timeline</a>&nbsp;|&nbsp;<a href="?p=arbeit.graphs">Graphs</a></li>
				<li><a href="?p=arbeit.total">Total Vertr&auml;ge</a></li>
                <li><a href="?p=arbeit.admin">admin</a></li>
			</ul>
    </div>
    <?=link_back_foot('');?>
<? } ?>