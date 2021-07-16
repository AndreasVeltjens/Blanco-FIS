<?php require_once('Connections/qsdatenbank.php');

include('IfisArtikelVersionConfig.php');

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
/*  Modul Etikett4 f�r Blanco CS
Seriennummerettikett f�r BLT K oval 52 x 34  */
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
/* lege eine neue SN an */
/* doppelte Ausf�hrung */
/* User, artikel id */
$url_artikelid=$artikelid;

/* lade Stammdaten */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);



/* suche letzte g�ltige SN */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztebekanntesn = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' AND fertigungsmeldungen.fsn <=100000 ORDER BY fertigungsmeldungen.fsn desc";
$letztebekanntesn = mysql_query($query_letztebekanntesn, $qsdatenbank) or die(mysql_error());
$row_letztebekanntesn = mysql_fetch_assoc($letztebekanntesn);
$totalRows_letztebekanntesn = mysql_num_rows($letztebekanntesn);

/* lege Datensatz an */
	$hurl_artikelid=$url_artikelid;
	$hurl_user=$url_user;
	$sn=$row_letztebekanntesn['fsn']+1;
	$frei=1;
	$version=$row_rst4['version'];
	$signatur="611401FISpdfetikett4";
	if ($fsn1<>"") {$sn1=$fsn1;} else {$sn1=" ";}
	$sn2=date("Y/m",time());
	$sn3=" ";
	$sn4=" ";
	$notes=" ";
	$notes1=" ";
	$status="2";
	$frfid=" ";
	$pgeraet="Sichtpruefung bei Montage $location";
	$farbeitsplatz="611401";
	$erstelltdatum=date('Y-m-d H:i:s',time());
	$freigabedatum=date('Y-m-d H:i:s',time());
	$configprofil=json_encode(array("Color"=>$_REQUEST['Color'],"Plug"=>$_REQUEST['Plug'],"Logo"=>$_REQUEST['Logo'],"Artikel"=>$_REQUEST['Artikel']) );

  $insertSQL = sprintf("INSERT INTO fertigungsmeldungen (fartikelid, fuser, fdatum, fsn, version, fsn1, fsn2, fsn3, fsn4,
pgeraet, signatur, farbeitsplatz, fsonder, ffrei, fnotes,fnotes1,status, erstelltdatum, freigabedatum, history ,frfid, configprofil) VALUES ( %s, %s, %s, %s,%s,%s,%s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($hurl_artikelid, "int"),
                       GetSQLValueString($hurl_user, "int"),
                       "now()",
                       GetSQLValueString($sn, "text"),
					   GetSQLValueString($version, "text"),
                       GetSQLValueString($sn1, "text"),
                       GetSQLValueString($sn2, "text"),
                       GetSQLValueString($sn3, "text"),
                       GetSQLValueString($sn4, "text"),
					   GetSQLValueString($pgeraet, "text"),
					   GetSQLValueString($signatur, "text"),
					   GetSQLValueString($farbeitsplatz, "text"),
                       GetSQLValueString(isset($sonder) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($frei) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($notes, "text"),
					   GetSQLValueString($notes1, "text"),
					   GetSQLValueString($status, "int"),
					   GetSQLValueString($erstelltdatum, "text"),
					   GetSQLValueString($freigabedatum, "text"),
					   GetSQLValueString("Montage 611401 User $hurl_user  - $erstelltdatum; Freigabe 611401 User $hurl_user  - $freigabedatum;", "text"),
					   GetSQLValueString($frfid, "text"),
					   GetSQLValueString($configprofil, "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());


/* pr�fe zun�chst ob es Fertigungsmeldungen f�r die Serienummern gibt. */

$check_url_sn_ab=$sn;
$url_sn_bis=$sn;
$url_sn_ab=$sn;


$conf=new IfisFid($configprofil);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

include ('./libraries/fpdf/fpdf.php');
require('./libraries/fpdf/pdf_js.php');




	$logo=1;


		$diff=0;
		$aaa=0;
		$iii=0;
	/* lade Sourcedateien f�r Barcode */
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";
	$pdf =& new FPDF('P','mm','e105x34');

/* do {  es ist nur ein Typenschild im Einst�ckfluss pro Ger�t notwendig*/

		$pdf->AddPage();
		$pdf->SetDisplayMode('real');
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(200);





		if (strlen($url_sn_ab)==1){
			$url_nummer="000".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){
			$url_nummer="0".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==7){
			$url_nummer="00".$url_sn_ab;}
			else{
			$url_nummer="00".$url_sn_ab;
		}

        //setup for manuell config
		$offset=1;
		$offsety=7.3;

		//for ifisautoprint
        $offset=2;
        $offsety=5.6;
do {

		/* lese letzte bekannte FID-ID zum Artikel */
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst21 = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' and fsn='$url_sn_ab' and fertigungsmeldungen.ffrei=1 order by fertigungsmeldungen.fdatum desc" ;
		$rst21 = mysql_query($query_rst21, $qsdatenbank) or die(mysql_error());
		$row_rst21 = mysql_fetch_assoc($rst21);
		$totalRows_rst21 = mysql_num_rows($rst21);

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

		$uuu=0;

	$offset_x=0;

	/* if Artikel No comes from artikelversion configprofil*/
	if (strlen($_REQUEST['Artikel'])>0){$row_rst4['Nummer']=$_REQUEST['Artikel'];}

	$pdf->Line(1+$offset,10+$offsety,50+$offset,10+$offsety);

	$pdf->Image('./picture/update1/' . 'LogoTS2017.jpg', 2 + $offset+$offset_x, 3.4 + $offsety, 50, 5.8, "", "");
	$pdf->Image('./picture/update1/' . 'LogoTS2017_sn.jpg', 29 + $offset+$offset_x, 11 + $offsety, 4, 4, "", "");
	$pdf->Image('./picture/update1/' . 'LogoTS2017_work.jpg', 29 + $offset+$offset_x, 14.5 + $offsety, 4, 4, "", "");

	$pdf->SetFont('Helvetica','',8);

	if (strlen($conf->GetArtikelNummer()) >0){
        $pdf->Text(1+$offset,13.5+$offsety,"Item No. ".$conf->GetArtikelNummer()."-".$conf->GetArtikelPlug() );

    }else{
        $pdf->Text(1+$offset,13.5+$offsety,"Item No. ".$row_rst4['Nummer']."-".str_replace("Version ","",$row_rst21['version']) );

    }

	$pdf->SetFont('Helvetica','',8);
	$pdf->Text(1+$offset,17.5+$offsety,substr(utf8_decode($row_rst4['Bezeichnung']),0,21));


	$pdf->SetFont('Helvetica','',7);
	$pdf->Text(34+$offset,13.5+$offsety,"".$url_nummer);/* SN */
	$pdf->SetFont('Helvetica','',7);
	if ($rep==1) {
		$pdf->Text(34+$offset,17.5+$offsety,"R-".date("m-Y",time()) );
	} else {
		$pdf->Text(34+$offset,17.5+$offsety,date("m-Y",time()) );
	}




// nur auf elektrischen Bauteil
	if ($offset<53){

	if (is_file( './picture/update1/'.'LogoTS2017_info.jpg')){
		$pdf->Image( './picture/update1/' . 'LogoTS2017_info.jpg', 1 + $offset+$offset_x, 21.5 + $offsety, 4, 4, "", "");
		$offset_x+=5;
	}


		$pdf->Text(1+$offset,21.2+$offsety,$row_rst4['leistungsangabe']);

		// $pdf->Text(1+$offset,28+$offsety,$row_rst4['pruefzeichen'])
		if (strpos($row_rst4['pruefzeichen'],"elektro")!== false) {
			if (is_file( './picture/update1/' . 'LogoTS2017_ce.jpg')) {
				$pdf->Image( './picture/update1/' . 'LogoTS2017_ce.jpg', 1 + $offset + $offset_x, 22 + $offsety, 4, 4, "", "");
				$offset_x += 5;
			}
		}
		
		/* soll ab 12/2017 weg
		if (strpos($row_rst4['pruefzeichen'],"gost")!== false) {

			if (is_file( './picture/update1/' . 'LogoTS2017_gost.jpg')) {
				$pdf->Image( './picture/update1/' . 'LogoTS2017_gost.jpg', 1 + $offset + $offset_x, 22 + $offsety, 4, 4, "", "");
				$offset_x += 5;
			}
		}

		*/
		if ((strpos($row_rst4['pruefzeichen'],"x4") !== false) or (strpos($row_rst4['pruefzeichen'],"x6")!== false)) {

			if (is_file( './picture/update1/'.'LogoTS2017_ip.jpg')) {
				$pdf->Image( './picture/update1/' . 'LogoTS2017_ip.jpg', 1 + $offset+$offset_x, 22 + $offsety, 4, 4, "", "");
				$offset_x+=5;

				if (strpos($row_rst4['pruefzeichen'],"x4")!== false) {
					if (is_file( './picture/update1/' . 'LogoTS2017_x4.jpg')) {
						$pdf->Image( './picture/update1/' . 'LogoTS2017_x4.jpg', 1 + $offset + $offset_x, 22 + $offsety, 4, 4, "", "");
						$offset_x += 5;
					}
				}
				if (strpos($row_rst4['pruefzeichen'],"x6")!== false) {
					if (is_file( './picture/update1/'.'LogoTS2017_x6.jpg')) {
						$pdf->Image( './picture/update1/' . 'LogoTS2017_x6.jpg', 1 + $offset+$offset_x, 22 + $offsety, 4, 4, "", "");
						$offset_x+=5;
					}
				}

			}
		}
		
		if (strpos($row_rst4['pruefzeichen'],"vde")!== false) {
			if (is_file( './picture/update1/' . 'LogoTS2017_vde.jpg')) {
				$pdf->Image( './picture/update1/' . 'LogoTS2017_vde.jpg', 1 + $offset + $offset_x, 22 + $offsety, 4, 4, "", "");
				$offset_x += 5;
			}
		}

	/* ab 01/2018 soll EAC Russian TÜV Symbol angezeigt werden*/
		if (strpos($row_rst4['pruefzeichen'],"eac")>0) {
			if (is_file('./picture/update1/' . 'LogoTS2017_eac.jpg')) {
				$pdf->Image('./picture/update1/' . 'LogoTS2017_eac.jpg', 1 + $offset + $offset_x, 22 + $offsety, 4, 4, "", "");
				$offset_x += 5;
			}
		}		
		if (strpos($row_rst4['pruefzeichen'],"elektro")!== false) {
			if (is_file( './picture/update1/' . 'LogoTS2017_weee.jpg')) {
				$pdf->Image( './picture/update1/' . 'LogoTS2017_weee.jpg', 1 + $offset + $offset_x, 22 + $offsety, 4, 4, "", "");
				$offset_x += 5;
			}
		}
	} 
	/*
	if (strlen($row_rst4['traglast'])>0) {
		if (is_file( './picture/update1/'.'LogoTS2017_load.jpg')) {
			$pdf->Image( './picture/update1/' . 'LogoTS2017_load.jpg', 1 + $offset+$offset_x, 22 + $offsety, 4, 4, "", "");
			$offset_x+=5;
			$pdf->SetFont('Helvetica','',7);
			$pdf->Text(1 + $offset+$offset_x, 22 + $offsety+2,str_replace("max","",$row_rst4['traglast']));
			$pdf->Text(3 + $offset+$offset_x, 22 + $offsety+4,  "max.");
		}
	}
	*/



	$pdf->SetFont('Helvetica','',7);

		/* Erg�nzung T.Dieter nur auf dem Elektrischenbauteil die Leistungsangabe 02-2011 */
		if ($offset==1){
					//$pdf->Text(1+$offset,21,$row_rst4['leistungsangabe']);
					//$barcodepath="picture/eti_".$row_rst4['pruefzeichen'].".jpg";
					//$pdf->Image($barcodepath,1+$offset,23,45,7,"","");
					//$pdf->Text(1+$offset,23,$row_rst4['traglast']);
					//$pdf->SetFont('Helvetica','',8);
		}else{

		if ($row_rst21['fid']>0) {
					/* Barcode auf Typenschild  */
					include('barcode/barcodeI25_1.php'); /* horizonale Ausrichtung Barcode */
					$barcodepath="barcode/testbildh".$iii.".png";   /* Barcode auf Typenschild */
					/* merke FID String */
					$barcodepathforsn=$barcodepath;
					$im=$e->Stroke($row_rst21['fid'],"barcode/testbildh".$iii.".png");
	 				/* 	$pdf->Image($barcodepath,25,5,20,40,"","");*/
					$pdf->Image($barcodepath,55,21+$offsety,45,10,"","");
					$pdf->SetFont('Helvetica','',7);
					}else{
					$pdf->SetFont('Helvetica','',7);
					$pdf->Text(55,21.5," FID noch nicht vergeben.");/* FID-Text */
					}

		}

		$pdf->SetFont('Helvetica','',7);


		/* barcode 1 */
		$uuu=1;
		 $url_nummer="".$url_nummer;

		
		 $offset=$offset+52; /* offset in Pixel 148+ 5 mm rand =50 Pixel */

	}while ($offset<=60); /* Anzahl der wiederholung */

			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab;
			$diff=$diff+1;

/* } while ( ($url_sn_ab<=$url_sn_bis )or $diff == 100); */

/* $pdf->AutoPrint(); */
		$pdf->Output('./print/typenschild/drucker1/'.date("Y-m-d-h-min",time())."-".$sn.".pdf",'F');


/* Verpackung */


		/* Blanco Layout 10x5 - MaterialNummer + Seriennummer + Fertigungsdatum A+ EAN  */
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
		$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
		$row_rst4 = mysql_fetch_assoc($rst4);
		$totalRows_rst4 = mysql_num_rows($rst4);


		$diff=0;
		$aaa=0;
		$iii=0;
		$url_ean13=$row_rst4['ean13'];

		/* if Artikel No comes from artikelversion configprofil*/
		if (strlen($_REQUEST['Artikel'])>0){
			$row_rst4['Nummer']=$_REQUEST['Artikel'];
			$row_rst4['Zeichnungsnummer']= $_REQUEST['Artikel'];
		}

		if ($row_rst4['Zeichnungsnummer']==""){ $row_rst4['Zeichnungsnummer']=$row_rst4['Nummer'];}
		$url_artikelnummer=$row_rst4['Zeichnungsnummer'];


		$url_nummer=$sn;
		$url_sn_von=$sn;
		$url_sn_bis=$sn;



	/* lade Sourcedateien f�r Barcode */

	$pdf =& new FPDF('P','mm','e10x5');
do {  /* Anzahl der Seriennummernettiketten pro Ger�t */


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
	 	$pdf->Image($barcodepath,85,1,10,30,"","");



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
            $pdf->Text(86,2,"EAN13");
		}


/* barcode 1 */
		$uuu=1;
		$url_nummer="".$url_artikelnummer;

		/* Einfügen Barcode Materialnummer
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,45,1,10,30,"","");
		$pdf->Text(45,2,"Material-Nr.");

		Anzahl der Ettiketten einer Seriennummer */


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
	 	$pdf->Image($barcodepath,65,1,10,30,"","");

		$aaa=$aaa+1;
		if ($aaa==$row_rst4['anzahl_etikett_verpackung']){
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab;
		}

		$pdf->Text(65,2,"Serien-Nr.");


/* Materialnummer aus Produktionswerk anzeigen Text mit Version */

		if ($row_rst4['Nummer']<>$row_rst4['Zeichnungsnummer']){
				$pdf->SetFont('Helvetica','',6);
				$pdf->Text(40,35,"BCK".$row_rst4['Nummer']."-".str_replace("Version ","",$row_rst21['version']) );
				$pdf->SetFont('Helvetica','B',22);
				$pdf->Text(2,17,$row_rst4['Zeichnungsnummer']."-".str_replace("Version ","",$row_rst21['version']) );
		} else {
				$pdf->SetFont('Helvetica','B',22);

            if (strlen($conf->GetArtikelNummer()) >0 ){
                $pdf->Text(1,17,$conf->GetArtikelNummer()."-".$conf->GetArtikelPlug() );
            }else{
				$pdf->Text(1,17,$url_artikelnummer."-".str_replace("Version ","",$row_rst21['version']));
            }
		}

		$pdf->SetFont('Helvetica','',15);
		$pdf->Text(2,30,"FD ".date("d-m-Y",time() ));
/* Ausgabe Seriennumer */
		$sntxt=$url_nummer;
		$pdf->Text(2,35,"SN ".$sntxt);

		$pdf->SetFont('Helvetica','',9);
		$pdf->ln(36);
		$pdf->MultiCell(100,4,utf8_decode(($row_rst4['Bezeichnung'])),0,"L",0);
		$pdf->SetFont('Helvetica','',10);
		/* $pdf->Write(2,40,utf8_decode($url_bezeichnung),6,"","");  */

/* anzeigen der Pr�fzeichen  */

		$barcodepath="picture/eti_".$row_rst4['pruefzeichen'].".jpg";
		$pdf->Image($barcodepath,0,18,45,7,"","");
/* anzeigen FID Barcode */
/* anzeigen FID Barcode */

 /* Fehlerabfrage, wenn Parameter Anzahl der Etiketten falsch */
		if (($row_rst4['anzahl_etikett_verpackung'])=='') {$row_rst4['anzahl_etikett_verpackung']=1;}
		$druckende=$row_rst4['anzahl_etikett_verpackung'];

					$pdf->Image($barcodepathforsn,75,31,30,10,"","");
					$pdf->SetFont('Helvetica','',6);
					$pdf->Text(69,39,"  ".$druckende."-FID");/* FID-Text */
		

		
/* logo anzeigen */
		
		if($row_rst4['logo']=="BCK"){
		
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		 
		}elseif ($row_rst4['logo']=="BLANCOCS") {
		
		$pdf->Image('picture/logo-sw.jpg',-2,1,32,6,"",""); 
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS GmbH & Co.KG ");  */
		 
		}else{
		/* Standardlayout */
		
		/* $pdf->Image('picture/logo-sw.jpg',2,1,30,5,"","");  */
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		}
		
		
		$diff=$diff+1;
	
  
		
} while (  $diff < $druckende);
		
	$pdf->Output('./print/verpackung/drucker2/'.date("Y-m-d-h-min",time())."-".$sn.".pdf",'F');
			

if ($row_rst4['dokuset']==1){ 
	include('pdf.dokuset.php');			
	$pdf->Output('./print/dokuset/drucker10/'.date("Y-m-d-h-min",time())."-".$sn.".pdf",'F');			
}			


 $updateGoTo = "pp.print.php".substr($editFormAction,39,1000)."&oktxt=Druck abgeschlossen. letzte Artikel:".$row_rst4['Nummer'];
header(sprintf("Location: %s", $updateGoTo)); 


?>