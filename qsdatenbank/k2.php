<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "k2";
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
$query_rst2 = "SELECT * FROM ablage";
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT ablage.id_ablage, ablage.beschreibung FROM ablage";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

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
$updateGoTo = "k1.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w?hlen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "k22.php?url_user=".$row_rst1['id']."&url_id_ablage=".$HTTP_POST_VARS["hurl_id_ablage"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w?hlen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["new"])) ) {
$updateGoTo = "k21.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w?hlen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="728" border="0" cellspacing="0" cellpadding="0">
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
      <td width="141">Aktionen W&auml;hlen:</td>
      <td width="135"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="370"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="new" type="submit" id="new" value="neue Ablage anlegen">
        </div></td>
    </tr>
    <tr> 
      <td><img src="picture/rechnerschutz.gif" width="52" height="36"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="725" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="84"><strong>ID</strong></td>
    <td width="209"><strong>Name</strong></td>
    <td width="141"><strong>Datum</strong></td>
    <td width="70"><strong>LOKZ</strong></td>
    <td width="134"><strong>Wer</strong>k</td>
    <td width="140">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;<?php echo $row_rst2['id_ablage']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; <?php echo $row_rst2['beschreibung']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo $row_rst2['datum']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <font size="1"> 
        <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo $row_rst2['werk']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <font size="1" face="Arial, Helvetica, sans-serif"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_ablage" type="hidden" id="hurl_id_ablage" value="<?php echo $row_rst2['id_ablage']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
          </font></div></td>
    </tr>
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  
</table>
<?php } // Show if recordset not empty ?>

<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
</p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Retouren angelegt.</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rsttyp);
?>
</p>

  <?php include("footer.tpl.php"); ?>
