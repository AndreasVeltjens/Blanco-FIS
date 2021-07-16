<?php require_once('Connections/qsdatenbank.php'); 
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmid=$url_fmid";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT entscheidung.entname, entscheidung.ueberschrift, entscheidung.entmemo, entscheidungsvarianten.varid, entscheidungsvarianten.varname, entscheidungsvarianten.varbeschreibung, entscheidungsvarianten.Kosten1 FROM entscheidung, entscheidungsvarianten WHERE entscheidungsvarianten.entid=entscheidung.entid AND entscheidung.entid=$row_rst1[fm2variante] ORDER BY entscheidungsvarianten.varname";
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
$query_rst4 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$row_rst1[fmartikelid] and fertigungsmeldungen.fsn=$row_rst1[fmsn] ORDER BY fertigungsmeldungen.fdatum desc";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmartikelid, fehlermeldungen.fmuser, fehlermeldungen.fm1datum, fehlermeldungen.fmsn, fehlermeldungen.fm3, fehlermeldungen.fm6, fehlermeldungen.fm1notes FROM fehlermeldungen WHERE fehlermeldungen.fmartikelid=$row_rst1[fmartikelid] and fehlermeldungen.fmsn=$row_rst1[fmsn] ORDER BY fehlermeldungen.fmdatum desc";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fehler.fname, fehler.fkurz, fehler.fid, fehler.lokz, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid, linkfehlerfehlermeldung.lffid FROM linkfehlerfehlermeldung, fehler WHERE linkfehlerfehlermeldung.lokz=0 AND linkfehlerfehlermeldung.fmid='$url_fmid' AND fehler.fid =linkfehlerfehlermeldung.fid ORDER BY fehler.fkurz";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);


		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF('P','mm');
		$pdf->AddPage();
		$pdf->SetDisplayMode('real');
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(200);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->text(5,5, '                             Prüfprotokoll für eine Reparatur elektrischer Geräte gemäß DIN VDE 0701/0702');
		$pdf->SetFont('Helvetica','',8);
		$pdf->Image('picture\logo.jpg',2,3,20,5,"","");
		$url_nummer=$row_rst1[fmid];	
include('picture/barcodeI25.php');
	 $pdf->Image('barcode\testbild.png',185,15,18,55,"","");
	 
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(100,15,'Fehlermeldenummer:');
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Text(150,15,$row_rst1['fmid']);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(2,15,'Leipzig, '.date("d.m.Y",time()));
		$pdf->Ln(10);
		$pdf->Text(2,20,'Auftraggeber                                                                                     Auftragnehmer (Elektrofachkraft)');
		
		$pdf->SetFont('Helvetica','',11);
		$pdf->Text(115,25, 'BLANCO CS Kunststofftechnik GmbH');
		$pdf->Text(115,30, 'Werkstättenstr. 31');
		$pdf->Text(115,35, 'DE-04319 Leipzig- Engelsdorf');
		
		$pdf->Text(2,25, utf8_decode($row_rst1['rechnung']));
		$pdf->Ln(2);
		$pdf->SetFont('Helvetica','',11);
	if ((strlen($row_rst1['ansprechpartner']))>1){
		$pdf->Text(2,30,utf8_decode($row_rst1['ansprechpartner']));
		$pdf->Ln(2);
		}
		$pdf->MultiCell(0,0, utf8_decode($row_rst1['rstrasse']),0);
		$pdf->Ln(3);
		$pdf->Write(5,utf8_decode($row_rst1['rplz'])." ".utf8_decode($row_rst1['rort'])." - ".utf8_decode($row_rst1['rland']));
		
		$pdf->Ln(30);
		
		/* $pdf->SetFont('Helvetica','B',14);
		$pdf->SetFontSize(14);
	 	$pdf->Write(13, 'Betreff: '.utf8_decode($row_rst2['ueberschrift'])); 
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(5); */
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Write(5, 'SN                          : '.utf8_decode($row_rst1['fmsn']));
	
		
		$pdf->Ln(5);
		$pdf->Write(5, 'Gerätetyp	            	: '.urldecode($row_rst3['Bezeichnung']."        "));
		
		$pdf->Ln(5);
		$pdf->Write(5, 'Fertigungsdatum    	: '.urldecode(substr($row_rst1['fmfd'],0,7)));

		$pdf->Ln(5);
		$pdf->Write(5, 'Versandlabel         : '.utf8_decode($row_rst1['fm11notes']));
		
	if ($row_rst1['fm12notes']<>" " or $row_rst1['fm12notes']!=="0"){ 
		$pdf->Ln(5);
		$pdf->Write(5, 'Kommision            : '.utf8_decode($row_rst1['fm12notes']));
		
	}
		/* Lieferadresse begin */
		$pdf->Ln(5);
		if ($row_rst1['lstrasse']==""){$row_rst1['lstrasse']="siehe Anschrift";}
		$pdf->Write(5, 'Rücklieferung an   : ');
		$pdf->Write(5, utf8_decode($row_rst1['lieferung']));$pdf->Ln(4);
		$pdf->Write(5, utf8_decode($row_rst1['lstrasse']." , "));
		$pdf->Write(5, utf8_decode($row_rst1['lplz'])."  ".utf8_decode($row_rst1['lort'])." , ".utf8_decode($row_rst1['lland']));
	$pdf->Ln(3);
		
		/* Lieferadresse ende */
	
		$pdf->Ln(1);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetFontSize(12);
		
		/* $pdf->Write(10, 'Sehr geehrte Damen und Herren,'); */
		
		
		/* $pdf->MultiCell(160,5,utf8_decode($row_rst2['entmemo']),0,"","");
		 */
		
		$pdf->MultiCell(0,4,utf8_decode($row_rst1['fm2notes']),0,"","");
		$pdf->SetFont('Helvetica','',8);
	
	$pdf->MultiCell(10,5,"Fehlererfassung zum aktuellen Reparaturfall:",0,"","");
		
	do{
	$pdf->MultiCell(0,4,utf8_decode($row_rst6['fkurz'])." - ".utf8_decode($row_rst6['fname']),0,"","");
	
	} while ($row_rst6 = mysql_fetch_assoc($rst6)); 
