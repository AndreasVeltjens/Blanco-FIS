<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php  $la = "la24";
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

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE fehler SET fkurz=%s, fname=%s, lokz=%s, farbeitsplatzgruppe=%s, fkosten=%s WHERE fid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fkurz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fname'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['farbeitsplatzgruppe'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fkosten'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["new"]))) {
  $insertSQL = sprintf("INSERT INTO fehler (fkurz, fname, fuser) VALUES (%s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['textfield'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textfield2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fehler  ORDER BY fehler.lokz, fehler.fkurz, fehler.farbeitsplatzgruppe";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM `user`";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_apg = "SELECT * FROM arbeitsplatz ORDER BY arbeitsplatz.arb_name";
$rst_apg = mysql_query($query_rst_apg, $qsdatenbank) or die(mysql_error());
$row_rst_apg = mysql_fetch_assoc($rst_apg);
$totalRows_rst_apg = mysql_num_rows($rst_apg);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la2.php?url_user=".$row_rst1['id'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}


if ((isset($HTTP_POST_VARS["add"])) ) {
$updateGoTo = "la23.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_id=".$HTTP_POST_VARS["hurl_id"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["err"])) ) {
$updateGoTo = "la24.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="892" border="0" cellspacing="0" cellpadding="0">
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
      <td width="339"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="123"> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"><a href="getexcel5.php" target="_blank">Download 
          als *.xls</a></div></td>
    </tr>
    <tr> 
      <td>Kurzbezeichnung</td>
      <td> <input type="text" name="textfield"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Beschreibung</td>
      <td><input name="textfield2" type="text" size="50" maxlength="255"> </td>
      <td><div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="new" type="submit" id="new" value="Fehler anlegen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>
<input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
</form>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Fehler<br>
 
  
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="105"><strong><em>Bezeichnung</em></strong></td>
    <td width="179"><strong><em>....</em></strong></td>
    <td width="81"><strong>verbundene Kosten</strong></td>
    <td width="81"><strong><em>angelegt von</em></strong></td>
    <td width="95"><strong>ID</strong></td>
    <td width="100"><strong>lokz</strong></td>
    <td width="159"><div align="right"></div></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['fkurz']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['fname']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['fkosten']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst9['id'], $row_rst2['fuser']))) {echo $row_rst9['name'];}?> 
        <?php
} while ($row_rst9 = mysql_fetch_assoc($rst9));
  $rows = mysql_num_rows($rst9);
  if($rows > 0) {
      mysql_data_seek($rst9, 0);
	  $row_rst9 = mysql_fetch_assoc($rst9);
  }
?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['fid']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $row_rst2['fid']; ?>">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
      <?php if (isset($HTTP_POST_VARS['edit']) && ($HTTP_POST_VARS['hurl_id']==$row_rst2['fid'])){?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/1.gif" width="20" height="10"> <input name="fkurz" type="text" id="fkurz" value="<?php echo $row_rst2['fkurz']; ?>" size="10"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input name="fname" type="text" id="fname" value="<?php echo $row_rst2['fname']; ?>" size="50"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input name="fkosten" type="text" id="fkosten" value="<?php echo $row_rst2['fkosten']; ?>"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <select name="farbeitsplatzgruppe" id="farbeitsplatzgruppe">
          <?php
do {  
?>
          <option value="<?php echo $row_rst_apg['arb_id']?>"<?php if (!(strcmp($row_rst_apg['arb_id'], $row_rst2['farbeitsplatzgruppe']))) {echo "SELECTED";} ?>><?php echo $row_rst_apg['arb_name']?></option>
          <?php
} while ($row_rst_apg = mysql_fetch_assoc($rst_apg));
  $rows = mysql_num_rows($rst_apg);
  if($rows > 0) {
      mysql_data_seek($rst_apg, 0);
	  $row_rst_apg = mysql_fetch_assoc($rst_apg);
  }
?>
        </select></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="checkbox" type="checkbox" id="checkbox" value="1" <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?>></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><img src="picture/save.gif" width="18" height="18"> 
          <input name="save" type="submit" id="save" value="speichern">  <input type="hidden" name="MM_update" value="form1">
        </div></td>
      <?php }?>
    
  </form></tr>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
 
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst9);

mysql_free_result($rst_apg);
?>
<?php include("footer.tpl.php"); ?>
