<?php require_once('Connections/nachrichten.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "tracking";
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

?><?php 


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
    <tr bgcolor="#CCCCFF"> 
      <td height="46"><img src="picture/icon-route.gif" width="50" height="30"></td>
      <td>FID &auml;ndern</td>
      <td width="58">&nbsp;</td>
      <td width="275"><input name="hurl_fid" type="text" id="hurl_fid" value="<?php if ($totalRows_rstlokz==1) { echo $HTTP_POST_VARS['hurl_fid']; }?>"> 
        <input name="fidstatussuchen" type="submit" id="fidstatussuchen" value="Fertigungsdatensatz suchen"> 
      </td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="46">&nbsp;</td>
      <td colspan="2"> <p> 
          <textarea name="textarea" cols="48" rows="15"><?php echo $row_rstlokz['history']; ?></textarea>
          <br>
          fdatum: <?php echo $row_rstlokz['fdatum']; ?></p>
        <p>erstellt: <?php echo $row_rstlokz['erstelltdatum']; ?><br>
          freigegeben: <?php echo $row_rstlokz['freigabedatum']; ?></p>
        <p>verkauft am: <?php echo $row_rstlokz['verkauftam']; ?><br>
          versendet am: <?php echo $row_rstlokz['versendetam']; ?></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <td> <p>Material: 
          <select name="select">
            <option value="" <?php if (!(strcmp("", $row_rstlokz['fartikelid']))) {echo "SELECTED";} ?>>FID 
            eingabe fehlt.- keine Zuordnung gefunden..</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rstartikelliste['artikelid']?>"<?php if (!(strcmp($row_rstartikelliste['artikelid'], $row_rstlokz['fartikelid']))) {echo "SELECTED";} ?>><?php echo substr($row_rstartikelliste['Beschreibung'],0,40)?> <?php echo $row_rstartikelliste['Nummer']?></option>
            <?php
} while ($row_rstartikelliste = mysql_fetch_assoc($rstartikelliste));
  $rows = mysql_num_rows($rstartikelliste);
  if($rows > 0) {
      mysql_data_seek($rstartikelliste, 0);
	  $row_rstartikelliste = mysql_fetch_assoc($rstartikelliste);
  }
?>
          </select>
        </p>
        <p>letzte Aktion: <?php echo $HTTP_POST_VARS['RadioGroup1']; ?> </p>
        <table width="391">
          <tr> 
            <td><label> 
              <input type="radio" name="RadioGroup1" value="1">
              Löschkennzeichnung/ Falschdruck</label></td>
          </tr>
          <tr> 
            <td><label> 
              <input type="radio" name="RadioGroup1" value="2">
              zurück auf freigegeben (in BCK, aber nicht verkauft)</label></td>
          </tr>
          <tr> 
            <td><label> 
              <input type="radio" name="RadioGroup1" value="3">
              zurück auf Arbeitsanfang (in Arbeit bei BCK)</label></td>
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
