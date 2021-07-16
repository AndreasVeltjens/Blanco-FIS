<?php require_once('Connections/qsdatenbank.php'); 
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




/* Wenn nachdruck angew�hlt ist suche  g�ltige SN */
if ( $nachdruck==1 && $fsn1>0 ) {

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztebekanntesn = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' AND fertigungsmeldungen.fsn =$fsn1 ORDER BY fertigungsmeldungen.fdatum desc";
$letztebekanntesn = mysql_query($query_letztebekanntesn, $qsdatenbank) or die(mysql_error());
$row_letztebekanntesn = mysql_fetch_assoc($letztebekanntesn);
$totalRows_letztebekanntesn = mysql_num_rows($letztebekanntesn);

$sn= $fsn1;
/* Wenn nachdruck angew�hlt ist suche  g�ltige SN */
} elseif ($freigabe==1 && $ffid>0 ) {

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztebekanntesn = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fid =$ffid ORDER BY fertigungsmeldungen.fdatum desc";
$letztebekanntesn = mysql_query($query_letztebekanntesn, $qsdatenbank) or die(mysql_error());
$row_letztebekanntesn = mysql_fetch_assoc($letztebekanntesn);
$totalRows_letztebekanntesn = mysql_num_rows($letztebekanntesn);

$sn= $row_letztebekanntesn['fsn'];
$url_artikelid=$row_letztebekanntesn['fartikelid'];
/* lade Stammdaten */	
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

} else {


/* suche letzte g�ltige SN */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztebekanntesn = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' AND fertigungsmeldungen.fsn <=100000 ORDER BY fertigungsmeldungen.fsn desc";
$letztebekanntesn = mysql_query($query_letztebekanntesn, $qsdatenbank) or die(mysql_error());
$row_letztebekanntesn = mysql_fetch_assoc($letztebekanntesn);
$totalRows_letztebekanntesn = mysql_num_rows($letztebekanntesn);

/* ansonsten lege Datensatz an */
	$hurl_artikelid=$url_artikelid;
	$hurl_user=$url_user;
	$sn=$row_letztebekanntesn['fsn']+1;
	$frei=1;
	$version=$row_rst4['version'];
	$signatur="pp.611404.pdf.sn";
	if ($fsn1<>"") {$sn1=$fsn1;} else {$sn1=" ";}
	if ($fsn2<>"") {$sn2=$fsn2;} else {$sn2=date("Y/m",time());}/* 2. Eingabefeld */
	if ($fsn3<>"") {$sn3=$fsn3;} else {$sn3=" ";}
	$sn4=" ";
	$notes=" ";
	$notes1=" ";
	$status="2";
	$pgeraet="Sichtpr�fung bei Ersatzteilverpackungsstation";
	$farbeitsplatz="$location";
	$erstelltdatum=date('Y-m-d H:i:s',time());
	$freigabedatum=date('Y-m-d H:i:s',time());

	

  $insertSQL = sprintf("INSERT INTO fertigungsmeldungen (fartikelid, fuser, fdatum, fsn, version, fsn1, fsn2, fsn3, fsn4, pgeraet, signatur, farbeitsplatz, fsonder, ffrei, fnotes,fnotes1,status, erstelltdatum, freigabedatum, history, frfid) VALUES (%s,%s,%s,%s,%s,%s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
					   GetSQLValueString("Montage 611404 User $hurl_user  - $erstelltdatum; Freigabe 611404 User $hurl_user  - $freigabedatum;", "text"),
					   GetSQLValueString($frfid, "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

} /* ende neu anlegen */

/* pr�fe zun�chst ob es Fertigungsmeldungen f�r die Serienummern gibt. */
$check_url_sn_ab=$sn;
$url_sn_bis=$sn;
$url_sn_ab=$sn;
if (($sn>0) && ($totalRows_letztebekanntesn)>0) {
if ($row_rst4['anzahl_etikett_typenschild']>=5) {$row_rst4['anzahl_etikett_typenschild']=1;}
if ($row_rst4['anzahl_etikett_typenschild']=='') {$row_rst4['anzahl_etikett_typenschild']=1;}

include ('./libraries/fpdf/fpdf.php');
require('./libraries/fpdf/pdf_js.php');

	$logo=$row_rst4['logo'];	
	$diff=0;
	$aaa=0;
	$iii=0;


	$offset=2; /*Abstand x-Achse*/
	$offsety=8; /*Abstand von Oben*/

	$offset_x=0; /*Abstand zwischen den Etiketten */
	$offsettext=1; /*Abstand zum nächsten Zeichen */


	/* lade Sourcedateien f�r Barcode */
	include "../src/jpgraph.php";
	include "../src/jpgraph_canvas.php";
	include "../src/jpgraph_barcode.php";
	$pdf =& new FPDF('P','mm','e105x34');
/*   es ist nur ein Typenschild im Einst�ckfluss pro Ger�t notwendig*/
		$pdf->AddPage();
		$pdf->SetDisplayMode('real');
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(10);
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

	do {
		/* lese letzte bekannte FID-ID zum Artikel */
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst21 = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' and fsn='$url_sn_ab' order by fertigungsmeldungen.fdatum desc" ;
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

		$pdf->Line(1+$offset,10+$offsety,60+$offset,10+$offsety);

		$pdf->Image('./picture/update1/' . 'LogoTS2017.jpg', 2+ $offset+$offset_x, 3.4 + $offsety, 50, 5.8, "", "");
		$pdf->Image('./picture/update1/' . 'LogoTS2017_sn.jpg', 29 + $offset+$offset_x, 10.2 + $offsety, 5, 5, "", "");
		$pdf->Image('./picture/update1/' . 'LogoTS2017_work.jpg', 29 + $offset+$offset_x, 14.5 + $offsety, 5, 5, "", "");

		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(1+$offset+$offsettext,14+$offsety,"Item No. ".$row_rst4['Nummer']."-".str_replace("Version ","",$row_rst21['version']) );

		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(1+$offset+$offsettext,18+$offsety,substr(utf8_decode($row_rst4['Bezeichnung']),0,20));


		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(34+$offset+$offsettext,14+$offsety,"".$url_nummer);/* SN */
		$pdf->SetFont('Helvetica','',7);
		if ($rep==1) {
			$pdf->Text(34+$offset+$offsettext,18+$offsety,"R-".date("m-Y",time()) );
		} else {
			$pdf->Text(34+$offset+$offsettext,18+$offsety,date("m-Y",time()) );
		}

		
// nur auf elektrischen Bauteil
		if ($offset < 20){
            $offset_x=$offset;

			if (is_file( './picture/update1/'.'LogoTS2017_info.jpg')){
				$pdf->Image( './picture/update1/' . 'LogoTS2017_info.jpg', 1 + $offset+$offset_x, 19 + $offsety, 4, 4, "", "");
				$offset_x+=5;
			}

			
			$pdf->Text(1+$offset,22+$offsety,$row_rst4['leistungsangabe']);

			// $pdf->Text(1+$offset,28+$offsety,$row_rst4['pruefzeichen'])
			if (strpos($row_rst4['pruefzeichen'],"elektro")>0) {
				if (is_file( './picture/update1/' . 'LogoTS2017_ce.jpg')) {
					$pdf->Image( './picture/update1/' . 'LogoTS2017_ce.jpg', 1 + $offset + $offset_x, 19 + $offsety, 4, 4, "", "");
					$offset_x += 5;
				}
			}
			/* 
			ab 12/2017 entfernt
			
			if (strpos($row_rst4['pruefzeichen'],"gost")>0) {

				if (is_file( './picture/update1/' . 'LogoTS2017_gost.jpg')) {
					$pdf->Image( './picture/update1/' . 'LogoTS2017_gost.jpg', 1 + $offset + $offset_x, 24 + $offsety, 4, 4, "", "");
					$offset_x += 5;
				}
			}

			*/
			if ((strpos($row_rst4['pruefzeichen'],"x4")>0) or (strpos($row_rst4['pruefzeichen'],"x6")>0)) {

				if (is_file( './picture/update1/'.'LogoTS2017_ip.jpg')) {
					$pdf->Image( './picture/update1/' . 'LogoTS2017_ip.jpg', 1 + $offset+$offset_x, 19 + $offsety, 4, 4, "", "");
					$offset_x+=5;

					if (strpos($row_rst4['pruefzeichen'],"x4")>0) {
						if (is_file( './picture/update1/' . 'LogoTS2017_x4.jpg')) {
							$pdf->Image( './picture/update1/' . 'LogoTS2017_x4.jpg', 1 + $offset + $offset_x, 19 + $offsety, 4, 4, "", "");
							$offset_x += 5;
						}
					}
					if (strpos($row_rst4['pruefzeichen'],"x6")>0) {
						if (is_file( './picture/update1/'.'LogoTS2017_x6.jpg')) {
							$pdf->Image( './picture/update1/' . 'LogoTS2017_x6.jpg', 1 + $offset+$offset_x, 19 + $offsety, 4, 4, "", "");
							$offset_x+=5;
						}
					}

				}
			}
			if (strpos($row_rst4['pruefzeichen'],"vde")>0) {
				if (is_file( './picture/update1/' . 'LogoTS2017_vde.jpg')) {
					$pdf->Image( './picture/update1/' . 'LogoTS2017_vde.jpg', 1 + $offset + $offset_x, 19 + $offsety, 4, 4, "", "");
					$offset_x += 5;
				}
			}
				/* ab 01/2018 soll EAC Russian TÜV Symbol angezeigt werden*/
			if (strpos($row_rst4['pruefzeichen'],"eac")>0) {
				if (is_file('./picture/update1/' . 'LogoTS2017_eac.jpg')) {
					$pdf->Image('./picture/update1/' . 'LogoTS2017_eac.jpg', 1 + $offset + $offset_x, 19 + $offsety, 4, 4, "", "");
					$offset_x += 5;
				}
			}		
			if (strpos($row_rst4['pruefzeichen'],"elektro")>0) {
				if (is_file( './picture/update1/' . 'LogoTS2017_weee.jpg')) {
					$pdf->Image( './picture/update1/' . 'LogoTS2017_weee.jpg', 1 + $offset + $offset_x, 19 + $offsety, 4, 4, "", "");
					$offset_x += 5;
				}
			}
		}
		
		/* ab 2017 entfernt
		if (strlen($row_rst4['traglast'])>0) {
			if (is_file( './picture/update1/'.'LogoTS2017_load.jpg')) {
				$pdf->Image( './picture/update1/' . 'LogoTS2017_load.jpg', 1 + $offset+$offset_x, 22 + $offsety, 4, 4, "", "");
				$offset_x+=5;
				$pdf->SetFont('Helvetica','',7);
				$pdf->Text(1 + $offset+$offset_x, 24 + $offsety+2,str_replace("max","",$row_rst4['traglast']));
				$pdf->Text(3 + $offset+$offset_x, 24 + $offsety+4,  "max.");
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

				if ($row_rst4['hinweis']=="WSE") {
					$pdf->SetFont('Helvetica','B',12);
					$pdf->Text(4+$offset,7+$offsety,"250V / 6,3A / T / H");
					$pdf->Text(10+$offset,26+$offsety,"H1");
					$pdf->Text(30+$offset,26+$offsety,"F1");
					$pdf->SetFont('Helvetica','',8);
				}else {
					/* Barcode auf Typenschild  */
					include('barcode/barcodeI25_1.php'); /* horizonale Ausrichtung Barcode */
					$barcodepath = "barcode/testbildh" . $iii . ".png";   /* Barcode auf Typenschild */
					/* merke FID String */
					$barcodepathforsn = $barcodepath;
					$im = $e->Stroke($row_rst21['fid'], "barcode/testbildh" . $iii . ".png");
					/* 	$pdf->Image($barcodepath,25,5,20,40,"","");*/
					$pdf->Image($barcodepath, 55, 27, 45, 10, "", "");
				}
				$pdf->SetFont('Helvetica','',7);
			}else{
				$pdf->SetFont('Helvetica','',7);
				$pdf->Text(55,24,"FID noch nicht vergeben.");/* FID-Text */
			}

		}

		$pdf->SetFont('Helvetica','',7);

		$uuu=1;
		$url_nummer="".$url_nummer;
		$offset=$offset+52; /* offset in Pixel 148+ 5 mm rand =50 Pixel */
		$offsettext=$offsettext+0;
        $offset_x=0;        /* Rücksetzen der Einzeliconabstände */


	}while ($offset<=60); /* Anzahl der wiederholung */
			
			
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab;
			$diff=$diff+1;
		
