<?php require_once('Connections/qsdatenbank.php'); 

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmid=$url_fmid";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT entscheidung.entname, entscheidung.ueberschrift, entscheidung.entmemo, entscheidung.gvwl, entscheidungsvarianten.varid, entscheidungsvarianten.varname, entscheidungsvarianten.varbeschreibung, entscheidungsvarianten.Kosten1 FROM entscheidung, entscheidungsvarianten WHERE entscheidungsvarianten.entid=entscheidung.entid AND entscheidung.entid=$row_rst1[fm2variante] ORDER BY entscheidungsvarianten.varname";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

$suche=$row_rst1['fmartikelid'];

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid=$suche";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$url_fmid' ORDER BY fehlermeldungsdaten.varname";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fehler.fname, fehler.fkurz, fehler.fid, fehler.lokz, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid, linkfehlerfehlermeldung.lffid FROM linkfehlerfehlermeldung, fehler WHERE linkfehlerfehlermeldung.lokz=0 AND linkfehlerfehlermeldung.fmid='$url_fmid' AND fehler.fid =linkfehlerfehlermeldung.fid ORDER BY fehler.fkurz";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);


		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF();
		$pdf->AddPage();	
		$url_nummer=$row_rst1[fmid];
include('picture/barcodeI25.php');
	 $pdf->Image('barcode\testbild.png',5,10,18,55,"","");	
		  
	
		$pdf->SetFont('Helvetica','B',14);
		$pdf->Write(5, 'Q3-Online Reklamations- und Fehlermeldung');
		$pdf->Ln();
		$pdf->SetFont('Helvetica','',14);
		$pdf->Image('picture\logo.jpg',140,10,50,15,"","");
	
	
		
		$pdf->SetFontSize(12);
		$pdf->Write(5, '');
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
		$pdf->Text(140,75,'Fehlermeldenummer:');
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(180,75,$row_rst1['fmid']);
		if ($row_rst1['debitorennummer']>0){
		$pdf->SetFont('Helvetica','',10);
			$pdf->Text(140,80,'Ihre Kundennummer:');
			$pdf->SetFont('Helvetica','',12);
			$pdf->Text(180,80,$row_rst1['debitorennummer']);
			}
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(140,85,'Leipzig, '.date("d.m.Y",time()));
		
		
		
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Ln(10);
		$pdf->MultiCell(15,5,utf8_decode($row_rst1['rechnung']),10);
		$pdf->Ln(1);
		if ((strlen($row_rst1['ansprechpartner']))>1){
		$pdf->MultiCell(15,5,utf8_decode($row_rst1['ansprechpartner']),10);
		$pdf->Ln(2);
		}
		$pdf->MultiCell(15,5, utf8_decode($row_rst1['rstrasse']),0);
		$pdf->Ln(2);
		$pdf->Write(5,utf8_decode($row_rst1['rplz'])." ".utf8_decode($row_rst1['rort']));
		$pdf->Ln(4);
		$pdf->Write(5,utf8_decode($row_rst1['rland']));
		$pdf->Ln(10);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Write(5,"Faxnummer:".utf8_decode($row_rst1['faxnummer']));
		$pdf->Ln(5);
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Betreff: '.utf8_decode($row_rst2['ueberschrift']));
		
