<?php require_once('Connections/qsdatenbank.php');

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM fhm_ereignisart ORDER BY fhm_ereignisart.name";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_fhme='$url_id_fhme' ";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM fhm WHERE fhm.id_fhm='$row_rst3[id_fhm]'";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$row_rst5[artikelid]'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF();
		$pdf->AddPage();	
		$url_nummer=$row_rst3['id_fhme'];/* Übergabe der Dokumentnummer an Barcode */
		$url_sn_ab=$row_rst3['id_fhme'];
		if (strlen($url_sn_ab)==1){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){		
			$url_nummer="00".$url_sn_ab;}
			else{
			$url_nummer="0".$url_sn_ab;
		}
		
		
		include('picture/barcodeI25.php'); 
	 	$pdf->Image('barcode/testbild.png',5,10,18,55,"","");	
		  
	
		$pdf->SetFont('Helvetica','B',14);
		
		do {  /* Dokumentart */
 if (!(strcmp($row_rst4['id_fhmea'], $row_rst3['id_fhmea']))) {$pdf->Write(5, (utf8_decode(($row_rst4['name']))) );
 
 $dokumentart=utf8_decode(($row_rst4['name']));
 
 }
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
		
		$pdf->Ln();
		$pdf->SetFont('Helvetica','',14);
		 $pdf->Image('picture/logo.jpg',140,10,50,15,"","");
	 
	
		
		$pdf->SetFontSize(12);
		$pdf->Write(5, ' ');/* Dokumentartzusatz */
		$pdf->Ln(20);
		
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Text(140,30,''); /* Dokumentartzusatz */
		$pdf->SetFont('Helvetica','B',8);
		
		
		
include("pdf_anschrift.tmpl.php");		
		
		$pdf->SetFont('Helvetica','',10);
				
				
					if ($row_rst3['id_fhmea']==14) {
						$pdf->Text(140,77,"Lieferschein-Nr: " );
						$pdf->Text(140,83,"Paket-Versand-Nr: ".$row_rst3['versandlabel'] );
						} else{
						 $pdf->Text(140,77,$dokumentart."-Nr: " );
					 }
				
			

  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
		/* Datum */
		$pdf->Text(140,100,utf8_decode($row_rstuser['ort']).', '.$row_rst3['datum']); 
		$pdf->Text(140,105,'Referenznummer: '.$row_rst3['id_contact']); 
		$pdf->Text(140,110,'Inventar:       '.$row_rst5['id_fhm']); 
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(180,77,$url_nummer);
		
		
		
		if ($row_rst3['kundenreferenz']!=""){
		$pdf->Text(140,82,"Ihre Bestell-Nr.: ".(utf8_decode($row_rst3['kundenreferenz'])));
		}
		
		
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Ln(10);
		$pdf->MultiCell(15,5,(utf8_decode($row_rst3['firma'])),10);
		$pdf->Ln(1);
		if ((strlen($row_rst3['ansprechpartner']))>1){
		$pdf->MultiCell(15,5,(utf8_decode($row_rst3['ansprechpartner'])),10);
		$pdf->Ln(2);
		}
		$pdf->MultiCell(15,5, (utf8_decode($row_rst3['strasse'])),0);
		$pdf->Ln(2);
		$pdf->Write(5,(utf8_decode($row_rst3['plz']))." ".(utf8_decode($row_rst3['ort'])));
		$pdf->Ln(4);
		$pdf->Write(5,utf8_decode(utf8_decode($row_rst3['land'])));
		$pdf->Ln(10);
		if ($row_rst3['id_fhmea']!=14) { /* Anfang Anrede */
			$pdf->SetFont('Helvetica','',10);
			$pdf->Write(5,"per Fax an:".utf8_decode(utf8_decode($row_rst3['faxnummer'])));
		}
		$pdf->Ln(5);
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, ' '.utf8_decode(utf8_decode($row_rst3['headertxt'])));
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(20);

if ($totalRows_rst2>0){

		switch ($row_rst3['id_fhmea']) {
		case 14 :
			$pdf->Ln(5);
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("Sehr geehrte Damen und Herren,\n\ngemäß unserer Absprache übersenden wir Ihnen heute leihweise:")),0,"","");
		break;
		}


 $pdf->Ln(4);		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
		$pdf->Ln(4);
		$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
	
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer    : '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));

	/* if (($row_rst1['fm12notes'])!=="0"){ 
		$pdf->Ln(4);
		$pdf->Write(13, 'Kommision            : '.utf8_decode($row_rst1['fm12notes']));
		
	} */
	}
	
