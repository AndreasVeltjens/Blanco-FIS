<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la34";
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
  $insertSQL = sprintf("INSERT INTO entscheidungsvarianten (varid, varname, varbeschreibung, Kosten1, Datum, lokz, Kosten2, entid) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_varid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['kosten1'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['kosten2'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['select'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

  $insertGoTo = "la33.php";
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
$query_rst2 = "SELECT * FROM entscheidungsvarianten WHERE entscheidungsvarianten.varid='$url_varid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT entscheidung.entid, entscheidung.entname FROM entscheidung";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la33.php?url_user=".$row_rst1['id'];
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
      <td width="259"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="233"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="save" type="submit" id="save" value="Neu Anlegen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>gew&auml;hlter Prozess</td>
      <td>
<select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['entid']?>"<?php if (!(strcmp($row_rst3['entid'], $row_rst2['entid']))) {echo "SELECTED";} ?>><?php echo $row_rst3['entname']?></option>
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
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="614" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>Name des Varianten</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_rst2['varname']; ?>" size="50" maxlength="255"></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td width="180">Beschreibung</td>
      <td width="233"> <textarea name="beschreibung" cols="50" rows="8" id="beschreibung"><?php echo $row_rst2['varbeschreibung']; ?></textarea></td>
      <td width="201">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum:</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo $row_rst2['Datum']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>User-ID</td>
      <td> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
      <td>&nbsp;</td>
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
      <td><?php echo $row_rst2['entid']; ?> <input name="hurl_varid" type="hidden" id="hurl_varid" value="<?php echo $row_rst2['varid']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Kosten 1</td>
      <td><input name="kosten1" type="text" id="kosten1" value="<?php echo $row_rst2['Kosten1']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="19">Kosten 2</td>
      <td><input name="kosten2" type="text" id="kosten2" value="<?php echo $row_rst2['Kosten2']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