/* Lieferadresse begin */
		$pdf->Ln(4);
		if ($row_rst1['lstrasse']==""){$row_rst1['lstrasse']="siehe Anschrift";}
		
		$pdf->Write(13, 'Rücklieferung an   : ');
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode(str_replace("\n"," - ",$row_rst1['lieferung'])));
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode($row_rst1['lstrasse']." , "));
		$pdf->Write(13, utf8_decode($row_rst1['lplz'])."  ".utf8_decode($row_rst1['lort'])." , ".utf8_decode($row_rst1['lland']));
	$pdf->Ln(4);
		
		/* Lieferadresse ende */
	
		$pdf->Ln(10);
		
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Produktfehler oder Geschäftsprozessabweichung');
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(10);
		$pdf->Write(13, 'Materialnummer     '.utf8_decode($row_rst1['materialnummer']."        "));
		$pdf->Ln(4);
		
		$pdf->Write(13, 'Menge	                    '.utf8_decode($row_rst1['reklamenge']."        "));
		$pdf->Ln(4);
		$pdf->Write(13, 'Gerätetyp	            	 '.utf8_decode($row_rst3['Bezeichnung']."        "));
		$pdf->Ln(4);
		$pdf->Write(13, 'Seriennummer       '.utf8_decode($row_rst1['fmsn']));
		$pdf->Ln(4);
		$pdf->Write(13, 'Fertigungsdatum  	 '.utf8_decode(substr($row_rst1['fmfd'],0,7)));

	if (($row_rst1['fm12notes'])!==""){ 
		$pdf->Ln(4);
		$pdf->Write(13, 'Kommision             '.utf8_decode($row_rst1['fm12notes']));
		
	}
		
		$pdf->Ln(10);
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Fehlerbeschreibung / Prozessabweichung');
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode(str_replace("\n"," - ",$row_rst1['fm1notes'])));
		$pdf->Ln(10);
		$pdf->MultiCell(10,5,"Fehlererfassung:",0,"","");
		$pdf->Ln(4);
	do{
	$pdf->MultiCell(0,4,utf8_decode($row_rst6['fkurz'])." - ".utf8_decode($row_rst6['fname']),0,"","");
	$pdf->Ln(2);
	} while ($row_rst6 = mysql_fetch_assoc($rst6)); 
	
		
		
		$pdf->Ln(10);
		$pdf->Write(13, 'wird vom Q-Team ausgefüllt');
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Verantwortlich für Korrekturmaßnahme');
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		$pdf->Ln(4);
		
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT * FROM `user`";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);


		$name="noch nicht bestimmt";
	do {  
	 		if (!(strcmp($row_rstuser['id'], $row_rst1['fm9user']))) { $name=utf8_decode($row_rstuser['name']);}


		} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  		$rows = mysql_num_rows($rstuser);
	  if($rows > 0) {
     		 mysql_data_seek($rstuser, 0);
	  		$row_rstuser = mysql_fetch_assoc($rstuser);
	  }
		
		$pdf->Write(13,$name);
		
		
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode(str_replace("\n"," - ",$row_rst1['fm9datum'])));
		$pdf->Ln(10);
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Analysierte Fehlerursache');
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode(str_replace("\n"," - ",$row_rst1['fm9notes'])));
		$pdf->Ln(10);
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Endgültige Abstell- und Korrekturmaßnahme');
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode(str_replace("\n"," - ",$row_rst1['fm10notes'])));
		
		$pdf->Ln(10);
		$pdf->SetFont('Helvetica','B',13);
		$pdf->SetFontSize(13);
		$pdf->Write(13, 'Wirksamkeit Korrekturmaßnahme bestätigt:');
		$pdf->SetFont('Helvetica','',11);
		$pdf->SetFontSize(11);
		$pdf->Ln(4);
		$name= "noch nicht bestimmt";
		do {  
	 		if (!(strcmp($row_rstuser['id'], $row_rst1['fm10user']))) { $name=utf8_decode($row_rstuser['name']);}


		} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  		$rows = mysql_num_rows($rstuser);
	  if($rows > 0) {
     		 mysql_data_seek($rstuser, 0);
	  		$row_rstuser = mysql_fetch_assoc($rstuser);
	  }
		
		$pdf->Write(13,$name);
		$pdf->Ln(4);
		$pdf->Write(13, utf8_decode(str_replace("\n"," - ",$row_rst1['fm10datum'])));
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		/* if (($row_rst2['anrede'])=="0"){
		 $pdf->Write(5, 'Sehr geehrte Damen und Herren,'); }
		 else{
		$pdf->MultiCell(160,5,utf8_decode($row_rst1['anrede']),0,"","");
		}	
		$pdf->Ln(4);
		
		$pdf->MultiCell(160,5,utf8_decode($row_rst2['entmemo']),0,"","");
		
		$pdf->Ln(1);
		$pdf->MultiCell(10,5,"Bitte wählen Sie aus folgenden Varianten:",0,"","");
		$pdf->Ln(5);
		
		do {
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(5,5,"",1);
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(40,5,utf8_decode($row_rst4['varname']) ,0);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->MultiCell(50,4, utf8_decode(($row_rst4['varbeschreibung'])),0,"","");
		
		if ($row_rst4['Kosten1']>0){
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Cell(170,5,$row_rst4['Kosten1']." € zzgl. MWSt.","","","R");
		}
		
		$pdf->Ln(10);
		} while ($row_rst4 = mysql_fetch_assoc($rst4));

		$pdf->MultiCell(0,4,utf8_decode($row_rst1['fm2notes']),0,"",""); 
		$pdf->SetFont('Helvetica','',10);

	if ($row_rst2['gvwl']==0){
		$pdf->MultiCell(10,5,"Bitte erteilen Sie uns innerhalb von 7 Tagen einen Reparaturauftrag und kreuzen Sie die gewünschte Variante an.",0,"","");
		}else{
		$pdf->MultiCell(10,5,"Dieses Schreiben dient zur Ihrer Information über Ihren Reparaturauftrag des o.g. Gerätes. Wir \nsenden Ihnen Ihr Gerät nach erfolgreicher Reparatur und Funktionstest an o.g. Anschrift zurück.",0,"","");
		}
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetFontSize(12);
		$pdf->Write(10, 'Mit freundlichen Grüßen ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->SetFontSize(10);
		if ($row_rst2['gvwl']==0){
		$pdf->Write(10, 'BLANCO CS Kunststofftechnik GmbH                               Ihre Auftragsbestätigung:');
		}else{
		$pdf->Write(10, 'BLANCO CS Kunststofftechnik GmbH');
		
		}
		
		$pdf->Ln(5);
		
		$pdf->SetFont('Helvetica','',8);
		$pdf->SetFontSize(8);
		if ($row_rst2['gvwl']==0){
		
		$pdf->Write(10, 'i.A.                               i.A.                                                                           Telefonnummer für Rückfragen: _ _ _ _ _ _ _ _ ');
		$pdf->Ln(4);
		$pdf->Write(10, '                                                                                                                     Name   :  _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ ');
		$pdf->Ln(4);
		$pdf->Write(10, '                                                                                                                     Datum  :  _ _ _ _ _ _ _ ');
		$pdf->Ln(4);
		$pdf->Write(10, '                                                                                                                     rechtsgültige Unterschrift mit Firmenstempel');
		}else{
		$pdf->Write(10, 'i.A.                               i.A.  ');

		}
		 */

		$pdf->Output();
		exit;

?>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);
?>


