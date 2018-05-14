<?php
function writeHTMLHeader() {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
	
	echo '<head>';
		echo '<title>'.end(getVar('pmkPageTitle')).'</title>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<link rel="stylesheet" type="text/css" href="'.loadCSSFiles().'" />';
		echo '<link rel="shortcut icon" href="favicon.ico" />';
		echo '<script type="text/javascript" src="'.PMKHTTP.'/core/addons/jquery/jquery/jquery-1.2.6.min.js"></script>';
		echo '<script type="text/javascript" src="'.PMKHTTP.'/core/addons/jquery/datepicker/ui.datepicker.js"></script>';
		echo '<script type="text/javascript" src="'.loadJSFiles().'"></script>';
		echo '<SCRIPT language="JavaScript">function pmkListStartup(){'.CPumpkinList_loadList_startup().'}</SCRIPT>';
		
	echo '</head>';
}

function loadCSSFiles() {
	$CSSString = '';
	$files = pmkGetFileTypeList('css');
	foreach($files as $v) {
		$CSSString .= PMKHTTP.'/'.$v.',';
	}
	return substr($CSSString,0,count($CSSString)-2);
}

function loadJSFiles() {
	$jsString = '';
	$files = pmkGetFileTypeList('js');
	foreach($files as $v) {
		if(end(explode('/',$v)) != 'jquery-1.2.6.min.js' && end(explode('/',$v)) != 'ui.datepicker.js') {
			$jsString .= PMKHTTP.'/'.$v.',';
		}
	}
	return substr($jsString,0,count($jsString)-2);
}


function pmkGetFileTypeList($extension) {
	$extFiles = scanDirectories(PMKROOT,$extension);
	$eFiles = array();
	foreach($extFiles as $f) {
		$proot = $f;
		$pmkroot = '';
		$proot = explode('/',$proot);
		$key = array_search('pumpkin',$proot);
		for($i=$key+1;$i<count($proot);$i++) { $pmkroot .= $proot[$i].'/'; }
		$pmkroot = substr($pmkroot,0,count($pmkroot)-2);
		$eFiles[] = $pmkroot;
	}
	return $eFiles;
}

/**
 * Recursive function to scan a directory with * scandir() *
 *
 * @param String $rootDir
 * @return multi dimensional array
 */
function scanDirectories($rootDir,$ext) {
    // set filenames invisible if you want 
    $invisibleFileNames = array(".", "..", ".htaccess", ".htpasswd");
    // run through content of root directory
    $dirContent = scandir($rootDir);
    $allData = array();
    foreach($dirContent as $key => $content) {
        // filter all files not accessible
        $path = $rootDir.'/'.$content;
        if(!in_array($content, $invisibleFileNames)) {
            // if content is file & readable, add to array
            if(is_file($path) && is_readable($path) && $ext == end(explode(".", $content))) {
                $allData[] = $path;
            // if content is a directory and readable, add path and name
            }elseif(is_dir($path) && is_readable($path)) {
                // recursive callback to open new directory
                $allDatatmp = scanDirectories($path,$ext);
				foreach($allDatatmp as $a) {$allData[] = $a;}
            }
        }
    }
    return $allData;
}
?>