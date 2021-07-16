<?php require_once('Connections/qsdatenbank.php'); ?>
<?php  $la = "vl412";
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

if ((isset($HTTP_POST_VARS["add"]))) {
  $insertSQL = sprintf("INSERT INTO auslieferungen (nummer, bezeichnung, avis, paletten, palettenL,  typ, stueck) VALUES (%s, %s, %s,%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['nummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['bezeichnung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['avis'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['paletten'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['palettenL'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['htyp'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['stueck'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["save"]))) {
  $updateSQL = sprintf("UPDATE auslieferungen SET avis=%s, palettenL=%s  WHERE idaus=%s",
                       GetSQLValueString($HTTP_POST_VARS['stueckL'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['palettenL'], "text"),
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
$query_rst2 = "SELECT * FROM auslieferungen WHERE typ = 4 ORDER BY paletten ASC";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "co11.php?url_user=".$row_rst1['id'];
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
    <td colspan="3"><strong>vl412-Fertigungsmeldungen GEISS DU 1500 T6 Twinsheet</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="180">Aktionen W&auml;hlen:</td>
    <td width="542"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <input name="imageField" type="image" src="picture/iconRelistBlu_16x16.gif" width="16" height="16" border="0">
        <input type="submit" name="Submit" value="Ansicht aktualisieren">
        <a href="vl411.gantthourex1.php?<?php echo $HTTP_POST_VARS['datum']; ?>" target="_blank"> 
        <img src="picture/column-chart.gif" width="16" height="16" border="0">Diagramm 
        anzeigen</a> <a href="../documents/Vorlagen/ww-checkliste.doc" target="_blank"><img src="picture/bt_download_PDF.gif" width="17" height="18" border="0"> 
        WW-Checkliste</a> 
      </form></td>
    <td width="8" rowspan="4"> <div align="right"> </div></td>
  </tr>
  <tr> 
    <td rowspan="3"><img src="picture/icon_gr_fertigungsmeldung.gif" width="55" height="75"><img src="picture/weltweit_handeln.png" width="61" height="45"></td>
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
<table width="804" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="116"><div align="left"> 
        <p><font size="1"><em><strong> Reihen-<br>
          folge </strong></em></font></p>
      </div></td>
    <td width="119"><font size="1"><em><strong>Bezeichnung</strong></em></font></td>
    <td width="94"><font size="1"><em><strong>Material<br>
      nummer</strong></em></font></td>
    <td width="85"><p align="center"><font size="1"><em><strong>St&uuml;ck</strong></em></font></p></td>
    <td width="85"><div align="center"><font size="1"><em><strong>Vorgabe-<br>
        zeit</strong></em></font></div></td>
    <td width="124" bgcolor="#CCCCFF"><font size="1"><em><strong>Fertigungsmenge<br>
      GUT - Ausschu&szlig;</strong></em></font></td>
    <td width="42"><div align="right"> 
        <p><em><font size="1"></font></em></p>
      </div></td>
    <td width="139"><font size="1">&nbsp;</font></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><strong><img src="picture/b_newdb.png" width="16" height="16"><?php echo $row_rst2['paletten']; 
	
		
		?></strong></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['bezeichnung']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['nummer']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['stueck']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo round($row_rst2['stueck']*$row_rst2['zeit']/60/60,1); ?></div></td>
      <td    bgcolor="#CCCCFF" nowrap="nowrap"> 
        <input name="stueckL" type="text" id="stueck" value="<?php echo $row_rst2['avis']; ?>" size="10"> 
        <input name="palettenL" type="text" id="paletten" value="<?php echo $row_rst2['palettenL']; 
		$palettenzahl = $palettenzahl + $row_rst2['paletten'];
		
		$palettenzahlL=$palettenzahlL + $row_rst2['palettenL'];
		
		
		?>" size="10"> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <strong> St&uuml;ck </strong></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="save" type="submit" id="neu5" value="speichern">
          <input name="typ" type="hidden" id="typ" value="<?php echo $row_rst2['typ']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_idaus" type="hidden" id="hurl_idaus" value="<?php echo $row_rst2['idaus']; ?>">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form1">
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
.<img src="vl412.gantthourex1.php?datum=<?php echo $HTTP_POST_VARS['datum']; ?>"><br>
<?php if ($palettenzahl < $palettenzahlL) {echo "<br><strong>Es muss noch gearbeitet werden !</strong>";}
?>
<?php } // Show if recordset not empty ?>
  
  
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
</p>
<p><img src="picture/stars.gif" width="86" height="13"></p>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="645" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td colspan="5"><em>neue Materialnummer hinzuf&uuml;gen</em></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="192"><em><strong>Bezeichnung</strong></em></td>
      <td width="159"><em><strong>Materialnummer</strong></em></td>
      <td width="188"><em><strong>St&uuml;ck - Wochentag</strong></em></td>
      <td width="215"><p><strong><em><strong>Fertigungsmenge<br>
          </strong></em></strong><strong><em><strong>GUT-Ausschu&szlig;</strong></em></strong></p>
        </td>
      <td width="141">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap"><input name="bezeichnung" type="text" id="bezeichnung"></td>
      <td nowrap="nowrap"> <input name="nummer" type="text" id="nummer" size="15"></td>
      <td nowrap="nowrap"><input name="stueck" type="text" id="stueck3" size="10">
        <input name="paletten" type="text" id="stueck4" size="10"></td>
      <td nowrap="nowrap"><input name="avis" type="text" id="avis" value="0" size="10">
        <input name="palettenL" type="text" id="palettenL" value="0" size="10"> </td>
      <td nowrap="nowrap"> <div align="right">
          <input name="htyp" type="hidden" id="htyp" value="4">
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
