<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
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
 $la = "la14";


$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["speichern"]) )) {
  $updateSQL = sprintf("UPDATE fertigungsmeldungen SET freigabeuser=%s, freigabedatum=%s, version=%s, fsn1=%s, fsn2=%s, fsn3=%s, fsn4=%s, fsonder=%s, ffrei=%s, fnotes=%s, freigabedatum=%s, pgeraet=%s, fnotes1=%s, frfid=%s, status=%s WHERE fid=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fdatum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['version'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn4'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['sonder']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['frei']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['notes'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s',time()), "text"),
                       GetSQLValueString($HTTP_POST_VARS['pgeraet'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['notes1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['frfid'], "int"),
                       "'2'",
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  $oktxt.="<br>Fertigungsdaten gespeichert. <br>";
  
  $updateGoTo = "la11.php?url_user=".$HTTP_POST_VARS['hurl_user']."&url_artikelid=".$HTTP_POST_VARS['hurl_artikelid'];
  header(sprintf("Location: %s", $updateGoTo));
  
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert=1  AND artikeldaten.artikelid='$url_artikelid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fid='$url_fid'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.fsn1, fertigungsmeldungen.fsn2, fertigungsmeldungen.fsn3, fertigungsmeldungen.fsn4, fertigungsmeldungen.fsonder, fertigungsmeldungen.ffrei, fertigungsmeldungen.fnotes, fertigungsmeldungen.change FROM fertigungsmeldungen WHERE fertigungsmeldungen.fsn='$row_rst3[fsn1]' and fertigungsmeldungen.fartikelid = '$row_rst2[stuli]'";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten WHERE artikeldaten.artikelid ='$row_rst2[stuli]'";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM `user`";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la11.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["korrigieren"])) ) {
$updateGoTo = "la12.php?url_user=".$row_rst1['id']."&url_fid=".$HTTP_POST_VARS['hurl_fid'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["wiederholung"])) ) {
$updateGoTo = "la441.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS['hurl_artikelid']."&hurl_wiederholung_id=".$HTTP_POST_VARS['hurl_wiederholung_id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <br> <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="189">Aktionen W&auml;hlen:</td>
      <td width="443"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <input name="korrigieren" type="submit" id="korrigieren" value="korrigieren"></td>
      <td width="318"> <div align="right">
          <?php  if ($row_rst3['status']==1){?>
          <p>
            <input name="speichern" type="submit" id="speichern" value="Speichern">
          </p>
          <?php }else {?>
          <input name="wiederholung" type="submit" id="wiederholung" value="Wiederholungspr&uuml;fung">
        <?php }?>
		</div>
		
		</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Ger&auml;tetyp:</td>
      <td><strong><?php echo $row_rst2['Bezeichnung']; ?> - <?php echo $row_rst2['Nummer']; ?></strong></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>Status</td>
      <td> 
        <?php if ($row_rst3['status']==1){?>
        <img src="picture/b_edit.png" width="16" height="16"> in Arbeit 
        <?php } elseif ($row_rst3['status']==2) {?>
        <img src="picture/iconReadOnly_16x16.gif" width="16" height="16"> gemeldet 
        <?php } elseif ($row_rst3['status']==0) {?>
        <img src="picture/error.gif" width="16" height="16"> gel&ouml;scht 
        <?php }else {?>
		<img src="picture/error.gif" width="16" height="16"> unklar 
        <?php }?>
		
		
      </td>
      <td> <div align="right">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
          <input name="hurl_fid" type="hidden" id="hurl_fid" value="<?php echo $row_rst3['fid']; ?>">
          <input name="hurl_wiederholung_id" type="hidden" id="hurl_wiederholung_id" value="<?php echo $row_rst3['fsn']; ?>">
          <a href="etiprogramm.php?url_user=<?php echo $url_user; ?>&hurl_fid=<?php echo $row_rst3['fid']; ?>" target="_blank">weitere 
          Statusauswahl</a></div></td>
    </tr>
    <tr> 
      <td>Seriennummer/Version: </td>
      <td><?php echo $row_rst3['fsn']; ?> - 
        <input name="version" type="text" id="version" value="<?php echo $row_rst3['version']; ?>"> 
      </td>
      <td><div align="right"><a href="pdfetikett3.php?url_bezeichnung=<?php echo $row_rst2['Bezeichnung'] ?>&url_artikelnummer=<?php echo $row_rst2['Nummer'] ?>&url_sn_ab=<?php echo $row_rst3['fsn'] ?>&url_sn_bis=<?php echo $row_rst3['fsn'] ?>" target="_blank"><img src="picture/anschreiben.gif" alt="SN-Aufkleber drucken" width="18" height="18" border="0" align="absmiddle"> 
          SN-Aufkleber</a> </div></td>
    </tr>
  </table>
  <p> 
  <hr>
  <strong>Bauteildaten</strong></p> 
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td width="191">Serienummer Heizung</td>
      <td width="439"> <input name="sn1" type="text" id="sn1" value="<?php echo $row_rst3['fsn1']; ?>"></td>
      <td width="320"> <div align="right"></div></td>
    </tr>
    <tr> 
      <td>Serienummer Regler</td>
      <td> <input name="sn2" type="text" id="sn2" value="<?php echo $row_rst3['fsn2']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Serienummer L&uuml;fter</td>
      <td> <input name="sn3" type="text" id="sn3" value="<?php echo $row_rst3['fsn3']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>sonstiges</td>
      <td><input name="sn4" type="text" id="sn4" value="<?php echo $row_rst3['fsn4']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <hr>
  <strong>Pr&uuml;fung Gesamtbaugruppe:</strong> 
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td width="193">VDE-Pr&uuml;fung i.O.</td>
      <td width="725"> <div align="left">
          <input <?php if (!(strcmp($row_rst3['ffrei'],1))) {echo "checked";} ?> name="frei" type="checkbox" id="frei" value="1">
          <img src="picture/auftrag.gif" width="20" height="14"><a href="\\cde9aw01\documents\Datensicherung ETL Pr&uuml;fberichte\">alle 
          Pr&uuml;fprotokolle anzeigen</a> &ouml;ffne Monatsordner: <img src="picture/auftrag.gif" width="20" height="14"><a href="\\cde9aw01\documents\Datensicherung ETL Pr&uuml;fberichte\results\<?php echo substr($row_rst3['fdatum'],0,7); ?>">monatl. 
          Pr&uuml;fprotokolle anzeigen</a> </div></td>
      <td width="32">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td> <input type="text" name="fdatum" value="<?php echo $row_rst3['fdatum']; ?>" size="20" maxlength="99" class="textfield" onchange="return unNullify('fdatum', '0')" tabindex="10" id="field_3_3" /> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="Kalender" href="javascript:openCalendar(\'lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci\', \'insertForm\', \'field_3_3\', \'date\')"><img class="calendar" src="./themes/darkblue_orange/img/b_calendar.png" alt="Kalender"/></a>');
                    //-->
                    </script>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Sonderfreigabe erforderlich</td>
      <td> <input <?php if (!(strcmp($row_rst3['fsonder'],1))) {echo "checked";} ?> name="sonder" type="checkbox" id="sonder" value="1">
        <a href="../../../etl/results/" target="_blank"><img src="picture/aenderung_24x24.png" width="24" height="24" border="0" align="absmiddle">Ordner 
        anzeigen</a></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Grund:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><p>Funktionstest <br>
          und Messwerte:</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p><br>
        </p>
        <p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <?php 
		
		$url_fmnotes1="Schutzleiterwiderstand [Ohm] SOLL=0,1   IST= \n";
		$url_fmnotes1=$url_fmnotes1."Isolationswiderstand [MOhm] SOLL>=0,7   IST= \n";
		$url_fmnotes1=$url_fmnotes1."Hochspannungsprüfung [1,25 kV][mA] SOLL<=5,0 mA   IST= \n";
				
		?>
      <td><textarea name="notes1" cols="100" rows="20" id="notes"><?php echo $row_rst3['fnotes1']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>RFID</td>
      <td><img src="picture/smextlink.gif" width="16" height="9"> <input name="frfid" type="text" id="frfid" value="<?php echo $row_rst3['frfid']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Signatur</td>
      <td><img src="picture/s_process.png" width="16" height="16"> <?php echo $row_rst3['signatur']; ?>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pr&uuml;fer:</td>
      <td><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst9['id'], $row_rst3['fuser']))) {echo $row_rst9['name'];}?>
        <?php
} while ($row_rst9 = mysql_fetch_assoc($rst9));
  $rows = mysql_num_rows($rst9);
  if($rows > 0) {
      mysql_data_seek($rst9, 0);
	  $row_rst9 = mysql_fetch_assoc($rst9);
  }
