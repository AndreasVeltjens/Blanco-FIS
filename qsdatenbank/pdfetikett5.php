<?php require_once('Connections/qsdatenbank.php'); 

/*  Modul Etikett5 für Blanco CS 
Hinweis für BLT Umluftheizung Bei Reparaturen durch andere Stellen */	
	
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

if ($row_rst4['hinweis']=="" ) {
?>
<p><img src="picture/error.gif" width="16" height="16"> <font color="#FF0000">Diese 
  Druckvorlage ist nicht f&uuml;r die ausw&auml;hlte Artikelnummer vorgesehen.</font></p>
<p>Grund: keine Hinweis vergeben. &Auml;ndern Sie die Artikeleigenschaften Feld:['hinweis'].<br>
</p>
<p> <a href="Javascript:window.close()"><img src="picture/primary.gif" width="21" height="17" border="0" align="absmiddle">neuen 
  Artikel ausw&auml;hlen.</a></p>
<?php
}

	
include ('./libraries/fpdf/fpdf.php');
		
		$diff=0;
		$aaa=0;
		$iii=0;
	/* lade Sourcedateien für Barcode */	
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";
	$pdf =& new FPDF('P','mm','e77x39');
do {
	
		$pdf->AddPage();
		$pdf->SetDisplayMode('real');
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(10);
		
		$pdf->SetFont('Helvetica','',10);
		
		
		
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
		/* $barcodepath="barcode/testbild".$iii.".png"; */
		$pdf->SetFont('Helvetica','',6);
		
		
		$pdf->Text(4,5,"Bei Reparaturen durch andere Stellen erlischt die Gewährleistung.");
		$pdf->Text(4,10,"The warranty will be invalidated if the unit is repaired by anyone else.");
		$pdf->Text(4,15,"Dans le cas d`unde réparation par d´autres personnes, la garantie s´annule.");
		$pdf->Text(4,20,"Se láppareechio viene riparato da altri, la garanzia diventa nulla.");
		$pdf->Text(4,25,"Si Las reparaciones son realizadas por otras personas, se perderá el ");
		$pdf->Text(4,27,"Derecho a garantia.");
		$pdf->Text(4,31,"Bij reparaties door anderen vervalt de garantie.");
		$pdf->Text(4,35,"Hvis reparation udfores af andre, bortfalder garantien.");
		
		$barcodepath="picture/eti_pfeil.jpg";
	 	$pdf->Image($barcodepath,70,0,5,20,"",""); 
		
		/* barcode 1 */
		$uuu=1;		
		/* $url_nummer="".$url_artikelnummer;	
		include('barcode/barcodeI25_0.php'); */
		/* $barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,5,5,20,40,"",""); */
		
		
		
			
		$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);


$pdf->SetFont('Helvetica','',12);
/* $im=$e->Stroke($url_nummer,"barcode/testbild".$iii.".png"); */

		
	 /* 	$pdf->Image($barcodepath,25,5,20,40,"","");
		$pdf->Image($barcodepath,85,5,20,40,"",""); */
		/* $aaa=$aaa+1;
			if ($aaa==2){ */
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab+1;
			/* } */
		$diff=$diff+1;
		/* $pdf->Text(25,10,$url_nummer);
		$pdf->Text(25,42,$url_nummer);
		$pdf->Text(85,10,$url_nummer);
		$pdf->Text(85,42,$url_nummer); */
		/* $pdf->SetFont('Helvetica','',28); */
		/* $pdf->Text(45,40,$url_nummer); */
		
		/* $pdf->SetFont('Helvetica','',12); */
/* $pdf->Text(30,6,"$url_bezeichnung"); */
/* 	$pdf->Image('picture/logo.jpg',2,3,20,5,"","");
		 */
} while ( ($url_sn_ab<=$url_sn_bis )or $diff == 100);
		
		
		$pdf->Output();
		exit;
	
		

?>
<?php
mysql_free_result($rst1);
mysql_free_result($rst4);
?>

