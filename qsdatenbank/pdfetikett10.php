<?php require_once('Connections/qsdatenbank.php'); 

		/* Blanco Layout 10x5 - mit aktuellem Prüfplan */
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
		$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
		$row_rst4 = mysql_fetch_assoc($rst4);
		$totalRows_rst4 = mysql_num_rows($rst4);

		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst200 = "SELECT * FROM fhmprueschritt, link_artikeldaten_pruef WHERE fhmprueschritt.id_p=link_artikeldaten_pruef.pruef_id and link_artikeldaten_pruef.artikelid='$url_artikelid' ORDER BY fhmprueschritt.gruppe, fhmprueschritt.reihenfolge";
		$rst200 = mysql_query($query_rst200, $qsdatenbank) or die(mysql_error());
		$row_rst200 = mysql_fetch_assoc($rst200);
		$totalRows_rst200 = mysql_num_rows($rst200);




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
		$pdf->SetFont('Helvetica','',9);
		
		$gruppe="";
		$zeile=3;
		
		$pdf->Text(40,$zeile, urldecode("Qualitätssicherung"));
		$zeile=$zeile+4;
		$pdf->SetFont('Helvetica','',7);
	
  		do { 
       			if ($row_rst200['gruppe']!=$gruppe){ /* gruppe start */
						/* $pdf->Text(40,$zeile,utf8_decode($row_rst200['gruppe']));  */
						$zeile=$zeile+3;
				}
				
				/* $pdf->Text(45,$zeile,"- ".utf8_decode($row_rst200['name'])); */
				$zeile=$zeile+3;
				$gruppe=$row_rst200['gruppe'];
				
   		} while ($row_rst200 = mysql_fetch_assoc($rst200));
		
		$rows = mysql_num_rows($rst200);
  if($rows > 0) {
      mysql_data_seek($rst200, 0);
	  $row_rst200 = mysql_fetch_assoc($rst200);
  }
		
		
		 
			$zeile=$zeile+2;
			$pdf->Text(40,$zeile,utf8_decode("Mitarbeiter  _________________"));
		$pdf->SetFont('Helvetica','B',30);
		$pdf->Text(85,20,utf8_decode("QS"));
		$pdf->Text(85,30,utf8_decode("frei"));
		
		$pdf->Image('picture/ok.jpg',90,35,5,5,"",""); 
		
	/* EAN13 Code einfügen 
		if ($url_ean13>0 ){
		$uuu=1;		
		$url_nummer=substr($url_ean13,0,12);
		
		include('picture/barcodeEAN13.php');
		$barcodepath="barcode/testbildEAN13.png";
	 	$pdf->Image($barcodepath,85,1,20,40,"","");
		
		
		
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
		$barcodepath="barcode/testbildEAN13".$iii.".png";	
		$encoder = BarcodeFactory::Create(ENCODING_EAN13);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);

$pdf->SetFont('Helvetica','',8);
	$pdf->Text(85,5,"EAN13");
		}
		
		
		/* barcode 1 
		$uuu=1;		
		$url_nummer="".$url_artikelnummer;	
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,45,1,20,40,"","");
		
		  */
		
		if (strlen($url_sn_ab)==1){		
			$url_nummer="000".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){		
			$url_nummer="0".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){		
			$url_nummer="0".$url_sn_ab;}
			else{
			$url_nummer="00".$url_sn_ab;
		}
		/*
		$uuu=0;
		$barcodepath="barcode/testbild".$iii.".png";	
		$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);


$pdf->SetFont('Helvetica','',8);
$im=$e->Stroke($url_nummer,"barcode/testbild".$iii.".png");

		
	 	$pdf->Image($barcodepath,65,1,20,40,"",""); */
		
		$aaa=$aaa+1;
			if ($aaa==1){/* Anzahl der Ettiketten einer Seriennummer */
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab+1;
			}
		$diff=$diff+1;
		
	
		
		
			$pdf->SetFont('Helvetica','B',28);
		$pdf->Text(2,19,$url_artikelnummer);
		$pdf->SetFont('Helvetica','',15);
		$pdf->Text(2,28,"FD ".date("d-m-Y",time() ));
		$pdf->Text(2,33,"SN ".$url_nummer);
		
		$pdf->SetFont('Helvetica','',9);
		$pdf->ln(35);
		 $pdf->MultiCell(100,4,utf8_decode(($row_rst4['Bezeichnung'])),0,"L",0); 
	$pdf->SetFont('Helvetica','',10);
		/* $pdf->Write(2,40,utf8_decode($url_bezeichnung),6,"","");  */
		
		
/* logo anzeigen */
		
		if($row_rst4['logo']=="BCK"){
		
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		 
		}elseif ($row_rst4['logo']=="BLANCOCS") {
		
		$pdf->Image('picture/logo-sw.jpg',2,1,30,5,"",""); 
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS GmbH & Co.KG ");  */
		 
		}else{
		/* Standardlayout */
		
		/* $pdf->Image('picture/logo-sw.jpg',2,1,30,5,"","");  */
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		}
		
		
		
	
		
} while ( ($url_sn_ab<=$url_sn_bis )or $diff == 100);
		
		
		$pdf->Output();
		exit;
	
		

?>
<?php
mysql_free_result($rst1);
?>