/* Lieferadresse begin 
		$pdf->Ln(4);
		if ($row_rst1['lstrasse']==""){$row_rst1['lstrasse']="siehe Anschrift";}
		$pdf->Write(13, 'Lieferung an   : ');
		$pdf->Write(13, utf8_decode(utf8_decode($row_rst1['lieferung'])));
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode(utf8_decode($row_rst1['lstrasse']."  ")));
		$pdf->Write(13, utf8_decode(utf8_decode($row_rst1['lplz']))."  ".utf8_decode(utf8_decode($row_rst1['lort']))."   ".utf8_decode(utf8_decode($row_rst1['lland'])));
	$pdf->Ln(4);
		
	Lieferadresse ende */
	
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->SetFontSize(11);
		
		if ($row_rst3['id_fhmea']!=14) { /* Anfang Anrede */
				if (($row_rst3['anrede'])==""){
				 $pdf->Write(5, 'Sehr geehrte Damen und Herren,'); }
				 else{
				$pdf->MultiCell(160,5,utf8_decode(utf8_decode($row_rst3['anrede'])),0,"","");
				}
		} /* ende Anrede  */	
		$pdf->Ln(12);
		/* Textbausteinde */
		switch ($row_rst3['id_fhmea']) {
		case 1 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit melden wir Ihnen folgende Reparatur gemäß unserer AGB's:")),0,"","");
			
			$pdf->Ln(4);		
		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
	
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
			
			
		break;
		case 2 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit melden wir Ihnen folgende Wartung gemäß unserer AGB's:")),0,"","");
			$pdf->Ln(4);		
		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
	
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;
		case 3 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit melden wir Ihnen folgende Instandsetzung gemäß unserer AGB's:")),0,"","");
		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;
		case 4 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit melden wir Ihnen folgende Kalibrierung gemäß unserer AGB's:")),0,"","");
		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;
		case 5 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit melden wir Ihnen folgende Neuanschaffung zur Aufnahme in die Inventarliste:")),0,"","");
		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;
		case 6 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit melden wir Ihnen folgende Störung:")),0,"","");
		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;
		case 7 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit melden wir Ihnen folgende Verschrottung und Löschung aus der Inventarliste:")),0,"","");
		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;
		case 8 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit melden wir Ihnen folgende Stilllegung ")),0,"","");
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;		
		case 9 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit liefern wir Ihnen folgende Leistungen gemäß unserer AGB's:")),0,"","");
		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;
		
		case 10 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit teilen wir Ihnen mit:")),0,"","");
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);
	$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		break;
		case 12 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit teilen wir Ihnen die Einlagerung folgender Fertigungshilfsmittel mit:")),0,"","");
			$pdf->Ln(4);		
		$pdf->Write(13, 'Name	            	: '.urldecode($row_rst5['name']."        "));
	$pdf->Ln(4);$pdf->Write(13, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(13, 'Inventarnummer 	: '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));
		
		break;
		
		}
		$pdf->Ln(2);
	/* 	$pdf->Ln(1);
		$pdf->MultiCell(10,5,"Bitte wählen Sie aus folgenden Varianten:",0,"","");
		$pdf->Ln(5); */
		
		/* $pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(5,5,"",1); */
		/* Kasten zum Ankreuzen */
		
		if (($row_rst3['id_fhmea']!=14) or ($row_rst3['id_fhmea']!=15)) { /* Anfang Anrede */
				$pdf->Ln(6);
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(15,4, "Grund:" ,0);
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(20,4,utf8_decode(utf8_decode($row_rst3['material'])) ,0);
				
				$pdf->MultiCell(80,4, (utf8_decode(($row_rst3['beschreibung']))),0,"","");
				
			} /* ende Anrede  */	
		
		
		$pdf->SetFont('Helvetica','',10);

	
		/* $pdf->MultiCell(10,5,"Bitte anworten Sie innerhalb von 7 Tagen auf dieses Fax und kreuzen Sie entsprechende Variante an.",0,"","");
		 */
		switch ($row_rst3['id_fhmea']) {
		case 14 :
			$pdf->Ln(4);
			$pdf->SetFont('Helvetica','',11);
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode(wordwrap("Nach erfolgter Reparatur und Rücksendung Ihres defekten Gerätes werden wir dieses Servicegerät wieder abholen lassen. Sollte das Leihgerät durch unsachgemäße Behandlung einen Defekt \naufweisen, so behalten wir uns vor, die dafür notwendigen Reparaturaufwendungen in Rechnung zu stellen.\n \nWir bitten daher um sorgfältige Behandlung.",100,"\n",255) )),0,"","");
			$pdf->Ln(5);
		break;
		
		case 15 :
			$pdf->Ln(4);
			$pdf->SetFont('Helvetica','',11);
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode(wordwrap("Nach erfolgter Reparatur und Rücksendung Ihres defekten Gerätes werden wir dieses Servicegerät wieder abholen lassen. Sollte das Leihgerät durch unsachgemäße Behandlung einen Defekt \naufweisen, so behalten wir uns vor, die dafür notwendigen Reparaturaufwendungen in Rechnung zu stellen.\n \nWir bitten daher um sorgfältige Behandlung.",100,"\n",255) )),0,"","");
			$pdf->Ln(5);
		break;
		
		}
		$pdf->SetFont('Helvetica','',12);
		$pdf->SetFontSize(12);
		$pdf->Write(13, 'Mit freundlichen Grüßen ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->SetFontSize(12);
		$pdf->Write(13, utf8_decode($row_rstuser['firma']));
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetFontSize(12);
		$pdf->Write(10, 'i.A.                            i.A.');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Write(10, '');
		$pdf->Ln(10);

		$pdf->Output($dokumentart."-".$row_rst3['lfd_nr'].".pdf","I");
		exit;

?>