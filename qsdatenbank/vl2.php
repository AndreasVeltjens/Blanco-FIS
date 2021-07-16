<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/qsdatenbank.php'); ?>
<?php  $la = "vl2";
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
  $insertSQL = sprintf("INSERT INTO auslieferungen (nummer, bezeichnung, avis, typ, stueck) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['nummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['bezeichnung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['avis'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['htyp'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['stueck'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["save"]))) {
  $updateSQL = sprintf("UPDATE auslieferungen SET stueck=%s, avis=%s WHERE idaus=%s",
                       GetSQLValueString($HTTP_POST_VARS['stueck'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['avis'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_idaus'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS['delete']))) {
  $deleteSQL = sprintf("DELETE FROM auslieferungen WHERE idaus=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_idaus'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM auslieferungen WHERE auslieferungen.typ = 2 ORDER BY auslieferungen.bezeichnung asc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
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

if ((isset($HTTP_POST_VARS["neu"])) ) {
$updateGoTo = "la11.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong>LA1-Fertigungsmeldungen Montierte Teile</strong></td>
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
    <td rowspan="3"><img src="picture/icon_gr_fertigungsmeldung2.gif" width="55" height="75"></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Artikeltypen<br>
  
<table width="733" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="119"><em><strong>Bezeichnung</strong></em></td>
    <td width="132"><em><strong>Materialnummer</strong></em></td>
    <td width="91"><em><strong>St&uuml;ck</strong></em></td>
    <td width="165"><strong>aktuell </strong></td>
    <td width="104">&nbsp;</td>
    <td width="122">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['bezeichnung']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['nummer']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['stueck']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input name="stueck" type="text" id="stueck" value="<?php echo $row_rst2['stueck']; ?>" size="10">
        <input name="avis" type="hidden" id="avis" value="<?php echo $row_rst2['avis']; ?>" size="10"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/b_newdb.png" width="16" height="16"> 
        <input name="save" type="submit" id="neu5" value="speichern"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> <img src="picture/b_drop.png" width="16" height="16"> 
          <input name="delete" type="submit" id="delete" value="L&ouml;schen">
          <input name="typ" type="hidden" id="typ" value="<?php echo $row_rst2['typ']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_idaus" type="hidden" id="hurl_idaus" value="<?php echo $row_rst2['idaus']; ?>">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form1">
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  
</table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
</p>
<p>&nbsp;</p>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td colspan="5"><em>neue Materialnummer hinzuf&uuml;gen</em></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="121"><em><strong>Bezeichnung</strong></em></td>
      <td width="146"><em><strong>Materialnummer</strong></em></td>
      <td width="96"><em><strong>St&uuml;ck aktuell</strong></em></td>
      <td width="106"><strong><em></em></strong></td>
      <td width="159">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap"><input name="bezeichnung" type="text" id="bezeichnung"></td>
      <td nowrap="nowrap"> <input name="nummer" type="text" id="nummer"></td>
      <td nowrap="nowrap"><input name="stueck" type="text" id="stueck3"></td>
      <td nowrap="nowrap"><input name="avis" type="hidden" id="avis" value="0"> 
      </td>
      <td nowrap="nowrap"> <div align="right">
          <input name="htyp" type="hidden" id="htyp" value="2">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="add" type="submit" id="add" value="hinzuf&uuml;gen">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>

<p>&nbsp;</p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

?>
<?php include("footer.tpl.php"); ?>
