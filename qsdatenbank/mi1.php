<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php  $la = "mi1";
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

if ((isset($HTTP_POST_VARS['delete']))) {
  $deleteSQL = sprintf("DELETE FROM mitteilung WHERE id_mitt=%s",
                       GetSQLValueString($HTTP_POST_VARS['hmitt'], "int"));

  mysql_select_db($database_nachrichten, $nachrichten);
  $Result1 = mysql_query($deleteSQL, $nachrichten) or die(mysql_error());
}

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["add"]))) {
  $insertSQL = sprintf("INSERT INTO mitteilung (name, datum, Zeit, vorlauf, werk, beschreibung,`user`) VALUES (%s, %s, %s, %s, %s,  %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['zeit'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['vorlauf'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "text"));

  mysql_select_db($database_nachrichten, $nachrichten);
  $Result1 = mysql_query($insertSQL, $nachrichten) or die(mysql_error());
}


$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rst2 = "SELECT * FROM mitteilung ORDER BY mitteilung.datum desc";
$rst2 = mysql_query($query_rst2, $nachrichten) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "mi12.php?url_user=".$row_rst1['id']."&url_id_mitt=".$HTTP_POST_VARS["hmitt"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong>Mitteilungen pflegen</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="152">Aktionen W&auml;hlen:</td>
    <td width="339"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </form></td>
    <td width="123" rowspan="4"> <div align="right"> </div></td>
  </tr>
  <tr> 
    <td rowspan="3"><img src="picture/email.gif" width="52" height="36"></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
  
<p>&nbsp;</p>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td colspan="5"><em>neue Mitteilung hinzuf&uuml;gen</em></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="150"><em><strong>Datum</strong></em></td>
      <td width="124"><em><strong>Zeit</strong></em></td>
      <td width="147"><em><strong>Name</strong></em></td>
      <td width="168"><strong><em>Vorlauf</em></strong></td>
      <td width="141">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap"><input name="datum" type="text" id="datum" size="20" maxlength="10"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td nowrap="nowrap"> <input name="zeit" type="text" id="zeit" size="15"></td>
      <td nowrap="nowrap"> 
        <textarea name="name" cols="50" rows="3" id="name"></textarea> 
      </td>
      <td nowrap="nowrap"><input name="vorlauf" type="text" id="vorlauf2" value="-1" size="10"> 
      </td>
      <td nowrap="nowrap"> <div align="right"> 
          <input name="werk" type="hidden" id="werk" value="<?php echo $row_rst1['werk']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="add" type="submit" id="add" value="hinzuf&uuml;gen">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');">
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">Zusatz</td>
      <td nowrap="nowrap"><textarea name="beschreibung" cols="60" rows="3" id="beschreibung"></textarea></td>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>
<p>&nbsp;</p>

  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Mitteilungen<br>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="117"><em><strong>Datum</strong></em></td>
    <td width="126"><em><strong>Zeit</strong></em></td>
    <td width="162"><em><strong>Name</strong></em></td>
    <td width="89"><strong>Vorlauf</strong></td>
    <td width="81"><strong>Werk</strong></td>
    <td width="71"><div align="right">
        <p>User </p>
        </div></td>
    <td width="113">lokz</td>
    <td width="243">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/iconMessage_16x16.gif" alt="<?php echo $row_rst2['beschreibung']; ?>" width="16" height="16" border="0" align="absmiddle"></a> 
        <?php echo $row_rst2['datum']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Zeit']; ?>Uhr </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo nl2br($row_rst2['name']); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst2['vorlauf']; ?>Tag</div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst2['werk']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right">
          <input name="typ" type="hidden" id="typ2" value="<?php echo $row_rst2['typ']; ?>">
          <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $url_user; ?>">
          <input name="hurl_idaus" type="hidden" id="hurl_idaus2" value="<?php echo $row_rst2['idaus']; ?>">
          <?php echo $row_rst2['user']; ?> </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">
<input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1">
        <img src="picture/b_newdb.png" width="16" height="16"> 
        <input name="edit" type="submit" id="edit" value="&auml;ndern"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hmitt" type="hidden" id="hmitt" value="<?php echo $row_rst2['id_mitt']; ?>">
          <img src="picture/b_drop.png" width="16" height="16"> 
          <input name="delete" type="submit" id="delete" value="L&ouml;schen">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form1">
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<br>
<?php if ($palettenzahl < $palettenzahlL) {echo "<br><strong>Es muss noch gearbeitet werden !</strong>";}
?><?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
</p>
<p>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

?>
</p>
<?php include("footer.tpl.php"); ?>
