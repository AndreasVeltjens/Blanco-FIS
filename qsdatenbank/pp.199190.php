<?php require_once('Connections/qsdatenbank.php'); ?>
<?php 
$la="start";
$location="199190";
$url_arb_id="7";
$equipment="Verpackungswagen";
$fhm_id="1270";
$arbeitsplatz="Ersatzteile, Zubeh&ouml;r und K&uuml;hlplattenverpackung";

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

/* gehe zum Typenschilddruck */
if (isset($HTTP_POST_VARS["neugeraet"]) && $HTTP_POST_VARS["hurl_artikelid"]>0 && $HTTP_POST_VARS["hurl_seite"]<>"" ) {

$updateGoTo = "pp.199190.pdf.sn.php?".substr($HTTP_POST_VARS["hurl_seite"],27,1000)."&fsn1=".$HTTP_POST_VARS["suchtext"]."&fsn2=".$HTTP_POST_VARS["suchtext2"]."&fsn3=".$HTTP_POST_VARS["suchtext3"]."&location=".$location;
header(sprintf("Location: %s", $updateGoTo));
$oktxt.="Aufkleber gedruckt.";
}

if ($HTTP_POST_VARS["nachdrucken"]){$action="nachdrucken";}

if (isset($HTTP_POST_VARS["nachdrucken"]) && $HTTP_POST_VARS["hurl_artikelid"]>0 && $HTTP_POST_VARS["suchtext"]>0 && $HTTP_POST_VARS["hurl_seite"]<>"" ) {

$updateGoTo = "pp.199190.pdf.sn.php?".substr($HTTP_POST_VARS["hurl_seite"],27,1000)."&fsn1=".$HTTP_POST_VARS["suchtext"]."&location=".$location."&nachdruck=1";
header(sprintf("Location: %s", $updateGoTo));
$oktxt.="Aufkleber und Dokuset gedruckt.";
}

include("function.tpl.php");

$suchtextfocus=1; /* setze fokus auf Eingabefeld für RFID- und Barcodescanner */	 
include("header.tpl.php");
/* include("menu.tpl.php");  menü wird ausgeblendet auf 611301*/?>


<?php include('pp.arbeitsplatz.php'); ?>
<hr>
<?php include('pp.mitarbeiter.php'); ?>
<hr>
<?php include('pp.199190.wartung.php'); ?>
<hr>
<?php include('pp.info.php'); ?>
<hr>
<?php include("footer.tpl.php"); ?>
</body>