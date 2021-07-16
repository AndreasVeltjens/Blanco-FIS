<?php require_once('Connections/qsdatenbank.php'); ?>
<?php function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = (($theValue != "") ? "'" . $theValue . "'" : "NULL");
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
   $la = "la41";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
	if (isset($goback)){$GoTo = $goback."?url_user=".$row_rst1['id'];}
	else{
	$GoTo = "la4.php?url_user=".$row_rst1['id'];
	}
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["cancel2"])) or (isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save1"])) or (isset($HTTP_POST_VARS["save2"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save4"])) or (isset($HTTP_POST_VARS["save5"])) or  (isset($HTTP_POST_VARS["save6"] ))  ) {
if ($HTTP_POST_VARS['eingangsdatum']==""){$HTTP_POST_VARS['eingangsdatum']==date("Y-m-d",time());}
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm1=%s, materialnummer=%s, reklamenge=%s, lokz=%s, fm1user=%s, fm1notes=%s, fm11notes=%s, fm12notes=%s, fm0=%s, fmnotes=%s, fm1datum=%s WHERE fmid=%s",
                       
                       GetSQLValueString($HTTP_POST_VARS['fm1'],"int"),
                      
					  GetSQLValueString($HTTP_POST_VARS['materialnummer'],"text"),
					  GetSQLValueString($HTTP_POST_VARS['reklamenge'],"text"),
					  GetSQLValueString($HTTP_POST_VARS['lokz'],"int"),
					  
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['debitor'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['endkunde'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['kommision'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fm0'],"int"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['eingangsdatum'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
	
	/* bei oldset werden Daten in separates Feld geschrieben */
	/* oldset =0 Fehleraufnahme abgeschlossen, und fm=0 */
	if (($HTTP_POST_VARS["oldset"]=="0")) {
  $updateSQL = sprintf("UPDATE fehlermeldungen SET  fm1text=%s, fm1bnotes=%s WHERE fmid=%s",

                       GetSQLValueString($HTTP_POST_VARS['fm3user'],"text")." - ".GetSQLValueString($HTTP_POST_VARS['eingangsdatum'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

/* ENDE oldset */

		if (isset($goback)){$updateGoTo = $goback."?url_user=".$row_rst1['id'];}
		else{
		if (isset($HTTP_POST_VARS["save"])){
		if (isset($qmb)){$updateGoTo = "qmb4.php";}else{$updateGoTo = "la4.php";}
		}
		if (isset($HTTP_POST_VARS["save2"])){$updateGoTo = "la42.php";}
		if (isset($HTTP_POST_VARS["save3"])){$updateGoTo = "la43.php";}
		if (isset($HTTP_POST_VARS["save4"])){$updateGoTo = "la44.php";}
		if (isset($HTTP_POST_VARS["save5"])){$updateGoTo = "la45.php";}
		if (isset($HTTP_POST_VARS["save6"])){$updateGoTo = "la46.php";}
		if (isset($HTTP_POST_VARS["cancel2"])){$updateGoTo = "la402.php";}
		}
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS['hurl_lffid'])) && ($HTTP_POST_VARS['hurl_lffid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM linkfehlerfehlermeldung WHERE lffid=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_lffid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
}



if ((isset($HTTP_POST_VARS["add"]))) {
  $insertSQL = sprintf("INSERT INTO linkfehlerfehlermeldung (fmid, fid, lffuser) VALUES (%s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}





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
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

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
$query_rst7 = "SELECT entscheidung.entid, entscheidung.entname, entscheidung.lokz, entscheidung.sapkst, artikeldaten_entscheidung_link.id_ent FROM entscheidung, artikeldaten_entscheidung_link WHERE artikeldaten_entscheidung_link.id_ent=entscheidung.entid AND artikeldaten_entscheidung_link.lokz=0 AND entscheidung.lokz = 0 AND artikeldaten_entscheidung_link.id_artikel='$row_rst4[fmartikelid]' ORDER BY entscheidung.entname";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstkunden = "SELECT * FROM kundendaten WHERE kundendaten.lokz=0 ORDER BY kundendaten.firma";
$rstkunden = mysql_query($query_rstkunden, $qsdatenbank) or die(mysql_error());
$row_rstkunden = mysql_fetch_assoc($rstkunden);
$totalRows_rstkunden = mysql_num_rows($rstkunden);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst42 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid,fertigungsmeldungen.version, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$row_rst4[fmartikelid] and fertigungsmeldungen.fsn=$row_rst4[fmsn] ORDER BY fertigungsmeldungen.fdatum desc";
$rst42 = mysql_query($query_rst42, $qsdatenbank) or die(mysql_error());
$row_rst42 = mysql_fetch_assoc($rst42);
$totalRows_rst42 = mysql_num_rows($rst42);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst52 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmartikelid, fehlermeldungen.fmuser, fehlermeldungen.fm1datum, fehlermeldungen.fmsn, fehlermeldungen.fm3, fehlermeldungen.fm6, fehlermeldungen.fm1notes, fehler.fid, fehler.fkurz, fehler.fname, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid FROM fehlermeldungen, fehler, linkfehlerfehlermeldung WHERE fehlermeldungen.fmartikelid=$row_rst4[fmartikelid] and fehlermeldungen.fmsn=$row_rst4[fmsn] AND linkfehlerfehlermeldung.fmid AND fehlermeldungen.fmid=linkfehlerfehlermeldung.fmid and linkfehlerfehlermeldung.fid =fehler.fid ORDER BY fehlermeldungen.fmdatum desc";
$rst52 = mysql_query($query_rst52, $qsdatenbank) or die(mysql_error());
$row_rst52 = mysql_fetch_assoc($rst52);
$totalRows_rst52 = mysql_num_rows($rst52);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$url_fmid' ORDER BY fehlermeldungsdaten.varname";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM entscheidung WHERE entscheidung.entid='$row_rst4[fm2variante]'";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst10 = "SELECT * FROM `komponenten_asm_vorlagen` WHERE (`lokz` =0 and `artikelid`=0) or (`lokz` =0 and `artikelid`='$row_rst4[fmartikelid]')ORDER BY komponenten_asm_vorlagen.gruppe, komponenten_asm_vorlagen.name";
$rst10 = mysql_query($query_rst10, $qsdatenbank) or die(mysql_error());
$row_rst10 = mysql_fetch_assoc($rst10);
$totalRows_rst10 = mysql_num_rows($rst10);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst91 = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$url_fmid' AND fehlermeldungsdaten.entid=1 ORDER BY fehlermeldungsdaten.varname";
$rst91 = mysql_query($query_rst91, $qsdatenbank) or die(mysql_error());
$row_rst91 = mysql_fetch_assoc($rst91);
$totalRows_rst91 = mysql_num_rows($rst91);


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<link href="css/print.css" rel="stylesheet" type="text/css">





<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  <?php  include("la4q.status.php");?>
  <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?> 
        - Schritt 3 von 3</strong></td>
    </tr>
    <tr> 
      <td colspan="3"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="152">Aktionen W&auml;hlen:</td>
      <td width="241"> <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> </td>
      <td width="337"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          <input name="save" type="submit" id="save" value="Speichern und zur Liste">
        </div></td>
    </tr>
  </table>
  <table width="600" border="0" cellpadding="1" cellspacing="0">
    <tr> 
      <td width="141"> <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <td width="585"> <table width="595" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
          <tr bgcolor="#FFFFFF"> 
            <td height="24">Materialnummer</td>
            <td><input name="materialnummer" type="text" id="materialnummer2" value="<?php echo $row_rst4['materialnummer']; ?>" size="20" maxlength="255"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="24">Reklamierte Menge</td>
            <td><input name="reklamenge" type="text" id="reklamenge2" value="<?php echo $row_rst4['reklamenge']; ?>" size="12" maxlength="255"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="24">L&ouml;schkennzeichen</td>
            <td><input <?php if (!(strcmp($row_rst4['fm1'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="140" height="24"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
              Debitor /H&auml;ndler/ Endkunde</td>
            <td width="455"> <input name="debitor" type="text" id="debitor3" value="<?php echo $row_rst4['fm1notes']; ?>" size="50" maxlength="255"> 
              <input name="fm3user" type="hidden" id="fm3user3" value="<?php echo $row_rst1['id']; ?>"> 
              <input name="hurl_user2" type="hidden" id="hurl_user23" value="<?php echo $row_rst1['id']; ?>"> 
            </td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td><img src="picture/versandlabel.jpg" width="19" height="20">Verlabel 
              (GLS-Nr.)/FAUF-Nr.</td>
            <td> <input name="endkunde" type="text" id="endkunde2" value="<?php echo $row_rst4['fm11notes']; ?>" size="50" maxlength="255"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td>Kommision</td>
            <td> <input name="kommision" type="text" id="kommision2" value="<?php echo $row_rst4['fm12notes']; ?>" size="50" maxlength="255"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td>Eingangsdatum</td>
            <td><input name="eingangsdatum" type="text" id="eingangsdatum2" value="<?php echo $row_rst4['fm1datum']; ?>" size="20" maxlength="10"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'eingangsdatum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
              jjjj-mm-tt </td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="19">Freigabe zur Bearbeitung </td>
            <td> <input <?php if (!(strcmp($row_rst4['fm0'],1))) {echo "checked";} ?> name="fm0" type="checkbox" id="fm0" value="1"> 
              &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <input name="oldset" type="hidden" id="oldset3" value="<?php echo $row_rst4['fm1']; ?>">
              abgeschlossen 
              <input <?php if (!(strcmp($row_rst4['fm1'],1))) {echo "checked";} ?> name="fm1" type="checkbox" id="fm14" value="1"> 
              <input name="hurl_fmid2" type="hidden" id="hurl_fmid23" value="<?php echo $row_rst4['fmid']; ?>"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="19"><p>Fehlerbeschreibung</p>
              <p>Reparaturvorschlag</p></td>
            <td><textarea name="textarea" cols="70" rows="5"><?php echo $row_rst4['fmnotes']; ?></textarea></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="19">Schutzleiterwiderstand</td>
            <td><?php echo $row_rst4['schutzleiter']; ?> Ohm</td>
          </tr>
        </table>
        <p> <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Liste 
          der Fehler:</strong></em><br>
          <input type="hidden" name="MM_insert2" value="form1">
          <?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
          <font color="#FF0000">noch keine Fehler gespeichert.</font> 
          <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
        </p>
        <table width="600" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
          <?php do { ?>
          <tr> 
            <td width="8" height="21">&nbsp; 
            </td>
            <td width="94">&nbsp;</td>
            <td width="628"> 
              <p><?php echo $row_rst6['fkurz']; ?>- <?php echo $row_rst6['fname']; ?> 
                <input name="hurl_fid2" type="hidden" id="hurl_fid2" value="<?php echo $row_rst6['fid']; ?>">
                <input name="hurl_user4" type="hidden" id="hurl_user4" value="<?php echo $row_rst1['id']; ?>">
                <input name="hurl_fmid4" type="hidden" id="hurl_fmid4" value="<?php echo $row_rst4['fmid']; ?>">
                <input name="hurl_lffid2" type="hidden" id="hurl_lffid2" value="<?php echo $row_rst6['lffid']; ?>">
                <?php if ($row_rst6['fid']==21){ ?>
                <img src="picture/s_host.png"> <a href="la44.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst4['fmid']; ?>">VDE-Prüfung 
                erstellen</a> 
                <?php } ?>
            </td>
          </tr>
          <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
        </table>
        <?php }  ?>
        <br> <em><strong><img src="picture/s_tbl.png" width="16" height="16"> 
        Liste der Fertigungsdaten:</strong></em> <?php if ($totalRows_rst42 == 0) { // Show if recordset empty ?>
        <p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur Auswahl 
          gefunden.</font></p>
        <?php } // Show if recordset empty ?> <?php if ($totalRows_rst42 > 0) { // Show if recordset not empty ?>
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="296">M&ouml;gliche Fertigungsdaten:</td>
            <td width="147">Version</td>
            <td width="147">Status </td>
            <td width="105">User</td>
            <td width="182">Bemerkungen </td>
          </tr>
          <?php do { ?>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
              <?php echo $row_rst42['fdatum']; ?> <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst42['fdatum']; ?>"> 
              <input name="name" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
              <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
              <input name="debitor2" type="hidden" id="debitor2" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst42['version']; ?></td>
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
        <?php } // Show if recordset not empty ?> <br> <em><strong><img src="picture/s_tbl.png" width="16" height="16"> 
        Liste der Reparaturen/ Retourenabwicklungen:</strong></em> <?php if ($totalRows_rst52 == 0) { // Show if recordset empty ?>
        <p><font color="#FF0000">keine m&ouml;glichen Reparaturdaten oder Fehlermeldungen 
          zur Auswahl gefunden.</font></p>
        <?php } // Show if recordset empty ?> <?php if ($totalRows_rst52 > 0) { // Show if recordset not empty ?>
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="221">M&ouml;gliche Reparaturdaten:</td>
            <td width="73">Status </td>
            <td width="193">Fehler</td>
            <td width="90">FMID</td>
            <td width="90">User</td>
            <td width="243">Bemerkungen </td>
          </tr>
          <?php do { ?>
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td bgcolor="#EEEEEE" nowrap="nowrap"> <?php echo $row_rst52['fm1datum']; ?> 
              <input name="fmfd13" type="hidden" id="fmfd13" value="<?php echo $row_rst52['fm1datum']; ?>"> 
              <input name="name3" type="hidden" id="name3" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
              <input name="select3" type="hidden" id="select3" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
              <input name="debitor22" type="hidden" id="debitor22" value="<?php echo $HTTP_POST_VARS['debitor']?>"> 
              <?php if (($row_rst52['fmid']) == ($row_rst4['fmid'])) {echo "(in Bearbeitung)";} ?>
            </td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst52['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
              - <img src="picture/<?php if (!(strcmp($row_rst52['fm3'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
            </td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst52['fkurz']; ?> 
              - <?php echo $row_rst52['fname']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="la42.php?url_user=<?php echo $url_user ;?>&url_fmid=<?php echo $row_rst52['fmid']; ?>" target="_blank"><?php echo $row_rst52['fmid']; ?></a></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst52['fmuser']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst52['fm1notes']; ?></td>
          </tr>
          <?php } while ($row_rst52 = mysql_fetch_assoc($rst52)); ?>
        </table>
        <p> insgesamt <?php echo $totalRows_rst52 ?> Reparaturmeldungen gefunden. <br>
          (Es werden nur Meldungen mit echten Fehlerzuordnungen angezeigt.) </p>
        <p>&nbsp; </p>
        <?php } // Show if recordset not empty ?> 
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="200">Entscheidung: Prozess</td>
            <td><select name="fm2variante" id="select">
                <option value="0" <?php if (!(strcmp(0, $row_rst4['fm2variante']))) {echo "SELECTED";} ?>>keine 
                Angabe</option>
                <?php
do {  ?>
                <?php echo $row_rst7['entname']."- ".$row_rst7['varname']." - ".substr($row_rst7['varbeschreibung'],0,50)." - ".$row_rst7['sapkst']?> 
                <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
                <?php
do {  
?>
                <option value="<?php echo $row_rst7['entid']?>"<?php if (!(strcmp($row_rst7['entid'], $row_rst4['fm2variante']))) {echo "SELECTED";} ?>><?php echo $row_rst7['entname']?></option>
                <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
              </select> <input name="oldfm2variante" type="hidden" id="oldfm2variante" value="<?php echo $row_rst4['fm2variante']; ?>"> 
            </td>
          </tr>
          <tr> 
            <td>SAP-SD-Nummer / Kostenstelle</td>
            <td> <input name="sapsd" type="text" id="sapsd" value="<?php echo $row_rst4['sapsd']; ?>"> 
              <select name="sapkst" id="sapkst">
                <option value="0" <?php if (!(strcmp(0, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>Vorgabewert</option>
                <option value="61100" <?php if (!(strcmp(61100, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61100 
                Fertigung BPT</option>
                <option value="61612" <?php if (!(strcmp(61612, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61612 
                GWL</option>
                <option value="61621" <?php if (!(strcmp(61621, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61621 
                Kulanz</option>
                <option value="61521" <?php if (!(strcmp(61521, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61521 
                F&E Leipzig</option>
                <option value="61821" <?php if (!(strcmp(61821, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61821 
                Verschrottung BPT</option>
                <option value="61611" <?php if (!(strcmp(61611, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61611 
                kostenpflichtige Reparatur</option>
              </select> <div align="right"> </div></td>
          </tr>
          <tr> 
            <td>Sonderbehandlungskennzeichen</td>
            <td width="366"> <select name="vkz" id="vkz">
                <option value="0" <?php if (!(strcmp(0, $row_rst4['vkz']))) {echo "SELECTED";} ?>>keine</option>
                <option value="1" <?php if (!(strcmp(1, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Verschrottung</option>
                <option value="2" <?php if (!(strcmp(2, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Sonderbehandlung</option>
                <option value="3" <?php if (!(strcmp(3, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Servicetüre</option>
              </select> <?php echo ($row_rst4['fm1datum']-$row_rst4['fmfd'])." Jahre alt"; ?></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><input name="hurl_fmid4" type="hidden" id="hurl_fmid4" value="<?php echo $row_rst4['fmid']; ?>"> 
              <input name="hurl_user4" type="hidden" id="hurl_user4" value="<?php echo $row_rst1['id']; ?>"> 
              <input name="fm2user2" type="hidden" id="fm2user2" value="<?php echo $row_rst1['id']; ?>"></td>
          </tr>
          <tr> 
            <td>Datum Kostenvoranschlag</td>
            <td> <input name="fm2datum" type="text" id="fm2datum2" value="<?php echo $row_rst4['fm2datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm2datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
              jjjj-mm-tt gedruckt: 
              <input <?php if (!(strcmp($row_rst4['fm2'],1))) {echo "checked";} ?> name="fm2" type="checkbox" id="fm22" value="1"> 
            </td>
          </tr>
          <tr> 
            <td><p>Bemerkungen zur Reparartur</p>
              <p>&nbsp;</p>
              <p><br>
              </p></td>
            <td> <textarea name="fm2notes" cols="50" rows="4" id="textarea"><?php echo $row_rst4['fm2notes']; ?></textarea></td>
          </tr>
          <tr> 
            <td colspan="2">SAP-Kundennummer:<img src="picture/btnHelp.gif" alt="Kundennummer wird bei Anlage einer Retourenabwicklung automatisch gelsen, sofern Daten vorhanden sind." width="16" height="16"> 
              <input name="debitorennummer" type="text" id="debitorennummer" value="<?php echo $row_rst4['debitorennummer']; ?>"> 
            </td>
          </tr>
        </table>
        <p><br>
          <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Kopfdaten 
          zur Kostenkl&auml;rung:</strong></em> </p>
        <table width="600" height="205" border="0" cellpadding="0" cellspacing="0">
          <tr bgcolor="#CCCCFF"> 
            <td width="190">Anschrift</td>
            <td width="540"><textarea name="rechnung" cols="45"><?php echo ($row_rst4['rechnung']); ?></textarea></td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Ansprechpartner</td>
            <td><input name="ansprechpartner" type="text" id="ansprechpartner" value="<?php echo $row_rst4['ansprechpartner']; ?>" size="40"></td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Anrede</td>
            <td><input name="anrede" type="text" id="anrede" value="<?php echo $row_rst4['anrede']; ?>" size="40"></td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Stra&szlig;e</td>
            <td><input name="rstrasse" type="text" id="rstrasse" value="<?php echo ($row_rst4['rstrasse']); ?>" size="40"></td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td height="24">PLZ</td>
            <td><input name="rplz" type="text" id="rplz" value="<?php echo ($row_rst4['rplz']); ?>"></td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Ort</td>
            <td><input name="rort" type="text" id="rort" value="<?php echo ($row_rst4['rort']); ?>" size="40"></td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Land</td>
            <td><input name="rland" type="text" id="rland" value="<?php echo ($row_rst4['rland']); ?>"></td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td><div align="left">Rechnung und Lieferung gleich </div></td>
            <td><input type="checkbox" name="checkbox" value="1">
              Faxnummer: 
              <input name="rfax" type="text" id="rfax" value="<?php echo ($row_rst4['faxnummer']); ?>"></td>
          </tr>
        </table>
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="190">Anschrift</td>
            <td width="540"><textarea name="lieferung" cols="45"><?php echo ($row_rst4['lieferung']); ?></textarea></td>
          </tr>
          <tr> 
            <td>Stra&szlig;e</td>
            <td><input name="lstrasse" type="text" id="lstrasse2" value="<?php echo ($row_rst4['lstrasse']); ?>" size="40"></td>
          </tr>
          <tr> 
            <td>PLZ</td>
            <td><input name="lplz" type="text" id="lplz3" value="<?php echo ($row_rst4['lplz']); ?>"></td>
          </tr>
          <tr> 
            <td>Ort</td>
            <td><input name="lort" type="text" id="lort2" value="<?php echo ($row_rst4['lort']); ?>" size="40"></td>
          </tr>
          <tr> 
            <td>Land</td>
            <td><input name="lland" type="text" id="lland2" value="<?php echo ($row_rst4['lland']); ?>"></td>
          </tr>
        </table>
        <p><br>
          <?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
          <font color="#FF0000">keine Positionsdaten vorhanden.</font> 
          <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>
        </p>
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="143">Postion</td>
            <td width="308">Beschreibung</td>
            <td width="94">Kosten</td>
            <td width="31">Lokz</td>
            <td width="30">Ents.</td>
            <td width="124"><div align="right">Aktion</div></td>
          </tr>
          <?php do { ?>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst9['varname']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(($row_rst9['varbeschreibung']),60,"<br>",255); ?> 
              <input name="hurl_fmid3" type="hidden" id="hurl_fmid2" value="<?php echo $row_rst4['fmid']; ?>"> 
              <input name="hurl_user3" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>"> 
              <input name="fm2user" type="hidden" id="fm2user" value="<?php echo $row_rst1['id']; ?>"></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst9['Kosten1']; ?> &euro;</td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst9['lokz'],1))) {echo "checked";} ?> name="lokz2" type="checkbox" id="lokz3" value="1"></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst9['entid'],1))) {echo "checked";} ?> name="frei" type="checkbox" id="frei3" value="1"></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
                <input name="hurl_varid" type="hidden" id="hurl_fmid_varid3" value="<?php echo $row_rst9['varid']; ?>">
                <input name="hurl_user3" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>">
                <img src="picture/b_edit.png" width="16" height="16"> 
                <input name="editpos" type="submit" id="editpos" value="Bearbeiten">
                <img src="picture/b_drop.png" width="16" height="16"> 
                <input name="delpos" type="submit" id="delpos3" value="l&ouml;schen">
              </div></td>
          </tr>
          <?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?>
        </table>
        <?php }?>
        <p><em><strong><img src="picture/s_tbl.png" width="16" height="16"> Kopfdaten 
          zur Reparaturfreigabe:</strong></em> </p>
        <p>
		
		<?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$url_fmid' ORDER BY fehlermeldungsdaten.varname";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);
?>
          <?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
          <font color="#FF0000">keine Positionsdaten vorhanden.</font> 
          <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>
        </p>
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="120">Postion</td>
            <td width="205">Beschreibung</td>
            <td width="177">Kosten</td>
            <td width="37">Lokz</td>
            <td width="83">Entscheidung.</td>
            <td width="108"><div align="right"><strong>Aktion</strong></div></td>
          </tr>
          <?php do { ?>
          <tr> 
            <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst9['varname']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(($row_rst9['varbeschreibung']),60,"<br>",255); ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst9['Kosten1']; ?> 
              &euro;</td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst9['lokz'],1))) {echo "checked";} ?> name="lokz3" type="checkbox" id="lokz2" value="1"></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="entid" type="checkbox" id="entid"  value="1" <?php if (!(strcmp($row_rst9['entid'],1))) {echo "checked";} ?>></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
                <input name="hurl_varid2" type="hidden" id="hurl_varid" value="<?php echo $row_rst9['varid']; ?>">
                <input name="hurl_user5" type="hidden" id="hurl_user5" value="<?php echo $row_rst1['id']; ?>">
                <input name="Entscheidung" type="submit"  value="Auswahl">
              </div></td>
          </tr>
          <?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?>
		  <?php }?>
        </table>
        <p>&nbsp;</p>
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td bgcolor="#FFCC00"><em><strong><img src="picture/s_host.png" width="16" height="16"> 
              Reparatur</strong></em></td>
            <td width="207" bgcolor="#FFCC00" ><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst4['fmartikelid']; ?>&url_sn_ab=<?php echo $row_rst4['fmsn']; ?>&url_sn_bis=<?php echo $row_rst4['fmsn']; ?>&rep=1" target="_blank"><img src="picture/rules.gif" alt="Typenschild drucken" width="13" height="18" border="0" align="absmiddle"> 
              Typenschild drucken </a> </td>
            <td bgcolor="#FFCC00">&nbsp;</td>
          </tr>
          <tr> 
            <td width="190" bgcolor="#FFCC00">Datum VDE Pr&uuml;fung</td>
            <td bgcolor="#FFCC00" > <input name="fm4datum" type="text" id="fm4datum2" value="<?php echo $row_rst4['fm4datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm4datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script> <br> </td>
            <td width="333" bgcolor="#FFCC00"><div align="right"> </div></td>
          </tr>
          <tr bgcolor="#FFCC00"> 
            <td colspan="2"><img src="picture/b_docs.png" width="16" height="16"> 
              Hinweis: Ger&auml;te, die einen neuen Ger&auml;te typ erhalten bitte 
              als Neuger&auml;te mit dem Hinweis auf diese Retoure fertigmelden. 
              -&gt; <a href="la1.php?url_user=<?php echo $url_user ?>" target="_blank">Fertigungsmeldung 
              erstellen</a></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" bgcolor="#FFCC00"><p> 
                <?php 


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst42 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$row_rst4[fmartikelid] and fertigungsmeldungen.fsn=$row_rst4[fmsn] ORDER BY fertigungsmeldungen.fdatum desc";
$rst42 = mysql_query($query_rst42, $qsdatenbank) or die(mysql_error());
$row_rst42 = mysql_fetch_assoc($rst42);
$totalRows_rst42 = mysql_num_rows($rst42);




?>
                <br>
                <em><strong><img src="picture/s_tbl.png" width="16" height="16"> 
                Liste der Fertigungsdaten:</strong></em></p>
              <?php if ($totalRows_rst42 == 0) { // Show if recordset empty ?>
              <p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur 
                Auswahl gefunden.</font></p>
              <?php } // Show if recordset empty ?>
              <?php if ($totalRows_rst42 > 0) { // Show if recordset not empty ?>
              <table width="600
			  " border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="296">M&ouml;gliche Fertigungsdaten:</td>
                  <td width="147">Status </td>
                  <td width="105">User</td>
                  <td width="182">Bemerkungen </td>
                </tr>
                <?php do { ?>
                <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                  <td    bgcolor="#EEEEEE" nowrap="nowrap"> <?php echo $row_rst42['fdatum']; ?> 
                    <input name="fmfd12" type="hidden" id="fmfd122" value="<?php echo $row_rst42['fdatum']; ?>"> 
                    <input name="name2" type="hidden" id="name22" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
                    <input name="select2" type="hidden" id="select22" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
                    <input name="debitor3" type="hidden" id="debitor32" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
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
              <?php } // Show if recordset not empty ?>
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td><em> <strong><img src="picture/sap.png" width="16" height="16"> 
              R&uuml;ckmeldung</strong></em></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>Arbeitszeit:</td>
            <td> <input name="fm4notes" value="<?php echo $row_rst4['fm4notes']; ?>" size="10">
              min</td>
            <td><img src="picture/b_docs.png" width="16" height="16"> Die Reparaturzeit 
              incl. Fehleraufnahme in min.</td>
          </tr>
        </table>
        <input type="hidden" name="MM_update2" value="form2"> <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr bgcolor="#CCCCFF"> 
            <td width="190">Material ausw&auml;hlen:<img src="picture/help2.gif" alt="Materialentnahme f&uuml;r R&uuml;ckmeldungen" width="16" height="16"></td>
            <td > <select name="komponente" id="komponente">
                <?php
do {  
?>
                <option value="<?php echo $row_rst8['kompnr']?>"<?php if (!(strcmp($row_rst8['kompnr'], $HTTP_POST_VARS['komponente']))) {echo "SELECTED";} ?>><?php echo  $row_rst8['kompnr']." - ".($row_rst8['kompname']);?></option>
                <?php
} while ($row_rst8 = mysql_fetch_assoc($rst8));
  $rows = mysql_num_rows($rst8);
  if($rows > 0) {
      mysql_data_seek($rst8, 0);
	  $row_rst8 = mysql_fetch_assoc($rst8);
  }
?>
              </select>
              Anzahl: 
              <input name="anzahl" type="text" id="anzahl" value="1" size="5">
              Lagerort: 
              <input name="lagerort" type="text" id="lagerort" value="<?php echo $HTTP_POST_VARS['lagerort']; ?>" size="5" maxlength="4"></td>
            <td width="156"> <div align="right"> 
                <input type="hidden" name="MM_insert22" value="form2">
                <input name="idfm" type="hidden" id="idfm" value="<?php echo $row_rst4['fmid']; ?>">
                <img src="picture/b_newdb.png" width="16" height="16"> </div></td>
          </tr>
        </table>
        <table width="600" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td colspan="2" ><img src="picture/folder_open.gif" width="16" height="16"> 
              Leistungsbeschreibung mit Baugruppenvorlage</td>
            <td width="83">&nbsp;</td>
          </tr>
          <?php do { ?>
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td width="36" nowrap="nowrap"  bgcolor="#EEEEEE" ><a href="<?php echo $editFormAction; ?>&url_detail=<?php echo $row_rst10['id_asml']; ?>"><img src="picture/bssctoc1.gif" width="32" height="16" border="0"></a> 
            <td><?php echo $row_rst10['gruppe']; ?> - <?php echo $row_rst10['name']; ?> 
              <input name="hurl_id_asml" type="hidden" id="hurl_id_asml2" value="<?php echo $row_rst10['id_asml']; ?>"> 
              <input name="idfm" type="hidden" id="idfm" value="<?php echo $row_rst4['fmid']; ?>"> 
              <input name="fmartikelid" type="hidden" id="fmartikelid" value="<?php echo $row_rst4['fmartikelid']; ?>"> 
              <div align="right"> </div></td>
            <td width="83"><div align="right"> 
              </div></td>
          </tr>
          <?php 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT `komponenten_vorlage`.* , komponenten_asm.anzahl, komponenten_asm.artikelid FROM komponenten_asm, komponenten_vorlage WHERE (((`komponenten_asm`.`id_asml`= '$row_rst10[id_asml]') AND (`komponenten_vorlage`.`id_komp`= `komponenten_asm`.`komp_id`)) AND komponenten_asm.lokz=0 and komponenten_asm.artikelid=0) or (((`komponenten_asm`.`id_asml`= '$row_rst10[id_asml]') AND (`komponenten_vorlage`.`id_komp`= `komponenten_asm`.`komp_id`)) AND komponenten_asm.lokz=0 and komponenten_asm.artikelid='$row_rst4[fmartikelid]' )";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);
	
	
	if ($totalRows_rst8>0 && $row_rst10['id_asml']==$url_detail){
	?>
          <?php do { ?>
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td nowrap="nowrap"  bgcolor="#EEEEEE" >&nbsp; 
            <td><a href="<?php echo $editFormAction; ?>&url_detail=0"><img src="picture/bssctoc2.gif" width="32" height="16" border="0"></a> 
              <?php echo $row_rst8['anzahl']; ?> St&uuml;ck - <?php echo $row_rst8['kompnr']; ?> 
              - <?php echo $row_rst8['kompname']; ?></td>
            <td>&nbsp;</td>
          </tr>
          <?php } while ($row_rst8 = mysql_fetch_assoc($rst8)); ?>
          <?php } /* ende von rst8 */?>
          <?php } while ($row_rst10 = mysql_fetch_assoc($rst10)); ?>
        </table>
        <?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>
        <?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM komponenten_vorlage";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$url_fmid' ORDER BY fehlermeldungsdaten.varname";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst10 = "SELECT * FROM `komponenten_asm_vorlagen` WHERE (`lokz` =0 and `artikelid`=0) or (`lokz` =0 and `artikelid`='$row_rst4[fmartikelid]')ORDER BY komponenten_asm_vorlagen.gruppe, komponenten_asm_vorlagen.name";
$rst10 = mysql_query($query_rst10, $qsdatenbank) or die(mysql_error());
$row_rst10 = mysql_fetch_assoc($rst10);
$totalRows_rst10 = mysql_num_rows($rst10);


?>
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <?php do { ?>
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td width="116" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['kompnr']; ?></div></td>
            <td width="273" nowrap="nowrap"  bgcolor="#EEEEEE"   > <div align="left"> 
                <?php do {  
?>
                <?php if (!(strcmp($row_rst8['kompnr'], $row_rst9['kompnr']))) {echo htmlentities($row_rst8['kompname']);}?>
                <?php
} while ($row_rst8 = mysql_fetch_assoc($rst8));
  $rows = mysql_num_rows($rst8);
  if($rows > 0) {
      mysql_data_seek($rst8, 0);
	  $row_rst8 = mysql_fetch_assoc($rst8);
  }
?>
              </div></td>
            <td width="101" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['anzahl']; ?> 
                ST </div></td>
            <td width="88" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['lagerort']; ?></div></td>
            <td width="152" nowrap="nowrap"  bgcolor="#EEEEEE"   > <div align="right"> 
                <input type="hidden" name="MM_update22" value="form3">
                <input name="hlokz" type="hidden" id="hlokz2" value="1">
                <input name="idruck" type="hidden" id="idruck2" value="<?php echo $row_rst9['id_ruck']; ?>">
                <img src="picture/b_drop.png" width="16" height="16"> </div></td>
          </tr>
          <input type="hidden" name="MM_update22" value="form3">
          <?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?>
          <br>
          insgesamt <?php echo $totalRows_rst9 ?> Komponenten r&uuml;ckgemeldet. 
          <a href="getexcel.php?url_fmid=<?php echo $row_rst4['fmid']; ?>"><img src="picture/ruckmeldung.png" width="100" height="16" border="0"></a><br>
        </table>
        <?php } // Show if recordset not empty ?>
        <p> 
          <?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
          <font color="#FF0000">noch keine Teile r&uuml;ckmeldet.</font> 
          <?php } // Show if recordset empty ?>
        </p>
       
        
        <table width="600" border="0" cellpadding="0" cellspacing="0">
          <tr bgcolor="#FFFFCC"> 
            <td colspan="2"><em><strong><img src="picture/s_db.png" width="16" height="16"> 
              Verpacken und Versand</strong></em></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFCC"> 
            <td width="212">Datum Verpacken/Kommision</td>
            <td width="398"> <input name="fm5datum" type="text" id="fm5datum" value="<?php echo $row_rst4['fm5datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm5datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
              jjjj-mm-tt erledigt: 
              <input <?php if (!(strcmp($row_rst4['fm5'],1))) {echo "checked";} ?> name="fm5" type="checkbox" id="fm43" value="1"></td>
            <td width="119">&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFCC"> 
            <td>Bemerkungen<br> <br> <br> <br> </td>
            <td> <textarea name="fm5notes" cols="50" id="textarea2"><?php echo $row_rst4['fm5notes']; ?></textarea> 
              <img src="picture/help2.gif" alt="SAP-Liefernummer eingeben" width="16" height="16"></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFCC"> 
            <td><img src="picture/versandlabel.jpg" width="46" height="41" border="0" align="absmiddle"> 
              Versandlabel</td>
            <td> <input name="glsversand2" type="text" value="<?php echo $row_rst4['glsversand']; ?>" size="20"> 
              <img src="picture/help2.gif" alt="Mit Barcodescanner Strichcode vom Versandetikett einscannen oder per Hand eintragen" width="16" height="16"> 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td colspan="2"><em><strong><img src="picture/s_db.png" width="16" height="16"> 
              Warenausgang und Fakura</strong></em></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Datum Warenausgang/Faktura</td>
            <td> <input name="fm6datum" type="text" id="fm2datum2" value="<?php echo $row_rst4['fm6datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm6datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
              jjjj-mm-tt erledigt: 
              <input <?php if (!(strcmp($row_rst4['fm6'],1))) {echo "checked";} ?> name="fm6" type="checkbox" id="fm43" value="1"> 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Bemerkungen<br> <br> <br> <br> </td>
            <td> <textarea name="fm6notes" cols="50" id="textarea"><?php echo $row_rst4['fm6notes']; ?></textarea> 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFCC"> 
            <td colspan="2"><em><strong><img src="picture/s_db.png" width="16" height="16"> 
              Zahlungseingang Mahnwesen</strong></em></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFCC"> 
            <td>Datum Warenausgang/Faktura</td>
            <td> <input name="fm8datum" type="text" id="fm8datum" value="<?php echo $row_rst4['fm8datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm8datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
              jjjj-mm-tt erledigt: 
              <input <?php if (!(strcmp($row_rst4['fm8'],1))) {echo "checked";} ?> name="fm8" type="checkbox" id="fm43" value="1"></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFCC"> 
            <td><p>Bemerkungen</p>
              <p>Mahnstufe/ Zahlungserinnerung<br>
                <br>
                <br>
                <br>
              </p></td>
            <td> <textarea name="fm8txt" cols="50" id="textarea"><?php echo $row_rst4['fm8txt']; ?></textarea></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td colspan="2"><em><strong><img src="picture/s_db.png" width="16" height="16"> 
              Endg&uuml;ltige Korrekturma&szlig;nahme</strong></em></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Datum </td>
            <td> <input name="fm9datum" type="text" id="fm9datum" value="<?php echo $row_rst4['fm9datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm6datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
              jjjj-mm-tt erledigt: 
              <input <?php if (!(strcmp($row_rst4['fm6'],1))) {echo "checked";} ?> name="fm6" type="checkbox" id="fm43" value="1"> 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Analysierte Fehlerursache</td>
            <td> <textarea name="fm9notes" cols="50" rows="4" id="textarea"><?php echo $row_rst4['fm9notes']; ?></textarea> 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Endg&uuml;ltige Abstell- und Korrekturma&szlig;nahme</td>
            <td> <textarea name="fm10notes" cols="50" rows="5" id="textarea"><?php echo $row_rst4['fm10notes']; ?></textarea> 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>Datum best&auml;tigte Wirksamkeit</td>
            <td> <input name="fm6datum" type="text" id="fm2datum2" value="<?php echo $row_rst4['fm6datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm6datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
              jjjj-mm-tt erledigt: 
              <input <?php if (!(strcmp($row_rst4['fm6'],1))) {echo "checked";} ?> name="fm6" type="checkbox" id="fm43" value="1"> 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#CCCCFF"> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3"> 
              <?php 
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fehler.fname, fehler.fkurz, fehler.fid, fehler.lokz, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid, linkfehlerfehlermeldung.lffid FROM linkfehlerfehlermeldung, fehler WHERE linkfehlerfehlermeldung.lokz=0 AND linkfehlerfehlermeldung.fmid='$url_fmid' AND fehler.fid =linkfehlerfehlermeldung.fid ORDER BY fehler.fkurz";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);
?>
              <?php if ($totalRows_rst5 == 0) { // Show if recordset empty ?>
              <font color="#FF0000">Fehlerdefinition f&uuml;r diesen Artikel gespeichert. 
              Fehlerdefinition in Modul la24 pflegen.</font> 
              <?php } // Show if recordset empty ?> <?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
              <em><br>
              m&ouml;gliche Fehler</em><br>
              <table width="600" border="0" cellpadding="0" cellspacing="0">
                
                 <?php do {?>   
                <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                  <td    bgcolor="#EEEEEE" nowrap="nowrap" width="10"> <input name="add" type="submit" id="add2" value="Auswahl"></td>
                  <td    bgcolor="#EEEEEE" nowrap="nowrap" width="260"> 
                    <?php if ($row_rst5['fid']==21){ ?>
                    <img src="picture/fertig.gif" width="15" height="15"> 
                    <?php } ?>
                    <?php echo $row_rst5['fkurz']; ?>- <?php echo $row_rst5['fname']; ?></td>
                  <td    bgcolor="#EEEEEE" nowrap="nowrap" width="5"><input name="hurl_user6" type="hidden" id="hurl_user6" value="<?php echo $row_rst1['id']; ?>"> 
                    <input name="hurl_fmid5" type="hidden" id="hurl_fmid5" value="<?php echo $row_rst4['fmid']; ?>"> 
                    <input name="hurl_fid" type="hidden" id="hurl_fid3" value="<?php echo $row_rst5['fid']; ?>"> 
                    <input type="hidden" name="MM_insert3" value="form2"> 
                  <td width="3"></td>
                </tr>
                <?php } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
              </table>
              <?php }?>
			  
			  <?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
              <font color="#FF0000">noch keine Fehler gespeichert.</font> 
              <?php } // Show if recordset empty ?> <?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
              <br>
              <em>aufgetretene Fehler (bei mehrmaligem Auftreten des Fehler:entspricht 
              Anzahl des gleichen Fehlers) <a href="\\lznt01\werklz\Elektroabteilung\Aufnahme  Foto" target="_blank">Bilder 
              ansehen</a></em> 
              <table width="600" border="0" cellpadding="0" cellspacing="0">
                <?php do { ?>
                <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                  <td  bgcolor="#EEEEEE" nowrap="nowrap" width="115"><img src="picture/b_drop.png" width="16" height="16"> 
                    <input name="delete" type="submit" id="delete3" value="L&ouml;schen"></td>
                  <td  bgcolor="#EEEEEE" nowrap="nowrap" width="95"><?php echo $row_rst6['fkurz']; ?></td>
                  <td   bgcolor="#EEEEEE" nowrap="nowrap" width="520"> 
                    <?php echo $row_rst6['fname']; ?> <input name="hurl_fid" type="hidden" id="hurl_fid3" value="<?php echo $row_rst6['fid']; ?>"> 
                    <input name="hurl_user6" type="hidden" id="hurl_user6" value="<?php echo $row_rst1['id']; ?>"> 
                    <input name="hurl_fmid5" type="hidden" id="hurl_fmid5" value="<?php echo $row_rst4['fmid']; ?>"> 
                    <input name="hurl_lffid" type="hidden" id="hurl_lffid3" value="<?php echo $row_rst6['lffid']; ?>"> 
                    <?php if ($row_rst6['fid']==21){ ?>
                    <img src="picture/Bild.gif" width="22" height="9"> <img src="picture/s_host.png"> 
                    <a href="la44.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst4['fmid']; ?>">VDE-Pr&uuml;fung 
                    erstellen</a> <img src="picture/fertig.gif" width="15" height="15"> 
                    <?php } ?>
                  </td>
                </tr>
                <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
              </table>
              <?php } // Show if recordset not empty ?> </td>
          </tr>
        </table>
		
		
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>

<br>
<?php include("la4.rstuserst.php");?>
<p>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rsttyp);

mysql_free_result($rst9);

mysql_free_result($rst10);
?>
</p>
  <?php include("footer.tpl.php"); ?>
