<p>Reparaturabteilung</p>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2" bgcolor="#CCCCCC"><strong>Wareneingang</strong></td>
    <td width="35">&nbsp;</td>
    <td colspan="2" bgcolor="#CCCCCC"><strong>Warenausgang</strong></td>
  </tr>
  <tr> 
    <td width="64">&nbsp;</td>
    <td width="248">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="69">&nbsp;</td>
    <td width="262">&nbsp;</td>
  </tr>
  <tr> 
    <td><img src="picture/rechnerschutz.gif" width="52" height="36"></td>
    <td bgcolor="#FFFFFF">LZ611406<br>
      Dell Optiplex GX260</td>
    <td>&nbsp;</td>
    <td><img src="picture/rechnerschutz.gif" width="52" height="36"></td>
    <td bgcolor="#FFFFFF">LZ611408<br>
      Dell Optiplex GX260</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><img src="picture/sap.png" width="16" height="16"></td>
    <td>SAP-Logon 710</td>
  </tr>
  <tr> 
    <td><img src="picture/icon_drucken_16x16.gif" width="16" height="16"></td>
    <td>Etikettdrucker Zebra TLP 2844 f&uuml;r FM Etikett 10 x 5</td>
    <td>&nbsp;</td>
    <td><img src="picture/icon_drucken_16x16.gif" width="16" height="16"></td>
    <td>cde9x752 f&uuml;r Lieferschein A4 und <br>
      GLS-Versand Label A5</td>
  </tr>
  <tr> 
    <td><img src="picture/zugang.png" width="53" height="36"></td>
    <td bgcolor="#FFFFFF">Mitarbeiterliste</td>
    <td>&nbsp;</td>
    <td><img src="picture/zugang.png" width="53" height="36"></td>
    <td bgcolor="#FFFFFF">Mitarbeiterliste</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td> <?php if ($totalRows_rst611406 == 0) { // Show if recordset empty ?>
      Kein Mitarbeiter ausgewählt. 
      <?php } // Show if recordset empty ?> <?php if ($totalRows_rst611406 > 0) { // Show if recordset not empty ?>
      <?php do { ?>
      -<?php echo $row_rst611406['name']."<br>"; ?> 
      <?php } while ($row_rst611406 = mysql_fetch_assoc($rst611406)); ?>
      <?php } // Show if recordset not empty ?> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td> <?php if ($totalRows_rst611408 == 0) { // Show if recordset empty ?>
      Kein Mitarbeiter ausgewählt. 
      <?php } // Show if recordset empty ?> <?php if ($totalRows_rst611408 > 0) { // Show if recordset not empty ?>
      <?php do { ?>
      -<?php echo $row_rst611408['name']."<br>"; ?> 
      <?php } while ($row_rst611408 = mysql_fetch_assoc($rst611408)); ?>
      <?php } // Show if recordset not empty ?> </td>
    <td width="4">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" bgcolor="#CCCCCC"><strong>Reparatur</strong></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><img src="picture/rechnerschutz.gif" width="52" height="36"></td>
    <td bgcolor="#FFFFFF">LZ611407<br>
      Dell Optiplex GX260</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><img src="picture/icon_drucken_16x16.gif" width="16" height="16"></td>
    <td>Etikettdrucker Zebra TLP 2844 f&uuml;r Typenschilder</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><img src="picture/zugang.png" width="53" height="36"></td>
    <td bgcolor="#FFFFFF">Mitarbeiterliste</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td> <?php if ($totalRows_rst611407 == 0) { // Show if recordset empty ?>
      Kein Mitarbeiter ausgewählt. 
      <?php } // Show if recordset empty ?> <?php if ($totalRows_rst611407 > 0) { // Show if recordset not empty ?>
      <?php do { ?>
      -<?php echo $row_rst611407['name']."<br>"; ?> 
      <?php } while ($row_rst611407 = mysql_fetch_assoc($rst611407)); ?>
      <?php } // Show if recordset not empty ?> </td>
  </tr>
</table>
<p>&nbsp;</p>
