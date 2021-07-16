<?php require_once('Connections/qsdatenbank.php');
/* Beschriftung von Hilfsmittel auf 100x50 Aufkleber */
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
		$pdf =& new FPDF('P','mm','e10x5');
		$pdf->AddPage();	
		$url_nummer=$row_rst5['id_fhm'];/* Übergabe der Dokumentnummer an Barcode */
		$url_sn_ab=$row_rst5['id_fhm'];
		if (strlen($url_sn_ab)==1){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==7){		
			$url_nummer="00".$url_sn_ab;}
			else{
			$url_nummer="0".$url_sn_ab;
		}
		
		
		include('picture/barcodeI25.php'); 
	 	$pdf->Image('barcode/testbild.png',5,1,15,50,"","");	
		  
	
		$pdf->SetFont('Helvetica','B',14);
				
		
		$pdf->SetFont('Helvetica','',12);
		 $pdf->Image('picture/logo.jpg',50,1,40,10,"","");
	 
		
		$pdf->Write(5,urldecode($row_rst5['name'].""));
		$pdf->Ln(4);
		$pdf->Write(5, 'Gerätetyp	            	: '.urldecode($row_rst2['Bezeichnung'].""));
		$pdf->Ln(4);
		$pdf->Write(5, 'Seriennummer    : '.urldecode($row_rst5['sn']));
		$pdf->Ln(4);
		$pdf->Write(5, 'Fertigungsdatum: '.utf8_decode(substr($row_rst5['baujahr'],0,7)));
		$pdf->Ln(4);
		$pdf->Write(5, 'Inventarnummer : '.utf8_decode(substr($row_rst5['Inventarnummer'],0,7)));

		$pdf->Output("Seriennummeraufkleber-".$row_rst5['id_fhm'].".pdf","I");
		exit;

?>