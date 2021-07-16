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
	/* lade Artikeldaten */	
/* User, artikel id */
$url_artikelid=$artikelid;

/* lade Stammdaten */	
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

$url_artikelnummer=$row_rst4['Nummer'];

/* suche letzte gültige SN */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztebekanntesn = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' AND fertigungsmeldungen.fsn <=100000 ORDER BY fertigungsmeldungen.fsn desc";
$letztebekanntesn = mysql_query($query_letztebekanntesn, $qsdatenbank) or die(mysql_error());
$row_letztebekanntesn = mysql_fetch_assoc($letztebekanntesn);
$totalRows_letztebekanntesn = mysql_num_rows($letztebekanntesn);


/* lege Datensatz an */
	$hurl_artikelid=$url_artikelid;
	$hurl_user=$url_user;
	$sn=$row_letztebekanntesn['fsn']+1;
	$frei=0;
	$version=$row_rst4['version'];
	$signatur="61140FISpdfetikett3";
	if ($fsn1<>"") {$sn1=$fsn1;} else {$sn1=" ";}
	$sn2=date("Y/m",time());
	$sn3=" ";
	$sn4=" ";
	$notes=" ";
	$notes1=" ";
	$status="1";
	$pgeraet="In Arbeit nehmen am Arbeitsplatz $location";
	$farbeitsplatz=$location;
	$erstelltdatum=date('Y-m-d H:i:s',time());

  $insertSQL = sprintf("INSERT INTO fertigungsmeldungen (fartikelid, fuser, fdatum, fsn, version, fsn1, fsn2, fsn3, fsn4, pgeraet, signatur, farbeitsplatz,fsonder, ffrei, fnotes,fnotes1, status,erstelltdatum, history, frfid) VALUES (%s,%s,%s,%s,%s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
						"$frei",
                     
                       GetSQLValueString($notes, "text"),
					   GetSQLValueString($notes1, "text"),
					   GetSQLValueString($status, "int"),
					    GetSQLValueString($erstelltdatum, "text"),
						GetSQLValueString("Arbeitsanfang $location User $hurl_user  - $erstelltdatum; ", "text"),
					   GetSQLValueString($frfid, "text"));

		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
			
		$url_sn_ab=$row_letztebekanntesn['fsn']+1;
		$url_sn_bis=$row_letztebekanntesn['fsn']+1;
		$url_nummer=$row_letztebekanntesn['fsn']+1;

		$diff=0;
		$aaa=0;
		$iii=0;
/* lade Sourcedateien für Barcode */
		include ('./libraries/fpdf/fpdf.php');	
		include "../src/jpgraph.php";
		include "../src/jpgraph_canvas.php";
		include "../src/jpgraph_barcode.php";
/* lade Template für Layout */
		include ('./pdf.HSN.php');	
/* Export */
		$pdf->Output('./print/verpackung/drucker3/'.date("Y-m-d-h-min",time())."-".$url_nummer.".pdf",'F');
		$updateGoTo = "pp.print.php".substr($editFormAction,39,1000)."&oktxt=SN erzeugt.";
		header(sprintf("Location: %s", $updateGoTo)); 
?><?php
mysql_free_result($rst1);
?>