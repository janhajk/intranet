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
                $code = '<ul id="menu">';
                foreach($pages as $k=>$p) {
                    $code .= '<li><a href="?p='.$p.'"><div class="icon" style="background-image:url(pumpkin/user/images/icons/'.$k.'.png)">'.$k.'</div></a></li>';
                }
                $code .= '</ul>';
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

