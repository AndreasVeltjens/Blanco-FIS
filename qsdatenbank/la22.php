<?php
require_once('Connections/qsdatenbank.php');
require_once('../Connections/qsdatenbank.php');
include ('IfisArtikelVersionConfig.php');
$la = "la22";
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {
  $insertSQL = sprintf("INSERT INTO artikelversionsstuecklisten (artikelnummer, bezeichnung, hinweis, st_art, menge, version_id) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['artikelnummer'], "text"),
                       GetSQLValueString($_POST['bezeichnung'], "text"),
                       GetSQLValueString($_POST['hinweis'], "text"),
                       GetSQLValueString($_POST['st_art'], "text"),
                       GetSQLValueString($_POST['menge'], "text"),
                       GetSQLValueString($_POST['versions_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($_POST['delete'])) && ($_POST['hst_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM artikelversionsstuecklisten WHERE st_id=%s",
                       GetSQLValueString($_POST['hst_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
}


if ((isset($_POST["erstmuster"]))) {
if ($_POST['sn2']==""){$_POST['sn2']="none";}
if ($_POST['sn3']==""){$_POST['sn3']="none";}
if ($_POST['sn4']==""){$_POST['sn4']="none";}

  $insertSQL = sprintf("INSERT INTO fertigungsmeldungen (fartikelid, fuser, fdatum, fsn, version, fsn1, fsn2, fsn3, fsn4, pgeraet, signatur, fsonder, ffrei, fnotes,fnotes1,frfid) VALUES (%s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hurl_artikelid'], "int"),
                       GetSQLValueString($_POST['hurl_user'], "int"),
                       "now()",
                       GetSQLValueString($_POST['fsn'], "text"),
					   GetSQLValueString($_POST['version'], "text"),
                       GetSQLValueString($_POST['sn1'], "text"),
                       GetSQLValueString($_POST['sn2'], "text"),
                       GetSQLValueString($_POST['sn3'], "text"),
                       GetSQLValueString($_POST['sn4'], "text"),
					   GetSQLValueString($_POST['pgeraet'], "text"),
					   GetSQLValueString($_POST['signatur'], "text"),
                       GetSQLValueString(isset($_POST['sonder']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['frei']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['notes'], "text"),
					   GetSQLValueString($_POST['notes1'], "text"),
					   GetSQLValueString($_POST['frfid'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
$saveok=1;
$old_sn = $_POST['sn'];
$oktxt="Datensatz f�r SN $old_sn erfolgreich angelegt. Ettikettdruck unten.";
}



if ((isset($_POST["save"])) or (isset($_POST["saveonly"])) or (isset($_POST["freigabe"]))) {
if (isset($_POST["freigabe"])){
  $_POST['beschreibung']=$_POST['beschreibung']." Mitarbeiter ".$row_rst1['name']." Freigabe am ".date('Y-m-d',time());
}
  $updateSQL = sprintf("UPDATE artikelversionen SET beschreibung=%s, version=%s, absn=%s, Datum=%s ,
materialnummer=%s ,kundenmaterialnummer=%s , ean13=%s, pruefzeichen=%s, configprofil=%s WHERE id=%s",
                       GetSQLValueString($_POST['beschreibung'], "text"),
					   GetSQLValueString($_POST['version'], "text"),
					    GetSQLValueString($_POST['absn'], "int"),
                       GetSQLValueString($_POST['datum'], "date"),
					   GetSQLValueString($_POST['materialnummer'], "text"),
					    GetSQLValueString($_POST['kundenmaterialnummer'], "text"),
					    GetSQLValueString($_POST['ean13'], "text"),
						 GetSQLValueString($_POST['pruefzeichen'], "text"),
                      GetSQLValueString($_POST['configprofil'], "text"),
                       GetSQLValueString($_POST['hurl_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

 if (isset($_POST["freigabe"])) {/* Freigabe in Artikeldaten */
 
 $updateSQL = sprintf("UPDATE artikeldaten SET version=%s, Nummer=%s, Zeichnungsnummer=%s,ean13=%s  WHERE artikelid=%s",
                        GetSQLValueString($_POST['version'], "text"),
					   GetSQLValueString($_POST['materialnummer'], "text"),
					    GetSQLValueString($_POST['kundenmaterialnummer'], "text"),
					    GetSQLValueString($_POST['ean13'], "text"),
						 
                       GetSQLValueString($_POST['hurl_artikelid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
 
 }/* ende freigabe in Artikeldaten */


if (!isset($_POST["saveonly"])) {

  $updateGoTo = "la21.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
}




mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM  artikeldaten, artikelversionen WHERE artikelversionen.id = '$url_id' and artikeldaten.artikelid= artikelversionen.artikelid";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

$configprofil = new IfisArtikelVersionConfig($row_rst2['configprofil']);

if (isset($_POST["LoadDefaults"])) {
  $configprofil->LoadDefaults();
  $row_rst2['configprofil']=$configprofil->GetJson();
}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst20 = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert=1  AND artikeldaten.artikelid='$url_artikelid'";
$rst20 = mysql_query($query_rst20, $qsdatenbank) or die(mysql_error());
$row_rst20 = mysql_fetch_assoc($rst20);
$totalRows_rst20 = mysql_num_rows($rst20);

$maxRows_rst_stueckliste = 50;
$pageNum_rst_stueckliste = 0;
if (isset($HTTP_GET_VARS['pageNum_rst_stueckliste'])) {
  $pageNum_rst_stueckliste = $HTTP_GET_VARS['pageNum_rst_stueckliste'];
}
$startRow_rst_stueckliste = $pageNum_rst_stueckliste * $maxRows_rst_stueckliste;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_stueckliste = "SELECT * FROM artikelversionsstuecklisten WHERE artikelversionsstuecklisten.version_id='$url_id' ORDER BY artikelversionsstuecklisten.st_art" ;
$query_limit_rst_stueckliste = sprintf("%s LIMIT %d, %d", $query_rst_stueckliste, $startRow_rst_stueckliste, $maxRows_rst_stueckliste);
$rst_stueckliste = mysql_query($query_limit_rst_stueckliste, $qsdatenbank) or die(mysql_error());
$row_rst_stueckliste = mysql_fetch_assoc($rst_stueckliste);

if (isset($HTTP_GET_VARS['totalRows_rst_stueckliste'])) {
  $totalRows_rst_stueckliste = $HTTP_GET_VARS['totalRows_rst_stueckliste'];
} else {
  $all_rst_stueckliste = mysql_query($query_rst_stueckliste);
  $totalRows_rst_stueckliste = mysql_num_rows($all_rst_stueckliste);
}
$totalPages_rst_stueckliste = ceil($totalRows_rst_stueckliste/$maxRows_rst_stueckliste)-1;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsts = "SELECT artikeldaten.Bezeichnung, artikeldaten.Nummer,artikeldaten.Zeichnungsnummer, artikeldaten.version FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid'";
$rsts = mysql_query($query_rsts, $qsdatenbank) or die(mysql_error());
$row_rsts = mysql_fetch_assoc($rsts);
$totalRows_rsts = mysql_num_rows($rsts);

if ((isset($_POST["cancel"])) ) {
$updateGoTo = "la21.php?url_user=".$row_rst1['id']."&url_artikelid=".$_POST["hurl_artikelid"]."&url_id=".$_POST["hurl_id"];;
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Cancel)";}

if ((isset($_POST["edit"])) ) {
$updateGoTo = "la22.php?url_user=".$row_rst1['id']."&url_artikelid=".$_POST["hurl_artikelid"]."&url_id=".$_POST["hurl_id"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Sprintf)";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr> 
    <td colspan="3"><strong>la22- Artikelversionen bearbeiten</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="143">Aktionen W&auml;hlen:</td>
    <td width="620"> <form name="form2" method="post" action="">
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
      </form></td>
    <td width="217"> <div align="right"> </div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>Version f&uuml;r Material:</td>
    <td><?php echo $row_rsts['Bezeichnung']; ?></td>
    <td>letzte freigegebene Version:</td>
  </tr>
  <tr> 
    <td>Materialnummer:</td>
    <td><?php echo $row_rsts['Nummer']; ?> </td>
    <td><div align="left"><?php echo $row_rsts['version']; ?></div></td>
  </tr>
  <tr>
    <td>K-Materialnummer:</td>
    <td><?php echo $row_rsts['Zeichnungsnummer']; ?></td>
    <td>&nbsp;</td>
  </tr>
</table>
  
  
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td>ID-Version</td>
      <td>&Auml;nderungsantrag <?php echo $row_rst2['id']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="143">Beschreibung<br> <br> <br> <br> <br> <br> <br> <br> <br> 
      </td>
      <td width="486">
        <textarea name="beschreibung" cols="60" rows="8" id="beschreibung"><?php echo $row_rst2['beschreibung']; ?></textarea>
      </td>
      <td width="351">&nbsp;</td>
    </tr>

    <tr>
      <td width="143">Konfiguration<br> <br> <br> <br> <br> <br> <br> <br> <br>
      </td>
      <td width="486">
        <textarea name="configprofil" cols="60" rows="40" id="configprofil"><?php echo $row_rst2['configprofil']; ?></textarea>
      </td>
      <td width="351">Konfigurationspr&uuml;fung<br>
    <?php echo $configprofil->ShowProfilStatus(); ?>
      <hr>

        <?php echo $configprofil->ShowConfig(); ?>
        <hr>

      </td>
    </tr>
    <tr>
      <td colspan="3">
        <?php echo $configprofil->ShowControlFields(); ?>
      </td>

    </tr>

    <tr>
      <td colspan="3">
            Materialnummer bei Elektrische Bauteilen: <?php echo $configprofil->FindArtikelNummer('grey','DE','no'); ?><hr>
            Materialnummer (Standard Vorauswahl): <?php echo $configprofil->FindArtikelNummer('grey','','no'); ?><hr>
            Materialnummer bei Laserung: <?php echo $configprofil->FindArtikelNummer('grey','','yes'); ?><hr>
            Materialnummer bei grau CH: <?php echo $configprofil->FindArtikelNummer('grey','CH','no'); ?><hr>
            <a href="ifisArtikelVersionConfigFile.php?url_id=<?php echo $_GET["url_id"]; ?>" target="_blank">Download Konfiguration</a>
          <hr>
      </td>

    </tr>

    <tr> 
      <td>Datum:</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo $row_rst2['Datum']; ?>"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
      <td><div align="right">
          <input name="LoadDefaults" type="submit" id="LoadDefaults" value="Vorlage laden">
          <input name="saveonly" type="submit" id="saveonly" value="Nur &Auml;nderung speichern">
          <input name="save" type="submit" id="save" value="Nur &Auml;nderung speichern und zurück">
        </div></td>
    </tr>
    <tr> 
      <td>User-ID</td>
      <td><?php echo $row_rst2['user']; ?> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst3['id'], $row_rst2['user']))) {echo $row_rst3['name'];} ?> 
        <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?></select>
        </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td> <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $row_rst2['id']; ?>"> 
        <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>"> 
      </td>
      <td>&nbsp;</td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>g&uuml;ltig ab Serienummer</td>
      <td> <input name="absn" type="text" id="absn" value="<?php echo $row_rst2['absn']; ?>"></td>
      <td><div align="right">
          <input name="freigabe" type="submit" id="freigabe" value="Diese Version jetzt freigeben">
        </div></td>
    </tr>
    <tr> 
      <td>Version</td>
      <td><input name="version" type="text" id="version" value="<?php echo $row_rst2['version']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Materialnummer</td>
      <td><input name="materialnummer" type="text" id="materialnummer" value="<?php echo $row_rst2['materialnummer']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td>Kundenmaterialnummer</td>
      <td><input name="kundenmaterialnummer" type="text" id="kundenmaterialnummer" value="<?php echo $row_rst2['kundenmaterialnummer']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><img src="picture/iconFixedprice_16x16.gif" width="16" height="16"> 
        EAN13-Code</td>
      <td><input name="ean13" type="text" size="60" value="<?php echo $row_rst2['ean13']; ?>"></td>
      <td>auf Etikettdruck von EAN13</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><img src="picture/certificate_ok.gif" width="16" height="16">Pruefzeichen</td>
      <td><input name="pruefzeichen" type="text" size="60" value="<?php echo $row_rst2['pruefzeichen']; ?>"></td>
      <td>im root-Ordner ../picture</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
<hr>
<p><strong>St&uuml;cklistenzuordnung/ Dokumente / Komponenten und Baugruppen</strong> 
  -&gt; <a href="dokuset.tmp1.php?url_artikelid=<?php echo $url_artikelid ?>" target="_blank">Dokuset anzeigen</a></p>
<table width="980" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCFF"> 
    <td colspan="6">Neue Komponenten anf&uuml;gen </td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td width="153">Artikelnummer</td>
    <td width="204">Bezeichung</td>
    <td width="157">Anzahl</td>
    <td width="146">weitere Hinweise</td>
    <td width="146">&nbsp;</td>
    <td width="146">ID Art</td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
      <td> <input name="artikelnummer" type="text" id="artikelnummer"> </td>
      <td> <input name="bezeichnung" type="text" id="bezeichnung"></td>
      <td> <input name="menge" type="text" id="menge" value=" St&uuml;ck"></td>
      <td> <input name="hinweis" type="text" id="bezeichnung3" value=" "></td>
      <td> <select name="st_art" id="select2">
          <option value="Komponente">Komponente</option>
          <option value="PRT">PRT</option>
		  <option value="ASM">ASM</option>
          <option value="Information">Information</option>
          <option value="Anleitung">Anleitung</option>
          <option value="Bild">Bild</option>
        </select></td>
      <td> <input name="versions_id" type="hidden" id="versions_id" value="<?php echo $url_id; ?>"> 
        <input name="anlegen" type="submit" id="anlegen" value="Anlegen"></td>
      <input type="hidden" name="MM_insert" value="form4">
    </form>
  </tr>
  <tr> 
    <td><?php if ($totalRows_rst_stueckliste == 0) { // Show if recordset empty ?>
      <font color="#FF0000">keine St&uuml;ckliste vorhanden.</font> 
      <?php } // Show if recordset empty ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php if ($totalRows_rst_stueckliste > 0) { // Show if recordset not empty ?>
  <?php do { ?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td><?php echo $row_rst_stueckliste['artikelnummer']; ?></td>
    <td><?php echo $row_rst_stueckliste['bezeichnung']; ?></td>
    <td><?php echo $row_rst_stueckliste['menge']; ?></td>
    <td><?php echo $row_rst_stueckliste['hinweis']; ?></td>
    <td> 
      <?php if ( $row_rst_stueckliste['st_art']=='Bild'){; ?>
      <img src="../documents/materialbilder/ersatzteilsets/<?php echo $row_rst_stueckliste['bezeichnung']; ?>.png" width="60"> 
      <?php }else { ?>
	  
	  <?php echo $row_rst_stueckliste['st_art']; ?>
	  <?php }?>
    </td>
    <td> 
      <form name="form5" method="post" action="">
        <input name="delete" type="submit" id="delete" value="L&ouml;schen">
        <input name="hst_id" type="hidden" id="hst_id" value="<?php echo $row_rst_stueckliste['st_id']; ?>">
      </form></td>
  </tr>
  <?php } while ($row_rst_stueckliste = mysql_fetch_assoc($rst_stueckliste)); ?>
  <tr> 
    <td colspan="6"><br>
      Eintr&auml;ge: <?php echo $totalRows_rst_stueckliste ?> </td>
  </tr>
  <?php } // Show if recordset not empty ?>
</table>
<hr>
<form name="form3" method="post" action="">
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><strong>f&uuml;r Version&auml;nderungen gibt es ein Erstmuster 
        oder Nachbemusterung</strong></td>
    </tr>
    <tr> 
      <td width="158">SN</td>
      <td width="223"><input name="fsn" type="text" id="fsn" value="<?php echo $row_rst2['absn']; ?>"></td>
      <td width="349">&nbsp;</td>
    </tr>
    <tr> 
      <td>Version</td>
      <td><input name="version" type="text" id="version" value="<?php echo $row_rst2['version']; ?>"></td>
      <td> <div align="right"> 
          <input name="erstmuster" type="submit" id="erstmuster" value="Erstmuster anlegen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
<table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr> 
    <td>ausgew&auml;hlter Ger&auml;tetyp:</td>
    <td><strong><?php echo $row_rst20['Bezeichnung']; ?> 
      <input name="anzahlp" type="hidden" id="anzahlp" value="<?php echo $row_rst20['anzahlp']; ?>">
      <input name="hurl_user" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
      <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst20['artikelid']; ?>">
      </strong></td>
    <td width="206" rowspan="10"><div align="right"></div>
      <div align="right"><a href="../documents/materialbilder/<?php echo $row_rst20['Nummer'] ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" alt="EAN/UPC Nummer: <?php echo $row_rst20['ean13'] ?>" width="180" height="140" border="0"></a> 
      </div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <?php if ($totalRows_letztebekanntesn == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Keine letzte Seriennummer gefunden. Bitte 
      Eingabe pr&uuml;fen.</font></strong> 
      <?php } // Show if recordset empty ?>
    </td>
  </tr>
  <tr> 
    <td>letzte Bekannte SN:</td>
    <td><?php echo $old_sn; ?> </td>
  </tr>
  <?php if ($totalRows_letztebekanntesn > 0) { // Show if recordset not empty ?>
  <tr> 
    <td><strong>Seriennummer: </strong></td>
    <td><strong><?php echo $row_letztebekanntesn['fsn']+1; ?></strong> <input name="sn" type="hidden" id="sn7" value="<?php echo $row_letztebekanntesn['fsn']+1; ?>"> 
      <input name="version" type="text" id="version" value="<?php echo $row_rst2['version']; ?>"> 
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td> <div align="right"> 
        <select name="select2">
          <?php
do {  
?>
          <option value="<?php echo $row_rstversion['version']?>"<?php if (!(strcmp($row_rstversion['version'], $row_rstversion['version']))) {echo "SELECTED";} ?>><?php echo $row_rstversion['version']?></option>
          <?php
} while ($row_rstversion = mysql_fetch_assoc($rstversion));
  $rows = mysql_num_rows($rstversion);
  if($rows > 0) {
      mysql_data_seek($rstversion, 0);
	  $row_rstversion = mysql_fetch_assoc($rstversion);
  }
?>
        </select>
        <img src="picture/help.gif" width="17" height="17"></div></td>
  </tr>
  <?php } // Show if recordset not empty ?>
  <tr> 
    <td><em><strong><img src="picture/add.gif" width="15" height="14"> Bauteildaten</strong></em></td>
    <td>&nbsp;</td>
  </tr>
    <?php if ($row_rst20['anzahlp']>=1){ ?>
    <tr> 
    <td width="139">Seriennummer Heizung <br>
      Fertigungsdatum </td>
    <td width="385"> <input name="sn1" type="text" id="sn1"> <font size="1">Eingabeformat: 
      2501BG , 08/11 od. 4072</font></td>
  </tr>
  <?php }?>
  <?php if ($row_rst2['anzahlp']>=2){ ?>
  <tr> 
    <td>Seriennummer Regler</td>
    <td> <input name="sn2" type="text" id="sn2"> <font size="1">Eingabeformat: 
      110304/77 oder 106823</font></td>
  </tr>
  <?php }?>
  <?php if ($row_rst2['anzahlp']>=3){ ?>
  <tr> 
    <td>Seriennummer L&uuml;fter</td>
    <td> <input name="sn3" type="text" id="sn3"> <font size="1">Eingabeformat: 
      08/11</font></td>
  </tr>
  <?php }?>
  <?php if ($row_rst2['anzahlp']>=4){ ?>
  <tr> 
    <td>sonstiges</td>
    <td><input name="sn4" type="text" id="sn4"></td>
  </tr>
  <?php }?>
</table>
<table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr> 
    <td width="188">Pr&uuml;fung i.O.</td>
    <td width="753"><input name="frei" type="checkbox" id="frei" value="1"> <img src="picture/st1.gif" width="15" height="15"></td>
    <td width="39">&nbsp;</td>
  </tr>
  <tr> 
    <td>Datum</td>
    <td> automatisch 
        <script>
                    <!--
                    document.write('<a title="Kalender" href="javascript:openCalendar(\'lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci\', \'insertForm\', \'datum\', \'date\')"><img class="calendar" src="./picture/b_calendar.png" alt="Kalender"/></a>');
                    //-->
                    </script></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>Sonderfreigabe erforderlich</td>
    <td><input name="sonder" type="checkbox" id="sonder" value="1"> <img src="picture/st2.gif" width="15" height="15"></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><p>Grund:<br>
        &Auml;nderungen: </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td><textarea name="notes" cols="80" rows="7" id="notes"></textarea></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><p>Funktionstest <br>
        und Messwerte:<br>
      </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <?php 
		
		$url_fmnotes1="Schutzleiterwiderstand [Ohm] SOLL=0,1   IST= \n";
		$url_fmnotes1=$url_fmnotes1."Isolationswiderstand [MOhm] SOLL>=0,7   IST= \n";
		$url_fmnotes1=$url_fmnotes1."Hochspannungspr�fung [1,25 kV][mA] SOLL<=5,0 mA   IST= \n";
				
		?>
    <td><textarea name="notes1" cols="80" rows="7" id="notes"><?php echo $url_fmnotes1; ?></textarea></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>Signatur</td>
    <td><img src="picture/s_process.png" width="16" height="16"><?php echo md5(time()); ?>&nbsp; 
      <input name="signatur" type="hidden" id="signatur" value="<?php echo md5(time()); ?>"></td>
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
    <td>RFID</td>
    <td><img src="picture/smextlink.gif" width="16" height="9"> <input name="frfid" type="text" id="frfid"></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

mysql_free_result($rst20);

mysql_free_result($rst_stueckliste);

mysql_free_result($rsts);
?>
</p>

  <?php include("footer.tpl.php"); ?>
