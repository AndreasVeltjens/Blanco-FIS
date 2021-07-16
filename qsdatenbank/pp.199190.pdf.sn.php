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
/*  Modul Etikett4 für Blanco CS 
Seriennummerettikett für BLT K oval 52 x 34  */
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
/* lege eine neue SN an */
/* doppelte Ausführung */
/* User, artikel id */
$url_artikelid=$artikelid;

/* lade Stammdaten */	
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);




/* Wenn nachdruck angewählt ist suche  gültige SN */
if ( $nachdruck==1 && $fsn1>0 ) {

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztebekanntesn = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' AND fertigungsmeldungen.fsn =$fsn1 ORDER BY fertigungsmeldungen.fdatum desc";
$letztebekanntesn = mysql_query($query_letztebekanntesn, $qsdatenbank) or die(mysql_error());
$row_letztebekanntesn = mysql_fetch_assoc($letztebekanntesn);
$totalRows_letztebekanntesn = mysql_num_rows($letztebekanntesn);

$sn= $fsn1;

} else {


/* suche letzte gültige SN */
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
	$signatur="pp.199190.pdf.sn";
	if ($fsn1<>"") {$sn1=$fsn1;} else {$sn1=" ";}
	if ($fsn2<>"") {$sn2=$fsn2;} else {$sn2=date("Y/m",time());}/* 2. Eingabefeld */
	if ($fsn3<>"") {$sn3=$fsn3;} else {$sn3=" ";}
	$sn4=" ";
	$notes=" ";
	$notes1=" ";
	$status="2";
	$pgeraet="Sichtprüfung bei Ersatzteilverpackungsstation";
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

/* prüfe zunächst ob es Fertigungsmeldungen für die Serienummern gibt. */

$check_url_sn_ab=$sn;
$url_sn_bis=$sn;
$url_sn_ab=$sn;


if (($sn>0) && ($totalRows_letztebekanntesn)>0) {

if ($row_rst4['anzahl_etikett_typenschild']>=5) {$row_rst4['anzahl_etikett_typenschild']=1;}
if ($row_rst4['anzahl_etikett_typenschild']=='') {$row_rst4['anzahl_etikett_typenschild']=1;}


include ('./libraries/fpdf/fpdf.php');
require('./libraries/fpdf/pdf_js.php');

	
		
		
	$logo=1;	
		
		
		$diff=0;
		$aaa=0;
		$iii=0;
/* lade Sourcedateien für Barcode */	
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";
	$pdf =& new FPDF('P','mm','e105x34');
	
/*   es ist nur ein Typenschild im Einstückfluss pro Gerät notwendig*/
	
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
	
		
		$offset=1;
		

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
		/* $barcodepath="barcode/testbild".$iii.".png";   Barcode auf Typenschild*/
		$pdf->SetFont('Helvetica','',8);
		$pdf->Text(1+$offset,15,substr(utf8_decode($row_rst4['Bezeichnung']),0,21));
		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(1+$offset,18,"MAT ".$row_rst4['Nummer']);
		$pdf->SetFont('Helvetica','',7);
		
		/* Ergänzung T.Dieter nur auf dem Elektrischenbauteil die Leistungsangabe 02-2011 */
		if ($offset==1){
					$pdf->Text(1+$offset,21,$row_rst4['leistungsangabe']);
					$barcodepath="picture/eti_".$row_rst4['pruefzeichen'].".jpg";
					$pdf->Image($barcodepath,1+$offset,23,45,7,"","");  
					$pdf->Text(1+$offset,23,$row_rst4['traglast']);
					$pdf->SetFont('Helvetica','',8);
		}else{
		
		if ($row_rst21['fid']>0) {
					/* Barcode auf Typenschild  */	
					include('barcode/barcodeI25_1.php'); /* horizonale Ausrichtung Barcode */
					$barcodepath="barcode/testbildh".$iii.".png";   /* Barcode auf Typenschild */
					/* merke FID String */
					$barcodepathforsn=$barcodepath;
					$im=$e->Stroke($row_rst21['fid'],"barcode/testbildh".$iii.".png");
	 				/* 	$pdf->Image($barcodepath,25,5,20,40,"","");*/
					$pdf->Image($barcodepath,57,23,40,10,"","");
					$pdf->SetFont('Helvetica','',7);
					$pdf->Text(56,26,"FID");/* FID-Text */
					}else{
					$pdf->SetFont('Helvetica','',7);
					$pdf->Text(56,24,"FID noch nicht vergeben.");/* FID-Text */
					}
					$pdf->Text(1+$offset,21,$row_rst4['traglast']);
		
		}
			
		$pdf->SetFont('Helvetica','',8);
		$pdf->Text(30+$offset,15,"SN-".$url_nummer."-");  /* SN */
		$pdf->SetFont('Helvetica','',7);
		
		
		if ($nachdruck==1) {
			$pdf->Text(20+$offset,18,"WD-Druck" );
		}
		
		if ($rep==1) {
		$pdf->Text(27+$offset,18,"Rep.-FD ".date("m-Y",time()) );
		} else {
		$pdf->Text(32+$offset,18,"FD ".date("m-Y",time()) );
		}
		
/* barcode 1 */
		$uuu=1;		
		 $url_nummer="".$url_nummer;
		if($row_rst4['logo']=="BCK"){
		
		$pdf->SetFont('Helvetica','',4.8);
		 $pdf->Text(1+$offset,11,"BLANCO Professional Kunststofftechnik GmbH - D-04319 Leipzig");
		 $pdf->Line(0+$offset,12,50+$offset,12); 
		 
		}elseif ($row_rst4['logo']=="BLANCOCS") {
		
		if ($logo==1){
		$pdf->Image('picture/logo-sw.jpg',15+$offset,3,32,6,"",""); 
		$pdf->SetFont('Helvetica','',5.2);
		 $pdf->Text(1+$offset,11,"BLANCO Professional GmbH & Co.KG  D-75038 Oberderdingen");
		 $pdf->Line(0+$offset,12,50+$offset,12); }
		 
		}else{
/* Standardlayout */
			if ($logo==1){
			$pdf->Image('picture/logo-sw.jpg',15+$offset,3,32,6,"",""); 
			$pdf->SetFont('Helvetica','',4.8);
			 $pdf->Text(1+$offset,11,"BLANCO Professional Kunststofftechnik GmbH - D-04319 Leipzig");
			 $pdf->Line(0+$offset,12,55+$offset,12); 
			}
		}
		 $offset=$offset+53; /* offset in Pixel 148+ 5 mm rand =50 Pixel */

	}while ($offset<=60); /* Anzahl der wiederholung */
			
			
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab;
			$diff=$diff+1;
		
/* $pdf->AutoPrint(); */
if ($row_rst4['anzahl_etikett_typenschild']==0) {
}else{
		$pdf->Output('./print/verpackung/drucker11/'.date("Y-m-d h-min",time())."-".$sn.".pdf",'F');
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
		
		
		
	/* lade Sourcedateien für Barcode */	

	$pdf =& new FPDF('P','mm','e10x5');
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
	$pdf->Text(86,1,"EAN13");
		}
		
		
		/* barcode 1 */
		$uuu=1;		
		$url_nummer="".$url_artikelnummer;	
		include('picture/barcodeI25_0.php');
		$barcodepath="barcode/testbild.png";
	 	$pdf->Image($barcodepath,45,1,10,30,"","");
		
		
		
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

		
	 	$pdf->Image($barcodepath,65,1,10,30,"","");
		
		$aaa=$aaa+1;
/* Anzahl der Ettiketten einer Seriennummer */
		
		if ($aaa==4 or $row_rst4['anzahl_etikett_verpackung']==$aaa){
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab;
		}
		
		$pdf->Text(45,1,"Material-Nr. ".$row_rst4['anzahl_etikett_verpackung']);
		$pdf->Text(65,1,"Serien-Nr.");
	
		
		
		$pdf->SetFont('Helvetica','B',28);
		$pdf->Text(2,16,$url_artikelnummer);
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

/* anzeigen der Prüfzeichen  */

		$barcodepath="picture/eti_".$row_rst4['pruefzeichen'].".jpg";
		$pdf->Image($barcodepath,0,18,45,7,"","");
		
		
		if ($nachdruck==1) {
			$pdf->Text(40,40,"WD-Druck" );
		}
/* anzeigen FID Barcode */

		$pdf->Image($barcodepathforsn,66,35,30,10,"","");
		$pdf->SetFont('Helvetica','',7);
		$pdf->Text(65,40,"FID");/* FID-Text */
		
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
	  if ($row_rst4['anzahl_etikett_verpackung']>=5) {$row_rst4['anzahl_etikett_verpackung']=5;}
	  if ($row_rst4['anzahl_etikett_verpackung']=='') {$row_rst4['anzahl_etikett_verpackung']=1;}
		
} while ( $diff < ($row_rst4['anzahl_etikett_verpackung']) );

if ($row_rst4['anzahl_etikett_verpackung']==0) {
}else{		
	$pdf->Output('./print/verpackung/drucker11/'.date("Y-m-d-h-i-s",time())."-$diff-".$url_artikelnummer."-".$sn.".pdf",'F');
}

if ($row_rst4['dokuset']==1){
	include('pdf.dokuset.php');			
	$pdf->Output('./print/verpackung/drucker11/'.date("Y-m-d-h-i-s",time())."-".$sn.".pdf",'F');
}


$updateGoTo = "pp.print.php".substr($editFormAction,33,1000)."&oktxt=Pdf SN $sn erzeugt.";
}else{
$updateGoTo = "pp.print.php".substr($editFormAction,33,1000)."&oktxt=Druckfehler. Es gibt kein Artikel mit SN $sn.";
}

header(sprintf("Location: %s", $updateGoTo)); 	
?>