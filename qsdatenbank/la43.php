<?php require_once('Connections/qsdatenbank.php'); ?><?php
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



if ((isset($HTTP_POST_VARS["Entscheidung"]))) {
  $updateSQL = sprintf("UPDATE fehlermeldungsdaten SET lokz=%s, entid=%s WHERE varid=%s",
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['entid']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_varid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  $oktxt.="fertig.$HTTP_POST_VARS[hurl_varid]";
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);



if ((isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save1"])) or (isset($HTTP_POST_VARS["save2"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save4"])) or (isset($HTTP_POST_VARS["save5"])) or  (isset($HTTP_POST_VARS["save6"] ))  ) {
  if (($HTTP_POST_VARS['fm3datum']=="0000-00-00")&&($HTTP_POST_VARS['fm3']==1)) {
  $today="now()";
  $errtxt .= "Datum wurde auf Tagesdatum gesetzt.";
  } else { 
  $today=GetSQLValueString($HTTP_POST_VARS['fm3datum'], "date");
  $errtxt .= "Datum OK."; }
 		
		$hartikelid=$HTTP_POST_VARS['hartikelid'];
 
 		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rsttyp = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$hartikelid' ";
		$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
		$row_rsttyp = mysql_fetch_assoc($rsttyp);
		$totalRows_rsttyp = mysql_num_rows($rsttyp);
 		
		$repvorgabezeit=$row_rsttyp['repvorgabezeit'];
		$kostenstelle= $HTTP_POST_VARS['sapkst'];
		
		if ($repvorgabezeit>0){ /* suche nach aktueller abrechnungsvorschrift */
					mysql_select_db($database_qsdatenbank, $qsdatenbank);
					$query_rst_kst = "SELECT *  FROM abrechnungsvorschriften WHERE abrechnungsvorschriften.kst_datum_ab <= date(now( ) ) AND abrechnungsvorschriften.kst_datum_bis > date( now( ) )  AND abrechnungsvorschriften.lokz =0 AND kst_nummer='$kostenstelle' AND kst_vorgabezeit='$repvorgabezeit' LIMIT 0 , 30 ";
					$rst_kst = mysql_query($query_rst_kst, $qsdatenbank) or die(mysql_error());
					$row_rst_kst = mysql_fetch_assoc($rst_kst);
					$totalRows_rst_kst = mysql_num_rows($rst_kst);
					$pcnaauftrag=$row_rst_kst['kst_auftrag'];
						
		}
		
		
 		if ( (($pcnaauftrag>=900000) && ($HTTP_POST_VARS['hsappcna']>0)) or (($pcnaauftrag>=900000) && ($editabrechnung==1)) ){
					  $updateSQL = sprintf("UPDATE fehlermeldungen SET sappcna=%s WHERE fmid=%s",
										   GetSQLValueString($pcnaauftrag, "int"),
										   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));
					
					  mysql_select_db($database_qsdatenbank, $qsdatenbank);
					  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
					  $errtxt .= "PCNA-Auftragsnummer ".$pcnaauftrag." f&uuml;r FM ".$HTTP_POST_VARS['hurl_fmid']." angelegt.";
		}else{ /* else  */
					$errtxt .= "PCNA-Auftragsnummer -$pcnaauftrag- nicht gefunden.<br>";
					$errtxt .= "Abrechnungsregeln und Zeiträume in der Tabelle ergänzen.<br>";
					 $errtxt .= "<br>PCNA-fmid ".$HTTP_POST_VARS['hurl_fmid'];
					 $errtxt .= "<br>PCNA-kst ".$HTTP_POST_VARS['sapkst'];
					 $errtxt .= "<br>PCNA-artikelid ".$HTTP_POST_VARS['hartikelid'];
					 $errtxt .= "<br>PCNA-repvorgabezeit ".$row_rsttyp['repvorgabezeit'];
					  $errtxt .= "<br>PCNA-pcnaauftrag ".$pcnaauftrag;
					 $errtxt .= "<br>".$query_rst_kst;
 		}
 
 
 
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm3=%s, fm3variante=%s, fm3datum=%s, fm3user=%s, fm3notes=%s, sapsd=%s, sapkst=%s, intern=%s,vip=%s, vkz=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fm3'],"int"),
                       GetSQLValueString($HTTP_POST_VARS['fm3variante'], "int"),
                       $today,
                       GetSQLValueString($HTTP_POST_VARS['fm3user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fm3notes'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['sapsd'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['sapkst'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['intern'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['vip'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['vkz'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
if (isset($goback)){	
	$updateGoTo = $goback;
	}else{
		if (isset($HTTP_POST_VARS["save"])){
		if (isset($qmb)){$updateGoTo = "qmb4.php";}else{$updateGoTo = "la4.php";}
		}
		if (isset($HTTP_POST_VARS["save1"])){$updateGoTo = "la41.php";}
		if (isset($HTTP_POST_VARS["save2"])){$updateGoTo = "la42.php";}
		if (isset($HTTP_POST_VARS["save4"])){$updateGoTo = "la44.php";}
		if (isset($HTTP_POST_VARS["save5"])){$updateGoTo = "la45.php";}
		if (isset($HTTP_POST_VARS["save6"])){$updateGoTo = "la46.php";}
  }
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo)); 
}

$la = "la43";

if ((isset($HTTP_POST_VARS["cancel"])) ) {
	if (isset($goback)){
		if ($goback=="la471s.php"){
			$GoTo = "la471s.php?url_user=".$row_rst1['id'];
			}
			else 
			{	
			$GoTo = "la43s.php?url_user=".$row_rst1['id'];
			}
	}else{
		$GoTo = "la4.php?url_user=".$row_rst1['id'];
	}
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt .= "Aktion wählen. Bitte erneut versuchen. (Cancel)";}




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

$kostenklarung = $row_rst4["fm2variante"];
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst7 = "SELECT entscheidung.entid, entscheidung.entname, entscheidung.entmemo, entscheidungsvarianten.varid, entscheidungsvarianten.varname, entscheidungsvarianten.varbeschreibung FROM entscheidung, entscheidungsvarianten WHERE entscheidung.lokz=0 AND entscheidungsvarianten.lokz=0 AND entscheidungsvarianten.entid=entscheidung.entid and entscheidung.entid=$kostenklarung ORDER BY entscheidung.entname, entscheidungsvarianten.varname";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

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

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form1" method="POST">
  <table width="700" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        </font></strong> 
        <?php } // Show if recordset empty ?> <strong><font color="#FF0000"><?php echo $oktxt  ?></font></strong> 
        <strong><font color="#FF0000"><?php echo $errtxt  ?></font></strong></td>
    </tr>
    <tr> 
      <td width="213">Aktionen W&auml;hlen:</td>
      <td width="278"> <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> </td>
      <td width="209"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          <input name="save" type="submit" id="save" value="Speichern und zur Liste">
        </div></td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"> <?php  include("la4.status.php");?> </td>
    </tr>
  </table>
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr bgcolor="#FFFFFF"> 
      <td width="212">Debitor </td>
      <td width="662"> <?php echo $row_rst4['fm1notes']; ?></td>
      <td width="76"><input name="fm3user" type="hidden" id="fm3user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_fmid3" type="hidden" id="hurl_fmid4" value="<?php echo $row_rst4['fmid']; ?>"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19">Reparaturvorschlag</td>
      <td><?php echo $row_rst4['fmnotes']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19">&nbsp;</td>
      <td><?php echo $row_rst4['fm2notes']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>Gewicht des Ger&auml;tes bei Anlieferung</td>
      <td><?php echo $row_rst4['gewichtvorher']; ?>&nbsp;kg</td>
      <td>&nbsp;</td>
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
      <td width="206" bgcolor="#FFFFFF">Fehlererfassung</td>
      <td width="523" bgcolor="#FFFFFF" > 
        <?php echo $row_rst6['fkurz']; ?>- <?php echo $row_rst6['fname']; ?>
<input name="hurl_fid" type="hidden" id="hurl_fid" value="<?php echo $row_rst6['fid']; ?>">
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>">
        <input name="hurl_lffid" type="hidden" id="hurl_lffid" value="<?php echo $row_rst6['lffid']; ?>"></td>
  </tr>
  <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
</table>
<?php } // Show if recordset not empty ?>
  <br>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="207">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td>SAP-SD-Nummer / Kostenstelle</td>
      <td colspan="2"> <input name="sapsd" type="text" id="sapsd" value="<?php echo $row_rst4['sapsd']; ?>"> 
        <select name="sapkst" id="select2">
          <option value="61100" <?php if (!(strcmp(61100, $row_rst4['sapkst']))) {echo "SELECTED";} ?>>61100 
          nicht verwenden Fertigung BPT</option>
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
        </select> <input name="hartikelid" type="hidden" id="hartikelid" value="<?php echo $row_rst4['fmartikelid']; ?>"> 
        <input name="hsappcna" type="hidden" id="hsappcna" value="<?php echo $row_rst4['sappcna']; ?>"></td>
    </tr>
    <tr> 
      <td>Dringlichkeitskennzeichen</td>
      <td colspan="2"> <select name="vip">
          <option value="" <?php if (!(strcmp("", $row_rst4['vip']))) {echo "SELECTED";} ?>>keine 
          Auswahl</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst4['vip']))) {echo "SELECTED";} ?>>normal</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst4['vip']))) {echo "SELECTED";} ?>>1-bevorzugt</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst4['vip']))) {echo "SELECTED";} ?>>2-sofort</option>
        </select> <img src="picture/btnHelp.gif" alt="zeigt in der &Uuml;bersichtsliste ein Lampensymbol an: Eingabe als 0, 1 oder 2" width="16" height="16">oder 
        Sonderbehandlung 
        <select name="vkz" id="vkz">
          <option value="0" <?php if (!(strcmp(0, $row_rst4['vkz']))) {echo "SELECTED";} ?>>keine</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Verschrottung</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Sonderbehandlung</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst4['vkz']))) {echo "SELECTED";} ?>>Servicetüre</option>
        </select></td>
    </tr>
    <tr> 
      <td>Datum Reparaturfreigabe</td>
      <td width="720"> <input name="fm3datum" type="text" id="fm2datum2" value="<?php echo $row_rst4['fm3datum']; ?>"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm3datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt Freigabe: 
        <input <?php if (!(strcmp($row_rst4['fm3'],1))) {echo "checked";} ?> name="fm3" type="checkbox" id="fm22" value="1"> 
      </td>
      <td width="23">&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><p><img src="picture/feed.png" width="16" height="16"> Zustand des Ger&auml;tes/Verpackung 
          / Ger&auml;t vollst&auml;ndig (oder nur Deckel oder nur Grundk&ouml;rper, 
          mit Kabel ?)</p></td>
      <td colspan="2"><textarea name="intern" cols="70" rows="5" id="intern"><?php echo $row_rst4['intern']; ?></textarea></td>
    </tr>
    <tr> 
      <td><p>Bemerkungen</p>
        <p>Datum/ Name und <br>
          Auftragsnummer vom Kunden</p></td>
      <td> <textarea name="fm3notes" cols="50" id="textarea"><?php echo $row_rst4['fm3notes']; ?></textarea></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>SAP-Kundennummer:<img src="picture/btnHelp.gif" alt="Wenn die Kundendaten zur Reparaturaufnahme vorhanden waren, wird die Kundennummer angezeigt." width="16" height="16"></td>
      <td><p><strong><?php echo $row_rst4['debitorennummer']; ?></strong></p></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>SAP-Lieferanschrift:<img src="picture/btnHelp.gif" alt="SAP Kundennummer für Warenempänger angezeigt." width="16" height="16"></td>
      <td><p><strong><?php echo $row_rst4['debitorennummer2']; ?></strong></p></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="730"><strong>Reparaturvariante</strong> <select name="fm3variante" id="select3">
          <option value="0" <?php if (!(strcmp(0, $row_rst4['fm2variante']))) {echo "SELECTED";} ?>>keine 
          Angabe</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst7['varid']?>"<?php if (!(strcmp($row_rst7['varid'], $row_rst4['fm3variante']))) {echo "SELECTED";} ?>><?php echo substr($row_rst7['entname'],0,20)."- ".$row_rst7['varname']." - ".substr($row_rst7['varbeschreibung'],0,80)?></option>
          <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
        </select> <img src="picture/btnHelp.gif" alt="zeigt alle Kostenvoranschlagsvarianten an." width="16" height="16"></td>
    </tr>
  </table>
  <p> 
    <input type="hidden" name="MM_update" value="form2">
  </p>
