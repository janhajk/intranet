<?
// **************************************************************************************************
// Holt die Konfigurationseinstellungen für das die Rechnung
// **************************************************************************************************
function getConfig() {
	$db = $GLOBALS['db'];
	$info = array();
	$sql = "SELECT * FROM `rap_config`";
	$db->query($sql);
	while($l = $db->results()){
		$info[$l['name']] = $l['value'];
	}
	foreach($info as $k=>$i) { $info[$k] = utf8_decode($i); }
	return $info;
}

// **************************************************************************************************
// Rechnungstotal
// **************************************************************************************************
function getRechnungsTotal($RechnNr) {
	$db = $GLOBALS['db'];
	$sql = "SELECT * FROM `rap_bills` WHERE `id` = $RechnNr LIMIT 1";
	$db->query($sql);
	while($l = $db->results()) {
		$von = $l['von'];  // Datum von letzter Rechnung
		$bis = $l['bis'];  // Datum von letzter Rechnung
		$vertrag = $l['vertrag'];
	}
	$sql = 'SELECT SUM(stunden) AS total FROM rap_stunden 
	WHERE
	vertrag = '.$vertrag.' AND 
	date >= \''.$von.'\' AND 
	date <= \''.$bis.'\'';
	$db->query($sql);
	while($l = $db->results()) {
		$total = $l['total'];
	}
	return round($total*getVertragsFee($vertrag)/5,2)*5;
}

function getVertragsFee($vertrag) {
	$db = $GLOBALS['db'];
	$sql = "SELECT nettoansatz FROM `rap_vertrag` WHERE `id` = $vertrag LIMIT 1";
	$db->query($sql);
	while($l = $db->results()) {
		return $l['nettoansatz'];
	}
}

function getBillById($id) {
    $db = $GLOBALS['db'];
    $sql = "SELECT * FROM rap_bills WHERE id = ".((int) $id)." LIMIT 1";
    $db->query($sql);
    while($l = $db->results()){
        $r = $l;
    }
    return $r;
}

function getContractById($id){
    $db = $GLOBALS['db'];
    $sql = "SELECT * FROM rap_vertrag WHERE ID = ".((int) $id)." LIMIT 1";
    $db->query($sql);
    while($l = $db->results()){
        $r = $l;
    }
    return $r;
}
// **************************************************************************************************
// Vertragstotal seit letzter Rechnung
// **************************************************************************************************

function get_Vertragstotal_since_last_bill($vertrag) {
	$db = $GLOBALS['db'];
	// Zeitraum
	$bis = date("Y-m-d");  	// Aktueller Tag
	$von = "2000-01-01";	// default Datum wenn noch keine Rechnung vorhanden
	// Datum von letzter Rechnung
	$sql = "SELECT `date` FROM `rap_bills` WHERE `vertrag` = $vertrag ORDER BY `date` DESC LIMIT 0,1";
	$db->query($sql);
	while($l = $db->results()) {
		$von = $l[0];  // Datum von letzter Rechnung
	}
// **************************************************************************************************
	// Daten für die Arbeitsbeschriebgruppierten Zeilen
	$data = array();
	// SQL zum Auswählen von den Stunden, Gruppiert nach dem Arbeitsbeschrieb
	$sql  = 'SELECT `rap_stunden`.`date`,`rap_arbeit`.`name` AS `arbeitsbeschrieb`, `rap_vertrag`.`nettoansatz`, SUM(`rap_stunden`.`stunden`) AS `total`   
	FROM rap_stunden 
	LEFT JOIN rap_vertrag ON (`rap_stunden`.`vertrag`    = `rap_vertrag`.`ID`) 
	LEFT JOIN rap_arbeit  ON (`rap_stunden`.`arbeit`     = `rap_arbeit`.`ID`) 
	WHERE  
	`rap_vertrag`.`ID` = '.$vertrag.' AND 
	`rap_stunden`.`date` >= \''.$von.'\' AND 
	`rap_stunden`.`date` <= \''.$bis.'\' 
	GROUP BY `rap_arbeit`.`name`   
	ORDER BY `rap_stunden`.`date` DESC';
	//echo $sql;
	$db->query($sql);
	while($l = $db->results()) {
		$data[] = $l;
	}
    //Data
	$total = 0;
    foreach($data as $row)
    {
		$total+=$row[2]*$row[3];
    }
    return $total;
}
function get_Stunden_since_last_bill($vertrag) {
	$db = $GLOBALS['db'];
	// Zeitraum
	$bis = date("Y-m-d");  	// Aktueller Tag
	$von = "2000-01-01";	// default Datum wenn noch keine Rechnung vorhanden
	// Datum von letzter Rechnung
	$sql = "SELECT `date` FROM `rap_bills` WHERE `vertrag` = $vertrag ORDER BY `date` DESC LIMIT 0,1";
	$db->query($sql);
	while($l = $db->results()) {
		$von = $l[0];  // Datum von letzter Rechnung
	}
// **************************************************************************************************
	// Daten für die Arbeitsbeschriebgruppierten Zeilen
	$data = array();
	// SQL zum Auswählen von den Stunden, Gruppiert nach dem Arbeitsbeschrieb
	$sql  = 'SELECT `rap_stunden`.`date`,`rap_arbeit`.`name` AS `arbeitsbeschrieb`, `rap_vertrag`.`nettoansatz`, SUM(`rap_stunden`.`stunden`) AS `total`   
	FROM rap_stunden 
	LEFT JOIN rap_vertrag ON (`rap_stunden`.`vertrag`    = `rap_vertrag`.`ID`) 
	LEFT JOIN rap_arbeit  ON (`rap_stunden`.`arbeit`     = `rap_arbeit`.`ID`) 
	WHERE  
	`rap_vertrag`.`ID` = '.$vertrag.' AND 
	`rap_stunden`.`date` >= \''.$von.'\' AND 
	`rap_stunden`.`date` <= \''.$bis.'\' 
	GROUP BY `rap_arbeit`.`name`   
	ORDER BY `rap_stunden`.`date` DESC';
	$db->query($sql);
	while($l = $db->results()) {
		$data[] = $l;
	}
    //Data
	$total = 0;
    foreach($data as $row)
    {
		$total+=$row[3];
    }
    return $total;
}

