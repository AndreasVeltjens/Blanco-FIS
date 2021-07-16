<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la46";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save1"])) or (isset($HTTP_POST_VARS["save2"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save4"])) or (isset($HTTP_POST_VARS["save5"])) or  (isset($HTTP_POST_VARS["save6"] )) or  (isset($HTTP_POST_VARS["save7"] )) or  (isset($HTTP_POST_VARS["save8"] )) or  (isset($HTTP_POST_VARS["save9"] )) ) {
 
   if (($HTTP_POST_VARS['fm6datum']=="0000-00-00")&&($HTTP_POST_VARS['fm6']==1)) {$today="now()";} else { $today=GetSQLValueString($HTTP_POST_VARS['fm6datum'], "date");}
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm6=%s, fm6datum=%s, fm6user=%s, fm6notes=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fm6'],"int"),
                       $today,
                       GetSQLValueString($HTTP_POST_VARS['fm6user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fm6notes'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
if (isset($goback)){$updateGoTo = "la46s.php";}else{
if (isset($HTTP_POST_VARS["save"])){
if (isset($qmb)){$updateGoTo = "qmb4.php";}else{$updateGoTo = "la4.php";}
}
if (isset($HTTP_POST_VARS["save1"])){$updateGoTo = "la41.php";}
if (isset($HTTP_POST_VARS["save2"])){$updateGoTo = "la42.php";}
if (isset($HTTP_POST_VARS["save3"])){$updateGoTo = "la43.php";}

if (isset($HTTP_POST_VARS["save4"])){$updateGoTo = "la44.php";}
if (isset($HTTP_POST_VARS["save5"])){$updateGoTo = "la45.php";}

if (isset($HTTP_POST_VARS["save6"])){$updateGoTo = "la46.php";}
if (isset($HTTP_POST_VARS["save7"])){$updateGoTo = "la47.php";}
if (isset($HTTP_POST_VARS["save8"])){$updateGoTo = "la48.php";}
if (isset($HTTP_POST_VARS["save9"])){$updateGoTo = "la49.php";}

}
  
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}



if ((isset($HTTP_POST_VARS["cancel"])) ) {
if (isset($goback)){$GoTo = "la46s.php?url_user=".$row_rst1['id'];}else{
$GoTo = "la4.php?url_user=".$row_rst1['id'];}
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}




mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmid='$url_fmid'";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);
$url_artikelid= $row_rst4['fmartikelid'];
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT linkfehlerartikel.lfaid, fehler.fid, fehler.fkurz, fehler.fname, fehler.lokz, linkfehlerartikel.fid, linkfehlerartikel.artikelid, linkfehlerartikel.lokz FROM fehler, linkfehlerartikel WHERE fehler.lokz=0 AND linkfehlerartikel.lokz=0 AND linkfehlerartikel.fid=fehler.fid AND linkfehlerartikel.artikelid='$url_artikelid' ORDER BY fehler.fkurz";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fehler.fname, fehler.fkurz, fehler.fid, fehler.lokz, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid, linkfehlerfehlermeldung.lffid FROM linkfehlerfehlermeldung, fehler WHERE linkfehlerfehlermeldung.lokz=0 AND linkfehlerfehlermeldung.fmid='$url_fmid' AND fehler.fid =linkfehlerfehlermeldung.fid ORDER BY fehler.fkurz";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst7 = "SELECT entscheidung.entid, entscheidung.entname, entscheidung.entmemo, entscheidungsvarianten.varid, entscheidungsvarianten.varname, entscheidungsvarianten.varbeschreibung FROM entscheidung, entscheidungsvarianten WHERE entscheidung.lokz=0 AND entscheidungsvarianten.lokz=0 AND entscheidungsvarianten.entid=entscheidung.entid ORDER BY entscheidung.entname, entscheidungsvarianten.varname";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst91 = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$url_fmid' AND fehlermeldungsdaten.entid=1 ORDER BY fehlermeldungsdaten.varname";
$rst91 = mysql_query($query_rst91, $qsdatenbank) or die(mysql_error());
$row_rst91 = mysql_fetch_assoc($rst91);
$totalRows_rst91 = mysql_num_rows($rst91);

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form1" method="POST">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="171">Aktionen W&auml;hlen:</td>
      <td width="341"> <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> </td>
      <td width="218"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          <input name="save" type="submit" id="save" value="Speichern und zur Liste">
        </div></td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><?php  include("la4.status.php");?> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3"><div align="right"></div></td>
    </tr>
  </table>
  <table width="730" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr bgcolor="#FFFFFF"> 
      <td width="172">Serienummer</td>
      <td width="393"> <div align="left">
          <input name="name" type="text" id="name" value="<?php echo $row_rst4['fmsn']; ?>" size="10" maxlength="255">
          Typ 
          <select name="select">
            <?php
do {  
?>
            <option value="<?php echo $row_rst2['artikelid']?>"<?php if (!(strcmp($row_rst2['artikelid'], $row_rst4['fmartikelid']))) {echo "SELECTED";} ?>><?php echo $row_rst2['Bezeichnung']?></option>
            <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
          </select>
          Fertigungsdatum</div></td>
      <td width="165"><div align="right"> 
          <input name="fmfd" type="text" id="fmfd2" value="<?php echo $row_rst4['fmfd']; ?>">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Debitor </td>
      <td> <input name="debitor" type="text" id="debitor" value="<?php echo $row_rst4['fm1notes']; ?>" size="50" maxlength="255"></td>
      <td><input name="fm6user" type="hidden" id="fm6user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19">Freigabe zur Bearbeitung </td>
      <td> <input <?php if (!(strcmp($row_rst4['fm0'],1))) {echo "checked";} ?> name="fm0" type="checkbox" id="fm02" value="1"> 
        &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fehleraufnahme 
        abgeschlossen</td>
      <td><input <?php if (!(strcmp($row_rst4['fm1'],1))) {echo "checked";} ?> name="fm1" type="checkbox" id="fm12" value="1"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid3" value="<?php echo $row_rst4['fmid']; ?>"></td>
    </tr>
  </table>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td width="210">Reparaturvorschlag<br> <br> </td>
      <td width="511"> <textarea name="textarea" cols="70" rows="3"><?php echo $row_rst4['fmnotes']; ?></textarea></td>
      <td width="9">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
 

<?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
<font color="#FF0000">noch keine Fehler gespeichert.</font> 
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <?php do { ?>
  <tr>  
      <td width="1">&nbsp; 
      </td>
      <td width="221" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="508" bgcolor="#FFFFFF"> 
        <?php echo $row_rst6['fkurz']; ?>- <?php echo $row_rst6['fname']; ?> </td>
  </tr>
  <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
</table>
<?php } // Show if recordset not empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td height="19">Datum Auftrag</td>
      <td><?php echo $row_rst4['fm3datum']; ?>- Auftragsnummer: <?php echo $row_rst4['sapsd']; ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="210" height="19">Datum VDE Pr&uuml;fung</td>
      <td width="476"><?php echo $row_rst4['fm4datum']; ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19">Datum Kommision </td>
      <td><?php echo $row_rst4['fm5datum']; ?>&nbsp; - Lieferung: <?php echo $row_rst4['fm5notes']; ?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="19">Sendungs-ID</td>
      <td><?php echo $row_rst4['glsversnd']; ?></td>
    </tr>
  </table>
  <br>
  <em><strong><img src="picture/fertig.gif" width="15" height="15"> Retourenabwicklung 
  erledigt</strong></em><br>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="212">Datum Warenausgang/Faktura</td>
      <td width="398"> <input name="fm6datum" type="text" id="fm2datum2" value="<?php echo $row_rst4['fm6datum']; ?>"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm6datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt erledigt: 
        <input <?php if (!(strcmp($row_rst4['fm6'],1))) {echo "checked";} ?> name="fm6" type="checkbox" id="fm43" value="1"></td>
      <td width="119">&nbsp;</td>
    </tr>
    <tr> 
      <td>Bemerkungen<br> <br> <br> <br> </td>
      <td> <textarea name="fm6notes" cols="50" id="textarea"><?php echo $row_rst4['fm6notes']; ?></textarea></td>
      <td>&nbsp; </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><p><img src="picture/feed.png" width="16" height="16"> Zustand des Ger&auml;tes/Verpackung 
          / Ger&auml;t vollst&auml;ndig (oder nur Deckel oder nur Grundk&ouml;rper, 
          mit Kabel ?)</p></td>
      <td colspan="2"><textarea name="intern" cols="70" rows="5" id="intern"><?php echo $row_rst4['intern']; ?></textarea></td>
    </tr>
    <tr> 
      <td>&nbsp; </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><p> 
          <?php 


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst42 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$row_rst4[fmartikelid] and fertigungsmeldungen.fsn=$row_rst4[fmsn] ORDER BY fertigungsmeldungen.fdatum desc";
$rst42 = mysql_query($query_rst42, $qsdatenbank) or die(mysql_error());
$row_rst42 = mysql_fetch_assoc($rst42);
$totalRows_rst42 = mysql_num_rows($rst42);




?>
          <br>
          <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Liste 
          der Fertigungsdaten:</strong></em></p>
        <?php if ($totalRows_rst42 == 0) { // Show if recordset empty ?>
        <p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur Auswahl 
          gefunden.</font></p>
        <?php } // Show if recordset empty ?> <?php if ($totalRows_rst42 > 0) { // Show if recordset not empty ?>
        <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="296">M&ouml;gliche Fertigungsdaten:</td>
            <td width="147">Status </td>
            <td width="105">User</td>
            <td width="182">Bemerkungen </td>
          </tr>
          <?php do { ?>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
              <?php echo $row_rst42['fdatum']; ?> <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst42['fdatum']; ?>"> 
              <input name="name2" type="hidden" id="name2" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
              <input name="select2" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
              <input name="debitor2" type="hidden" id="debitor2" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst42['ffrei'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
              - <img src="picture/<?php if (!(strcmp($row_rst42['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
            </td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst42['fuser']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst42['fnotes']; ?></td>
          </tr>
          <?php } while ($row_rst42 = mysql_fetch_assoc($rst42)); ?>
        </table>
        <br>
        insgesamt <?php echo $totalRows_rst42 ?> Fertigungsmeldungen gefunden.<br>
        <?php } // Show if recordset not empty ?> </td>
    </tr>
    <tr> 
      <td colspan="3"> <?php if ($totalRows_rst91 == 0) { // Show if recordset empty ?>
        <font color="#FF0000">keine Positionsdaten f&uuml;r den Kostenvoranschlag 
        vorhanden.</font> 
        <?php } // Show if recordset empty ?> <?php if ($totalRows_rst91 > 0) { // Show if recordset not empty ?>
        <table width="733" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="131">Postion</td>
            <td width="374">Beschreibung</td>
            <td width="114"><div align="right">Kosten</div></td>
            <td width="31">Lokz</td>
            <td width="83">Entscheidung.</td>
          </tr>
          <?php do { ?>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst91['varname']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(($row_rst91['varbeschreibung']),60,"<br>",255); ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst91['Kosten1']; ?> &euro;</div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst91['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz3" value="1"></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
                <input <?php if (!(strcmp($row_rst91['entid'],1))) {echo "checked";} ?> name="frei" type="checkbox" id="frei3" value="1">
              </div></td>
          </tr>
          <?php } while ($row_rst91 = mysql_fetch_assoc($rst91)); ?>
        </table>
        <?php } // Show if recordset not empty ?> </td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2">
</form>
<p>&nbsp;</p>
 <?php include("la4.rstuserst.php");?>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rst7);

mysql_free_result($rsttyp);
?>
</p>

  <?php include("footer.tpl.php"); ?>
