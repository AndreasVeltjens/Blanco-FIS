<?php 
$maxRows_letztegereate = 14;
$pageNum_letztegereate = 0;
if (isset($HTTP_GET_VARS['pageNum_letztegereate'])) {
  $pageNum_letztegereate = $HTTP_GET_VARS['pageNum_letztegereate'];
}
$startRow_letztegereate = $pageNum_letztegereate * $maxRows_letztegereate;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztegereate = "SELECT * FROM bearbeitungsbestand ORDER BY datum ASC ";
$query_limit_letztegereate = sprintf("%s LIMIT %d, %d", $query_letztegereate, $startRow_letztegereate, $maxRows_letztegereate);
$letztegereate = mysql_query($query_limit_letztegereate, $qsdatenbank) or die(mysql_error());
$row_letztegereate = mysql_fetch_assoc($letztegereate);

if (isset($HTTP_GET_VARS['totalRows_letztegereate'])) {
  $totalRows_letztegereate = $HTTP_GET_VARS['totalRows_letztegereate'];
} else {
  $all_letztegereate = mysql_query($query_letztegereate);
  $totalRows_letztegereate = mysql_num_rows($all_letztegereate);
}
$totalPages_letztegereate = ceil($totalRows_letztegereate/$maxRows_letztegereate)-1;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_zuletztfertig = "SELECT * FROM fertiggestelltbestand ORDER BY datum DESC limit 0,10";
$zuletztfertig = mysql_query($query_zuletztfertig, $qsdatenbank) or die(mysql_error());
$row_zuletztfertig = mysql_fetch_assoc($zuletztfertig);
$totalRows_zuletztfertig = mysql_num_rows($zuletztfertig);

$queryString_rst_material = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst_material") == false && 
        stristr($param, "totalRows_rst_material") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst_material = "&" . implode("&", $newParams);
  }
}
$queryString_rst_material = sprintf("&totalRows_rst_material=%d%s", $totalRows_rst_material, $queryString_rst_material);


?><p><img src="picture/arrowClose.gif" width="8" height="9"> letzte Ger&auml;te<br>
  <img src="picture/b_edit.png" width="16" height="16"> in Bearbeitung<br>
<?php if ($totalRows_letztegereate > 0) { // Show if recordset not empty ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="12%">SN</td>
    <td width="38%">Material</td>
    <td width="25%">Datum</td>
    <td width="25%">Mitarbeiter</td>
  </tr>
  <?php do { ?>
  <tr> 
    <td><img src="picture/arrowClose.gif" width="8" height="9"><?php echo $row_letztegereate['fsn']; ?></td>
    <td><img src="picture/memory.gif"> <?php echo $row_letztegereate['nummer']; ?>- <?php echo utf8_decode($row_letztegereate['bezeichnung']); ?></td>
    <td><?php echo $row_letztegereate['datum']; ?></td>
    <td><img src="picture/c_profile.gif" width="16" height="16"><?php echo utf8_decode($row_letztegereate['user']); ?></td>	
  </tr>
  <?php } while ($row_letztegereate = mysql_fetch_assoc($letztegereate)); ?>
</table>
<?php } // Show if recordset not empty ?>

<p><img src="picture/iconReadOnly_16x16.gif" width="16" height="16"> zuletzt fertig</p>
<?php if ($totalRows_zuletztfertig > 0) { // Show if recordset not empty ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="12%">SN</td>
    <td width="38%">Material</td>
    <td width="25%">Datum</td>
    <td width="25%">Mitarbeiter</td>
  </tr>
  <?php do { ?>
  <tr> 
    <td><img src="picture/arrowClose.gif" width="8" height="9"><?php echo $row_zuletztfertig['fsn']; ?></td>
    <td> <img src="picture/memory.gif"> <?php echo $row_zuletztfertig['nummer']; ?>- <?php echo $row_zuletztfertig['bezeichnung']; ?></td>
    <td><?php echo $row_zuletztfertig['datum']; ?></td>
    <td><img src="picture/c_profile.gif" width="16" height="16"><?php echo $row_zuletztfertig['user']; ?></td>
  </tr>
  <?php } while ($row_zuletztfertig = mysql_fetch_assoc($zuletztfertig)); ?>
</table>
<?php } // Show if recordset not empty 
mysql_free_result($zuletztfertig);

mysql_free_result($letztegereate);
?>
