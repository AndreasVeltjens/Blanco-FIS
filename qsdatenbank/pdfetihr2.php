<?php require_once('Connections/qsdatenbank.php');
/* für Personal-Nr. ID-Card */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM `user` WHERE `user`.id='$url_user_id'";

$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

		include ('./libraries/fpdf/fpdf.php');
		$pdf =& new FPDF('P','mm','e10x5');
		$pdf->AddPage();	
		$url_nummer=$row_rst2['idcard'];/* Übergabe der Dokumentnummer an Barcode */
		$url_sn_ab=$url_nummer;
		if (strlen($url_sn_ab)==1){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==7){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==9){		
			$url_nummer="00".$url_sn_ab;}
			else{
			$url_nummer="0".$url_sn_ab;
		}
		
		
		include('picture/barcodeI25.php'); 
	 	$pdf->Image('barcode/testbild.png',5,0,15,40,"","");	
		  
	
		$pdf->SetFont('Helvetica','B',14);
			$pdf->Text(20,20,"FIS- ID Card- ".$row_rst2['idcard']);	
		$pdf->Text(20,30,utf8_decode($row_rst2['name']));	
		
		$pdf->SetFont('Helvetica','',12);
		 $pdf->Image('picture/logo.jpg',50,4,44,12,"","");
	 
		

		$pdf->Output("ID Card-".$row_rst2['idcard'].".pdf","I");
		exit;

?>