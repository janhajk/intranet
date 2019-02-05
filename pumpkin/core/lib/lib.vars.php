<?
	/*
	PMKROOT
	PMKHTTP
	PMKADDONS
	PMKADDONS_HTTP
	ROOT_PATH
	PMKTMP
	USER_ACT
	USER_IMG
	USER_IMG_HTTP
	PMKCSS
	PMKCSS_HTTP
	PMKJS
	PMKJS_HTTP
	PMKIMG
	PMKIMG_HTTP	
	*/
	

	// GLOBAL PMKROOT
	// Rootverzeichnis System von Pumpkin sans trailing '/'
	$pmkroot = '';
	$proot = explode('/',dirname(__FILE__));
	$key = array_search('pumpkin',$proot);
	for($i=0;$i<=$key;$i++) { $pmkroot .= $proot[$i].'/'; }
	define('PMKROOT', substr($pmkroot,0,strlen($pmkroot)-2));
	
	// Global PMKHTTP
	// HTTP Adresse von pumpkin relativ zu domain sans trailing '/' 
	// Bsp: http://www.ihredomain.ch/utils/pumpkin -> utils/pumpkin
	$key1 = array_search('intranet',$proot);
	$key2 = array_search('pumpkin',$proot);
	$pmkroot = '';
	for($i=$key1+1;$i<=$key2;$i++) { $pmkroot .= $proot[$i].'/'; }
	$pmkroot = substr($pmkroot,0,strlen($pmkroot)-2);
	define('PMKHTTP',$pmkroot);
	
	// Addons Pfade
	define('PMKADDONS',PMKROOT.'/core/addons');
	define('PMKADDONS_HTTP',PMKHTTP.'/core/addons');

	// GLOBAL ROOT_PATH
	// System Pfad zu http Verzeichnis
	$ROOT_PATH = $_SERVER['DOCUMENT_ROOT'];
	define('ROOT_PATH', $ROOT_PATH);
	
	// GLOBAL TMP
	// System Pfad zu tmp Folder with trailing '/'!!
	define('PMKTMP', $pmkCaller.'tmp/');
	
	// Constants USER
	// Benutzerverzeichnisse
	define('USER_ACT', $pmkCaller.PMKHTTP.'/user/act');
	define('USER_IMG_HTTP', $pmkCaller.PMKHTTP.'/user/images');
	define('USER_IMG', PMKROOT.'/user/images');
	
	// System Pfad und HTML zu CSS Verzeichnis
	define('PMKCSS', PMKROOT.'/css');
	define('PMKCSS_HTTP', $pmkCaller.PMKHTTP.'/css');
	// System Pfad und HTML zu JAVA Script Verzeichnis
	define('PMKJS', PMKROOT.'/javascript');
	define('PMKJS_HTTP', $pmkCaller.PMKHTTP.'/javascript');
	// System Pfad und HTML zu Image Verzeichnis
	define('PMKIMG', PMKROOT.'/images');
	define('PMKIMG_HTTP', $pmkCaller.PMKHTTP.'/images');
	
	
	/*
	 * Sites Pfade
	 */
	 define('SITES_ROOT', PMKROOT.'/sites');
	 
	
	// Database Variables
	$GLOBALS['pmkdbhost']  		= $dbhost;
	$GLOBALS['pmkdbuser'] 		= $dbuser;
	$GLOBALS['pmkdbpassword']  	= $dbpassword;
	$GLOBALS['pmkdbdbname']  	= $dbdbname;
?>