<?

// variables to connect to the mysql server
$dbhost 	= "localhost";
$dbuser 	= "";
$dbpassword     = "";
$dbdbname 	= "";

// Path to Pumpkin-Folder with NO trailing Slash
$path_to_pumpkin = $_SERVER['DOCUMENT_ROOT'].'/pumpkin';

// Level of Caller of pumpkin-Framework exp: '../'
// Used for http Links; default is ''; can be changed before config.php is called
if(!isset($pmkCaller)) { $pmkCaller = ''; }


// Don't change
/* ---- */ include_once($path_to_pumpkin.'/core/lib/lib.php');

?>
