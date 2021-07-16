<p><em><strong><img src="picture/s_process.png" width="16" height="16"> &Uuml;bersicht 
  Workflow</strong></em></p>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="212">angelegt von </td>
    <td width="266"> 
      <?php
	  
	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuserst = "SELECT * FROM `user`";
$rstuserst = mysql_query($query_rstuserst, $qsdatenbank) or die(mysql_error());
$row_rstuserst = mysql_fetch_assoc($rstuserst);
$totalRows_rstuserst = mysql_num_rows($rstuserst);
	  
do {  
?>
      <?php if (!(strcmp($row_rstuserst['id'], $row_rst4['fmuser']))) {echo $row_rstuserst['name'];}?> 
      <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
    </td>
    <td width="252">FD <?php echo $row_rst4['fmfd']; ?></td>
  </tr>
  <tr> 
    <td>Fehleraufnahme</td>
    <td> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_rstuserst['id'], $row_rst4['fm1user']))) {echo $row_rstuserst['name'];}?> 
      <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
    </td>
    <td><?php echo $row_rst4['fm1datum']; ?></td>
  </tr>
  <tr> 
    <td>Kostenkl&auml;rung</td>
    <td> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_rstuserst['id'], $row_rst4['fm2user']))) {echo $row_rstuserst['name'];}?> 
      <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
    </td>
    <td><?php echo $row_rst4['fm2datum']; ?></td>
  </tr>
  <tr> 
    <td>Reparaturfreigabe</td>
    <td> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_rstuserst['id'], $row_rst4['fm3user']))) {echo $row_rstuserst['name'];}?> 
      <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
    </td>
    <td><?php echo $row_rst4['fm3datum']; ?></td>
  </tr>
  <tr> 
    <td>VDE-Pr&uuml;fung</td>
    <td> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_rstuserst['id'], $row_rst4['fm4user']))) {echo $row_rstuserst['name'];}?> 
      <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
    </td>
    <td><?php echo $row_rst4['fm4datum']; ?></td>
  </tr>
  <tr> 
    <td>Kommissionierung</td>
    <td> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_rstuserst['id'], $row_rst4['fm5user']))) {echo $row_rstuserst['name'];}?> 
      <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
    </td>
    <td><?php echo $row_rst4['fm5datum']; ?></td>
  </tr>
  <tr> 
    <td>WA</td>
    <td> 
      <?php
do {  
?>
      <?php if (!(strcmp($row_rstuserst['id'], $row_rst4['fm6user']))) {echo $row_rstuserst['name'];}?> 
      <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
    </td>
    <td><?php echo $row_rst4['fm6datum']; ?></td>
  </tr>
  <tr>
    <td>Zahlungseingang</td>
    <td>
      <?php
do {  
?>
      <?php if (!(strcmp($row_rstuserst['id'], $row_rst4['fm8user']))) {echo $row_rstuserst['name'];}?>
      <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
    </td>
    <td><?php echo $row_rst4['fm8datum']; ?></td>
  </tr>
  <tr> 
    <td>zuletzt ge&auml;ndert:</td>
    <td>&nbsp;</td>
    <td><?php echo $row_rst4['fmdatum']; ?></td>
  </tr>
  <tr> 
    <td>SAP-FAUF-PCNA</td>
    <td><?php echo $row_rst4['sappcna']; ?></td>
    <td>SAP-SD: <?php echo $row_rst4['sapsd']; ?></td>
  </tr>
  <tr> 
    <td>SAP-Abrechnungsvorschrift</td>
    <td><?php echo $row_rst4['sapkst']; ?></td>
    <td>R&Uuml;CK-Status: <?php echo $row_rst4['fm7']; ?></td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp; 
      <?php mysql_free_result($rstuserst); ?>
    </td>
  </tr>
</table>
