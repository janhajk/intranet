<? // Arbeit.Rechnungen.Edit Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.rechnungen.edit' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {?>
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
        <h2>Invoice # <?php echo $_GET['id']; ?></h2>
        <?php
            $bill = getBillById($_GET['id']);
            $vertrag = getContractById($bill['id']);
        ?>
        <p><?php echo $vertrag['title']; ?></p>
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="control-label col-sm-2" for="bis">Date:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="bis" name="bis" value="<?php echo $bill['bis']; ?>">
                </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $bill['id']; ?>" />
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </div>
        </form>
            [id] => 1115
    [vertrag] => 24
    [filename] => 
    [status] => 2
    [betrag] => 0
    [von] => 2013-07-07
    [bis] => 2014-04-10
        }
    </div>
<? } ?>