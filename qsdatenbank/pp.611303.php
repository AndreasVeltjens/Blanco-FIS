<?php require_once('Connections/qsdatenbank.php'); ?>
<?php 
$la="start";
$location="611303";
$url_arb_id="6";
$equipment="Fr&auml;sen";
$fhm_id="20";
$arbeitsplatz="Fr&auml;sen";

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

include("function.tpl.php");
$suchtextfocus=1; /* setze fokus auf Eingabefeld f?r RFID- und Barcodescanner */	 
include("header.tpl.php");
/* include("menu.tpl.php");  men? wird ausgeblendet auf 611301*/?>
<?php include('pp.arbeitsplatz.php'); ?>
<hr>
<?php include('pp.mitarbeiter.php'); ?>
<hr>
<?php include('pp.611303.wartung.php'); ?>
<hr>
<?php include('pp.info.php'); ?>
<hr>
<?php include("footer.tpl.php"); ?>