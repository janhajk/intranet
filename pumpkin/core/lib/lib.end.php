<?
// Datenbank schliessen
if (isset($db)) {
	$db->close();
}

// Globale Variablen auflösen 
unset($db);
unset($kantone);
unset($ME_DATA);
?>