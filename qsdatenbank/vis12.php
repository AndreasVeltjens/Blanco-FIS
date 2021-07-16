<?php require_once('Connections/qsdatenbank.php'); ?>
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
  $la = "vis12";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "vis10.php?url_user=".$row_rst1['id'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$GoTo = "vis14.php?url_user=".$row_rst1['id']."&url_id_kz=".$HTTP_POST_VARS['hurl_id_kz']."&url_id_kzd=".$HTTP_POST_VARS['hurl_id_kzd'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}


$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["add"] ))) {
  $insertSQL = sprintf("INSERT INTO kennzahlen_daten (id_kz, wertx, wert1y, wert2y, wert3y,wert4y) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_kz'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['xachse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['yachse1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['yachse2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['yachse3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['yachse4'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["save"] ))) {
  $updateSQL = sprintf("UPDATE kennzahlen SET name=%s, beschreibung=%s, lokz=%s, werk=%s, xachse=%s, yachse=%s, yachse2=%s,yachse3=%s,yachse4=%s, gruppe=%s, anzeige=%s, bearbeiter=%s, diagrammtyp=%s  WHERE id_kz=%s",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['xachse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['yachse'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['yachse2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['yachse3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['yachse4'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['gruppe'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anzeige'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['bearbeiter'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['diagrammtyp'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_kz'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen WHERE kennzahlen.id_kz='$url_id_kz'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

$maxRows_rst3 = 12;
$pageNum_rst3 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst3'])) {
  $pageNum_rst3 = $HTTP_GET_VARS['pageNum_rst3'];
}
$startRow_rst3 = $pageNum_rst3 * $maxRows_rst3;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM kennzahlen_daten WHERE kennzahlen_daten.id_kz='$url_id_kz' ORDER BY kennzahlen_daten.wertx desc";
$query_limit_rst3 = sprintf("%s LIMIT %d, %d", $query_rst3, $startRow_rst3, $maxRows_rst3);
$rst3 = mysql_query($query_limit_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);

if (isset($HTTP_GET_VARS['totalRows_rst3'])) {
  $totalRows_rst3 = $HTTP_GET_VARS['totalRows_rst3'];
} else {
  $all_rst3 = mysql_query($query_rst3);
  $totalRows_rst3 = mysql_num_rows($all_rst3);
}
$totalPages_rst3 = ceil($totalRows_rst3/$maxRows_rst3)-1;

$queryString_rst3 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst3") == false && 
        stristr($param, "totalRows_rst3") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst3 = "&" . implode("&", $newParams);
  }
}
$queryString_rst3 = sprintf("&totalRows_rst3=%d%s", $totalRows_rst3, $queryString_rst3);



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
      <td width="139">Aktionen W&auml;hlen:</td>
      <td width="349"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="242"> <div align="right"><a href="vis2.php?url_user=<?php echo $row_rst1['id']?>&url_id_kz=<?php echo $row_rst2['id_kz']; ?>&goback=vis1" target="_blank"><img src="picture/column-chart.gif" alt="Diagramm anzeigen" width="16" height="16" border="0"></a> 
          <img src="picture/save.gif" width="18" height="18"> 
          <input name="save" type="submit" id="save" value="speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <input name="hurl_id_kz" type="hidden" id="hurl_id_kz" value="<?php echo $row_rst2['id_kz']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Beschreibung</td>
      <td><strong> 
        <textarea name="beschreibung" cols="50" rows="4" id="beschreibung"><?php echo $row_rst2['beschreibung']; ?></textarea>
        </strong></td>
      <td rowspan="9"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <img src="<?php echo $row_rst2['diagrammtyp']; ?>.php?url_id_kz=<?php echo $url_id_kz ?>" alt="Vorschau" width="300" height="200"></div></td>
    </tr>
    <tr> 
      <td height="24">&Uuml;berschrift:</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_rst2['name']; ?>" size="50" maxlength="255"></td>
    </tr>
    <tr> 
      <td>x-Bezeichnung</td>
      <td><input name="xachse" type="text" id="xachse" value="<?php echo $row_rst2['xachse']; ?>" size="50" maxlength="255"></td>
    </tr>
    <tr> 
      <td>y-Bezeichnung</td>
      <td><input name="yachse" type="text" id="yachse" value="<?php echo $row_rst2['yachse']; ?>" size="50" maxlength="255"></td>
    </tr>
    <tr> 
      <td>y2Bezeichnung</td>
      <td><input name="yachse2" type="text" id="yachse2" value="<?php echo $row_rst2['yachse2']; ?>" size="50" maxlength="255"></td>
    </tr>
    <tr> 
      <td>y3-Bezeichnung</td>
      <td><input name="yachse3" type="text" id="yachse3" value="<?php echo $row_rst2['yachse3']; ?>" size="50" maxlength="255"></td>
    </tr>
    <tr>
      <td>y4-Bezeichnung</td>
      <td><input name="yachse4" type="text" id="yachse4" value="<?php echo $row_rst2['yachse4']; ?>" size="50" maxlength="255"></td>
    </tr>
    <tr> 
      <td>Werk</td>
      <td><input name="werk" type="text" id="werk" value="<?php echo $row_rst2['werk']; ?>" size="10" maxlength="255"></td>
    </tr>
    <tr> 
      <td>Anzeigeskalierung</td>
      <td><input name="anzeige" type="text" id="anzeige" value="<?php echo $row_rst2['anzeige']; ?>" size="10" maxlength="255">
        L&auml;nge der HTML-Balken</td>
    </tr>
    <tr> 
      <td>Diagramtyp</td>
      <td colspan="2"> 
        <select name="diagrammtyp" id="diagrammtyp">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>keine 
          Visualisierung ausgew&auml;hlt</option>
          <option value="vis2.tmp1" <?php if (!(strcmp("vis2.tmp1", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>1-Dia 
          x-y + Zielvorgabe</option>
          <option value="vis2.tmp2" <?php if (!(strcmp("vis2.tmp2", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>2-Dia 
          x-y1,y2 Balkendiagramm</option>
          <option value="vis2.tmp3" <?php if (!(strcmp("vis2.tmp3", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>3-Dia 
          x-y + Zielvorgabe +12 Monate R&uuml;ckblick bei 12 Werten</option>
          <option value="vis2.tmp4" <?php if (!(strcmp("vis2.tmp4", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>4-Dia 
          x-y1,y2 + 12 Monate R&uuml;ckblick</option>
          <option value="vis2.tmp5" <?php if (!(strcmp("vis2.tmp5", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>5-Dia 
          x-y1/y2 Quotient-Balkendiagram +12 Monate R&uuml;ckbilck</option>
          <option value="vis2.tmp6" <?php if (!(strcmp("vis2.tmp6", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>6-Dia 
          x-y1/y2 Quotient-kummuliert Balken+ 12 Monate</option>
          <option value="vis2.tmp7" <?php if (!(strcmp("vis2.tmp7", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>7-Dia 
          x-y1+Zielvorgabe Zoom 85-105</option>
          <option value="vis2.tmp8" <?php if (!(strcmp("vis2.tmp8", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>8-Dia 
          x-y1+y2+y3+y4 Cashflow</option>
          <option value="vis2.tmp9" <?php if (!(strcmp("vis2.tmp9", $row_rst2['diagrammtyp']))) {echo "SELECTED";} ?>>9-Dia 
          none</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td>ID</td>
      <td><?php echo $row_rst2['id_kz']; ?> automatisch vergeben</td>
      <td><img src="picture/b_drop.png" width="16" height="16"> L&ouml;schkennzeichnung 
        <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz2" value="1"></td>
    </tr>
    <tr> 
      <td height="22">Gruppe</td>
      <td><input name="gruppe" type="text" id="gruppe" value="<?php echo $row_rst2['gruppe']; ?>"></td>
      <td><img src="picture/iconchance_16x16.gif" width="16" height="16"> Bearbeiter 
        <input name="bearbeiter" type="text" id="bearbeiter2" value="<?php echo $row_rst2['bearbeiter']; ?>"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>

<p><strong><em> <img src="picture/b_insrow.png" width="16" height="16"> Neuen 
  Datensatz hinzuf&uuml;gen</em></strong></p>
  <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="255" height="25">x Achse - <?php echo $row_rst2['xachse']; ?></td>
      <td width="326"><input name="xachse" type="text" id="xachse"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'xachse\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td width="149"> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_id_kz" type="hidden" id="hurl_id_kz" value="<?php echo $row_rst2['id_kz']; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>1. Wert - <?php echo $row_rst2['yachse']; ?></td>
      <td><input name="yachse1" type="text" id="yachse1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>2. Wert - <?php echo $row_rst2['yachse2']; ?></td>
      <td><input name="yachse2" type="text" id="yachse2"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>3. Wert - <?php echo $row_rst2['yachse3']; ?></td>
      <td><input name="yachse3" type="text" id="yachse3"></td>
      <td> <div align="right"></div></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td>4. Wert - <?php echo $row_rst2['yachse4']; ?></td>
      <td><input name="yachse4" type="text" id="yachse4"></td>
      <td><div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="add" type="submit" id="add" value="anlegen">
        </div></td>
    </tr>
  </table>
  <p>
<input type="hidden" name="MM_insert" value="form2">
    <br>
  </p>
</form>
<strong><em><img src="picture/s_tbl.png" width="16" height="16"> Liste der gespeicherten 
Werte</em></strong> 

  <br>
  <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>

<?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, 0, $queryString_rst3); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, max(0, $pageNum_rst3 - 1), $queryString_rst3); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, min($totalPages_rst3, $pageNum_rst3 + 1), $queryString_rst3); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, $totalPages_rst3, $queryString_rst3); ?>">Last</a> 
Anzeige von <?php echo ($startRow_rst3 + 1) ?> bis <?php echo min($startRow_rst3 + $maxRows_rst3, $totalRows_rst3) ?> 
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="122"><strong>Status</strong></td>
    <td width="87"><strong>ID</strong></td>
    <td width="83"><div align="left"><?php echo $row_rst2['xachse']; ?></div></td>
    <td width="84"><div align="left"><?php echo $row_rst2['yachse']; ?></div></td>
    <td width="91"><div align="right"><?php echo $row_rst2['yachse2']; ?></div></td>
    <td width="91"><div align="right"><?php echo $row_rst2['yachse3']; ?></div></td>
    <td width="91"><div align="center"><?php echo $row_rst2['yachse4']; ?></div></td>
    <td width="27"><strong>lokz</strong></td>
    <td width="164">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <?php   if ($row_rst3['gruppe']!=$gruppe){?>
    <tr > 
      <td colspan="8" nowrap="nowrap"    bgcolor="#CCCCCC"><em><img src="picture/s_tbl.png" width="16" height="16"><?php echo $row_rst2['gruppe']; ?></em></td>
      <td    bgcolor="#CCCCCC" nowrap="nowrap">&nbsp;</td>
    </tr>
    <?php 	}?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if (!(strcmp($row_rst2['lokz'],0))) { // Show if recordset not empty ?>
        <img src="picture/st1.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        <?php }  ?>
        
        <?php if (!(strcmp($row_rst2['lokz'],1))) { // Show if recordset empty ?>
        <img src="picture/st3.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        <?php }  ?>
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="-7">&nbsp; </font> 
        <?php echo $row_rst3['id_kzd']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"></div>
        <?php echo $row_rst3['wertx']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"></div>
        <?php echo $row_rst3['wert1y']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"><?php echo $row_rst3['wert2y']; ?><font size="-7" face="Arial, Helvetica, sans-serif"> </font> 
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst3['wert3y']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst3['wert4y']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="-7"> 
        <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_id_kzd" type="hidden" id="hurl_id_kzd" value="<?php echo $row_rst3['id_kzd']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_kz" type="hidden" id="hurl_id_kz" value="<?php echo $row_rst2['id_kz']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php 
  $gruppe=$row_rst3['gruppe'];  
  } while ($row_rst3 = mysql_fetch_assoc($rst3)); ?>
</table>
<br>
<p>&nbsp;<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, 0, $queryString_rst3); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, max(0, $pageNum_rst3 - 1), $queryString_rst3); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, min($totalPages_rst3, $pageNum_rst3 + 1), $queryString_rst3); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, $totalPages_rst3, $queryString_rst3); ?>">Last</a> 
  Anzeige von <?php echo ($startRow_rst3 + 1) ?> bis <?php echo min($startRow_rst3 + $maxRows_rst3, $totalRows_rst3) ?></p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst3 ?> Datens&auml;tze angelegt.</p>
<?php } // Show if recordset not empty ?>
<p>Legende: </p>
<p><font size="1" face="Arial, Helvetica, sans-serif"></font></p>
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
