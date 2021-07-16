<?php require_once('Connections/qsdatenbank.php'); 
require_once('../Connections/qsdatenbank.php'); ?><?php $la = "fhm22";
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

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form4")) {
  $insertSQL = sprintf("INSERT INTO fhmprueschritt (id_p, name, istwert, sollwert, ergebnis, gruppe) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_p2'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['istwert'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['grenzwert'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ergebnis'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['gruppe'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form20")) {
  $insertSQL = sprintf("INSERT INTO link_artikeldaten_pruef (artikelid, pruef_id) VALUES (%s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['artikelid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_p'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["add"]) )) {
  $insertSQL = sprintf("INSERT INTO fhm_pruef (name, lokz, datum, dauer, tage, gruppe) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['dauer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['tage'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['gruppe'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["save"]))) {
  $updateSQL = sprintf("UPDATE fhm_pruef SET name=%s, lokz=%s, datum=%s, dauer=%s, tage=%s, gruppe=%s WHERE id_p=%s",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['dauer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['tage'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['gruppe'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_p'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fhmprueschritt WHERE fhmprueschritt.id_p='$url_id_p' ORDER BY fhmprueschritt.gruppe, fhmprueschritt.reihenfolge";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM fhm_pruef WHERE fhm_pruef.id_p='$url_id_p'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst21 = "SELECT * FROM artikeldaten, link_artikeldaten_pruef WHERE link_artikeldaten_pruef.pruef_id='$url_id_p' and artikeldaten.artikelid=link_artikeldaten_pruef.artikelid ORDER BY artikeldaten.Gruppe";
$rst21 = mysql_query($query_rst21, $qsdatenbank) or die(mysql_error());
$row_rst21 = mysql_fetch_assoc($rst21);
$totalRows_rst21 = mysql_num_rows($rst21);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst20 = "SELECT * FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rst20 = mysql_query($query_rst20, $qsdatenbank) or die(mysql_error());
$row_rst20 = mysql_fetch_assoc($rst20);
$totalRows_rst20 = mysql_num_rows($rst20);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "fhm2.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "fhm22.php?url_user=".$row_rst1['id']."&url_id_p=".$HTTP_POST_VARS['hurl_id_p'];
  header(sprintf("Location: %s", $updateGoTo));
}
if ((isset($HTTP_POST_VARS["neu"])) ) {
$updateGoTo = "fhm21.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
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
    <td width="152">Aktionen W&auml;hlen:</td>
    <td width="339"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      </form></td>
    <td width="123" rowspan="4"> <div align="right"> </div></td>
  </tr>
  <tr> 
    <td rowspan="3"><img src="picture/icon_gr_fertigungsmeldung.gif" width="55" height="75"></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>

</table>
  
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="733" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="177">Beschreibung</td>
      <td width="438"> <input name="name" type="text" id="name" value="<?php echo $row_rst3['name']; ?>" size="60"> 
      </td>
      <td width="118"> <input name="hurl_id_p" type="hidden" id="hurl_id_p" value="<?php echo $row_rst3['id_p']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr> 
      <td>Lokz</td>
      <td><input <?php if (!(strcmp($row_rst3['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="checkbox"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pr&uuml;ffristzeitraum</td>
      <td><input name="tage" type="text" id="tage" value="<?php echo $row_rst3['tage']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Dauer der Pr&uuml;fung</td>
      <td><input name="dauer" type="text" id="dauer" value="<?php echo $row_rst3['dauer']; ?>"></td>
      <td><input name="add" type="submit" id="add" value="Kopie anlegen"></td>
    </tr>
    <tr> 
      <td>Gruppe</td>
      <td><input name="gruppe" type="text" id="gruppe" value="<?php echo $row_rst3['gruppe']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td><input name="datum" type="text" id="datum" value="<?php echo $row_rst3['datum']; ?>"></td>
      <td><input name="save" type="submit" id="save" value="Speichern"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form3">
  <input type="hidden" name="MM_insert" value="form3">
</form>
<p><strong><em>Neuen Pr&uuml;fschritt anlegen</em></strong></p>
<form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="733" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="178">Beschreibung</td>
      <td width="375"> <input name="beschreibung" type="text" id="beschreibung" value="<?php echo $row_rst3['datum']; ?>" size="80" maxlength="255"></td>
      <td width="180">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Grenzwert</td>
      <td> <input name="grenzwert" type="text" id="grenzwert"> </td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Einheit</td>
      <td> <input name="ergebnis" type="text" id="ergebnis"> </td>
      <td> <div align="right"> 
          <input name="save2" type="submit" id="save24" value="neu anlegen">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Art</td>
      <td> <select name="istwert" id="istwert">
          <option value="0">keine Auswahl</option>
          <option value="1">Ja/Nein</option>
          <option value="2">Wert</option>
        </select></td>
      <td><input name="hurl_id_p2" type="hidden" id="hurl_id_p22" value="<?php echo $row_rst3['id_p']; ?>">
        <input name="hurl_user2" type="hidden" id="hurl_user3" value="<?php echo $url_user; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td>Gruppe</td>
      <td><input name="gruppe" type="text" id="gruppe"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form4">
</form>
<p>&nbsp;</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Pr&uuml;fungen<br>
  
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="261"><strong><em>Bezeichnung</em></strong></td>
    <td width="93"><strong><em>Grenzwert</em></strong></td>
    <td width="92"><strong><em>Einheit</em></strong></td>
    <td width="101"><strong><em>Art</em></strong></td>
    <td width="183">&nbsp;</td>
  </tr>
  <?php 
  $gruppe="";
  do { ?>
  <?php   if ($row_rst2['gruppe']!=$gruppe){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="8" nowrap="nowrap"    bgcolor="#EEEEEE"><em><br>
      <img src="picture/s_tbl.png" width="16" height="16"> <?php echo $row_rst2['gruppe']; ?></em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['name']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['sollwert']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['ergebnis']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['istwert']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_id_p" type="hidden" id="hurl_id_p" value="<?php echo $row_rst2['id_p']; ?>">
          <input name="hurl_id_ps" type="hidden" id="hurl_id_ps" value="<?php echo $row_rst2['id_ps']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php
    $gruppe=$row_rst2['gruppe'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
</p>
<p><strong><em>Neue Artikelzuordnung anlegen</em></strong></p>
<form name="form20" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="733" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="178">Beschreibung</td>
      <td width="375"> <select name="artikelid" id="artikelid">
          <option value="""" <?php if (!(strcmp("", $row_rst1['id']))) {echo "SELECTED";} ?>>keine 
          Auswahl</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst20['artikelid']?>"<?php if (!(strcmp($row_rst20['artikelid'], $HTTP_POST_VARS['artikelid']))) {echo "SELECTED";} ?>><?php echo $row_rst20['Bezeichnung']?></option>
          <?php
} while ($row_rst20 = mysql_fetch_assoc($rst20));
  $rows = mysql_num_rows($rst20);
  if($rows > 0) {
      mysql_data_seek($rst20, 0);
	  $row_rst20 = mysql_fetch_assoc($rst20);
  }
?>
        </select></td>
      <td width="180">
<input name="addartikelid" type="submit" id="addartikelid" value="neu anlegen"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input name="hurl_id_p" type="hidden" id="hurl_id_p23" value="<?php echo $row_rst3['id_p']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form20">
</form>
<p>&nbsp; </p>
<?php if ($totalRows_rst21 > 0) { // Show if recordset not empty ?>
Liste der Artikelzuordnungen<br>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="261"><strong><em>Bezeichnung</em></strong></td>
    <td width="93"><strong><em>Material-Nr.</em></strong></td>
    <td width="92"><strong></strong></td>
    <td width="101"><strong></strong></td>
    <td width="183">&nbsp;</td>
  </tr>
  <?php 
  $gruppe="";
  do { ?>
  <?php   if ($row_rst21['Gruppe']!=$gruppe){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="8" nowrap="nowrap"    bgcolor="#EEEEEE"><em><br>
      <img src="picture/s_tbl.png" width="16" height="16"> <?php echo $row_rst21['Gruppe']; ?></em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst21['Bezeichnung']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst21['Nummer']; ?> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"> 
          <input name="hurl_user3" type="hidden" id="hurl_user2" value="<?php echo $url_user; ?>">
          <input name="hurl_id_p3" type="hidden" id="hurl_id_p2" value="<?php echo $row_rst21['id_p']; ?>">
          <input name="hurl_id_ps2" type="hidden" id="hurl_id_ps2" value="<?php echo $row_rst21['id_ps']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="edit2" type="submit" id="edit2" value="Bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php
    $gruppe=$row_rst21['Gruppe'];
   } while ($row_rst21 = mysql_fetch_assoc($rst21)); ?>
</table>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rst21 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
<?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user3" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
</p>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst20);

mysql_free_result($rst3);

?>
<?php include("footer.tpl.php"); ?>
