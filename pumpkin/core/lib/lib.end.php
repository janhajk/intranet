<?
// Datenbank schliessen
if (isset($db)) {
	$db->close();
}

// Globale Variablen aufl�sen 
unset($db);
unset($kantone);
unset($ME_DATA);
?>