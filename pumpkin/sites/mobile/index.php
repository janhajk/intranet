<?php
// **************************************************************************************************
	//Lib laden
	include('elements.php');
	define('SITE_ROOT', SITES_ROOT.'/mobile');
	define('SITE_HTML', PMKHTTP.'/sites/'.PMKSITE);
// **************************************************************************************************
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>intranet</title>

        <!-- Bootstrap -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <?=getCSS();?>
        <?=loadJSTimeline();?>
    </head>
    <body <?=onLoadTimeline();?> onresize="">
        <?php
            if(isset($_SESSION['msg'])){
                echo "<div class=\"msg\">".$_SESSION['msg']."</div>";
                unset($_SESSION['msg']);
            }

            // Include all p.-Files
            $dir = SITE_ROOT.'/pages/';
            $files = scandir($dir);
            foreach($files as $i=>$value) {
                    if (substr($value, 0, 2) != 'p.') {
                            unset($files[$i]);
                    }
            }
            foreach($files as $v) {
                include($dir.$v);
            }


           /*
            * Startseite / Default-Seite
            */
            if(!isset($_GET[$page]) || $_GET[$page]=='') { ?>
            <div class="container" style="max-width:700px !important;">
                <div class="list-group">
                <?php
                if (canEnterBinary('0.0.0.0.0.0.0.0.1.1')) { ?>
                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">Fonds (Total)</h4>
                        <p class="list-group-item-text">CHF <?php echo pmkCurrency(stocks_totalFonds()); ?></p>
                    </div>
                    <div class="list-group-item">&nbsp;</div><?php
                }
                foreach($pages as $k=>$p) {?>
                    <a href="?p=<?php echo $p[0]; ?>" class="list-group-item">
                        <h4 class="list-group-item-heading"><?php echo $p[1]; ?></h4>
                        <p class="list-group-item-text"><?php echo $p[2]; ?></p>
                    </a>
                <?php
                } ?>
                <div class="list-group-item">&nbsp;</div>
                <div class="list-group-item">
                    <h4 class="list-group-item-heading">Random Scripture</h4>
                    <p class="list-group-item-text"><?php //echo getRandomScripture();?></p>
                </div>
                </div>
            </div>
            <?php
            }
            /*
             * Ende Startseite / Default-Seite
             */
        ?>
    </body>
</html>

