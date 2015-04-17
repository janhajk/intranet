<? // Arbeit.Rechnungen.Edit Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.rechnungen.edit' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {?>
    <div class="container" style="max-width:700px !important;">
        <?php echo $nav_arbeit; ?>
        <h2>Invoice # <?php echo $_GET['id']; ?></h2>
        <?php
            $bill = getBillById($_GET['id']);
            $vertrag = getContractById($bill['vertrag']);
        ?>
        <p>Invoice for: <?php echo $vertrag['title']; ?></p>
        <form class="form-horizontal"  action="<?php echo SITE_HTML;?>/actions.php" method="post" role="form">
            <div class="form-group">
                <label class="control-label col-sm-2" for="bis">Date:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="bis" name="bis" value="<?php echo $bill['bis']; ?>">
                </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $bill['id']; ?>" />
            <input type="hidden" name="a" value="invoiceEdit" />

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </div>
        </form>
    </div>
<? } ?>