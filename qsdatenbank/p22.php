<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/fedatenbank.php'); ?>
<?php
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

$la = "p22";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "p2.php?url_user=".$row_rst1['id']."&url_prj_id=".$HTTP_POST_VARS['hurl_prj_id'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["save"] ))) {
  $updateSQL = sprintf("UPDATE projektdatendaten SET datum=%s, termin=%s, status=%s, erstellt=%s, beschreibung=%s, arbeitszeit=%s, stundensatz=%s WHERE prjd_id=%s",
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
					   GetSQLValueString($HTTP_POST_VARS['termin'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['status'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['erstellt'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['arbeitszeit'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['stundensatz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_prjd_id'], "int"));

  mysql_select_db($database_fedatenbank, $fedatenbank);
  $Result1 = mysql_query($updateSQL, $fedatenbank) or die(mysql_error());

  $updateGoTo = "p2.php?url_user=".$row_rst1['id']."&url_prj_id=".$HTTP_POST_VARS['hurl_prj_id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rst4 = "SELECT * FROM projektdatendaten WHERE projektdatendaten.prjd_id='$url_prjd_id'";
$rst4 = mysql_query($query_rst4, $fedatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);



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
      <td width="123"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="save" type="submit" id="save" value="speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <input name="hurl_prjd_id" type="hidden" id="hurl_prjd_id" value="<?php echo $row_rst4['prjd_id']; ?>"> 
        <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $row_rst4['prj_id']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Beschreibung Workflow<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 
        <br> <br> <br> <br> <br> <br> <br> <br> </td>
      <td><strong> 
        <textarea name="beschreibung" cols="60" rows="20" id="beschreibung"><?php echo $row_rst4['beschreibung']; ?></textarea>
        </strong></td>
      <td><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr> 
      <td>erstellt/ bearbeitet von</td>
      <td><input name="erstellt" type="text" id="erstellt" value="<?php echo $row_rst4['erstellt']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Arbeitszeit</td>
      <td><input name="arbeitszeit" type="text" id="arbeitszeit" value="<?php echo $row_rst4['arbeitszeit']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Stundensatz</td>
      <td> <input name="stundensatz" type="text" id="stundensatz" value="<?php echo $row_rst4['stundensatz']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td><input name="datum" type="text" id="datum" value="<?php echo $row_rst4['datum']; ?>" size="50" maxlength="255">
        <input name="hurl_prj_id2" type="hidden" id="hurl_prj_id2" value="<?php echo $url_prj_id; ?>"> 
        <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Termin</td>
      <td><input name="termin" type="text" id="termin" value="<?php echo $row_rst4['termin']; ?>" size="50" maxlength="255">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'termin\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Status 
        <?php if  ($row_rst4['status']==1 ){?>
        <img src="picture/fertig.gif" width="15" height="15"> 
        <?php }else{?>
        <img src="picture/b_edit.png" width="16" height="16"> 
        <?php }?>
      </td>
      <td>
<select name="status" id="status">
          <option value="1" <?php if (!(strcmp(1, $row_rst4['termin']))) {echo "SELECTED";} ?>>erledigt</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst4['termin']))) {echo "SELECTED";} ?>>geplant</option>
        </select></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
<br>
<p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst4);
?>
</p>

  <?php include("footer.tpl.php"); ?>
