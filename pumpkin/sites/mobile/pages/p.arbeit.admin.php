<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.admin' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
        <?php echo $nav_arbeit; ?>
        <div class="container" style="max-width:700px !important;">
        <h2>Admin</h2>
        <h3>New Contract</h3>
        <form role="form" action="<?=SITE_HTML;?>/actions.php" method="post">
            <input class="form-control" type="text" name="arbeitstitel" placeholder="The Work of the Contract" />
            <input class="form-control" type="text" name="nettoansatz" placeholder="Wage per Hour" />
            <input class="form-control" type="text" name="title" placeholder="Selection Titel" />
            <input class="form-control" type="text" name="name" placeholder="Contractor Reference Person" />
            <input class="form-control" type="text" name="firma" placeholder="Company Name" />
            <input class="form-control" type="text" name="strasse" placeholder="Street Adress" />
            <input class="form-control" type="text" name="ort" placeholder="ZIP Code, City" />
            <input class="form-control" type="hidden" name="a" value="addVertrag" />
            <input class="btn btn-default" type="submit" value="ok" />
        </form>
        <h3>new Worktitle</h3>
        <form role="form" action="<?=SITE_HTML;?>/actions.php" method="post">
            <input class="form-control" type="text" name="name" value="" />
            <input class="form-control" type="hidden" name="a" value="addArbeit" />
            <input  class="btn btn-default" type="submit" value="ok" />
        </form>

        <? $config = getConfig();?>
        <h3>Config</h3>
        <form role="form" action="<?=SITE_HTML;?>/actions.php" method="post">
            <? foreach($config as $k=>$c) { ?>
            <input class="form-control" type="text" name="<?=$k;?>" value="<?=utf8_encode($c);?>" style="width:100%;" />
            <? } ?>
            <input type="hidden" name="a" value="updateConfig" />
            <input class="btn btn-default" type="submit" value="ok" />
        </form>
    </div>
<? } ?>