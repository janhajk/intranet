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
            if(!isset($_GET[$page]) || $_GET[$page]=='') {
                echo (canEnterBinary('0.0.0.0.0.0.0.0.1.1'))?makeElement('News', '<div>Fondwert:&nbsp;CHF&nbsp;'.stocks_totalFonds().'.-</div>'):'';
                $code = '<div class="container">';
                $i = 0;
                foreach($pages as $k=>$p) {
                    $code .= '<a href="?p='.$p.'" class="btn btn-default btn-block">'.$k.'</a>';
                }
                $code .= '</div>';
                makeElement('Seiten', $code);
                $code = getRandomScripture();
                echo makeElement('Zuf&auml;llige Schriftstelle', $code);
            }
            /*
             * Ende Startseite / Default-Seite
             */
        ?>
        <div class="box" style="text-align:center;font-size:7pt;">
            Pumpkin-Framework is &copy; 2009 by Jan Sch&auml;r (<a href="mailto:jan.schaer@janschaer.ch">Jan Schär</a>)
        </div>
    </body>
</html>

