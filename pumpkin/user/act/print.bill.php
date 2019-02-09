<?php
// **************************************************************************************************
	//Lib laden
	include($_SERVER['DOCUMENT_ROOT'].'/config.php');
// **************************************************************************************************
	require_once(PMKADDONS.'/fpdf/fpdf.php');
// **************************************************************************************************
	class PDF extends FPDF
	{
	function Circle($x, $y, $r, $style='')
	{
		$this->Ellipse($x, $y, $r, $r, $style);
	}

	function Ellipse($x, $y, $rx, $ry, $style='D')
	{
		if($style=='F')
			$op='f';
		elseif($style=='FD' or $style=='DF')
			$op='B';
		else
			$op='S';
		$lx=4/3*(M_SQRT2-1)*$rx;
		$ly=4/3*(M_SQRT2-1)*$ry;
		$k=$this->k;
		$h=$this->h;
		$this->_out(sprintf('%.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c',
			($x+$rx)*$k, ($h-$y)*$k,
			($x+$rx)*$k, ($h-($y-$ly))*$k,
			($x+$lx)*$k, ($h-($y-$ry))*$k,
			$x*$k, ($h-($y-$ry))*$k));
		$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
			($x-$lx)*$k, ($h-($y-$ry))*$k,
			($x-$rx)*$k, ($h-($y-$ly))*$k,
			($x-$rx)*$k, ($h-$y)*$k));
		$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
			($x-$rx)*$k, ($h-($y+$ly))*$k,
			($x-$lx)*$k, ($h-($y+$ry))*$k,
			$x*$k, ($h-($y+$ry))*$k));
		$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c %s',
			($x+$lx)*$k, ($h-($y+$ry))*$k,
			($x+$rx)*$k, ($h-($y+$ly))*$k,
			($x+$rx)*$k, ($h-$y)*$k,
			$op));
	}
    function SetDash($black=false, $white=false)
    {
        if($black and $white)
            $s=sprintf('[%.3f %.3f] 0 d', $black*$this->k, $white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }
	}
// **************************************************************************************************
	// config
	$config = getConfig();
// **************************************************************************************************
	// Übergebene Variablen
	$nr = $_GET['nr'];
	// Datum von letzter Rechnung
	$sql = "SELECT * FROM `rap_bills` WHERE `id` = $nr LIMIT 1";
	$db->query($sql);
	while($l = $db->results()) {
		$von = $l['von'];  // Datum von letzter Rechnung
		$bis = $l['bis'];  // Datum von letzter Rechnung
		$vertrag = $l['vertrag'];
	}	
// **************************************************************************************************
	// neues PDF-Dokument (/-Objekt) erstellen
	$pdf=new PDF();
	$pdf->AddPage();
	// automatischer Seitenumbruch deaktivieren
	$pdf->SetAutoPageBreak(false);
// **************************************************************************************************	
	// Information über Vertrag abrufen (Adresse, Titel, usw.)
	$sql = "SELECT * FROM `rap_vertrag` WHERE `ID` = $vertrag LIMIT 0,1";
	$db->query($sql);
	$vertragsinfos = $db->results();
// **************************************************************************************************
// Rechnugsblatt formatieren
// **************************************************************************************************
	make_background($pdf);
	//Unterschrift
    $pdf->Image(USER_IMG.'/bil/JOB_xxx-xx-xx_jan_unterschrift.png',18,240,45);
	// Text
	$pdf->SetY(100);
	$pdf->SetFont('helvetica','',9);
	$pdf->Cell(190,5,$config['hello'].',',0,1,'L',0);
	$pdf->Cell(190,5,$config['text'].' \''.$vertragsinfos['arbeitstitel'].'\'',0,1,'L',0);
	$pdf->SetY(230);
	$gruss = explode("%%",$config['gruss']);
	$pdf->Cell(190,5,$gruss[0],0,1,'L',0);
	$pdf->Cell(190,5,$gruss[1],0,1,'L',0);
	$pdf->SetY(265);
	$pdf->Cell(190,5,$config['myname'],0,1,'L',0);
	// Kontoinformation
	$pdf->SetFont('helvetica','',8);
	$pdf->Text(130,260,'Bitte den Betrag innerhalb von 30 Tagen');
	$pdf->Text(130,264,'auf folgendes Konto einzahlen:');
	$pdf->Text(130,268,$config['myname'].', '.$config['strasse'].', '.$config['plz'].' '.$config['ort']);
	$pdf->Text(130,272,'IBAN: '.$config['iban']);
// **************************************************************************************************
	// Empfänger
	$pdf->SetY(55);
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(190,3,'RECHNUNG AN:',0,1,'L',0);
	$pdf->SetY(60);
	$pdf->SetX(40);$pdf->SetFont('helvetica','',12);
	$pdf->SetX(40);$pdf->Cell(190,5,$vertragsinfos['name'],0,1,'L',0);
	$pdf->SetX(40);$pdf->Cell(190,5,$vertragsinfos['firma'],0,1,'L',0);
	$pdf->SetX(40);$pdf->Cell(190,5,$vertragsinfos['strasse'],0,1,'L',0);
	$pdf->SetX(40);$pdf->Cell(190,5,$vertragsinfos['ort'],0,1,'L',0);
// **************************************************************************************************
// Tabelle mit Stunden und Beträgen
// **************************************************************************************************
	$header = array('DATUM', 'BESCHREIBUNG', 'EHP', 'MENGE', 'KOSTEN CHF');
// **************************************************************************************************
	// Daten für die Arbeitsbeschriebgruppierten Zeilen
	$data = array();
	// SQL zum Auswählen von den Stunden, Gruppiert nach dem Arbeitsbeschrieb
	$sql  = 'SELECT rap_stunden.date,
            rap_arbeit.name AS arbeitsbeschrieb,
            rap_vertrag.nettoansatz,
            SUM(rap_stunden.stunden) AS total
	FROM rap_stunden
	LEFT JOIN rap_vertrag ON (rap_stunden.vertrag = rap_vertrag.ID)
	LEFT JOIN rap_arbeit  ON (rap_stunden.arbeit  = rap_arbeit.ID)
	WHERE
	rap_vertrag.ID = '.$vertrag.' AND
	rap_stunden.date >= \''.$von.'\' AND
	rap_stunden.date <= \''.$bis.'\'
	GROUP BY rap_arbeit.name
	ORDER BY rap_stunden.date DESC';
	 echo $sql;
	if (!$db->query($sql)) {
		echo '<p>'$db->errormessage.'</p><p>'.$sql.'</p>';
		exit();
	}
	while($l = $db->results()) {
		$data[] = $l;
	}
	$pdf->SetY(115);
	$totalbetrag = Abrechnungstabelle($header,$data,$pdf);
	stundenrapport($pdf);
	einzahlungsschein($pdf, $totalbetrag);
// **************************************************************************************************
// Ausgabe
// **************************************************************************************************
	// Ansicht der Ausgabe
	$pdf->SetDisplayMode('fullpage', 'single');
	// Dateiname zusammensetzen
	$filename = 'JOB_'.date("Y-m-d").'_BIL_'.(str_replace(" ", "",$vertragsinfos['firma'])).'_No-'.$nr.'.pdf';
	// zur Ansicht ausgeben
	$pdf->Output($filename,'D');
// **************************************************************************************************
	//Lib schliessen
	endlib();			// from lib/lib.php
// **************************************************************************************************
?>