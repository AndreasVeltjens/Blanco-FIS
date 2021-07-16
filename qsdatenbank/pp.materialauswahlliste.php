<?php  if ($artikelid==$row_rst_material['artikelid']){?>
<table width="420" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#00CC00">
    <?php }else{?>
	<table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<?php }?>
    <tr> 
          <td width="120"><a href="<?php echo $editFormAction."&artikelid=".$row_rst_material['artikelid']."&materialnummer$materialindex=1"; ?>">
		  <img src="../documents/materialbilder/<?php echo $row_rst_material['link_dokument'] ?>" height="110" border="0" ></a><br> 
        <strong><?php echo utf8_decode($row_rst_material['Bezeichnung']); ?></strong><br> <?php echo $row_rst_material['Nummer']; ?> </td>
	<?php  if ($artikelid==$row_rst_material['artikelid']){?>
	<td width="300"> Stückliste und Versionsinfo für <?php echo $row_rst_material['version'] ?></td>
	<?php }?>	
		
    </tr>
    <?php  if ($artikelid==$row_rst_material['artikelid']){?>
    <tr> 
      <td><form action="<?php echo $editFormAction; ?>" method="POST" name="form2" >
	  
	  
	   <?php if ($row_rst_material['anzahlp']>0){ ?>
	   
        <input name="suchtext" type="text" class="eingabefeld" id="suchtext" value="" size="20" maxlength="20">
		<?php if ($row_rst_material['anzahlp']>=2){?>
		 <input name="suchtext2" type="text" class="eingabefeld" id="suchtext2" value="" size="20" maxlength="20">
		<?php }?>
		<?php if ($row_rst_material['anzahlp']>=3){?>
		 <input name="suchtext3" type="text" class="eingabefeld" id="suchtext3" value="" size="20" maxlength="20">
		<?php }?>
		
        <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>"><hr>
          <?php }?>
          <p>
            <input type="Submit" name="neugeraet" class="buttongross" value=" Neues Ger&auml;t ">
            <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst_material['artikelid']; ?>">
            <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
            <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">
          </p>
        </form>
		<?php echo $oktxt?>
		</td>
		<?php  if ($artikelid==$row_rst_material['artikelid']){?>
<?php 		

mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst2 = "SELECT artikelversionen.id FROM  artikeldaten, artikelversionen WHERE artikeldaten.artikelid='$row_rst_material[artikelid]' AND artikelversionen.artikelid='$row_rst_material[artikelid]' AND artikelversionen.version = artikeldaten.version ";
		$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
		$row_rst2 = mysql_fetch_assoc($rst2);
		$totalRows_rst2 = mysql_num_rows($rst2);
	

		$maxRows_rst_stueckliste = 50;
		$pageNum_rst_stueckliste = 0;
		if (isset($HTTP_GET_VARS['pageNum_rst_stueckliste'])) {
		  $pageNum_rst_stueckliste = $HTTP_GET_VARS['pageNum_rst_stueckliste'];
		}
		$startRow_rst_stueckliste = $pageNum_rst_stueckliste * $maxRows_rst_stueckliste;

		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst_stueckliste = "SELECT * FROM artikelversionsstuecklisten WHERE artikelversionsstuecklisten.version_id ='$row_rst2[id]' AND artikelversionsstuecklisten.st_art<>'Bild' ORDER BY artikelversionsstuecklisten.st_art";
		$query_limit_rst_stueckliste = sprintf("%s LIMIT %d, %d", $query_rst_stueckliste, $startRow_rst_stueckliste, $maxRows_rst_stueckliste);
		$rst_stueckliste = mysql_query($query_limit_rst_stueckliste, $qsdatenbank) or die(mysql_error());
		$row_rst_stueckliste = mysql_fetch_assoc($rst_stueckliste);
		
		if (isset($HTTP_GET_VARS['totalRows_rst_stueckliste'])) {
		  $totalRows_rst_stueckliste = $HTTP_GET_VARS['totalRows_rst_stueckliste'];
		} else {
		  $all_rst_stueckliste = mysql_query($query_rst_stueckliste);
		  $totalRows_rst_stueckliste = mysql_num_rows($all_rst_stueckliste);
		}
		$totalPages_rst_stueckliste = ceil($totalRows_rst_stueckliste/$maxRows_rst_stueckliste)-1;

?>
		
		
		
	<td width="200"> 
	
	
	<?php if ($totalRows_rst_stueckliste > 0) { // Show if recordset not empty ?>
  <?php do { ?> <?php echo utf8_decode($row_rst_stueckliste['menge']); ?> - <?php echo utf8_decode($row_rst_stueckliste['artikelnummer']); ?> - <?php echo utf8_decode($row_rst_stueckliste['bezeichnung']); ?> - <?php echo utf8_decode($row_rst_stueckliste['hinweis']); ?><br>
    <?php } while ($row_rst_stueckliste = mysql_fetch_assoc($rst_stueckliste)); ?>
	<?php } else {// Show if recordset not empty ?> 
	 Ich habe keine St&uuml;ckliste gefunden. Herr Rose ist schuld. <br> Es gibt <?php echo $totalRows_rst2 ?> Versionen.
		<?php } // Show if recordset not empty ?> 
	</td>
	<?php }?>
    </tr>
    <?php } /* wenn artikel ausgewählt */?>
  </table>