</form>

<p>
  <?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
  <font color="#FF0000">keine Positionsdaten vorhanden.</font> 
  <?php } // Show if recordset empty ?>
  <?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>
</p>
<table width="950" border="0" cellpadding="0" cellspacing="0">
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
    <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
      <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst9['varname']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(($row_rst9['varbeschreibung']),60,"<br>",255); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst9['Kosten1']; ?> &euro;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst9['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz3" value="1"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="entid" type="checkbox" id="entid"  value="1" <?php if (!(strcmp($row_rst9['entid'],1))) {echo "checked";} ?>></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_varid" type="hidden" id="hurl_varid" value="<?php echo $row_rst9['varid']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="Entscheidung" type="submit"  value="Auswahl">
        </div></td>
     
    </form>
  </tr>
  <?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?>
</table>


<p> 
  <?php }?>
</p>
<p><strong>Zur Info:</strong></p>
<table width="950" height="205" border="0" cellpadding="0" cellspacing="0">
  <tr bgcolor="#CCCCFF"> 
    <td width="187">Rechnungsempf&auml;nger</td>
    <td width="763"> <select name="select2">
        <option value="""" <?php if (!(strcmp("", $HTTP_POST_VARS['select2']))) {echo "SELECTED";} ?>>keine 
        &Auml;nderung</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rstkunden['idk']?>"<?php if (!(strcmp($row_rstkunden['idk'], $HTTP_POST_VARS['select2']))) {echo "SELECTED";} ?>><?php echo utf8_decode($row_rstkunden['firma']);?></option>
        <?php
} while ($row_rstkunden = mysql_fetch_assoc($rstkunden));
  $rows = mysql_num_rows($rstkunden);
  if($rows > 0) {
      mysql_data_seek($rstkunden, 0);
	  $row_rstkunden = mysql_fetch_assoc($rstkunden);
  }
?>
      </select></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Anschrift</td>
    <td><textarea name="rechnung" cols="45"><?php echo ($row_rst4['rechnung']); ?></textarea></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Ansprechpartner</td>
    <td><input name="ansprechpartner" type="text" id="ansprechpartner2" value="<?php echo $row_rst4['ansprechpartner']; ?>" size="40"></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Anrede</td>
    <td><input name="anrede" type="text" id="anrede2" value="<?php echo $row_rst4['anrede']; ?>" size="40"></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Stra&szlig;e</td>
    <td><input name="rstrasse" type="text" id="rstrasse2" value="<?php echo ($row_rst4['rstrasse']); ?>" size="40"></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td height="24">PLZ</td>
    <td><input name="rplz" type="text" id="rplz2" value="<?php echo ($row_rst4['rplz']); ?>"></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Ort</td>
    <td><input name="rort" type="text" id="rort2" value="<?php echo ($row_rst4['rort']); ?>" size="40"></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Land</td>
    <td><input name="rland" type="text" id="rland2" value="<?php echo ($row_rst4['rland']); ?>"></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td><div align="left">Rechnung und Lieferung gleich </div></td>
    <td><input type="checkbox" name="checkbox" value="1">
      Faxnummer: 
      <input name="rfax" type="text" id="rfax2" value="<?php echo ($row_rst4['faxnummer']); ?>"></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="950" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="188">Lieferung an:</td>
    <td width="762"><select name="select">
        <option value="""" <?php if (!(strcmp("", $HTTP_POST_VARS['select3']))) {echo "SELECTED";} ?>>keine 
        &Auml;nderung</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rstkunden['idk']?>"<?php if (!(strcmp($row_rstkunden['idk'], $HTTP_POST_VARS['select3']))) {echo "SELECTED";} ?>><?php echo utf8_decode($row_rstkunden['firma']);?></option>
        <?php
} while ($row_rstkunden = mysql_fetch_assoc($rstkunden));
  $rows = mysql_num_rows($rstkunden);
  if($rows > 0) {
      mysql_data_seek($rstkunden, 0);
	  $row_rstkunden = mysql_fetch_assoc($rstkunden);
  }
