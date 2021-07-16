<?php require_once('Connections/qsdatenbank.php'); 

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM kundendaten WHERE kundendaten.idk='$url_idk' ";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if (isset($url_artikelid) && ($url_artikelid>0)) {$stxt=" artikelid='".$url_artikelid."' ";}
else{ $stxt=" (Kontierung='Fert' or Kontierung='Ersatzteile' or Kontierung='Fertigware') ";}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE $stxt ORDER BY Kontierung, Gruppe, Bezeichnung";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF();
		$pdf->AddPage();	
		$url_nummer=$row_rst1[kundenummer];
include('picture/barcodeI25.php');
	if (isset($url_artikelid) && ($url_artikelid>0)) {$stxt="Materialdatenblatt ";}else{$stxt="Transferpreise ";}
	
		$pdf->SetFont('Helvetica','B',14);
		$pdf->Write(5, $stxt);
		$pdf->Ln();
		$pdf->SetFont('Helvetica','',14);
		
	
	
		
		$pdf->SetFontSize(12);
		
		$pdf->Write(5,'Stand '.date("Y-m-d",time()));
		$pdf->Ln(20);
		include("pdf_anschrift.tmpl.php");
	/* 	$pdf->SetFont('Helvetica','B',10);
		$pdf->Text(140,30,'Retourenabwicklung');
		$pdf->SetFont('Helvetica','B',8);
		$pdf->Text(140,35,'BLANCO CS Kunststofftechnik GmbH');
		$pdf->SetFont('Helvetica','',8);
		$pdf->Text(140,40,'Werkstättenstraße 31');
		$pdf->Text(140,43,'04319 Leipzig- Engelsdorf');
		$pdf->Text(140,50,'Tel.: 0341 / 4773088');
		$pdf->Text(140,53,'Fax : 0341 / 4773000');
		
		$pdf->Text(140,60,'Ihr Ansprechpartner: Herr Günther Tauber');
		$pdf->Text(140,63,'Tel.: 0341/65229424');
		
		$pdf->Text(140,66,'E-Mail: guenther.tauber@blanco.de'); */
		/* $pdf->SetFont('Helvetica','',10);
		$pdf->Text(140,75,'Fehlermeldenummer:');
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(180,75,$row_rst1['fmid']);
		if ($row_rst1['debitorennummer']>0){
		$pdf->SetFont('Helvetica','',10);
			$pdf->Text(140,80,'Ihre Kundennummer:');
			$pdf->SetFont('Helvetica','',12);
			$pdf->Text(180,80,$row_rst1['debitorennummer']);
			} */
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(140,85,'Leipzig, '.date("d.m.Y",time()));
		
		
		
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Ln(10);
		$pdf->MultiCell(15,5,utf8_decode($row_rst1['firma']),10);
		$pdf->Ln(1);
		if ((strlen($row_rst1['ansprechpartner']))>1){
		$pdf->MultiCell(15,5,utf8_decode(utf8_decode($row_rst1['ansprechpartner'])),10);
		$pdf->Ln(2);
		}
		$pdf->MultiCell(15,5, utf8_decode($row_rst1['strasse']),0);
		$pdf->Ln(2);
		$pdf->Write(5,utf8_decode($row_rst1['plz'])." ".utf8_decode($row_rst1['ort']));
		$pdf->Ln(4);
		$pdf->Write(5,utf8_decode($row_rst1['land']));
		$pdf->Ln(10);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Write(5,"Faxnummer:".utf8_decode($row_rst1['faxnummer']));
		$pdf->Ln(5);
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Betreff: '.$stxt);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(5);
		
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		
		if (($row_rst2['anrede'])=="0"){
		 $pdf->Write(5, 'Sehr geehrte Damen und Herren,'); }
		 else{
		$pdf->MultiCell(160,5,utf8_decode($row_rst1['anrede']),0,"","");
		}	
		$pdf->Ln(4);
		
	
		
		$pdf->Ln(1);
		$pdf->MultiCell(10,5,"anbei die Übersicht der Materialdaten",0,"","");
		$pdf->Ln(5);
		
		do {
		if ($gruppe!=$row_rst4['Gruppe']){
		$pdf->SetFont('Helvetica','B',11);
		$pdf->SetFontSize(11);
		$pdf->Ln(1);
		$pdf->MultiCell(10,5,utf8_decode($row_rst4['Gruppe']),0,"","");
		$pdf->Ln(5);
		}
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(3,3,"",1);
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(30,5,utf8_decode($row_rst4['Nummer']) ,0);

		
		$pdf->SetFont('Helvetica','',10);
		$pdf->MultiCell(35,4, utf8_decode((substr($row_rst4['Bezeichnung'],0,50))),0,"","");
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(33,5," ",0);
	 /* für jede Seite */
		 $pdf->Image('barcode\testbild.png',0,0,18,55,"","");
		$pdf->Image('picture\logo.jpg',140,10,50,15,"","");
		
		$pdf->SetFont('Helvetica','',8);
		$pdf->MultiCell(80,5, utf8_decode(wordwrap($row_rst4['Beschreibung'])),0,"","");
		$pdf->Cell(33,5," ",0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->MultiCell(80,5,"EAN-Code: ".utf8_decode(wordwrap($row_rst4['ean13'])."   Traglastangabe: ".$row_rst4['traglast']),0,"","");
		
		$pdf->Cell(33,5," ",0);
		$pdf->SetFont('Helvetica','',8);
		$pdf->MultiCell(80,5,utf8_decode("Gewicht: ".wordwrap($row_rst4['gewicht']."   Kennzeichnung/Logo: ".$row_rst4['pruefzeichen']." - ".$row_rst4['leistungsangabe'])),0,"","");
		
		
		
		if ($row_rst4['Einzelpreis']>0){
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(170,5,$row_rst4['Einzelpreis']." € zzgl. MWSt.","","","R");
		}
		
		$pdf->Ln(10);
		$gruppe=$row_rst4['Gruppe'];
		$materialbild="..\\documents\materialbilder\\".$row_rst4['Nummer'].".png";
		$pdf->Image($materialbild,140,90,60,40,"","");
		} while ($row_rst4 = mysql_fetch_assoc($rst4));

		/* $pdf->MultiCell(0,4,utf8_decode($row_rst1['fm2notes']),0,"",""); */
		$pdf->SetFont('Helvetica','',10);

	
		
		

		

		$pdf->Output();
		exit;

?>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);
?>


