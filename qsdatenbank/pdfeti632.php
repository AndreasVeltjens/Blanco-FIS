<?php require_once('Connections/qsdatenbank.php');
require_once('Connections/vsdatenbank.php');


mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst4 = "SELECT * FROM vorgangsart ORDER BY vorgangsart.anzeigetxt";
$rst4 = mysql_query($query_rst4, $vsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst3 = "SELECT * FROM vorgange WHERE vorgange.id_vg='$url_id_vg' ";
$rst3 = mysql_query($query_rst3, $vsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst5 = "SELECT * FROM vorgangsdaten WHERE vorgangsdaten.id_vg='$url_id_vg' AND vorgangsdaten.lokz=0 ORDER BY vorgangsdaten.pos, vorgangsdaten.kontierung";
$rst5 = mysql_query($query_rst5, $vsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst7 = "SELECT * FROM kontierung ORDER BY kontierung.datevtxt";
$rst7 = mysql_query($query_rst7, $vsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);



		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF();
		$pdf->AddPage();	
		$url_nummer="0".$row_rst3['lfd_nr'];/* №bergabe der Dokumentnummer an Barcode */
		
		include('picture/barcodeI25.php'); 
	 	$pdf->Image('barcode/testbild.png',5,10,18,55,"","");	
		  
	
		$pdf->SetFont('Helvetica','B',14);
		
		do {  /* Dokumentart */
 if (!(strcmp($row_rst4['id_vga'], $row_rst3['vorgangsart']))) {$pdf->Write(5, (utf8_decode(utf8_decode($row_rst4['anzeigetxt']))) );
 
 $dokumentart=utf8_decode(utf8_decode($row_rst4['anzeigetxt']));
 
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
				do {  /* Dokumentart */
 if (!(strcmp($row_rst4['id_vga'], $row_rst3['vorgangsart']))) {$pdf->Text(140,77,utf8_decode(utf8_decode($row_rst4['anzeigetxt']))."-Nr: " );}
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(180,77,$row_rst3['lfd_nr']);
		
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
		$pdf->SetFont('Helvetica','',10);
		$pdf->Write(5,"per Fax an:".utf8_decode(utf8_decode($row_rst3['faxnummer'])));
		$pdf->Ln(5);
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Betreff: '.utf8_decode(utf8_decode($row_rst3['headertxt'])));
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(5);
		/* $pdf->Write(13, 'Gerдtetyp	            	: '.urldecode($row_rst3['Bezeichnung']."        "));
		$pdf->Write(13, 'Seriennummer     : '.urldecode($row_rst1['fmsn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	: '.utf8_decode(substr($row_rst1['fmfd'],0,7)));
 */
	/* if (($row_rst1['fm12notes'])!=="0"){ 
		$pdf->Ln(4);
		$pdf->Write(13, 'Kommision            : '.utf8_decode($row_rst1['fm12notes']));
		
	} */
/* Lieferadresse begin */
		$pdf->Ln(4);
		if ($row_rst1['lstrasse']==""){$row_rst1['lstrasse']="siehe Anschrift";}
		$pdf->Write(13, 'Lieferung an   : ');
		$pdf->Write(13, utf8_decode(utf8_decode($row_rst1['lieferung'])));
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode(utf8_decode($row_rst1['lstrasse']."  ")));
		$pdf->Write(13, utf8_decode(utf8_decode($row_rst1['lplz']))."  ".utf8_decode(utf8_decode($row_rst1['lort']))."   ".utf8_decode(utf8_decode($row_rst1['lland'])));
	$pdf->Ln(4);
		
		/* Lieferadresse ende */
	
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->SetFontSize(11);
		
		if (($row_rst3['anrede'])=="0"){
		 $pdf->Write(5, 'Sehr geehrte Damen und Herren,'); }
		 else{
		$pdf->MultiCell(160,5,utf8_decode(utf8_decode($row_rst3['anrede'])),0,"","");
		}	
		$pdf->Ln(4);
		/* Textbausteinde */
		switch ($row_rst3['vorgangsart']) {
		case 1 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit bieten wir Ihnen folgende Leistungen gemдя unserer AGB's an:")),0,"","");
		break;
		case 2 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit bestдtigen wir Ihnen folgende Leistungen gemдя unserer AGB's:")),0,"","");
		break;
		case 3 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit liefern wir Ihnen folgende Leistungen gemдя unserer AGB's:")),0,"","");
		break;
		case 4 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit berechnen wir Ihnen folgende Leistungen gemдя unserer AGB's:")),0,"","");
		break;
		case 5 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit weisen wir Sie auf folgende offene Posten hin. Bitte zahlen Sie unverzьglich\ndie offenen Betrag auf die unten genannte Bankverbindung. ")),0,"","");
		break;
		case 6 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit erhalten Sie von uns eine Gutschrift, die wir in den nдchsten Werktagen als\nBankgutschrift an die uns bekannte Bankanschrift in des genannten Betrages ьberweisen. ")),0,"","");
		break;
		case 7 :
			$pdf->MultiCell(160,5,utf8_decode(utf8_encode("hiermit bestellen wir zu unseren ABG's folgende Leistungen: ")),0,"","");
		break;
		}
		$pdf->Ln(4);
	/* 	$pdf->Ln(1);
		$pdf->MultiCell(10,5,"Bitte wдhlen Sie aus folgenden Varianten:",0,"","");
		$pdf->Ln(5); */
		
		do {
		/* $pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(5,5,"",1); */
		/* Kasten zum Ankreuzen */
		if ($row_rst5['kontierung']==0){
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(15,4,utf8_decode(utf8_decode($row_rst5['pos'])) ,0);
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(20,4,utf8_decode(utf8_decode($row_rst5['material'])) ,0);
				
				$pdf->MultiCell(80,4, utf8_decode(utf8_decode(($row_rst5['beschreibung']))),0,"","");
				
		
		} elseif ($row_rst5['kontierung']==1){
				$pdf->MultiCell(0,4, utf8_decode(utf8_decode((Alternativposition))),0,"","");
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(15,4,utf8_decode(utf8_decode("Pos. ".$row_rst5['pos'])) ,0);
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(20,4,utf8_decode(utf8_decode($row_rst5['material'])) ,0);
				$pdf->MultiCell(80,4, utf8_decode(utf8_decode(($row_rst5['beschreibung']))),0,"","");
				if ($row_rst5['menge']>0){
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(35,5,"","","","L");
				$pdf->Cell(35,4,"Menge ".utf8_decode(utf8_decode($row_rst5['menge'])),"","","L");
				}
				if ($row_rst5['nettoVK']>0){
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(50,4,"Einzelpreis ".$row_rst5['nettoVK']." А ","","","R");
				}
				if ($row_rst5['bruttoVK']>0){
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(50,5,"Gesamt ".$row_rst5['bruttoVK']." А ","","","R");
				$gesamtbruttoVK[$row_rst5['kontierung']]=$gesamtbruttoVK[$row_rst5['kontierung']]+$row_rst5['bruttoVK'];
				}
		} else {
				
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(15,4,utf8_decode(utf8_decode("Pos. ".$row_rst5['pos'])) ,0);
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(20,4,utf8_decode(utf8_decode($row_rst5['material'])) ,0);
				$pdf->MultiCell(80,4, utf8_decode(utf8_decode(($row_rst5['beschreibung']))),0,"","");
				if ($row_rst5['menge']>0){
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(35,5,"","","","L");
				$pdf->Cell(35,4,"Menge ".utf8_decode(utf8_decode($row_rst5['menge'])),"","","L");
				}
				if ($row_rst5['nettoVK']>0){
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(50,4,"Einzelpreis ".$row_rst5['nettoVK']." А ","","","R");
				}
				if ($row_rst5['bruttoVK']>0){
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(50,5,"Gesamt ".$row_rst5['bruttoVK']." А ","","","R");
				$gesamtbruttoVK[$row_rst5['kontierung']]=$gesamtbruttoVK[$row_rst5['kontierung']]+$row_rst5['bruttoVK'];
				}
				if ($row_rst5['datum']!="0000-00-00"){
				$pdf->SetFont('Helvetica','',9);
				$pdf->Ln(4);
				$pdf->Cell(35,5,"","","","L");
				$pdf->Cell(35,5,"Liefertermin ".$row_rst5['datum']." ","","","L");
				}
				
		} /* ende von Vorkontierung */
		
		$pdf->Ln(10);
		} while ($row_rst5 = mysql_fetch_assoc($rst5));
do{
		/* $pdf->MultiCell(0,4,utf8_decode($row_rst3['fm2notes']),0,"",""); */
		$pdf->Ln(5);
		if ($gesamtbruttoVK[$row_rst7['id_k']]>0){
		$pdf->SetFont('Helvetica','',9);
		 $summenetto=round($gesamtbruttoVK[$row_rst7['id_k']],2);
		 $summemwst=round($gesamtbruttoVK[$row_rst7['id_k']]*$row_rst7['prozent'],2);
		 $summebrutto=$summenetto+$summemwst;
		
		$pdf->Cell(170,5,utf8_decode($row_rst7['anzeigetxt'])." Gesamtpreis (netto)   ".sprintf("%01.2f",$summenetto)." А ","","","R");
		$pdf->Ln(5);
		$pdf->Cell(170,5," zzgl. ".($row_rst7['prozent']*100)." % USt.  ".sprintf("%01.2f",$summemwst)." А ","","","R");
		$pdf->Ln(5);
		$pdf->Cell(170,5,utf8_decode($row_rst7['anzeigetxt'])." Gesamtpreis (brutto)   ".sprintf("%01.2f",$summebrutto)." А ","","","R");
		$pdf->Ln(10);
		}
} while ($row_rst7 = mysql_fetch_assoc($rst7));	
		
		
		$pdf->SetFont('Helvetica','',10);

	
		/* $pdf->MultiCell(10,5,"Bitte anworten Sie innerhalb von 7 Tagen auf dieses Fax und kreuzen Sie entsprechende Variante an.",0,"","");
		 */
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->SetFontSize(12);
		$pdf->Write(13, 'Mit freundlichen Grьяen ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->SetFontSize(12);
		$pdf->Write(13, utf8_decode($row_rstuser['firma']));
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetFontSize(12);
		$pdf->Write(10, ' ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Write(10, '');
		$pdf->Ln(10);

		$pdf->Output($dokumentart."-".$row_rst3['lfd_nr'].".pdf","I");
		exit;

?>