/* $pdf->AutoPrint(); */
		if ($row_rst4['anzahl_etikett_typenschild']==0) {
		}else{
			$pdf->Output('./print/typenschild/drucker8/'.date("Y-m-d-h-min",time())."-".$sn.".pdf",'F');
		}		
		
/* Verpackung */
/* Blanco Layout 10x5 - MaterialNummer + Seriennummer + Fertigungsdatum A+ EAN  */
		$diff=0;
		$aaa=0;
		$iii=0;
		$url_ean13=$row_rst4['ean13'];
		$url_artikelnummer=$row_rst4['Nummer'];
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
	/* $pdf->Text(86,1,"EAN13"); */
		}
		/* barcode 1 */
		$uuu=1;		
		$url_nummer="".$url_artikelnummer;	

		/* Barcode Materialnummer
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,45,1,10,30,"","");
        */

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
	 	$pdf->Image($barcodepath,68,1,10,30,"","");
		
		$aaa=$aaa+1;
/* Anzahl der Ettiketten einer Seriennummer */
		if ($aaa==4 or $row_rst4['anzahl_etikett_verpackung']==$aaa){
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab;
		}
/* Materialnummer aus Produktionswerk anzeigen Text */		
		if ($row_rst4['Nummer']<>$row_rst4['Zeichnungsnummer']){
				$pdf->SetFont('Helvetica','',6);
				$pdf->Text(40,35,"BCK".$row_rst4['Nummer']."-".str_replace("Version ","",$row_rst21['version']) );
				$pdf->SetFont('Helvetica','B',24);	
				$pdf->Text(2,18,$row_rst4['Zeichnungsnummer']."-".str_replace("Version ","",$row_rst21['version']) );
		} else {
				$pdf->SetFont('Helvetica','B',24);	
				$pdf->Text(2,18,$url_artikelnummer."-".str_replace("Version ","",$row_rst21['version']));
		}
