<?php require_once('Connections/qsdatenbank.php'); ?><?php
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM fhm_ereignis  WHERE fhm_ereignis.id_fhme='$url_fhme_id'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);



$lieferung=utf8_decode($row_rst1['firma']);
$strasse=utf8_decode($row_rst1['strasse']);
$ort=utf8_decode($row_rst1['plz'])." ".utf8_decode($row_rst1['ort']);
$land=utf8_decode($row_rst1['land']);

		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF('P','mm','a5');
		$pdf->AddPage();
		$pdf->SetDisplayMode('real');
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(200);
		

		
		$pdf->Ln(0);
		$i=0;
		$kopie=-10;
		do {
		
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(60,68+$kopie,'170070');

		$pdf->Text(80,68+$kopie,'Speisetransportbehälter');
		$pdf->Text(80,71+$kopie,'Fehlermeldenummer:');
		$pdf->Text(120,71+$kopie,$row_rst1['fmid']);
		
		$pdf->Text(80,88+$kopie,'BLANCO CS Kunststofftechnik GmbH');
		$pdf->Text(80,91+$kopie,'Werkstättenstraße 31');
		$pdf->Text(80,94+$kopie,'04319 Leipzig');
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(60,78+$kopie,'_');/* Packstücke */
		$pdf->Text(60,90+$kopie,'á '.$row_rst3['gewicht'].' kg');/* Gewicht */
		$pdf->Text(57,101+$kopie,date("d.m.Y",time()) );
		
		
		
	$pdf->SetFont('Helvetica','B',10);
		$pdf->Ln(2);
		$pdf->Text(62,30+$kopie, substr($lieferung,0,43));
		$pdf->Ln(5);
		$pdf->Text(62,34+$kopie, substr($lieferung,44,43));
		$pdf->Ln(4);
		$pdf->Text(62,38+$kopie, substr($lieferung,87,43));
		$pdf->Ln(4);
		$pdf->Text(62,45+$kopie, $strasse);
		$pdf->Ln(4);
		$pdf->Text(62,49+$kopie,$ort);
		$pdf->Ln(4);
		$pdf->Text(70,56+$kopie,$land);
		$pdf->Ln(25);
		$kopie=100;
		$i=$i+1;
		} while ($i<2);
		$pdf->SetFont('Helvetica','B',8);
		$pdf->text(5,102, '                             Versandschein für GLS einer Reparatur');
		$pdf->SetFont('Helvetica','',8);
		$pdf->Image('picture\logo.jpg',2,100,20,5,"","");

		$pdf->Output();
		exit;

?>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>

