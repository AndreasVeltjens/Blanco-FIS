<?php require_once('Connections/qsdatenbank.php');

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM fhm_ereignisart ORDER BY fhm_ereignisart.name";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM fhm WHERE fhm.id_fhm='$url_id_fhm'";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$row_rst5[artikelid]'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF('P','mm','e77x39');
		$pdf->AddPage();	
		$url_nummer=$row_rst5['id_fhm'];/* Übergabe der Dokumentnummer an Barcode */
		$url_sn_ab=$row_rst5['id_fhm'];
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
	 	$pdf->Image('barcode/testbild.png',5,1,12,40,"","");	
		$pdf->SetMargins(0,0);
	$pdf->SetAutoPageBreak(0);
		$pdf->Image('picture/logo.jpg',50,1,22,6,"","");
		/* wenn ortsveränderliches Inventar FHM */
		if ($row_rst5['ortsveraend']==1){
		$pdf->Image('picture/jahrespruefplakett_bgva3.jpg',20,4,15,15,"","");
		$pdf->SetFont('Helvetica','B',11);
		$pdf->Text(25,13,substr(date('Y',time()),2,2)+1);
		$pdf->Text(35,13,"geprüft nach BGV A3");
		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(16,4,"nächster Prüftermin");
	
		}
		
		/* wenn ortsfestes Inventar FHM */
		if ($row_rst5['ortsfest']==1){
		$pdf->Image('picture/jahrespruefplakett_bgva3.jpg',20,4,15,15,"","");
		
		$pdf->SetFont('Helvetica','B',11);
		$pdf->Text(25,13,substr(date('Y',time()),2,2)+1);
		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(35,13,"geprüft nach UVV/VBG u.DIN VDE");
		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(15,4,"nächster Prüftermin");
	
		}
		
		$pdf->SetFont('Helvetica','B',9);
			$pdf->MultiCell(14,3,wordwrap(utf8_decode($row_rst5['name']),30,"\n",255),0,"","");
	
		
		$pdf->SetFont('Helvetica','',6);
		 
	 
		
		
		$pdf->Write(5, '                                       Gerätetyp	    	: '.urldecode($row_rst2['Bezeichnung'].""));
		$pdf->Ln(2);
		$pdf->Write(5, '                                       Seriennummer    : '.urldecode($row_rst5['sn']));
		$pdf->Ln(2);
		$pdf->Write(5, '                                       Fertigungsdatum: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(2);
		$pdf->Write(5, '                                       Inventarnummer : '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));

		$pdf->Output("Seriennummeraufkleber-".$row_rst5['id_fhm'].".pdf","I");
		exit;

?>