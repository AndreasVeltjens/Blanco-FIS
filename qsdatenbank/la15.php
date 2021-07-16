<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "la15";
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

if ((isset($HTTP_POST_VARS["save"]))&& ($HTTP_POST_VARS['sn']!="") && ($HTTP_POST_VARS['snbis']>$HTTP_POST_VARS['sn'])) {
if ($HTTP_POST_VARS['sn2']==""){$HTTP_POST_VARS['sn2']="none";}
if ($HTTP_POST_VARS['sn3']==""){$HTTP_POST_VARS['sn3']="none";}
if ($HTTP_POST_VARS['sn4']==""){$HTTP_POST_VARS['sn4']="none";}
do {
  $insertSQL = sprintf("INSERT INTO fertigungsmeldungen (fartikelid, fuser, fdatum, fsn, version, fsn1, fsn2, fsn3, fsn4, pgeraet, signatur, fsonder, ffrei, fnotes) VALUES (%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_artikelid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['sn'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['version'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn4'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['pgeraet'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['signatur'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['sonder']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['frei']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['notes'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
$HTTP_POST_VARS['sn']=$HTTP_POST_VARS['sn']+1;

} while ($HTTP_POST_VARS['snbis']>=$HTTP_POST_VARS['sn']);


  $insertGoTo = "la11.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztebekanntesn = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$url_artikelid' AND fertigungsmeldungen.fsn <=100000 ORDER BY fertigungsmeldungen.fsn desc";
$letztebekanntesn = mysql_query($query_letztebekanntesn, $qsdatenbank) or die(mysql_error());
$row_letztebekanntesn = mysql_fetch_assoc($letztebekanntesn);
$totalRows_letztebekanntesn = mysql_num_rows($letztebekanntesn);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstversion = "SELECT * FROM artikelversionen WHERE artikelversionen.artikelid='$row_rst2[artikelid]' AND artikelversionen.lokz=0  ORDER BY artikelversionen.version";
$rstversion = mysql_query($query_rstversion, $qsdatenbank) or die(mysql_error());
$row_rstversion = mysql_fetch_assoc($rstversion);
$totalRows_rstversion = mysql_num_rows($rstversion);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la11.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="152">Aktionen W&auml;hlen:</td>
      <td width="339"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"></td>
      <td width="123"> <div align="right">
          <input name="pruefen" type="submit" id="pruefen" value="Pr&uuml;fen">
          <img src="picture/bildergalerie.gif" width="9" height="10"> 
          <?php if (isset($HTTP_POST_VARS['menge']) && $HTTP_POST_VARS['menge']>0 ){ ?><input name="save" type="submit" id="save" value="Speichern"><?php }?>
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Ger&auml;tetyp:</td>
      <td><strong><?php echo $row_rst2['Bezeichnung']; ?> 
        <input name="anzahlp" type="hidden" id="anzahlp" value="<?php echo $row_rst2['anzahlp']; ?>">
        </strong></td>
      <td><div align="right"><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst2['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst2['Nummer'] ?>&url_sn_ab=<?php echo $row_rst3['fsn']; ?>&url_sn_bis=<?php echo $row_rst3['fsn']; ?>" target="_blank"><img src="picture/rules.gif" alt="Typenschild drucken" width="13" height="18" border="0" align="absmiddle"> 
          Typenschild </a> </div></td>
    </tr>
    <tr> 
      <td>Anzahl der Ger&auml;te</td>
      <td><input name="menge" type="text" id="menge" value="<?php echo $HTTP_POST_VARS['menge'] ?>">
        St&uuml;ck </td>
      <td> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>"></td>
    </tr>
    <tr> 
      <td>Seriennummer von: </td>
      <td> <strong><?php echo $row_letztebekanntesn['fsn']+1; ?></strong> bis <strong><?php echo $row_letztebekanntesn['fsn']+$HTTP_POST_VARS['menge']; ?></strong> </td>
      <td><div align="right"><a href="pdfetikett3.php?url_bezeichnung=<?php echo $row_rst2['Bezeichnung'] ?>&url_artikelnummer=<?php echo $row_rst2['Nummer'] ?>&url_sn_ab=<?php echo $row_rst3['fsn'] ?>&url_sn_bis=<?php echo $row_rst3['fsn'] ?>" target="_blank"><img src="picture/anschreiben.gif" alt="SN-Aufkleber drucken" width="18" height="18" border="0" align="absmiddle"> 
          SN-Aufkleber</a> </div></td>
    </tr>
    <tr>
      <td>Version</td>
      <td><input name="version" type="text" id="version" value="<?php echo $row_rst2['version']; ?>">
        <select name="select">
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
        <img src="picture/help.gif" width="17" height="17"><br>
        <br>
        <input name="sn" type="hidden" id="sn" value="<?php echo $row_letztebekanntesn['fsn']+1 ?>">
        <input name="snbis" type="hidden" id="snbis" value="<?php echo $row_letztebekanntesn['fsn']+$HTTP_POST_VARS['menge']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <hr>
  Bauteildaten 
    <table width="730" border="0" cellpadding="0" cellspacing="0">
    <?php if ($row_rst2['anzahlp']>=1){ ?>
    <tr> 
      <td width="179">Fertigungsdatum</td>
      <td width="353"> <input name="sn1" type="text" id="sn1" value="<?php echo date("Y/m/d",time()); ?>">
        <font size="1">Eingabeformat: 2011-07-01</font></td>
      <td width="198" rowspan="4"><div align="right"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" alt="EAN/UPC Nummer: <?php echo $row_rst2['ean13'] ?>" width="180" height="140" border="0"></a></div></td>
    </tr>
    <?php }?>
    <?php if ($row_rst2['anzahlp']>=2){ ?>
    <tr> 
      <td>Seriennummer Regler</td>
      <td> <input name="sn2" type="text" id="sn2"></td>
    </tr>
    <?php }?>
    <?php if ($row_rst2['anzahlp']>=3){ ?>
    <tr> 
      <td>Seriennummer L&uuml;fter</td>
      <td> <input name="sn3" type="text" id="sn3"></td>
    </tr>
    <?php }?>
    <?php if ($row_rst2['anzahlp']>=4){ ?>
    <tr> 
      <td>sonstiges</td>
      <td><input name="sn4" type="text" id="sn4"></td>
    </tr>
    <?php }?>
  </table>
  <hr>
  Pr&uuml;fung
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="152">Pr&uuml;fung i.O.</td>
      <td width="435"><input name="frei" type="checkbox" id="frei" value="1"> 
        <img src="picture/st1.gif" width="15" height="15"></td>
      <td width="27">&nbsp;</td>
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
      <td><textarea name="notes" cols="50" rows="7" id="notes"></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Signatur</td>
      <td><img src="picture/s_process.png" width="16" height="16"><?php echo md5(time()); ?>&nbsp; 
        <input name="signatur" type="hidden" id="signatur" value="<?php echo md5(time()); ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pr&uuml;fger&auml;te:</td>
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
      <td colspan="3">
	  <hr></td>
    </tr>
    <tr> 
      <td colspan="3"> 
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
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['sollwert']; ?> </td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['ergebnis']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['istwert']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
              <div align="right"> 
                <input name="hurl_user22" type="hidden" id="hurl_user22" value="<?php echo $url_user; ?>">
                <input name="hurl_id_p" type="hidden" id="hurl_id_p" value="<?php echo $row_rst200['id_p']; ?>">
                <input name="hurl_id_ps" type="hidden" id="hurl_id_ps" value="<?php echo $row_rst200['id_ps']; ?>">
              </div></td>
          </tr>
          <?php
    $gruppe=$row_rst200['gruppe'];
   } while ($row_rst200 = mysql_fetch_assoc($rst200)); ?>
        </table>
        <?php } // Show if recordset not empty ?> <?php if ($totalRows_rst200 == 0) { // Show if recordset empty ?>
        <p><font color="#FF0000">Keine Daten vorhanden. Pr&uuml;fplanung f&uuml;r 
          den Artikel nicht notwendig.</font></p>
        <?php } // Show if recordset empty ?> </td>
    </tr>
  </table>
  <p>&nbsp; </p>
  <input type="hidden" name="MM_insert" value="form2">
</form>
<p> 

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rstversion);

?>
</p>

  <?php include("footer.tpl.php"); ?>
