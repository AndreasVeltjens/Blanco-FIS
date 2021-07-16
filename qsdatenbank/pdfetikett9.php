<?php require_once('Connections/qsdatenbank.php'); 

		/* Blanco Layout 10x5 - MaterialNummer + Seriennummer + Fertigungsdatum A+ EAN + Wareneingang  */
		
		
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst2 = "SELECT * FROM wareneingang WHERE wareneingang.we_id='$url_we_id' ";
		$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
		$row_rst2 = mysql_fetch_assoc($rst2);
		$totalRows_rst2 = mysql_num_rows($rst2);

		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.Nummer='$row_rst2[we_nummer]' ";
		$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
		$row_rst4 = mysql_fetch_assoc($rst4);
		$totalRows_rst4 = mysql_num_rows($rst4);
		include ('./libraries/fpdf/fpdf.php');
		
		$diff=0;
		$aaa=0;
		$iii=0;
		$url_ean13=$row_rst4['ean13'];
		
		
		
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
		
		
		
	
			
		
		$pdf->SetFont('Helvetica','',16);
		
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
		} */
		
		
		/* barcode 1 */
		$uuu=1;		
		$url_nummer="".$row_rst2['we_nummer'];	
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,45,1,20,40,"","");
		
		$url_sn_ab= $row_rst2['we_id'];
		
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

		
	 	$pdf->Image($barcodepath,65,1,20,40,"","");
		
		$aaa=$aaa+1;
			if ($aaa==1){/* Anzahl der Ettiketten einer Seriennummer */
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab+1;
			}
		$diff=$diff+1;
		
		$pdf->Text(5,5,"Prüfumfang: ".utf8_decode($row_rst2['we_pruefumfang']));
		$pdf->Text(5,8,"Liefermenge: ".utf8_decode($row_rst2['we_stueckzahl']));
		$pdf->Text(45,5,"");
		$pdf->Text(65,5,"");
	
		
		
			$pdf->SetFont('Helvetica','B',28);
		$pdf->Text(2,19,$row_rst2['we_nummer']);
		$pdf->SetFont('Helvetica','',15);
		$pdf->Text(2,28,"WE ".$row_rst2['we_datum']);
		$pdf->Text(2,33,"ID ".$url_nummer );
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(81,10,"Mitarbeiter ".$row_rst2['we_user'] );
			$pdf->SetFont('Helvetica','B',28);
		
		$pdf->Text(81,25," QS ");
		$pdf->Text(81,35,"FREI");
		
		$pdf->SetFont('Helvetica','',9);
		$pdf->ln(35);
		 $pdf->MultiCell(100,4,utf8_decode(($row_rst2['we_bezeichnung'])),0,"L",0); 
	
		/* $pdf->Write(2,40,utf8_decode($url_bezeichnung),6,"","");  */
		
		
	
		
	
		

		
		
		$pdf->Output();
		exit;
	
		

?>
<?php
mysql_free_result($rst1);
?>

