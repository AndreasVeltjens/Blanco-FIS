<?php ?> 
<style type="text/css">
<!--
.button {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	text-align: left;
	height: 16px;
	width: 100px;
	cursor: hand;
	border: thin none #FFFFCC;
}
-->
</style>
 
<table width="65" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="63"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif">Fehlermeldung 
        Q<?php echo $row_rst4['fmart']; ?></font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><strong>FM 
        <?php echo $row_rst4['fmid']; ?></strong></font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if ((($row_rst4['vip']>=1))) {echo "<img src=\"picture/b_tipp.png\" alt=\"Dringlichkeitskennzeichen. Terminreparatur. Schnelle Reparatur erforderlich.\">" ;}?>
        <?php if (($row_rst4['vip']>=2)) {echo "<img src=\"picture/b_tipp.png\">";}?>
        </font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif">SAP-Nr.:&nbsp; 
      <?php echo $row_rst4['materialnummer']; ?> </font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rsttyp['artikelid'], $row_rst4['fmartikelid']))) {echo $row_rsttyp['Bezeichnung'];} ?>
        <?php
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?>
        &nbsp; </font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif">SN:&nbsp; 
        <?php echo $row_rst4['fmsn']; ?> </font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
        </font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo htmlentities(utf8_decode(substr($row_rst4['fm1notes'],0,25)));  ?> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst2['fmid']; ?>">
        </font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo " / ".htmlentities(utf8_decode(substr($row_rst4['fm11notes'],0,25))); ?>&nbsp; 
        </font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo " / ".htmlentities(utf8_decode(substr($row_rst4['fm12notes'],0,25)));?>&nbsp; 
        </font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><strong>Fertigungsdatum 
        </strong><?php echo $row_rst4['fmfd']; ?></font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><strong>Eingang: 
        </strong><?php echo $row_rst4['fm1datum']; ?></font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"></font></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <img src="picture/<?php if (!(strcmp($row_rst4['fm0'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="geplante Retoure noch nicht aufgenommen" width="15" height="15"> 
      <input name="save0" type="submit" class="button" id="save04" value="Kundendaten">
      <img src="picture/iconchance_16x16.gif" width="16" height="16"></font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <img src="picture/<?php if (!(strcmp($row_rst4['fm1'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Fehleraufnahme" width="15" height="15"> 
      <input name="save1" type="submit" class="button" id="save14" value="Fehleraufnahme">
      <img src="picture/b_tblops.png" alt="Fehleraufnahme" width="16" height="16"></font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <img src="picture/<?php if (!(strcmp($row_rst4['fm2'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kostenkl&auml;rung" width="15" height="15"> 
      <input name="save2" type="submit" class="button" id="save25" value="Kostenkl&auml;rung">
      <img src="picture/money.gif" alt="Kostenkl&auml;rung" width="15" height="15"></font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <?php if  ($row_rst4['vkz']>0){ 
					  if ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
      <img src="picture/<?php if (!(strcmp($row_rst4['fm3'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparaturfreigabe" width="15" height="15"> 
      <?php }?>
      <input name="save3" type="submit" class="button" id="save33" value="Reparaturfreigabe">
      <img src="picture/b_insrow.png" alt="Reparaturfreigabe" width="16" height="16"> 
      </font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <?php if  ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
      <img src="picture/<?php if ($row_rst4['sappcna']>1) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="SAP Fertigungsauftr&auml;ge als PCNA angelegt" width="15" height="15"> 
      <?php }?>
      <input name="save42" type="submit" class="button" id="save44" value="Abrechnungsvorschrift">
      <img src="picture/sap.png" alt="PCNA-Auftrag angelegt" width="16" height="16"> 
      </font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <?php if  ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
      <img src="picture/<?php if (!(strcmp($row_rst4['fm4'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparatur + VDE-Pr&uuml;fung" width="15" height="15"> 
      <?php }?>
      <input name="save4" type="submit" class="button" id="save4" value="VDE-Pr&uuml;fung">
      <img src="picture/s_host.png" alt="Reparatur" width="16" height="16"> </font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <?php if  ($row_rst4['vkz']>0){ 
					  if ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
      <img src="picture/<?php if (!(strcmp($row_rst4['fm5'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kommissionierstatus" width="15" height="15"> 
      <?php }?>
      <input name="save5" type="submit" class="button" id="save52" value="Kommisionierung">
      <img src="picture/orderstatus.gif" alt="Versand" width="22" height="12"> 
      </font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <img src="picture/<?php if (!(strcmp($row_rst4['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen" width="15" height="15"></font> 
      <font size="2" face="Arial, Helvetica, sans-serif"> 
      <input name="save6" type="submit" class="button" id="save62" value="Warenausgang">
      <img src="picture/fertig.gif" alt="technisch abgeschlossen" width="15" height="15"> 
      </font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <img src="picture/<?php if (!(strcmp($row_rst4['fm8'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen" width="15" height="15"> 
      <input name="save7" type="submit" class="button" id="save72" value="Zahlungseingang">
      <img src="picture/iconBestOffer_16x16.gif" alt="Rechnung bezahlt" width="16" height="16"> 
      </font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst4['fm9'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen" width="15" height="15"></font> 
      <input name="save8" type="submit" class="button" id="save8" value="Korrekturmaßnahme"></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfetikett1.php?url_fmid=<?php echo $row_rst4['fmid']; ?>" target="_blank"><img src="picture/printetikett.png" width="120" height="16" border="0"></a></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfwbserweitert.php?url_fmid=<?php echo $row_rst4['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?> " target="_blank"><img src="picture/wbs.png" width="120" height="16" border="0"></a></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfwbs.php?url_fmid=<?php echo $row_rst4['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?> " target="_blank"><img src="picture/wbs.png" width="120" height="16" border="0"></a></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfkv1.php?url_fmid=<?php echo $row_rst4['fmid'];?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/kostenvoranschlag.png" width="120" height="16" border="0"></a></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfversandetikettGLS.php?url_fmid=<?php echo $row_rst4['fmid']; ?>" target="_blank"><img src="picture/glslabel.png" width="120" height="16" border="0"></a></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');">
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="la6.php?url_user=<?php echo $row_rst1['id']; ?>&url_user_id=<?php echo $row_rst1['id'];?> " target="_blank"><img src="picture/kundendatenanlegen.png" alt="Kunden anlegen erfolgt in einem neuen Fenster" width="120" height="16" border="0"></a></td>
  </tr>
</table>
<?php ?>
