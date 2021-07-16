<?php require_once('Connections/qsdatenbank.php'); 

		/* Blanco Layout 10x5 - MaterialNummer + Seriennummer + Fertigungsdatum A+ EAN  */
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
		$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
		$row_rst4 = mysql_fetch_assoc($rst4);
		$totalRows_rst4 = mysql_num_rows($rst4);


		include ('./libraries/fpdf/fpdf.php');
		
		$diff=0;
		$aaa=0;
		$iii=0;
		
		
		
	/* lade Sourcedateien für Barcode */	
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";
	$pdf =& new FPDF('P','mm','e10x5');
do {
	
		
		$pdf->SetLeftMargin(0);
		$pdf->SetTopMargin(1);
		$pdf->SetDisplayMode('real');
		/* $pdf->SetMargins(0.1,0.1,0.1,0.1); */
		
		$pdf->AddPage();
		 $pdf->SetAutoPageBreak(200);
		
		
		
	
			
		
		$pdf->SetFont('Helvetica','',16);
		
		/* EAN13 Code einfügen */
		if ($url_ean13>0 ){
		$uuu=1;		
		$url_nummer=substr($row_rst4['ean13'],0,12);
		
		include('picture/barcodeEAN13.php');
		$barcodepath="barcode/testbildEAN13.png";
	 	$pdf->Image($barcodepath,65,10,15,30,"","");
		
		
		
		if (strlen($url_sn_ab)==1){		
			$url_nummer="000".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){		
			$url_nummer="0".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){		
			$url_nummer="0".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==7){		
			$url_nummer="0".$url_sn_ab;}
			else{
			$url_nummer="00".$url_sn_ab;
		}
		
		$uuu=0;
		$barcodepath="barcode/testbildEAN13".$iii.".png";	
		$encoder = BarcodeFactory::Create(ENCODING_EAN13);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);

$pdf->SetFont('Helvetica','',8);
	$pdf->Text(65,5,"EAN13");
		}
		
		
		/* barcode 1  Materialnummer*/
		$uuu=1;		
		$url_nummer="".$row_rst4['Nummer'];	
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,85,10,15,30,"","");
		
		
		
		if (strlen($url_sn_ab)==1){		
			$url_nummer="000".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){		
			$url_nummer="0".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){		
			$url_nummer="0".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==7){		
			$url_nummer="0".$url_sn_ab;}
			else{
			$url_nummer="00".$url_sn_ab;
		}
		
		$uuu=0;
		$barcodepath="barcode/testbild".$iii.".png";	
		$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);


/* $pdf->SetFont('Helvetica','',8);
$im=$e->Stroke($url_nummer,"barcode/testbild".$iii.".png");

		
	 	$pdf->Image($barcodepath,65,1,20,40,"","");
		
		$aaa=$aaa+1;
		/* Anzahl der Ettiketten einer Seriennummer */
		/* 	if ($aaa==1){
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab+1;
			}
		$diff=$diff+1;
		$pdf->Text(45,5,"Material-Nr.");
 */		/* $pdf->Text(65,5,"Serien-Nr."); */
	
		
		
			$pdf->SetFont('Helvetica','B',28);
		$pdf->Text(2,21,$row_rst4['Nummer']);
		$pdf->SetFont('Helvetica','',12);
		$pdf->Text(2,28,"Einlagerung ".date("d-m-Y",time() ));
	 	$pdf->Text(2,33,"Lagerplatz: ".$row_rst4['lagerplatz']); 
		$pdf->Text(2,38,"Stückzahl: _____"); 
		
	/* 	$pdf->Text(2,33,"SN ".$url_nummer); */
		
		$pdf->SetFont('Helvetica','',14);
		$pdf->ln(7);
		 $pdf->MultiCell(100,4,utf8_decode(substr($row_rst4['Bezeichnung'],0,80)),0,"L",0); 
	$pdf->SetFont('Helvetica','',10);
		/* $pdf->Write(2,40,utf8_decode($url_bezeichnung),6,"","");  */
		
		
/* logo anzeigen */
		
		if($row_rst4['logo']=="BCK"){
		
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		 
		}elseif ($row_rst4['logo']=="BLANCOCS") {
		
		$pdf->Image('picture/logo-sw.jpg',-3,1,32,6,"",""); 
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS GmbH & Co.KG ");  */
		 
		}else{
		/* Standardlayout */
		
		/* $pdf->Image('picture/logo-sw.jpg',2,1,30,5,"","");  */
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		}
		
		
		
	
		
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
		}
		
		$pdf->Output();
		exit;
	
		

?>
<?php
mysql_free_result($rst1);
?>

