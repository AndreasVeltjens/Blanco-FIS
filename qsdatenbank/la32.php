<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la32";
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
  $updateSQL = sprintf("UPDATE entscheidung SET entname=%s, entmemo=%s, entdatum=%s, lokz=%s, gvwl=%s, ueberschrift=%s, sapkst=%s WHERE entid=%s",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($HTTP_POST_VARS['gvwl']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($HTTP_POST_VARS['ueberschrift'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['sapkst'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_entid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la3.php";
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
$query_rst2 = "SELECT * FROM entscheidung WHERE entscheidung.entid='$url_entid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la3.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la33.php?url_user=".$row_rst1['id']."&url_entid=".$HTTP_POST_VARS["hurl_entid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
<table width="950" border="0" cellspacing="0" cellpadding="0">
  
  
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
      <td width="128">
<input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
   </td>
      <td width="364">
<div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Varianten anlegen">
          <img src="picture/b_newdb.png" width="16" height="16"> 
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
  
  
  <table width="950" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td height="24">Name des Prozesses</td>
      <td><input name="name" type="text" id="name" value="<?php echo utf8_decode($row_rst2['entname']); ?>" size="50" maxlength="255"></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&Uuml;berschrift Prozesses</td>
      <td><input name="ueberschrift" type="text" id="ueberschrift" value="<?php echo utf8_decode($row_rst2['ueberschrift']); ?>" size="70" maxlength="255"></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td width="153">Beschreibung im Anschreiben (Kostenvoranschlag, Warenbegleitschrein)</td>
      <td width="442"> <textarea name="beschreibung" cols="75" rows="12" id="beschreibung"><?php echo utf8_decode($row_rst2['entmemo']); ?></textarea></td>
      <td width="19">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum:</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo $row_rst2['entdatum']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>SAP-Kostenstelle</td>
      <td> <input name="sapkst" type="text" id="sapkst" value="<?php echo $row_rst2['sapkst']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Gew&auml;hrleistungsprozess</td>
      <td> <input name="gvwl" type="checkbox" id="gvwl" value="1" <?php if (!(strcmp($row_rst2['gvwl'],1))) {echo "checked";} ?>> 
      </td>
      <td>&nbsp; </td>
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
      <td><?php echo $row_rst2['entid']; ?> <input name="hurl_entid" type="hidden" id="hurl_entid" value="<?php echo $row_rst2['entid']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
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
