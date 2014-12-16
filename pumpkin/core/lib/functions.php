<?
/**
 * Included all the functions.* files
 * is beeing read from db
 * read in new files via admin
 */
	$includes = array();
	$db->query('SELECT * FROM pmkLIB');
	#if(!$db->count) {$db->query('DELETE FROM pmkLIB');include_once('functions.include.php');pmkUpdateIncludes();}
	while($r = $db->results()) {
		$includes[] = $r['file'];
	}
	foreach($includes as $i) { require_once($i); }
	require_once(PMKROOT.'/core/CPumpkinList/functions.CPumpkinList.php');
	require_once(PMKROOT.'/core/addons/googlechart/gChart2.php');
?>
