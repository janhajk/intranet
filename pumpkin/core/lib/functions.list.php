<?

// erstellt eine Liste mit Zahleneinträgen von 1 bis $count; ein Eintag bekommt 'selected'->$default
function list_option_number($count, $default) {
	for ($i=1;$i<=$count;$i++) {
		echo "<option value=\"$i\"";
		if ($i == $default){echo ' selected="selected"';} 
		echo ">$i</option>\n\r";
	}
}
?>