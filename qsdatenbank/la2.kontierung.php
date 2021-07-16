<MM:DECORATION OUTLINE="Repeat" OUTLINEID=1><table width="980" border="0" cellspacing="0" cellpadding="4">
  <tr> 
    <td colspan="2">Liste der Materialnummern: <?php echo ($startRow_rst2 + 1) ?> 
      bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?></td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td><em><strong>Bezeichnung - Material</strong></em></td>
    <td><em><strong>Bezeichnung - Material- </strong></em></td>
  </tr>
  <tr> 
    <td width="730"> 
	
	<table width="435"  border="0" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <?php $i=0;?>
        <?php do { 
		
		?>
        <?php   if ($row_rst2['Kontierung']!=$kontierung){?>
        <tr bgcolor="#C9C9C9" onMouseDown="setPointer(this, 1, 'click', '#C9C9C9', '#CCCCFF', '#FFCC99');" onMouseOver="setPointer(this, 1, 'over', '#C9C9C9', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#C9C9C9', '#CCCCFF', '#FFCC99');"> 
          <td colspan="8" nowrap="nowrap"><em><a href="la1.php?url_user=<?php echo $row_rst1['id']  ?>" target="_self"><img src="picture/auftrag.gif" width="20" height="14" border="0" align="absmiddle"></a><a href="<?php echo $la ?>.php?url_user=<?php echo $row_rst1['id']  ?>" target="_self">alles 
            anzeigen</a><br>
            <img src="picture/add.gif" width="15" height="14"> <strong><a href="la1.php?url_user=<?php echo $row_rst1['id'] ?>&url_kontierung=<?php echo $row_rst2['Kontierung'] ?>" target="_self"><font size="4"><img src="picture/anforderung_24x24.png" width="24" height="24" border="0" align="absmiddle"></font></a> 
            <a href="<?php echo $la ?>.php?url_user=<?php echo $row_rst1['id'] ?>&url_kontierung=<?php echo $row_rst2['Kontierung'] ?>" target="_self"><font size="4"><?php echo $row_rst2['Kontierung']; ?></font></a></strong></em></td>
        </tr>
        <?php 	}?>
        <?php 		
		
		
				if (round($totalRows_rst2/2,0)==$i){
				 ?>
      </table>
	  </td>
    <td> 
	<table width="435" border="0" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <?php } ?>
        <?php if ( ($row_rst2['Kontierung']==$url_kontierung)  or $HTTP_POST_VARS['suchtext']<>""){?>
        <?php   if ($row_rst2['Gruppe']!=$gruppe){?>
        <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
          <td colspan="11" nowrap="nowrap"    bgcolor="#EEEEEE"><em><img src="picture/1.gif" width="20" height="10"><img src="picture/add.gif" width="15" height="14"> 
            <strong><img src="picture/folder_open.gif" width="16" height="16" border="0" align="absmiddle"><?php echo $row_rst2['Gruppe']; ?></strong></em></td>
        </tr>
        <?php 	}?>
        <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td width="90"   bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/2.gif" width="40" height="10"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer']; ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer']; ?>.png" alt="Bild anzeigen" width="40" height="30" border="0" align="texttop"></a></td>
            <td   width="200" bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Bezeichnung']; ?>
			<a href="pdfetikett11.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><br>
              <img src="picture/anschreiben.gif" alt="Lagerplatzbeschriftung" width="18" height="18" border="0"></a> 
              <a href="pdfetikett12.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="Lagerplatz A4 mit Bild" width="18" height="18" border="0"></a> 
              <a href="pdfla2k.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialkennzeichnung A4 " width="17" height="18" border="0"></a><a href="pdfla2p.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialdatenblatt mit Bild" width="17" height="18" border="0"></a><a href="pdfla2.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialdaten ohne Bild" width="17" height="18" border="0"></a><a href="pdfetikett13.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="Lagerplatz Aufkleber mit Bild" width="18" height="18" border="0"></a> 
              <a href="pdfetikett13.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>&alternative=1" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="alternativer Lagerplatz 1 - Aufkleber mit Bild" width="18" height="18" border="0"></a>-<a href="pdfetikett13.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>&alternative=2" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="alternativer Lagerplatz 2- Aufkleber mit Bild" width="18" height="18" border="0"></a> 
              <a href="pdfetikett12.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>&alternative=2" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="alternativer Lagerplatz 2 - A4 mit Bild" width="18" height="18" border="0"></a> 
            </td>
            <td   width="20" bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><?php if( $row_rst2['ean13']<>"") { ?>
                <input name="imageField" type="image" src="picture/icon_scan.gif" alt="EAN13-Code : <?php echo $row_rst2['ean13'];?>" width="16" height="16" border="0">
                <?php } ?></div></td>
            <td  width="20" bgcolor="#EEEEEE"  nowrap="nowrap"><div align="center"><font color="#99CC00"><?php echo $row_rst2['Nummer']; ?></font></div>
			
			
			
			
			
			
			<?php 
			mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstvs = "SELECT * FROM artikelversionen WHERE artikelversionen.artikelid = '$row_rst2[artikelid]' ORDER BY artikelversionen.Datum";
$rstvs = mysql_query($query_rstvs, $qsdatenbank) or die(mysql_error());
$row_rstvs = mysql_fetch_assoc($rstvs);
$totalRows_rstvs = mysql_num_rows($rstvs);
			
			
			
			?><?php if ($totalRows_rstvs > 0) { 
				do {
				 if ($row_rstvs['version']==$row_rst2['version']) {echo "<strong>";}
				   echo $row_rstvs['materialnummer']."<br>";
				   if ($row_rstvs['version']==$row_rst2['version']) {echo "</strong>";}
				
				} while ($row_rstvs= mysql_fetch_assoc($rstvs)); ?>
			<?php }else{?>
			<?php echo 'kein Erstmuster';  ?>
			<?php }?>
			
			
			
			
			</td>
            <td  width="35"  bgcolor="#EEEEEE" nowrap="nowrap"> <input name="stamm" type="submit" id="neu3" value="B">
              <img src="picture/b_edit.png" width="16" height="16"></td>
            <td   width="2" bgcolor="#EEEEEE" nowrap="nowrap"><?php if( $row_rst2['aktiviert']==1){; ?><img src="picture/eo.png" width="15" height="15"><?php } ?></td>
            <td   width="2" bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['version']; ?></div></td>
            <td   width="2" bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"> 
                <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $url_user; ?>">
                <input name="edit" type="submit" id="edit" value="V">
                <img src="picture/b_newdb.png" width="16" height="16">
<input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
              </div></td>
          </tr>
        </form>
        <?php
		
		}/* ende kontierung */
$gruppe=$row_rst2['Gruppe'];
	 $kontierung=$row_rst2['Kontierung'];
	
	$i=$i+1;
	
	
	
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
      </table></td>
  </tr>
</table>
