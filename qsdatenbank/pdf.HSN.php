<?php
	
	$pdf =& new FPDF('P','mm','e10x5');
do {
		$pdf->SetFont('Helvetica','',6);
		$pdf->AddPage();
		$pdf->SetDisplayMode('real');
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(200);
		
		
		
		$pdf->Text(30,17," R _________________________________");
		$pdf->Text(30,25," H _________________________________");
		$pdf->Text(30,33," L _________________________________");

		/* barcode 1  Materialnumnmer*/
		$uuu=1;		
		$url_nummer="".$url_artikelnummer;	
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,5,5,10,32,"","");
		
		
		
		if (strlen($url_sn_ab)==1){		
			$url_nummer="000".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){		
			$url_nummer="0".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){		
			$url_nummer="0".$url_sn_ab;}
			else{
			$url_nummer="00".$url_sn_ab;
		}
	
		$uuu=0;
		$barcodepath="barcode/testbild".$iii.".png";	
		$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
		$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
		$e->SetModuleWidth(1);
		$e->SetHeight(90);
		$e->SetImgFormat('PNG');
		$e->SetVertical(0);
		$pdf->SetFont('Helvetica','',12);
		$im=$e->Stroke($url_nummer,"barcode/testbild".$iii.".png");
		$pdf->Image($barcodepath,15,0,30,10,"","");
		
		/* barcode 1  Modulnummer*/
		$barcodepath="barcode/testbildv".$iii.".png";	
		$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
		$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
		$e->SetModuleWidth(1);
		$e->SetHeight(90);
		$e->SetImgFormat('PNG');
		$e->SetVertical(1);
		$pdf->SetFont('Helvetica','',12);
		$im=$e->Stroke($url_nummer,"barcode/testbildv".$iii.".png");

		$pdf->Image($barcodepath,90,5,10,32,"","");
		
		$aaa=$aaa+1;
			if ($aaa==2){
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab+1;
			}
		$diff=$diff+1;
		/* $pdf->Text(25,10,$url_nummer);
		$pdf->Text(25,42,$url_nummer);
		$pdf->Text(85,10,$url_nummer);
		$pdf->Text(85,42,$url_nummer); */
		$pdf->SetFont('Helvetica','',22);
		$pdf->Text(32,40,"SN ".$url_nummer);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(45,6,utf8_decode(utf8_encode($row_rst4['Bezeichnung'])));
		if (strlen($row_rst4['Beziechnung'])==0){
		$pdf->Text(45,6,utf8_decode(utf8_encode($url_bezeichnung) ));
		
		}
	/* 	$pdf->Image('picture/logo.jpg',2,3,20,5,"",""); */
		
} while ( ($url_sn_ab<=$url_sn_bis )or $diff == 100);		
	
?>