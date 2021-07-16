<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/fedatenbank.php'); ?>
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
?>
<?php require_once('../Connections/fedatenbank.php'); ?>
<?php  $la = "p212";

if ((isset($HTTP_POST_VARS["add"]))) {

	if ((strlen($HTTP_POST_VARS['datum'])<1)){
		$datum=$HTTP_POST_VARS['datum']="now()";
	} else{
		$datum=GetSQLValueString($HTTP_POST_VARS['datum'], "date");
	}

if ((strlen($HTTP_POST_VARS['termin'])<1)){
		$termin=$HTTP_POST_VARS['termin']="now()";
	} else{
		$termin=GetSQLValueString($HTTP_POST_VARS['termin'], "date");
	}

  $insertSQL = sprintf("INSERT INTO projektdatendaten (prj_id, datum, erstellt, beschreibung, status, termin, arbeitszeit) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_prj_id'], "int"),
                       $datum,
                       GetSQLValueString($HTTP_POST_VARS['beteiligte'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['status'], "int"),
					   $termin,
                       GetSQLValueString($HTTP_POST_VARS['arbeitsaufwand'], "text"));

  mysql_select_db($database_fedatenbank, $fedatenbank);
  $Result1 = mysql_query($insertSQL, $fedatenbank) or die(mysql_error());
}


$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE projektdaten SET prj_nummer=%s, prj_ueberschrift=%s, prj_beschreibung=%s, qualitaetsverbesserung=%s, kostenreduzierung=%s, fertigungsoptimierung=%s, zeichnungsanpassung=%s, teileanpassung=%s, servicefreundlichkeit=%s, sonstiges=%s, abgeschlossen=%s, datumabschluss=%s, eingestellt=%s, grund=%s, typ=%s, link=%s, kunde=%s, id_kundennummer=%s, kundenberater=%s, land=%s, projektleiter=%s, projektkaufmann=%s, angebotsnummer=%s, bestellnummer=%s, auftragsnrwz=%s, auftragsnrteile=%s, datumae=%s, datumpe=%s, zahlungsbedingungen=%s, auftragswertteile=%s, vertrag=%s, porganisation=%s, beteiligte=%s, endterminwz=%s, serienstart=%s, datumme=%s, gwlbedingungen=%s, ponalen=%s WHERE id_prj=%s",
                       GetSQLValueString($HTTP_POST_VARS['prj_nummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ueberschrift'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['qualitaetverbesserung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['kostenreduzierung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['fertigungsoptimierung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['zeichnungsanpassung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['teileanpassung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['servicefreundlichkeit']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['sonstiges']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['abgeschlossen']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datumabschluss'], "date"),
                       GetSQLValueString(isset($HTTP_POST_VARS['eingestellt']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['grund'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['typ'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['link'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['kunde'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['id_kundennummer'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['kundenberater'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['land'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['projektleiter'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['projektkaufmann'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['angebotsnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['bestellnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['auftragsnrwz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['auftragsnrteile'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datumae'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['datumpe'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['zahlungsbedingungen'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['auftragswertteile'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['vertrag'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['porganisation'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beteiligte'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['endterminwz'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['serienstart'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['datumme'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['gwlbedingungen'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ponalen'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_prj_id'], "int"));

  mysql_select_db($database_fedatenbank, $fedatenbank);
  $Result1 = mysql_query($updateSQL, $fedatenbank) or die(mysql_error());
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rst2 = "SELECT projektklassen.beschreibung, projektdaten.id_prj, projektdaten.prj_nummer, projektdaten.prj_datum, projektdaten.prj_erstellt, projektdaten.prj_ueberschrift, projektdaten.abgeschlossen, projektdaten.eingestellt, projektdaten.typ, projektdaten.prioritaet, projektdatendaten.prjd_id, projektdatendaten.datum, projektdatendaten.erstellt, projektdatendaten.beschreibung, projektdatendaten.arbeitszeit, projektdatendaten.status, projektdatendaten.termin FROM projektdaten, projektklassen, projektdatendaten WHERE projektklassen.id_prj_typ= projektdaten.typ AND projektdatendaten.prj_id = projektdaten.id_prj AND projektdatendaten.prj_id = '$url_prj_id' ORDER BY projektdatendaten.datum desc";
$rst2 = mysql_query($query_rst2, $fedatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rst3 = "SELECT * FROM projektdaten WHERE projektdaten.id_prj='$url_prj_id'";
$rst3 = mysql_query($query_rst3, $fedatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rsttyp = "SELECT projektklassen.id_prj_typ, projektklassen.beschreibung FROM projektklassen ORDER BY projektklassen.beschreibung";
$rsttyp = mysql_query($query_rsttyp, $fedatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstkunde = "SELECT kundendaten.idk, kundendaten.firma, kundendaten.plz, kundendaten.ort, kundendaten.kundenummer FROM kundendaten";
$rstkunde = mysql_query($query_rstkunde, $qsdatenbank) or die(mysql_error());
$row_rstkunde = mysql_fetch_assoc($rstkunde);
$totalRows_rstkunde = mysql_num_rows($rstkunde);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "p1.php?url_user=".$row_rst1['id']."&print=0";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["print"])) ) {
$updateGoTo = "p212.php?url_user=".$row_rst1['id']."&url_prjd_id=".$HTTP_POST_VARS["hurl_prjd_id"]."&print=1";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



if ((isset($HTTP_POST_VARS["editprj"])) ) {
$updateGoTo = "p212.php?url_user=".$row_rst1['id']."&url_prj_id=".$HTTP_POST_VARS["hurl_prj_id"];

  header(sprintf("Location: %s", $updateGoTo));
}
if ((isset($HTTP_POST_VARS["artikel"])) ) {
$updateGoTo = "p23.php?url_user=".$row_rst1['id']."&url_prj_id=".$HTTP_POST_VARS["hurl_prj_id"];

  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "p22.php?url_user=".$row_rst1['id']."&url_prjd_id=".$HTTP_POST_VARS["hurl_prjd_id"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
if ($print!=1){
	include("menu.tpl.php");
}
?>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="4"><strong>p212-Projektdaten - bearbeiten</strong></td>
    </tr>
    <tr> 
      <td colspan="4"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="194">Aktionen W&auml;hlen:</td>
      <td colspan="3"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
        <input name="print" type="submit" id="print" value="Druckansicht"> <input name="editprj" type="submit" id="editprj" value="Projektdaten bearbeiten"> 
        <input name="artikel" type="submit" id="artikel" value="Artikelversionen"> 
        <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $row_rst3['id_prj']; ?>"> 
        <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $url_prj_id; ?>"> 
        <div align="right"> </div></td>
    </tr>
    <tr> 
      <td rowspan="5"><img src="picture/icon_gr_fertigungsmeldung.gif" width="55" height="75"></td>
      <td width="199">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><strong><?php echo substr($row_rst2['prj_ueberschrift'],0,80)." ..."; ?></strong></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"><div align="right"><a href="pdfp22.php?url_prj_id=<?php echo $url_prj_id ?>"><img src="picture/btn_r7_c1.gif" width="133" height="26" border="0"></a><a href="pdfp2.php?url_prj_id=<?php echo $url_prj_id ?>"><img src="picture/btn_r6_c1.gif" width="133" height="26" border="0"></a></div></td>
    </tr>
    <tr> 
      <td colspan="3"><?php echo $row_rst3['prj_beschreibung']; ?></td>
    </tr>
    <tr> 
      <td><?php echo $row_rst3['prj_erstellt']; ?>- <?php echo $row_rst3['prj_datum']; ?></td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td colspan="3"> 
        <?php if  ($row_rst3['abgeschlossen']==0 ){?>
        <img src="picture/st1.gif" width="15" height="15"> 
        <?php }else{?>
        <img src="picture/st3.gif" width="15" height="15"> 
        <?php }?>
        <?php if ($row_rst3['prioritaet']>=2){; ?>
        <img src="picture/b_tipp.png" width="16" height="16"> 
        <?php }?>
        <?php if ($row_rst3['prioritaet']>=3){; ?>
        <img src="picture/b_tipp.png" width="16" height="16"> 
        <?php }?>
        <?php echo $row_rst3['id_prj']; ?>- <?php echo substr($row_rst3['prj_nummer'],0,20); ?></td>
    </tr>
    <tr> 
      <td>Typ:</td>
      <td colspan="3"> 
        <?php if ($row_rst3['qualitaetsverbesserung']==1){ ?>
        <img src="picture/certificate_ok.gif" alt="Qualit&auml;tsverbesserung" width="16" height="16"> 
        <?php }?>
        <?php if ($row_rst3['kostenreduzierung']==1){ ?>
        <img src="picture/iconBestOffer_16x16.gif" alt="Kostenreduzierung"> 
        <?php }?>
        <?php if ($row_rst3['zeichnungsanpassung']==1){ ?>
        <img src="picture/screenshots.gif" alt="Zeichnungsanpassung" width="16" height="16"> 
        <?php }?>
        <?php if ($row_rst3['teileanpassung']==1){ ?>
        <img src="picture/icon_tray_view_close.gif" alt="Teileanpassung" width="20" height="14"> 
        <?php }?>
        <?php if ($row_rst3['fertigungsoptimierung']==1){ ?>
        <img src="picture/stumbleupon.gif" alt="Fertigungsoptimierung" width="16" height="16"> 
        <?php }?>
        <?php if ($row_rst3['servicefreundlichkeit']==1){ ?>
        <img src="picture/iconFdbkBlu_16x16.gif" alt="Servicefreundlichkeit" width="16" height="16"> 
        <?php }?>
        <?php if ($row_rst3['sonstiges']==1){ ?>
        <img src="picture/iconFixedprice_16x16.gif" alt="Sonstiges" width="16" height="16"> 
        <?php }?>
      </td>
    </tr>
    <tr> 
      <td>&Uuml;berschrift:</td>
      <td colspan="3"> <input name="ueberschrift" type="text" id="ueberschrift" value="<?php echo $row_rst3['prj_ueberschrift']; ?>" size="60"> 
        <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $row_rst3['id_prj']; ?>"> 
      </td>
    </tr>
    <tr> 
      <td>Beschreibung:<br> <br> <br> <br> <br> </td>
      <td colspan="3"> <textarea name="beschreibung" cols="80" rows="8" id="beschreibung"><?php echo $row_rst3['prj_beschreibung']; ?></textarea></td>
    </tr>
    <tr> 
      <td><em>letzte Aktivit&auml;t:</em></td>
      <td colspan="3"><em><br>
        <?php echo $row_rst2['beschreibung']; ?></em></td>
    </tr>
    <tr> 
      <td>Projekttyp: / Projekt-Nr.</td>
      <td colspan="3"><select name="typ" id="typ">
          <?php
do {  
?>
          <option value="<?php echo $row_rsttyp['id_prj_typ']?>"<?php if (!(strcmp($row_rsttyp['id_prj_typ'], $row_rst3['typ']))) {echo "SELECTED";} ?>><?php echo $row_rsttyp['beschreibung']?></option>
          <?php
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?>
        </select>
        - 
        <input name="prj_nummer" type="text" id="prj_nummer" value="<?php echo $row_rst3['prj_nummer']; ?>"></td>
    </tr>
    <tr> 
      <td>Projektleiter:</td>
      <td colspan="3"> <input name="projektleiter" type="text" id="projektleiter" value="<?php echo $row_rst3['projektleiter']; ?>" size="80"></td>
    </tr>
    <tr> 
      <td>Projektkaufmann:</td>
      <td colspan="3"> <input name="projektkaufmann" type="text" id="projektkaufmann" value="<?php echo $row_rst3['projektkaufmann']; ?>" size="80"></td>
    </tr>
    <tr> 
      <td>Projektorganisation</td>
      <td colspan="3"> <textarea name="porganisation" cols="80" rows="3"><?php echo $row_rst3['porganisation']; ?></textarea></td>
    </tr>
    <tr> 
      <td>beteiligte Unternehmensbereiche</td>
      <td colspan="3"><textarea name="beteiligte" cols="80" rows="3" id="beteiligte"><?php echo $row_rst3['beteiligte']; ?></textarea></td>
    </tr>
    <tr> 
      <td>Meilensteine</td>
      <td colspan="3"> <input name="datumme" type="text" value="<?php echo $row_rst3['datumme']; ?>" size="80"> 
      </td>
    </tr>
    <tr> 
      <td>Projekter&ouml;ffnung:</td>
      <td colspan="3"> <input name="datumpe" type="text" value="<?php echo $row_rst3['datumpe']; ?>"></td>
    </tr>
    <tr> 
      <td>eingestellt am:</td>
      <td colspan="3"> <input <?php if (!(strcmp($row_rst3['eingestellt'],1))) {echo "checked";} ?> name="eingestellt" type="checkbox" id="eingestellt" value="1"> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;abgeschlossen: 
        <input <?php if (!(strcmp($row_rst3['abgeschlossen'],1))) {echo "checked";} ?> name="abgeschlossen" type="checkbox" id="abgeschlossen2" value="1">
        Datum: 
        <input name="datumabschluss" type="text" id="datumabschluss2" value="<?php echo $row_rst3['datumabschluss']; ?>" size="12"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form4\', \'datumabschluss\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
    </tr>
    <tr> 
      <td>Grund:</td>
      <td colspan="3"><textarea name="grund" cols="80" rows="4" id="textarea"><?php echo $row_rst3['grund']; ?></textarea></td>
    </tr>
    <tr> 
      <td><img src="picture/certificate_ok.gif" width="16" height="16"> Qualit&auml;tsverbesserung:</td>
      <td> <input <?php if (!(strcmp($row_rst3['qualitaetsverbesserung'],1))) {echo "checked";} ?> name="qualitaetverbesserung" type="checkbox" id="qualitaetverbesserung" value="1"></td>
      <td width="251"><img src="picture/screenshots.gif" width="16" height="16"> 
        Zeichnungsanpassung:</td>
      <td width="86"><input <?php if (!(strcmp($row_rst3['zeichnungsanpassung'],1))) {echo "checked";} ?> name="zeichnungsanpassung" type="checkbox" id="zeichnungsanpassung2" value="1"></td>
    </tr>
    <tr> 
      <td><img src="picture/iconBestOffer_16x16.gif"> Kostenreduzierung:</td>
      <td> <input <?php if (!(strcmp($row_rst3['kostenreduzierung'],1))) {echo "checked";} ?> name="kostenreduzierung" type="checkbox" id="kostenreduzierung" value="1"></td>
      <td> <img src="picture/icon_tray_view_close.gif" width="20" height="14">Teileanpassung:</td>
      <td><input <?php if (!(strcmp($row_rst3['teileanpassung'],1))) {echo "checked";} ?> name="teileanpassung" type="checkbox" id="teileanpassung2" value="1"></td>
    </tr>
    <tr> 
      <td><img src="picture/stumbleupon.gif" width="16" height="16"> Fertigungsoptimierung</td>
      <td> <input <?php if (!(strcmp($row_rst3['fertigungsoptimierung'],1))) {echo "checked";} ?> name="fertigungsoptimierung" type="checkbox" id="fertigungsoptimierung" value="1"></td>
      <td><img src="picture/iconFdbkBlu_16x16.gif" width="16" height="16"> Servicefreundlichkeit</td>
      <td><input <?php if (!(strcmp($row_rst3['servicefreundlichkeit'],1))) {echo "checked";} ?> name="servicefreundlichkeit" type="checkbox" id="servicefreundlichkeit2" value="1"></td>
    </tr>
    <tr> 
      <td><img src="picture/iconFixedprice_16x16.gif" width="16" height="16"> 
        Sonstiges</td>
      <td> <input <?php if (!(strcmp($row_rst3['sonstiges'],1))) {echo "checked";} ?> name="sonstiges" type="checkbox" id="sonstiges" value="1"></td>
      <td>&nbsp;</td>
      <td><input name="save" type="submit" id="save2" value="speichern"></td>
    </tr>
    <tr> 
      <td>Priorit&auml;t 
        <?php if ($row_rst2['prioritaet']>=2){; ?>
        <img src="picture/b_tipp.png" width="16" height="16"> 
        <?php }?>
        <?php if ($row_rst2['prioritaet']>=3){; ?>
        <img src="picture/b_tipp.png" width="16" height="16"> 
        <?php }?>
      </td>
      <td colspan="3"><select name="select">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['prioritaet']))) {echo "SELECTED";} ?>>nicht 
          festgelegt</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['prioritaet']))) {echo "SELECTED";} ?>>gering</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['prioritaet']))) {echo "SELECTED";} ?>>hoch</option>
        </select> </td>
    </tr>
    <tr> 
      <td>Ordner: Projektdaten</td>
      <td colspan="3"><input name="link" type="text" id="link" value="<?php echo $row_rst3['link']; ?>" size="80"> 
        <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>Link Projekdaten</td>
      <td colspan="3"><a href="<?php echo $row_rst3['link']; ?>"><?php echo $row_rst3['link']; ?></a> </td>
    </tr>
    <tr> 
      <td><strong>Kunden-Nr.</strong></td>
      <td colspan="3"> <select name="id_kundennummer" id="id_kundennummer">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['id_kundennummer']))) {echo "SELECTED";} ?>>keine 
          Zuordnung</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rstkunde['idk']?>"<?php if (!(strcmp($row_rstkunde['idk'], $row_rst3['id_kundennummer']))) {echo "SELECTED";} ?>><?php echo substr($row_rstkunde['firma'],0,40)." - ".$row_rstkunde['kundenummer'];?></option>
          <?php
} while ($row_rstkunde = mysql_fetch_assoc($rstkunde));
  $rows = mysql_num_rows($rstkunde);
  if($rows > 0) {
      mysql_data_seek($rstkunde, 0);
	  $row_rstkunde = mysql_fetch_assoc($rstkunde);
  }
?>
        </select></td>
    </tr>
    <tr> 
      <td><strong>Kunde:</strong></td>
      <td colspan="3"> <strong> 
        <input name="kunde" type="text" value="<?php echo $row_rst3['kunde']; ?>" size="80">
        </strong></td>
    </tr>
    <tr> 
      <td>Berater des Kunden:</td>
      <td colspan="3"> <input name="kundenberater" type="text" value="<?php echo $row_rst3['kundenberater']; ?>" size="80"></td>
    </tr>
    <tr> 
      <td>Land:</td>
      <td colspan="3"> <input name="land" type="text" value="<?php echo $row_rst3['land']; ?>" size="80"></td>
    </tr>
    <tr> 
      <td>Vertr&auml;ge</td>
      <td colspan="3"> <textarea name="vertrag" cols="80" rows="3"><?php echo $row_rst3['vertrag']; ?></textarea></td>
    </tr>
    <tr> 
      <td>Gew&auml;hrleistungsbedingungen</td>
      <td colspan="3"> <textarea name="gwlbedingungen" cols="80" rows="3"><?php echo $row_rst3['gwlbedingungen']; ?></textarea></td>
    </tr>
    <tr> 
      <td>Zahlungsbedingungen</td>
      <td colspan="3"><textarea name="zahlungsbedingungen" cols="80" rows="3"><?php echo $row_rst3['zahlungsbedingungen']; ?></textarea></td>
    </tr>
    <tr> 
      <td>P&ouml;nalen</td>
      <td colspan="3"><textarea name="ponalen" cols="80" rows="3"><?php echo $row_rst3['ponalen']; ?></textarea></td>
    </tr>
    <tr> 
      <td>Angebotsnummer</td>
      <td colspan="3"> <input name="angebotsnummer" type="text" value="<?php echo $row_rst3['angebotsnummer']; ?>"></td>
    </tr>
    <tr> 
      <td>Bestellnummer Kunde</td>
      <td colspan="3"> <input name="bestellnummer" type="text" value="<?php echo $row_rst3['bestellnummer']; ?>">
        Datum Auftragserteilung 
        <input name="datumae" type="text" value="<?php echo $row_rst3['datumae']; ?>"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr> 
      <td>Auftragswert Werkzeug (netto)</td>
      <td colspan="3"> <input name="auftragsnrwz" type="text" value="<?php echo $row_rst3['auftragsnrwz']; ?>" size="80"></td>
    </tr>
    <tr> 
      <td>Auftragsnummer WZ intern</td>
      <td colspan="3"> <input name="auftragsnrwz" type="text" value="<?php echo $row_rst3['auftragsnrwz']; ?>">
        Datum Endtermin Werkzeuge: 
        <input name="endterminwz" type="text" value="<?php echo $row_rst3['endterminwz']; ?>"></td>
    </tr>
    <tr> 
      <td>Auftragswert Teile (netto)</td>
      <td colspan="3"> <input name="auftragswertteile" type="text" value="<?php echo $row_rst3['auftragswertteile']; ?>" size="80"></td>
    </tr>
    <tr> 
      <td>Auftragsnummer Teile</td>
      <td colspan="3"> <input name="auftragsnrteile" type="text" value="<?php echo $row_rst3['auftragsnrteile']; ?>">
        Datum Serienstart teile 
        <input name="serienstart" type="text" value="<?php echo $row_rst3['serienstart']; ?>"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2">
</form>
<br>
<strong><em>neue Aktivit&auml;t anlegen:</em></strong><br>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="176" height="72">Beschreibung/ Aktivit&auml;t<br> <br> <br> <br> 
      </td>
      <td width="279"><em> 
        <textarea name="textarea" cols="50" rows="4"></textarea>
        </em></td>
      <td width="275">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td>Arbeitsaufwand</td>
      <td> <input name="arbeitsaufwand" type="text" id="arbeitsaufwand"> </td>
      <td>Status: 
        <select name="status" id="status">
          <option value="1">erledigt</option>
          <option value="0">geplant</option>
        </select></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td>Datum</td>
      <td> <input name="datum" type="text" id="datum"> <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $url_prj_id; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td> <div align="left">Termin 
          <input name="termin" type="text" id="termin">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'termin\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td>Beteiligte</td>
      <td><input name="beteiligte" type="text" id="beteiligte" value="<?php echo $row_rst1['name']; ?>"></td>
      <td><div align="right">
          <input name="add" type="submit" id="add" value="speichern">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>
<p>&nbsp;</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<em><strong>Liste der Projektdetails</strong></em><br>
<br>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="value">
    <td width="109">Status</td>
    <td width="109"><em><strong>Datum</strong></em></td>
    <td width="266"><em><strong>Beschreibung/ Aktivit&auml;t</strong></em></td>
    <td width="81"><em><strong>Benutzer</strong></em></td>
    <td width="155"><strong>Arbeitsaufwand</strong></td>
    <td width="119">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php   if ($row_rst2['typ']!=$typ){?>
  <tr bgcolor="#CCCCCC" > 
    <td colspan="9" nowrap="nowrap"><em><br>
      </em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');">
      <td    bgcolor="#EEEEEE" nowrap="nowrap">
	  
	  <?php if  ($row_rst2['termin']< (date("Y-m-d",time() ) )){?>
        <img src="picture/st1.gif" width="15" height="15"> 
        <?php }else{?>
        <img src="picture/st3.gif" width="15" height="15"> 
        <?php }?>
	  <?php if  ($row_rst2['status']==1 ){?>
        <img src="picture/fertig.gif" width="15" height="15"> 
        <?php }else{?>
        <img src="picture/b_edit.png" width="16" height="16"> 
        <?php }?>
	  
	  </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst2['datum']; ?> - </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo substr(nl2br($row_rst2['beschreibung']),0,80)." .."; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['erstellt']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['arbeitszeit']; ?> Std.</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_prjd_id" type="hidden" id="hurl_prjd_id" value="<?php echo $row_rst2['prjd_id']; ?>">
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php
    $typ=$row_rst2['typ'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<?php } // Show if recordset not empty ?>
<font color="#FF0000">
<?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
 <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rsttyp);

mysql_free_result($rstkunde);

?>
<?php include("footer.tpl.php"); ?>
