<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "vl3";
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
if ((isset($HTTP_POST_VARS['select'])) and ($HTTP_POST_VARS['select']!="0")){
$dokumentgruppe=" AND formatett='".($HTTP_POST_VARS['select'])."' ";
} else {$dokumentgruppe="";}

$suchwhere="WHERE ( nummer like '%".$HTTP_POST_VARS['suchtext']."%' OR name like '%".$HTTP_POST_VARS['suchtext']."%' ) $dokumentgruppe";


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT versandlabel.formatett FROM versandlabel where versandlabel.lokz=0 group BY versandlabel.formatett ORDER BY versandlabel.formatett";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM versandlabel $suchwhere and versandlabel.lokz=0 ORDER BY versandlabel.name";
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
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w?hlen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "vl32.php?url_user=".$row_rst1['id']."&url_id_ett=".$HTTP_POST_VARS["hurl_id_ett"]."&url_suchtext=".$HTTP_POST_VARS["suchtext"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w?hlen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["new"])) ) {
$updateGoTo = "vl31.php?url_user=".$row_rst1['id']."&url_id_fhm=".$HTTP_POST_VARS["hurl_id_fhm"]."&url_suchtext=".$HTTP_POST_VARS["suchtext"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w?hlen. Bitte erneut versuchen.";}


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="4"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="4"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <br> </td>
    </tr>
    <tr> 
      <td width="85" rowspan="3"><img src="picture/versandlabel.jpg" width="82" height="83"></td>
      <td width="105">Aktionen W&auml;hlen:</td>
      <td width="300"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="240"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="new" type="submit" id="new" value="neues Dokument freigeben">
        </div></td>
    </tr>
    <tr> 
      <td>Dokumentgruppe</td>
      <td colspan="2"> <select name="select">
          <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS["select"]))) {echo "SELECTED";} ?>>alle 
          Gruppen anzeigen</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['formatett']?>"<?php if (!(strcmp($row_rst3['formatett'], $HTTP_POST_VARS["select"]))) {echo "SELECTED";} ?>><?php echo $row_rst3['formatett']?></option>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </select></td>
    </tr>
    <tr> 
      <td>Text oder Materialnummerverweis</td>
      <td colspan="2"> <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>" size="50"> 
        <input name="suchen" type="submit" id="suchen" value="Suchen"> </td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
angezeigte Datens&auml;tze <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?>
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="21"><font size="1"><strong>Typ</strong></font></td>
    <td width="51"><strong>ID</strong></td>
    <td width="115"><strong>Bezeichnung</strong></td>
    <td width="113"><strong>Materialnummer</strong></td>
    <td width="122"><strong>Dokumentgruppe</strong></td>
    <td width="68"><strong>ansehen</strong></td>
    <td width="232"><div align="right"><strong>letze &Auml;nderung- bearbeiten</strong></div></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="../documents/<?php echo htmlentities(utf8_decode($row_rst2['link'])); ?>"><img src="picture/iconShipBlue_16x16.gif" alt="Dokument ansehen" width="16" height="16" border="0"></a></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['id_ett']; ?>&nbsp;</font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; <?php echo wordwrap(htmlentities(utf8_decode($row_rst2['name'])),50,"<br>",200); ?> 
        <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;<?php echo htmlentities(utf8_decode($row_rst2['nummer'])); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo htmlentities(utf8_decode($row_rst2['formatett'])); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><font size="1">&nbsp;<a href="../documents/<?php echo htmlentities(utf8_decode($row_rst2['link'])); ?>" target="_blank"><img src="picture/information.gif" alt="Dokument ansehen" border="0"></a></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <font size="1"></font><font size="1"><?php echo ($row_rst2['lastmod']); ?></font><font size="1" face="Arial, Helvetica, sans-serif"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_ett" type="hidden" id="hurl_id_ett" value="<?php echo $row_rst2['id_ett']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> </font><font size="1" face="Arial, Helvetica, sans-serif"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
          </font></div></td>
    </tr>
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<font size="1" face="Arial, Helvetica, sans-serif"> 
<input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
</font> 

<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
  angezeigte Datens&auml;tze <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?></p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Dokumente angelegt.</p>
<p> 
<?php } // Show if recordset not empty ?>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);
mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
