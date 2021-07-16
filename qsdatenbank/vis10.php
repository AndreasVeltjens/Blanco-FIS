<?php require_once('Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "vis10";
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


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen WHERE kennzahlen.bearbeiter='$url_user' ORDER BY kennzahlen.gruppe, kennzahlen.name";
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

if (isset($HTTP_GET_VARS['totalRows_rst2'])) {
  $totalRows_rst2 = $HTTP_GET_VARS['totalRows_rst2'];
} else {
  $all_rst2 = mysql_query($query_rst2);
  $totalRows_rst2 = mysql_num_rows($all_rst2);
}
$totalPages_rst2 = ceil($totalRows_rst2/$maxRows_rst2)-1;




$queryString_rst2 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst2") == false && 
        stristr($param, "totalRows_rst2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst2 = "&" . implode("&", $newParams);
  }
}
$queryString_rst2 = sprintf("&totalRows_rst2=%d%s", $totalRows_rst2, $queryString_rst2);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}
if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "vis12.php?url_user=".$row_rst1['id']."&url_id_kz=".$HTTP_POST_VARS["hurl_id_kz"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["new"])) ) {
$updateGoTo = "vis11.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["diagramm"])) ) {
$updateGoTo = "vis2.php?url_user=".$row_rst1['id']."&url_id_kz=".$HTTP_POST_VARS["hurl_id_kz"]."&goback=vis1";
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

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
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <br> </td>
    </tr>
    <tr> 
      <td width="145">Aktionen W&auml;hlen:</td>
      <td width="234"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="351"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td><img src="picture/voip.gif" width="52" height="36"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td><div align="right"><font size="4">Benutzersicht</font></div></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
 
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
Anzeige von <?php echo ($startRow_rst2 + 1) ?>bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?>
<table width="1048" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="42"><strong>Status</strong></td>
    <td width="115"><strong>Name</strong></td>
    <td width="121">&nbsp;</td>
    <td width="121"><div align="right"><strong>Beschreibung</strong></div></td>
    <td width="73"><div align="right"><strong>Werk</strong></div></td>
    <td width="96"><div align="right"><strong>x-Achse</strong></div></td>
    <td width="112"><div align="right"><strong>y-Achse</strong></div></td>
    <td width="103"><div align="center">Pflege</div></td>
    <td width="27"><strong>lokz</strong></td>
    <td width="75">&nbsp;</td>
    <td width="151">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <?php   if ($row_rst2['gruppe']!=$gruppe){?>
    <tr > 
      <td colspan="9" nowrap="nowrap"    bgcolor="#CCCCCC"><em><img src="picture/s_tbl.png" width="16" height="16"><?php echo $row_rst2['gruppe']; ?></em></td>
      <td    bgcolor="#CCCCCC" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#CCCCCC" nowrap="nowrap">&nbsp;</td>
    </tr>
    <?php 	}?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
        <img src="picture/st1.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        <?php } // Show if recordset not empty ?>
        </font><font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
        <img src="picture/st3.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        <?php } // Show if recordset empty ?>
        <a href="vis2.php?url_user=<?php echo $row_rst1['id'];?>&url_id_kz=<?php echo $row_rst2['id_kz']; ?>&goback=vis1" target="_blank"><img src="picture/column-chart.gif" alt="Diagramm anzeigen" width="16" height="16" border="0"></a> 
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="-7">&nbsp; </font><?php echo $row_rst2['name']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/b_edit.png" width="16" height="16">
        <input name="edit" type="submit" id="edit" value="Bearbeiten"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"> <?php echo nl2br($row_rst2['beschreibung']); ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><?php echo $row_rst2['werk']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="left"><?php echo $row_rst2['xachse']; ?><font size="-7" face="Arial, Helvetica, sans-serif"> </font> 
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><?php echo $row_rst2['yachse']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst2['bearbeiter']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="-7"> 
        <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp; </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_kz" type="hidden" id="hurl_id_kz" value="<?php echo $row_rst2['id_kz']; ?>">
        </div></td>
    </tr>
  </form>
  <?php 
  $gruppe=$row_rst2['gruppe'];  
  } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<br>


<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
  Anzeige von <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?></p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Visualisierungen angelegt.</p>
<?php } // Show if recordset not empty ?>

<p>Legende: </p>
<p><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/st1.gif" alt="Pr&uuml;fstatus" width="15" height="15"></font> 
  Visualisierung aktiv.<br>
  <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/st3.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
  </font> Visualisierung &uuml;berf&auml;llig. Sofort bearbeiten.<br>
  <img src="picture/b_tipp.png">Achtung Parameter / Istwert ausserhalb der zul&auml;ssigen 
  Toleranz.</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);


?>
</p>

  <?php include("footer.tpl.php"); ?>