?>
        EDV Freigabedatum: <?php echo $row_rst3['freigabedatum']; ?> </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>letzte &Auml;nderung:</td>
      <td><?php echo $row_rst3['change']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>verwendete Pr&uuml;fger&auml;te:</td>
      <td><select name="pgeraet" id="select">
          <option value="Kompaktpr&uuml;fger&auml;t ETL X8">Kompaktpr&uuml;fger&auml;t 
          ETL X8</option>
          <option value="-------------------------">--------------------------</option>
          <option value="Hochspanungspr&uuml;fer 1 UH 28 M-E01098 und Sicherheitspr&uuml;fer RD 28 1 K-E-01099">Hochspannungspr&uuml;fer 
          1 UH 28 M-E01098 und Sicherheitspr&uuml;fer 1 RD 28 K-E-01099</option>
          <option value="Hochspanungspr&uuml;fer 2 UH 28 M-E01098 und Sicherheitspr&uuml;fer RD 28 2 K-E-01099">Hochspannungspr&uuml;fer 
          2 UH 28 M-E01098 und Sicherheitspr&uuml;fer 2 RD 28 K-E-01099</option>
          <option value="-------------------------">---------------------------</option>
          <option value="Sichtpr&uuml;fung gem&auml;&szlig; Pr&uuml;fplan">Sichtpr&uuml;fung 
          gem&auml;&szlig; Pr&uuml;fplan</option>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pr&uuml;fger&auml;te:</td>
      <td><?php echo utf8_decode($row_rst3['pgeraet']); ?><a href="../../../etl/results/dummy/" target="_blank"> <img src="picture/add.gif" width="15" height="14" border="0" align="absmiddle"> 
        Funktionskontrolle Pr&uuml;fger&auml;t anzeigen</a></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
 </form>
  <?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
  <p><strong><br>
    Retrograde St&uuml;cklistenkomponente</strong></p>
  
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCFF">
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td width="203">Komponenten-ID</td>
    <td width="432"><?php echo $row_rst4['fid']; ?> <a href="la14.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst5['artikelid']; ?>&url_fid=<?php echo $row_rst4['fid']; ?>">Fertigungsdaten 
      aufrufen</a></td>
    <td width="317"><div align="right"><img src="picture/auftrag.gif" width="20" height="14"><a href="\\cde9aw01\documents\Datensicherung ETL Pr&uuml;fberichte\results\<?php echo substr($row_rst4['fdatum'],0,7); ?>">monatl. 
        Pr&uuml;fprotokolle anzeigen</a> </div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"><img src="picture/auftrag.gif" width="20" height="14"><a href="\\cde9aw01\documents\Datensicherung ETL Pr&uuml;fberichte\">alle 
        Pr&uuml;fprotokolle anzeigen</a> </div></td>
  </tr>
  <tr> 
    <td>Komponenten-Beschreibung</td>
    <td>&nbsp;<strong><?php echo $row_rst5['Bezeichnung']; ?></strong> </td>
    <td> <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>"> 
      <input name="hurl_artikelid2" type="hidden" id="hurl_artikelid2" value="<?php echo $row_rst2['artikelid']; ?>"> 
      <input name="hurl_fid2" type="hidden" id="hurl_fid2" value="<?php echo $row_rst3['fid']; ?>"></td>
  </tr>
  <tr> 
    <td>Seriennummer/Version: </td>
    <td><input name="sn5" type="text" id="sn" value="<?php echo $row_rst4['fsn']; ?>"> 
      <input name="version" type="text" id="sn" value="<?php echo $row_rst4['version']; ?>"> 
    </td>
    <td><div align="right"><a href="pdfetikett3.php?url_bezeichnung=<?php echo $row_rst5['Bezeichnung'] ?>&url_artikelnummer=<?php echo $row_rst5['Nummer'] ?>&url_sn_ab=<?php echo $row_rst4['fsn'] ?>&url_sn_bis=<?php echo $row_rst4['fsn'] ?>" target="_blank"><img src="picture/anschreiben.gif" alt="SN-Aufkleber drucken" width="18" height="18" border="0" align="absmiddle"> 
        SN-Aufkleber</a> </div></td>
  </tr>
