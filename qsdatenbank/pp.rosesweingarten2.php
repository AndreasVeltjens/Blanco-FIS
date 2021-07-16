<?php 

$maxRows_letztegereate = 100;
$pageNum_letztegereate = 0;
if (isset($HTTP_GET_VARS['pageNum_letztegereate'])) {
  $pageNum_letztegereate = $HTTP_GET_VARS['pageNum_letztegereate'];
}
$startRow_letztegereate = $pageNum_letztegereate * $maxRows_letztegereate;

$anzahltage= $HTTP_POST_VARS['anzahltage'];
$arbeitstage= $HTTP_POST_VARS['arbeitstage'];

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztegereate = "SELECT farbeitsplatz, artikeldaten.artikelid, Nummer, Bezeichnung, 
artikelversionen.materialnummer, artikelversionen.kundenmaterialnummer, fertigungsmeldungen.version ,count( fertigungsmeldungen.fid ) AS anzahl
FROM artikeldaten, fertigungsmeldungen RIGHT JOIN artikelversionen  ON (artikelversionen.artikelid = fertigungsmeldungen.fartikelid AND artikelversionen.version = fertigungsmeldungen.version)
WHERE fertigungsmeldungen.fartikelid = artikeldaten.artikelid

AND $anzahltage <= fertigungsmeldungen.freigabedatum
AND $anzahltage <= fertigungsmeldungen.verkauftam 
AND $arbeitstage > fertigungsmeldungen.freigabedatum
AND $arbeitstage > fertigungsmeldungen.verkauftam 
GROUP BY farbeitsplatz,artikeldaten.artikelid, fertigungsmeldungen.version";
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
$queryString_rst_material = sprintf("&totalRows_rst_material=%d%s", $totalRows_rst_material, $queryString_rst_material);?>

<p><img src="picture/arrowClose.gif" width="8" height="9"> Ger&auml;te<br>
  <img src="picture/b_edit.png" width="16" height="16"> Verkaufte Geräte im o.g. Zeitraum<br>
<?php if ($totalRows_letztegereate > 0) { // Show if recordset not empty ?>
<table width="980" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="40">Anzahl</td>
    <td width="60">Material</td>
    <td width="60">K-Material-Nummer</td>
    <td width="80">Version</td>
    <td width="500">Bezeichnung</td>
    <td width="100">Arbeitsplatz</td>
  </tr>
  <?php $arbeitsplatz=0;?>
  <?php do { ?>
  <?php if ($arbeitsplatz<>$row_letztegereate['farbeitsplatz']){?>
  <tr bgcolor="#FFFFCC"> 
    <td height="20">Arbeitplatz: </td>
    <td colspan="4"><?php echo $row_letztegereate['farbeitsplatz']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <?php }?>
  <tr> 
    <td height="20"><img src="picture/arrowClose.gif" width="8" height="9"><?php echo $row_letztegereate['anzahl']; ?></td>
    <td> <img src="picture/memory.gif"> <?php echo $row_letztegereate['materialnummer']; ?></td>
    <td><?php echo $row_letztegereate['kundenmaterialnummer']; ?></td>
    <td><?php echo $row_letztegereate['version']; ?></td>
    <td><?php echo $row_letztegereate['Bezeichnung']; ?></td>
    <td><?php echo $row_letztegereate['farbeitsplatz']; ?></td>
  </tr>
  <?php $arbeitsplatz=$row_letztegereate['farbeitsplatz'];?>
  <?php } while ($row_letztegereate = mysql_fetch_assoc($letztegereate)); ?>
</table>
<?php } // Show if recordset not empty 

mysql_free_result($letztegereate);
?>