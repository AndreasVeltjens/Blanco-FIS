        <?php 
if ($HTTP_POST_VARS[seriennummer]==""){$HTTP_POST_VARS[seriennummer]=0;}
if ($HTTP_POST_VARS[typ]==""){$HTTP_POST_VARS[typ]=0;}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.version,  fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes, fertigungsmeldungen.fsn1, fertigungsmeldungen.fsn2, fertigungsmeldungen.fsn3, fertigungsmeldungen.fsn4 FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$HTTP_POST_VARS[typ]' and fertigungsmeldungen.fsn='$HTTP_POST_VARS[seriennummer]' ORDER BY fertigungsmeldungen.fdatum asc";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);
?>


<?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000" size="1">keine m&ouml;glichen Fertigungsdaten zur Auswahl gefunden.</font></p>
<?php } // Show if recordset empty ?>
 
<?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>

<table width="305" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="100"><font size="1"><strong>M&ouml;gliche Fertigungsdaten:</strong></font></td>
    <td width="50"><font size="1">Status </font></td>
    <td width="50"><font size="1">Version </font></td>
    <td width="100"><font size="1">Bauteile</font></td>
  </tr>
  <?php do { ?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"> 
      <input name="ubernehmen" type="submit" id="ubernehmen" value="y">
      <?php echo $row_rst4['fdatum']; ?> <br>
      <a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst4['fartikelid']; ?>&url_fmid=<?php echo $row_rst5['fmid']; ?>" target="_blank"><img src="picture/primary.gif" alt="<?php echo wordwrap(nl2br($row_rst4['fnotes']),40,"<br>",255); ?>" width="21" height="17" border="0" align="absmiddle"></a><a href="la14.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst4['fartikelid']; ?>&url_fid=<?php echo $row_rst4['fid']; ?>" target="_blank">Fertigungsdaten</a> 
      <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst4['fdatum']; ?>">
      <input name="name" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>">
      <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>">
      <input name="debitor" type="hidden" id="debitor2" value="<?php echo $HTTP_POST_VARS['debitor']?>">
      <br>
      <img src="picture/iconchance_16x16.gif" width="16" height="16"> <?php
do {  
?>
      <?php if (!(strcmp($row_rstuser['id'], $row_rst4['fuser']))) {echo $row_rstuser['name'];}?>
      <?php
} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  $rows = mysql_num_rows($rstuser);
  if($rows > 0) {
      mysql_data_seek($rstuser, 0);
	  $row_rst9 = mysql_fetch_assoc($rstuser);
  }
?>
      </font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><img src="picture/<?php if (!(strcmp($row_rst4['ffrei'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" alt="Ger&auml;tepr&uuml;fstatus" width="15" height="15"> 
      - <img src="picture/<?php if (!(strcmp($row_rst4['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" alt="Sonderfreigabe" width="15" height="15"> 
      </font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo $row_rst4['version']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">Heizung 
      <?php echo $row_rst4['fsn1']; ?>-<br>
      Regler <?php echo $row_rst4['fsn2']; ?>-<br>
      L&uuml;fter <?php echo $row_rst4['fsn3']; ?>-<br>
      Sonstiges <?php echo $row_rst4['fsn4']; ?></font></td>
  </tr>
  <?php if ($HTTP_POST_VARS['version']==""){$HTTP_POST_VARS['version']=$row_rst4['version'];} ?>
  <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
</table>

<font size="1">insgesamt <?php echo $totalRows_rst4 ?> Fertigungsmeldungen gefunden.</font> 
<?php } // Show if recordset not empty ?>

