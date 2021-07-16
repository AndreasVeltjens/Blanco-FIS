<?php 
/* DokuSet */


		/* Blanco Layout 10x5 - MaterialNummer + Seriennummer + Fertigungsdatum A+ EAN  */
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
		$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
		$row_rst4 = mysql_fetch_assoc($rst4);
		$totalRows_rst4 = mysql_num_rows($rst4);
		
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst2 = "SELECT artikelversionen.id FROM  artikeldaten, artikelversionen WHERE artikeldaten.artikelid='$url_artikelid' AND artikelversionen.artikelid='$url_artikelid' AND artikelversionen.version = artikeldaten.version ";
		$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
		$row_rst2 = mysql_fetch_assoc($rst2);
		$totalRows_rst2 = mysql_num_rows($rst2);
		
		$maxRows_rst_stueckliste = 50;
		$pageNum_rst_stueckliste = 0;
		if (isset($HTTP_GET_VARS['pageNum_rst_stueckliste'])) {
		  $pageNum_rst_stueckliste = $HTTP_GET_VARS['pageNum_rst_stueckliste'];
		}
		$startRow_rst_stueckliste = $pageNum_rst_stueckliste * $maxRows_rst_stueckliste;

		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst_stueckliste = "SELECT * FROM artikelversionsstuecklisten WHERE artikelversionsstuecklisten.version_id ='$row_rst2[id]' ORDER BY artikelversionsstuecklisten.st_art";
		$query_limit_rst_stueckliste = sprintf("%s LIMIT %d, %d", $query_rst_stueckliste, $startRow_rst_stueckliste, $maxRows_rst_stueckliste);
		$rst_stueckliste = mysql_query($query_limit_rst_stueckliste, $qsdatenbank) or die(mysql_error());
		$row_rst_stueckliste = mysql_fetch_assoc($rst_stueckliste);
		
		if (isset($HTTP_GET_VARS['totalRows_rst_stueckliste'])) {
		  $totalRows_rst_stueckliste = $HTTP_GET_VARS['totalRows_rst_stueckliste'];
		} else {
		  $all_rst_stueckliste = mysql_query($query_rst_stueckliste);
		  $totalRows_rst_stueckliste = mysql_num_rows($all_rst_stueckliste);
		}
		$totalPages_rst_stueckliste = ceil($totalRows_rst_stueckliste/$maxRows_rst_stueckliste)-1;


		$diff=0;
		$aaa=0;
		$iii=0;
		$url_ean13=$row_rst4['ean13'];
		$url_artikelnummer=$row_rst4['Nummer'];
		
		$url_nummer=$sn;
		$url_sn_von=$sn;
		$url_sn_ab=$sn;
		$url_sn_bis=$sn;
		
		
		
	/* lade Sourcedateien für Barcode */	

	$pdf =& new FPDF('P','mm','a4');
do {  /* Anzahl der Seriennummernettiketten pro Gerät */
	
		
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
		$url_nummer=substr($url_ean13,0,12);
		
		include('picture/barcodeEAN13.php');
		$barcodepath="barcode/testbildEAN13.png";
	  	$pdf->Image($barcodepath,105,1,10,30,"",""); 
		
		
		
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
	$pdf->Text(106,2,"EAN13");
		}
			
		
/* barcode 1 */
		$uuu=1;		
		$url_nummer="".$url_artikelnummer;	
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,65,1,10,30,"","");
		
		
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
		$e->SetModuleWidth(2);
		$e->SetHeight(70);
		$e->SetImgFormat('PNG');
		$e->SetVertical(1);
		$pdf->SetFont('Helvetica','',8);
		$im=$e->Stroke($url_nummer,"barcode/testbild".$iii.".png");
	 	$pdf->Image($barcodepath,85,1,10,30,"",""); 
		
		$aaa=$aaa+1;
		if ($aaa==$row_rst4['anzahl_etikett_verpackung']){/* Anzahl der Ettiketten einer Seriennummer */
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab;
		}
		
		$pdf->Text(65,2,"Material-Nr.");
		$pdf->Text(85,2,"Serien-Nr.");
	
		
		
		$pdf->SetFont('Helvetica','B',28);
		$pdf->Text(2,16,$url_artikelnummer);
		$pdf->SetFont('Helvetica','',15);
		$pdf->Text(2,30,"FD ".date("d-m-Y",time() ));
