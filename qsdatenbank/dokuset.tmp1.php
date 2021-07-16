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


/* lade Stammdaten */	
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);



/* suche letzte gültige SN */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztebekanntesn = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' AND fertigungsmeldungen.fsn <=100000 ORDER BY fertigungsmeldungen.fsn desc";
$letztebekanntesn = mysql_query($query_letztebekanntesn, $qsdatenbank) or die(mysql_error());
$row_letztebekanntesn = mysql_fetch_assoc($letztebekanntesn);
$totalRows_letztebekanntesn = mysql_num_rows($letztebekanntesn);
	
$diff=0;
$aaa=0;
$iii=0;

if ($url_sn>0){
	$sn=$url_sn;
}else{
	$sn=1;
}
/* lade Sourcedateien für Barcode */

include ('./libraries/fpdf/fpdf.php');
require('./libraries/fpdf/pdf_js.php');
	
	
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";

if ($row_rst4['dokuset']==1){
	include('pdf.dokuset.php');			
	$pdf->Output();
		exit;
}

?>