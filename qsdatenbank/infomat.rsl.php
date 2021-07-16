<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php 
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst10 = "SELECT * FROM `komponenten_asm_vorlagen`, komponenten_link_asml WHERE komponenten_link_asml.id_asml=komponenten_asm_vorlagen.id_asml and komponenten_link_asml.artikelid='$HTTP_POST_VARS[typ]' and komponenten_link_asml.lokz=0 and komponenten_link_asml.abversion <='$HTTP_POST_VARS[version]' and komponenten_link_asml.bisversion>='$HTTP_POST_VARS[version]'";
$rst10 = mysql_query($query_rst10, $qsdatenbank) or die(mysql_error());
$row_rst10 = mysql_fetch_assoc($rst10);
$totalRows_rst10 = mysql_num_rows($rst10);


?>


<table width="300" border="0" cellspacing="1" cellpadding="0">
  <tr> 
    <td colspan="2" ><font size="1"><img src="picture/folder_open.gif" width="16" height="16"> 
      Ersatzteile Kundenservice</font></td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td width="36" nowrap="nowrap"  bgcolor="#EEEEEE" ><font size="1"><a href="<?php echo $editFormAction; ?>&url_detail=<?php echo $row_rst10['id_asml']; ?>"><img src="picture/bssctoc1.gif" width="32" height="16" border="0"></a> 
      </font> 
    <td><font size="1"><strong><?php echo $row_rst10['gruppe']; ?> - <?php echo $row_rst10['name']; ?></strong> 
      <input name="hurl_id_asml" type="hidden" id="hurl_id_asml2" value="<?php echo $row_rst10['id_asml']; ?>">
      <input name="idfm" type="hidden" id="idfm" value="<?php echo $row_rst4['fmid']; ?>">
      <input name="fmartikelid" type="hidden" id="fmartikelid" value="<?php echo $row_rst4['fmartikelid']; ?>">
      <img src="picture/btnHelp.gif" alt="<?php echo $row_rst10['repbeschreibung'];?>" width="16" height="16"> 
      </font> <div align="right"> </div></td>
  </tr>
  <?php 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM artikeldaten, komponenten_link_artikeldaten WHERE komponenten_link_artikeldaten.lokz=0 AND (komponenten_link_artikeldaten.giltnur='$HTTP_POST_VARS[typ]' or komponenten_link_artikeldaten.giltnur='0') AND komponenten_link_artikeldaten.id_asml='$row_rst10[id_asml]' AND artikeldaten.artikelid=komponenten_link_artikeldaten.artikelid ";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);
	
	
	if ($totalRows_rst8>0){
	?>
  <?php do { ?>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td nowrap="nowrap"  bgcolor="#EEEEEE" ><font size="1">&nbsp;</font> 
      <font size="1"><img src="picture/1.gif" width="20" height="10"></font> <font size="1"><img src="picture/iconCkoutBlue_16x16.gif" alt="Hinweis: <?php echo $row_rst8['zusatztxt']?>" width="16" height="16"></font>
    <td><font size="1"> 
      <?php echo $row_rst8['Nummer']; ?> - <?php echo $row_rst8['Beschreibung']; ?> - <?php echo round($row_rst8['Einzelpreis'],2); ?> &euro; <img src="picture/btnHelp.gif" alt="<?php echo $row_rst8['zusatztxt'];?>" width="16" height="16"></font></td>
  </tr><?php } while ($row_rst8 = mysql_fetch_assoc($rst8)); ?>
  <?php }else { ?>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');">
    <td nowrap="nowrap"  bgcolor="#EEEEEE" >&nbsp;
    <td><font color="#FF0000">Reparatur 
      ist nur im Werk m&ouml;glich</font></td>
  </tr>
  
  <?php } /* ende von rst8 */?>
  <?php } while ($row_rst10 = mysql_fetch_assoc($rst10)); ?>
</table>
<?php
mysql_free_result($rst10);
?>
