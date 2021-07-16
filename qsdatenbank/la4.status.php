<?php ?>
<link href="fis.css" rel="stylesheet" type="text/css">
<table width="950" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="40"><font size="1"><strong>VIP</strong></font></td>
    <td width="49"><font size="1"><strong>FMID</strong></font></td>
    <td width="137"><font size="1"><strong>Typ</strong></font></td>
    <td width="67"><font size="1"><strong>SN</strong></font></td>
    <td width="431"><font size="1"><strong>Debitor H&auml;ndler Endkunde Kommision</strong></font></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="3" face="Arial, Helvetica, sans-serif"> 
      <?php if ((($row_rst4['vip']>=1))) {echo "<img src=\"picture/b_tipp.png\" alt=\"Dringlichkeitskennzeichen. Terminreparatur. Schnelle Reparatur erforderlich.\">" ;}?>
      <?php if (($row_rst4['vip']>=2)) {echo "<img src=\"picture/b_tipp.png\">";}?>
      </font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="3" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fmid']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="3" face="Arial, Helvetica, sans-serif"> 
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
      </font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="3" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fmsn']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="3"><?php echo htmlentities(utf8_decode(substr($row_rst4['fm1notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst4['fm11notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst4['fm12notes'],0,25)));?></font><font size="3" face="Arial, Helvetica, sans-serif"> 
      <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
      <input name="hurl_fmid2" type="hidden" id="hurl_fmid2" value="<?php echo $row_rst2['fmid']; ?>">
      </font></td>
  </tr>
</table>
      
<table width="950" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="69"><font size="1"><strong>Fertigungsdatum</strong></font></td>
    <td width="71"><font size="1"><strong>Eingang</strong></font></td>
    <td width="76"><div align="center"><strong><font size="1">Menge</font></strong></div></td>
    <td width="331"><div align="center"><strong><a href="la412.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst4['fmid']; ?>" target="_self"><font size="1">weitere 
        Daten anzeigen </font></a></strong></div></td>
    <td width="331"><div align="center"><strong><font size="1">SAP-Nr.</font></strong></div>
      <div align="center"></div>
      <div align="center"></div>
      <div align="center"></div>
      <div align="center"></div>
      <div align="center"></div></td>
  </tr>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fmfd']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['fm1datum']; ?></font></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <strong><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['reklamenge']; ?> </font></strong></div></td>
    <td nowrap="nowrap"    bgcolor="#EEEEEE">&nbsp;</td>
    <td nowrap="nowrap"    bgcolor="#EEEEEE"> <div align="center"> <strong><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst4['materialnummer']; ?> </font></strong></div>
      <div align="center"> </div>
      <div align="center"></div>
      <div align="center"> </div>
      <div align="center"> </div>
      <div align="center"> </div></td>
  </tr>
</table>
<table width="950" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#FFFFFF"> 
    <td width="66"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/b_newdb.png" alt="Retoureneingang" width="16" height="16"><img src="picture/<?php if (!(strcmp($row_rst4['fm0'],1))) {echo "st1.gif";}elseif (!(strcmp($row_rst4['fm0'],2))){echo "st2.gif";}else{echo "st3.gif";}?>" alt="geplante Retoure noch nicht aufgenommen" width="15" height="15"></font></div></td>
    <td width="72"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/b_tblops.png" alt="Fehleraufnahme" width="16" height="16"><img src="picture/<?php if (!(strcmp($row_rst4['fm1'],1))) {echo "st1.gif";}elseif (!(strcmp($row_rst4['fm1'],2))){echo "st2.gif";}else{echo "st3.gif";}?>" alt="Fehleraufnahme" width="15" height="15"></font></div></td>
    <td width="81"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/money.gif" alt="Kostenkl&auml;rung" width="15" height="15"><img src="picture/<?php if (!(strcmp($row_rst4['fm2'],1))) {echo "st1.gif";}elseif (!(strcmp($row_rst4['fm2'],2))){echo "st2.gif";}else{echo "st3.gif";}?>" alt="Kostenkl&auml;rung" width="15" height="15"></font></div></td>
    <td width="87"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <img src="picture/b_insrow.png" alt="Reparaturfreigabe" width="16" height="16"> 
        <?php if  ($row_rst4['vkz']>0){ 
					  if ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
        <img src="picture/<?php if (!(strcmp($row_rst4['fm3'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparaturfreigabe" width="15" height="15"> 
        <?php }?>
        </font></div></td>
    <td width="84"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <img src="picture/s_host.png" alt="Reparatur" width="16" height="16"> 
        <?php if  ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
        <img src="picture/<?php if (!(strcmp($row_rst4['fm4'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparatur + VDE-Pr&uuml;fung" width="15" height="15"> 
        <?php }?>
        </font></div></td>
    <td width="94"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/sap.png" alt="Reparaturfreigabe" width="16" height="16"> 
        <?php if  ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
        <img src="picture/<?php if ($row_rst4['sappcna']>1) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="SAP Fertigungsauftr&auml;ge als PCNA angelegt" width="15" height="15"> 
        <?php }?>
        </font></div></td>
    <td width="90"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/orderstatus.gif" alt="Versand" width="22" height="12"> 
        <?php if  ($row_rst4['vkz']>0){ 
					  if ($row_rst4['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
        <img src="picture/<?php if (!(strcmp($row_rst4['fm5'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kommissionierstatus" width="15" height="15"> 
        <?php }?>
        </font></div></td>
    <td width="50"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/fertig.gif" alt="technisch abgeschlossen" width="15" height="15"><img src="picture/<?php if (!(strcmp($row_rst4['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen" width="15" height="15"></font></div></td>
    <td width="50"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/iconBestOffer_16x16.gif" alt="Rechnung bezahlt" width="16" height="16"><img src="picture/<?php if (!(strcmp($row_rst4['fm8'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen" width="15" height="15"></font></div></td>
    <td width="50"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/Cfchart.gif" width="18" height="18"><img src="picture/<?php if (!(strcmp($row_rst4['fm9'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen" width="15" height="15"></font></div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td> 
      <?php if ($la=="la402"){ echo "Kundendaten";} else { ?>
      <div align="center"> 
        <input name="cancel2" type="submit" id="cancel23" class="la4button" value="Ansprechpartner">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la41"){ echo "Fehler";} else { ?>
      <div align="center"> 
        <input name="save1" type="submit" id="save1" class="la4button" value="Fehler">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la42"){ echo "Kosten";} else { ?>
      <div align="center"> 
        <input name="save2" type="submit" id="save2" class="la4button" value="Kosten">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la43"){ echo "Freigabe";} else { ?>
      <div align="center"> 
        <input name="save3" type="submit" id="save3"  class="la4button" value="Freigabe">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la44"){ echo "Reparatur";} else { ?>
      <div align="center"> 
        <input name="save4" type="submit" id="save4"  class="la4button" value="Reparatur">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la47"){ echo "Abrechnung";} else { ?>
      <div align="center"> 
        <input name="save7" type="submit" id="save7"  class="la4button" value="Abrechnung">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la45"){ echo "Verpacken";} else { ?>
      <div align="center"> 
        <input name="save5" type="submit" id="save5"  class="la4button" value="Kommision">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la46"){ echo "Versand";} else { ?>
      <div align="center"> 
        <input name="save6" type="submit" id="save6"  class="la4button" value="Versand">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la48"){ echo "Zahlung";} else { ?>
      <div align="center"> 
        <input name="save8" type="submit" id="save8"  class="la4button" value="Verrechnung">
        <?php } ?>
      </div></td>
    <td> 
      <?php if ($la=="la49"){ echo "Q-Auswertung";} else { ?>
      <div align="center"> 
        <input name="save9" type="submit" id="save9"  class="la4button" value="Q-Auswertung">
        <?php } ?>
      </div></td>
  </tr>
<tr>
<td colspan="10" bgcolor="#FFCC00">
Status: 
<?php if (!(strcmp($row_rst4['fm1'],2))){echo utf8_encode("Trocknung des Gerätes im Wärmeschrank läuft. ");}?>
<?php if (!(strcmp($row_rst4['vkz'],1))){echo utf8_encode("Verschrottung des Gerätes ist vorgesehen. ");}?>
<?php if (!(strcmp($row_rst4['vip'],1))){echo utf8_encode("Kunde hat bereits nachgefragt. ");}?>
<?php if (!(strcmp($row_rst4['vip'],2))){echo utf8_encode("Bitte ziemlich zügig bearbeiten! ");}?>
<?php if (!(strcmp($row_rst4['fm1'],0))){echo utf8_encode("Fehleraufnahme läuft ");}?>
<?php if (!(strcmp($row_rst4['fm3'],1))){echo utf8_encode("Reparatur ist freigeben. ");}?>
<?php if (!(strcmp($row_rst4['fm4'],1))){echo utf8_encode("Reparatur abgeschlossen. ");}?>
<?php if (!(strcmp($row_rst4['fm5'],1))){echo utf8_encode("Gerät ist versendet. ");}?>
</td>
</tr>
</table>
<?php ?>