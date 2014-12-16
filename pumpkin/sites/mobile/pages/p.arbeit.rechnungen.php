<? // Arbeit.Rechnungen Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.rechnungen' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {	?>
<?=link_back_head($pages['Arbeit']);?>
    <div class="box">
        <div class="title">Rechnungen</div>
        <div>
            <div class="header2">erstellen</div>
                <table>
                    <tr>
                        <td><form action="<?=SITE_HTML;?>/actions.php" method="post">
                            <select name="v"><option>Vertrag</option><?=getVertrag(); ?></select><br>
                            <input type="submit" value="ok" />
                            <input type="hidden" name="a" value="makeBill" />
                            </form>
                        </td>
                    </tr>
                </table>
        	</div>
            <div class="header2">Rechnungen; offen:  CHF&nbsp;<?=number_format(getBillsOpenBetrag(),2,",","`");?></div>
				<table cellspacing="0" border="1" align="center">
					<thead><tr><th>Nr</th><th>Datum</th><th>Betrag</th><th align="center">*</th><th>download</th></tr></thead>
					<?
					$statuscolor = array('#d6d6d6','#ffd5d5','#e3ffdb','#e3ffdb');
					$a = getLastBills();
					foreach($a as $aa) { ?>
						<tr style="background-color:<?=$statuscolor[$aa['status']];?>">
							<td><a href="?p=arbeit.rechnungen.edit&id=<?=$aa['id'];?>"><?=$aa['id'];?></a></td>
							<td><?=$aa['bis'];?></td>
							<td><?=number_format(getRechnungsTotal($aa['id']),2,",","`");?></td>
							<td><a href="<?=SITE_HTML;?>/actions.php?a=changeBillStatus&b=<?=$aa['id'];?>&s=<?=$aa['status'];?>"><img src="/user/images/<?=getStatus($aa['status']);?>" border="0" title="<?=getVertragTitle($aa['vertrag']);?>" /></a></td>
							<td><a href="<?=USER_ACT;?>/print.bill.php?nr=<?=$aa['id'];?>&a=BillPDF">PDF</a></td>
						</tr>
					<? } ?>
				</table>
        	</div>
    </div>
    <?=link_back_foot($pages['Arbeit']);?>
<? } ?>
<?
function getStatus($s) {
	switch($s) {
		case 0:return 'print_icon.png';
		case 1:return 'sende_icon.png';
		case 2:return 'tick_icon.png';
		case 3:return 'tick_icon.png';
	}
}
?>