<?php require_once('Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
  $la = "vis34";
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

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE kennzahlen_steps SET modul=%s, `parameter`=%s, anzeigedauer=%s, lokz=%s, name=%s, reihenfolge=%s WHERE id_stp=%s",
                       GetSQLValueString($HTTP_POST_VARS['modul'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['parameter'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anzeigedauer'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['reihenfolge'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hid_stp'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "vis32.php?url_user=".$row_rst1['id']."&url_id_kzv=".$HTTP_POST_VARS['hurl_id_kzv'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen_visualisierung WHERE kennzahlen_visualisierung.id_kzv='$url_id_kzv'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM kennzahlen_steps WHERE kennzahlen_steps.id_stp='$url_id_stp' ";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

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
      <td colspan="3"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="163">Aktionen W&auml;hlen:</td>
      <td width="328"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="123"> <div align="right"></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <input name="hurl_id_kzv" type="hidden" id="hurl_id_kzv" value="<?php echo $row_rst2['id_kzv']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Name</td>
      <td><strong> 
        <textarea name="name" cols="50" rows="3" id="name"><?php echo $row_rst2['name']; ?></textarea>
        </strong></td>
      <td><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr> 
      <td height="24">Datum</td>
      <td><input name="datum" type="text" id="datum" value="<?php echo $row_rst2['datum']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>User</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Werk</td>
      <td><input name="werk" type="text" id="werk" value="<?php echo $row_rst2['werk']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ID-KZV</td>
      <td><?php echo $row_rst2['id_kzv']; ?>automatisch vergeben</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schkennzeichnung</td>
      <td> <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Gruppe</td>
      <td><input name="gruppe" type="text" id="gruppe" value="<?php echo $row_rst2['gruppe']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Anzeige</td>
      <td><input name="anzeige" type="text" id="anzeige" value="<?php echo $row_rst2['anzeige']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  
</form>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <p><strong><em>Step bearbeiten</em></strong></p>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td>ID</td>
      <td><?php echo $row_rst3['id_stp']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td width="153">Modul</td>
      <td width="255"> <input name="modul" type="text" id="modul" value="<?php echo $row_rst3['modul']; ?>"></td>
      <td width="206"> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Parameter</td>
      <td> <input name="parameter" type="text" id="parameter" value="<?php echo $row_rst3['parameter']; ?>"></td>
      <td> <input name="hurl_id_kzv" type="hidden" id="hurl_id_kzv" value="<?php echo $row_rst3['id_kzv']; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Anzeigedauer</td>
      <td> <input name="anzeigedauer" type="text" id="anzeigedauer" value="<?php echo $row_rst3['anzeigedauer']; ?>"></td>
      <td> <div align="right"> 
          <input name="hid_stp" type="hidden" id="hid_stp" value="<?php echo $row_rst3['id_stp']; ?>">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Angezeigter Name</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_rst3['name']; ?>" size="60"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Reihenfolge</td>
      <td><input name="reihenfolge" type="text" id="reihenfolge" value="<?php echo $row_rst3['reihenfolge']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>L&ouml;schkennzeichen</td>
      <td> <input <?php if (!(strcmp($row_rst3['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
      <td> <div align="right"> 
          <input name="edit2" type="submit" id="edit2" value="&auml;ndern">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
  <input type="hidden" name="MM_update" value="form2">
</form>
<p><strong><em>Liste der Steps</em></strong></p>

  <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>

<?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="21"><font size="1"><strong>Typ</strong></font></td>
    <td width="51"><strong>ID</strong></td>
    <td width="115"><strong>Bezeichnung</strong></td>
    <td width="113"><strong>Parameter</strong></td>
    <td width="122"><strong>Anzeigedauer</strong></td>
    <td width="68"><strong>Reihenfolge</strong></td>
    <td width="68"><strong>lokz</strong></td>
    <td width="232">&nbsp;</td>
  </tr>
  <?php do { ?>
  
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php if ($row_rst3['reihenfolge']==$row_rst2['step']) {?><img src="picture/st1.gif" width="15" height="15"><?php }?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo htmlentities(utf8_decode($row_rst3['name'])); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst3['modul']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;<?php echo $row_rst3['parameter']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst3['anzeigedauer']; ?>sek.</div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst3['reihenfolge']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp; 
        <input <?php if (!(strcmp($row_rst3['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">
<div align="right"><font size="1" face="Arial, Helvetica, sans-serif"> 
          </font></div></td>
    </tr>
  
  <?php } while ($row_rst3 = mysql_fetch_assoc($rst3)); ?>
</table>
<font size="1" face="Arial, Helvetica, sans-serif"> 
<input name="hurl_user2" type="hidden" id="hurl_user22" value="<?php echo $row_rst1['id']; ?>">
</font> 
<?php } // Show if recordset not empty ?>
<p>&nbsp;Statistik: <?php echo $totalRows_rst3 ?> Steps angelegt.</p>
<p>&nbsp; </p>
<p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