function getLastBills() {
	$db = $GLOBALS['db'];
	$a = array();
	$sql = "SELECT * FROM rap_bills ORDER BY status ASC, bis DESC";
	$db->query($sql);
	while($r = $db->results()) {
		$a[] = $r;
	}
	return $a;
}

function getBillsOpenBetrag() {
	$db = $GLOBALS['db'];
	$total = 0;
	$a = array();
	$sql = "SELECT * FROM rap_bills WHERE status != '2'";
	$db->query($sql);
	while($r = $db->results()) {
		$a[] = $r['id'];
	}
	foreach($a as $aa) { $total += getRechnungsTotal($aa); }
	return $total;	
}



function einzahlungsschein($pdf, $totalbetrag) {
	$config = getConfig();
	$x = 30;
	$y = 190;
	$h = 80;
	$w = 150;
	$pdf->AddPage();
	make_background($pdf);
	$pdf->SetFillColor(255, 204, 204);
	$pdf->Rect($x,$y,$w,$h, 'F');
	$pdf->SetDrawColor(0, 0, 0); 
	$pdf->line($x,$y+3.5,$x+$w,$y+3.5);  // obere Schwarze dünne Linie
	$pdf->line(61+$x,$y+3.5,61+$x,$y+$h);	// senkrechte Lange linie
	$pdf->line(61+$x,$y+29,$w+$x,$y+29);	// mittlere horizontale Linie
	$pdf->line(118+$x,$y+3.5,118+$x,$y+4+25);	// Kurze vertikale linie
	$pdf->SetLineWidth(0.5); 
	$pdf->line($x+3,$y+80,$x+3+5,$y+80);$pdf->line($x+3,$y+78,$x+3,$y+80);	// ecklein Begrenzung links
	$pdf->line($x+140,$y+80,$x+140+5,$y+80);$pdf->line($x+140+5,$y+78,$x+140+5,$y+80);	// ecklein Begrenzung rechts
	$pdf->SetLineWidth(0.2);
	$pdf->SetFont('helvetica','B',8);
	$pdf->Text($x+7, $y+3, 'Einzahlung Giro');
	$pdf->Text($x+7+50, $y+3, 'Versement Virement');
	$pdf->Text($x+7+105, $y+3, 'Versamento Girata');
	swisscross($x+3,$y+3.2,$pdf);swisscross($x+30,$y+3.2,$pdf);swisscross($x+53,$y+3.2,$pdf);swisscross($x+85,$y+3.2,$pdf);swisscross($x+108,$y+3.2,$pdf);swisscross($x+138,$y+3.2,$pdf);
	$pdf->SetFont('helvetica','',6);
	$pdf->SetTextColor(255, 100, 100);
	$pdf->Text($x+3, $y+6, 'Einzahlung fuer / Versement pour / Versamento per');
	$pdf->Text($x+64, $y+6, 'Zahlungszweck / Motif versement / Motivo versamento');
	$pdf->Text($x+64, $y+39, 'Einbezahlt von / Verse par / Versato da');
	$pdf->SetDrawColor(255, 100, 100);
	$pdf->SetLineWidth(0.1);
	$pdf->line($x+64,$y+55,$x+64+80,$y+55);
	$pdf->line($x+64,$y+55+6,$x+64+80,$y+55+6);
	$pdf->line($x+64,$y+55+6+6,$x+64+80,$y+55+6+6);
	$pdf->SetDash(0.3, 0.4);$pdf->line($x+64,$y+55+6+6+6,$x+64+80,$y+55+6+6+6);
	$pdf->SetDash(0.3, 0.4);$pdf->Circle($x+135, $y+16, 9, '');$pdf->SetDash();$pdf->SetLineWidth(0.2);		// Stempel-Kreis
	// Bank
	$pdf->SetFont('helvetica','B',8);
	$pdf->SetTextColor(0, 0, 0);
	$i = 3.5;
	$pdf->Text($x+3, $y+6+$i*2, 'UBS AG');
	$pdf->Text($x+3, $y+6+$i*3, '8098 Zuerich');
	// Zugunsten von
	$pdf->SetFont('helvetica','',6);
	$pdf->SetTextColor(255, 100, 100);
	$pdf->Text($x+3, $y+6+$i*5, 'Zugunsten von / En faveur de / A favore di');
	// Jan Schär...
	$pdf->SetFont('helvetica','B',8);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Text($x+3, $y+6+$i*6, $config['myname']);
	$pdf->Text($x+3, $y+6+$i*7, $config['strasse']);
	$pdf->Text($x+3, $y+6+$i*8, $config['plz'].' '.$config['ort']);
	$pdf->Text($x+3, $y+6+$i*9, $config['iban']);
	// Konto
	$pdf->SetFont('helvetica','',6);
	$pdf->SetTextColor(255, 100, 100);
	$pdf->Text($x+3, $y+6+$i*10, 'Konto / Compte / Conto');
	// Konto-Nr.
	// Jan Schär...
	$pdf->SetFont('helvetica','B',8);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Text($x+3, $y+6+$i*11, $config['ubskonto']);
	// CHF
	$pdf->SetFont('helvetica','B',9);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Text($x+3, $y+6+$i*13, 'CHF');
	// Betrag
	$pdf->SetDrawColor(255, 100, 100);
	$pdf->SetFillColor(255, 255, 255); 
		// Ecklein
		$k = 4.5;
		$pdf->rect($x+3+$k*0, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*1, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*2, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*3, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*4, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*5, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*6, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*7, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*8, $y+6+$i*13.5,4,5,'FD');
		
		$pdf->rect($x+3+$k*10, $y+6+$i*13.5,4,5,'FD');
		$pdf->rect($x+3+$k*11, $y+6+$i*13.5,4,5,'FD');
	// Betrag
		$pdf->SetFont('helvetica','B',12);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Text($x+3+$k*9+1.5, $y+6+$i*13.5+5,'.');	// Punkt
		// Franken
		$totalbetrag = number_format($totalbetrag,2,'.','');
		$rappen = substr($totalbetrag,strlen($totalbetrag)-2);
		$franken = substr($totalbetrag,0,strlen($totalbetrag)-3);
		$betragsteile = split('.',$totalbetrag,1);
		$pdf->SetFont('helvetica','B',9);
		$pdf->SetTextColor(0, 0, 0);
		for($s=strlen($franken)-1;$s>=0;$s--) {
			$pdf->Text($x+3+$k*(8-$s)+1.5, $y+6+$i*13.5+4.5,$franken[strlen($franken)-$s-1]);
		}
		// Rappen
		$pdf->Text($x+3+$k*10+1.5, $y+6+$i*13.5+4.5,$rappen[0]);	
		$pdf->Text($x+3+$k*11+1.5, $y+6+$i*13.5+4.5,$rappen[1]);
	// Zahlungszweck / Rechnungsnummer
			$pdf->SetFont('helvetica','B',8);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Text($x+70, $y+15, 'R-Nr. '.$GLOBALS['nr']);
		
}
function swisscross($x, $y, $pdf) {
	$pdf->SetLineWidth(0.1);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->line($x,$y-1,$x+1,$y-1);
	$pdf->line($x+2,$y-1,$x+3,$y-1);
	$pdf->line($x,$y-2,$x+1,$y-2);
	$pdf->line($x+2,$y-2,$x+3,$y-2);
	$pdf->line($x+1,$y,$x+2,$y);
	$pdf->line($x+1,$y-3,$x+2,$y-3);
	$pdf->line($x,$y-1,$x,$y-2);
	$pdf->line($x+1,$y,$x+1,$y-1);
	$pdf->line($x+1,$y-2,$x+1,$y-3);
	$pdf->line($x+2,$y,$x+2,$y-1);
	$pdf->line($x+2,$y-2,$x+2,$y-3);
	$pdf->line($x+3,$y-1,$x+3,$y-2);
	$pdf->SetLineWidth(0.2);
}

