<?php
// **************************************************************************************************
	//Lib laden
	include('elements.php');
	define('SITE_ROOT', SITES_ROOT.'/mobile');
	define('SITE_HTML', PMKHTTP.'/sites/'.PMKSITE);
// **************************************************************************************************
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>intranet</title>
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
                            $code = "<ul>";
                            foreach($pages as $k=>$p) {
                                if($k!='File') {$code .= '<li><a href="?p='.$p.'">'.$k.'</a></li>';}
                            }
                            $code .= '</ul>';
                makeElement('Seiten', $code);
                $code = getRandomScripture();
                echo makeElement('Zuf&auml;llige Schriftstelle', $code);
            }
            /*
             * Ende Startseite / Default-Seite
             */
            include (PMKROOT.'/parts/footer.php');
        ?>
    </body>
</html>