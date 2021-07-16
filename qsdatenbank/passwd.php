<?php require_once('Connections/qsdatenbank.php'); ?>
<?php 
$la="passwd";
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
if (($HTTP_POST_VARS["password1"])==($HTTP_POST_VARS["password2"])) {
if  ($HTTP_POST_VARS["password1"]==""){ $errtext="Passwort enth&auml;lt Leerzeichen. Bitte nochmals eingeben.";}else{
 $updateSQL = sprintf("UPDATE user SET passwort=%s, email=%s, telefon=%s, fax=%s, anschrift=%s, plz=%s, ort=%s, land=%s, werk=%s WHERE id=%s",
                       GetSQLValueString($HTTP_POST_VARS['password1'], "text"),
                   
                       GetSQLValueString($HTTP_POST_VARS['email'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['tel'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fax'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anschrift'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['plz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['land'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  $errtext="Passwort ge&auml;ndert.";
  }
}else{
$errtext="Passw&ouml;rter stimmen nicht überein. Bitte neu eingeben.";
}
}


$url_user =$HTTP_POST_VARS['benutzername'];
$url_pass = $HTTP_POST_VARS['passwort'];
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.username = '$url_user' AND user.passwort = '$url_pass'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["anmelden"])) && ($totalRows_rst1 > 0)) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Benutzname oder Passwort fehlerhaft. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");

?>

<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong>Eigene Einstellungen &auml;ndern</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
    <tr> 
      <td>&nbsp;</td>
      <td><img src="picture/icon_login.gif" width="28" height="28"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="141">Ihr 
        Benutzerkonto</td>
      <td width="190"><input name="benutzername" type="text" id="benutzername"> 
      </td>
      <td width="283"> 
        <input name="hiddenField" type="hidden" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="passwort" type="password" id="passwort"> 
      </td>
      <td><input name="seichern" type="submit" id="seichern" value="nochmals anmelden"> 
      </td>
    </tr>
    <?php } // Show if recordset empty ?>
    <tr> 
      <td><img src="picture/myaccount.gif" width="52" height="36"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <?php if ($totalRows_rst1 > 0) { // Show if recordset not empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="141">ID</td>
      <td width="95"><?php echo $row_rst1['id']; ?></td>
      <td width="378"><?php echo $row_rst1['username']; ?> <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Rechtessystem</td>
      <td><?php echo $row_rst1['recht']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>User aktiviert</td>
      <td> <input <?php if (!(strcmp($row_rst1['aktiviert'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <?php } // Show if recordset not empty ?>
  <p>&nbsp;</p>
  <p> 
    <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
    <font color="#FF0000">Bitte melden Sie sich nochmals mit Benutzername und 
    bisherigem Passwort an.</font> 
    <?php } // Show if recordset empty ?>
    <br>
  </p>
  <?php if ($totalRows_rst1 > 0) { // Show if recordset not empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="141">Name:</td>
      <td width="297"> <input name="name" type="text" id="name2" value="<?php echo $row_rst1['name']; ?>"></td>
      <td width="176">&nbsp;</td>
    </tr>
    <tr> 
      <td>Passwort:</td>
      <td> <input name="password1" type="password" id="password12"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>nochmaliges Passwort:</td>
      <td> <input name="password2" type="password" id="password22"></td>
      <td> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>E-Mail:</td>
      <td><input name="email" type="text" id="email" value="<?php echo $row_rst1['email']; ?>" size="80"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Telefon:</td>
      <td><input name="tel" type="text" id="tel" value="<?php echo $row_rst1['telefon']; ?>" size="80"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Fax:</td>
      <td><input name="fax" type="text" id="fax" value="<?php echo $row_rst1['fax']; ?>" size="80"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="24">Anschrift:</td>
      <td><input name="anschrift" type="text" id="anschrift" value="<?php echo $row_rst1['anschrift']; ?>" size="80"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>PLZ + Ort</td>
      <td><input name="plz" type="text" id="plz" value="<?php echo $row_rst1['plz']; ?>" size="10" maxlength="10"> <input name="ort" type="text" id="ort" value="<?php echo $row_rst1['ort']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Land</td>
      <td><input name="land" type="text" id="land" value="<?php echo $row_rst1['land']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Werk</td>
      <td><input name="werk" type="text" id="werk" value="<?php echo $row_rst1['werk']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"><div align="right"> 
          <input name="save" type="submit" id="save" value="Daten &uuml;bernehmen">
        </div></td>
    </tr>
  </table>
  <?php } // Show if recordset not empty ?>
  <p>&nbsp; </p>
  <?php if ($totalRows_rst1 > 0) { // Show if recordset not empty ?>
  <?php } // Show if recordset not empty ?>
  <p> 
    <input type="hidden" name="MM_update" value="form1">
    <br>
  </p>
</form>
  
<p><strong><font color="#FF0000"><?php echo $errtext;?></font></strong></p>
<p> 
  <?php

mysql_free_result($rst1);
?>
</p>
<?php include("footer.tpl.php"); ?>
