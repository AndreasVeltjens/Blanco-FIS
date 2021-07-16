<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
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

if ((isset($HTTP_POST_VARS['delpos']) && $HTTP_POST_VARS['hurl_varid']>0 )) {
  $deleteSQL = sprintf("DELETE FROM fehlermeldungsdaten WHERE varid=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_varid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
    $oktxt=$oktxt."ausgewählte Position gelöscht. ";
}

$la = "la42";
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
/* Aktualisiere Prozess bei einzelwahl */
if ((isset($HTTP_POST_VARS["prozessedit"]))) {
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm2variante=%s, fm2user=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['select4'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fm2user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}
/* Aktualisiere Varianten bei einzelwahl */
if ((isset($HTTP_POST_VARS["insertvariante"]))) {


 include("la42.tmp2.php");
    $oktxt=$oktxt."Positionsdaten für Kostenvoranschlag aus Stammdatensatz kopiert.  FM".$HTTP_POST_VARS['hurl_fmid'];
}



if ((isset($HTTP_POST_VARS["addpos"] ))) {
  $insertSQL = sprintf("INSERT INTO fehlermeldungsdaten (varname, varbeschreibung, Kosten1, Datum, lokz, entid, fmid) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['varname'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibungpos'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['Kosten1'], "double"),
                       "now()",
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['frei']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
    $oktxt=$oktxt."Positionsdaten für Kostenvoranschlag aus Stammdatensatz kopiert.  ";
}

if ($HTTP_POST_VARS['select2']>0){
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$suche =$HTTP_POST_VARS['select2'];
$query_rstkundenR = "SELECT * FROM kundendaten WHERE kundendaten.idk=$suche";
$rstkundenR = mysql_query($query_rstkundenR, $qsdatenbank) or die(mysql_error());
$row_rstkundenR = mysql_fetch_assoc($rstkundenR);
$totalRows_rstkundenR = mysql_num_rows($rstkundenR);

$HTTP_POST_VARS['rechnung'] = utf8_decode($row_rstkundenR['firma']);
$HTTP_POST_VARS['anprechpartner'] = utf8_decode($row_rstkundenR['ansprechpartner']);
$HTTP_POST_VARS['anrede'] = utf8_decode($row_rstkundenR['anrede']);
$HTTP_POST_VARS['rstrasse'] = utf8_decode($row_rstkundenR['strasse']);
$HTTP_POST_VARS['rplz'] = utf8_decode($row_rstkundenR['plz']);
$HTTP_POST_VARS['rort'] = utf8_decode($row_rstkundenR['ort']);
$HTTP_POST_VARS['rland'] = utf8_decode($row_rstkundenR['land']);
$HTTP_POST_VARS['rfax'] = utf8_decode($row_rstkundenR['faxnummer']);
$HTTP_POST_VARS['debitorennummer'] = utf8_decode($row_rstkundenR['kundenummer']);
/* $HTTP_POST_VARS['fm2notes']= $HTTP_POST_VARS['fm2notes'].utf8_decode($row_rstkundenR['bemerkung']); */
$HTTP_POST_VARS['fm2notes']=utf8_decode($row_rstkundenR['bemerkung']);



if ($HTTP_POST_VARS['checkbox']==1){
	$HTTP_POST_VARS['lieferung'] =utf8_decode($row_rstkundenR['firma']);
	$HTTP_POST_VARS['lstrasse'] = utf8_decode($row_rstkundenR['strasse']);
	$HTTP_POST_VARS['lplz'] = utf8_decode($row_rstkundenR['plz']);
	$HTTP_POST_VARS['lort'] = utf8_decode($row_rstkundenR['ort']);
	$HTTP_POST_VARS['lland'] = utf8_decode($row_rstkundenR['land']);
	$HTTP_POST_VARS['debitorennummer2'] = utf8_decode($row_rstkundenR['kundenummer']);
	$HTTP_POST_VARS['select_idk2'] = $HTTP_POST_VARS['select_idk'];
	}
}

if ($HTTP_POST_VARS['select3']>0 ){
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$suche =$HTTP_POST_VARS['select3'];
$query_rstkundenL = "SELECT * FROM kundendaten WHERE kundendaten.idk=$suche";
$rstkundenL = mysql_query($query_rstkundenL, $qsdatenbank) or die(mysql_error());
$row_rstkundenL = mysql_fetch_assoc($rstkundenL);
$totalRows_rstkundenL = mysql_num_rows($rstkundenL);

$HTTP_POST_VARS['lieferung'] = utf8_decode($row_rstkundenL['firma']);
$HTTP_POST_VARS['lstrasse'] = utf8_decode($row_rstkundenL['strasse']);
$HTTP_POST_VARS['lplz'] = utf8_decode($row_rstkundenL['plz']);
$HTTP_POST_VARS['lort'] = utf8_decode($row_rstkundenL['ort']);
$HTTP_POST_VARS['lland'] = utf8_decode($row_rstkundenL['land']);
$HTTP_POST_VARS['fm2notes']= $HTTP_POST_VARS['fm2notes'].utf8_decode($row_rstkundenL['bemerkung']);
$HTTP_POST_VARS['debitorennummer2']=$row_rstkundenL['kundenummer'];

}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["saveaktuell"])) or (isset($HTTP_POST_VARS["save"])) or  (isset($HTTP_POST_VARS["cancel2"] )) or (isset($HTTP_POST_VARS["save1"])) or (isset($HTTP_POST_VARS["save2"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save4"])) or (isset($HTTP_POST_VARS["save5"])) or  (isset($HTTP_POST_VARS["save6"] )) or  (isset($HTTP_POST_VARS["save7"] )) or  (isset($HTTP_POST_VARS["save8"] )) or  (isset($HTTP_POST_VARS["save9"] ))) {  
  if (($HTTP_POST_VARS['fm2datum']=="0000-00-00")&&($HTTP_POST_VARS['fm2']==1)) {$today="now()";
   $oktxt=$oktxt."Dokumentdatum heute gesetzt.  ";
  } else { $today=GetSQLValueString($HTTP_POST_VARS['fm2datum'], "date");}
  
  if ($HTTP_POST_VARS["checkbox"]==1){
  $HTTP_POST_VARS['lieferung']=$HTTP_POST_VARS['rechnung'];
  $HTTP_POST_VARS['lstrasse']=$HTTP_POST_VARS['rstrasse'];
  $HTTP_POST_VARS['lplz']=$HTTP_POST_VARS['rplz'];
  $HTTP_POST_VARS['lort']=$HTTP_POST_VARS['rort'];
  $HTTP_POST_VARS['lland']=$HTTP_POST_VARS['rland'];
  $HTTP_POST_VARS['debitorennummer2']=$HTTP_POST_VARS['debitorennummer'];

  $oktxt=$oktxt."Lieferaddresse vom Rechnungsempfänger kopiert. ";
 }
  
  /* Kostenstellenprüfung */
  
if ($HTTP_POST_VARS['sapkst']==0 && $HTTP_POST_VARS['fm2variante']<>0){
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT entscheidung.gvwl, entscheidung.sapkst, entscheidung.entid FROM entscheidung WHERE entscheidung.entid='$HTTP_POST_VARS[fm2variante]'";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

$HTTP_POST_VARS['sapkst']=$row_rst8['sapkst'];
$oktxt=$oktxt."Kostenstelle aktualisiert. ";
}
/* Ende Kostenstellenprüfung */  
  
  /* Prüfe und anlegen von neuen Positionsdaten */
 if  ($HTTP_POST_VARS['fm2variante']>0 && $HTTP_POST_VARS['oldfm2variante']==0){
 include("la42.tmp1.php");
 }/* Ende von Prüfe und anlegen von neuen Positionsdaten */
 
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm2=%s, fm2variante=%s, fm2datum=%s, fm2user=%s, fm2notes=%s, fm12notes=%s, 
  sapsd=%s, sapkst=%s, debitorennummer=%s, debitorennummer2=%s, rechnung=%s, rstrasse=%s, ansprechpartner=%s,
  anrede=%s, rplz=%s, rort=%s, rland=%s, lieferung=%s, lstrasse=%s, lplz=%s, lort=%s, lland=%s, faxnummer=%s,
  intern=%s,alternativerechnung=%s, vkz=%s, fmsg=%s , idk=%s, idk2=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fm2'],"int"),
                       GetSQLValueString($HTTP_POST_VARS['fm2variante'], "int"),
                       $today,
                       GetSQLValueString($HTTP_POST_VARS['fm2user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fm2notes'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['kommision'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['sapsd'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['sapkst'], "int"),
					   
					   GetSQLValueString($HTTP_POST_VARS['debitorennummer'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['debitorennummer2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['rechnung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rstrasse'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['ansprechpartner'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['anrede'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rplz'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rort'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rland'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['lieferung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lstrasse'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lplz'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lort'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lland'], "text"),
   					   GetSQLValueString($HTTP_POST_VARS['rfax'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['intern'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['alternativerechnung'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['vkz'], "int"),
						GetSQLValueString($HTTP_POST_VARS['fmsg'], "int"),
						GetSQLValueString($HTTP_POST_VARS['select_idk'], "int"),
						GetSQLValueString($HTTP_POST_VARS['select_idk2'], "int"),
					
					   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
$oktxt=$oktxt."Daten aktualisert und gespeichert. ";
if ((isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save1"])) or (isset($HTTP_POST_VARS["save2"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save4"])) or (isset($HTTP_POST_VARS["save5"])) or  (isset($HTTP_POST_VARS["save6"] )) or  (isset($HTTP_POST_VARS["save7"] )) or  (isset($HTTP_POST_VARS["save8"] )) or  (isset($HTTP_POST_VARS["save9"] )) or  (isset($HTTP_POST_VARS["cancel2"] )) ) {
if (isset($goback)){$updateGoTo = "la42s.php";}
else{
if (isset($HTTP_POST_VARS["save"])){

if (isset($qmb)){$updateGoTo = "qmb4.php";}else{$updateGoTo = "la4.php";}
}
if (isset($HTTP_POST_VARS["save1"])){$updateGoTo = "la41.php";}
if (isset($HTTP_POST_VARS["save3"])){$updateGoTo = "la43.php";}
if (isset($HTTP_POST_VARS["save4"])){$updateGoTo = "la44.php";}
if (isset($HTTP_POST_VARS["save5"])){$updateGoTo = "la45.php";}
if (isset($HTTP_POST_VARS["save6"])){$updateGoTo = "la46.php";}
if (isset($HTTP_POST_VARS["save7"])){$updateGoTo = "la47.php";}
if (isset($HTTP_POST_VARS["save8"])){$updateGoTo = "la48.php";}
if (isset($HTTP_POST_VARS["save9"])){$updateGoTo = "la49.php";}
if (isset($HTTP_POST_VARS["cancel2"])){$updateGoTo = "la402.php";}


 }
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
} /* end saveaktuell */

/* Freigabebutton für Gewährleistungsretouren */
if ((isset($HTTP_POST_VARS["freigabe"]))&& $HTTP_POST_VARS["fm2"]==1 ) {
 if (($HTTP_POST_VARS['fm2datum']=="0000-00-00")&&($HTTP_POST_VARS['fm2']==1)) {$today="now()";} else { $today=GetSQLValueString($HTTP_POST_VARS['fm2datum'], "date");}
 
 if ($HTTP_POST_VARS["checkbox"]==1){
  $HTTP_POST_VARS['lieferung']=$HTTP_POST_VARS['rechnung'];
  $HTTP_POST_VARS['lstrasse']=$HTTP_POST_VARS['rstrasse'];
  $HTTP_POST_VARS['lplz']=$HTTP_POST_VARS['rplz'];
  $HTTP_POST_VARS['lort']=$HTTP_POST_VARS['rort'];
  $HTTP_POST_VARS['lland']=$HTTP_POST_VARS['rland'];
 }
  
  /* Kostenstellenprüfung */
  
if ($HTTP_POST_VARS['sapkst']==0 && $HTTP_POST_VARS['fm2variante']<>0){
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT entscheidung.gvwl, entscheidung.sapkst, entscheidung.entid FROM entscheidung WHERE entscheidung.entid='$HTTP_POST_VARS[fm2variante]'";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

$HTTP_POST_VARS['sapkst']=$row_rst8['sapkst'];
$oktxt=$oktxt."Kostenstelle geprüft.";
}
/* Ende Kostenstellenprüfung */  
  
  /* Prüfe und anlegen von neuen Positionsdaten */
 if  ($HTTP_POST_VARS['fm2variante']>0 && $HTTP_POST_VARS['oldfm2variante']==0){
 include("la42.tmp1.php");
 }/* Ende von Prüfe und anlegen von neuen Positionsdaten */
 $updateSQL = sprintf("UPDATE fehlermeldungen SET fm2=%s, fm2variante=%s, fm2datum=%s, fm2user=%s, fm2notes=%s, sapsd=%s, sapkst=%s, 
 debitorennummer=%s,debitorennummer2=%s, rechnung=%s, rstrasse=%s, ansprechpartner=%s,anrede=%s, rplz=%s, rort=%s, rland=%s, lieferung=%s, 
 lstrasse=%s, lplz=%s, lort=%s, lland=%s, faxnummer=%s, vkz=%s, idk=%s, idk2=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fm2'],"int"),
                       GetSQLValueString($HTTP_POST_VARS['fm2variante'], "int"),
                       $today,
                       GetSQLValueString($HTTP_POST_VARS['fm2user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fm2notes'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['sapsd'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['sapkst'], "int"),
					   
					   GetSQLValueString($HTTP_POST_VARS['debitorennummer'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['debitorennummer2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['rechnung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rstrasse'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['ansprechpartner'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['anrede'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rplz'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rort'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rland'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['lieferung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lstrasse'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lplz'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lort'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lland'], "text"),
   					   GetSQLValueString($HTTP_POST_VARS['rfax'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['vkz'], "int"),
						etSQLValueString($HTTP_POST_VARS['select_idk'], "int"),
						GetSQLValueString($HTTP_POST_VARS['select_idk2'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

$GoTo = "la43.php?url_user=".$row_rst1['id']."&goback=la42s.php";
	if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $GoTo .= (strpos($GoTo, '?')) ? "&" : "?";
    $GoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  	}

 header(sprintf("Location: %s", $GoTo));
}else{
if (isset($HTTP_POST_VARS["freigabe"])){
$errtxt = $errtxt."Kurzlink nicht möglich. Bitte Entscheidung prüfen oder Freigabe gedruckt fehlt noch.";
}
}/* ende freigabe */

if ((isset($HTTP_POST_VARS["cancel"])) ) {
if (isset($goback)){$GoTo = "la42s.php?url_user=".$row_rst1['id'];}
else{ 
$GoTo = "la4.php?url_user=".$row_rst1['id'];
}
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = $errtxt."";}


if ((isset($HTTP_POST_VARS['editpos']) && $HTTP_POST_VARS['hurl_varid']>0 )) {
$GoTo = "la422.php?url_user=".$row_rst1['id'];
$GoTo = $GoTo."&url_varid=".$HTTP_POST_VARS['hurl_varid'];
$GoTo = $GoTo."&url_fmid=".$url_fmid;
$GoTo = $GoTo."&goback=".$goback;

 header(sprintf("Location: %s", $GoTo));

}else{$errtxt =$errtxt."";}


if ((isset($HTTP_POST_VARS["addkunden"])) ) {
$GoTo = "la61.php?url_user=".$row_rst1['id'];
$GoTo = $GoTo."&url_fmid=".$url_fmid;
/* 
Enter in rechnung verursacht Fehler in Headerwechsel
$GoTo = $GoTo."&url_firma=".$HTTP_POST_VARS['rechnung'];
$GoTo = $GoTo."&url_strasse=".$HTTP_POST_VARS['rstrasse'];
$GoTo = $GoTo."&url_plz=".$HTTP_POST_VARS['rplz'];
$GoTo = $GoTo."&url_ort=".$HTTP_POST_VARS['rort'];
$GoTo = $GoTo."&url_land=".$HTTP_POST_VARS['rland'];
$GoTo = $GoTo."&url_fax=".$HTTP_POST_VARS['rfax'];
$GoTo = $GoTo."&url_ansprechpartner=".$HTTP_POST_VARS['ansprechpartner'];
$GoTo = $GoTo."&url_anrede=".$HTTP_POST_VARS['anrede'];
 */
 header(sprintf("Location: %s", $GoTo));

}else{$errtxt =$errtxt."";}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmid='$url_fmid'";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);
$url_artikelid= $row_rst4['fmartikelid'];

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT linkfehlerartikel.lfaid, fehler.fid, fehler.fkurz, fehler.fname, fehler.lokz, linkfehlerartikel.fid, linkfehlerartikel.artikelid, linkfehlerartikel.lokz FROM fehler, linkfehlerartikel WHERE fehler.lokz=0 AND linkfehlerartikel.lokz=0 AND linkfehlerartikel.fid=fehler.fid AND linkfehlerartikel.artikelid='$url_artikelid' ORDER BY fehler.fkurz";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fehler.fname, fehler.fkurz, fehler.fid, fehler.lokz, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid, linkfehlerfehlermeldung.lffid FROM linkfehlerfehlermeldung, fehler WHERE linkfehlerfehlermeldung.lokz=0 AND linkfehlerfehlermeldung.fmid='$url_fmid' AND fehler.fid =linkfehlerfehlermeldung.fid ORDER BY fehler.fkurz";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst7 = "SELECT entscheidung.entid, entscheidung.entname, entscheidung.lokz, entscheidung.sapkst, artikeldaten_entscheidung_link.id_ent FROM entscheidung, artikeldaten_entscheidung_link WHERE artikeldaten_entscheidung_link.id_ent=entscheidung.entid AND artikeldaten_entscheidung_link.lokz=0 AND entscheidung.lokz = 0 AND artikeldaten_entscheidung_link.id_artikel='$row_rst4[fmartikelid]' ORDER BY entscheidung.entname";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstkunden = "SELECT * FROM kundendaten WHERE kundendaten.lokz=0 ORDER BY kundendaten.firma";
$rstkunden = mysql_query($query_rstkunden, $qsdatenbank) or die(mysql_error());
$row_rstkunden = mysql_fetch_assoc($rstkunden);
$totalRows_rstkunden = mysql_num_rows($rstkunden);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst42 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid,fertigungsmeldungen.version, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$row_rst4[fmartikelid] and fertigungsmeldungen.fsn=$row_rst4[fmsn] ORDER BY fertigungsmeldungen.fdatum desc";
$rst42 = mysql_query($query_rst42, $qsdatenbank) or die(mysql_error());
$row_rst42 = mysql_fetch_assoc($rst42);
$totalRows_rst42 = mysql_num_rows($rst42);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst52 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmartikelid, fehlermeldungen.fmuser, fehlermeldungen.fm1datum, fehlermeldungen.fmsn, fehlermeldungen.fm3, fehlermeldungen.fm6, fehlermeldungen.fm1notes FROM fehlermeldungen WHERE fehlermeldungen.fmartikelid=$row_rst4[fmartikelid] and fehlermeldungen.fmsn=$row_rst4[fmsn] ORDER BY fehlermeldungen.fmdatum desc";
$rst52 = mysql_query($query_rst52, $qsdatenbank) or die(mysql_error());
$row_rst52 = mysql_fetch_assoc($rst52);
$totalRows_rst52 = mysql_num_rows($rst52);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM entscheidung WHERE entscheidung.entid='$row_rst4[fm2variante]'";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT * FROM `user`";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form1" method="POST">
  <table width="950" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><strong><font color="#FF0000">
        <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        Bitte melden Sie sich an.<br>
        <?php } // Show if recordset empty ?>
        <?php echo htmlentities($errtxt) ?></font></strong> <strong><font color="#00CC00"> 
        <?php echo htmlentities($oktxt) ?></font></strong> </td>
    </tr>
    <tr> 
      <td width="136">Aktionen W&auml;hlen:</td>
      <td width="245"> <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> </td>
      <td width="349"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          <input name="save" type="submit" id="save" value="Speichern und zur Liste">
        </div></td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td>&nbsp;</td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td colspan="3"> <?php  include("la4.status.php");?> </td>
    </tr>
  </table>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="292"><br> <br> </td>
      <td width="658"><p><br>
          <input name="hurl_fmid3" type="hidden" id="hurl_fmid3" value="<?php echo $row_rst4['fmid']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="fm2user" type="hidden" id="fm3user2" value="<?php echo $row_rst1['id']; ?>">
          <a href="%5C%5Clznt01%5Cwerklz%5CElektroabteilung%5CAufnahme%20%20Foto" target="_blank">Bilder 
          ansehen</a></p></td>
    </tr>
  </table>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td width="200">Entscheidung: Prozess</td>
      <td colspan="2"><select name="fm2variante" id="select">
          <option value="0" <?php if (!(strcmp(0, $row_rst4['fm2variante']))) {echo "SELECTED";} ?>>keine 
          Angabe</option>
          <?php

      
do {  
?>
          <option value="<?php echo $row_rst7['entid']?>"<?php if (!(strcmp($row_rst7['entid'], $row_rst4['fm2variante']))) {echo "SELECTED";} ?>><?php echo utf8_decode($row_rst7['entname'])?></option>
          <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
        </select> <input name="oldfm2variante" type="hidden" id="oldfm2variante" value="<?php echo $row_rst4['fm2variante']; ?>"> 
        <?php if ($row_rst8['gvwl'] == 1) { // Show if recordset empty ?>
        <input name="freigabe" type="submit" id="freigabe" value="zur Freigabe"> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>SAP-SD-Nummer / Kostenstelle</td>
      <td colspan="2"> <input name="sapsd" type="text" id="sapsd" value="<?php echo $row_rst4['sapsd']; ?>"> 
        <select name="sapkst" id="sapkst">
          <option value="0" <?php if (!(strcmp(0, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>Vorgabewert</option>
          <option value="61100" <?php if (!(strcmp(61100, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61100 
          Fertigung BPT</option>
          <option value="61612" <?php if (!(strcmp(61612, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61612 
          GWL</option>
          <option value="61621" <?php if (!(strcmp(61621, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61621 
          Kulanz</option>
          <option value="61521" <?php if (!(strcmp(61521, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61521 
          F&E Leipzig</option>
          <option value="61821" <?php if (!(strcmp(61821, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61821 
          Verschrottung BPT</option>
          <option value="61611" <?php if (!(strcmp(61611, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61611 
          kostenpflichtige Reparatur</option>
        </select> <div align="right"> </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Sonderbehandlungskennzeichen</td>
      <td width="366"> <select name="vkz" id="vkz">
          <option value="0" <?php if (!(strcmp(0, $row_rst4['vkz']))) {echo "SELECTED";} ?>>keine</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Verschrottung</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Sonderbehandlung</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Servicetüre</option>
        </select></td>
      <td width="164"><div align="right"> <?php echo ($row_rst4['fm1datum']-$row_rst4['fmfd'])." Jahre alt"; ?> 
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td><input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="fm2user" type="hidden" id="fm2user" value="<?php echo $row_rst1['id']; ?>"></td>
      <td><div align="right"></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Datum Kostenvoranschlag</td>
      <td> <input name="fm2datum" type="text" id="fm2datum2" value="<?php echo $row_rst4['fm2datum']; ?>"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm2datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
      <td><div align="right"> gedruckt: 
          <input <?php if (!(strcmp($row_rst4['fm2'],1))) {echo "checked";} ?> name="fm2" type="checkbox" id="fm2" value="1">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Kommision</td>
      <td> <input name="kommision" type="text" id="kommision" value="<?php echo $row_rst4['fm12notes']; ?>" size="50" maxlength="255"></td>
      <td><div align="right"></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>SAP-Kundennummer:<img src="picture/btnHelp.gif" alt="Kundennummer wird bei Anlage einer Retourenabwicklung automatisch gelsen, sofern Daten vorhanden sind." width="16" height="16"></td>
      <td><input name="debitorennummer" type="text" id="debitorennummer" value="<?php echo $row_rst4['debitorennummer']; ?>">
        Serviceger&auml;te einblenden 
        <input <?php if (!(strcmp($row_rst4['fmsg'],1))) {echo "checked";} ?> name="fmsg" type="checkbox" id="fmsg" value="1">
        Wer ?</td>
      <td><input name="debitorennummer2" type="text" id="debitorennummer2" value="<?php echo $row_rst4['debitorennummer2']; ?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><p><img src="picture/feed.png" width="16" height="16"> Zustand des Ger&auml;tes/Verpackung 
          / </p>
        <p>Ger&auml;t vollst&auml;ndig (oder nur Deckel <br>
          oder nur Grundk&ouml;rper, mit Kabel ?)</p></td>
      <td colspan="2"><textarea name="intern" cols="70" rows="5" id="intern"><?php echo $row_rst4['intern']; ?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>Reparaturvorschlag:</td>
      <td><?php echo $row_rst4['fmnotes']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><p>allgemeine Bemerkungen<br>
          zur fehlermeldung/<br>
          Abwicklung</p>
        </td>
      <td> <textarea name="fm2notes" cols="70" rows="4" id="textarea"><?php echo $row_rst4['fm2notes']; ?></textarea></td>
      <td> <div align="right"> 
          <input name="saveaktuell" type="submit" id="saveaktuell2" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td colspan="3">&nbsp; </td>
    </tr>
    <tr> 
      <td colspan="3"> <div align="right"> 
          <input name="addkunden" type="submit" id="addkunden" value="Kundendaten &uuml;bernehmen">
          <a href="la6.php?url_user=<?php echo $row_rst1['id']; ?>&url_user_id=<?php echo $row_rst1['id'];?> " target="_blank"><img src="picture/kundendatenanlegen.png" alt="Kunden anlegen erfolgt in einem neuen Fenster" width="120" height="16" border="0"> 
          </a> <a href="pdfwbserweitert.php?url_fmid=<?php echo $row_rst4['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?> " target="_blank">E<img src="picture/wbs.png" width="120" height="16" border="0"></a> 
          <a href="pdfwbs.php?url_fmid=<?php echo $row_rst4['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?> " target="_blank"> 
          <img src="picture/wbs.png" width="120" height="16" border="0"></a><a href="pdfkv1.php?url_fmid=<?php echo $row_rst4['fmid'];?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/kostenvoranschlag.png" width="120" height="16" border="0"></a> 
          <a href="pdfkostenvoranschlag.php?url_fmid=<?php echo $row_rst4['fmid']; ?>" target="_blank"> 
          </a> </div></td>
    </tr>
    <tr> 
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><div align="right"><a href="pdfetikett1.php?url_fmid=<?php echo $row_rst4['fmid']; ?>" target="_blank"><img src="picture/printetikett.png" width="120" height="16" border="0"></a> 
        </div></td>
    </tr>
    <tr> 
      <td colspan="3"><div align="right"><a href="pdfkv1.php?url_fmid=<?php echo $row_rst4['fmid'];?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/download1.gif" width="17" height="17" border="0">Q1 
          Debitoren Reklamations- und Fehlermeldung -Formular</a></div></td>
    </tr>
    <tr> 
      <td colspan="3"><div align="right"><a href="pdfq2.php?url_fmid=<?php echo $row_rst4['fmid'];?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/download1.gif" width="17" height="17" border="0">Q2 
          Kreditioren Reklamations- und Fehlermeldung -Formular</a></div></td>
    </tr>
    <tr> 
      <td colspan="3"><div align="right"><a href="pdfq3.php?url_fmid=<?php echo $row_rst4['fmid'];?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/download1.gif" width="17" height="17" border="0">Q3 
          Online Reklamations- und Fehlermeldung -Formular</a></div></td>
    </tr>
  </table>
  
   
    
  <br>
  <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Liste der Fehler:</strong></em><br>
  <img src="picture/<?php if (!(strcmp($row_rst5['fm3'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
  <input type="hidden" name="MM_insert" value="form1">

<?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
<font color="#FF0000">noch keine Fehler gespeichert.</font> 
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
 <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <?php do { ?>
  <tr>  
      <td width="8">&nbsp; 
      </td>
      <td width="193">&nbsp;</td>
      <td width="529"> 
        <p><?php echo $row_rst6['fkurz']; ?>- <?php echo $row_rst6['fname']; ?> 
          <input name="hurl_fid" type="hidden" id="hurl_fid" value="<?php echo $row_rst6['fid']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>">
          <input name="hurl_lffid" type="hidden" id="hurl_lffid" value="<?php echo $row_rst6['lffid']; ?>">
          <?php if ($row_rst6['fid']==21){ ?>
          <img src="picture/s_host.png"> <a href="la44.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst4['fmid']; ?>">VDE-Prüfung 
          erstellen</a> 
          <?php } ?>
      </td>
  </tr>
  <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
</table>
<?php } // Show if recordset not empty ?>
<br>
  <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Liste der Fertigungsdaten:</strong></em> 
  <?php if ($totalRows_rst42 == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur Auswahl gefunden.</font></p>
  <?php } // Show if recordset empty ?>
 
  <?php if ($totalRows_rst42 > 0) { // Show if recordset not empty ?>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="296">M&ouml;gliche Fertigungsdaten:</td>
      <td width="147">Version</td>
      <td width="147">Status </td>
      <td width="105">User</td>
      <td width="182">Bemerkungen </td>
    </tr>
    <?php do { ?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php echo $row_rst42['fdatum']; ?> <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst42['fdatum']; ?>"> 
        <input name="name" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
        <input name="debitor" type="hidden" id="debitor" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst42['version']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst42['ffrei'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
        - <img src="picture/<?php if (!(strcmp($row_rst42['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rstuser['id'], $row_rst42['fuser']))) {echo $row_rstuser['name'];}?>
        <?php
} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  $rows = mysql_num_rows($rstuser);
  if($rows > 0) {
      mysql_data_seek($rstuser, 0);
	  $row_rstuser = mysql_fetch_assoc($rstuser);
  }
?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst42['fnotes']; ?></td>
    </tr>
    <?php } while ($row_rst42 = mysql_fetch_assoc($rst42)); ?>
  </table>
  <br>
  insgesamt <?php echo $totalRows_rst42 ?> Fertigungsmeldungen gefunden.<br><?php } // Show if recordset not empty ?>
  <br>
  <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Liste der Reparaturen/ 
  Retourenabwicklungen:</strong></em> 
  <?php if ($totalRows_rst52 == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine m&ouml;glichen Reparaturdaten oder Fehlermeldungen zur Auswahl gefunden.</font></p>
  <?php } // Show if recordset empty ?>
    
    <?php if ($totalRows_rst52 > 0) { // Show if recordset not empty ?>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="200">M&ouml;gliche Reparaturdaten:</td>
      <td width="79">FMID</td>
      <td width="59">&nbsp;</td>
      <td width="1">&nbsp;</td>
      <td width="165">User</td>
      <td width="100">Bemerkungen </td>
    </tr>
    <?php do { ?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td bgcolor="#EEEEEE" nowrap="nowrap"> 
        <strong><img src="picture/folder_open.gif" width="16" height="16"><?php echo $row_rst52['fm1datum']; ?> 
        <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst52['fm1datum']; ?>">
        <input name="name" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>">
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>">
        <input name="debitor" type="hidden" id="debitor" value="<?php echo $HTTP_POST_VARS['debitor']?>">
        <?php if (($row_rst52['fmid']) == ($row_rst4['fmid'])) {echo "(in Bearbeitung)";} ?>
        </strong></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><a href="la42.php?url_user=<?php echo $url_user ;?>&url_fmid=<?php echo $row_rst52['fmid']; ?>" target="_blank"><?php echo $row_rst52['fmid']; ?></a></strong></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rstuser['id'], $row_rst52['fmuser']))) {echo $row_rstuser['name'];}?>
        <?php
} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  $rows = mysql_num_rows($rstuser);
  if($rows > 0) {
      mysql_data_seek($rstuser, 0);
	  $row_rstuser = mysql_fetch_assoc($rstuser);
  }
?>
        </strong></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><?php echo $row_rst52['fm1notes']; ?></strong></td>
    </tr>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/3.gif" width="60" height="10">Fehler</td>
      <td colspan="5" nowrap="nowrap"    bgcolor="#EEEEEE"> 
        <?php 
	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst53 = "SELECT fehler.fid, fehler.fkurz, fehler.fname, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid FROM fehler, linkfehlerfehlermeldung WHERE linkfehlerfehlermeldung.fmid=$row_rst52[fmid] and linkfehlerfehlermeldung.fid =fehler.fid ";
$rst53 = mysql_query($query_rst53, $qsdatenbank) or die(mysql_error());
$row_rst53 = mysql_fetch_assoc($rst53);
$totalRows_rst53 = mysql_num_rows($rst53);
	  ?><table width="530" border="0" cellpadding="0" cellspacing="0">
          <?php do { ?>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td width="109" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><img src="picture/<?php if (!(strcmp($row_rst53['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15">- 
                <?php echo $row_rst53['fkurz']; ?> </div></td>
            <td width="421" nowrap="nowrap"  bgcolor="#EEEEEE"   > 
              <?php echo $row_rst53['fname']; ?>
                      </td>              
          </tr>
          
          <?php } while ($row_rst53 = mysql_fetch_assoc($rst53)); ?>
         
        </table>
		
		</td>
    </tr>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/3.gif" width="60" height="10">Reparatur</td>
      <td colspan="5" nowrap="nowrap"    bgcolor="#EEEEEE">        <?php 
	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT komponenten.id_ruck, komponenten.kompnr, komponenten.anzahl, komponenten.lagerort, komponenten.lokz FROM komponenten WHERE komponenten.id_fm = $row_rst52[fmid] and  komponenten.lokz=0 ORDER BY komponenten.id_ruck DESC";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);
	  
	  ?><?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>
        <?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM komponenten_vorlage";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);


?><table width="530" border="0" cellpadding="0" cellspacing="0">
          <?php do { ?>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td width="116" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['kompnr']; ?></div></td>
            <td width="273" nowrap="nowrap"  bgcolor="#EEEEEE"   > 
              <div align="left"> 
                <?php do {  
?>
                <?php if (!(strcmp($row_rst8['kompnr'], $row_rst9['kompnr']))) {echo htmlentities($row_rst8['kompname']);}?>
                <?php
} while ($row_rst8 = mysql_fetch_assoc($rst8));
  $rows = mysql_num_rows($rst8);
  if($rows > 0) {
      mysql_data_seek($rst8, 0);
	  $row_rst8 = mysql_fetch_assoc($rst8);
  }
?>
              </div></td>
            <td width="101" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['anzahl']; ?> ST </div></td>
            <td width="88" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['lagerort']; ?></div></td>
            <td width="152" nowrap="nowrap"  bgcolor="#EEEEEE"   > 
              <div align="right"> 
                <input type="hidden" name="MM_update2" value="form3">
                <input name="hlokz" type="hidden" id="hlokz" value="1">
                <input name="idruck" type="hidden" id="idruck" value="<?php echo $row_rst9['id_ruck']; ?>">
              </div></td>
          </tr>
          <input type="hidden" name="MM_update2" value="form3">
          <?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?>          
        </table>
        <?php } // Show if recordset not empty ?> <p> 
          <?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
          <font color="#FF0000">noch keine Teile r&uuml;ckmeldet.</font> 
          <?php } // Show if recordset empty ?>
        </p></td>
    </tr>
    <?php } while ($row_rst52 = mysql_fetch_assoc($rst52)); ?>
  </table>
<p> insgesamt <?php echo $totalRows_rst52 ?> Reparaturmeldungen gefunden. <br>
  (Es werden nur Meldungen mit echten Fehlerzuordnungen angezeigt.) </p>
<p>&nbsp; </p>
<?php } // Show if recordset not empty ?>
  <?php  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstsg = "SELECT fhm_ereignis.id_fhme, fhm_ereignis.datum, fhm_ereignis.user, fhm_ereignis.beschreibung, fhm_ereignis.id_contact, fhm_ereignis.id_fhm, fhm_ereignis.id_fhmea, fhm.sn, fhm.artikelid, fehlermeldungen.fmid, fehlermeldungen.debitorennummer, fhm.id_fhm, fehlermeldungen.fmid
FROM fhm_ereignis, fehlermeldungen, fhm
WHERE fhm_ereignis.id_contact = fehlermeldungen.debitorennummer
AND fhm_ereignis.id_contact<>0
AND fhm_ereignis.id_contact<>''
AND fhm.id_fhm = fhm_ereignis.id_fhm
AND fehlermeldungen.fmid =$url_fmid 
ORDER BY fhm_ereignis.datum
LIMIT 0 , 10 ";
$rstsg = mysql_query($query_rstsg, $qsdatenbank) or die(mysql_error());
$row_rstsg = mysql_fetch_assoc($rstsg);
$totalRows_rstsg = mysql_num_rows($rstsg);?>



<?php 
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstsg2 = "SELECT fhm_ereignisart.id_fhmea, fhm_ereignisart.name, fhm_ereignisart.piclink FROM fhm_ereignisart WHERE fhm_ereignisart.lokz=0 ORDER BY fhm_ereignisart.name";
$rstsg2 = mysql_query($query_rstsg2, $qsdatenbank) or die(mysql_error());
$row_rstsg2= mysql_fetch_assoc($rstsg2);
$totalRows_rstsg2 = mysql_num_rows($rstsg2);


?>
  <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Liste Umlagerungen 
  der Servicebeh&auml;lter oder Servicet&uuml;ren bzw. Equipment (FHM):</strong></em> 
  <?php if ($totalRows_rstsg == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine m&ouml;glichen Servicebeh&auml;lter oder -t&uuml;ren 
    zur Auswahl gefunden.</font></p>
  <?php } // Show if recordset empty ?>
 
  <?php if ($totalRows_rstsg > 0) { // Show if recordset not empty ?>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="296">M&ouml;gliche Umlagerungen:</td>
      <td width="147">Typ</td>
      <td width="147">Seriennummer</td>
      <td width="147">Status </td>
      <td width="105">User</td>
      <td width="182">Bemerkungen </td>
    </tr>
    <?php do { ?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php echo $row_rstsg['datum']; ?> <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst42['fdatum']; ?>"> 
        <input name="name" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
        <input name="debitor" type="hidden" id="debitor" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
      
	  
	  
	  
	  <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php do {  
?>
      <?php if (!(strcmp($row_rsttyp['artikelid'], $row_rstsg['artikelid']))) {echo $row_rsttyp['Bezeichnung'];} ?>
      <?php
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rstsg['sn']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp; 
        <?php 
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstsg1 = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_ref_fhme='$row_rstsg[id_fhme]'";
$rstsg1 = mysql_query($query_rstsg1, $qsdatenbank) or die(mysql_error());
$row_rstsg1 = mysql_fetch_assoc($rstsg1);
$totalRows_rstsg1 = mysql_num_rows($rstsg1);
  
  ?>
        <?php if  ($totalRows_rstsg1>0){ ?>
        <img src="picture/fertig.gif" alt="<?php echo $row_rstsg1['id_ref_fhme'] ?>" width="15" height="15"> 
        <?php }?>
        <?php
do {  
?>
        <?php if (!(strcmp($row_rstsg2['id_fhmea'], $row_rstsg['id_fhmea']))) {echo "<img src=\"picture/".$row_rstsg2['piclink']."\">".$row_rstsg2['name'];} ?>
        <?php 
} while ($row_rstsg2 = mysql_fetch_assoc($rstsg2));
  $rows = mysql_num_rows($rstsg2);
  if($rows > 0) {
      mysql_data_seek($rstsg2, 0);
	  $row_rstsg2 = mysql_fetch_assoc($rstsg2);
  }
	?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rstuser['id'], $row_rstsg['user']))) {echo $row_rstuser['name'];}?> 
        <?php
} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  $rows = mysql_num_rows($rstuser);
  if($rows > 0) {
      mysql_data_seek($rstuser, 0);
	  $row_rstuser = mysql_fetch_assoc($rstuser);
  }
?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rstsg['beschreibung']; ?></td>
    </tr>
    <?php } while ($row_rstsg = mysql_fetch_assoc($rstsg)); ?>
  </table>
  <br>
  insgesamt <?php echo $totalRows_rstsg ?> Umlagerungen zum Kunden gefunden.<br>
  <?php } // Show if recordset not empty ?>
<br>







    <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Kopfdaten 
    zur Kostenkl&auml;rung:</strong></em> </p>
    
  <table width="950" height="205" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="190">Kostenvoranschlag an:</td>
      <td width="540"> <select name="select2">
          <option value="""" <?php if (!(strcmp("", $HTTP_POST_VARS['select2']))) {echo "SELECTED";} ?>>keine 
          &Auml;nderung</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rstkunden['idk']?>"><?php echo utf8_decode($row_rstkunden['firma']);?></option>
          <?php
} while ($row_rstkunden = mysql_fetch_assoc($rstkunden));
  $rows = mysql_num_rows($rstkunden);
  if($rows > 0) {
      mysql_data_seek($rstkunden, 0);
	  $row_rstkunden = mysql_fetch_assoc($rstkunden);
  }
?>
        </select></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Anschrift</td>
      <td><textarea name="rechnung" cols="45"><?php echo ($row_rst4['rechnung']); ?></textarea>
        Kunden-Nr.<?php echo ($row_rst4['debitorennummer']); ?></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Ansprechpartner</td>
      <td><input name="ansprechpartner" type="text" id="ansprechpartner" value="<?php echo $row_rst4['ansprechpartner']; ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Anrede</td>
      <td><input name="anrede" type="text" id="anrede" value="<?php echo $row_rst4['anrede']; ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Stra&szlig;e</td>
      <td><input name="rstrasse" type="text" id="rstrasse" value="<?php echo ($row_rst4['rstrasse']); ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="24">PLZ</td>
      <td><input name="rplz" type="text" id="rplz" value="<?php echo ($row_rst4['rplz']); ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Ort</td>
      <td><input name="rort" type="text" id="rort" value="<?php echo ($row_rst4['rort']); ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Land</td>
      <td><input name="rland" type="text" id="rland" value="<?php echo ($row_rst4['rland']); ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><div align="left">Rechnung und Lieferung gleich </div></td>
      <td><input type="checkbox" name="checkbox" value="1">
        Faxnummer: 
        <input name="rfax" type="text" id="rfax" value="<?php echo ($row_rst4['faxnummer']); ?>"></td>
    </tr>
  </table>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="192">Warenempf&auml;nger Lieferung an:</td>
      <td width="538"><select name="select3">
          <option value="""" <?php if (!(strcmp("", $HTTP_POST_VARS['select3']))) {echo "SELECTED";} ?>>keine 
          &Auml;nderung</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rstkunden['idk']?>"><?php echo utf8_decode($row_rstkunden['firma']);?></option>
          <?php
} while ($row_rstkunden = mysql_fetch_assoc($rstkunden));
  $rows = mysql_num_rows($rstkunden);
  if($rows > 0) {
      mysql_data_seek($rstkunden, 0);
	  $row_rstkunden = mysql_fetch_assoc($rstkunden);
  }
?>
        </select> </td>
    </tr>
    <tr> 
      <td>Anschrift</td>
      <td><textarea name="lieferung" cols="45"><?php echo ($row_rst4['lieferung']); ?></textarea>
        Kunden-Nr.<?php echo ($row_rst4['debitorennummer2']); ?></td>
    </tr>
    <tr> 
      <td>Stra&szlig;e</td>
      <td><input name="lstrasse" type="text" id="lstrasse2" value="<?php echo ($row_rst4['lstrasse']); ?>" size="40"></td>
    </tr>
    <tr> 
      <td>PLZ</td>
      <td><input name="lplz" type="text" id="lplz3" value="<?php echo ($row_rst4['lplz']); ?>"></td>
    </tr>
    <tr> 
      <td>Ort</td>
      <td><input name="lort" type="text" id="lort2" value="<?php echo ($row_rst4['lort']); ?>" size="40"></td>
    </tr>
    <tr> 
      <td>Land</td>
      <td><input name="lland" type="text" id="lland2" value="<?php echo ($row_rst4['lland']); ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Rechnungsempf&auml;nger</td>
      <td> <textarea name="alternativerechnung" cols="45" rows="5"><?php echo ($row_rst4['alternativerechnung']); ?></textarea> 
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="right"> 
          <input name="saveaktuell" type="submit" id="saveaktuell" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>Kundenzuordnung</td>
      <td><select name="select_idk">
          <option value="" <?php if ($HTTP_POST_VARS['select_idk']=="") {echo "SELECTED";} ?>>keine 
          Auswahl</option>
          <?php do { ?>
          <option value="<?php echo $row_rstkunden['idk']?>" <?php if ( $row_rstkunden['idk']==$row_rst4['idk']){echo "SELECTED";} ?>><?php echo utf8_decode($row_rstkunden['firma']);?></option>
          <?php
} while ($row_rstkunden = mysql_fetch_assoc($rstkunden));
  $rows = mysql_num_rows($rstkunden);
  if($rows > 0) {
      mysql_data_seek($rstkunden, 0);
	  $row_rstkunden = mysql_fetch_assoc($rstkunden);
  }
?>
        </select> </td>
    </tr>
    <tr>
      <td>Lieferzuordnung</td>
      <td><select name="select_idk2">
          <option value="" <?php if ($HTTP_POST_VARS['select_idk']=="") {echo "SELECTED";} ?>>keine 
          Auswahl</option>
          <?php do {  ?>
          <option value="<?php echo $row_rstkunden['idk']?>" <?php if ( $row_rstkunden['idk']==$row_rst4['idk2']){echo "SELECTED";} ?>><?php echo utf8_decode($row_rstkunden['firma']);?></option>
          <?php
} while ($row_rstkunden = mysql_fetch_assoc($rstkunden));
  $rows = mysql_num_rows($rstkunden);
  if($rows > 0) {
      mysql_data_seek($rstkunden, 0);
	  $row_rstkunden = mysql_fetch_assoc($rstkunden);
  }
?>
        </select> </td>
    </tr>
  </table>
  </form>



<p><br>
</p>
<form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td width="141">Entscheidung: Prozess</td>
      <td width="589" colspan="2"><select name="select4" id="select2">
          <option value="0" <?php if (!(strcmp(0, $row_rst4['fm2variante']))) {echo "SELECTED";} ?>>keine 
          Angabe</option>
          <?php
do {  ?>
          <?php echo utf8_decode($row_rst7['entname'])."- ".utf8_decode($row_rst7['varname'])." - ".substr(utf8_decode($row_rst7['varbeschreibung']),0,50)." - ".$row_rst7['sapkst']?> 
          <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
          <?php
do {  
?>
          <option value="<?php echo $row_rst7['entid']?>"<?php if (!(strcmp($row_rst7['entid'], $row_rst4['fm2variante']))) {echo "SELECTED";} ?>><?php echo utf8_decode($row_rst7['entname'])?></option>
          <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
        </select> <input name="oldfm2variante2" type="hidden" id="oldfm2variante2" value="<?php echo $row_rst4['fm2variante']; ?>"> 
        <?php if ($row_rst8['gvwl'] == 1) { // Show if recordset empty ?>
        <input name="freigabe2" type="submit" id="freigabe2" value="zur Freigabe"> 
        <?php } // Show if recordset empty ?> <input name="prozessedit" type="submit" id="prozessedit" value="Prozess ausw&auml;hlen"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="fm2user" type="hidden" id="fm2user" value="<?php echo $row_rst1['id']; ?>"> 
      </td>
    </tr>
    <?php 
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstvarianten = "SELECT * FROM entscheidungsvarianten WHERE entscheidungsvarianten.entid='$row_rst4[fm2variante]'";
$rstvarianten = mysql_query($query_rstvarianten, $qsdatenbank) or die(mysql_error());
$row_rstvarianten = mysql_fetch_assoc($rstvarianten);
$totalRows_rstvarianten = mysql_num_rows($rstvarianten);
  
  
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td>Varianten</td>
      <td colspan="2"><select name="select5">
          <?php
do {  
?>
          <option value="<?php echo $row_rstvarianten['varid']?>"<?php if (!(strcmp($row_rstvarianten['varid'], "$HTTP_POST_VARS[select5]"))) {echo "SELECTED";} ?>><?php echo ($row_rstvarianten['varname']." - ".substr($row_rstvarianten['varbeschreibung'],0,80));?></option>
          <?php
} while ($row_rstvarianten = mysql_fetch_assoc($rstvarianten));
  $rows = mysql_num_rows($rstvarianten);
  if($rows > 0) {
      mysql_data_seek($rstvarianten, 0);
	  $row_rstvarianten = mysql_fetch_assoc($rstvarianten);
  }
?>
        </select> <?php if ($totalRows_rstvarianten > 0) { // Show if recordset not empty ?>
        <input name="insertvariante" type="submit" id="insertvariante" value="Variante als Position kopieren">
        <?php } // Show if recordset not empty ?></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form4">
  <input type="hidden" name="MM_insert" value="form4">
</form>
<p>&nbsp; </p>
<p> <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Positionsdaten 
  zur Kostenkl&auml;rung:</strong></em> </p>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td width="144" nowrap="nowrap" bgcolor="#EEEEEE">Position</td>
      <td width="282" nowrap="nowrap"    bgcolor="#EEEEEE">Beschreibung</td>
      <td width="89" nowrap="nowrap"    bgcolor="#EEEEEE">Kosten</td>
      <td width="29" nowrap="nowrap"    bgcolor="#EEEEEE">&nbsp;</td>
      <td width="29" nowrap="nowrap"    bgcolor="#EEEEEE">&nbsp;</td>
      <td width="157" nowrap="nowrap"    bgcolor="#EEEEEE"><div align="right"><a href="pdfkv1.php?url_fmid=<?php echo $row_rst4['fmid'];?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/kostenvoranschlag.png" width="120" height="16" border="0"></a></div></td>
    </tr>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td bgcolor="#EEEEEE" nowrap="nowrap"><input name="varname" type="text" id="varname2"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <textarea name="beschreibungpos" cols="50" rows="3" id="textarea2"></textarea></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <input name="Kosten1" type="text" id="Kosten12" size="10" maxlength="10"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst9['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz3" value="1"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst9['entid'],1))) {echo "checked";} ?> name="frei" type="checkbox" id="frei3" value="1"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_fmid" type="hidden" id="hurl_fmid_varid22" value="<?php echo $row_rst4['fmid']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user42" value="<?php echo $row_rst1['id']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="addpos" type="submit" id="addpos3" value="anlegen">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
</form>
<br>
<?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$url_fmid' ORDER BY fehlermeldungsdaten.varname";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);




?>
<?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
<font color="#FF0000">keine Positionsdaten vorhanden.</font> 
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>
<table width="950" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="143">Postion</td>
    <td width="308">Beschreibung</td>
    <td width="94">Kosten</td>
    <td width="31">Lokz</td>
    <td width="30">Ents.</td>
    <td width="124"><div align="right">Aktion</div></td>
  </tr>
  <?php do { ?>
<form name="form3" method="post" action="">
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst9['varname']; ?></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(($row_rst9['varbeschreibung']),60,"<br>",255); ?>
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="fm2user" type="hidden" id="fm2user" value="<?php echo $row_rst1['id']; ?>"></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst9['Kosten1']; ?> &euro;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst9['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz3" value="1"></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst9['entid'],1))) {echo "checked";} ?> name="frei" type="checkbox" id="frei3" value="1"></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_varid" type="hidden" id="hurl_fmid_varid3" value="<?php echo $row_rst9['varid']; ?>">
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="editpos" type="submit" id="editpos" value="Bearbeiten">
          <img src="picture/b_drop.png" width="16" height="16"> 
          <input name="delpos" type="submit" id="delpos3" value="l&ouml;schen">
      </div></td>
  </tr>
</form>
  <?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?>
</table>


<?php } // Show if recordset not empty ?>
<p> </p>
<p> 
  <input type="hidden" name="MM_update" value="form2">
</p>
<?php include("la4.picture.php"); ?>
<?php include("la4.rstuserst.php"); ?>
<?php
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rst7);

mysql_free_result($rstkunden);





mysql_free_result($rst42);

mysql_free_result($rst52);

mysql_free_result($rsttyp);

mysql_free_result($rst9);

mysql_free_result($rstvarianten);

mysql_free_result($rstsg);
?></p>
<p><a href="pdfkostenvoranschlag.php?url_fmid=<?php echo $row_rst4['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/kostenvoranschlag.png" width="120" height="16" border="0"></a></p>
<?php include("footer.tpl.php"); ?>
