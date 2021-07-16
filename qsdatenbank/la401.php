<?php require_once('Connections/qsdatenbank.php'); ?>
<?php   $la = "la401";

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "la40.php?url_user=".$url_user;
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}
if ($HTTP_POST_VARS['bauteil']==""){$HTTP_POST_VARS['bauteil']="bitte eingeben";}

if ((isset($HTTP_POST_VARS["weiter"])) ) {
$GoTo = "la40.php?url_user=".$url_user."&name=".$HTTP_POST_VARS[name]."&select=".$HTTP_POST_VARS[select]."&fmfd=".$HTTP_POST_VARS[fmfd];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}


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

if ($HTTP_POST_VARS[name]==""){$HTTP_POST_VARS[name]="0";}
if ($HTTP_POST_VARS[select]==""){$HTTP_POST_VARS[select]=0.1;}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstservice = "SELECT fhm.id_fhm, fhm.name, fhm.artikelid, fhm.sn, fhm.lokz FROM fhm WHERE fhm.artikelid = $HTTP_POST_VARS[select] and fhm.sn=$HTTP_POST_VARS[name] ";
$rstservice = mysql_query($query_rstservice, $qsdatenbank) or die(mysql_error());
$row_rstservice = mysql_fetch_assoc($rstservice);
$totalRows_rstservice = mysql_num_rows($rstservice);

   



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

if ($HTTP_POST_VARS[name]==""){$HTTP_POST_VARS[name]=0;}
if ($HTTP_POST_VARS[select]==""){$HTTP_POST_VARS[select]=0;}
if (isset($HTTP_POST_VARS[ubernehmen])){$HTTP_POST_VARS[fmfd]=$HTTP_POST_VARS[fmfd1];}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes, fertigungsmeldungen.fsn1, fertigungsmeldungen.fsn2, fertigungsmeldungen.fsn3, fertigungsmeldungen.fsn4 FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$HTTP_POST_VARS[select] and fertigungsmeldungen.fsn=$HTTP_POST_VARS[name] ORDER BY fertigungsmeldungen.fdatum desc";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmartikelid, fehlermeldungen.fmuser, fehlermeldungen.fm1datum, fehlermeldungen.fmsn, fehlermeldungen.fm3, fehlermeldungen.fm6, fehlermeldungen.fm1notes FROM fehlermeldungen WHERE fehlermeldungen.fmartikelid=$HTTP_POST_VARS[select] and fehlermeldungen.fmsn=$HTTP_POST_VARS[name] ORDER BY fehlermeldungen.fmdatum desc";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid,fertigungsmeldungen.version, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.fsn1, fertigungsmeldungen.fsn2, fertigungsmeldungen.fsn3, fertigungsmeldungen.fsn4 FROM fertigungsmeldungen WHERE fertigungsmeldungen.ffrei=1 AND fertigungsmeldungen.fartikelid = $HTTP_POST_VARS[select] AND fertigungsmeldungen.fsn1 like '%$HTTP_POST_VARS[bauteil]%'";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
<table width="730" border="0" cellspacing="0" cellpadding="0">
  
  
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?> 
        - Schritt 1 von 3</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="124">Aktionen W&auml;hlen:</td>
      <td width="253"> 
        <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="353">
<div align="right"><img src="picture/iconTealStar_25x25.gif" width="25" height="25"> 
          <input name="weiter" type="submit" id="weiter" value="Daten &uuml;bernehmen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td> <div align="right"></div></td>
    </tr>
  </table>
  
  

  
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>Serienummer</td>
      <td><input name="name" type="text" id="name" value="<?php echo $HTTP_POST_VARS[name]; ?>" size="10" maxlength="255">
      </td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td width="169">Typ</td>
      <td width="234"><select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst2['artikelid']?>"<?php if (!(strcmp($row_rst2['artikelid'], $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>><?php echo $row_rst2['Bezeichnung']?></option>
          <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
        </select> </td>
      <td width="327">&nbsp;</td>
    </tr>
    <tr> 
      <td>Fertigungsdatum</td>
      <td> <input name="fmfd" type="text" id="fmfd" value="<?php echo $HTTP_POST_VARS['fmfd']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fmfd\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
      <td><div align="right"> </div></td>
    </tr>
    <tr>
      <td>Bauteilnummer:</td>
      <td><input name="bauteil" type="text" id="bauteil" value="<?php echo $HTTP_POST_VARS['bauteil']; ?>"></td>
      <td>
<div align="right">
          <input name="suche" type="submit" id="suche2" value="Fertigungsdaten suchen">
        </div></td>
    </tr>
    <tr> 
      <td>User-ID</td>
      <td> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
  
  
<p><em>Ergebnis St&uuml;cklistenaufl&ouml;sung:</em></p>

  <?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>