/* 
	$pdf->MultiCell(0,4,"Aufnahme   	          :".utf8_decode($row_rst1['fm1datum'])." - ".utf8_decode($row_rst1['fm1notes']),0,"","");
	$pdf->MultiCell(0,4,"Fehlererfassung    :".utf8_decode($row_rst1['fm2datum'])." - ".utf8_decode($row_rst1['fm2notes']),0,"","");
	$pdf->MultiCell(0,4,"Reparaturfreigabe :".utf8_decode($row_rst1['fm3datum'])." - ".utf8_decode($row_rst1['fm3notes']),0,"","");
	$pdf->MultiCell(0,4,"Reparatur              :".utf8_decode($row_rst1['fm4datum'])." - ".utf8_decode($row_rst1['fm4notes']),0,"","");
	$pdf->MultiCell(0,4,"Versand                 :".utf8_decode($row_rst1['fm5datum'])." - ".utf8_decode($row_rst1['fm5notes']),0,"","");
	$pdf->MultiCell(0,4,"Warenausgang      :".utf8_decode($row_rst1['fm6datum'])." - ".utf8_decode($row_rst1['fm6notes']),0,"","");
 */

	/* $pdf->Ln(3);
	$pdf->MultiCell(10,5,"vorläufige Entscheidung zum aktuellen Reparaturfall:",0,"B","12");
	$pdf->SetFont('Helvetica','B',12);
		$pdf->SetFontSize(12);
		$pdf->Write(12,utf8_decode($row_rst2['ueberschrift']));
		$pdf->SetFont('Helvetica','',12);
		$pdf->Ln(5);
		$pdf->Write(12,utf8_decode($row_rst2['entname']));
		$pdf->Ln(15);

	$pdf->MultiCell(10,5,"Gerätehistorie",0,"B","12");

	$pdf->MultiCell(10,5,"mögliche Fertigungsmeldung:",0,"","");
		
	do{
	$pdf->MultiCell(0,4,utf8_decode($row_rst4['fdatum'])." - ".utf8_decode($row_rst4['fnotes']),0,"","");
	$pdf->Ln(2);
	} while ($row_rst4 = mysql_fetch_assoc($rst4)); 
    $pdf->Ln(3);
	$pdf->MultiCell(10,5,"mögliche Reparaturmeldungen:",0,"","");

	do{
	$pdf->MultiCell(0,4,utf8_decode($row_rst5['fm1datum'])." - ".utf8_decode($row_rst5['fm1notes']),0,"","");
	$pdf->Ln(2);
	} while ($row_rst5 = mysql_fetch_assoc($rst5)); 
	
	
	$pdf->Ln(3);
	
		$pdf->MultiCell(10,5,"Dieses Dokument stellt den derzeitigen Stand der Retourenbearbeitung dar.",0,"","");
		
		
		$pdf->SetFont('Helvetica','',12);
		$pdf->SetFontSize(12); */
		/* $pdf->Write(13, 'Mit freundlichen Grüßen ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->SetFontSize(12);
		$pdf->Write(13, 'BLANCO CS Kunststofftechnik GmbH ');
		$pdf->Ln(10);
		
		$pdf->SetFont('Helvetica','',10);
		$pdf->SetFontSize(12);
		$pdf->Write(10, 'i.A. ');
		$pdf->Ln(5);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Write(10, 'Qualitätsbeauftragter ');
		$pdf->Ln(10); */

		$pdf->Output();
		exit;

?>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>

