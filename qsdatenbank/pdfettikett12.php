<?php require_once('Connections/qsdatenbank.php'); 

		/* Blanco Layout 10x5 - Ettikettdruckprogramm PDFettikett12
		
		text
		logo BCK, BLANCOCS, kein Wert
		materialnummer
		barcode aus Materialnummer  ==1
		
		*/
		


		include ('./libraries/fpdf/fpdf.php');
		
		$diff=0;
		$aaa=0;
		$iii=0;
		
		
		
	/* lade Sourcedateien für Barcode */	
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";
	$pdf =& new FPDF('P','mm','e10x5');
		
		$pdf->SetLeftMargin(0);
		$pdf->SetTopMargin(1);
		$pdf->SetDisplayMode('real');
		/* $pdf->SetMargins(0.1,0.1,0.1,0.1); */
		
		$pdf->AddPage();
		 $pdf->SetAutoPageBreak(200);
		$pdf->SetFont('Helvetica','',9);
		
		/* barcode 1  */
	
	if ($barcode=="1"){
		if (strlen($materialnummer)==1){		
			$url_nummer="000".$materialnummer;}
			elseif (strlen($materialnummer)==3){		
			$url_nummer="0".$materialnummer;}
			elseif (strlen($materialnummer)==5){		
			$url_nummer="0".$materialnummer;}
			else{
			$url_nummer="00".$materialnummer;
		}
		
			
			
		include('picture/barcodeI25_0.php');
		$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);
$im=$e->Stroke($url_nummer,"barcode/testbild.png");
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,85,1,20,40,"","");
		
		 
	} /* ende Barcode */
	
	
		/* schreibe Materialnummer */
		if ($materialnummer<>""){
		$pdf->ln(6);
		$pdf->SetFont('Helvetica','B',28);
		$pdf->MultiCell(80,8,$materialnummer,0,"L",0); 
		
		$pdf->SetFont('Helvetica','',15);
		}
		/* schreibe Materialnummer */
		

		$pdf->ln(4);
		$pdf->SetFont('Helvetica','',$schrift);
		$pdf->MultiCell(40,5,wordwrap($text,40,"\n",40),0,"L",0); 
		$pdf->Cell(80,4,"  ",0,"L",0); 
		
		
		/* logo anzeigen auswählen*/
		
		if($logo=="BCK"){
		
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		 
		}elseif ($logo=="BLANCOCS") {
		
		$pdf->Image('picture/logo-sw.jpg',2,1,30,5,"",""); 
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS GmbH & Co.KG ");  */
		 
		}else{
		/* Standardlayout */
		
		/* $pdf->Image('picture/logo-sw.jpg',2,1,30,5,"","");  */
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		}
		
		
		
	
		

		
		
		$pdf->Output();
		exit;
	
		

?>
<?php
mysql_free_result($rst1);
?>

