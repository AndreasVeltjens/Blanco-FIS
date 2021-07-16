<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/vsdatenbank.php'); ?>
<?php $la = "la632";
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

if ((isset($HTTP_POST_VARS["speichern"] ))) {
  $updateSQL = sprintf("UPDATE vorgange SET lokz=%s, datum=%s, headertxt=%s, firma=%s, strasse=%s, plz=%s, ort=%s, land=%s, fax=%s, tel=%s, anrede=%s, is_kd=%s, ansprechpartner=%s, telefon=%s, telefax=%s, email=%s WHERE id_vg=%s",
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['headertxt'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['firma'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['strasse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['plz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['land'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['faxnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['telefon'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anrede'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['selectkd'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['ansprechpartner'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['telefon'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['faxnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['email'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_vg'], "int"));

  mysql_select_db($database_vsdatenbank, $vsdatenbank);
  $Result1 = mysql_query($updateSQL, $vsdatenbank) or die(mysql_error());
  $oktxt="Adressdaten gespeichert.";
}

if ((isset($HTTP_POST_VARS["anlegen"])&& ($HTTP_POST_VARS["pos"]!="") )) {
  $insertSQL = sprintf("INSERT INTO vorgangsdaten (id_vg, pos, beschreibung, menge, nettoVK, bruttoVK, lieferschein, datum, kontierung, kurztxt, material) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_vg'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['pos'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['kurztext'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['menge'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['nettoVK'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['bruttoVK'], "double"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lieferschein']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['kontierung'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['kurztext'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['material'], "text"));

  mysql_select_db($database_vsdatenbank, $vsdatenbank);
  $Result1 = mysql_query($insertSQL, $vsdatenbank) or die(mysql_error());
  $oktxt="Neuer Datensatz angelegt. ".$HTTP_POST_VARS["pos"]." - ".$HTTP_POST_VARS["material"]." - ".$HTTP_POST_VARS["kurztext"];
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "la621.php?url_user=".$row_rst1['id']."&url_idk=".$url_is_kd;
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "";}

if ((isset($HTTP_POST_VARS["bearbeiten"])) ) {
$GoTo = "la6322.php?url_user=".$row_rst1['id']."&url_idk=".$HTTP_POST_VARS['hurl_is_kd']."&url_id_vg=".$HTTP_POST_VARS['hurl_id_vg']."&url_id_vgd=".$HTTP_POST_VARS['hurl_id_vgd'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "";}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT * FROM kundendaten ";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);


$HTTP_POST_VARS["suchtext"]=$row_rst6['suchbegriff'];
if (isset($HTTP_POST_VARS["suchtext"])){
if ($HTTP_POST_VARS["suchtext"]==""){$HTTP_POST_VARS["suchtext"]="unbekannt";}
$suchtext=" WHERE fehlermeldungen.fmid like '".$HTTP_POST_VARS["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$HTTP_POST_VARS["suchtext"]."%' OR fehlermeldungen.fmsn like '".$HTTP_POST_VARS["suchtext"]."' ";}
else{$suchtext="";}

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

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst4 = "SELECT * FROM vorgangsart ORDER BY vorgangsart.anzeigetxt";
$rst4 = mysql_query($query_rst4, $vsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst3 = "SELECT * FROM vorgange WHERE vorgange.id_vg='$url_id_vg' ";
$rst3 = mysql_query($query_rst3, $vsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst5 = "SELECT * FROM vorgangsdaten WHERE vorgangsdaten.id_vg='$url_id_vg' ORDER BY vorgangsdaten.kontierung, vorgangsdaten.pos";
$rst5 = mysql_query($query_rst5, $vsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst7 = "SELECT * FROM kontierung ORDER BY kontierung.datevtxt";
$rst7 = mysql_query($query_rst7, $vsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM kundendaten WHERE kundendaten.idk='$url_is_kd'";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

if ((isset($HTTP_POST_VARS["vorlage"])) ) {
$row_rst3['ansprechpartner']=utf8_decode($row_rst8['ansprechpartner']);
$row_rst3['telefon']=utf8_decode($row_rst8['telefon']);
$row_rst3['faxnummer']=utf8_decode($row_rst8['telfax']);
$row_rst3['email']=utf8_decode($row_rst8['email']);
$row_rst3['firma']=($row_rst8['firma']);
$row_rst3['anrede']=utf8_decode($row_rst8['anrede']);
$row_rst3['strasse']=utf8_decode($row_rst8['strasse']);
$row_rst3['plz']=utf8_decode($row_rst8['plz']);
$row_rst3['ort']=($row_rst8['ort']);
$row_rst3['land']=utf8_decode($row_rst8['land']);
$oktxt = "Hinweis: gewählte Vorlagedaten aus dem Kundendatenstamm übernommen.";
}


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
      <td colspan="3">
	  <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?>
        <?php if ($oktxt !="") { // Show if recordset not empty ?>
        <strong><font color="#00CC33"><?php echo htmlentities($oktxt);?> </font></strong>
        <?php } // Show if recordset not empty ?>
		 <?php if ($errtxt !="") { // Show if recordset not empty ?>
        <strong><font color="#FF0000"><?php echo htmlentities($errtxt); ?> </font></strong>
        <?php } // Show if recordset not empty ?> 
		</td>
    </tr>
    <tr> 
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="369"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="123"><div align="right"></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2"><strong><em><img src="picture/s_tbl.png" width="16" height="16"> 
       
          <?php
do {  
?>
          <?php if (!(strcmp($row_rst4['id_vga'], $row_rst3['vorgangsart']))) {echo htmlentities($row_rst4['anzeigetxt']);}?></option>
        <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?></select>
        - <?php echo $row_rst3['lfd_nr']; ?>- allgemeine Daten</em></strong> </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>Vorlage verwenden:</td>
      <td colspan="2"><select name="selectkd" id="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst6['idk']?>"<?php if (!(strcmp($row_rst6['idk'], $row_rst3['is_kd']))) {echo "SELECTED";} ?>><?php echo substr(utf8_decode($row_rst6['firma']),0,60)?></option>
          <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </select>
        <input name="vorlage" type="submit" id="vorlage" value="Vorlage verwenden"></td>
    </tr>
    <tr> 
      <td width="148">Firmenname<br> <br> <br> <br> </td>
      <td width="352"> <textarea name="firma" cols="50" id="firma"><?php echo utf8_decode($row_rst3['firma']); ?></textarea></td>
      <td width="230"><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>Ansprechpartner</td>
      <td> <input name="ansprechpartner" type="text" id="ansprechpartner" value="<?php echo utf8_decode($row_rst3['ansprechpartner']); ?>" size="50"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Stra&szlig;e</td>
      <td><input name="strasse" type="text" id="strasse" value="<?php echo utf8_decode($row_rst3['strasse']); ?>" size="50"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>PLZ</td>
      <td><input name="plz" type="text" id="plz" value="<?php echo utf8_decode($row_rst3['plz']); ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Ort</td>
      <td><input name="ort" type="text" id="ort" value="<?php echo utf8_decode($row_rst3['ort']); ?>" size="50"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Land</td>
      <td><input name="land" type="text" id="land" value="<?php echo utf8_decode($row_rst3['land']); ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Faxnummer</td>
      <td><input name="faxnummer" type="text" id="faxnummer" value="<?php echo utf8_decode($row_rst3['telefax']); ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Telefon</td>
      <td><input name="telefon" type="text" id="telefon" value="<?php echo utf8_decode($row_rst3['telefon']); ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum:</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo utf8_decode($row_rst3['datum']); ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Kunden-ID </td>
      <td> <input name="hurl_id_vg" type="hidden" id="hurl_id_vg" value="<?php echo $row_rst3['id_vg']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <?php echo $row_rst3['is_kd']; ?> - <?php echo $row_rst3['id_vg']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schkennzeichen </td>
      <td> 
<input <?php if (!(strcmp($row_rst3['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
      <td><div align="right"><a href="pdfeti632.php?url_user_id=<?php echo $row_rst1['id']; ?>&url_id_vg=<?php echo $row_rst3['id_vg']; ?>" target="_blank">drucken</a> 
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>Email-Adresse</td>
      <td> <input name="email" type="text" id="email" value="<?php echo utf8_decode($row_rst3['email']); ?>"></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>&Uuml;berschrift:</td>
      <td colspan="2"> <textarea name="headertxt" cols="90" id="headertxt"><?php echo utf8_decode($row_rst3['headertxt']); ?></textarea></td>
    </tr>
    <tr> 
      <td>Anrede</td>
      <td> <input name="anrede" type="text" id="anrede" value="<?php echo utf8_decode($row_rst3['anrede']); ?>" size="50"> 
      </td>
      <td><div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="speichern" type="submit" id="speichern" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
<p><strong><em><img src="picture/s_db.png" width="16" height="16"> Positionsdaten</em></strong></p>
<?php if ($totalRows_rst5 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Belege derzeit noch nicht verf&uuml;gbar.</font></p>
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="55"><strong>Pos.</strong></td>
    <td width="98"><strong>Material</strong></td>
    <td width="190"><strong>Beschreibung</strong></td>
    <td width="49">Menge</td>
    <td width="58">NettoVK</td>
    <td width="46">Gesamt</td>
    <td width="54">Datum</td>
    <td width="62">&nbsp;</td>
    <td width="108">&nbsp;</td>
  </tr>
  <?php  $buchstabealt ="";?>
  <?php do {  ?>
  <form name="form3" method="post" action="">
    <?php
   if ($buchstabealt!= $row_rst5['kontierung'])  {  ?>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="9" nowrap="nowrap"><strong><em><img src="picture/s_tbl.png" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst7['id_k'], $row_rst5['kontierung']))) {echo $row_rst7['datevtxt'];}?>
        <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
        </em></strong></td>
    </tr>
    <?php } ?>
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;<?php echo $row_rst5['pos']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        <?php echo substr((utf8_decode($row_rst5['material'])),0,30); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo substr((utf8_decode($row_rst5['beschreibung'])),0,30); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo (utf8_decode($row_rst5['menge'])); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo (utf8_decode($row_rst5['nettoVK'])); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo substr(utf8_decode($row_rst5['bruttoVK']),0,150); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;<?php echo $row_rst5['datum']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp; 
        <input name="hurl_id_vgd" type="hidden" id="hurl_id_vgd" value="<?php echo $row_rst5['id_vgd']; ?>">
        <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_id_vg" type="hidden" id="hurl_id_vg" value="<?php echo $row_rst5['id_vg']; ?>">
<input name="hurl_is_kd" type="hidden" id="hurl_is_kd" value="<?php echo $row_rst3['is_kd']; ?>">

        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="bearbeiten" type="submit" id="bearbeiten" value="Bearbeiten">
          </font></div></td>
    </tr>
  </form>
  <?php 
  $buchstabealt= ($row_rst5['kontierung']);
  } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
</table>
<?php } // Show if recordset not empty ?>
<br>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <img src="picture/b_newdb.png" width="16" height="16"><em><strong> neue Position 
  anlegen</strong></em><br>
  <table width="730" border="0" cellpadding="0" cellspacing="1">
    <tr> 
      <td width="69"><strong>Pos.</strong></td>
      <td width="69"><strong>Material</strong></td>
      <td width="183"><strong>Beschreibung</strong></td>
      <td width="47">Menge</td>
      <td width="60">NettoVK</td>
      <td width="75">Gesamt</td>
      <td width="66">Datum</td>
      <td width="53">&nbsp;</td>
      <td width="98">&nbsp;</td>
    </tr>
    
    <tr bgcolor="#CCCCCC"> 
      <td colspan="9" nowrap="nowrap"><strong><em><img src="picture/s_tbl.png" width="16" height="16"> 
        <select name="kontierung" id="kontierung">
          <?php
do {  
?>
          <option value="<?php echo $row_rst7['id_k']?>"><?php echo $row_rst7['datevtxt']?></option>
          <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
}
?>
        </select>
        Lieferung 
        <input name="lieferschein" type="checkbox" id="lieferschein" value="1">
        </em></strong></td>
    </tr>
   <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        <input name="pos" type="text" id="pos" size="10">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        <input name="material" type="text" id="material" size="10">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="kurztext" type="text" id="kurztext"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="menge" type="text" id="menge" size="6"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="nettoVK" type="text" id="nettoVK2" size="10"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="bruttoVK" type="text" id="bruttoVK" size="10"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp; 
        <input name="datum" type="text" id="datum" size="10">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp; 
        <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_id_vg" type="hidden" id="hurl_id_vg" value="<?php echo $url_id_vg; ?>">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="anlegen" type="submit" id="anlegen" value="anlegen">
          </font></div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
</form>
<p>&nbsp;</p>
<?php
mysql_free_result($rst3);

mysql_free_result($rst5);

mysql_free_result($rst7);

mysql_free_result($rst8);

mysql_free_result($rst6);

mysql_free_result($rst4);
?>
<?php include("footer.tpl.php"); ?>
