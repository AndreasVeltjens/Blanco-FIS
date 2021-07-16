<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/vsdatenbank.php'); ?>
<?php $la = "la621";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
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

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "la6.php?url_user=".$row_rst1['id'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$GoTo = "la632.php?url_user=".$row_rst1['id']."&url_id_vg=".$HTTP_POST_VARS['hurl_id_vg']."&url_is_kd=".$HTTP_POST_VARS['hurl_is_kd'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE kundendaten SET firma=%s, strasse=%s, plz=%s, ort=%s, faxnummer=%s, telefon=%s, anrede=%s, datum=%s, erstellt=%s, land=%s, lokz=%s, ansprechpartner=%s, email=%s, suchbegriff=%s WHERE idk=%s",
                       GetSQLValueString($HTTP_POST_VARS['firma'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['strasse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['plz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['faxnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['telefon'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anrede'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['land'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['ansprechpartner'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['email'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['suchbegriff'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_idk'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la6.php?url_user=" . $row_rst1['id'] . "";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT * FROM kundendaten WHERE kundendaten.idk='$url_idk'";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);


$HTTP_POST_VARS["suchtext"]=$row_rst6['suchbegriff'];
if (isset($HTTP_POST_VARS["suchtext"])){
if ($HTTP_POST_VARS["suchtext"]==""){$HTTP_POST_VARS["suchtext"]="unbekannt";}
$suchtext=" WHERE fehlermeldungen.fmid like '".$HTTP_POST_VARS["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$HTTP_POST_VARS["suchtext"]."%' OR fehlermeldungen.fmsn like '".$HTTP_POST_VARS["suchtext"]."' ";}
else{$suchtext=" WHERE fehlermeldungen.fmid like '111111111'";}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT `user`.id, `user`.name FROM `user`";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmsn,  fehlermeldungen.fmartikelid, fehlermeldungen.fmfd, fehlermeldungen.fm1, fehlermeldungen.fm2, fehlermeldungen.fm3, fehlermeldungen.fm4, fehlermeldungen.fm5, fehlermeldungen.fm0, fehlermeldungen.fm6, fehlermeldungen.fm6, fehlermeldungen.fm1notes, fehlermeldungen.fm11notes, fehlermeldungen.fm12notes, fehlermeldungen.sappcna, fehlermeldungen.fm1datum, fehlermeldungen.vip, fehlermeldungen.vkz FROM fehlermeldungen $suchtext ORDER BY fehlermeldungen.fmid DESC";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst3 = "SELECT vorgange.id_vg, vorgange.lfd_nr, vorgange.lokz, vorgange.datum, vorgange.vorgangsart, vorgange.headertxt, vorgange.firma, vorgange.strasse, vorgange.plz, vorgange.ort, vorgange.status, vorgange.bezahlt, vorgange.is_kd, vorgangsart.id_vga, vorgangsart.anzeigetxt, vorgangsart.anzeigebild FROM vorgange, vorgangsart WHERE vorgange.is_kd='$url_idk' AND vorgangsart.id_vga=vorgange.vorgangsart ORDER BY vorgange.vorgangsart, vorgange.datum";
$rst3 = mysql_query($query_rst3, $vsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
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
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="369"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="123"> <div align="right"><img src="picture/addressbook.gif" width="52" height="36"> 
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2"><strong><em><img src="picture/s_tbl.png" width="16" height="16"> 
        allgemeine Kundendaten</em></strong> </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>Firmenname<br> <br> <br> <br> </td>
      <td> <textarea name="firma" cols="50" id="firma"><?php echo utf8_decode($row_rst6['firma']); ?></textarea></td>
      <td><div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input type="submit" name="Submit" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>Ansprechpartner</td>
      <td> <input name="ansprechpartner" type="text" id="ansprechpartner" value="<?php echo utf8_decode($row_rst6['ansprechpartner']); ?>" size="50"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="180">Anrede</td>
      <td width="324"> <input name="anrede" type="text" id="anrede" value="<?php echo utf8_decode($row_rst6['anrede']); ?>" size="50">
      </td>
      <td width="110">&nbsp;</td>
    </tr>
    <tr> 
      <td>Stra&szlig;e</td>
      <td><input name="strasse" type="text" id="strasse" value="<?php echo utf8_decode($row_rst6['strasse']); ?>" size="50">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>PLZ</td>
      <td><input name="plz" type="text" id="plz" value="<?php echo utf8_decode($row_rst6['plz']); ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Ort</td>
      <td><input name="ort" type="text" id="ort" value="<?php echo utf8_decode($row_rst6['ort']); ?>" size="50">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Land</td>
      <td><input name="land" type="text" id="land" value="<?php echo utf8_decode($row_rst6['land']); ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Faxnummer</td>
      <td><input name="faxnummer" type="text" id="faxnummer" value="<?php echo utf8_decode($row_rst6['faxnummer']); ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Telefon</td>
      <td><input name="telefon" type="text" id="telefon" value="<?php echo utf8_decode($row_rst6['telefon']); ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum:</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo utf8_decode($row_rst6['datum']); ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Kunden-ID und Suchbegriff</td>
      <td> <input name="hurl_idk" type="hidden" id="hurl_idk" value="<?php echo utf8_decode($row_rst6['idk']); ?>">
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <?php echo $row_rst6['idk']; ?> </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schkennzeichen </td>
      <td> <input <?php if (!(strcmp($row_rst6['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>Suchbegriff</td>
      <td><input name="suchbegriff" type="text" id="suchbegriff" value="<?php echo utf8_decode($row_rst6['suchbegriff']); ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Email-Adresse</td>
      <td>
<input name="email" type="text" id="email" value="<?php echo utf8_decode($row_rst6['email']); ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
<p><strong><em><img src="picture/s_db.png" width="16" height="16"> Liste der Auftr&auml;ge:</em></strong></p>
<?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Belege derzeit noch nicht verf&uuml;gbar.</font></p>
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="106"><strong>Belegnummer</strong></td>
    <td width="104"><strong>Firmenname</strong></td>
    <td width="91"><strong>Stra&szlig;e</strong></td>
    <td width="53">PLZ</td>
    <td width="51">Ort</td>
    <td width="131">&Uuml;berschrift</td>
    <td width="51">Datum</td>
    <td width="24">&nbsp;</td>
    <td width="109">&nbsp;</td>
  </tr>
  <?php  $buchstabealt ="";?>
  <?php do {  ?>
  <form name="form1" method="post" action="">
    <?php
   if ($buchstabealt!= substr((utf8_decode($row_rst3['anzeigetxt'])),0,1))  {  ?>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="9" nowrap="nowrap"><img src="picture/<?php echo $row_rst3['anzeigebild']; ?>"> 
        <?php echo  substr( (utf8_decode($row_rst3['anzeigetxt'])),0,1) ?> <?php echo utf8_decode($row_rst3['anzeigetxt']); ?></td>
    </tr>
    <?php } ?>
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;<?php echo $row_rst3['id_vg']; ?>- 
        <?php echo $row_rst3['lfd_nr']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        <?php echo substr((utf8_decode($row_rst3['firma'])),0,30); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo substr((utf8_decode($row_rst3['strasse'])),0,30); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo (utf8_decode($row_rst3['plz'])); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo (utf8_decode($row_rst3['ort'])); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo substr(utf8_decode($row_rst3['headertxt']),0,150); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;<?php echo $row_rst3['datum']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp; 
        <input name="hurl_id_vg" type="hidden" id="hurl_id_vg" value="<?php echo $row_rst3['id_vg']; ?>">
        <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_is_kd" type="hidden" id="hurl_is_kd" value="<?php echo $row_rst3['is_kd']; ?>">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
          </font></div></td>
    </tr>
  </form>
  <?php 
  $buchstabealt= substr($row_rst3['anzeigetxt'],0,1);
  } while ($row_rst3 = mysql_fetch_assoc($rst3)); ?>
</table>
<?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<p><strong><em><img src="picture/s_db.png" width="16" height="16"> zus&auml;tzliche 
  Anschriften und Lieferaddressen:</em></strong></p>
<p><font color="#FF0000">Modul derzeit noch nicht verf&uuml;gbar</font></p>
<p><strong><em><img src="picture/s_tbl.png" width="16" height="16"> Liste der 
  Ger&auml;te:</em></strong></p>
<p><font color="#FF0000">Modul derzeit noch nicht verf&uuml;gbar</font> (Zuordnung 
  von Fertigungsmeldung und Kundennummer)</p>
<p><strong><em><img src="picture/s_tbl.png" width="16" height="16"> Liste der 
  Retourenabwicklungen:</em></strong></p>
<?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">keine Retourenabwicklung gefunden.</font></p>
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
insgesamt <?php echo $totalRows_rst4 ?>gefunden. 
<?php include("la4.zeile.php");?>

<?php } // Show if recordset not empty ?>
<p>&nbsp; </p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst6);

mysql_free_result($rstuser);

mysql_free_result($rsttyp);

mysql_free_result($rst4);

mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
