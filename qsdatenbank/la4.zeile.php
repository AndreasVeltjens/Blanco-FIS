<?php ?>
<table width="774" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="40"><strong>VIP</strong></td>
    <td width="44"><strong>FMID</strong></td>
    <td width="129"><strong>Typ</strong></td>
    <td width="47"><strong>SN</strong></td>
    <td width="103"><font color="#FF0000">USN</font></td>
    <td width="103"><strong>Debitor</strong></td>
    <td width="63"><strong>FD</strong></td>
    <td width="68"><strong>Eingang</strong></td>
    <td width="20"><div align="center"><img src="picture/b_newdb.png" width="16" height="16"></div></td>
    <td width="20"><p align="center"><img src="picture/b_tblops.png" width="16" height="16"></p></td>
    <td width="20"><div align="center"><img src="picture/money.gif" width="15" height="15"></div></td>
    <td width="20"><div align="center"><img src="picture/b_insrow.png" width="16" height="16"></div></td>
    <td width="20"><div align="center"><img src="picture/sap.png" width="16" height="16"></div></td>
    <td width="20"><div align="center"><img src="picture/s_host.png" width="16" height="16"></div></td>
    <td width="20"><div align="center"><img src="picture/s_db.png" width="16" height="16"></div></td>
    <td width="20"><div align="center"><img src="picture/fertig.gif" width="15" height="15"></div></td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php if ((($row_rst4['vip']>=1))) {echo "<img src=\"picture/b_tipp.png\" alt=\"Dringlichkeitskennzeichen. Terminreparatur. Schnelle Reparatur erforderlich.\">" ;}?>
      <?php if (($row_rst4['vip']>=2)) {echo "<img src=\"picture/b_tipp.png\">";}?>
      </font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fmid']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr(GetArtikelData($row_rst4['fmartikelid']),0,50); ?> </font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fmsn']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font color="#FF0000" size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fmsn2']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo htmlentities(utf8_decode(substr($row_rst4['fm1notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm11notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst4['fm12notes'],0,25)));?></font><font size="1" face="Arial, Helvetica, sans-serif"> 
      <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
      <input name="hurl_fmid2" type="hidden" id="hurl_fmid2" value="<?php echo $row_rst2['fmid']; ?>">
      </font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fmfd']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fm1datum']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
      <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst4['fm0'],1))) {echo "st1.gif";}  elseif (!(strcmp($row_rst4['fm0'],2))) {echo "st2.gif";}else{echo "st3.gif";}?>" alt="geplante Retoure noch nicht aufgenommen" width="15" height="15"> 
        </font></div></td>
    <td    bgcolor="#EEEEcc" nowrap="nowrap"><div align="center"> 
        <font size="1" face="Arial, Helvetica, sans-serif"> <img src="picture/<?php if (!(strcmp($row_rst4['fm1'],1))) {echo "st1.gif";}  elseif (!(strcmp($row_rst4['fm1'],2))) {echo "st2.gif";} else{echo "st3.gif";}?>" alt="Fehleraufnahme" width="15" height="15"></font></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
      <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
        <img src="picture/<?php if (!(strcmp($row_rst4['fm2'],1))) {echo "st1.gif";} elseif (!(strcmp($row_rst4['fm2'],2))) {echo "st2.gif";}else{echo "st3.gif";}?>" alt="Kostenkl&auml;rung" width="15" height="15"> 
        </font></div></td>
    <td    bgcolor="#EEEEcc" nowrap="nowrap"> 
      <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if  ($row_rst4['vkz']>0){ 
					  if ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
        <img src="picture/<?php if (!(strcmp($row_rst4['fm3'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparaturfreigabe" width="15" height="15"> 
        <?php }?>
        </font></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if  ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
        <img src="picture/<?php if ($row_rst4['sappcna']>1) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="SAP Fertigungsauftr&auml;ge als PCNA angelegt" width="15" height="15"> 
        <?php }?>
        </font></div></td>
    <td    bgcolor="#EEEEcc" nowrap="nowrap"> 
      <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if  ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
        <img src="picture/<?php if (!(strcmp($row_rst4['fm4'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparatur + VDE-Pr&uuml;fung" width="15" height="15"> 
        <?php }?>
        </font></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
      <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if  ($row_rst4['vkz']>0){ 
					  if ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
        <img src="picture/<?php if (!(strcmp($row_rst4['fm5'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kommissionierstatus - Lieferung-Nr.: <?php echo $row_rst4['fm5notes'];?> Versand-Nr.: <?php echo $row_rst4['glsversand']; ?>" width="15" height="15"> 
        <?php }?>
        </font></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"> 
        <font size="1" face="Arial, Helvetica, sans-serif"> <img src="picture/<?php if (!(strcmp($row_rst4['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen - Rechnung-Nr.: <?php echo $row_rst4['fm6notes'];?>" width="15" height="15"></font></div></td>
  </tr>
  <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
</table>
      
<?php ?>