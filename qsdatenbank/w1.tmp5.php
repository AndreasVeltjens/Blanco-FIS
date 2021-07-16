<tr bgcolor="#CCCCFF"> 
                <td colspan="3"><img src="picture/add.gif" width="15" height="14"> 
                  <em>neue Meldung anlegen 2. Schritt</em> </td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td>&nbsp;</td>
                <td><font size="4">Fehlzeitmeldung</font></td>
                <td>&nbsp;</td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td>von:</td>
                <td><input name="von" type="text" id="von" size="10" maxlength="10" value="<?php echo $HTTP_POST_VARS['bis'] ?>"> 
                  <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'von\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
                  bis 
                  <input name="bis" type="text" id="bis" value="<?php echo $HTTP_POST_VARS['bis'] ?>" size="10" maxlength="10"> 
                  <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'bis\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script></td>
                <td><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['name']; ?>"> 
                  <input name="status" type="hidden" id="status" value="0"><input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"></td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td>Abwesenheitstext</td>
                <td><textarea name="textarea" cols="80" rows="7"></textarea></td>
                <td><div align="right">
                    <input name="add" type="submit" id="add" value="Meldung speichern">
                  </div></td>
              </tr>