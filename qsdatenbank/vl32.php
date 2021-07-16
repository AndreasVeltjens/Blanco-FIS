<?php require_once('Connections/qsdatenbank.php'); ?>
<?php  $la = "vl32";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = (($theValue != "") ? "'" . $theValue . "'" : "NULL");
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM ablage WHERE ablage.id_ablage='$url_id_ablage'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT fhm_gruppe.id_fhm_gruppe, fhm_gruppe.name FROM fhm_gruppe WHERE fhm_gruppe.lokz=0";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM versandlabel WHERE versandlabel.id_ett=$url_id_ett";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "vl3.php?url_user=".$row_rst1['id'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["save"] ))) {
  $updateSQL = sprintf("UPDATE versandlabel SET name=%s, nummer=%s, link=%s, formatett=%s WHERE id_ett=%s",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['nummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['link'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['formatett'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_ett'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "vl3.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>



<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  <table width="614" border="0" cellspacing="0" cellpadding="0">
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
      <td width="123"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="save" type="submit" id="save" value="anlegen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>
<input name="hurl_id_ett" type="hidden" id="hurl_id_ett" value="<?php echo $row_rst4['id_ett']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Name</td>
      <td><strong> 
        <textarea name="name" cols="50" rows="3" id="name"><?php echo $row_rst4['name']; ?></textarea>
        </strong></td>
      <td><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr> 
      <td>Materialnummer</td>
      <td><input name="nummer" type="text" id="nummer" value="<?php echo $row_rst4['nummer']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Link/Dokument</td>
      <td><input name="link" type="text" id="link" value="<?php echo $row_rst4['link']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Format</td>
      <td><input name="formatett" type="text" id="formatett" value="<?php echo $row_rst4['formatett']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>
<br>
<p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);
?>
</p>

  <?php include("footer.tpl.php"); ?>
