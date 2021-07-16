<?php require_once('Connections/psdatenbank.php'); ?>
<?php require_once('Connections/qsdatenbank.php'); 


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT * FROM `user` WHERE `user`.id='$url_userid'";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst2 = "SELECT * FROM pramien WHERE pramien.pra_id='$url_praid'";
$rst2 = mysql_query($query_rst2, $psdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);


		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF();
		$pdf->AddPage();

		$pdf->SetFont('Helvetica','B',14);
		$pdf->Write(5, 'Prämienlohnbescheinigung');
		$pdf->Ln();
		$pdf->SetFont('Helvetica','',14);
		$pdf->Image('picture\logo.jpg',140,10,50,15,"","");
		$pdf->SetFontSize(12);
		$pdf->Write(5, 'für Zeitraum '.utf8_decode($row_rst2['von'])." bis ".utf8_decode($row_rst2['bis']));
		$pdf->Ln(20);
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Text(140,30,' ');
		$pdf->SetFont('Helvetica','B',8);
		$pdf->Text(140,35,'BLANCO CS Kunststofftechnik GmbH');
		$pdf->SetFont('Helvetica','',8);
		$pdf->Text(140,40,'Werkstättenstraße 31');
		$pdf->Text(140,43,'04319 Leipzig- Engelsdorf');
		$pdf->Text(140,50,'Tel.: 0341 / 4773088');
		$pdf->Text(140,53,'Fax : 0341 / 4773000');
		
		$pdf->Text(140,60,'');
		$pdf->Text(140,63,'');
		
		$pdf->Text(140,66,'E-Mail: andreas.saupe@blanco.de');
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(140,77,'Bewertung');
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(180,77,$row_rst1['fmid']);
		
		
		$pdf->Text(140,83,'Leipzig, '.date("d.m.Y",time()));
		
		$pdf->SetFont('Helvetica','',6);
		$pdf->Text(22,52,'BLANCO CS Kunststofftechnik GmbH - Werkstättenstraße 31 - 04319 Leipzig');
		
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Ln(10);
		$pdf->MultiCell(15,5,utf8_decode($row_rst6['anrede']),10);
		$pdf->Ln(1);
		if ((strlen($row_rst6['name']))>1){
		$pdf->MultiCell(15,5,utf8_decode($row_rst6['name']),10);
		$pdf->Ln(2);
		}
		$pdf->MultiCell(15,5, utf8_decode($row_rst6['anschrift']),0);
		$pdf->Ln(2);
		$pdf->Write(5,utf8_decode($row_rst6['plz'])." ".utf8_decode($row_rst6['ort']));
		$pdf->Ln(4);
		$pdf->Write(5,utf8_decode($row_rst6['land']));
		$pdf->Ln(10);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Write(5,utf8_decode($row_rst1['faxnummer']));
		$pdf->Ln(15);
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Prämienlohn für Zeitraum '.utf8_decode($row_rst2['von'])." bis ".utf8_decode($row_rst2['bis']));
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(5);
		
	
		$pdf->Ln(20);
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->SetFontSize(12);
		
		if (($row_rst6['anrede2'])==""){
		 $pdf->Write(5, 'Sehr geehrte(r) Mitarbeiter(in),'); }
		 else{
		$pdf->MultiCell(160,5,utf8_decode($row_rst6['anrede2'])." ".utf8_decode($row_rst6['name']).",",0,"","");
		}	
		$pdf->Ln(8);
		
		$pdf->MultiCell(160,5,'hiermit danken wir Ihnen und honorieren Ihre persönliche Arbeit mit ',0,"","");
		$pdf->MultiCell(160,5,'einer variablen Prämie in Höhe von (brutto)',0,"","");
		 
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->SetFontSize(12);
		$pdf->MultiCell(100,5,"                                              €  ".$url_pramie,0,"","");
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->SetFontSize(12);
		$pdf->Ln(8);
		$pdf->MultiCell(160,5,'Die Auszahlung erfolgt vereinbarungsgemäß mit Ihrer nächsten ',0,"","");
		$pdf->MultiCell(160,5,'Lohnabrechnung.',0,"","");
		
		
		$pdf->SetFont('Helvetica','',10);

	
		$pdf->MultiCell(10,5,"Aus dieser Zahlung entstehen keine Ansprüche auf zukünftige Prämienzahlungen.",0,"","");
		
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->SetFontSize(12);
		$pdf->Write(13, 'Mit freundlichen Grüßen ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->SetFontSize(12);
		$pdf->Write(13, 'BLANCO CS Kunststofftechnik GmbH ');
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetFontSize(12);
		$pdf->Write(10, 'i.V. ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Write(10, 'Werkleiter');
		$pdf->Ln(10);

		$pdf->Output();
		exit;

?>