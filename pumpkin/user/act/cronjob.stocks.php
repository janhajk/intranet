<?php
// **************************************************************************************************
	//Lib laden
	include($_SERVER['DOCUMENT_ROOT'].'/config.php');
// **************************************************************************************************
	//updateStatsStocks();
	stocks_update();
    // Redirect back
    echo '<script>(function(){window.setTimeout(function(){window.location.href="/index.php?p=1";},5000);})();</script>';
// **************************************************************************************************
	//Lib schliessen
	endlib();			// from lib/lib.php
// **************************************************************************************************
?>