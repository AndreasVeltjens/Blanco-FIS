<?php 

$maxRows_letztegereate = 14;
$pageNum_letztegereate = 0;
if (isset($HTTP_GET_VARS['pageNum_letztegereate'])) {
  $pageNum_letztegereate = $HTTP_GET_VARS['pageNum_letztegereate'];
}
$startRow_letztegereate = $pageNum_letztegereate * $maxRows_letztegereate;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztegereate = "SELECT fid, fsn, Nummer, Bezeichnung, versendetam, versendetuser FROM  artikeldaten, fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=artikeldaten.artikelid AND fertigungsmeldungen.status =2 AND fertigungsmeldungen.fid >'300000' AND fertigungsmeldungen.verkauftam<>'0000-00-00 00:00:00' AND fertigungsmeldungen.versendetam<>'0000-00-00 00:00:00' ORDER BY fertigungsmeldungen.versendetam desc ";
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

?>
<p><img src="picture/arrowClose.gif" width="8" height="9"> Ger&auml;te<br>
  <img src="picture/b_edit.png" width="16" height="16"> zum Versand vorbereitet.<br>
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
    <td><?php echo $row_letztegereate['versendetam']; ?></td>
    <td><font size="1"> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_wartungsuser['id'], $row_letztegereate['versendetuser']))) {
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

<?php mysql_free_result($letztegereate);?>