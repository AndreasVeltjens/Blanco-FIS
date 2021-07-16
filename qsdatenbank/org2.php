<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/codatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];

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

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO kostenstellengruppen (kurzzeichen, beschreibung, id_subks, verantwortlicher, werk) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['kurzzeichen'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['id_subks'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['verantwortlicher'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "text"));

  mysql_select_db($database_codatenbank, $codatenbank);
  $Result1 = mysql_query($insertSQL, $codatenbank) or die(mysql_error());
  $oktxt="Daten ge&auml;ndert.";
  }

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE kostenstellengruppen SET kurzzeichen=%s, beschreibung=%s, id_subks=%s, verantwortlicher=%s, lokz=%s, Datum=%s, Datumlokz=%s, werk=%s WHERE id_ks=%s",
                       GetSQLValueString($HTTP_POST_VARS['kurzzeichen'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['id_subks'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['verantwortlicher'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['datumlokz'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_ks'], "int"));

  mysql_select_db($database_codatenbank, $codatenbank);
  $Result1 = mysql_query($updateSQL, $codatenbank) or die(mysql_error());
}
 $la = "org2";

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_codatenbank, $codatenbank);
$query_rst2 = "SELECT * FROM kostenstellengruppen WHERE kostenstellengruppen.id_ks='$url_id_ks'";
$rst2 = mysql_query($query_rst2, $codatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_codatenbank, $codatenbank);
$query_rst10 = "SELECT * FROM kostenstellengruppen ORDER BY kostenstellengruppen.kurzzeichen";
$rst10 = mysql_query($query_rst10, $codatenbank) or die(mysql_error());
$row_rst10 = mysql_fetch_assoc($rst10);
$totalRows_rst10 = mysql_num_rows($rst10);



if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "org1.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.</font></strong> <br> <?php } // Show if recordset empty ?>
        <font color="#FF0000"><?php echo $errtxt ?></font> <font color="#009900"><?php echo $oktxt ?></font> 
        <br> </td>
    </tr>
    <tr> 
      <td width="145">Aktionen W&auml;hlen:</td>
      <td width="81"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
        <img src="picture/b_drop.png" width="16" height="16"> </td>
      <td width="504"> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>"></td>
      <td><div align="right"> 
          <script language="JavaScript" type="text/JavaScript">
function sf(){document.form2.suchtext.focus()}
</script> 
          <p><input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
        </p></div></td>
    </tr>
  </table></form>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <p><em><strong><img src="picture/b_edit.png" width="16" height="16" align="absmiddle"> 
    Organisiationseinheit &auml;ndern:</strong></em></p>
  <table width="730" border="0" cellpadding="1" cellspacing="0" bgcolor="#CCCCFF">
    <tr> 
      <td width="144">Kurzzeichen</td>
      <td width="144">Verantwortlicher</td>
      <td width="152">Beschreibung</td>
      <td width="171">Werk </td>
      <td width="109"><div align="right"> 
          <input name="save" type="submit" id="save2" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td> <input name="kurzzeichen" type="text" id="kurzzeichen" value="<?php echo $row_rst2['kurzzeichen']; ?>"></td>
      <td> <input name="verantwortlicher" type="text" id="verantwortlicher" value="<?php echo $row_rst2['verantwortlicher']; ?>"></td>
      <td> <input name="beschreibung" type="text" id="beschreibung" value="<?php echo $row_rst2['beschreibung']; ?>" size="50"></td>
      <td><input name="werk" type="text" id="werk" value="<?php echo $row_rst2['werk']; ?>" size="8" maxlength="4"> 
      </td>
      <td> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>Zuordnung</td>
      <td><select name="id_subks" class="csslistbox" id="select2">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['id_subks']))) {echo "SELECTED";} ?>>Hauptebene</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst10['id_ks']?>"<?php if (!(strcmp($row_rst10['id_ks'], $row_rst2['id_subks']))) {echo "SELECTED";} ?>><?php echo $row_rst10['kurzzeichen']?></option>
          <?php
} while ($row_rst10 = mysql_fetch_assoc($rst10));
  $rows = mysql_num_rows($rst10);
  if($rows > 0) {
      mysql_data_seek($rst10, 0);
	  $row_rst10 = mysql_fetch_assoc($rst10);
  }
?>
        </select></td>
      <td> <input name="hurl_id_ks" type="hidden" id="hurl_id_ks" value="<?php echo $row_rst2['id_ks']; ?>"> 
        <input name="hurl_userid" type="hidden" id="hurl_userid" value="<?php echo $row_rst1['id']; ?>"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schkennzeichen</td>
      <td> <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo $row_rst2['Datum']; ?>"></td>
      <td><script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schdatum</td>
      <td> <input name="datumlokz" type="text" id="datumlokz" value="<?php echo $row_rst2['Datumlokz']; ?>"></td>
      <td><script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'datumlokz\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);


mysql_free_result($rst2);

mysql_free_result($rst10);



?>
</p>

  <?php include("footer.tpl.php"); ?>
