<? // Arbeit.Rechnungen Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.rechnungen' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
    <div class="container" style="max-width:700px !important;">
        <?php echo $nav_arbeit; ?>
        <h2>Invoices</h2>
        <form class="form-inline" role="form" action="<?=SITE_HTML;?>/actions.php" method="post">
            <div class="form-group">
                <label for="v">New Invoice for:</label>
                <select class="form-control" name="v" id="v"><option>select contract...</option><?=getVertrag(); ?></select>
            </div>
            <button type="submit" value="ok" class="btn btn-default">ok</button>
            <input type="hidden" name="a" value="makeBill" />
        </form>
        <p></p><p>Total unpaid invoices:  CHF&nbsp;<?=number_format(getBillsOpenBetrag(),2,",","'");?></p>
        <table class="table table-hover">
                <thead>
                    <tr>
                        <th>&#35;</th>
                        <th>Date</th>
                        <th>Contractor</th>
                        <th>Total</th>
                        <th>status</th>
                        <th>PDF</th>
                    </tr>
                </thead><?
                $statuscolor = array('info','danger','success');
                $statusIcon = array('print', 'envelope', 'ok');
                $statusTitle = array('Created', 'Sent to Recipent', 'Paid');
                $a = getLastBills();
                foreach($a as $aa) { ?>
                <tr class="<?php echo $statuscolor[$aa['status']];?>">
                    <td><a href="?p=arbeit.rechnungen.edit&id=<?=$aa['id'];?>"><?=$aa['id'];?></a></td>
                    <td><?=$aa['bis'];?></td>
                    <td><?php echo getVertragTitle($aa['vertrag']);?></td>
                    <td align="right"><?=number_format(getRechnungsTotal($aa['id']),2,",","'");?></td>
                    <td>
                        <a href="<?=SITE_HTML;?>/actions.php?a=changeBillStatus&b=<?=$aa['id'];?>&s=<?=$aa['status'];?>" title="<?php echo $statusTitle[$aa['status']];?>">
                            <span class="glyphicon glyphicon-<?php echo $statusIcon[$aa['status']];?>"></span>
                        </a>
                    </td>
                    <td><a href="<?=USER_ACT;?>/print.bill.php?nr=<?=$aa['id'];?>&a=BillPDF">PDF</a></td>
                </tr>
            <? } ?>
        </table>
    </div>
<? } ?>