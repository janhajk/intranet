<?php

/*
 * Checks, wether a table exists in a DB or notes_body
 * $table string name of the Table to checkdate
* return boolean 0:does not exist; 1:does exist
 */
function pmkDoesTableExistInDB($table){
	$GLOBALS['db']->query("SHOW TABLES LIKE '$table'");
    return ($GLOBALS['db']->results())?1:0;
}

/*
 * Checks if a table exists, if not creates it from the *.sql file
 * located in the core/sql folder with the same name as the table
 */
function pmkInstallDbTable($table) {
	if(!pmkDoesTableExistInDB($table)) {
		$sql = file_get_contents(PMKROOT.'/core/sql/'.$table.'.sql');
		//if(!$GLOBALS['db']->query($sql)) return $GLOBALS['db']->errormessage;
		$GLOBALS['db']->query($sql);
		return "Tabelle '$table' neu erstellt.<br>".$sql;
	}
	else { return 'gibts schon'; }
}
?>