</table>
  <table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCFF">
    <tr> 
      <td width="204">Serienummer Heizung</td>
      <td width="426"> <input name="sn12" type="text" id="sn12" value="<?php echo $row_rst4['fsn1']; ?>"></td>
      <td width="322">&nbsp;</td>
    </tr>
    <tr> 
      <td>Serienummer Regler</td>
      <td> <input name="sn22" type="text" id="sn22" value="<?php echo $row_rst4['fsn2']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Serienummer L&uuml;fter</td>
      <td> <input name="sn32" type="text" id="sn32" value="<?php echo $row_rst4['fsn3']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>sonstiges</td>
      <td><input name="sn42" type="text" id="sn42" value="<?php echo $row_rst4['fsn4']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCFF">
    <tr> 
      <td width="204">VDE-Pr&uuml;fung i.O.</td>
      <td width="706"> <input <?php if (!(strcmp($row_rst4['ffrei'],1))) {echo "checked";} ?> name="frei2" type="checkbox" id="frei2" value="checkbox"></td>
      <td width="42">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td> <input type="text" name="fields[multi_edit][+%60upid%60+%3D+0][datum]2" value="<?php echo $row_rst4['fdatum']; ?>" size="20" maxlength="99" class="textfield" onchange="return unNullify('fdatum', '0')" tabindex="10" id="fields[multi_edit][+%60upid%60+%3D+0][datum]" /> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="Kalender" href="javascript:openCalendar(\'lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci\', \'insertForm\', \'field_3_3\', \'date\')"><img class="calendar" src="./themes/darkblue_orange/img/b_calendar.png" alt="Kalender"/></a>');
                    //-->
                    </script></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Sonderfreigabe erforderlich</td>
      <td> <input <?php if (!(strcmp($row_rst4['fsonder'],1))) {echo "checked";} ?> name="sonder2" type="checkbox" id="sonder2" value="checkbox"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Grund:</td>
      <td><textarea name="textarea" cols="50" rows="3" id="textarea"><?php echo $row_rst4['fnotes']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <?php } // Show if recordset not empty ?>
  <p><br>
    <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
    <font color="#FF0000"> keine retrograde St&uuml;cklistenkomponenten gefunden.</font> 
    <?php } // Show if recordset empty ?>
  </p>
  <p>&nbsp;</p>

  <p>&nbsp; </p>
  <?php 
  
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst200 = "SELECT * FROM fhmprueschritt, link_artikeldaten_pruef WHERE fhmprueschritt.id_p=link_artikeldaten_pruef.pruef_id and link_artikeldaten_pruef.artikelid='$row_rst2[artikelid]' ORDER BY fhmprueschritt.gruppe, fhmprueschritt.reihenfolge";
$rst200 = mysql_query($query_rst200, $qsdatenbank) or die(mysql_error());
$row_rst200 = mysql_fetch_assoc($rst200);
$totalRows_rst200 = mysql_num_rows($rst200);
  
  
  ?>
