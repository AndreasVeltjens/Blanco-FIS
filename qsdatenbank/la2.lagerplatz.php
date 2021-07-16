<table width="200" border="0" cellspacing="0" cellpadding="4">
  <tr> 
    <td width="881">Liste der Materialnummern: <?php echo ($startRow_rst2 + 1) ?> 
      bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?></td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td><em><strong>Bezeichnung - Material- </strong></em></td>
  </tr>
  <tr> 
    <td><table width="800"  border="0" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <?php $i=0;?>
        <?php do { 
		
		?>
        
        <?php   if (substr($row_rst2['lagerplatz'],0,4)!=$kontierung){?>
        <tr bgcolor="#C9C9C9" onMouseDown="setPointer(this, 1, 'click', '#C9C9C9', '#CCCCFF', '#FFCC99');" onMouseOver="setPointer(this, 1, 'over', '#C9C9C9', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#C9C9C9', '#CCCCFF', '#FFCC99');"> 
          <td colspan="8" nowrap="nowrap" bgcolor="#666666"><em><a href="<?php echo $la ?>.php?url_user=<?php echo $row_rst1['id']  ?>" target="_self">alles 
            anzeigen</a><br>
            <img src="picture/add.gif" width="15" height="14"> <strong><a href="<?php echo $la ?>.php?url_user=<?php echo $row_rst1['id'] ?>&url_kontierung=<?php echo substr($row_rst2['lagerplatz'],0,4); /* Lagerort */ ?>" target="_self"><font size="4"><?php echo substr($row_rst2['lagerplatz'],0,4); /* Lagerort */ ?></font></a></strong></em></td>
        </tr>
        <?php 	}?>
        <?php 		
		
/*  zweigesteilete Liste		
				if (round($totalRows_rst2/2,0)==$i){
				 ?>
      </table>
      <table width="150" border="0" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <?php } */?>
        <?php if ( (substr($row_rst2['lagerplatz'],0,4) /* Lagerort */==$url_kontierung)  or $HTTP_POST_VARS['suchtext']<>""){?>
        <?php   if (substr($row_rst2['lagerplatz'],5,3) /* Warenhaus */!=$gruppe){?>
        <?php if ($gruppe<>""){ ?>
		</table>
      
      <?php }?>
    
  <tr bgcolor="#999999" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="7" nowrap="nowrap"><em><img src="picture/1.gif" width="20" height="10"><img src="picture/add.gif" width="15" height="14"> 
      <strong>Warenhaus <?php echo substr($row_rst2['lagerplatz'],5,3); ?></strong></em></td>
  </tr>
  <?php 	}?>
  <?php   if (substr($row_rst2['lagerplatz'],8,3) /* Ebene */!=$gruppe2){ ?>
  
  <table align="left">
  
  
  <tr bgcolor="#CCCCCC" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="7" nowrap="nowrap"><em><img src="picture/2.gif"><img src="picture/add.gif" width="15" height="14"> 
      <strong>Fach <?php echo substr($row_rst2['lagerplatz'],8,3); ?></strong></em></td>
  </tr>
  <?php 	}?>
  <?php   if (substr($row_rst2['lagerplatz'],11,3) /* Fach */!=$gruppe3){?>
  <tr bgcolor="#6666FF" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="7" nowrap="nowrap"><em><img src="picture/3.gif"><img src="picture/add.gif" width="15" height="14"> 
      <strong>Ebene <?php echo substr($row_rst2['lagerplatz'],11,3); ?></strong></em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td width="180"   bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/4.gif" width="80" height="10"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer']; ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer']; ?>.png" alt="Bild anzeigen" width="50" height="40" border="0" align="texttop"></a></td>
      <td   width="200" bgcolor="#EEEEEE" nowrap="nowrap"><strong><?php echo $row_rst2['Nummer']; ?><a href="pdfla2k.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialkennzeichnung A4 " width="17" height="18" border="0"></a><a href="pdfla2p.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialdatenblatt mit Bild" width="17" height="18" border="0"></a><a href="pdfla2.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialdaten ohne Bild" width="17" height="18" border="0"></a> 
        <?php if( $row_rst2['ean13']<>"") { ?>
        <input name="imageField" type="image" src="picture/icon_scan.gif" alt="EAN13-Code : <?php echo $row_rst2['ean13'];?>" width="16" height="16" border="0">
        <?php } ?>
        </strong><br> 
        <?php echo $row_rst2['Bezeichnung']; ?><a href="pdfla2k.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><br>
        </a> <a href="pdfetikett11.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/anschreiben.gif" alt="Lagerplatzbeschriftung" width="18" height="18" border="0"></a><?php echo $row_rst2['lagerplatz']; ?> </td>
           
		   
		 <?php if ($printansicht<>1 ){ ?>  
		   <td   width="120" bgcolor="#EEEEEE" nowrap="nowrap"> <p> 
                <input name="stamm" type="submit" id="stamm2" value="B">
                <img src="picture/b_edit.png" width="16" height="16"> <br>
                <?php if( $row_rst2['aktiviert']==1){; ?>
                <img src="picture/eo.png" width="15" height="15"> 
                <?php } ?>
                <?php echo $row_rst2['version']; ?> 
                <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
                <input name="edit" type="submit" id="edit" value="V">
                <img src="picture/b_newdb.png" width="16" height="16"> 
                <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
                <select name="lagerplatzliste">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['lagerplatz']))) {echo "SELECTED";} ?>>keine 
          Auswahl</option>
          <option value="WH1" <?php if (!(strcmp("WH1", $row_rst2['lagerplatz']))) {echo "SELECTED";} ?>>WH1</option>
          <option value="WH2" <?php if (!(strcmp("WH2", $row_rst2['lagerplatz']))) {echo "SELECTED";} ?>>WH2</option>
          <option value="WH3" <?php if (!(strcmp("WH3", $row_rst2['lagerplatz']))) {echo "SELECTED";} ?>>WH3</option>
          <option value="WH4" <?php if (!(strcmp("WH4", $row_rst2['lagerplatz']))) {echo "SELECTED";} ?>>WH4</option>
          <option value="--------------" <?php if (!(strcmp("--------------", $row_rst2['lagerplatz']))) {echo "SELECTED";} ?>>--------------------</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rstlagerort['lagerplatz']?>"<?php if (!(strcmp($row_rstlagerort['lagerplatz'], $row_rst2['lagerplatz']))) {echo "SELECTED";} ?>><?php echo $row_rstlagerort['lagerplatz']?></option>
          <?php
} while ($row_rstlagerort = mysql_fetch_assoc($rstlagerort));
  $rows = mysql_num_rows($rstlagerort);
  if($rows > 0) {
      mysql_data_seek($rstlagerort, 0);
	  $row_rstlagerort = mysql_fetch_assoc($rstlagerort);
  }
?>
        </select>
				
				
				<input type="submit" name="umlagern" value="U">
				
              </p></tr> 
			  <?php } // ende Printansicht <> 1 ?>
			  
  </form>

  <?php
		
		}/* ende kontierung */
		$gruppe3=substr($row_rst2['lagerplatz'],11,3); /* Fach */
		$gruppe2=substr($row_rst2['lagerplatz'],8,3); /* Ebene */
	    $gruppe=substr($row_rst2['lagerplatz'],5,3); /* Warenhaus */
	    $kontierung=substr($row_rst2['lagerplatz'],0,4); /* Lagerort */
	
	$i=$i+1;
	
	
	
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
</td> </tr> </table>