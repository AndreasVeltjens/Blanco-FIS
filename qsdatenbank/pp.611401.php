<?php require_once('Connections/qsdatenbank.php'); ?>
<?php 
$la="start";
$location="611401";
$url_arb_id="7";
$equipment="Schrauber Elektro/Druckluft";
$fhm_id="1252";
$arbeitsplatz="Montage Blancotherm";

include('./IfisArtikelVersionConfig.php');

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

if ($artikelid>0) {
  $artikel="";
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $query_rstv = "SELECT artikeldaten.anzahlp, artikeldaten.artikelid, artikeldaten.link_dokument, artikeldaten.version, artikeldaten.Bezeichnung,
                artikeldaten.Nummer, artikelversionen.configprofil  FROM  artikeldaten, artikelversionen
                  WHERE artikeldaten.artikelid = '$artikelid'
                  and artikeldaten.artikelid= artikelversionen.artikelid
                  AND artikeldaten.version= artikelversionen.version";
  $rstv = mysql_query($query_rstv, $qsdatenbank) or die(mysql_error());
  $row_rstv = mysql_fetch_assoc($rstv);
  $totalRows_rstv = mysql_num_rows($rstv);

  $configprofilv = new IfisArtikelVersionConfig($row_rstv['configprofil']);
  $artikel=$configprofilv->FindArtikelNummer($_POST["Color"],$_POST["Plug"],$_POST["Logo"]);
}



/* gehe zum Typenschilddruck */
if (isset($_POST["neugeraet"]) && $_POST["hurl_artikelid"]>0 && $_POST["hurl_seite"]<>"" ) {

$updateGoTo = "pdfetikett4automatisch.php?".substr($_POST["hurl_seite"],27,1000)."&fsn1=".$_POST["suchtext"]."&Color=".$_POST["Color"]."&Logo=".$_POST["Logo"]."&Plug=".$_POST["Plug"]."&Artikel=".$artikel;
header(sprintf("Location: %s", $updateGoTo));
$oktxt.="neues Ger&auml;t angelegt.";
}

/* gehe zum Typenschilddruck / Dokumentdruck */
if (isset($_POST["nachdrucken"]) && $_POST["hurl_artikelid"]>0 && $_POST["suchtext"]>0 && $_POST["hurl_seite"]<>"" ) {

$updateGoTo = "pdfetikett4automatisch.php?".substr($_POST["hurl_seite"],27,1000)."&fsn1=".$_POST["suchtext"];
header(sprintf("Location: %s", $updateGoTo));
$oktxt.="Ger&auml;t dokumente nochmals angelegt.";
}

include("function.tpl.php");

$suchtextfocus=1; /* setze fokus auf Eingabefeld f�r RFID- und Barcodescanner */	 	 
include("header.tpl.php");
/* include("menu.tpl.php");  men� wird ausgeblendet auf 611301*/?>


<?php include('pp.arbeitsplatz.php'); ?>
<hr>
<?php include('pp.mitarbeiter.php'); ?>
<hr>
<?php include('pp.611401.wartung.php'); ?>
<hr>
<?php include('pp.info.php'); ?>
<hr>
<?php include("footer.tpl.php"); ?>
</body>