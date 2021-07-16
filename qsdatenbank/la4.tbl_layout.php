<table width="950" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="25">VIP</td>
    <td width="44"><strong>FMID</strong></td>
    <td width="100"><strong>Bezeichnung</strong></td>
    <td width="47"><strong>SN</strong></td>
    <td width="241">Debitor</td>
    <td width="71">FD</td>
    <td width="71">Eingang</td>
    <td width="16"><div align="center"><img src="picture/b_newdb.png" width="16" height="16"></div></td>
    <td width="16"><p align="center"><img src="picture/b_tblops.png" width="16" height="16"></p></td>
    <td width="15"><div align="center"><img src="picture/money.gif" width="15" height="15"></div></td>
    <td width="16"><div align="center"><img src="picture/b_insrow.png" width="16" height="16"></div></td>
    <td width="16"><div align="center"><img src="picture/sap.png" width="16" height="16"></div></td>
    <td width="16"><div align="center"><img src="picture/s_host.png" width="16" height="16"></div></td>
    <td width="16"><div align="center"><img src="picture/orderstatus.gif" width="22" height="12"></div></td>
    <td width="15"><div align="center"><img src="picture/fertig.gif" width="15" height="15"></div></td>
	 <td width="16"><div align="center"><img src="picture/iconBestOffer_16x16.gif" width="16" height="16"></div></td>
   
    <td width="112">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif">
	  <?php if ($row_rst2['fmdatum']>=date("Y-m-d H:i:s",time()-100)) {echo "<img src=\"picture/iconAuction_16x16.gif\" alt=\"Achtung. Dieser Datensatz innerhalb der letzten 5 min von einem Benutzer bearbeitet.\">";}?>
	  <?php if ((($row_rst2['vip']>=1))) {echo "<img src=\"picture/b_tipp.png\" alt=\"Dringlichkeitskennzeichen. Terminreparatur. Schnelle Reparatur erforderlich.\">" ;}?>
      
		<?php if (($row_rst2['vip']>=2)) {echo "<img src=\"picture/b_tipp.png\">";}?>
        </font></td>
      
	  
	  <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fmid']; ?> 
        <?php if ($row_rst2['fm0']==0){ ?>
        <img src="picture/pickreturn.gif" alt="Retoure ist angelegt , Wareneingang noch nicht erfolgt." width="16" height="16"> 
        <?php }?>
        <?php if ($row_rst2['lokz']==1){ ?>
        <img src="picture/error.gif" alt="Datensatz LOKZ-Kennzeichen"  border="0"> 
        <?php }?>
        <?php if ($row_rst2['fmart']==1){ ?>
        <img src="picture/kostenvoranschlag.gif" alt="Q1 -Debitorenfehlermeldung: -Anzahl: <?php echo $row_rst2['reklamenge']; ?> - <?php echo $row_rst2['fmnotes'];?>" width="20" height="14"> 
        <?php }?>
        <?php if ($row_rst2['fmart']==2){ ?>
        <img src="picture/gutschrift.gif" alt="Q2-Kreditoren -Anzahl: <?php echo $row_rst2['materialnummer']; ?> - <?php echo $row_rst2['reklamenge']; ?> - <?php echo $row_rst2['fmnotes'];?>"  border="0"> 
        <?php }?>
        <?php if ($row_rst2['fmart']==3){ ?>
        <img src="picture/lieferung.gif" alt="Q3-Eigenfertigung -Anzahl: <?php echo $row_rst2['materialnummer']; ?> - <?php echo $row_rst2['reklamenge']; ?> - <?php echo $row_rst2['fmnotes'];?>"  border="0"> 
        <?php }?>
        <?php if ($row_rst2['fm6']!=1){ ?>
        <a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst2['fmid']; ?>"><img src="picture/b_edit.png" alt="bearbeiten" width="16" height="16" border="0"></a> 
        <?php }else{?>
        <a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst2['fmid']; ?>"><img src="picture/fertig.gif" alt="anzeigen" width="16" height="16" border="0" target="_blank"></a> 
        <?php }?>
        </font></td>
      
	  <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rsttyp['artikelid'], $row_rst2['fmartikelid']))) {echo substr((($row_rsttyp['Bezeichnung'])),0,36);} ?>
        <?php
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?>
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fmsn']; ?> </font><font color="#FF0000" size="1" face="Arial, Helvetica, sans-serif"><?php if ($row_rst2['fmsn']<>$row_rst2['fmsn2']){echo $row_rst2['fmsn2']; } ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo htmlentities(utf8_decode(substr($row_rst2['fm1notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm11notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm12notes'],0,25)));?></font><font size="1" face="Arial, Helvetica, sans-serif"> 
        <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_fmid" type="hidden" id="hurl_fmid2" value="<?php echo $row_rst2['fmid']; ?>">
		<?php if ($row_rst2['gewichtvorher']>0) { echo $row_rst2['gewichtvorher']." kg";}?>
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fmfd']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fm1datum']; ?></font> <a href="pdfetikett1.php?url_fmid=<?php echo $row_rst2['fmid']; ?>" target="_blank"><img src="picture/icon_info.gif" alt="Etikett drucken" width="14" height="14" border="0"></a> 
        <a href="pdfversandetikettGLS.php?url_fmid=<?php echo $row_rst2['fmid']; ?>" target="_blank"><img src="picture/anschreiben.gif" alt="Versandetikett für GLS drucken" width="14" height="14" border="0"></a><a href="
	  
	  <?php if ($row_rst2['fmart']==1) {echo "pdfq3_8d.php";}
	  elseif ($row_rst2['fmart']==2){echo "pdfq3_8d.php";}
	  elseif ($row_rst2['fmart']==3){echo "pdfq3_8d.php";}	  
	  
	  ?>?url_fmid=<?php echo $row_rst2['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/download1.gif" alt="8-D Report anzeigen" width="17" height="17" border="0"></a> 
        <a href="
	  
	  <?php if ($row_rst2['fmart']==1) {echo "pdfkv1.php";}
	  elseif ($row_rst2['fmart']==2){echo "pdfq2.php";}
	  elseif ($row_rst2['fmart']==3){echo "pdfq3.php";}	  
	  
	  ?>?url_fmid=<?php echo $row_rst2['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?>" target="_blank"><img src="picture/ico_anruflisten.gif" alt="Schriftverkehr anzeigen" width="16" height="16" border="0"></a> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst2['fm0'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="geplante Retoure noch nicht aufgenommen" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEcc" nowrap="nowrap"><div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/<?php if (!(strcmp($row_rst2['fm1'],1))) { echo "st1.gif";} elseif (!(strcmp($row_rst2['fm1'],2))) {echo "st2.gif";} else{echo "st3.gif";}?>
										
		 " alt="Fehleraufnahme" width="15" height="15"></font></div>
		  
		  
		  
		  
		  </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/<?php if (!(strcmp($row_rst2['fm2'],1))) {
		  if ($row_rst2['sapkst']==61612){
		  echo "st4.gif";
		 } else {
		  echo "st1.gif";}
		  
		  }else{echo "st3.gif";}?>" alt="Kostenkl&auml;rung" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEcc" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <?php if  ($row_rst2['vkz']>0){ 
					  if ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>

			  <img src="picture/<?php if (!(strcmp($row_rst2['fm3'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparaturfreigabe" width="15" height="15"> 
          <?php }?>
		  </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">
	  <?php if  ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
		 
	  <img src="picture/<?php if ($row_rst2['sappcna']>1) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="SAP Fertigungsauftr&auml;ge als PCNA angelegt <?php echo $row_rst2['sappcna'];?>" width="15" height="15">
      <?php }?></font></div></td>
	  <td    bgcolor="#EEEEcc" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
    	  <?php if  ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
		  <img src="picture/<?php if (!(strcmp($row_rst2['fm4'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparatur + VDE-Pr&uuml;fung" width="15" height="15"> 
          <?php }?>
		  </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif">
	  
	  <?php if  ($row_rst2['vkz']>0){ 
					  if ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
	  	<img src="picture/<?php if (!(strcmp($row_rst2['fm5'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kommissionierstatus - Lieferung-Nr.: <?php echo $row_rst2['fm5notes'];?> Versand-Nr.: <?php echo $row_rst2['glsversand']; ?>" width="15" height="15">
	  <?php }?> 
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/<?php if (!(strcmp($row_rst2['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen - Rechnung-Nr.: <?php echo $row_rst2['fm6notes'];?>" width="15" height="15"></font></div></td>
       <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst2['fm8'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Rechnung bezahlt" width="15" height="15"></font></div></td>
     
	  <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <font size="1" face="Arial, Helvetica, sans-serif"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
          </font></div></td>
    </tr>
  </form>
  
  <?php 
  if ($HTTP_POST_VARS['zeigefehler']==1 ){
  
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstz = "SELECT fehler.fname, fehler.fkurz, fehler.fid, fehler.lokz, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid, linkfehlerfehlermeldung.lffid FROM linkfehlerfehlermeldung, fehler WHERE linkfehlerfehlermeldung.lokz=0 AND linkfehlerfehlermeldung.fmid='$row_rst2[fmid]' AND fehler.fid =linkfehlerfehlermeldung.fid ORDER BY fehler.fkurz";
$rstz = mysql_query($query_rstz, $qsdatenbank) or die(mysql_error());
$row_rstz = mysql_fetch_assoc($rstz);
$totalRows_rstz = mysql_num_rows($rstz);
  
  ?>
  
   <tr> 
    <td width="25"></td>
    <td width="44"><strong></strong></td>
    <td width="100"><strong>Fehler</strong></td>
    <td width="47"><strong></td>
    <td width="241"><?php do {?>
	<?php echo $row_rstz['fkurz']; ?> - <?php echo $row_rstz['fname']; ?>; 
	<?php } while ($row_rstz = mysql_fetch_assoc($rstz)); ?></td>
    <td width="71"></td>
    <td width="71"></td>
    <td width="16"></td>
    <td width="16"></td>
    <td width="15"></td>
    <td width="16"></td>
    <td width="16"></td>
    <td width="16"></td>
    <td width="16"></td>
    <td width="15"></td>
	 <td width="16"></td>
   
    <td width="112">&nbsp;</td>
  </tr>
  <?php }?>
  
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>