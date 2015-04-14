<?
/*
 * Updates the Links to all the Lib-Files to be included by the framework
 * Is beeing stored in db for performance reasons
 * function can be called from admin-menu
 */
function pmkUpdateIncludes() {
	$db = $GLOBALS['db'];
	// Delete old links
	$db->query('DELETE FROM pmkLIB');
	/*
		scannt den lib Ordner und included alle functions dateien
	*/
	$files = scandir(dirname(__FILE__).'/');
	foreach($files as $i => $value) {
			if (substr($value, 0, 1) == '.' || $value == 'lib.php' || $value == 'lib.end.php' || $value == 'functions.php' || $value == 'psw' || $value == 'old.php' || $value == 'class.db.php' || $value == 'lib.vars.php' || $value == '_info') {
					unset($files[$i]);
			}
	}
	foreach($files as $v) {
		$db->query("INSERT INTO pmkLIB (file) VALUES ('".dirname(__FILE__).'/'.$v."');");
	}
	
	/*
		scannt den user/lib Ordner und included alle functions dateien
	*/
	$files = scandir(PMKROOT.'/user/lib/');
	foreach($files as $i => $value) {
			if (substr($value, 0, 1) == '.' || $value == 'old.php') {
					unset($files[$i]);
			}
	}
	foreach($files as $v) {
		$db->query("INSERT INTO pmkLIB (file) VALUES ('".PMKROOT.'/user/lib/'.$v."');");
	}
}

function pmkCssUpdate($site='mobile') {
	$db = $GLOBALS['db'];
	// DELETE Old information
	$db->query('DELETE FROM pmkCSS WHERE site LIKE \''.$site.'\'');
	$dir = PMKROOT.'/sites/'.$site.'/css';
	$files = scandir($dir);
	foreach($files as $i => $value) {
			if (substr($value, -4) !== '.css') {
					unset($files[$i]);
			}
	}
	foreach($files as $v) {
		$content = file_get_contents($dir.'/'.$v);
		// Remove whitespace/etc. in CSS
		$content = preg_replace("/\n|\t|\r/i", '', $content);
		$content = preg_replace("/\s\{|\{\s/i", '{', $content);
		$content = preg_replace("/\s\}|\}\s/i", '}', $content);
		$content = preg_replace("/:\s/i", ':', $content);
        $sql = "INSERT INTO pmkCSS (content, site) VALUES ('" . $content . "','" . $site . "')";
		$db->query($sql);
        $db->db_com_get_last_error();
	}
}

function getCSS($site='mobile') {
	$db = $GLOBALS['db'];
	$db->query('SELECT `content` FROM `pmkCSS` WHERE `site` = \''.$site.'\' LIMIT 0,1');
	$content = $db->results();
	return '<style type="text/css">'.$content['content'].'</style>';
}
?>