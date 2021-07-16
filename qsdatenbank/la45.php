<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la45";
if ($HTTP_POST_VARS['lagerort']==""){$HTTP_POST_VARS['lagerort']="P602";}
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

if ((isset($HTTP_POST_VARS["delete"]))) {
  $updateSQL = sprintf("UPDATE komponenten SET lokz=%s WHERE id_ruck=%s",
                       GetSQLValueString($HTTP_POST_VARS['hlokz'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['idruck'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["add"] ))) {
  $insertSQL = sprintf("INSERT INTO komponenten (kompnr, anzahl, lagerort, id_fm) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['komponente'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anzahl'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['lagerort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['idfm'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}


if ((isset($HTTP_POST_VARS["aktualisieren"])) or (isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save1"])) or (isset($HTTP_POST_VARS["save2"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save4"])) or (isset($HTTP_POST_VARS["save5"])) or  (isset($HTTP_POST_VARS["save6"] ))  ) {

  if (($HTTP_POST_VARS['fm5datum']=="0000-00-00")&&($HTTP_POST_VARS['fm5']==1)) {$today="now()";} else { $today=GetSQLValueString($HTTP_POST_VARS['fm5datum'], "date");}
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm5=%s, fm5datum=%s, fm5user=%s, fm5notes=%s ,  intern=%s , glsversand=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fm5'],"int"),
                       $today,
                       GetSQLValueString($HTTP_POST_VARS['fm5user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fm5notes'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['intern'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['glsversand'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
if (!isset($HTTP_POST_VARS["aktualisieren"])){
if (isset($goback)){$updateGoTo = "la45s.php";
}else{
if (isset($HTTP_POST_VARS["save"])){

if (isset($qmb)){$updateGoTo = "qmb4.php";}else{$updateGoTo = "la4.php";}
}
if (isset($HTTP_POST_VARS["save1"])){$updateGoTo = "la41.php";}
if (isset($HTTP_POST_VARS["save2"])){$updateGoTo = "la42.php";}
if (isset($HTTP_POST_VARS["save3"])){$updateGoTo = "la43.php";}
if (isset($HTTP_POST_VARS["save4"])){$updateGoTo = "la44.php";}

if (isset($HTTP_POST_VARS["save6"])){$updateGoTo = "la46.php";}

}
  
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  }
}



if ((isset($HTTP_POST_VARS["cancel"])) ) {
if (isset($goback)){$GoTo = "la45s.php?url_user=".$row_rst1['id'];
}else{
$GoTo = "la4.php?url_user=".$row_rst1['id'];
}
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
$query_rst9 = "SELECT komponenten.id_ruck, komponenten.kompnr, komponenten.anzahl, komponenten.lagerort, komponenten.lokz FROM komponenten WHERE komponenten.id_fm = $url_fmid and  komponenten.lokz=0 ORDER BY komponenten.id_ruck DESC";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM komponenten_vorlage WHERE komponenten_vorlage.lokz=0";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

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
      <td width="131">Aktionen W&auml;hlen:</td>
      <td width="302"> <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> </td>
      <td width="297"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
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
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3"><div align="right"></div></td>
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
      <td width="135" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="594" bgcolor="#FFFFFF"> 
        <?php echo $row_rst6['fkurz']; ?>- <?php echo $row_rst6['fname']; ?> </td>
    </tr>
    <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td>Reparaturvorschlag<br> <br> </td>
      <td> <?php echo $row_rst4['fmnotes']; ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19" colspan="2"><input name="hurl_user" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="fm5user" type="hidden" id="fm5user2" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid3" value="<?php echo $row_rst4['fmid']; ?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19" colspan="2">Reparaturfreigabe: <a href="pdfkv1.php?url_fmid=<?php echo $row_rst4['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?>%20" target="_blank"><img src="picture/ico_anruflisten.gif" alt="Kostenvoranschlag" width="16" height="16" border="0"> 
        Kostenvoranschlag anzeigen</a></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19" colspan="2"> <?php if ($totalRows_rst91 == 0) { // Show if recordset empty ?>
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
    <tr bgcolor="#FFFFFF"> 
      <td width="243" height="19">Datum VDE Pr&uuml;fung</td>
      <td width="490"><?php echo $row_rst4['fm4datum']; ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19"><strong>SAP-Auftragsnummer</strong></td>
      <td><strong><?php echo $row_rst4['sapsd']; ?> </strong></td>
    </tr>
  </table>
  <br>
  <em><strong><img src="picture/s_db.png" width="16" height="16"> Verpacken</strong></em><br>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="212">Datum Verpacken/Kommision</td>
      <td width="398"> <input name="fm5datum" type="text" id="fm2datum2" value="<?php echo $row_rst4['fm5datum']; ?>"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm5datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt erledigt: 
        <input <?php if (!(strcmp($row_rst4['fm5'],1))) {echo "checked";} ?> name="fm5" type="checkbox" id="fm43" value="1"></td>
      <td width="119"><div align="right"> 
          <input name="aktualisieren" type="submit" id="aktualisieren" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>Bemerkungen<br> <br> <br> <br> </td>
      <td> <textarea name="fm5notes" cols="50" id="textarea"><?php echo $row_rst4['fm5notes']; ?></textarea> 
        <img src="picture/help2.gif" alt="SAP-Liefernummer eingeben" width="16" height="16"></td>
      <td><div align="right"><a href="pdfversandetikettGLS.php?url_fmid=<?php echo $row_rst4['fmid']; ?>" target="_blank"><img src="picture/glslabel.png" width="120" height="16" border="0"></a><br>
          <br>
          <br>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><p><img src="picture/feed.png" width="16" height="16"> Zustand des Ger&auml;tes/Verpackung 
          / Ger&auml;t vollst&auml;ndig (oder nur Deckel oder nur Grundk&ouml;rper, 
          mit Kabel ?)</p></td>
      <td colspan="2"><textarea name="intern" cols="70" rows="5" id="intern"><?php echo $row_rst4['intern']; ?></textarea></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><img src="picture/versandlabel.jpg" width="46" height="41" border="0" align="absmiddle"> 
        Versandlabel</td>
      <td> <input name="glsversand" type="text" value="<?php echo $row_rst4['glsversand']; ?>" size="20"> 
        <img src="picture/help2.gif" alt="Mit Barcodescanner Strichcode vom Versandetikett einscannen oder per Hand eintragen" width="16" height="16"> 
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>
    <?php 


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst42 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$row_rst4[fmartikelid] and fertigungsmeldungen.fsn=$row_rst4[fmsn] ORDER BY fertigungsmeldungen.fdatum desc";
$rst42 = mysql_query($query_rst42, $qsdatenbank) or die(mysql_error());
$row_rst42 = mysql_fetch_assoc($rst42);
$totalRows_rst42 = mysql_num_rows($rst42);




?>
    <br>
    <em><strong><img src="picture/s_tbl.png" width="16" height="16"> Liste der 
    Fertigungsdaten:</strong></em> </p>
  <?php if ($totalRows_rst42 == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur Auswahl gefunden.</font></p>
  <?php } // Show if recordset empty ?>
  <?php if ($totalRows_rst42 > 0) { // Show if recordset not empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="296">M&ouml;gliche Fertigungsdaten:</td>
      <td width="147">Status </td>
      <td width="105">User</td>
      <td width="182">Bemerkungen </td>
    </tr>
    <?php do { ?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <?php echo $row_rst42['fdatum']; ?> 
        <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst42['fdatum']; ?>"> 
        <input name="name2" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
        <input name="select2" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
        <input name="debitor" type="hidden" id="debitor" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
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
  <br>
  <input type="hidden" name="MM_update" value="form2">
</form>
<form action="<?php echo $editFormAction; ?>" name="form2" method="POST">
  <table width="730" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCCCFF">
    <tr bgcolor="#CCCCFF"> 
      <td width="190">Material ausw&auml;hlen:<img src="picture/help2.gif" alt="Materialentnahme f&uuml;r R&uuml;ckmeldungen" width="16" height="16"></td>
      <td > <select name="komponente" id="komponente">
          <?php
do {  
?>
          <option value="<?php echo $row_rst8['kompnr']?>"<?php if (!(strcmp($row_rst8['kompnr'], $HTTP_POST_VARS['komponente']))) {echo "SELECTED";} ?>><?php echo $row_rst8['kompnr']." - ".utf8_decode($row_rst8['kompname']);?></option>
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
          <input name="idfm" type="hidden" id="idfm" value="<?php echo $row_rst4['fmid']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="add" type="submit" id="add" value="hinzuf&uuml;gen">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert2" value="form2">
</form>
<?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <?php do { ?>
  <form action="<?php echo $editFormAction; ?>" name="form3" method="POST">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td width="116" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['kompnr']; ?></div></td>
      <td width="269" nowrap="nowrap"  bgcolor="#EEEEEE"   > <div align="left"> 
          <?php do {  
?>
          <?php if (!(strcmp($row_rst8['kompnr'], $row_rst9['kompnr']))) {echo htmlentities(($row_rst8['kompname']));}?>
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
          ST</div></td>
      <td width="92" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['lagerort']; ?></div></td>
      <td width="152" nowrap="nowrap"  bgcolor="#EEEEEE"   > <div align="right"> 
          <input type="hidden" name="MM_update2" value="form3">
          <input name="hlokz" type="hidden" id="hlokz" value="1">
          <input name="idruck" type="hidden" id="idruck" value="<?php echo $row_rst9['id_ruck']; ?>">
          <img src="picture/b_drop.png" width="16" height="16"> 
          <input name="delete" type="submit" id="delete" value="l&ouml;schen">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update2" value="form3">
  </form>
  <?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?>
  <br>
  insgesamt <?php echo $totalRows_rst9 ?> Komponenten r&uuml;ckgemeldet. <a href="getexcel.php?url_fmid=<?php echo $row_rst4['fmid']; ?>"><img src="picture/ruckmeldung.png" width="100" height="16" border="0"></a><br>
</table>
<?php } // Show if recordset not empty ?>
<p> 
  <?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
  <font color="#FF0000">noch keine Teile r&uuml;ckmeldet.</font> 
  <?php } // Show if recordset empty ?>
</p>
<?php include("la4.rstuserst.php");?>
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

mysql_free_result($rst8);


?>
</p>
 
<?php include("footer.tpl.php"); ?>