// **************************************************************************************************
// Erstellt den Hintergrund für das Blatt (Bilder, Logo, Adresse, Rechnungsnummer
// **************************************************************************************************
function make_background($pdf) {
	$config = getConfig();
	// Bilder
	//Header + Logo Image
    $pdf->Image(USER_IMG.'/bil/header.jpg',0,0,210);
	//Footer
    $pdf->Image(USER_IMG.'/bil/footer.jpg',0,268.42,210);
	// Adresse Jan Schär
	$pdf->SetY(5);
	$pdf->SetFont('helvetica','B',16);
	$pdf->Cell(190,8,$config['myname'],0,1,'R',0);
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(190,4,'Projekt- und IT-Berater',0,1,'R',0);
	$pdf->Cell(190,4,'im Baumanagement',0,1,'R',0);
	$pdf->SetFont('helvetica','',9);
	$pdf->Cell(190,4,$config['strasse'].' '.$config['plz'].' '.$config['ort'],0,1,'R',0);
	$pdf->Cell(190,4,'Tel: '.$config['telefon'],0,1,'R',0);
	$pdf->SetFont('helvetica','I',9);
	$pdf->Cell(190,4,'e-mail: '.$config['email'],0,1,'R',0);
	$pdf->Cell(190,4,'internet: '.$config['www'],0,1,'R',0);
	// Rechnugsnummer
	$pdf->SetY(45);
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(190,4,'RECHNUNGSNR. # '.$GLOBALS['nr'],0,1,'R',0);
	$pdf->Cell(190,4,'Rechnung vom: '.$GLOBALS['bis'],0,1,'R',0);
}
function Abrechnungstabelle($header,$data, $pdf)
{
    //Colors, line width and bold font
    $pdf->SetFillColor(219,229,241);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(50,92,142);
    $pdf->SetFont('','B',9);
    //Header
    $w=array(23,87,26,27,28);
    $pdf->SetLineWidth(.6);
	$pdf->Cell(array_sum($w),0,'','T');  // Letzte Linie unten
	$pdf->Ln();
    $pdf->SetLineWidth(.1);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],10,$header[$i],1,0,'C',1);
    $pdf->Ln();
    //Color and font restoration
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('');
    //Data
    $fill=0;
	$total = 0;
    foreach($data as $row)
    {
        $pdf->Cell($w[0],6,$GLOBALS['bis'],'LR',0,'C',$fill);
        $pdf->Cell($w[1],6,$row['arbeitsbeschrieb'],'LR',0,'L',$fill);
        $pdf->Cell($w[2],6,number_format($row['nettoansatz'],2,",","`"),'LR',0,'R',$fill);
        $pdf->Cell($w[3],6,(round($row['total'],2)).' h','LR',0,'R',$fill);
		$pdf->Cell($w[4],6,number_format($row['nettoansatz']*$row['total'],2,",","`"),'LR',0,'R',$fill);
		$total+=$row['nettoansatz']*$row['total'];
        $pdf->Ln();
        $fill=!$fill;
    }
    $pdf->Cell(array_sum($w),0,'','T');  // Letzte Linie unten
	$pdf->Ln();
	$fill = 0;
		// Totale
        $pdf->Cell($w[0],6,'',0,0,'C',$fill);$pdf->Cell($w[1],6,'',0,0,'L',$fill);  // Leere Zellen
        $pdf->Cell($w[2]+$w[3],6,'Total ohne MWST','LRB',0,'R',$fill);
		$pdf->Cell($w[4],6,number_format($total,2,",","`"),'LRB',0,'R',$fill);
		$pdf->Ln();
        $pdf->Cell($w[0],6,'',0,0,'C',$fill);$pdf->Cell($w[1],6,'',0,0,'L',$fill);  // Leere Zellen
        $pdf->Cell($w[2]+$w[3],6,'MWST zum Satz von 7.6%','LRB',0,'R',$fill);
		$pdf->Cell($w[4],6,'keine MWST','LRB',0,'R',$fill);
		$pdf->Ln();
        $pdf->Cell($w[0],6,'',0,0,'C',$fill);$pdf->Cell($w[1],6,'',0,0,'L',$fill);  // Leere Zellen
		$pdf->SetFont('','B');
		$pdf->SetFillColor(255,204,153);
        $pdf->Cell($w[2]+$w[3],6,'Total zu bezahlen incl. MWST','LRB',0,'R',0);
		$pdf->Cell($w[4],6,number_format($total,2,",","`"),'LRB',0,'R',1);
		$pdf->Ln();
    return $total;
}


