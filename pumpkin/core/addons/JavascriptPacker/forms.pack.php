<?php
	// Klasse um JavaScript on the Fly zu packen
	require 'class.JavaScriptPacker.php';
	// formen, welche eingeschlossen werden
	$src = array();
	$src[] = 'bill';
	$src[] = 'vertrag';
	$src[] = 'firma';
	$src[] = 'leistung';
	$src[] = 'v_overview';
	// Dateiname des gepackten Javascripts
	$out = 'forms.packed.php';
	// Javascript erstellen
	$script = '';	// Inhalt des JavaCodes
	foreach ($src as $s) {
		$content = file_get_contents('forms/'.$s.".manipulate.php");
		$script .= "pmkLSTfrmedit".$s."='".$content."';";	
	}
	// JavaScript packen
	$packer = new JavaScriptPacker($script, 62, true, false);
	$packed = $packer->pack();
	echo $packed;
?>