/* Ausgabe Seriennumer */
		
		$pdf->Text(2,35,"SN ".$sn);
		$pdf->Text(2,40,$row_rst4['version']);
		
		$pdf->ln(45);
		$pdf->SetFont('Helvetica','',20);
		$pdf->MultiCell(100,4,utf8_decode(($row_rst4['Kontierung'])." - ".($row_rst4['Gruppe'])),0,"L",0); 
		$pdf->SetFont('Helvetica','B',16);
		$pdf->ln(10);
		$pdf->MultiCell(100,4,utf8_decode(($row_rst4['Bezeichnung'])),0,"L",0); 
		$pdf->SetFont('Helvetica','',10);
		$pdf->ln(5);
		$pdf->MultiCell(100,4,utf8_decode(($row_rst4['Beschreibung'])),0,"L",0); 
		/* $pdf->Write(2,40,utf8_decode($url_bezeichnung),6,"","");  */

/* anzeigen der Prüfzeichen  */

	 	$barcodepath="picture/eti_".$row_rst4['pruefzeichen'].".jpg"; 
	$pdf->Image($barcodepath,0,18,45,7,"",""); 
/* anzeigen FID Barcode */
/* anzeigen FID Barcode */

 /* Fehlerabfrage, wenn Parameter Anbzahl der Etiketten falsch */
		if (($row_rst4['anzahl_etikett_verpackung'])=='') {$row_rst4['anzahl_etikett_verpackung']=1;}
		$druckende=$row_rst4['anzahl_etikett_verpackung'];


		if ($barcodepathforsn==''){
					$pdf->Text(150,25,"  ".$fid." ");/* FID-Text */
		}else{
					$pdf->Image($barcodepathforsn,160,22,30,10,"",""); 
					$pdf->SetFont('Helvetica','',6);
					$pdf->Text(150,25,"  ".$fid." ");/* FID-Text */
		}
					
		if ($nachdruck==1) {
			$pdf->Text(150,30,"Kopie" );
		}

		
/* logo anzeigen */
		
		if($row_rst4['logo']=="BCK"){
	 	$pdf->Image('picture\logo.jpg',150,1,40,13,"",""); 
		$pdf->SetFont('Helvetica','',5.5);
		 $pdf->Text(150,11,"BLANCO Professional Kunststofftechnik GmbH "); 
		 
		}elseif ($row_rst4['logo']=="BLANCOCS") {
	 	$pdf->Image('picture\logo.jpg',150,1,40,13,"",""); 
		/* $pdf->Image('picture/logo-sw.jpg',2,1,30,5,"",""); */ 
		$pdf->SetFont('Helvetica','',5.5);
		 $pdf->Text(150,11," "); 
		 
		}else{
		/* Standardlayout */
		
		/* $pdf->Image('picture/logo-sw.jpg',2,1,30,5,"","");  */
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		}
		
		
		$diff=$diff+1;
		
	$materialbild="..\\documents\materialbilder\\".$row_rst4['Nummer'].".png";
	 $pdf->Image($materialbild,130,90,60,40,"",""); 
	
if ($totalRows_rst_stueckliste > 0) { // Show if recordset not empty 
	$bildx=2;
	$bildy=150;
	$bildl=60;
	$bildb=60;
	$bildanzahl=0;
  do { 
  /* Kompoentenliste */
  	if  ($row_rst_stueckliste['st_art']=="Komponente") {
		
		$pdf->MultiCell(100,3,utf8_decode($row_rst_stueckliste['menge'].' - '.$row_rst_stueckliste['artikelnummer'].' - '.$row_rst_stueckliste['bezeichnung']),0,"L",0); 
  	}
    /* Bilderliste */
  	if  ($row_rst_stueckliste['st_art']=="Bild") {
		$bildanzahl=$bildanzahl+1;
		$materialbild="..\\documents\materialbilder\ersatzteilsets\\".$row_rst_stueckliste['bezeichnung'].".png";
	 	$pdf->Image($materialbild,$bildx,$bildy,$bildl,$bildb,"","");   
		$pdf->SetFont('Helvetica','',5.5);
		$texty = $bildy-5;
		$pdf->Text($bildx,$texty,utf8_decode($row_rst_stueckliste['hinweis']));
		
		$bildx=$bildx+$bildl+10;
		if ($bildanzahl>=3){
			$bildy=$bildy+$bildb+10;
			$bildanzahl=0;
			$bildx=2;
		}
	}
  
  
  } while ($row_rst_stueckliste = mysql_fetch_assoc($rst_stueckliste));
  } // Show if recordset not empty Stückliste
  
		
} while (  $diff < $druckende);
?>