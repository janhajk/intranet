<?php
function createRandomPassword() {
    $chars = "abcdefghijkmnpqrstuvwxyz23456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;
    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

// **************************************************************************************************
// Überträgt die Benutzernamen und Psw in die Htaccess Datei
// **************************************************************************************************
function updateHtpasswd() {
	if (canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
		$file = ROOT_PATH."/.htpasswd";				// Pfad der .htpasswd-Datei
		$db = $GLOBALS['db'];						// Datenbank
		$db->query("SELECT * FROM `pmkUsers`");		// ...
		$fs = fopen($file, "w");					// htpasswd Datei zum überschreiben öffnen
		$i = 0;										// zähler
		while($r = $db->results()) {				// alle User durchgehen
			$name = $r['email'];					// * Benutzername
			$pass = crypt($r['psw']);				// * Passwort (md5 kodiert)
			if(fwrite($fs, "$name:$pass\n")) $i++;	// schreiben (+neue Zeile und mit ':' getrennt)
		}
		fclose($fs);								// Datei speichern und schliessen
		return $i.' Eintr&auml;ge geschrieben';
	}
	else { return 'not enough rights!'; }
}
// **************************************************************************************************

function fisherYatesShuffle(&$items) 
{ 
   for ($i = count($items) - 1; $i > 0; $i--) 
   { 
      $j = @mt_rand(0, $i); 
      $tmp = $items[$i]; 
      $items[$i] = $items[$j]; 
      $items[$j] = $tmp; 
   } 
}


function pmkEncode($key, $text)
{
    $l_k = strlen($key);
    $l_t = strlen($text);
    
    if($l_k == 0) return $text; // Ohne Key keine Verschlüsselung!!!
    
    $encoded = "";
    $k = 0; // Position im Key
    for($i=0; $i<$l_t; $i++)
    {
        if($k > $l_k) $k = 0; // Wenn ende des keys, dann wieder von vorne
        $encoded .= chr(ord($text[$i]) ^ ord($key[$k])); // Verschlüsselung
        $k++;
    }
    return $encoded;
}

function pmkDecode($key, $chiffre)
{
    $l_k = strlen($key);
    $l_t = strlen($chiffre);
    
    if($l_k == 0) return $text; // Ohne Key keine Verschlüsselung!!!
    
    $decoded = "";
    
    $k = 0; // Position im Key
    for($i=0; $i<$l_t; $i++)
    {
        if($k > $l_k) $k = 0; // Wenn ende des keys, dann wieder von vorne
        $decoded .= chr(ord($chiffre[$i]) ^ ord($key[$k])); // Verschlüsselung
        $k++;
    }
    
    return $decoded;
}
?>