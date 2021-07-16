                <td colspan="3"><img src="picture/add.gif" width="15" height="14"> 
                  <em>neue Meldung anlegen 2. Schritt</em></td>
              </tr><tr bgcolor="#CCCCFF">
                <td>&nbsp;</td>
                <td><font size="4">Verbesserungsvorschlag</font></td>
                <td>&nbsp;</td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td width="137">Verbesserungs- bzw. Problembeschreibung<br> <font size="1">.</font><br> 
                  <img src="picture/help2.gif" alt="Bitte immer die Fehlermeldenummer oder Datensatz ID gleichzeitig mit angeben. Den Text immer Eingabefenster nicht &uuml;berschreiben oder l&ouml;schen" width="16" height="16"> 
                  <br> </td>
                <td width="432"> <textarea name="textarea" cols="80" rows="7"></textarea> 
                </td>
                <td width="159"> <div align="right">
      <input name="add" type="submit" id="add" value="Meldung speichern">
    </div></td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td>&nbsp;</td>
                <td>&nbsp; </td>
                <td><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['name']; ?>"> 
                  <input name="status" type="hidden" id="status" value="0">
    <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"></td>
              </tr>