<p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur Auswahl gefunden.</font></p>
<?php } // Show if recordset empty ?>
<br>
<?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
<table width="1060" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="296"><strong>M&ouml;gliche Fertigungsdaten:</strong></td>
    <td width="54">Status </td>
    <td width="79">User</td>
    <td width="156">Version</td>
    <td width="156">Bauteile</td>
    <td width="319">Bemerkungen </td>
  </tr>
  <?php do { ?>
  <form name="form2" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="ubernehmen2" type="submit" id="ubernehmen2" value="&uuml;bernehmen"> 
        <?php echo $row_rst6['fdatum']; ?> - <strong><?php echo $row_rst6['fsn']; ?></strong> <input name="fmfd" type="hidden" id="fmfd" value="<?php echo $row_rst6['fdatum']; ?>"> 
        <input name="name" type="hidden" id="name" value="<?php echo $row_rst6['fsn']; ?>"> 
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
        <input name="bauteil" type="hidden" id="bauteil" value="<?php echo $HTTP_POST_VARS['bauteil']; ?>"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst6['ffrei'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
        - <img src="picture/<?php if (!(strcmp($row_rst6['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst6['fuser']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst6['version']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst6['fsn1']; ?>- <?php echo $row_rst6['fsn2']; ?>- <?php echo $row_rst6['fsn3']; ?>- <?php echo $row_rst6['fsn4']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst6['fnotes']; ?> - <a href="la14.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst6['fartikelid']; ?>&url_fid=<?php echo $row_rst6['fid']; ?>" target="_blank">Fertigungsdaten 
        an zeigen</a></td>
    </tr>
  </form>
  <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
</table>
<br>
insgesamt <?php echo $totalRows_rst6 ?> Fertigungsmeldungen gefunden. 
<?php } // Show if recordset not empty ?>
<p><br>
  <em>Fertigungsdaten</em>:<br>
  <input type="hidden" name="MM_insert" value="form1">
  <br>
</p>
<?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur Auswahl gefunden.</font></p>
  <?php } // Show if recordset empty ?>

  <br>
  <?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="296"><strong>M&ouml;gliche Fertigungsdaten:</strong></td>
    <td width="54">Status </td>
    <td width="79">User</td>
    <td width="156">Bauteile</td>
    <td width="319">Bemerkungen </td>
  </tr>
  <?php do { ?>
  <form name="form2" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="ubernehmen" type="submit" id="ubernehmen" value="&uuml;bernehmen"> 
        <?php echo $row_rst4['fdatum']; ?> <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst4['fdatum']; ?>"> 
        <input name="name" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>">
        <input name="debitor2" type="hidden" id="debitor2" value="<?php echo $HTTP_POST_VARS['debitor']?>"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst4['ffrei'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
        - <img src="picture/<?php if (!(strcmp($row_rst4['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst4['fuser']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst4['fsn1']; ?>- <?php echo $row_rst4['fsn2']; ?>- <?php echo $row_rst4['fsn3']; ?>- <?php echo $row_rst4['fsn4']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst4['fnotes']; ?> - <a href="la14.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst4['fartikelid']; ?>&url_fid=<?php echo $row_rst4['fid']; ?>" target="_blank">Fertigungsdaten 
        an zeigen</a></td>
    </tr>
  </form>
  <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
</table>

<br>
insgesamt <?php echo $totalRows_rst4 ?> Fertigungsmeldungen gefunden. 
<?php } // Show if recordset not empty ?>
  
  <?php if ($totalRows_rst5 == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine m&ouml;glichen Reparaturdaten oder Fehlermeldungen zur Auswahl gefunden.</font></p>
  <?php } // Show if recordset empty ?>
    
    <?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="296"><strong>M&ouml;gliche Reparaturdaten:</strong></td>
    <td width="147">Status </td>
    <td width="105">User</td>
    <td width="182">Bemerkungen </td>
  </tr>
  <?php do { ?><form name="form2" method="post" action="">
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');">  
    <td bgcolor="#EEEEEE" nowrap="nowrap"><input name="ubernehmen" type="submit" id="ubernehmen" value="&uuml;bernehmen"> 
      <?php echo $row_rst5['fm1datum']; ?> <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst5['fm1datum']; ?>"> 
      <input name="name" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>">
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>">
        <input name="debitor" type="hidden" id="debitor" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst5['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
      - <img src="picture/<?php if (!(strcmp($row_rst5['fm3'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
    </td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst5['fmuser']; ?></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst5['fm1notes']; ?></td>
  </tr>
</form>
  <?php } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
</table>

<p> insgesamt <?php echo $totalRows_rst5 ?> Fertigungsmeldungen gefunden. </p>
<p>&nbsp;</p>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rstservice > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="237"><strong>M&ouml;gliche Serviceger&auml;te:</strong></td>
    <td width="154">Status </td>
    <td width="152">Seriennummer</td>
    <td width="187">Bemerkungen </td>
  </tr>
  <?php do { ?>
  <form name="form2" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td bgcolor="#EEEEEE" nowrap="nowrap">&nbsp; 
        <?php echo $row_rstservice['name']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rstservice['lokz'],0))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
        - <img src="picture/<?php if (!(strcmp($row_rst5['fm3'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rstservice['sn']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="fhm121.php?url_user=<?php echo $row_rst1['id']; ?>&url_id_fhm=<?php echo $row_rstservice['id_fhm']; ?>&goback=la40" target="_blank">weitere 
        Informationen anzeigen</a></td>
    </tr>
  </form>
  <?php } while ($row_rstservice = mysql_fetch_assoc($rstservice)); ?>
</table>
<p> insgesamt <?php echo $totalRows_rstservice ?> Serviceger&auml;te gefunden. </p>
<p>&nbsp;</p>
<?php } // Show if recordset not empty ?>
   <?php if ($totalRows_rstservice == 0) { // Show if recordset empty ?>
  
<p><font color="#FF0000">keine m&ouml;glichen Serviceger&auml;te gefunden oder 
  noch nicht angelegt.</font></p>
  <?php } // Show if recordset empty ?>
<?php

  
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);
mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rstservice);
?>
</p>

  <?php include("footer.tpl.php"); ?>
