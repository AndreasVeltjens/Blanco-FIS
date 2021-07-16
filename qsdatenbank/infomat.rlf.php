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
      Leistungsbeschreibung mit Baugruppenvorlage f&uuml;r Reparatur im Werk</font></td>
  </tr>
  <?php do { ?>
 
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td width="36" nowrap="nowrap"  bgcolor="#EEEEEE" ><font size="1"><a href="<?php echo $editFormAction; ?>&url_detail=<?php echo $row_rst10['id_asml']; ?>"><img src="picture/bssctoc1.gif" width="32" height="16" border="0"></a> 
        </font>
      
    <td><font size="1"><?php echo $row_rst10['gruppe']; ?> - <?php echo $row_rst10['name']; ?> 
      <input name="hurl_id_asml" type="hidden" id="hurl_id_asml2" value="<?php echo $row_rst10['id_asml']; ?>">
      <input name="idfm" type="hidden" id="idfm" value="<?php echo $row_rst4['fmid']; ?>">
      <input name="fmartikelid" type="hidden" id="fmartikelid" value="<?php echo $row_rst4['fmartikelid']; ?>">
      <img src="picture/btnHelp.gif" alt="<?php echo $row_rst10['repbeschreibung'];?>" width="16" height="16"> </font> 
      <div align="right"> </div></td>
    </tr>
    <?php 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT `komponenten_vorlage`.* , komponenten_asm.anzahl, komponenten_asm.artikelid FROM komponenten_asm, komponenten_vorlage WHERE (((`komponenten_asm`.`id_asml`= '$row_rst10[id_asml]') AND (`komponenten_vorlage`.`id_komp`= `komponenten_asm`.`komp_id`)) AND komponenten_asm.lokz=0 and komponenten_asm.artikelid=0) or (((`komponenten_asm`.`id_asml`= '$row_rst10[id_asml]') AND (`komponenten_vorlage`.`id_komp`= `komponenten_asm`.`komp_id`)) AND komponenten_asm.lokz=0 and komponenten_asm.artikelid='$HTTP_POST_VARS[typ]' )";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);
	
	
	if ($totalRows_rst8>0 && $row_rst10['id_asml']==$url_detail){
	?>
    <?php do { ?>
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      
    <td nowrap="nowrap"  bgcolor="#EEEEEE" ><font size="1">&nbsp;</font> 
      <font size="1"><a href="<?php echo $editFormAction; ?>&url_detail=0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="picture/bssctoc2.gif" width="32" height="16" border="0"></a></font> 
    <td><font size="1"> 
      <?php echo $row_rst8['anzahl']; ?> St&uuml;ck - <?php echo $row_rst8['kompnr']; ?> - <?php echo $row_rst8['kompname']; ?></font></td>
    </tr>
    <?php } while ($row_rst8 = mysql_fetch_assoc($rst8)); ?>
    <?php } /* ende von rst8 */?>
 
  <?php } while ($row_rst10 = mysql_fetch_assoc($rst10)); ?>
</table>
<?php
mysql_free_result($rst10);
?>
