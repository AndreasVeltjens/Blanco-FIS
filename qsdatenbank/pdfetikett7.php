<?php require_once('Connections/qsdatenbank.php'); 

/* pr�fe zun�chst ob es Fertigungsmeldungen f�r die Serienummern gibt. */
$check_url_sn_ab=$url_sn_ab;
do {
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' and fsn='$check_url_sn_ab' and fertigungsmeldungen.ffrei=1" ;
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

if ($totalRows_rst2==0){
$controll=1;
$controll_id = $check_url_sn_ab;
}

$check_url_sn_ab=$check_url_sn_ab+1;
	
} while ( ($check_url_sn_ab<=$url_sn_bis )or $diff == 100);
	




if ($controll==1 ) {
?>
<p><img src="picture/error.gif" width="16" height="16"> <font color="#FF0000">Diese 
  Druckvorlage ist nicht f&uuml;r das gew&auml;hlte Ger&auml;t mit der Seriennummer 
  vorgesehen.</font></p>
<p>Grund: Es wurde keine g&uuml;ltige Fertigungsmeldung f&uuml;r Ger&auml;t mit 
  Seriennummer -<?php echo $controll_id; ?>- gefunden.<br>
  Legen Sie die Fertigungsmeldung an und drucken Sie erneut.<br>
</p>
<p>Pr&uuml;fen Sie, ob die Fertigungsmeldung als Status i.O. hat, da f&uuml;r 
  n.i.O. ger&auml;te keine Typenschilder gedruckt werden d&uuml;rfen.</p>
<p> <a href="Javascript:window.close()"><img src="picture/primary.gif" width="21" height="17" border="0" align="absmiddle">neues 
  Ger&auml;t ausw&auml;hlen.</a></p>
<?php
}




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
		
		
		
	/* lade Sourcedateien f�r Barcode */	
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";
include("IfisArtikelVersionConfig.php");

	$pdf =& new FPDF('P','mm','e10x5');
do {


		$pdf->SetLeftMargin(0);
		$pdf->SetTopMargin(1);
		$pdf->SetDisplayMode('real');
/* $pdf->SetMargins(0.1,0.1,0.1,0.1); */
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(200);
		$pdf->SetFont('Helvetica','',16);
		
/* EAN13 Code einf�gen */
		if ($url_ean13>0 ){
		$uuu=1;		
		$url_nummer=substr($url_ean13,0,12);
		
		include('picture/barcodeEAN13.php');
		$barcodepath="barcode/testbildEAN13.png";
	 	$pdf->Image($barcodepath,85,1,15,30,"","");

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
			$pdf->Text(85,3,"EAN13");
		}

    /* lese letzte bekannte FID-ID zum Artikel */
    mysql_select_db($database_qsdatenbank, $qsdatenbank);
    $query_rst21 = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' and fsn='$url_nummer' and fertigungsmeldungen.ffrei=1 order by fertigungsmeldungen.fdatum desc" ;
    $rst21 = mysql_query($query_rst21, $qsdatenbank) or die(mysql_error());
    $row_rst21 = mysql_fetch_assoc($rst21);
    $totalRows_rst21 = mysql_num_rows($rst21);

    $conf=new IfisFid($row_rst21['configprofil']);


    /* barcode 1 */
		$uuu=1;
        if (strlen($conf->GetArtikelNummer()) >0 ) {
            $url_nummer=$conf->GetArtikelNummer()+0;
        }else {
            $url_nummer = "" . $url_artikelnummer + 0;
        }
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,45,1,15,30,"","");
		
		
		
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
			
		$pdf->SetFont('Helvetica','',8);
		$im=$e->Stroke($url_nummer,"barcode/testbild".$iii.".png");

		
	 	$pdf->Image($barcodepath,67,1,15,30,"","");
		
		$aaa=$aaa+1;
			if ($aaa==1){/* Anzahl der Ettiketten einer Seriennummer */
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab+1;
			}
		$diff=$diff+1;
		$pdf->Text(46,3,"Material-Nr.");
		$pdf->Text(65,3,"Serien-Nr.");
	

/* Materialnummer aus Produktionswerk anzeigen Text */		
		if ($row_rst4['Nummer']<>$row_rst4['Zeichnungsnummer']){
				$pdf->SetFont('Helvetica','',6);
				$pdf->Text(40,35,"BCK".$row_rst4['Nummer']."-".str_replace("Version ","",utf8_decode($row_rst21['version'])) );
				$pdf->SetFont('Helvetica','B',22);	
				$pdf->Text(2,17,$row_rst4['Zeichnungsnummer']."-".str_replace("Version ","",utf8_decode($row_rst21['version'])) );
		} else {
				$pdf->SetFont('Helvetica','B',22);

            if (strlen($conf->GetArtikelNummer()) >0 ){
                $pdf->Text(1, 17,  $conf->GetArtikelNummer() . "-" .$conf->GetArtikelPlug());
            }else {
                $pdf->Text(1, 17, $url_artikelnummer . "-" . str_replace("Version ", "", utf8_decode($row_rst21['version'])));
            }
		}
		$pdf->SetFont('Helvetica','',15);
		
		$fddatum=substr($row_rst21['freigabedatum'],0,10);
			if ($fddatum=="0000-00-00"){
				$fddatum=date("m-Y",time());
			}
		
		$pdf->Text(2,28,"FD ".$fddatum);
		$pdf->Text(2,33,"SN ".$url_nummer);
		$pdf->SetFont('Helvetica','',9);
		$pdf->ln(35);
		$pdf->MultiCell(100,4,substr(utf8_decode($row_rst4['Bezeichnung']),0,95),0,"L",0); 
		$pdf->SetFont('Helvetica','',10);
		/* $pdf->Write(2,40,utf8_decode($url_bezeichnung),6,"","");  */
		
		
/* FID - Barcode auf   */


/* 2of5 umwandlung der Zahl */
		if (strlen($row_rst21['fid'])==1){		
					$row_rst21['fid']="000".$row_rst21['fid'];}
		elseif (strlen($row_rst21['fid'])==3){		
					$row_rst21['fid']="0".$row_rst21['fid'];}
		elseif (strlen($row_rst21['fid'])==5){		
					$row_rst21['fid']="0".$row_rst21['fid'];}
		elseif (strlen($row_rst21['fid'])==7){		
					$row_rst21['fid']="0".$row_rst21['fid'];}
		else{
					$row_rst21['fid']="00".$row_rst21['fid'];
		}
				if ($row_rst21['fid']>0) {

						 	
		include('barcode/barcodeI25_1.php'); /* horizonale Ausrichtung Barcode */
		$barcodepath="barcode/testbildh".$iii.".png";   /* Barcode auf Typenschild */
		$im=$e->Stroke($row_rst21['fid'],"barcode/testbildh".$iii.".png");
/* 	$pdf->Image($barcodepath,25,5,20,40,"","");*/
		$pdf->Image($barcodepath,65,31,30,10,"","");
		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(63,32,"FID");/* FID-Text */
		}else{
		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(53,36,"FID noch nicht vergeben.");/* FID-Text */
		}
	
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
	
		
} while ( ($url_sn_ab<=$url_sn_bis )or $diff == 100);
		
		
		$pdf->Output();
		exit;
	
		

?>
<?php
mysql_free_result($rst1);
?>

