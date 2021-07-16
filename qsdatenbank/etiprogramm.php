<?php require_once('Connections/nachrichten.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
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

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
if (isset($hurl_fid) && $HTTP_POST_VARS['hurl_fid']==''){$HTTP_POST_VARS['hurl_fid']=$hurl_fid;} /* wennn aus Modul la12 fertigungsdaten ändern */


if ((isset($HTTP_POST_VARS["hurl_fid"])) && $HTTP_POST_VARS['RadioGroup1']>0  && isset($HTTP_POST_VARS["fidstatusaendern"]) ) {

if ($HTTP_POST_VARS['RadioGroup1']==1){/* löschenkennzeichen */
  $updateSQL = sprintf("UPDATE fertigungsmeldungen SET status=%s, ffrei=%s, 
  freigabedatum=%s, erstelltdatum=%s, verkauftam=%s, versendetam=%s, 
  history=%s, 
  verkauftuser=%s, versendetuser=%s, freigabeuser=%s WHERE fid=%s",
                       "0",
					   "0",
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
					   
                       GetSQLValueString($HTTP_POST_VARS['textarea']."- Datenmod. Loeschen-".date('Y-m-d',time()), "text"),
					   
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
					   
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"));
}elseif($HTTP_POST_VARS['RadioGroup1']==2){
$updateSQL = sprintf("UPDATE fertigungsmeldungen SET verkauftam=%s, versendetam=%s, 
  history=%s, 
  verkauftuser=%s, versendetuser=%s, freigabeuser=%s WHERE fid=%s",
                     
                       
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
					   
                       GetSQLValueString($HTTP_POST_VARS['textarea']."- Datenmod. zurueck nach BCK-".date('Y-m-d',time()), "text"),
					   
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
					   
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"));


}elseif ($HTTP_POST_VARS['RadioGroup1']==3){/* löschenkennzeichen */
  $updateSQL = sprintf("UPDATE fertigungsmeldungen SET status=%s, ffrei=%s, 
  freigabedatum=%s, verkauftam=%s, versendetam=%s, 
  history=%s, 
  verkauftuser=%s, versendetuser=%s, freigabeuser=%s WHERE fid=%s",
                       "1",
					   "0",
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
                       
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
					   
                       GetSQLValueString($HTTP_POST_VARS['textarea']."- Datenmod. Arbeitsanfang-".date('Y-m-d',time()), "text"),
					   
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
					   
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"));
					   
}elseif ($HTTP_POST_VARS['RadioGroup1']==4){/* zurück ins Lager setzen */
  $updateSQL = sprintf("UPDATE fertigungsmeldungen SET versendetam=%s, 
  history=%s, 
  versendetuser=%s WHERE fid=%s",
                       GetSQLValueString("0000-00-00 00:00:00", "date"),
					   GetSQLValueString($HTTP_POST_VARS['textarea']."- Datenmod. rueckgesendet in Lager ".date('Y-m-d',time()), "text"),
					   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
					   
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"));
					   
					   					  
 }elseif ($HTTP_POST_VARS['RadioGroup1']==5){/* manueller Versand */
  $updateSQL = sprintf("UPDATE fertigungsmeldungen SET
  versendetam=%s, 
  history=%s, 
  versendetuser=%s WHERE fid=%s",
                       
                       GetSQLValueString(date('Y-m-d',time()), "date"),
                       GetSQLValueString($HTTP_POST_VARS['textarea']."- Datenmod. manueller Versand-".date('Y-m-d',time()), "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),				   
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"));
}else { $errtxt="keine Auswahl getroffen.";
}
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

?><?php $la = "etiprogramm";


if ((isset($HTTP_POST_VARS['hurl_fid'])) && ($HTTP_POST_VARS['hurl_fid'] != "") && (isset($HTTP_POST_VARS['fiddelete']))) {
  $deleteSQL = sprintf("DELETE FROM fertigungsmeldungen WHERE fid=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
  $oktxt.="Fertigungsdatensatz ".$HTTP_POST_VARS['hurl_fid']." entfernt.<br>";
}





if ((isset($HTTP_POST_VARS['FIDspiegeln'])) && ($HTTP_POST_VARS['selecteins'] != "") && ($HTTP_POST_VARS['selectzwei'] != "") ) {
  
  /* suche alle Fertigungsmeldungen für Material eins */
  
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM fertigungsmeldungen WHERE fartikelid = '$HTTP_POST_VARS[selecteins]' AND referenz is null";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);
  
  if ($totalRows_rst1 > 0) {
  do {
  
  /* lege neue Datensatz an */
   
  $deleteSQL = sprintf("INSERT INTO fertigungsmeldungen (fartikelid ,fuser ,fdatum ,fsn ,version ,fsn1,fsn2,fsn3,fsn4,fsonder,ffrei,fnotes,signatur,pgeraet,fnotes1,frfid,referenz) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s ,%s,%s,%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($HTTP_POST_VARS['selectzwei'], "int"),
					  GetSQLValueString($row_rst1['fuser'], "text"),
					   GetSQLValueString($row_rst1['fdatum'], "text"), 
					   GetSQLValueString($row_rst1['fsn'], "text"), 
					   GetSQLValueString($row_rst1['version'], "text"), 
					   GetSQLValueString($row_rst1['fsn1']." ", "text"), 
					  GetSQLValueString($row_rst1['fsn2']." ", "text"), 
					   GetSQLValueString($row_rst1['fsn3']." ", "text"), 
					   GetSQLValueString($row_rst1['fsn4']." ", "text"), 
					   GetSQLValueString($row_rst1['fsonder'], "text"), 
					   GetSQLValueString($row_rst1['ffrei'], "text"), 
					   GetSQLValueString("Daten von FID".$row_rst1['fid']." kopiert. ".$row_rst1['fnotes'], "text"), 
					   GetSQLValueString($row_rst1['signatur']."COPY", "text"),
					   GetSQLValueString($row_rst1['pgeraet']."H", "text"),
					   GetSQLValueString($row_rst1['fnotes1']."-", "text"),
					   GetSQLValueString($row_rst1['rfid'], "text"),
					   GetSQLValueString($row_rst1['fid'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());

  /*  merke die neue Nummer */
  	$lastins= mysql_insert_id();
  
  /* schreibe nummer in alten Datensatz */
  
	  $updateSQL = sprintf("UPDATE fertigungsmeldungen SET fertigungsmeldungen.referenz= '$lastins' WHERE fertigungsmeldungen.fid='$row_rst1[fid]'");
	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
	  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
	 
	   
  }while ($row_rst1 = mysql_fetch_assoc($rst1));/* und nehme nächste FID */
  
  $oktxt.="Fertigungsdatensatze ".$totalRows_rst1." kopiert.<br>";
  
  } else {
  $oktxt.="Abbruch. Keine Fertigungsdatensatze für Material 1 gefunden. - ".$totalRows_rst1." Daten kopiert.<br>";
  }
}




/* Seriennummernsprung einfügen */

if ( isset($HTTP_POST_VARS['SNplus']) && ($HTTP_POST_VARS['SNcheckbox']==1) && ($HTTP_POST_VARS['selectSNplus']<>"")) {


/* Suche aus artikeldaten und fertigungsmeldungen die letzen SN-Nummern */
$kontierung= $HTTP_POST_VARS['selectSNplus'];
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM artikeldaten WHERE Kontierung like '%$kontierung%' ";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

do {

 /* suche Fertigungsdatensätze heraus und alle außer fsn999999 sind dummy wegen Repararur*/
 mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT MAX( fsn ) letzteSN FROM fertigungsmeldungen WHERE fartikelid='$row_rst1[artikelid]' AND fertigungsmeldungen.fsn<'999999' ";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

	if ($totalRows_rst2>0 && $row_rst2['letzteSN']>0){
	
	
	   $neuefsn = 0;	
	/* ermittle Sprung  */
	if ($row_rst2['letzteSN']<100){   $neuefsn = 100;}
	if ($row_rst2['letzteSN']>=100 && $row_rst2['letzteSN']<1000){   $neuefsn = 1000;}
	if ($row_rst2['letzteSN']>=1000 && $row_rst2['letzteSN']<2000){   $neuefsn = 2000;}
	if ($row_rst2['letzteSN']>=2000 && $row_rst2['letzteSN']<3000){   $neuefsn = 3000;}
	if ($row_rst2['letzteSN']>=3000 && $row_rst2['letzteSN']<4000){   $neuefsn = 5000;}
	if ($row_rst2['letzteSN']>=4000 && $row_rst2['letzteSN']<5000){   $neuefsn = 6000;}
	if ($row_rst2['letzteSN']>=5000 && $row_rst2['letzteSN']<10000){   $neuefsn = 10000;}
	if ($row_rst2['letzteSN']>=10000 && $row_rst2['letzteSN']<20000){   $neuefsn = 20000;}
	if ($row_rst2['letzteSN']>=20000 && $row_rst2['letzteSN']<30000){   $neuefsn = 30000;}
	if ($row_rst2['letzteSN']>=30000 && $row_rst2['letzteSN']<40000){   $neuefsn = 40000;}
	if ($row_rst2['letzteSN']>=40000 && $row_rst2['letzteSN']<50000){   $neuefsn = 50000;}
	if ($row_rst2['letzteSN']>=50000 && $row_rst2['letzteSN']<60000){   $neuefsn = 60000;}
	if ($row_rst2['letzteSN']>=60000 && $row_rst2['letzteSN']<70000){   $neuefsn = 70000;}
	if ($row_rst2['letzteSN']>=70000 && $row_rst2['letzteSN']<80000){   $neuefsn = 80000;}
	if ($row_rst2['letzteSN']>=80000 ) { $neuefsn = $row_rst2['letzteSN']+1000;}
	 /* lege neue Datensatz an */

  $deleteSQL = sprintf("INSERT INTO fertigungsmeldungen (fartikelid ,fuser ,fdatum ,fsn ,version ,fsn1,fsn2,fsn3,fsn4,fsonder,ffrei,fnotes,signatur,pgeraet,fnotes1,frfid,referenz) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s ,%s,%s,%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($row_rst1['artikelid'], "int"),
					    GetSQLValueString(10, "text"),
					   "now()", 
					   GetSQLValueString($neuefsn, "text"), 
					   GetSQLValueString($row_rst1['version']." ", "text"), 
					   GetSQLValueString($row_rst1['fsn1']." ", "text"), 
					  GetSQLValueString($row_rst1['fsn2']." ", "text"), 
					   GetSQLValueString($row_rst1['fsn3']." ", "text"), 
					   GetSQLValueString($row_rst1['fsn4']." ", "text"), 
					   GetSQLValueString(1, "text"), 
					   GetSQLValueString(1, "text"), 
					   GetSQLValueString("technischer Datensatzsprung".$row_rst1['artikelid']." kopiert. ", "text"), 
					   GetSQLValueString($row_rst1['signatur']."SNSPRUNG", "text"),
					   GetSQLValueString($row_rst1['pgeraet']."H", "text"),
					   GetSQLValueString($row_rst1['fnotes1']."-", "text"),
					   GetSQLValueString($row_rst1['rfid'], "text"),
					   GetSQLValueString($row_rst1['fid'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());

  /*  merke die neue Nummer */
  	$lastins= mysql_insert_id();
	
	$oktxt.=" bei Artikel ".$row_rst1[artikelid]."-".$row_rst1[Bezeichnung]." mit SN $neuefsn fortgeschrieben, Fertigungsdaten angelegt.<br>";
	}else {
	$oktxt.=" bei Artikel ".$row_rst1[artikelid]."-".$row_rst1[Bezeichnung]." wurde nicht fortgeschrieben, da keine Fertigungsdaten vorhanden.<br>";
	}


 }while ($row_rst1 = mysql_fetch_assoc($rst1));/* und nehme nächste FID */
  
  $oktxt.="Zusammenfassung der Artikeldatensätze - Anzahl ".$totalRows_rst1." gescannt.<br>";


} /* Seriennummernsprung einfügen Ende*/



















$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = $url_user";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fehler";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rstmi = "SELECT * FROM qmbmitteilungen WHERE (qmbmitteilungen.datum)>= date(curdate()) and (qmbmitteilungen.lokz=0) ORDER BY qmbmitteilungen.datum";
$rstmi = mysql_query($query_rstmi, $nachrichten) or die(mysql_error());
$row_rstmi = mysql_fetch_assoc($rstmi);
$totalRows_rstmi = mysql_num_rows($rstmi);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstartikelliste = "SELECT * FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rstartikelliste = mysql_query($query_rstartikelliste, $qsdatenbank) or die(mysql_error());
$row_rstartikelliste = mysql_fetch_assoc($rstartikelliste);
$totalRows_rstartikelliste = mysql_num_rows($rstartikelliste);



if ((isset($HTTP_POST_VARS["la1"])) ) {
$updateGoTo = "la1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["k1"])) ) {
$updateGoTo = "k1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["la2"])) ) {
$updateGoTo = "la2.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["la3"])) ) {
$updateGoTo = "la3.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["la4"])) ) {
$updateGoTo = "la4.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["qmb4"])) ) {
$updateGoTo = "qmb4.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["la6"])) ) {
$updateGoTo = "la6.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["fhm1"])) ) {
$updateGoTo = "fhm1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["la99"])) ) {
$updateGoTo = "la99.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["w1"])) ) {
$updateGoTo = "w1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["vis3"])) ) {
$updateGoTo = "vis3.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["vis10"])) ) {
$updateGoTo = "vis10.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["mi1"])) ) {
$updateGoTo = "mi1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["passwd"])) ) {
$updateGoTo = "pa<sswd.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}
if ((isset($HTTP_POST_VARS["p1"])) ) {
$updateGoTo = "p1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["la7"])) ) {
$updateGoTo = "la7.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["vl3"])) ) {
$updateGoTo = "vl3.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

	$colname_rstlokz = "1";
if (isset($HTTP_POST_VARS['hurl_fid'])) {
  $colname_rstlokz = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['hurl_fid'] : addslashes($HTTP_POST_VARS['hurl_fid']);
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstlokz = sprintf("SELECT * FROM fertigungsmeldungen WHERE fid = '$colname_rstlokz' ");
$rstlokz = mysql_query($query_rstlokz, $qsdatenbank) or die(mysql_error());
$row_rstlokz = mysql_fetch_assoc($rstlokz);
$totalRows_rstlokz = mysql_num_rows($rstlokz);

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="0" cellspacing="1">
    <tr> 
      <td colspan="4"><strong>Spezialprogramme FIS</strong></td>
    </tr>
    <tr> 
      <td colspan="4"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td colspan="4"> <?php if ($totalRows_rstmi > 0) { // Show if recordset not empty ?>
        <img src="picture/ur.gif" width="18" height="18"> Liste der Mitteilungen 
        <img src="picture/Bild.gif" width="22" height="9"> <br>
        <table width="780" border="0" cellpadding="0" cellspacing="0">
          <tr bgcolor="#CCCCFF" class="value"> 
            <td width="229"><em><strong>Information</strong></em></td>
            <td width="551"><em></em></td>
          </tr>
          <?php
  $i=1;
   do { 
  $i=$i+1;
$d=intval($i/2);
  
  ?>
          <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?> > 
            <td> 
              <img src="picture/iconMessage_16x16.gif" width="16" height="16" align="absmiddle"> 
              <?php 
	  if ($row_rstmi['datum']== date("Y-m-d",time() )){
	  echo "heute";
	  } else{
	  
	  
	  echo $row_rstmi['datum'];}
	   ?>
              - <?php echo substr($row_rstmi['Zeit'],0,5); ?> Uhr</td>
            <td    ><?php echo nl2br(utf8_decode($row_rstmi['name'])); ?> 
            </td>
          </tr>
          <?php } while ($row_rstmi = mysql_fetch_assoc($rstmi)); ?>
        </table>
        <br>
        <br>
        <?php } // Show if recordset not empty ?> </td>
    </tr>
    <tr> 
      <td colspan="4"><p><em><strong>Sehen was los ist:</strong></em> Zusammenfassung 
          ab 
          <?php if ($HTTP_POST_VARS['anzahltage']==0 or !isset($HTTP_POST_VARS['anzahltage'])){ $HTTP_POST_VARS['anzahltage']=date("Ymdhms",time()-100000);} ?>
          <?php if ($HTTP_POST_VARS['arbeitstage']==0 or !isset($HTTP_POST_VARS['arbeitstage'])){ $HTTP_POST_VARS['arbeitstage']=date("Ymdhms",time());;} ?>
          <input name="anzahltage" type="text" id="anzahltage" value="<?php echo $HTTP_POST_VARS['anzahltage'] ?>" size="20" maxlength="20">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'anzahltage\', \'time\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          bis 
          <input name="arbeitstage" type="text" id="arbeitstage" value="<?php echo $HTTP_POST_VARS['arbeitstage']; ?>" size="20" maxlength="20">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'arbeitstage\', \'time\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          <input name="Ansichtaktualisieren" type="submit" id="Ansichtaktualisieren" value="Aktualisieren">
          <?php include("pp.rosesweingarten.php"); ?> <p>&nbsp; </p></td>
    </tr>
    <tr> 
      <td colspan="4"><?php include("pp.rosesweingarten2.php"); ?></td>
    </tr>
    <tr> 
      <td colspan="4"><?php include("pp.rosesweingarten3.php"); ?></td>
    </tr>
    <td colspan="4"><hr></td>
    </tr>
    <tr> 
      <td colspan="2"><em><strong>Aktionen w&auml;hlen:</strong></em></td>
      <td width="58">&nbsp;</td>
      <td width="275">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td width="81">Ettiketten</td>
      <td width="316">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td rowspan="4">&nbsp;</td>
      <td rowspan="4">&nbsp;</td>
      <td>Text</td>
      <td><textarea name="text" cols="60" rows="6" wrap="VIRTUAL" id="text"><?php echo $HTTP_POST_VARS['text']; ?></textarea></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Materialnummer</td>
      <td><input name="materialnummer" type="text" id="materialnummer" value="<?php echo $HTTP_POST_VARS['materialnummer']; ?>">
        als Barcode 
        <input <?php if (!(strcmp($HTTP_POST_VARS['barcode'],1))) {echo "checked";} ?> name="barcode" type="checkbox" id="barcode" value="1"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Schriftgr&ouml;&szlig;e</td>
      <td><select name="schrift">
          <option value="6" <?php if (!(strcmp(6, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>6</option>
          <option value="7" <?php if (!(strcmp(7, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>7</option>
          <option value="8" <?php if (!(strcmp(8, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>8</option>
          <option value="9" <?php if (!(strcmp(9, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>9</option>
          <option value="10" <?php if (!(strcmp(10, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>10</option>
          <option value="11" <?php if (!(strcmp(11, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>11</option>
          <option value="12" <?php if (!(strcmp(12, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>12</option>
          <option value="13" <?php if (!(strcmp(13, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>13</option>
          <option value="14" <?php if (!(strcmp(14, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>14</option>
          <option value="15" <?php if (!(strcmp(15, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>15</option>
          <option value="16" <?php if (!(strcmp(16, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>16</option>
          <option value="18" <?php if (!(strcmp(18, $HTTP_POST_VARS['schrift']))) {echo "SELECTED";} ?>>17</option>
        </select></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Logo</td>
      <td> <div align="left"> 
          <select name="logo" id="logo">
            <option value="" <?php if (!(strcmp("", $HTTP_POST_VARS['logo']))) {echo "SELECTED";} ?>>keine 
            Logoaufdruck</option>
            <option value="BCK" <?php if (!(strcmp("BCK", $HTTP_POST_VARS['logo']))) {echo "SELECTED";} ?>>BLANCO 
            CS Kunststofftechnik GmbH</option>
            <option value="BLANCOCS" <?php if (!(strcmp("BLANCOCS", $HTTP_POST_VARS['logo']))) {echo "SELECTED";} ?>>BLANCO 
            CS Gmbh&amp;Co.KG</option>
          </select>
          <input name="etidruck" type="submit" id="etidruck" value="Aktualisieren">
          <a href="pdfettikett12.php?schrift=<?php echo $HTTP_POST_VARS['schrift'] ?>&text=<?php echo $HTTP_POST_VARS['text'] ?>&logo=<?php echo $HTTP_POST_VARS['logo'] ?>&materialnummer=<?php echo $HTTP_POST_VARS['materialnummer'] ?>&barcode=<?php echo $HTTP_POST_VARS['barcode'] ?>" target="_blank"> 
          <img src="picture/pdf_icon.gif" width="16" height="19" border="0"></a> 
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><strong><em> <br>
        Administratortools und Konfiguration</em></strong></td>
      <td>&nbsp;</td>
      <td><p><font color="#009900"><?php echo $oktxt; ?>&nbsp;</font></p>
        <p><font color="#FF0000"><?php echo $errtxt ?></font></p></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="46"><img src="picture/myaccount.gif" width="52" height="36"></td>
      <td>FID &auml;ndern</td>
      <td>&nbsp;</td>
      <td><input name="hurl_fid" type="text" id="hurl_fid" value="<?php if ($totalRows_rstlokz==1) { echo $HTTP_POST_VARS['hurl_fid']; }?>"> 
        <input name="fidstatussuchen" type="submit" id="fidstatussuchen" value="Fertigungsdatensatz suchen"> 
      </td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="46">&nbsp;</td>
      <td colspan="2"> <p> 
          <textarea name="textarea" cols="48" rows="10"><?php echo $row_rstlokz['history']; ?></textarea>
          <br>
          fdatum: <?php echo $row_rstlokz['fdatum']; ?></p>
        <p>erstellt: <?php echo $row_rstlokz['erstelltdatum']; ?><br>
          freigegeben: <?php echo $row_rstlokz['freigabedatum']; ?></p>
        <p>verkauft am: <?php echo $row_rstlokz['verkauftam']; ?><br>
          versendet am: <?php echo $row_rstlokz['versendetam']; ?></p></td>
      <td> letzte Aktion <?php echo $HTTP_POST_VARS['RadioGroup1']; ?> <table width="391">
          <tr> 
            <td><label> 
              <input type="radio" name="RadioGroup1" value="1">
              Löschkennzeichnung/ Falschdruck</label></td>
          </tr>
          <tr> 
            <td><label> 
              <input type="radio" name="RadioGroup1" value="2">
              zurück auf freigegeben (in BCK, nicht verkauft)</label></td>
          </tr>
          <tr> 
            <td><label> 
              <input type="radio" name="RadioGroup1" value="3">
              zurück auf Arbeitsanfang</label></td>
          </tr>
          <tr> 
            <td><label> 
              <input type="radio" name="RadioGroup1" value="4">
              zurück auf verkauft (also im Lager)</label></td>
          </tr>
          <tr> 
            <td><label> 
              <input type="radio" name="RadioGroup1" value="5">
              ist versendet</label></td>
          </tr>
        </table>
        <input name="fidstatusaendern" type="submit" id="fidstatusaendern" value="Fertigungsdatensatz &auml;ndern"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="46">&nbsp;</td>
      <td><p><font color="#FF0000">Fertigungsdaten l&ouml;schen</font></p>
        <p><font color="#FF0000">Achtung: Wenn weg dannn weg.</font></p></td>
      <td><font color="#FF0000">FID:</font></td>
      <td> <font color="#FF0000"> 
        <input name="fiddelete" type="submit" id="fiddelete" value="Fertigungsdatensatz L&ouml;schen">
        </font></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="46">&nbsp;</td>
      <td><p><img src="picture/b_insrow.png" width="16" height="16"> Stammdaten 
          spiegeln<br>
          <br>
          <br>
          Achtung. Es werden alle FID unter einem ausw&auml;hlten Material unter 
          eine neue Materialnummer kopiert. die alte FID erh&auml;lt eine Referenz 
          des neuen Datensatzes und die neue FID erh&auml;lt die FID des alten.</p>
        <p>Es wird nichts gel&ouml;scht.</p></td>
      <td>&nbsp;</td>
      <td><p>1. Fertigungsmeldungen f&uuml;r folgendes Material<br>
          <select name="selecteins" id="selecteins">
            <?php
do {  
?>
            <option value="<?php echo $row_rstartikelliste['artikelid']?>"><?php echo $row_rstartikelliste['Bezeichnung']?></option>
            <?php
} while ($row_rstartikelliste = mysql_fetch_assoc($rstartikelliste));
  $rows = mysql_num_rows($rstartikelliste);
  if($rows > 0) {
      mysql_data_seek($rstartikelliste, 0);
	  $row_rstartikelliste = mysql_fetch_assoc($rstartikelliste);
  }
?>
          </select>
          <br>
        </p>
        <p>2. Auswahl des Material, wo die neuen Datens&auml;tze angelegt werden.</p>
        <p> 
          <select name="selectzwei" id="selectzwei">
            <?php
do {  
?>
            <option value="<?php echo $row_rstartikelliste['artikelid']?>"><?php echo $row_rstartikelliste['Bezeichnung']?></option>
            <?php
} while ($row_rstartikelliste = mysql_fetch_assoc($rstartikelliste));
  $rows = mysql_num_rows($rstartikelliste);
  if($rows > 0) {
      mysql_data_seek($rstartikelliste, 0);
	  $row_rstartikelliste = mysql_fetch_assoc($rstartikelliste);
  }
?>
          </select>
          <br>
        </p>
        <p>3. 
          <input name="FIDspiegeln" type="submit" id="FIDspiegeln" value="Fertigungsdaten spiegeln">
        </p></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="46">&nbsp;</td>
      <td><p><img src="picture/b_insrow.png" width="16" height="16"> Seriennummersprung 
          erstellen.</p>
        <p>Dabei wird jeder Artikelgescannt und noch Fertigungsdaten gepr&uuml;ft. 
          anschlie&szlig;end wird ein leerer Datensatz mit einer gem&auml;&szlig; 
          einer Rundungstabelle unter Sondefreigabe angelegt. es Richtet sich 
          nach der Kontierung:</p>
        <p>Standard einstellung : es berifft nur Fertigware. Es muss f&uuml;r 
          jede Kontierung separat durchgef&uuml;hrt werden, da die Laufzeit des 
          Scriptes zu lange dauert.</p>
        <p>Hinweis: Die Aktion nur einmal auf&uuml;hren.</p></td>
      <td>&nbsp;</td>
      <td><p> 
          <select name="selectSNplus" id="selectSNplus">
            <option>bitte Auswahl treffen</option>
            <option value="fertigware">Kontierung: Fertigware</option>
            <option value="ersatz">Kontierung: Ersatzteile</option>
            <option value="halbteile">Kontierung: Halbteile</option>
            <option value="rohstoffe">Kontierung: Rohstoffe</option>
            <option value="industrie">Kontierung: Industrieteile</option>
          </select>
        </p>
        <p> 
          <input name="SNplus" type="submit" id="SNplus" value="Seriennummersprung erstellen">
        </p>
        <p>
          <input name="SNcheckbox" type="checkbox" id="SNcheckbox" value="1">
          wirklich ausf&uuml;hren?</p>
        <p>&nbsp; </p></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="46" colspan="4"><?php echo $oktxt; ?>&nbsp;</td>
    </tr>
  </table>
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  <input type="hidden" name="MM_update" value="form1">
</form>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rstmi);

mysql_free_result($rstartikelliste);

mysql_free_result($rstlokz);
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php include("footer.tpl.php"); ?>