<?php if ($totalRows_rst200 > 0) { // Show if recordset not empty ?>
Liste der Pr&uuml;fungen<br>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="242"><strong><em>Bezeichnung</em></strong></td>
    <td width="213"><strong><em>Grenzwert</em></strong></td>
    <td width="114"><strong><em>Einheit</em></strong></td>
    <td width="98"><strong><em>Art</em></strong></td>
    <td width="63">&nbsp;</td>
  </tr>
  <?php 
  $gruppe="";
  do { ?>
  <?php   if ($row_rst200['gruppe']!=$gruppe){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="8" nowrap="nowrap"    bgcolor="#EEEEEE"><em><br>
      <img src="picture/s_tbl.png" width="16" height="16"> <?php echo $row_rst200['gruppe']; ?></em></td>
  </tr>
  <?php 	}?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['name']; ?></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['sollwert']; ?> 
    </td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['ergebnis']; ?></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['istwert']; ?></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"> 
        <input name="hurl_user22" type="hidden" id="hurl_user22" value="<?php echo $url_user; ?>">
        <input name="hurl_id_p" type="hidden" id="hurl_id_p" value="<?php echo $row_rst200['id_p']; ?>">
        <input name="hurl_id_ps" type="hidden" id="hurl_id_ps" value="<?php echo $row_rst200['id_ps']; ?>">
      </div></td>
  </tr>
  <?php
    $gruppe=$row_rst200['gruppe'];
   } while ($row_rst200 = mysql_fetch_assoc($rst200)); ?>
</table>
</table>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rst200 == 0) { // Show if recordset empty ?>
<font color="#FF0000">Keine Daten vorhanden. Pr&uuml;fplanung f&uuml;r den 
  Artikel nicht notwendig.</font></p>
<?php } // Show if recordset empty ?>


  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst9);

?><?php include("footer.tpl.php"); ?>

