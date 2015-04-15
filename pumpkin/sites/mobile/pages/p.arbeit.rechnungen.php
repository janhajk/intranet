<? // Arbeit.Rechnungen Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.rechnungen' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
    <div class="container" style="max-width:700px !important;">
        <ul class="nav nav-pills" role="tablist">
            <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="?p=2">Business</a></li>
            <li class="active"><a href="?p=arbeit.rechnungen">Invoices</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Time Reports <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?=SITE_HTML;?>/actions.php?a=xlsstunden&vertrag=0">xls</a></li>
                    <li><a href="?p=arbeit.timeline&timeline=1">Timeline</a></li>
                    <li><a href="?p=arbeit.graphs">Graphs</a></li>
                </ul>
            </li>
            <li><a href="?p=arbeit.total">Contracts</a></li>
            <li><a href="?p=arbeit.admin"><span class="glyphicon glyphicon-wrench"></span></a></li>
        </ul>
        <h2>Invoices</h2>
        <form class="form-inline" role="form" action="<?=SITE_HTML;?>/actions.php" method="post">
            <div class="form-group">
                <label for="v">New Invoice for:</label>
                <select class="form-control" name="v" id="v"><option>select contract...</option><?=getVertrag(); ?></select>
            </div>
            <button type="submit" value="ok" class="btn btn-default">ok</button>
            <input type="hidden" name="a" value="makeBill" />
        </form>
        <p></p><p>Total unpaid invoices:  CHF&nbsp;<?=number_format(getBillsOpenBetrag(),2,",","`");?></p>
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
                $statuscolor = array('info','danger','success');
                $statusIcon = array('print', 'envelope', 'ok');
                $statusTitle = array('Created', 'Sent to Recipent', 'Paid');
                $a = getLastBills();
                foreach($a as $aa) { ?>
                <tr class="<?php echo $statuscolor[$aa['status']];?>">
                    <td><a href="?p=arbeit.rechnungen.edit&id=<?=$aa['id'];?>"><?=$aa['id'];?></a></td>
                    <td><?=$aa['bis'];?></td>
                    <td><?php echo getVertragTitle($aa['vertrag']);?></td>
                    <td align="right"><?=number_format(getRechnungsTotal($aa['id']),2,",","`");?></td>
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