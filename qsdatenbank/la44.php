<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
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

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE komponenten SET lokz=%s WHERE id_ruck=%s",
                       GetSQLValueString($HTTP_POST_VARS['hlokz'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['idruck'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["add"] ))) {

if ($HTTP_POST_VARS['scanfeld']==""){
  $insertSQL = sprintf("INSERT INTO komponenten (kompnr, anzahl, lagerort, id_fm) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['komponente'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anzahl'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['lagerort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['idfm'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}else{

  $insertSQL = sprintf("INSERT INTO komponenten (kompnr, anzahl, lagerort, id_fm) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['scanfeld'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anzahl'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['lagerort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['idfm'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  }
  $oktxt=$oktxt."Material $HTTP_POST_VARS[scanfeld] $HTTP_POST_VARS[komponente]  - Anzahl: $HTTP_POST_VARS[anzahl] gespeichert.";
}

if ((isset($HTTP_POST_VARS["ladd"] ))) {

/* Abfrage nach Komponenten für ausgewählte asml */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT `komponenten_vorlage`.* , komponenten_asm.anzahl, komponenten_asm.artikelid FROM komponenten_asm, komponenten_vorlage WHERE (((`komponenten_asm`.`id_asml`= '$HTTP_POST_VARS[hurl_id_asml]') AND (`komponenten_vorlage`.`id_komp`= `komponenten_asm`.`komp_id`)) AND (`komponenten_vorlage`.`lokz`= 0) AND komponenten_asm.lokz=0 and komponenten_asm.artikelid=0) or (((`komponenten_asm`.`id_asml`= '$HTTP_POST_VARS[hurl_id_asml]') AND (`komponenten_vorlage`.`id_komp`= `komponenten_asm`.`komp_id`)) AND komponenten_asm.lokz=0 and komponenten_asm.artikelid='$HTTP_POST_VARS[fmartikelid]' )";
/* $query_rst8 = "SELECT `komponenten_vorlage`.* , komponenten_asm.anzahl FROM komponenten_asm, komponenten_vorlage WHERE ((`komponenten_asm`.`id_asml`= $HTTP_POST_VARS[hurl_id_asml]) AND (`komponenten_vorlage`.`id_komp`= `komponenten_asm`.`id_asm`)) AND komponenten_asm.lokz=0";
 */$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);
if ($totalRows_rst8>0){
do{
  $insertSQL = sprintf("INSERT INTO komponenten (kompnr, anzahl, lagerort, id_fm) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($row_rst8['kompnr'], "text"),
                       GetSQLValueString($row_rst8['anzahl'], "int"),
                       GetSQLValueString($row_rst8['lagerort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['idfm'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  
  } while ($row_rst8 = mysql_fetch_assoc($rst8));
  } /* end von if >0 */
}



 $la = "la44";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["vde"])) ) {
$updateGoTo = "la441.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["select"]."&url_sn=".$HTTP_POST_VARS["name"]."&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&url_fmnotes=Fehlermeldung: ".$HTTP_POST_VARS["hurl_fmid"]."-Reparatur: ".str_replace("\n"," ",$HTTP_POST_VARS["textarea"]);
 if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save1"])) or (isset($HTTP_POST_VARS["save2"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save4"])) or (isset($HTTP_POST_VARS["save5"])) or  (isset($HTTP_POST_VARS["save6"] ))  ) {
  if (($HTTP_POST_VARS['fm4datum']=="0000-00-00")&&($HTTP_POST_VARS['fm4']==1)) {$today="now()";} else { $today=GetSQLValueString($HTTP_POST_VARS['fm4datum'], "date");}
 
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm4=%s, fm4datum=%s, fm4user=%s, fm4notes=%s, intern=%s, glsversand=%s, gewichtnachher=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fm4'],"int"),
                       $today,
                       GetSQLValueString($HTTP_POST_VARS['fm4user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fm4notes'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['intern'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['glsversand'], "text"),
					   GetSQLValueString(str_replace(",",".",$HTTP_POST_VARS['gewichtnachher']), "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  if (isset($goback)){$updateGoTo = "la44s.php";}else{
if (isset($HTTP_POST_VARS["save"])){
if (isset($qmb)){$updateGoTo = "qmb4.php";}else{$updateGoTo = "la4.php";}
}
if (isset($HTTP_POST_VARS["save1"])){$updateGoTo = "la41.php";}
if (isset($HTTP_POST_VARS["save2"])){$updateGoTo = "la42.php";}
if (isset($HTTP_POST_VARS["save3"])){$updateGoTo = "la43.php";}

if (isset($HTTP_POST_VARS["save5"])){$updateGoTo = "la45.php";}
if (isset($HTTP_POST_VARS["save6"])){$updateGoTo = "la46.php";}
}
  
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


if ((isset($HTTP_POST_VARS["cancel"])) ) {
if (isset($goback)){ $GoTo = "la44s.php?url_user=".$row_rst1['id'];}else{
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
$query_rst8 = "SELECT * FROM komponenten_vorlage WHERE komponenten_vorlage.lokz=0 order by komponenten_vorlage.beschaffungsart asc, komponenten_vorlage.kompname asc";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT komponenten.id_ruck, komponenten.kompnr, komponenten.anzahl, komponenten.lagerort, komponenten.lokz FROM komponenten WHERE komponenten.id_fm = $url_fmid and  komponenten.lokz=0 ORDER BY komponenten.id_ruck DESC";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst10 = "SELECT * FROM `komponenten_asm_vorlagen` WHERE `lokz` =0 ORDER BY komponenten_asm_vorlagen.gruppe, komponenten_asm_vorlagen.name";
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

<form name="form1" method="POST">
  <table width="950" border="0" cellspacing="0" cellpadding="0">
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
      <td width="129">Aktionen W&auml;hlen:</td>
      <td width="382"> <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> </td>
      <td width="218"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          <input name="save" type="submit" id="save" value="Speichern und zur Liste">
        </div></td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td><font color="#FFFFFF">Freigabe:</font> <input <?php if ((!(strcmp($row_rst4['fm4'],1))) or (isset($url_vde_ok))) {echo "checked";} ?> name="fm4" type="checkbox" id="fm22" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"> <?php  include("la4.status.php");?> </td>
    </tr>
  </table>
  <hr>
  <table width="950" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="450">
 
  <table width="450" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr bgcolor="#FFFFFF"> 
      <td width="190" height="19">Reparaturvorschlag</td>
      <td width="349"><?php echo $row_rst4['fmnotes']; ?></td>
      <td width="187"><input name="fm4user" type="hidden" id="fm4user2" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="select" type="hidden" id="select2" value="<?php echo $row_rst4['fmartikelid']; ?>"> 
        <input name="name" type="hidden" id="name2" value="<?php echo $row_rst4['fmsn']; ?>"> 
        <input name="textarea" type="hidden" id="textarea2" value="<?php echo $row_rst4['fmnotes']; ?>"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid3" value="<?php echo $row_rst4['fmid']; ?>"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
 

<?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
<font color="#FF0000">noch keine Fehler gespeichert.</font> 
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>

  <table width="450" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <?php do { ?>
  <tr>  
      <td width="1">&nbsp; 
      </td>
      <td width="190" bgcolor="#FFFFFF">Fehler:</td>
      <td bgcolor="#FFFFFF" > 
        <?php echo $row_rst6['fkurz']; ?>- <?php echo $row_rst6['fname']; ?> </td>
  </tr>
  <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
</table>
<?php } // Show if recordset not empty ?>
  <table width="450" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td width="145" height="19">Reparaturfreigabe: </td>
      <td width="300"><a href="pdfkv1.php?url_fmid=<?php echo $row_rst4['fmid']; ?>&url_user_id=<?php echo $row_rst1['id'];?>%20" target="_blank"><img src="picture/ico_anruflisten.gif" alt="Kostenvoranschlag" width="16" height="16" border="0"> 
        Kostenvoranschlag anzeigen</a></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19" colspan="2"> <?php if ($totalRows_rst91 == 0) { // Show if recordset empty ?>
        <font color="#FF0000">keine Positionsdaten f&uuml;r den Kostenvoranschlag 
        vorhanden.</font> 
        <?php } // Show if recordset empty ?> <?php if ($totalRows_rst91 > 0) { // Show if recordset not empty ?>
        <table width="450" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="127">Postion</td>
            <td width="131">Beschreibung</td>
            <td width="361"><div align="right">Kosten</div></td>
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
      <td height="19">Zusatz</td>
      <td><?php echo $row_rst4['fm3notes']; ?></td>
    </tr>
  </table>
  
      <table width="450" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td colspan="3"><hr></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td><em><strong><img src="picture/s_host.png" width="16" height="16"> 
            Reparatur</strong></em></td>
          <td width="120" >&nbsp;</td>
          <td width="201">&nbsp; </td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td width="129">Datum VDE Pr&uuml;fung</td>
          <td colspan="2" > <input name="fm4datum" type="text" id="fm2datum2" value="<?php echo $row_rst4['fm4datum']; ?>" size="15"> 
            <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'fm4datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script> <br> <div align="left"> </div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td><p><img src="picture/feed.png" width="16" height="16"> Zustand des 
              Ger&auml;tes/Verpackung / Ger&auml;t vollst&auml;ndig (oder nur 
              Deckel oder nur Grundk&ouml;rper, mit Kabel ?)</p></td>
          <td colspan="2"><textarea name="intern" cols="40" rows="10" id="intern"><?php echo $row_rst4['intern']; ?></textarea></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td>&nbsp;</td>
          <td colspan="2"><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst4['fmartikelid']; ?>&url_sn_ab=<?php echo $row_rst4['fmsn']; ?>&url_sn_bis=<?php echo $row_rst4['fmsn']; ?>&rep=1&logo=1" target="_blank"><img src="picture/rules.gif" alt="Typenschild drucken" width="13" height="18" border="0" align="absmiddle"> 
            Typenschild drucken </a></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td>&nbsp;</td>
          <td colspan="2"><input name="vde" type="submit" id="vde2" value="VDE-Pr&uuml;fung starten"></td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td>Gewicht nach Rep.:</td>
          <td><input name="gewichtnachher" type="text" id="gewichtnachher" value="<?php echo $row_rst4['gewichtnachher']; ?>" size="10" maxlength="255">
            kg</td>
          <td>Vorher: <?php echo $row_rst4['gewichtvorher']; ?> kg</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td><img src="picture/versandlabel.jpg" width="46" height="41" border="0" align="absmiddle"> 
            Versandlabel</td>
          <td> <input name="glsversand" type="text" value="<?php echo $row_rst4['glsversand']; ?>" size="20"> 
            <img src="picture/help2.gif" alt="Mit Barcodescanner Strichcode vom Versandetikett einscannen oder per Hand eintragen" width="16" height="16"> 
          </td>
          <td><p><font size="1"><img src="picture/b_docs.png" width="16" height="16"> 
              Hinweis: Ger&auml;te, die einen neuen<br>
              </font><font size="1">Ger&auml;te typ erhalten bitte als Neuger&auml;te<br>
              mit dem Hinweis auf diese Retoure<br>
              fertigmelden.<br>
              -&gt; <a href="la1.php?url_user=<?php echo $url_user ?>" target="_blank">Fertigungsmeldung 
              erstellen</a></font></p></td>
        </tr>
        <tr> 
          <td colspan="3"><hr> <p> 
              <?php 


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst42 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$row_rst4[fmartikelid] and fertigungsmeldungen.fsn=$row_rst4[fmsn] ORDER BY fertigungsmeldungen.fdatum desc";
$rst42 = mysql_query($query_rst42, $qsdatenbank) or die(mysql_error());
$row_rst42 = mysql_fetch_assoc($rst42);
$totalRows_rst42 = mysql_num_rows($rst42);




?>
          </td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="2"><em> <strong><img src="picture/sap.png" width="16" height="16"> 
            R&uuml;ckmeldung - Repararturzeit</strong></em></td>
          <td>&nbsp;</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td>Arbeitszeit:</td>
          <td> <input name="fm4notes" value="<?php echo $row_rst4['fm4notes']; ?>" size="10">
            min</td>
          <td><img src="picture/b_docs.png" width="16" height="16"> Die Reparaturzeit 
            incl. Fehleraufnahme in min.</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="3"><input name="save10" type="submit" id="save10" value="Speichern und zur Liste"></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="3"> <br> <em><strong><img src="picture/s_tbl.png" width="16" height="16"> 
            Liste der Fertigungsdaten:</strong></em></p> <?php if ($totalRows_rst42 == 0) { // Show if recordset empty ?>
            <p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur 
              Auswahl gefunden.</font></p>
            <?php } // Show if recordset empty ?> <?php if ($totalRows_rst42 > 0) { // Show if recordset not empty ?>
            <table width="450" border="0" cellpadding="0" cellspacing="0">
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
                  <input name="name2" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
                  <input name="select2" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
                  <input name="debitor" type="hidden" id="debitor" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst42['ffrei'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
                  - <img src="picture/<?php if (!(strcmp($row_rst42['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
                </td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst42['fuser']; ?></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst42['fnotes'],30,"<br>",255); ?></td>
              </tr>
              <?php } while ($row_rst42 = mysql_fetch_assoc($rst42)); ?>
            </table>
            <br>
            insgesamt <?php echo $totalRows_rst42 ?> Fertigungsmeldungen gefunden.<br>
            <?php } // Show if recordset not empty ?> </td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="3"><hr></td>
        </tr>
      </table>
  <input type="hidden" name="MM_update" value="form2">
</form>

</td>
 
<td width="500"> 
  <?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>

<?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM komponenten_vorlage";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);


?>

<table width="500" border="0" cellpadding="0" cellspacing="0"> 
 <?php do { ?>
  <form action="<?php echo $editFormAction; ?>" name="form3" method="POST">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
	<td width="10" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['kompnr']; ?></div></td>
      <td width="220"  nowrap="nowrap"  bgcolor="#EEEEEE"   > 
        <div align="left"> <?php do {  
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
      <td width="30" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['anzahl']; ?> ST </div></td>
      <td width="30" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><?php echo $row_rst9['lagerort']; ?></div></td>
      <td width="100" nowrap="nowrap"  bgcolor="#EEEEEE"   > 
        <div align="right">
          <input type="hidden" name="MM_update2" value="form3">
          <input name="hlokz" type="hidden" id="hlokz" value="1">
          <input name="idruck" type="hidden" id="idruck" value="<?php echo $row_rst9['id_ruck']; ?>">
          <img src="picture/b_drop.png" width="16" height="16"> 
          <input name="delete" type="submit" id="delete" value="l&ouml;schen">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form3">
  </form>

<?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?><br>
  insgesamt <?php echo $totalRows_rst9 ?> Komponenten r&uuml;ckgemeldet. <a href="getexcel.php?url_fmid=<?php echo $row_rst4['fmid']; ?>"><img src="picture/ruckmeldung.png" width="100" height="16" border="0"></a><br>
</table>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
  <div align="center"><font color="#FF0000"><strong>Hinweis: noch keine Teile 
    r&uuml;ckmeldet</strong>.</font> <br>
    <br>
  </div>
  <?php } // Show if recordset empty ?>


<form action="<?php echo $editFormAction; ?>" name="form2" method="POST">
    <table width="500" border="0" cellpadding="0" cellspacing="0">
      <tr bgcolor="#CCCCFF">
        <td colspan="3"><em><strong><img src="picture/sap.png" width="16" height="16"> 
          R&uuml;ckmeldung-Material</strong></em></td>
      </tr>
      <tr bgcolor="#CCCCFF"> 
        <td colspan="3"><select name="komponente" id="select3">
            <?php
do {  
?>
            <option value="<?php echo $row_rst8['kompnr']?>"<?php if (!(strcmp($row_rst8['kompnr'], $HTTP_POST_VARS['komponente']))) {echo "SELECTED";} ?>><?php echo  $row_rst8['kompnr']." - ".substr(($row_rst8['kompname']),0,50);?></option>
            <?php
} while ($row_rst8 = mysql_fetch_assoc($rst8));
  $rows = mysql_num_rows($rst8);
  if($rows > 0) {
      mysql_data_seek($rst8, 0);
	  $row_rst8 = mysql_fetch_assoc($rst8);
  }
?>
          </select></td>
      </tr>
      <tr bgcolor="#CCCCFF"> 
        <td width="142">Material <br>
          ausw&auml;hlen:<img src="picture/help2.gif" alt="Materialentnahme f&uuml;r R&uuml;ckmeldungen" width="16" height="16"></td>
        <td width="202" > <br>
          Anzahl: 
          <input name="anzahl" type="text" id="anzahl" value="1" size="5"> <br>
          Lagerort: 
          <select name="lagerort">
            <option value="R601">R601</option>
            <option value="Sond">Sond</option>
            <option value="------">-------</option>
            <option value="P602">P602</option>
            <option value="H601">H601</option>
            <option value="unbekannt">unbekannt</option>
          </select></td>
        <td width="156"> <div align="right"> 
            <input type="hidden" name="MM_insert2" value="form2">
            <input name="idfm" type="hidden" id="idfm" value="<?php echo $row_rst4['fmid']; ?>">
            <img src="picture/b_newdb.png" width="16" height="16"> 
            <input name="add" type="submit" id="add" value="hinzuf&uuml;gen">
          </div></td>
      </tr>
      <tr bgcolor="#CCCCFF"> 
        <td colspan="2">oder Materialnummer einscannen: </td>
        <td><input name="scanfeld" type="text" id="scanfeld"></td>
      </tr>
    </table>
  </form>



<table width="500" border="0" cellspacing="1" cellpadding="0">
  <tr> 
      <td colspan="2" ><img src="picture/folder_open.gif" width="16" height="16"> 
        Reparaturvorlagen</td>
    <td width="83">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form4" method="post" action="<?php echo $editFormAction; ?>">
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td width="36" nowrap="nowrap"  bgcolor="#EEEEEE" ><a href="<?php echo $editFormAction; ?>&url_detail=<?php echo $row_rst10['id_asml']; ?>"><img src="picture/bssctoc1.gif" width="32" height="16" border="0"></a> 
      <td><?php echo $row_rst10['gruppe']; ?> - <?php echo $row_rst10['name']; ?> <input name="hurl_id_asml" type="hidden" id="hurl_id_asml2" value="<?php echo $row_rst10['id_asml']; ?>"> 
        <input name="idfm" type="hidden" id="idfm" value="<?php echo $row_rst4['fmid']; ?>">
        <input name="fmartikelid" type="hidden" id="fmartikelid" value="<?php echo $row_rst4['fmartikelid']; ?>"> 
        <div align="right"> </div></td>
      <td width="83"><div align="right">
          <input name="ladd" type="submit" id="ladd3" value="hinzuf&uuml;gen">
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
          <font size="1"><?php echo $row_rst8['anzahl']; ?> St&uuml;ck - <?php echo $row_rst8['kompnr']; ?> - <?php echo $row_rst8['kompname']; ?></font></td>
      <td>&nbsp;</td>
    </tr>
    <?php } while ($row_rst8 = mysql_fetch_assoc($rst8)); ?>
    <?php } /* ende von rst8 */?>
  </form>
  <?php } while ($row_rst10 = mysql_fetch_assoc($rst10)); ?>
</table>
</td>
    </tr>
  </table>
<p>&nbsp; </p>

<?php include("la4.rstuserst.php");?>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rst7);

mysql_free_result($rst8);

mysql_free_result($rst42);

mysql_free_result($rst91);

mysql_free_result($rst9);

mysql_free_result($rsttyp);
?>
</p>
<?php include("footer.tpl.php"); ?>
