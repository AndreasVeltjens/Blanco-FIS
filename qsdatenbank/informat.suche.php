<table width="450" border="0" cellpadding="0" cellspacing="1">
  <tr>
    <td width="54">unbekannt</td>
    <td width="99">Ger&auml;tetyp:</td>
    <td width="348"> <select name="typ" id="typ">
        <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['typ']))) {echo "SELECTED";} ?>>bitte 
        auswählen</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rst3['artikelid']?>"<?php if (!(strcmp($row_rst3['artikelid'], $HTTP_POST_VARS['typ']))) {echo "SELECTED";} ?>><?php echo $row_rst3['Nummer']?> 
        - <?php echo $row_rst3['Bezeichnung']?></option>
        <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
      </select></td>
  </tr>
  <tr>
    <td>
<input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn'],1))) {echo "checked";} ?> name="checkboxsn" type="checkbox" id="checkboxsn" value="1"></td>
    <td>Seriennummer</td>
    <td> <input name="seriennummer" type="text" id="seriennummer" value="<?php echo $HTTP_POST_VARS['seriennummer']; ?>"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <?php if ($HTTP_POST_VARS['typ']>0 and $HTTP_POST_VARS['checkboxsn']==1){?>
  <tr>
    <td><input name="checkboxsn1" type="checkbox" id="checkboxsn1" value="1"></td>
    <td>Bemerkungen</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="checkboxsn1" type="checkbox" id="checkboxsn1" value="1"></td>
    <td>Heizung</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="checkboxsn2" type="checkbox" id="checkboxsn2" value="1"></td>
    <td>Regler</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="checkboxsn1" type="checkbox" id="checkboxsn1" value="1"></td>
    <td>Heizungsmodul</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="checkboxsn11" type="checkbox" id="checkboxsn11" value="1"></td>
    <td>Regler</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="checkboxsn12" type="checkbox" id="checkboxsn12" value="1"></td>
    <td>Heizung</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input name="checkboxsn13" type="checkbox" id="checkboxsn13" value="1"></td>
    <td>L&uuml;fter</td>
    <td>&nbsp;</td>
  </tr>
  
  <?php } ?>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td> <input name="suchen" type="submit" id="suchen" value="Suchen"></td>
  </tr>
</table>
