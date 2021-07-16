<table width="301" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="2"><em><img src="picture/arrowClose.gif" width="8" height="9"></em>Ger&auml;tetyp:</td>
    <td width="348"> <select name="typ" id="typ">
        <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['typ']))) {echo "SELECTED";} ?>>bitte 
        auswählen</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rst3['artikelid']?>"<?php if (!(strcmp($row_rst3['artikelid'], $HTTP_POST_VARS['typ']))) {echo "SELECTED";
		
		$stuli=$row_rst3['stuli'];
		$anzahlp=$row_rst3['anzahlp'];
		
		
		} ?>><?php echo $row_rst3['Nummer']?> - <?php echo $row_rst3['Bezeichnung']?></option>
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
    <td width="54"> <input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn'],1))) {echo "checked";} ?> name="checkboxsn" type="checkbox" id="checkboxsn" value="1"></td>
    <td width="99">Seriennummer</td>
    <td> <input name="seriennummer" type="text" id="seriennummer" value="<?php echo $HTTP_POST_VARS['seriennummer']; ?>"></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php if ($HTTP_POST_VARS['typ']>0 and $HTTP_POST_VARS['checkboxsn']==1){?>
  <?php if ($stuli==0 && $anzahlp==1) {?>
  <tr> 
    <td><input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn2'],1))) {echo "checked";} ?> name="checkboxsn3" type="checkbox" id="checkboxsn2" value="1"></td>
    <td>Bemerkungen</td>
    <td><input name="seriennummer2" type="text" id="seriennummer2" value="<?php echo $HTTP_POST_VARS['seriennummer2']; ?>"></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php } elseif ($stuli>0 && $anzahlp==2) {?>
  <tr> 
    <td><input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn3'],1))) {echo "checked";} ?> name="checkboxsn3" type="checkbox" id="checkboxsn3" value="1"></td>
    <td>Heizung</td>
    <td><input name="seriennummer3" type="text" id="seriennummer3" value="<?php echo $HTTP_POST_VARS['seriennummer3']; ?>"></td>
  </tr>
  <tr> 
    <td><input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn4'],1))) {echo "checked";} ?> name="checkboxsn4" type="checkbox" id="checkboxsn4" value="1"></td>
    <td>Regler</td>
    <td><input name="seriennummer4" type="text" id="seriennummer4" value="<?php echo $HTTP_POST_VARS['seriennummer4']; ?>"></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php }elseif ($stuli>0 && $anzahlp==1) {?>
  <tr> 
    <td><input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn5'],1))) {echo "checked";} ?> name="checkboxsn5" type="checkbox" id="checkboxsn5" value="1"></td>
    <td>Heizungsmodul</td>
    <td><input name="seriennummer5" type="text" id="seriennummer5" value="<?php echo $HTTP_POST_VARS['seriennummer5']; ?>"></td>
  </tr>
  <tr> 
    <td><input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn6'],1))) {echo "checked";} ?> name="checkboxsn6" type="checkbox" id="checkboxsn6" value="1"></td>
    <td>Regler</td>
    <td><input name="seriennummer6" type="text" id="seriennummer6" value="<?php echo $HTTP_POST_VARS['seriennummer6']; ?>"></td>
  </tr>
  <tr> 
    <td><input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn7'],1))) {echo "checked";} ?> name="checkboxsn7" type="checkbox" id="checkboxsn7" value="1"></td>
    <td>Heizung</td>
    <td><input name="seriennummer7" type="text" id="seriennummer7" value="<?php echo $HTTP_POST_VARS['seriennummer7']; ?>"></td>
  </tr>
  <tr> 
    <td><input <?php if (!(strcmp($HTTP_POST_VARS['checkboxsn8'],1))) {echo "checked";} ?> name="checkboxsn8" type="checkbox" id="checkboxsn8" value="1"></td>
    <td>L&uuml;fter</td>
    <td><input name="seriennummer8" type="text" id="seriennummer8" value="<?php echo $HTTP_POST_VARS['seriennummer8']; ?>"></td>
  </tr>
  <?php } else {?>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="2"><font color="#FF0000">keine Suchbegriffe vorhanden.</font></td>
  </tr>
  <?php }?>
  <?php } ?>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td> <input name="suchen" type="submit" id="suchen" value="Suchen"></td>
  </tr>
</table>
