<?
// **************************************************************************************************
	//Lib laden
	include($_SERVER['DOCUMENT_ROOT'].'/config.php');
// **************************************************************************************************
	if (canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
		// Backup all files
		$filename = 'files_'.date('Ymdhms').'.tar';
		$cmd = "tar --create -z --directory=".$_SERVER['DOCUMENT_ROOT']."/ --file=".$filename." ./ --exclude ./pumpkin/core/addons/backup/*";
		passthru($cmd,$return1);
		// Backup Datenbank
		$filename = 'mysql_'.date('Ymdhms').'.gz';
		$command = "mysqldump --opt --host=".$GLOBALS['pmkdbhost']." --user=".$GLOBALS['pmkdbuser']." --password=".$GLOBALS['pmkdbpassword']." ".$GLOBALS['pmkdbdbname']." | gzip > $filename";
		passthru($command, $return2);
	}
	else {
		$msg = 'not enough rights!';
	}
	echo $return1.'<br />'.$return2;
// **************************************************************************************************
	//Lib schliessen
	endlib();
// **************************************************************************************************
?>