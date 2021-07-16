<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php  $la = "vl41";
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

if ((isset($HTTP_POST_VARS["transfer"]))) {
  $insertSQL = sprintf("INSERT INTO produktionsprog (nummer, bezeichnung, stueck, avis, typ, paletten, palettenL, zeit, datum, gemg, gema, reihenfolge) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['nummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['bezeichnung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['avis'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['stueck'], "text"),
                       "3",
                       GetSQLValueString($HTTP_POST_VARS['paletten'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['palettenL'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['zeit'], "text"),
                       "now()",
                       "0", 
                       "0",
                       GetSQLValueString($HTTP_POST_VARS['paletten'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $errtxt=$errtxt." Transfer Datensatz kopiert";
  
}

if ((isset($HTTP_POST_VARS["planen"]))) {
$HTTP_POST_VARS['datum']=$HTTP_POST_VARS['datum']." 6:00";
} else {

$now = time();
$num = date("w");
if ($num == 0)
{ $sub = 6; }
else { $sub = ($num-1); }
$WeekMon  = mktime(0, 0, 0, date("m", $now)  , date("d", $now)-$sub, date("Y", $now));    //monday week begin calculation
$todayh = getdate($WeekMon); //monday week begin reconvert

$d = $todayh[mday];
$m = $todayh[mon];
$y = $todayh[year];
$HTTP_POST_VARS['datum']="$y-$m-$d"." 6:00";

}

if ((isset($HTTP_POST_VARS["add"]))) {
  $insertSQL = sprintf("INSERT INTO auslieferungen (nummer, bezeichnung, avis, paletten, palettenL,  typ, stueck, zeit) VALUES (%s,%s, %s, %s,%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['nummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['bezeichnung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['avis'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['paletten'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['palettenL'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['htyp'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['stueck'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['zeit'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["save"]))) {
  $updateSQL = sprintf("UPDATE auslieferungen SET stueck=%s, avis=%s, paletten=%s,  palettenL=%s WHERE idaus=%s",
                       GetSQLValueString($HTTP_POST_VARS['stueck'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['avis'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['paletten'], "text"),
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
$query_rst2 = "SELECT * FROM auslieferungen WHERE auslieferungen.typ = 3 ORDER BY auslieferungen.paletten asc";
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
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
<table width="1044" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong>vl41 Produktionsprogramm planen</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="117">Aktionen W&auml;hlen:</td>
      <td width="548"> 
        <input name="imageField2" type="image" src="picture/error.gif" width="16" height="16" border="0"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <input name="imageField" type="image" src="picture/iconRelistBlu_16x16.gif" width="16" height="16" border="0">
        <input type="submit" name="Submit" value="Ansicht aktualisieren">
        <a href="vl41.gantthourex1.php?<?php echo $HTTP_POST_VARS['datum']; ?>" target="_blank"><img src="picture/column-chart.gif" width="16" height="16" border="0">Diagramm 
        anzeigen</a> <a href="../documents/Vorlagen/ww-checkliste.doc" target="_blank"><img src="picture/bt_download_PDF.gif" width="17" height="18" border="0"> 
        WW-Checkliste</a></td>
    <td width="337"> <div align="right"> 
          <input name="imageField3" type="image" src="picture/blinklist.gif" width="16" height="16" border="0">
          Start Planung: 
          <input name="datum" type="text" id="datum" value="<?php echo $HTTP_POST_VARS['datum']; ?>">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          <input name="planen" type="submit" id="planen" value="Planen">
        </div></td>
  </tr>
</table></form>


  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Artikeltypen<br>
<table width="1055" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="89"><strong>Wochentag</strong></td>
    <td width="115"><em><strong>Bezeichnung</strong></em></td>
    <td width="115"><em><strong>Materialnummer</strong></em></td>
    <td width="82"><div align="center"><em><strong>St&uuml;ck</strong></em></div></td>
    <td width="68"><div align="center"><em><strong>Zeit [h]</strong></em></div></td>
    <td width="132"><strong>Planung<br>
      GUT - Wochentag</strong></td>
    <td width="157" bgcolor="#CCCCFF"><div align="right"> 
        <p><strong>R&uuml;ckmeldung</strong></p>
        <p><strong>GUT - Ausschu&szlig;</strong></p>
      </div></td>
    <td width="92">&nbsp;</td>
    <td width="152">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['paletten']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['bezeichnung']; ?> <input name="bezeichnung" type="hidden" id="bezeichnung" value="<?php echo $row_rst2['bezeichnung']; ?>"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['nummer']; ?> 
          <input name="nummer" type="hidden" id="nummer" value="<?php echo $row_rst2['nummer']; ?>">
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['stueck']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo round($row_rst2['zeit']*$row_rst2['stueck']/60/60,1); ?>
          <input name="zeit" type="hidden" id="zeit" value="<?php echo $row_rst2['zeit']; ?>">
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input name="stueck" type="text" id="stueck" value="<?php echo $row_rst2['stueck']; ?>" size="10"> 
        <input name="paletten" type="text" id="paletten" value="<?php echo $row_rst2['paletten']; 
		$palettenzahl = $palettenzahl + $row_rst2['paletten'];
		
		$palettenzahlL=$palettenzahlL + $row_rst2['palettenL'];
		
		
		?>" size="10"> </td>
      <td    bgcolor="#CCCCFF" nowrap="nowrap"><div align="right"> 
          <input name="avis" type="text" id="avis" value="<?php echo $row_rst2['avis']; ?>" size="10">
          <input name="palettenL" type="text" id="paletten3" value="<?php echo $row_rst2['palettenL']; 
	
		
		?>" size="10">
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/b_newdb.png" width="16" height="16"> 
        <input name="save" type="submit" id="neu5" value="speichern"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> <img src="picture/b_drop.png" width="16" height="16"> 
          <input name="delete" type="submit" id="delete" value="L&ouml;schen">
          <input name="typ" type="hidden" id="typ" value="<?php echo $row_rst2['typ']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="transfer" type="submit" id="transfer" value="Transfer into">
          <input name="hurl_idaus" type="hidden" id="hurl_idaus" value="<?php echo $row_rst2['idaus']; ?>">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="MM_insert" value="form1">
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<br>
<img src="vl41.gantthourex1.php?datum=<?php echo $HTTP_POST_VARS['datum']; ?>"><br>
<?php if ($palettenzahl < $palettenzahlL) {echo "<br><strong>Es muss noch gearbeitet werden !</strong>";}
?>
<?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
</p>
<p>&nbsp;</p>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="915" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td colspan="5"><em>neue Materialnummer hinzuf&uuml;gen</em></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="150"><em><strong>Bezeichnung</strong></em></td>
      <td width="124"><em><strong>Materialnummer-Vorgabezeit [s]</strong></em></td>
      <td width="147"><em><strong>GUT Teile - Wochentag</strong></em></td>
      <td width="168"><strong><em><strong>R&uuml;ckmeldung St&uuml;ck Ausschu&szlig;</strong></em></strong></td>
      <td width="141">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap"><input name="bezeichnung" type="text" id="bezeichnung"></td>
      <td nowrap="nowrap"> <input name="nummer" type="text" id="nummer" size="15">
        <input name="zeit" type="text" id="zeit" size="10"></td>
      <td nowrap="nowrap"><input name="stueck" type="text" id="stueck3" size="10">
        <input name="paletten" type="text" id="stueck4" size="10"></td>
      <td nowrap="nowrap"><input name="avis" type="text" id="avis" value="0" size="10">
        <input name="palettenL" type="text" id="palettenL" value="0" size="10"> </td>
      <td nowrap="nowrap"> <div align="right">
          <input name="htyp" type="hidden" id="htyp" value="3">
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