?>
      </select> </td>
  </tr>
  <tr> 
    <td>Anschrift</td>
    <td><textarea name="textarea" cols="45"><?php echo ($row_rst4['lieferung']); ?></textarea></td>
  </tr>
  <tr> 
    <td>Stra&szlig;e</td>
    <td><input name="lstrasse2" type="text" id="lstrasse22" value="<?php echo ($row_rst4['lstrasse']); ?>" size="40"></td>
  </tr>
  <tr> 
    <td>PLZ</td>
    <td><input name="lplz2" type="text" id="lplz2" value="<?php echo ($row_rst4['lplz']); ?>"></td>
  </tr>
  <tr> 
    <td>Ort</td>
    <td><input name="lort2" type="text" id="lort22" value="<?php echo ($row_rst4['lort']); ?>" size="40"></td>
  </tr>
  <tr> 
    <td>Land</td>
    <td><input name="lland2" type="text" id="lland22" value="<?php echo ($row_rst4['lland']); ?>"></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td><div align="right"> </div></td>
  </tr>
  <tr> 
    <td colspan="2"><strong>Reparaturvariante</strong> <select name="select" id="select4">
        <option value="0" <?php if (!(strcmp(0, $row_rst4['fm2variante']))) {echo "SELECTED";} ?>>keine 
        Angabe</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rst7['varid']?>"<?php if (!(strcmp($row_rst7['varid'], $row_rst4['fm3variante']))) {echo "SELECTED";} ?>><?php echo substr($row_rst7['entname'],0,20)."- ".$row_rst7['varname']." - ".substr($row_rst7['varbeschreibung'],0,80)?></option>
        <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
      </select> <img src="picture/btnHelp.gif" alt="zeigt alle Kostenvoranschlagsvarianten an." width="16" height="16"></td>
  </tr>
</table>
<?php include("la4.picture.php"); ?>
<p>&nbsp; </p>
<?php include("la4.rstuserst.php"); ?>
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

mysql_free_result($rst9);
?>
</p>

  <?php include("footer.tpl.php"); ?>