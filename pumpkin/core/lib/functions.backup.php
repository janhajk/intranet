<?
function pmkBackup($db,$files) {
	$msg = '';
	if (canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
		// Backup all files
		if($files=='1') {
			$filename = PMKTMP.'/backups/files_'.date('Ymdhms').'.tar';
			$cmd = "tar --create -z --directory=".$_SERVER['DOCUMENT_ROOT']."/ --file=".$filename." ./ --exclude ./pumpkin/core/addons/backup/*";
			passthru($cmd,$return1);
			$msg .= "Files Backed up $filename!<br />";
		}
		// Backup Datenbank
		if($db=='1') {
			$filename = PMKTMP.'backups/mysql_'.date('Ymdhms').'.gz';
			$command = "mysqldump --opt --host=".$GLOBALS['pmkdbhost']." --user=".$GLOBALS['pmkdbuser']." --password=".$GLOBALS['pmkdbpassword']." ".$GLOBALS['pmkdbdbname']." | gzip > $filename";
			passthru($command, $return2);
			$msg .= "DB Backed up $filename!";
		}
	}
	else {
		$msg = 'not enough rights!';
	}
	return $msg;
}
?>