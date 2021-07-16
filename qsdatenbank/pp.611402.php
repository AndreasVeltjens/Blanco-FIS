<?php require_once('Connections/qsdatenbank.php'); ?>
<?php 
$la="start";
$location="611402";
$url_arb_id="7";
$equipment="Schrauber Elektro/Druckluft";
$fhm_id="1255";
$arbeitsplatz="Heizmodulmontage Blancotherm Umluft";

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

$updateGoTo = "pdfetikett3automatisch.php?".substr($HTTP_POST_VARS["hurl_seite"],27,1000)."&fsn1=".$HTTP_POST_VARS["suchtext"]."&location=$location";
header(sprintf("Location: %s", $updateGoTo));
$oktxt.="neues Ger&auml;t angelegt.";
}

if ($HTTP_POST_VARS["fidfreigeben"]){$action="auftragsliste";}
if ($HTTP_POST_VARS["fidspeichern"]){$action="auftragbearbeiten";}

if (isset($HTTP_POST_VARS["fidfreigeben"]) && $HTTP_POST_VARS["hurl_artikelid"]>0 && $HTTP_POST_VARS["hfid"]>0 && strlen($HTTP_POST_VARS["fsn1"])>2 && $HTTP_POST_VARS["hurl_seite"]<>"" ) {

	$updateSQL = sprintf("UPDATE fertigungsmeldungen SET freigabeuser=%s, freigabedatum=%s, version=%s, fsn1=%s, 
	fsn2=%s, fsn3=%s, fsn4=%s, fsonder=%s, ffrei=%s, fnotes=%s, freigabedatum=%s, pgeraet=%s, fnotes1=%s, frfid=%s, status=%s WHERE fid=%s",
						   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString(date("Y-m-d",time()), "date"),
                       GetSQLValueString($HTTP_POST_VARS['version'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fsn1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fsn2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fsn3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fsn4'], "text"),
                       "0",
                       "1",
                       GetSQLValueString($HTTP_POST_VARS['notes'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s',time()), "text"),
                       GetSQLValueString($HTTP_POST_VARS['pgeraet'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['notes1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['frfid'], "int"),
                       "2",
                       GetSQLValueString($HTTP_POST_VARS['hfid'], "int"));

	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
	  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

$updateGoTo = "pp.611404.pdf.sn.php?".substr($HTTP_POST_VARS["hurl_seite"],27,1000)."&ffid=".$HTTP_POST_VARS["hfid"]."&location=".$location."&freigabe=1";
header(sprintf("Location: %s", $updateGoTo));
$oktxt.="Aufkleber und Dokuset gedruckt.";
}

if (isset($HTTP_POST_VARS["fidspeichern"]) && $HTTP_POST_VARS["hurl_artikelid"]>0 && $HTTP_POST_VARS["hfid"]>0 && strlen($HTTP_POST_VARS["fsn1"])>2 && $HTTP_POST_VARS["hurl_seite"]<>"" ) {

	$updateSQL = sprintf("UPDATE fertigungsmeldungen SET freigabeuser=%s, freigabedatum=%s, version=%s, fsn1=%s, 
	fsn2=%s, fsn3=%s, fsn4=%s, fsonder=%s, ffrei=%s, fnotes=%s, freigabedatum=%s, pgeraet=%s, fnotes1=%s, frfid=%s, status=%s WHERE fid=%s",
						   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString(date("Y-m-d",time()), "date"),
                       GetSQLValueString($HTTP_POST_VARS['version'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fsn1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fsn2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fsn3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fsn4'], "text"),
                       "0",
                       "0",
                       GetSQLValueString($HTTP_POST_VARS['notes'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s',time()), "text"),
                       GetSQLValueString($HTTP_POST_VARS['pgeraet'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['notes1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['frfid'], "int"),
                       "1",
                       GetSQLValueString($HTTP_POST_VARS['hfid'], "int"));

	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
	  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
	  $oktxt.="Daten gespeichert.";
	
}


include("function.tpl.php");

$suchtextfocus=1; /* setze fokus auf Eingabefeld für RFID- und Barcodescanner */	 
include("header.tpl.php");
/* include("menu.tpl.php");  menü wird ausgeblendet auf 611301*/?>


<?php include('pp.arbeitsplatz.php'); ?>
<hr>
<?php include('pp.mitarbeiter.php'); ?>
<hr>
<?php include('pp.611402.wartung.php'); ?>
<hr>
<?php include('pp.info.php'); ?>
<hr>
<?php include("footer.tpl.php"); ?>
</body>