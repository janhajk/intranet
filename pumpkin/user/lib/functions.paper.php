<?
function printSheet($von, $bis, $fach, $spezial, $matrix=1) {
	$cellsize = 4;
	include_once(PMKADDONS.'/fpdf/fpdf.php');
	$pdf=new FPDF();
	for ($i=$von;$i<=$bis;$i++) {
			// neue Seite
			$pdf->AddPage();
			$pdf->SetFont('Arial','',10); 
				// Begin: Header
					$pdf->SetY(2);
					$pdf->SetFont('Arial','B',20);
					$pdf->Cell(0,10,$fach,0,0,'L');
							
			$pdf->SetFont('Arial','',10); 
				// Begin: Header
					$pdf->SetY(2);
					$pdf->SetFont('Arial','B',7);
					$pdf->Cell(0,10,'EDC_2010-xx-xx_'.$_SESSION['vorname'].'_FHNW_'.$fach.'_'.$spezial.'_p'.substr('000'.$i,strlen($i)),0,0,'R');
				// Ende: Header
			// erstellt die Häuschen für das Papier
			$pdf->SetY(0);
			//$pdf->SetX(10);
			$pdf->SetX(0);
			$pdf->SetDrawColor(200, 200, 200);
			if($matrix) {
				for($s=1;$s<=(264/$cellsize);$s++) {
				$pdf->Ln();
					for($r=1;$r<=(192/$cellsize);$r++) {
						$pdf->Cell($cellsize,$cellsize,'',1);
						}
				}
			}
	}	
	$pdf->SetDisplayMode('fullpage', 'single');
	$pdf->Output("blatt.pdf", "D");
}
?>