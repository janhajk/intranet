<?
	session_start();
// vars laden
	include(dirname(__FILE__).'/lib.vars.php');

// Datenbank Klasse laden und Verbindung herstellen
	include(dirname(__FILE__).'/class.db.php');
	$db = new clsdb();

// Funktionsbibliothek laden
	include(dirname(__FILE__).'/functions.php');

// LIB schliessen GLOBAL
// von alten Scripts
	$ENDLIB = dirname(__FILE__).'/lib.end.php';
// für alle neuen Scripts
	define('ENDLIB', dirname(__FILE__).'/lib.end.php');

// Benutzer wird bei jedem Aufruf einer Seite/Aktion erneut eingeloggt
// dadurch kann man die Benutzerrechte einstellen, während jmd eingelogt ist
// Bei der nächsten Aktion werden somit die neuen Rechte angewendet
// Der Login führt über die htpasswd-Datei
	login($_SERVER["PHP_AUTH_USER"]);

// Funktion um LIB zu beenden/terminieren
	function endlib() {
		if (isset($GLOBALS['db'])) {
			$GLOBALS['db']->close();
			unset($GLOBALS['db']);
		}
		if (isset($GLOBALS['ME_DATA'])) {
			unset($GLOBALS['ME_DATA']);
		}
	}

?>