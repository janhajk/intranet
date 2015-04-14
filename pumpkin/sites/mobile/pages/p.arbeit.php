<? // Arbeit Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == $pages['Arbeit'][0] && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) { ?>
    <?=link_back_head('');?>
    <div class="box">
        <div class="title">Arbeit</div>
        <div>
            <div class="header2">Stunden erfassen</div>
            <table>
				<tr>
					<td><form action="<?=SITE_HTML;?>/actions.php" method="post">
						<select name="vertrag"><option>Vertrag</option><?=getVertrag(); ?></select><br>
						<select name="datum"><? getRecentDates(70); ?></select><br>
						<select name="von"><option>von</option><? getTimelist(6); ?></select><br>
						<select name="bis"><option>bis</option><? getTimelist(8); ?></select><br>
						<select name="arbeit"><option>Arbeit</option><?=getArbeiten(); ?></select><br>
						<input type="text" name="comment" /><br>
						<input type="submit" value="save" />
						<input type="hidden" name="a" value="writeArbeit" />
						</form>
					</td>
				</tr>
            </table>
        </div>
        <div>
            <div class="header2">aktuelle Stunden</div>
            <table><? stunden_overview(5); ?></table>
        </div>
        <div>
            <div class="header2">sonstiges</div>
			<ul>
				<li><a href="?p=arbeit.rechnungen">Rechnungen</a></li>
				<li><a href="?p=arbeit.stunden&l=10">Stunden&uuml;bersicht</a>&nbsp;&nbsp;&nbsp;<a href="<?=SITE_HTML;?>/actions.php?a=xlsstunden&vertrag=0">xls</a>&nbsp;|&nbsp;<a href="?p=arbeit.timeline&timeline=1">Timeline</a>&nbsp;|&nbsp;<a href="?p=arbeit.graphs">Graphs</a></li>
				<li><a href="?p=arbeit.total">Total Vertr&auml;ge</a></li>
                <li><a href="?p=arbeit.admin">admin</a></li>
			</ul>
        </div>
    </div>
    <?=link_back_foot('');?>
<? } ?>