<?php require_once('Connections/qsdatenbank.php'); ?><?php $la = "la75";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
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
  $updateSQL = sprintf("UPDATE pruefungdaten SET datum=%s, `user`=%s, messwert1=%s, messwert2=%s, messwert3=%s, messwert4=%s, messwert5=%s, lokz=%s WHERE id_pruefd=%s",
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['messwert1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['messwert2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['messwert3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['messwert4'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['messwert5'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_pruefd'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la74.php";
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
$query_rst4 = "SELECT * FROM pruefungdaten WHERE pruefungdaten.id_pruefd='$url_id_pruefd'";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la74.php?url_user=".$row_rst1['id'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}


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
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="128"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="364"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
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
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="614" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>User</td>
      <td><input name="user" type="text" id="user" value="<?php echo $row_rst4['user']; ?>" size="50" maxlength="255"></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td><input name="datum" type="text" id="datum" value="<?php echo $row_rst4['datum']; ?>"></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td width="153">&nbsp;</td>
      <td width="442">&nbsp; </td>
      <td width="19">&nbsp;</td>
    </tr>
    <tr> 
      <td>Messwert 1 <?php echo $row_rst2['wert1text']; ?></td>
      <td> <input name="messwert1" type="text" id="messwert1" value="<?php echo $row_rst4['messwert1']; ?>">
        <?php echo $row_rst2['wert1unit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Messwert 2 <?php echo $row_rst2['wert2text']; ?></td>
      <td> <input name="messwert2" type="text" id="messwert2" value="<?php echo $row_rst4['messwert2']; ?>">
        <?php echo $row_rst2['wert2unit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="25">Messwert 3 <?php echo $row_rst2['wert3text']; ?></td>
      <td> <input name="messwert3" type="text" id="messwert3" value="<?php echo $row_rst4['messwert3']; ?>">
        <?php echo $row_rst2['wert3unit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Messwert 4 <?php echo $row_rst2['wert4text']; ?></td>
      <td> <input name="messwert4" type="text" id="messwert4" value="<?php echo $row_rst4['messwert4']; ?>">
        <?php echo $row_rst2['wert4unit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Messwert 5 <?php echo $row_rst2['wert5text']; ?></td>
      <td> <input name="messwert5" type="text" id="messwert5" value="<?php echo $row_rst4['messwert5']; ?>">
        <?php echo $row_rst2['wert5unit']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td><img src="picture/b_drop.png" width="16" height="16">L&ouml;schkennzeichen 
      </td>
      <td> <input <?php if (!(strcmp($row_rst4['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1">
      </td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td height="19">ID</td>
      <td> <input name="hurl_id_pruef" type="hidden" id="hurl_id_pruef" value="<?php echo $row_rst2['id_pruef']; ?>">
        <?php echo $row_rst4['id_pruef']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">ID-Messwert</td>
      <td> <input name="hurl_id_pruefd" type="hidden" id="hurl_id_pruefd" value="<?php echo $row_rst4['id_pruefd']; ?>">
        <?php echo $row_rst4['id_pruefd']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">&nbsp;</td>
      <td>&nbsp;</td>
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

mysql_free_result($rst4);
?>
</p>

  <?php include("footer.tpl.php"); ?>
