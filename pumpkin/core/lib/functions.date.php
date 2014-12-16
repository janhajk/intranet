<?php
/**
 * wandelt ein traditionelles deutsches Datum
 * nach MySQL (ISO-Date).
 */
function date_german2mysql($datum) {
    list($t, $m, $j) = explode(".", $datum);
    return sprintf("%04d-%02d-%02d", $j, $m, $t);
}

/**
 * wandelt ein MySQL-DATE (ISO-Date)
 * in ein traditionelles deutsches Datum um.
 */
function date_mysql2german($datum) {
    list($jahr, $monat, $tag) = explode("-", $datum);
    return sprintf("%02d.%02d.%04d", $tag, $monat, $jahr);
}

/**
 * wandelt ein Exceldatum ('dd/mm/yyyy')
 * nach MySQL um (yyy-mm-dd).
 */
function date_excel2mysql($datum) {
	if($datum==''){return '0000-00-00';}	// Wenn leeres Datum
	$seperator = (strpos($datum,'.'))?'.':'/';	// Wenn durch Punkte getrenn (Text-Format)
    list($t, $m, $j) = explode($seperator, $datum);
	$j = (strlen($j)==2)?(($j>50&&$j<=99)?'19'.$j:'20'.$j):$j;	// Datum im Text-Format DD.MM.YY -> Jahrtausend fehlt
    return sprintf("%04d-%02d-%02d", $j, $m, $t);
}

// leeres Datum nicht erlaubt
// Überprüft, ob ein Datum korrekt ist / bzw. existiert
function isCorrectDateFormat($d, $s='.'){
	if ($d=='') 									{return 0;}  // Wenn Datum leer	
	elseif ($d == '00.00.0000') 					{return 1;} // Datum darf leer sein
	elseif (substr_count($d, $s) != 2) 	{return 0;}  // Datum muss genau zwei Separatoren haben
	else {
		list($t, $m, $j) = explode($s, $d); // Datum aufsplitten
		if 	   ((int) $t < 1    || (int) $t > 31)   {return 0;}
		elseif ((int) $m < 1    || (int) $m > 12)   {return 0;}
		elseif ((int) $j < 1900 || (int) $j > 2030) {return 0;}	
		elseif (checkdate($m, $t, $j))				{return 1;}
		else										{return 0;}
	}
} //end function isDate


// gleich wie "isCorrectDateFormat()" jedoch leeres Datum erlaubt
// Überprüft, ob ein Datum korrekt ist / bzw. existiert -> leeres Datum (0000-00-00) erlaubt
function isValidDate($d, $s='-') {
	if ($d == '0000-00-00') 						{return 1;} // Datum darf leer sein
	elseif (substr_count($d, $s) != 2) 				{return 0;} // Datum muss genau zwei Separatoren haben
	else {
		list($j, $m, $t) = explode($s, $d); // Datum aufsplitten
		if 	   ((int) $t < 1    || (int) $t > 31)   {return 0;}
		elseif ((int) $m < 1    || (int) $m > 12)   {return 0;}
		elseif ((int) $j < 1900 || (int) $j > 2030) {return 0;}	
		elseif (checkdate($m, $t, $j))				{return 1;}
		else										{return 0;}
	}
}

function microsoftdate2mysql($int) {
	$days = ($int-25569);	// Tage seit 01.01.1970
	$sek  = $days*60*60*24; // Sekunden seit 01.01.1970
	return date("Y-m-d", $sek);
}

function iso86012timestamp($tstamp) {
	// converts ISODATE to unix date
	// 1984-09-01T14:21:31Z
	sscanf($tstamp,"%u-%u-%uT%u:%u:%uZ",$year,$month,$day,$hour,$min,$sec);
	$newtstamp = mktime($hour,$min,$sec,$month,$day,$year);
	return $newtstamp;
}

/*
 * Mysql date to unix-timestamp
 * accepts either "2009-12-03" or "2009-12-03 20:53:15"
 */
function mysql2timestamp($d){
	if(strlen($d)==10) {
		return mktime(0,0,0,$d[5].$d[6],$d[8].$d[9],$d[0].$d[1].$d[2].$d[3]);
	}
	else {
		$val = explode(" ",$d);
		$date = explode("-",$val[0]);
		$time = explode(":",$val[1]);
		return mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
	}
}
?>