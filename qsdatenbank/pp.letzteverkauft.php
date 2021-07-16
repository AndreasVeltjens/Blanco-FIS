<?php 

$maxRows_letztegereate = 7;
$pageNum_letztegereate = 0;
if (isset($HTTP_GET_VARS['pageNum_letztegereate'])) {
  $pageNum_letztegereate = $HTTP_GET_VARS['pageNum_letztegereate'];
}
$startRow_letztegereate = $pageNum_letztegereate * $maxRows_letztegereate;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztegereate = "SELECT fid, fsn, Nummer, Bezeichnung, freigabedatum, freigabeuser FROM fertigungsmeldungen, artikeldaten WHERE fertigungsmeldungen.fartikelid=artikeldaten.artikelid AND fertigungsmeldungen.status =2 AND fertigungsmeldungen.fid >'300000' AND fertigungsmeldungen.verkauftam='0000-00-00 00:00:00' AND fertigungsmeldungen.versendetam='0000-00-00 00:00:00' ORDER BY fertigungsmeldungen.erstelltdatum desc ";
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
$query_zuletztfertig = "SELECT fid, fsn, Nummer, Bezeichnung, verkauftam, verkauftuser FROM fertigungsmeldungen, artikeldaten WHERE fertigungsmeldungen.fartikelid=artikeldaten.artikelid AND fertigungsmeldungen.status =2 AND fertigungsmeldungen.fid >'300000' AND fertigungsmeldungen.verkauftam<>'0000-00-00 00:00:00' AND fertigungsmeldungen.versendetam='0000-00-00 00:00:00' ORDER BY fertigungsmeldungen.verkauftam desc limit 0,7";
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
$queryString_rst_material = sprintf("&totalRows_rst_material=%d%s", $totalRows_rst_material, $queryString_rst_material);?>

<p><img src="picture/arrowClose.gif" width="8" height="9"> Ger&auml;te<br>
  <img src="picture/b_edit.png" width="16" height="16"> zum Verkauf verfügbar<br>
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
    <td>
      <img src="picture/memory.gif"> <?php echo $row_letztegereate['Nummer']; ?>- <?php echo utf8_decode($row_letztegereate['Bezeichnung']); ?></td>
    <td><?php echo $row_letztegereate['freigabedatum']; ?></td>
    <td><font size="1"> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_wartungsuser['id'], $row_letztegereate['fuser']))) {
		 ?>
      <img src="picture/usergruppe_16x16.png" width="16" height="16"> 
      <?php
			echo  utf8_decode($row_wartungsuser['name']);}?>
      <?php
} while ($row_wartungsuser = mysql_fetch_assoc($wartungsuser));
  $rows = mysql_num_rows($wartungsuser);
  if($rows > 0) {
      mysql_data_seek($wartungsuser, 0);
	  $row_wartungsuser = mysql_fetch_assoc($wartungsuser);
  }
?>
      </font></td>
  </tr>
  <?php } while ($row_letztegereate = mysql_fetch_assoc($letztegereate)); ?>
</table>
<?php } // Show if recordset not empty ?>

<p><img src="picture/iconReadOnly_16x16.gif" width="16" height="16"> zuletzt verkauft</p>
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
    <td> <img src="picture/memory.gif"> <?php echo $row_zuletztfertig['Nummer']; ?>- <?php echo $row_zuletztfertig['Bezeichnung']; ?></td>
    <td><?php echo $row_zuletztfertig['verkauftam']; ?></td>
    <td><font size="1"> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_wartungsuser['id'], $row_zuletztfertig['verkauftuser']))) {
		 ?>
      <img src="picture/usergruppe_16x16.png" width="16" height="16"> 
      <?php
			echo  utf8_decode($row_wartungsuser['name']);}?>
      <?php
} while ($row_wartungsuser = mysql_fetch_assoc($wartungsuser));
  $rows = mysql_num_rows($wartungsuser);
  if($rows > 0) {
      mysql_data_seek($wartungsuser, 0);
	  $row_wartungsuser = mysql_fetch_assoc($wartungsuser);
  }
?>
      </font></td>
  </tr>
  <?php } while ($row_zuletztfertig = mysql_fetch_assoc($zuletztfertig)); ?>
</table>
<?php } // Show if recordset not empty 
mysql_free_result($zuletztfertig);

mysql_free_result($letztegereate);

if($rows > 0) {
      mysql_data_seek($wartungsuser, 0);
	  $row_wartungsuser = mysql_fetch_assoc($wartungsuser);
  }
?>