/* Fertigungsdatum ausgeben */			
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
		if ($row_rst4['pruefzeichen']<>"kein"){
			$barcodepath="picture/eti_".$row_rst4['pruefzeichen'].".jpg";
			$pdf->Image($barcodepath,0,19,45,7,"","");
		}
/* wennn Nachdruck entfernt am 2015-03-10*/		
		/* if ($nachdruck==1) {
			$pdf->Text(40,40,"WD-Druck" );
		} */
/* anzeigen FID Barcode */

		$pdf->Image($barcodepathforsn,66,35,30,10,"","");
		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(65,40," ");/* FID-Text */
		
/* logo anzeigen */
		
		if ($row_rst4['logo']=="BCK" or $row_rst4['logo']=="BPK"){
		
		$pdf->SetFont('Helvetica','',5.5);
		/*  $pdf->Text(2,10,"BLANCO CS Kunststofftechnik GmbH ");  */
		 
		}elseif ($row_rst4['logo']=="BLANCOCS" or $row_rst4['logo']=="BPRO") {
		
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
	  if ($row_rst4['anzahl_etikett_verpackung']>=5) {$row_rst4['anzahl_etikett_verpackung']=5;}
	  if ($row_rst4['anzahl_etikett_verpackung']=='') {$row_rst4['anzahl_etikett_verpackung']=1;}
		
} while ( $diff < ($row_rst4['anzahl_etikett_verpackung']) );

if ($row_rst4['anzahl_etikett_verpackung']==0) {
}else{		
	$pdf->Output('./print/verpackung/drucker7/'.date("Y-m-d-h-min",time())."-$diff-".$url_artikelnummer."-".$sn.".pdf",'F');
}

if ($row_rst4['dokuset']==1){
	include('pdf.dokuset.php');			
	$pdf->Output('./print/dokuset/drucker10/'.date("Y-m-d-h-min",time())."-".$sn.".pdf",'F');
}


$updateGoTo = "pp.print.php".substr($editFormAction,33,1000)."&oktxt=Pdf SN $sn erzeugt. Typenschildanzahl: ".$row_rst4['anzahl_etikett_typenschild']." Verpackung: ".$row_rst4['anzahl_etikett_verpackung']." und Dokuset: ".$row_rst4['dokuset'];
}else{
$updateGoTo = "pp.print.php".substr($editFormAction,33,1000)."&oktxt=Druckfehler. Es gibt kein Artikel mit SN $sn. Bitte Eingabe wiederholen.";
}

header(sprintf("Location: %s", $updateGoTo)); 	
?>