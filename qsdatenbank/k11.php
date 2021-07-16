<?php require_once('Connections/qsdatenbank.php'); ?><?php
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
   $la = "k11";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "k1.php?url_user=".$row_rst1['id'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO zeichnungen (zeichnungsnummer, Ursprungszeichnung, art, revision, beschreibung, datum, ablage, bemerkung, id_ablage) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['zeichnungsnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ursprungszeichnung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['art'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['version'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['id_ablage'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['id_ablage'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

  $insertGoTo = "k1.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}





mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM zeichnungen WHERE zeichnungen.id_zeichnung = '$url_id_zeichnung'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT ablage.id_ablage, ablage.beschreibung FROM ablage WHERE ablage.lokz=0";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


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
      <td colspan="3">
        <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?>
      </td>
    </tr>
    <tr> 
      <td width="185">Aktionen W&auml;hlen:</td>
      <td width="306"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="123"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Beschreibung</td>
      <td><strong>
        <input name="beschreibung" type="text" id="beschreibung" value="<?php echo $row_rst2['beschreibung']; ?>" size="50" maxlength="255">
        </strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ID</td>
      <td>&nbsp;<?php echo $row_rst2['id_zeichnung']; ?> </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="614" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="186">Zeichnungsnummer</td>
      <td width="396"><input name="zeichnungsnummer" type="text" id="zeichnungsnummer" value="<?php echo $row_rst2['zeichnungsnummer']; ?>" size="50" maxlength="255">
      </td>
      <td width="32"><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>Art</td>
      <td> <input name="art" type="text" id="fmfd2" value="<?php echo $row_rst2['art']; ?>"></td>
      <td><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
      </td>
    </tr>
    <tr> 
      <td>Version</td>
      <td> <input name="version" type="text" id="version" value="<?php echo $row_rst2['revision']; ?>" size="10" maxlength="255"></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td height="19">Ursprungszeichnung</td>
      <td> <input name="ursprungszeichnung" type="text" id="ursprungszeichnung" value="<?php echo $row_rst2['Ursprungszeichnung']; ?>" size="10" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
    
  <table width="614" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="185">Bemerkungen<br> <br> </td>
      <td width="396"> <textarea name="textarea" cols="50" rows="3"><?php echo $row_rst2['bemerkung']; ?></textarea></td>
      <td width="33">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum:</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo $row_rst2['datum']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Ablage</td>
      <td>
<select name="id_ablage" id="id_ablage">
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['id_ablage']?>"<?php if (!(strcmp($row_rst3['id_ablage'], $row_rst2['id_ablage']))) {echo "SELECTED";} ?>><?php echo $row_rst3['beschreibung']?></option>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </select></td>
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

mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
