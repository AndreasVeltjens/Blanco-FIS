<?php require_once('Connections/qsdatenbank.php'); 

include('IfisArtikelVersionConfig.php');
/*  Modul Etikett4 f�r Blanco CS 
Seriennummerettikett f�r BLT K oval 52 x 34  */	
/* doppelte Ausf�hrung */
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
	
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

if ($row_rst4['pruefzeichen']=="" ) {
?>
<p><img src="file://///cde9aw01/qsdatenbank/picture/error.gif" width="16" height="16"> <font color="#FF0000">Diese 
  Druckvorlage ist nicht f&uuml;r die ausw&auml;hlte Artikelnummer vorgesehen.</font></p>
<p>Grund: keine Pr&uuml;fzeichen vergeben. &Auml;ndern Sie die Artikeleigenschaften 
  Feld:['pruefzeichen'].<br>
</p>
<p> <a href="Javascript:window.close()"><img src="file://///cde9aw01/qsdatenbank/picture/primary.gif" width="21" height="17" border="0" align="absmiddle">neuen 
  Artikel ausw&auml;hlen.</a></p>
<?php
}

if ($controll==1 ) {
?>
<p><img src="file://///cde9aw01/qsdatenbank/picture/error.gif" width="16" height="16"> <font color="#FF0000">Diese 
  Druckvorlage ist nicht f&uuml;r das gew&auml;hlte Ger&auml;t mit der Seriennummer 
  vorgesehen.</font></p>
<p>Grund: Es wurde keine g&uuml;ltige Fertigungsmeldung f&uuml;r Ger&auml;t mit 
  Seriennummer -<?php echo $controll_id; ?>- gefunden.<br>
  Legen Sie die Fertigungsmeldung an und drucken Sie erneut.<br>
</p>
<p>Pr&uuml;fen Sie, ob die Fertigungsmeldung als Status i.O. hat, da f&uuml;r 
  n.i.O. ger&auml;te keine Typenschilder gedruckt werden d&uuml;rfen.</p>
<p> <a href="Javascript:window.close()"><img src="file://///cde9aw01/qsdatenbank/picture/primary.gif" width="21" height="17" border="0" align="absmiddle">neues 
  Ger&auml;t ausw&auml;hlen.</a></p>
<?php
}


	
include ('./libraries/fpdf/fpdf.php');
require('./libraries/fpdf/pdf_js.php');

class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
    $script = "var pp = getPrintParams();";
    if($dialog)
        $script .= "pp.interactive = pp.constants.interactionLevel.full;";
    else
        $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
    $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
    $script .= "print(pp);";
    $this->IncludeJS($script);
}
}
		
		

		$diff=0;
		$aaa=0;
		$iii=0;
	/* lade Sourcedateien f�r Barcode */	
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";
	$pdf =& new PDF_AutoPrint('P','mm','e105x34');
	
do {
	
		$pdf->AddPage();
		$pdf->SetDisplayMode('real');
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(10);
		
		
		
		
		
		if (strlen($url_sn_ab)==1){		
			$url_nummer="000".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==3){		
			$url_nummer="0".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==5){		
			$url_nummer="00".$url_sn_ab;}
			elseif (strlen($url_sn_ab)==7){		
			$url_nummer="00".$url_sn_ab;}
			else{
			$url_nummer="0".$url_sn_ab;
		}
	
		
		$offset=0;
		$offsety=1;
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

	include("./pdf.update1.php");

	/* Erg�nzung T.Dieter nur auf dem Elektrischenbauteil die Leistungsangabe 02-2011 */
	if ($offset>1){

		if ($row_rst21['fid']>0) {
				 /* Barcode auf Typenschild  */
			include('barcode/barcodeI25_1.php'); /* horizonale Ausrichtung Barcode */
			$barcodepath="barcode/testbildh".$iii.".png";   /* Barcode auf Typenschild */
			$im=$e->Stroke($row_rst21['fid'],"barcode/testbildh".$iii.".png");
			/* 	$pdf->Image($barcodepath,25,5,20,40,"","");*/
			$pdf->Image($barcodepath,55,21+$offsety,45,10,"","");
			$pdf->SetFont('Helvetica','',7);
		}else{
			$pdf->SetFont('Helvetica','',7);
			$pdf->Text(55,24+$offsety,"  FID noch nicht vergeben.");/* FID-Text */
		}
	}
	$uuu=1;
	$url_nummer="".$url_nummer;
	$offset=$offset+53; /* offset in Pixel 148+ 5 mm rand =50 Pixel */

	}while ($offset<=60); /* Anzahl der wiederholung */
			
			$aaa=0;
			$iii=$iii+1;
			$url_sn_ab=$url_sn_ab+1;
			$diff=$diff+1;
			
} while ( ($url_sn_ab<=$url_sn_bis )or $diff == 100);
		
		$pdf->AutoPrint();
		$pdf->Output();
		exit;

mysql_free_result($rst1);
mysql_free_result($rst4);
?>