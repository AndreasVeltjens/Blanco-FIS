<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/qsdatenbank.php'); 
require_once('Connections/fedatenbank.php'); 

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rst4 = "SELECT * FROM projektdaten, projektklassen, projektdatendaten WHERE projektdaten.id_prj='$url_prj_id' AND projektdaten.id_prj= projektdatendaten.prj_id AND projektdaten.typ=projektklassen.id_prj_typ";
$rst4 = mysql_query($query_rst4, $fedatenbank) or die(mysql_error()); 
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM kundendaten WHERE kundendaten.idk = '$row_rst4[id_kundennummer]'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);



		include ('./libraries/fpdf/fpdf.php');
		
		$pdf =& new FPDF('P','mm','a4quer');
		$pdf->AddPage();
			
		$url_nummer=$row_rst1[kundenummer];
		if ($url_nummer==0 ){$url_nummer="1";}
include('picture/barcodeI25.php');
	
	$pdf->Image('barcode\testbild.png',22,150,18,55,"","");
		


		$pdf->SetFont('Helvetica','B',14);
		$pdf->Ln(10);
		$pdf->Write(5, 'Projekt: 00'.$row_rst4['id_prj']." - ".utf8_decode($row_rst4['prj_nummer']));
		$pdf->Ln();
		$pdf->SetFont('Helvetica','',14);
		
		
		$pdf->Image('picture\logo.jpg',23,10,50,15,"","");
	
		
		$pdf->SetFontSize(12);
		$pdf->Write(5,'Projektordner');
		$pdf->Ln(20);
	/* 	include("pdf_anschrift.tmpl.php"); */
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
		
		
		$pdf->Write(5,'Kunde');
		
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
		$pdf->Write(5,"Fax:".utf8_decode($row_rst1['faxnummer']));
		$pdf->Ln(5);
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Projekt: ');
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(10);

		$pdf->Write(4, utf8_decode(wordwrap(substr($row_rst4['prj_ueberschrift'],0,100),50,"\n",200)) );
			
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		$pdf->Write(13, 'Projekt-Nr: ');
		$pdf->Ln(5);
		$pdf->Write(13, utf8_decode($row_rst4['prj_nummer']));
		$pdf->Ln(5);
			
		$pdf->Write(13, "00".utf8_decode($row_rst4['prj_id']));
		
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(22,149,'Leipzig, '.date("d.m.Y",time()));
		
		/* if (($row_rst2['anrede'])=="0"){
		 $pdf->Write(5, 'Sehr geehrte Damen und Herren,'); }
		 else{
		$pdf->MultiCell(160,5,utf8_decode($row_rst1['anrede']),0,"","");
		}	
		$pdf->Ln(4);
		
	
		
		$pdf->Ln(1);
		$pdf->MultiCell(10,5,"anbei die Übersicht des aktuellen Projektstandes:",0,"","");
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
		$pdf->Cell(30,5,utf8_decode($row_rst4['datum']) ,0);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->MultiCell(100,4, utf8_decode((substr($row_rst4['Bezeichnung'],0,50))),0,"","");
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(33,5," ",0);
	 /* für jede Seite */
		 
		
	/* 	$pdf->SetFont('Helvetica','',8);
		$pdf->MultiCell(200,5, utf8_decode(wordwrap($row_rst4['beschreibung'])),0,"",""); */
		/* $pdf->Cell(33,5," ",0); */
		
		
		/* if ($row_rst4['Einzelpreis']>0){
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(170,5,$row_rst4['Einzelpreis']." € zzgl. MWSt.","","","R");
		}
		
		$pdf->Ln(5);
		$gruppe=$row_rst4['Gruppe'];
		} while ($row_rst4 = mysql_fetch_assoc($rst4)); */

		/* $pdf->MultiCell(0,4,utf8_decode($row_rst1['fm2notes']),0,"",""); */
		/* $pdf->SetFont('Helvetica','',10);

	if ($row_rst2['gvwl']==0){
		$pdf->MultiCell(10,5,"Zahlungsbedingungen: 14 netto.",0,"","");
		}else{
		$pdf->MultiCell(10,5," ",0,"","");
		}
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetFontSize(12);
		$pdf->Write(10, 'Mit freundlichen Grüßen ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->SetFontSize(10);
		if ($row_rst2['gvwl']==0){
		$pdf->Write(10, 'BLANCO CS Kunststofftechnik GmbH                              ');
		}else{
		$pdf->Write(10, 'BLANCO CS Kunststofftechnik GmbH');
		
		}
		
		$pdf->Ln(5);
		
		$pdf->SetFont('Helvetica','',8);
		$pdf->SetFontSize(8);

		$pdf->Write(10, 'i.A.                               i.A.  '); */ 




		
		

		

		$pdf->Output();
		exit;

?>