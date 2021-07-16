<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "la72";
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


if ((isset($HTTP_POST_VARS["save"]))) {
   $updateSQL = sprintf("UPDATE pruefungen SET name=%s, soll=%s, einheit=%s, ugw=%s, ogw=%s, ueg=%s, oeg=%s, lokz=%s, memo=%s, uegtxt=%s, oegtxt=%s, wert1unit=%s, wert2unit=%s, wert3unit=%s, wert4unit=%s, wert5unit=%s, anzahlwerte=%s, skalierung=%s, wert1text=%s, wert2text=%s, wert3text=%s, wert4text=%s, wert5text=%s, Umrechnungsfaktor=%s, werk=%s, formelid=%s , pruefart=%s , intervall=%s , gruppe=%s, fhm_id_pid=%s WHERE id_pruef=%s",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['soll'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['einheit'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ugw'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ogw'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['ueg'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['oeg'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['memo'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['uegtxt'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['oegtxt'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert1unit'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert2unit'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert3unit'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert4unit'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert5unit'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anzahlwerte'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['skalierfaktor'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert1text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert2text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert3text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert4text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert5text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['umrechnungsfaktor'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['formelid'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['pruefart'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['intervall'], "int"),
					   
					   GetSQLValueString($HTTP_POST_VARS['gruppe'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['fhm_id_pid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_pruef'], "int"));
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la7.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

 

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM pruefungen WHERE pruefungen.id_pruef='$url_id_pruef'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_parametertabelle = "SELECT * FROM fhm_parameter ORDER BY fhm_parameter.pid_programm";
$parametertabelle = mysql_query($query_parametertabelle, $qsdatenbank) or die(mysql_error());
$row_parametertabelle = mysql_fetch_assoc($parametertabelle);
$totalRows_parametertabelle = mysql_num_rows($parametertabelle);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la7.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la74.php?url_user=".$row_rst1['id']."&url_id_pruef=".$HTTP_POST_VARS["hurl_id_pruef"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["diagramm"])) ) {
$updateGoTo = "la73.php?url_user=".$row_rst1['id']."&url_id_pruef=".$HTTP_POST_VARS["hurl_id_pruef"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="113">Aktionen W&auml;hlen:</td>
      <td width="108"> <input name="imageField3" type="image" src="picture/error.gif" width="16" height="16" border="0">
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="509"> <div align="right">
          <input name="imageField" type="image" src="picture/column-chart.gif" width="16" height="16" border="0">
          <input name="diagramm" type="submit" id="diagramm" value="Diagramm zeigen">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Messwerte anlegen">
          <input name="imageField2" type="image" src="picture/Cfinclude.gif" width="18" height="18" border="0">
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"> 
          <script language="JavaScript" type="text/JavaScript">
function sf(){document.form1.name.focus()}
</script>
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>Name der Pr&uuml;fung</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_rst2['name']; ?>" size="60" maxlength="255"></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td width="164">Beschreibung</td>
      <td width="431"> <textarea name="memo" cols="60" rows="8" id="memo"><?php echo $row_rst2['memo']; ?></textarea></td>
      <td width="17">&nbsp;</td>
    </tr>
    <tr> 
      <td>Einheit</td>
      <td> <input name="einheit" type="text" id="einheit" value="<?php echo $row_rst2['einheit']; ?>"> 
        <select name="pruefart" id="pruefart">
          <option value="1" <?php if (!(strcmp(1, $row_rst2['pruefart']))) {echo "SELECTED";} ?>>Temperatur</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['pruefart']))) {echo "SELECTED";} ?>>Masse</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst2['pruefart']))) {echo "SELECTED";} ?>>Zeit</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst2['pruefart']))) {echo "SELECTED";} ?>>Dichte</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst2['pruefart']))) {echo "SELECTED";} ?>>Sonstiges</option>
        </select> </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="25">UEG</td>
      <td> <input name="ueg" type="text" id="ueg" value="<?php echo $row_rst2['ueg']; ?>"> 
        <?php echo $row_rst2['einheit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="25">UWG</td>
      <td> <input name="ugw" type="text" id="ugw" value="<?php echo $row_rst2['ugw']; ?>"> 
        <?php echo $row_rst2['einheit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>SOLL-Wert</td>
      <td> <input name="soll" type="text" id="soll" value="<?php echo $row_rst2['soll']; ?>"> 
        <?php echo $row_rst2['einheit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>OWG</td>
      <td> <input name="ogw" type="text" id="ogw" value="<?php echo $row_rst2['ogw']; ?>"> 
        <?php echo $row_rst2['einheit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>OEG</td>
      <td> <input name="oeg" type="text" id="oeg" value="<?php echo $row_rst2['oeg']; ?>"> 
        <?php echo $row_rst2['einheit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Ma&szlig;nahmetext untere Eingriff</td>
      <td> <textarea name="uegtxt" cols="60" id="uegtxt"><?php echo $row_rst2['uegtxt']; ?></textarea> 
      </td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>Ma&szlig;nahmetext obere Eingriff</td>
      <td> <textarea name="oegtxt" cols="60" id="oegtxt"><?php echo $row_rst2['oegtxt']; ?></textarea> 
      </td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td><img src="picture/b_drop.png" width="16" height="16">L&ouml;schkennzeichen 
      </td>
      <td> <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1"> 
      </td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td height="19">ID</td>
      <td> <input name="hurl_id_pruef" type="hidden" id="hurl_id_pruef" value="<?php echo $row_rst2['id_pruef']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Wert1 Beschreibung/ Einheit </td>
      <td><input name="wert1text" type="text" id="wert1text" value="<?php echo $row_rst2['wert1text']; ?>"> 
        <input name="wert1unit" type="text" id="wert1unit" value="<?php echo $row_rst2['wert1unit']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Wert2 Beschreibung/ Einheit </td>
      <td><input name="wert2text" type="text" id="wert2text" value="<?php echo $row_rst2['wert2text']; ?>"> 
        <input name="wert2unit" type="text" id="wert2unit" value="<?php echo $row_rst2['wert2unit']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Wert3 Beschreibung/ Einheit </td>
      <td><input name="wert3text" type="text" id="wert3text" value="<?php echo $row_rst2['wert3text']; ?>"> 
        <input name="wert3unit" type="text" id="wert3unit" value="<?php echo $row_rst2['wert3unit']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Wert4 Beschreibung/ Einheit </td>
      <td><input name="wert4text" type="text" id="wert4text" value="<?php echo $row_rst2['wert4text']; ?>"> 
        <input name="wert4unit" type="text" id="wert4unit" value="<?php echo $row_rst2['wert4unit']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Wert5 Beschreibung/ Einheit </td>
      <td><input name="wert5text" type="text" id="wert5text" value="<?php echo $row_rst2['wert5text']; ?>"> 
        <input name="wert5unit" type="text" id="wert5unit" value="<?php echo $row_rst2['wert5unit']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Formel-ID</td>
      <td> <select name="formelid" id="formelid">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['formelid']))) {echo "SELECTED";} ?>>keine 
          Formel ausgewählt</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst2['formelid']))) {echo "SELECTED";} ?>>Dichte 
          wert4/(wert1*wert2*wert3)</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['formelid']))) {echo "SELECTED";} ?>>mittlere 
          Dichte (wert1/wert4)</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst2['formelid']))) {echo "SELECTED";} ?>>nicht 
          verwenden</option>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Anzahl der Eingabefelder</td>
      <td><input name="anzahlwerte" type="text" id="anzahlwerte" value="<?php echo $row_rst2['anzahlwerte']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Skalierfaktor Anzeige</td>
      <td><input name="skalierfaktor" type="text" id="skalierfaktor" value="<?php echo $row_rst2['skalierung']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">fester Umrechnungsfaktor</td>
      <td><input name="umrechnungsfaktor" type="text" id="umrechnungsfaktor3" value="<?php echo $row_rst2['Umrechnungsfaktor']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Werkszuordnung </td>
      <td><input name="werk" type="text" id="werk" value="<?php echo $row_rst2['werk']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Pr&uuml;fintervall</td>
      <td><input name="intervall" type="text" id="intervall" value="<?php echo $row_rst2['intervall']; ?>">
        Tage </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Gruppe</td>
      <td><input name="gruppe" type="text" id="gruppe" value="<?php echo $row_rst2['gruppe']; ?>" size="80" maxlength="200">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Parameter oder Programm-Nr.</td>
      <td>
<select name="fhm_id_pid" id="fhm_id_pid">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['fhm_id_pid']))) {echo "SELECTED";} ?>>keine 
          Zuordnung-unbekannt</option>
          <?php
do {  
?>
          <option value="<?php echo $row_parametertabelle['fhm_pid']?>"<?php if (!(strcmp($row_parametertabelle['fhm_pid'], $row_rst2['fhm_id_pid']))) {echo "SELECTED";} ?>><?php echo $row_parametertabelle['pid_programm']." - ".$row_parametertabelle['pid_mk']." - ".$row_parametertabelle['pid_txt'];?></option>
          <?php
} while ($row_parametertabelle = mysql_fetch_assoc($parametertabelle));
  $rows = mysql_num_rows($parametertabelle);
  if($rows > 0) {
      mysql_data_seek($parametertabelle, 0);
	  $row_parametertabelle = mysql_fetch_assoc($parametertabelle);
  }
?>
        </select></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($parametertabelle);
?>
</p>

  <?php include("footer.tpl.php"); ?>
