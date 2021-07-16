<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
?>
<?php require_once('Connections/qsdatenbank.php'); ?>
<?php 
$la="start";
$location="611407";
$url_arb_id="7";
$equipment="Verkaufsstand";
$fhm_id="1279";
$arbeitsplatz="Verkauf an die BLANCO Professional GmbH&Co.KG";

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
if ( $HTTP_POST_VARS["suchtext"]>300000 && $HTTP_POST_VARS["hurl_seite"]<>"" ) {

$suchtxt=$HTTP_POST_VARS["suchtext"];
$suchtxtid=$HTTP_POST_VARS["suchtext"]+0;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_lastverkauf = "SELECT * FROM fertigungsmeldungen WHERE (fertigungsmeldungen.fid ='$suchtxtid' OR fertigungsmeldungen.frfid='$suchtxt') AND fertigungsmeldungen.versendetam='0000-00-00 00:00:00' ";
$rst_lastverkauf = mysql_query($query_rst_lastverkauf, $qsdatenbank) or die(mysql_error());
$row_rst_lastverkauf = mysql_fetch_assoc($rst_lastverkauf);
$totalRows_rst_lastverkauf = mysql_num_rows($rst_lastverkauf);

		if ($totalRows_rst_lastverkauf==1) {
		
			$updateSQL = sprintf("UPDATE fertigungsmeldungen SET verkauftam=%s, verkauftuser=%s, history=%s WHERE fid=%s",
							   "now()",
							   GetSQLValueString($HTTP_POST_VARS['hurl_user_id'],"int"),
							   GetSQLValueString("User ".$HTTP_POST_VARS['hurl_user_id']." - ".$HTTP_POST_VARS['hurl_seite'].";".$rst_lastverkauf['history'], "text"),
							   GetSQLValueString($HTTP_POST_VARS['suchtext'], "int"));
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
		  $oktxt.="Artikel mit  FID $suchtxt verkauft.";
		  $color_status="#00FF00";
		  if ($row_rst_lastverkauf['verkauftam']<>'0000-00-00 00:00:00') {
		  $oktxt.="Warnung: Artikel mit  FID $suchtxt wurde bereits am ".$row_rst_lastverkauf['verkauftam']." verkauft.";
		  $color_status="#FFFF00";
		  }
		} else { 
		$errtxt=$errtxt." Artikel mit FID $suchtxt nicht gefunden.";
		$color_status="#FF0000";
		}

} else { 
		$errtxt=$errtxt." Artikel mit FID $suchtxt nicht gefunden. Lagerort auswählen und Mitarbeiter prüfen.";
		$color_status="#FF0000";
		}

include("function.tpl.php");

$suchtextfocus=1; /* setze fokus auf Eingabefeld für RFID- und Barcodescanner */	 	 
include("header.tpl.php");
/* include("menu.tpl.php");  menü wird ausgeblendet auf 611301*/?>
<?php include('pp.arbeitsplatz.php'); ?>
<hr>
<?php include('pp.mitarbeiter.php'); ?>
<hr>
<?php include('pp.611407.wartung.php'); ?>
<hr>
<?php include('pp.info.php'); ?>
<hr>
<?php include("footer.tpl.php"); ?>
</body>
<?php
mysql_free_result($rst_lastverkauf);
?>
