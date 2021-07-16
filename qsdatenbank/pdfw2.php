<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); 



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.name = '$url_cus_id'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);


$url_user_id=$url_user;

mysql_select_db($database_nachrichten, $nachrichten);
$query_rst2 = "SELECT workflow.id_work, workflow.name, workflow.beschreibung, workflow.erstellt, workflow.datum, workflow.ursache,  workflow.status, workflow.param, workflow.von, workflow.bis, workflowdaten.id_work_d, workflowdaten.beschreibung, workflowdaten.datum, workflowdaten.`user`, workflowdaten.id_work FROM workflow, workflowdaten WHERE workflow.id_work=workflowdaten.id_work AND workflow.id_work = '$url_id_work' ORDER BY workflowdaten.datum asc";
$rst2 = mysql_query($query_rst2, $nachrichten) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rst3 = "SELECT * FROM workflow WHERE workflow.id_work='$url_id_work'";
$rst3 = mysql_query($query_rst3, $nachrichten) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF();
		$pdf->AddPage();	
		$url_nummer=$row_rst3['id_work'];
include('picture/barcodeI25.php');
	
	
		$pdf->SetFont('Helvetica','B',14);
		$pdf->Write(5, 'Meldung: 00'.$row_rst2['id_work']." - ".utf8_decode($row_rst2['name']));
		$pdf->Ln();
		$pdf->SetFont('Helvetica','',14);
		
	
	
	switch ($row_rst2['bereich']){
	case 1:$dokumentart="Verbesserungsvorschlag";break;
	case 2:$dokumentart="Verbesserungsvorschlag";break;
	case 3:$dokumentart="Verbesserungsvorschlag";break;
	case 4:$dokumentart="Verbesserungsvorschlag";break;
	case 5:$dokumentart="Fehlzeitmeldung";break;
	case 6:$dokumentart="Verbesserungsvorschlag";break;
	}
		$pdf->SetFontSize(12);
		$pdf->Write(5,$dokumentart);
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
	
		$pdf->MultiCell(15,5,utf8_decode(utf8_decode($row_rst1['name'])),10);
		$pdf->Ln(2);
	
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
		$pdf->Write(13, 'Betreff: '.utf8_decode($row_rst3['ursache']));
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(5);
		$pdf->Write(13, 'von: '.utf8_decode($row_rst3['von'])." bis ".utf8_decode($row_rst3['bis']));
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
		$pdf->MultiCell(10,5,"anbei die Übersicht zur Meldung:",0,"","");
		$pdf->Ln(5);
		
		do {
		if ($gruppe!=$row_rst2['Gruppe']){
		$pdf->SetFont('Helvetica','B',11);
		$pdf->SetFontSize(11);
		$pdf->Ln(1);
		$pdf->MultiCell(10,5,utf8_decode($row_rst2['Gruppe']),0,"","");
		$pdf->Ln(5);
		}
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(3,3,"",1);
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(30,5,utf8_decode($row_rst2['datum']) ,0);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->MultiCell(100,4, utf8_decode((substr($row_rst2['user'],0,50))),0,"","");
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(33,5," ",0);
	 /* für jede Seite */
		 $pdf->Image('barcode\testbild.png',0,0,18,55,"","");
		$pdf->Image('picture\logo.jpg',140,10,50,15,"","");
		
		$pdf->SetFont('Helvetica','',8);
		$pdf->MultiCell(200,5, utf8_decode(wordwrap($row_rst2['beschreibung'])),0,"","");
		/* $pdf->Cell(33,5," ",0); */
		
		
		
		
		
		$pdf->Ln(5);
		$gruppe=$row_rst3['Gruppe'];
		
		} while ($row_rst2 = mysql_fetch_assoc($rst2));

		/* $pdf->MultiCell(0,4,utf8_decode($row_rst1['fm2notes']),0,"",""); */
		$pdf->SetFont('Helvetica','',10);

	
		
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

		$pdf->Write(10, 'i.A.                               i.A.  ');




		
		

		

		$pdf->Output();
		exit;

?>