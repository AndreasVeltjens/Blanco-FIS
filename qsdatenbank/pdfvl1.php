<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM auslieferungen, artikeldaten WHERE auslieferungen.typ = 1  AND artikeldaten.Nummer=auslieferungen.nummer AND auslieferungen.lokz=0 and (auslieferungen.avis> 0) ORDER BY  artikeldaten.Gruppe asc , artikeldaten.bezeichnung asc";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);
?>
<?php require_once('Connections/qsdatenbank.php'); 



		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF();
		$pdf->AddPage();	
		$url_nummer="23443";
include('picture/barcodeI25.php');
	 $pdf->Image('barcode\testbild.png',5,10,18,55,"","");	
		  
	
		$pdf->SetFont('Helvetica','B',14);
		$pdf->Write(5, 'Lieferschein');
		$pdf->Ln();
		$pdf->SetFont('Helvetica','',14);
		$pdf->Image('picture\logo.jpg',140,10,50,15,"","");
	
	
		
		$pdf->SetFontSize(12);
		$pdf->Write(5, 'für eine Umlagerung ');
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
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(140,75,'Referenz: Nachschub Versandlager');
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(180,75,$row_rst1['fmid']);
		
		$pdf->SetFont('Helvetica','',10);
			$pdf->Text(140,80,'Ihre Kundennummer:');
			$pdf->SetFont('Helvetica','',12);
			$pdf->Text(180,80,"23443");
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(140,85,'Leipzig, '.date("d.m.Y",time()));
		
		
		
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Ln(10);
		$pdf->MultiCell(15,5,"BLANCO CS GmbH&Co.KG",10);
		$pdf->Ln(1);
		
		$pdf->MultiCell(15,5,"Wareneingang Nr. 1 / Werk 1",10);
		$pdf->Ln(2);
	
		$pdf->MultiCell(15,5, "Flehinger Str. 59",0);
		$pdf->Ln(2);
		$pdf->Write(5,"75038 Oberderdingen");
		$pdf->Ln(4);
		$pdf->Write(5,"Deutschland");
		$pdf->Ln(10);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Write(5,"Faxnummer: 07045 / 4481 646");
		$pdf->Ln(5);
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Betreff: Nachschub Versandlager BLANCOTHERM' );
		

		
	

	
		$pdf->Ln(15);
		
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		
	
		$pdf->Write(5, 'Sehr geehrte Damen und Herren,'); 
		
		
		$pdf->Ln(7);
		$pdf->MultiCell(10,5,"hiermit liefern wir folgende Positionen:",0,"","");

		$pdf->SetFont('Helvetica','',7);
		$pdf->Ln(3);
		$pdf->MultiCell(10,5,"Materialnummer Kunde               Materialnummer BCK + Bezeichnung                                                                  Menge                         Packstücke",0,"","");
		$pdf->Ln(2);
		
		do {
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(2,5," ",0);
		$pdf->Cell(5,5,"  ",1);
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(40,5,utf8_decode($row_rst1['Zeichnungsnummer']),0);
		$pdf->SetFont('Helvetica','',6);
		$pdf->Cell(10,5,utf8_decode($row_rst1['Nummer']),0);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->Cell(50,4, utf8_decode(($row_rst1['Bezeichnung'])),0,"","");
		
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(30,5,$row_rst1['avis']." Stück","","","R");
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(30,5,$row_rst1['palettenL']." Paletten","","","R");
		
		$summepaletten=$summepaletten+$row_rst1['palettenL'];
		$pdf->Ln(5);
		} while ($row_rst1 = mysql_fetch_assoc($rst1));

		/* $pdf->MultiCell(0,4,utf8_decode($row_rst1['fm2notes']),0,"",""); */
		$pdf->SetFont('Helvetica','',10);

	
		$pdf->MultiCell(10,5,"Bitte bestätigen Sie uns den Warenerhalt innerhalb der nächsten 2 Werktage ",0,"","");
	
		$pdf->MultiCell(10,5,"per Fax an 0341 4773000 oder monika.naumann@blanco.de . ",0,"","");
		$pdf->MultiCell(10,5,"Ware wurde geliefert auf $summepaletten Europaletten. ",0,"","");
		$pdf->Ln(4);
		/* $pdf->MultiCell(10,5,"EUROPALETTENTAUSCH     ja          /        nein                     Anzahl:   ________. ",0,"","");
		$pdf->Ln(4);
		$pdf->MultiCell(10,5,"Ware vollständig übergeben:     ja          /        nein                    ",0,"","");
		$pdf->Ln(4);
		$pdf->MultiCell(10,5,"Spedition: 	 __________________________    Fahrer:  _____________        Kennzeichen: _________. ",0,"","");
	 */
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetFontSize(12);
		$pdf->Write(10, 'Mit freundlichen Grüßen ');
		$pdf->Ln(3);
		$pdf->SetFont('Helvetica','B',11);
		$pdf->SetFontSize(10);
		$pdf->Ln(2);
		$pdf->Write(10, 'BLANCO CS Kunststofftechnik GmbH                              ');
		
		$pdf->SetFont('Helvetica','',8);
		$pdf->Ln(5);
		$pdf->Write(10, 'Dieses Schreiben wurde maschinell erstellt und ist auch ohne Unterschrift gültig.  ');


		$pdf->Output();
		exit;

?>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);
?>


