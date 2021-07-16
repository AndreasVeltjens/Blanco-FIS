<?php require_once('Connections/qsdatenbank.php'); 
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM kundendaten WHERE kundendaten.idk=$url_idk";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF('P','mm','e10x10');
		$pdf->AddPage();
		$pdf->SetDisplayMode('real');
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(200);
		$pdf->Image('picture/logo.jpg',2,3,20,5,"","");
		$pdf->SetFont('Helvetica','',6);
		$pdf->Text(35,6,"BLANCO CS Kunststofftechnik - Werkstättenstraße 31 - 04319 Leipzig");
		$url_nummer=$row_rst1[fmid];
				
		$pdf->SetFont('Helvetica','B',10);
		
		$pdf->Text(2,18, utf8_decode(utf8_decode($row_rst1['firma'])));
		$pdf->Ln(4);
		$pdf->SetFont('Helvetica','',10);
	if ((strlen($row_rst1['ansprechpartner']))>1){
		$pdf->Text(2,25,utf8_decode(utf8_decode($row_rst1['ansprechpartner'])));
		$pdf->Ln(4);
		}
		$pdf->MultiCell(0,0, utf8_decode(utf8_decode($row_rst1['strasse'])),5);
		$pdf->Ln(4);
		$pdf->Write(5,utf8_decode($row_rst1['plz'])." ".utf8_decode(utf8_decode($row_rst1['ort'])) );
		$pdf->Ln(4);
		$pdf->Write(5,utf8_decode(utf8_decode($row_rst1['land'])));
		
	
		$pdf->Output();
		exit;

?>
<?php
mysql_free_result($rst1);
?>