// **************************************************************************************************
// Erstellt den Stundenrapport
// **************************************************************************************************
function stundenrapport($pdf) {
	$db = $GLOBALS['db'];
	$pdf->AddPage('Portrait');	// Seite an PDF anfügen
	$pdf->SetAutoPageBreak(true, 20);
// **************************************************************************************************
	// Query für Stundenrapport
	$sql  = 'SELECT *, `rap_arbeit`.`name` AS `arbeitsbeschrieb` 
	FROM rap_stunden 
	LEFT JOIN rap_vertrag ON (`rap_stunden`.`vertrag`    = `rap_vertrag`.`ID`) 
	LEFT JOIN rap_arbeit  ON (`rap_stunden`.`arbeit`     = `rap_arbeit`.`ID`) 
	WHERE  
	`rap_vertrag`.`ID` = '.$GLOBALS['vertrag'].' AND 
	`rap_stunden`.`date` >= \''.$GLOBALS['von'].'\' AND 
	`rap_stunden`.`date` <= \''.$GLOBALS['bis'].'\'   
	ORDER BY `rap_stunden`.`date` ASC, `rap_stunden`.`von` ASC';
	if(!$db->query($sql)){echo '<br />'.$db->errormessage.'<br />'.$sql;}
	$stunden = array();
	while ($e = $db->results()) {$stunden[]=$e;}
// **************************************************************************************************
	make_background($pdf);
// **************************************************************************************************
	$pdf->SetY(50);
	$pdf->SetFont('','B',15);
	$pdf->Cell(190,10,'Stunderapport',0,1,'L',0);
	$pdf->SetFont('Arial','',9); 
		// Begin: Header
		$pdf->Cell(18,7,'Datum',1);
		$pdf->Cell(60,7,'Arbeit',1);
		$pdf->Cell(7,7,'h',1);
		$pdf->Cell(10,7,'von',1);
		$pdf->Cell(10,7,'bis',1);
		$pdf->Cell(86,7,'Kommentar',1);
		// Ende: Header
		//neue Zeile
		$pdf->Ln();
	// Liste der Stunden
	foreach($stunden as $entry) {
		// Begin: Auflistung
		$pdf->Cell(18,6, date_mysql2german($entry['date']),1);
		$pdf->Cell(60,6,utf8_decode($entry['arbeitsbeschrieb']),1);
		$pdf->Cell(7,6,$entry['stunden'],1);
		$pdf->Cell(10,6,substr($entry['von'],0,5),1);
		$pdf->Cell(10,6,substr($entry['bis'],0,5),1);
		$pdf->Cell(86,6,substr(utf8_decode($entry['comment']),0,90),1);
		// Ende: Auflistung
		$pdf->Ln();
	}
	$sql  = 'SELECT SUM(`rap_stunden`.`stunden`) AS `total` 
	FROM rap_stunden 
	LEFT JOIN rap_vertrag ON (`rap_stunden`.`vertrag`    = `rap_vertrag`.`ID`) 
	WHERE 
	`rap_vertrag`.`ID` = '.$GLOBALS['vertrag'].' AND 
	`rap_stunden`.`date` >= \''.$GLOBALS['von'].'\' AND 
	`rap_stunden`.`date` <= \''.$GLOBALS['bis'].'\'   
	LIMIT 0,1'; 
	$db->query($sql);
	$entry = $db->results();
		$pdf->Cell(18, 6, '',1);
		$pdf->Cell(60,6,'Total',1,'R');
		$pdf->Cell(7,6,round($entry['total'],2),1);
		$pdf->Cell(96,6,'',0);
	$pdf->SetAutoPageBreak(false);
}

?>