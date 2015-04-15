<? // Arbeit Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == $pages['Arbeit'][0] && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) { ?>
    <div class="container" style="max-width:700px !important;">
        <a href="index.php" role="button" class="btn btn-default btn-sm btn-block">back</a>
        <h2>Firma</h2>
        <div class="container">
			<ul class="nav nav-pills" role="tablist">
				<li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href="?p=arbeit.rechnungen">Rechnungen</a></li>
				<li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Stunden&uuml;bersicht <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?=SITE_HTML;?>/actions.php?a=xlsstunden&vertrag=0">xls</a></li>
                        <li><a href="?p=arbeit.timeline&timeline=1">Timeline</a></li>
                        <li><a href="?p=arbeit.graphs">Graphs</a></li>
                    </ul>
                </li>
				<li><a href="?p=arbeit.total">Total Vertr&auml;ge</a></li>
                <li><a href="?p=arbeit.admin">admin</a></li>
			</ul>
        </div>
            <h3>add new report entry</h3>
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

            <h3>time sheet</h3>
            <div>
                <div class="list-group">
                    <?php
                    $items = stunden_overview(5);
                    foreach ($items as $key=>$value) {?>
                        <a href="?p=arbeit.stunden.edit&id=<?php echo $value['id'];?>" class="list-group-item">
                            <h4 class="list-group-item-heading"><?php echo $value['date'] . ' '.substr($value['von'],0,5).'-'.substr($value['bis'],0,5);?></h4>
                            <p class="list-group-item-text">
                                <?php echo htmlspecialchars($value['comment']);?>
                            </p>
                        </a><?php
                    }
                ?>
                    <a href="?p=arbeit.stunden&l=10" class="list-group-item">
                        see all
                    </a>
                </div>
           </div>
    </div>
<? } ?>