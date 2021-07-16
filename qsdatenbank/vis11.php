<?php require_once('Connections/qsdatenbank.php'); ?>
<?php  $la = "vis11";
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

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO kennzahlen (name, beschreibung, lokz, werk, xachse, yachse, gruppe, anzeige) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['xachse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['yachse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['gruppe'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anzeige'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

  $insertGoTo = "vis1.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
  


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen WHERE kennzahlen.id_kz='$url_id_kz'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "vis1.php?url_user=".$row_rst1['id'];
 header(sprintf("Location: %s", $GoTo));
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
          <input name="save" type="submit" id="save" value="Neu anlegen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <input name="hurl_id_kz" type="hidden" id="hurl_id_kz" value="<?php echo $row_rst2['id_kz']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Beschreibung</td>
      <td><strong> 
        <textarea name="beschreibung" cols="50" rows="3" id="beschreibung"><?php echo $row_rst2['beschreibung']; ?></textarea>
        </strong></td>
      <td><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr> 
      <td height="24">&Uuml;berschrift:</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_rst2['name']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>x-Achsenbezeichnung</td>
      <td><input name="xachse" type="text" id="xachse" value="<?php echo $row_rst2['xachse']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>y-Achsenbezeichnung</td>
      <td><input name="yachse" type="text" id="yachse" value="<?php echo $row_rst2['yachse']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Werk</td>
      <td><input name="werk" type="text" id="werk" value="<?php echo $row_rst2['werk']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Anzeige</td>
      <td><input name="anzeige" type="text" id="anzeige" value="<?php echo $row_rst2['anzeige']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ID</td>
      <td><?php echo $row_rst2['id_kz']; ?>automatisch vergeben</td>
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
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<br>
<p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);
?>
</p>

  <?php include("footer.tpl.php"); ?>
