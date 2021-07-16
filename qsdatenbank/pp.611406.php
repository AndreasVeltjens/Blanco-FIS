<?php require_once('Connections/qsdatenbank.php'); ?>
<?php 
$la="start";
$location="611406";
$url_arb_id="11";
$equipment="Pr&uuml;ffeld Blancotherm";
$fhm_id="1276";
$arbeitsplatz="Elektromontage Aufheiztest";

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


if ((isset($HTTP_POST_VARS["neuermesswert"]))) {

if ($HTTP_POST_VARS['anzahlwerte']==1){
$HTTP_POST_VARS['wert2']=1;
$HTTP_POST_VARS['wert3']=1;
$HTTP_POST_VARS['wert4']=1;
$HTTP_POST_VARS['wert5']=1;
}
if ($HTTP_POST_VARS['anzahlwerte']==2){
$HTTP_POST_VARS['wert3']=1;
$HTTP_POST_VARS['wert4']=1;
$HTTP_POST_VARS['wert5']=1;
}
if ($HTTP_POST_VARS['anzahlwerte']==3){
$HTTP_POST_VARS['wert4']=1;
$HTTP_POST_VARS['wert5']=1;
}
if ($HTTP_POST_VARS['anzahlwerte']==4){
$HTTP_POST_VARS['wert5']=1;
}

  $insertSQL = sprintf("INSERT INTO pruefungdaten (datum, `user`, messwert1, messwert2, messwert3, messwert4, messwert5, id_pruef) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['wert1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert4'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['wert5'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_pruef'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}




include("function.tpl.php");
$suchtextfocus=1; /* setze fokus auf Eingabefeld für RFID- und Barcodescanner */	 
include("header.tpl.php");
/* include("menu.tpl.php");  menü wird ausgeblendet auf 611301*/?>
<?php include('pp.arbeitsplatz.php'); ?>
<hr>
<?php include('pp.mitarbeiter.php'); ?>
<hr>
<?php include('pp.611406.wartung.php'); ?>
<hr>
<?php include('pp.info.php'); ?>
<hr>
<?php include("footer.tpl.php"); ?>