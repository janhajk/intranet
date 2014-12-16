<?

//Lädt eine Datei herunter (rechtsprüfung wurde ist schon erfolgt
function downloadFile($id, $ext, $title, $path='') {
	$filename = $GLOBALS['ROOT_PATH'].'/files/'.$path.''.$id.'.'.$ext;
	$outname = $title.'.'.$ext;
	// Für IE leerzeichen ersetzten durch '%20'
	if(substr_count(strtoupper(getenv("HTTP_USER_AGENT")),"MSIE" )>0)
	{$outname=str_replace ( " ","%20",$outname);}
		
	if(file_exists($filename)){
		$ext = strtolower($ext);
		switch ($ext) {
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpe": case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default: $ctype="application/force-download";
		}
		
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: $ctype");
		header("Content-Disposition: attachment; filename=\"$outname\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".@filesize($filename));
		set_time_limit(0);
		@readfile($filename) or die("Keine Datei");
	}
}


// Gibt die Dateiendung einer Datei
function strip_ext($filename) {
	$filename = strtolower($filename) ;
	$exts = split("[/\\.]", $filename) ;
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts;
}


// Gibt Dateiname und Endung in einem Array zurück
function getFileNameInfo($filename){
   if(strrpos($filename, '.')){
       //Everything before last '.' in $filename.
       $name =  substr($filename, 0, strrpos($filename, '.'));
 
 
       //Everything after last $needle in $filename.
       $ext =  substr(strrchr($filename, '.'), 1);
 
       //Return $left and $right into an array.
       $a = array($name, $ext);
       return $a;
  	}
	else{
       return false;
   }
}

function Dateigroesse($URL)
{
    $Groesse = filesize($URL);

    if($Groesse < 1000)
    {
        return number_format($Groesse, 0, ",", ".")." Bytes";
    }
    elseif($Groesse < 1000000)
    {
        return number_format($Groesse/1024, 0, ",", ".")." kB";
    }
    else
    {
        return number_format($Groesse/1048576, 1, ",", ".")." MB";
    }
}


// Zählt die Anzahl Zeilen in einem Verzeichnis und gibt Dateien und Ordner als Liste aus
function CountLines_list($it, $ende){
	$a = 0;
	$end = explode(",", $ende);	
	echo '<ul>';
	for(;$it->valid();$it->next())
	   {
	   if($it->isDir() && !$it->isDot())
		  {
		  printf('<li class="dir"><b>%s</b></li>', $it->current());
		  if($it->hasChildren())
			 {
			 $bleh = $it->getChildren();
			 echo '<ul>' . CountLines_list($bleh, $ende) . '</ul>';
			 }
		  }
	   elseif($it->isFile())
		   {
			$endung  = substr(strrchr($it->current(), '.'), 1);
	
			if(in_array($endung,$end))
				{
			$a += $dat = substr_count(trim(file_get_contents($it->getPath().'/'.$it->getFileName())),"\n")+1;
			  echo '<li class="file">'.$it->getFileName().' (' . $dat. ' Zeilen)</li>';
				}
		  }
	   }
	echo '</ul>';
	return $a;
}

$TotalProjectLines = 0;

// Zählt die Anzahl Zeilen in einem Verzeichnis
function CountLines($it, $ende){
	$end = explode(",", $ende);	
	for(;$it->valid();$it->next()){
		if($it->isDir() && !$it->isDot()){
			if($it->hasChildren()){
				$bleh = $it->getChildren();
				CountLines($bleh, $ende);
			}
		}
		elseif($it->isFile()) {
			$endung  = substr(strrchr($it->current(), '.'), 1);	
			if(in_array($endung,$end)) {
				$GLOBALS['TotalProjectLines'] += $dat = substr_count(trim(file_get_contents($it->getPath().'/'.$it->getFileName())),"\n")+1;
			}
		}
	}
}

function CountProjectLines($total=0) {
    $end = "php,js,css";
	if(!$total) {
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/'), $end);
	}
	else {
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/act/'), $end);
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/cls/'), $end);
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/css/'), $end);
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/js/'), $end);
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/lang/'), $end);
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/pag/'), $end);
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/parts/'), $end);
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/css/'), $end);
		CountLines(new RecursiveDirectoryIterator($GLOBALS['ROOT_PATH'].'/bin/lib/'), $end);
	}
	return $GLOBALS['TotalProjectLines'];
}


?>