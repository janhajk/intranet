<? // Arbeit.Rechnungen Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.rechnungen' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
    <div class="container" style="max-width:700px !important;">
        <a href="index.php?p=<?php echo $pages['Arbeit'][0]; ?>" role="button" class="btn btn-default btn-sm btn-block">back</a>
        <h2>Rechnungen</h2>
        <form class="form-inline" role="form" action="<?=SITE_HTML;?>/actions.php" method="post">
            <div class="form-group">
                <label for="v">Neue Rechnung f&uuml;r:</label>
                <select class="form-control" name="v" id="v"><option>Vertrag</option><?=getVertrag(); ?></select>
            </div>
            <button type="submit" value="ok" class="btn btn-default">ok</button>
            <input type="hidden" name="a" value="makeBill" />
        </form>
        <h3>Rechnungen; offen:  CHF&nbsp;<?=number_format(getBillsOpenBetrag(),2,",","`");?></h3>
        <table class="table table-hover">
                <thead>
                    <tr>
                        <th>&#35;</th>
                        <th>Date</th>
                        <th>contractor</th>
                        <th>Total</th>
                        <th>status</th>
                        <th>PDF</th>
                    </tr>
                </thead><?
                $statuscolor = array('info','danger','success','success');
                $statusIcon = array('print', 'envelope', 'ok');
                $a = getLastBills();
                foreach($a as $aa) { ?>
                <tr class="<?php echo $statuscolor[$aa['status']];?>">
                    <td><a href="?p=arbeit.rechnungen.edit&id=<?=$aa['id'];?>"><?=$aa['id'];?></a></td>
                    <td><?=$aa['bis'];?></td>
                    <td><?php echo getVertragTitle($aa['vertrag']);?></td>
                    <td align="right"><?=number_format(getRechnungsTotal($aa['id']),2,",","`");?></td>
                    <td>
                        <a href="<?=SITE_HTML;?>/actions.php?a=changeBillStatus&b=<?=$aa['id'];?>&s=<?=$aa['status'];?>">
                            <span class="glyphicon glyphicon-<?php echo $statusIcon[$aa['status']];?>"></span>
                        </a>
                    </td>
                    <td><a href="<?=USER_ACT;?>/print.bill.php?nr=<?=$aa['id'];?>&a=BillPDF">PDF</a></td>
                </tr>
            <? } ?>
        </table>
    </div>
<? } ?>