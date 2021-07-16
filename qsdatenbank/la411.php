<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la411";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
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

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ($HTTP_POST_VARS['select2']>0){
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$suche =$HTTP_POST_VARS['select2'];
$query_rstkundenR = "SELECT * FROM kundendaten WHERE kundendaten.idk=$suche";
$rstkundenR = mysql_query($query_rstkundenR, $qsdatenbank) or die(mysql_error());
$row_rstkundenR = mysql_fetch_assoc($rstkundenR);
$totalRows_rstkundenR = mysql_num_rows($rstkundenR);

$HTTP_POST_VARS['rechnung'] = utf8_decode($row_rstkundenR['firma']);
$HTTP_POST_VARS['rstrasse'] = utf8_decode($row_rstkundenR['strasse']);
$HTTP_POST_VARS['rplz'] = utf8_decode($row_rstkundenR['plz']);
$HTTP_POST_VARS['rort'] = utf8_decode($row_rstkundenR['ort']);
$HTTP_POST_VARS['rland'] = utf8_decode($row_rstkundenR['land']);
$HTTP_POST_VARS['rfax'] = utf8_decode($row_rstkundenR['faxnummer']);

if ($HTTP_POST_VARS['checkbox']==1){
	$HTTP_POST_VARS['lieferung'] =utf8_encode($row_rstkundenR['firma']);
	$HTTP_POST_VARS['lstrasse'] = utf8_encode($row_rstkundenR['strasse']);
	$HTTP_POST_VARS['lplz'] = utf8_encode($row_rstkundenR['plz']);
	$HTTP_POST_VARS['lort'] = utf8_encode($row_rstkundenR['ort']);
	$HTTP_POST_VARS['lland'] = utf8_encode($row_rstkundenR['land']);
	}
}

if ($HTTP_POST_VARS['select3']>0 ){
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$suche =$HTTP_POST_VARS['select3'];
$query_rstkundenL = "SELECT * FROM kundendaten WHERE kundendaten.idk=$suche";
$rstkundenL = mysql_query($query_rstkundenL, $qsdatenbank) or die(mysql_error());
$row_rstkundenL = mysql_fetch_assoc($rstkundenL);
$totalRows_rstkundenL = mysql_num_rows($rstkundenL);

$HTTP_POST_VARS['lieferung'] = utf8_encode($row_rstkundenL['firma']);
$HTTP_POST_VARS['lstrasse'] = utf8_encode($row_rstkundenL['strasse']);
$HTTP_POST_VARS['lplz'] = utf8_encode($row_rstkundenL['plz']);
$HTTP_POST_VARS['lort'] = utf8_encode($row_rstkundenL['ort']);
$HTTP_POST_VARS['lland'] = utf8_encode($row_rstkundenL['land']);

}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);
if ((isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save1"])) or  (isset($HTTP_POST_VARS["saveaktuell"])) ) {
  
  
  if ($HTTP_POST_VARS["checkbox"]==1){
  $HTTP_POST_VARS['lieferung']=$HTTP_POST_VARS['rechnung'];
  $HTTP_POST_VARS['lstrasse']=$HTTP_POST_VARS['rstrasse'];
  $HTTP_POST_VARS['lplz']=$HTTP_POST_VARS['rplz'];
  $HTTP_POST_VARS['lort']=$HTTP_POST_VARS['rort'];
  $HTTP_POST_VARS['lland']=$HTTP_POST_VARS['rland'];
 }
  
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm1datum=%s, fm1notes=%s, fm11notes=%s, fm12notes=%s, rechnung=%s, rstrasse=%s, rplz=%s, rort=%s, rland=%s, lieferung=%s, lstrasse=%s, lplz=%s, lort=%s, lland=%s, faxnummer=%s WHERE fmid=%s",
		   				"now()",
		   				GetSQLValueString($HTTP_POST_VARS['debitor'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['debitor2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['debitor3'], "text"),
					   				
                       GetSQLValueString($HTTP_POST_VARS['rechnung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rstrasse'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rplz'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rort'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['rland'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['lieferung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lstrasse'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lplz'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lort'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lland'], "text"),
   					   GetSQLValueString($HTTP_POST_VARS['rfax'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

if (!isset($HTTP_POST_VARS["saveaktuell"])){
if (isset($goback)){$updateGoTo = "la42s.php";}else{
if (isset($HTTP_POST_VARS["save"])){$updateGoTo = "la41.php";}
if (isset($HTTP_POST_VARS["save1"])){$updateGoTo = "la41.php";}
if (isset($HTTP_POST_VARS["save3"])){$updateGoTo = "la43.php";}
 }
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
} /* end saveaktuell */



if ((isset($HTTP_POST_VARS["cancel"])) ) {
if (isset($goback)){$GoTo = "la42s.php?url_user=".$row_rst1['id'];}
else{ 
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
$query_rst7 = "SELECT entscheidung.entid, entscheidung.entname, entscheidung.lokz FROM entscheidung WHERE entscheidung.lokz = 0 ORDER BY entscheidung.entname";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstkunden = "SELECT * FROM kundendaten WHERE kundendaten.lokz=0 ORDER BY kundendaten.firma";
$rstkunden = mysql_query($query_rstkunden, $qsdatenbank) or die(mysql_error());
$row_rstkunden = mysql_fetch_assoc($rstkunden);
$totalRows_rstkunden = mysql_num_rows($rstkunden);

$suchtext=utf8_encode($row_rst4['fm1notes']);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstR = "SELECT kundendaten.idk, kundendaten.firma, kundendaten.strasse, kundendaten.plz, kundendaten.ort FROM kundendaten WHERE kundendaten.firma like '%$suchtext%' ORDER BY kundendaten.firma";
$rstR = mysql_query($query_rstR, $qsdatenbank) or die(mysql_error());
$row_rstR = mysql_fetch_assoc($rstR);
$totalRows_rstR = mysql_num_rows($rstR);





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
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="222">Aktionen W&auml;hlen:</td>
      <td width="159">&nbsp; </td>
      <td width="349"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          <input name="save" type="submit" id="save" value="weiter">
        </div></td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Plan</font></div></td>
            <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Fehler 
                aufnahme</font></div></td>
            <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Kosten</font></div></td>
            <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Reparatur 
                freigabe</font></div></td>
            <td><div align="center"><font size="1">SAP</font></div></td>
            <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Endpr&uuml;fung</font></div></td>
            <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Kommision</font></div></td>
            <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">erledigt</font></div></td>
          </tr>
          <tr> 
            <td width="80"><div align="center"><img src="picture/b_newdb.png" width="16" height="16"></div></td>
            <td width="80"><p align="center"><img src="picture/b_tblops.png" width="16" height="16"></p></td>
            <td width="80"><div align="center"><img src="picture/money.gif" width="15" height="15"></div></td>
            <td width="80"><div align="center"><img src="picture/b_insrow.png" width="16" height="16"></div></td>
            <td width="80"><div align="center"><img src="picture/sap.png" width="16" height="16"></div></td>
            <td width="80"><div align="center"><img src="picture/s_host.png" width="16" height="16"></div></td>
            <td width="80"><div align="center"><img src="picture/s_db.png" width="16" height="16"></div></td>
            <td width="80"><div align="center"><img src="picture/fertig.gif" width="15" height="15"></div></td>
          </tr>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst4['fm0'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" width="15" height="15"> 
                </font></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
                <img src="picture/<?php if (!(strcmp($row_rst4['fm1'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" width="15" height="15"></font></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
                <img src="picture/<?php if (!(strcmp($row_rst4['fm2'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" width="15" height="15"> 
                </font></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
                <img src="picture/<?php if (!(strcmp($row_rst4['fm3'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" width="15" height="15"> 
                </font></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if ($row_rst4['sappcna']>0) {echo "st1.gif";}else{echo "st3.gif";}?>" width="15" height="15"></font></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
                <img src="picture/<?php if (!(strcmp($row_rst4['fm4'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" width="15" height="15"> 
                </font></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst4['fm5'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" width="15" height="15"> 
                </font></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
                <img src="picture/<?php if (!(strcmp($row_rst4['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" width="15" height="15"></font></div></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="21"><strong>Fehlermeldung:</strong></td>
      <td colspan="2"><strong><font size="4"><?php echo $row_rst4['fmid']; ?>- 
        <?php echo urldecode($row_rst4['fm1notes']); ?>- <?php echo urldecode($row_rst4['fm11notes']); ?>- 
        <?php echo urldecode($row_rst4['fm12notes']); ?></font></strong></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="3"><div align="right"> <img src="picture/s_host.png" width="16" height="16"> 
          Kontaktdaten 
          <input name="save1" type="submit" id="save12" value="Fehleraufnahme">
          <input name="save2" type="submit" id="save2" value="Kosten">
          <input name="save3" type="submit" id="save3" value="Reparaturfreigabe">
          <input name="save4" type="submit" id="save4" value="VDE-Pr&uuml;fung">
          <input name="save5" type="submit" id="save5" value="Kommisionierung">
          <input name="save6" type="submit" id="save6" value="WA">
        </div></td>
    </tr>
  </table>
  <table width="730" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td width="190">Serienummer</td>
      <td ><input name="name" type="text" id="name2" value="<?php echo $row_rst4['fmsn']; ?>" size="10" maxlength="255">
        Typ 
        <select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst2['artikelid']?>"<?php if (!(strcmp($row_rst2['artikelid'], $row_rst4['fmartikelid']))) {echo "SELECTED";} ?>><?php echo $row_rst2['Bezeichnung']?></option>
          <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
        </select>
        Fertigungsdatum</td>
      <td width="114"><div align="right"> 
          <input name="fmfd" type="text" id="fmfd" value="<?php echo $row_rst4['fmfd']; ?>">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="24">Debitor </td>
      <td> <input name="debitor" type="text" id="debitor2" value="<?php echo $row_rst4['fm1notes']; ?>" size="50" maxlength="255"></td>
      <td><input name="fm3user" type="hidden" id="fm3user2" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Endkunde</td>
      <td> <input name="debitor2" type="text" id="debitor2" value="<?php echo $row_rst4['fm11notes']; ?>" size="50" maxlength="255"></td>
      <td>
<input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Kommision</td>
      <td> <input name="debitor3" type="text" id="debitor3" value="<?php echo $row_rst4['fm12notes']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Eingangsdatum</td>
      <td> <img src="picture/b_calendar.png" width="16" height="16"> automatisch 
        <?php echo $row_rst4['fm1datum']; ?> </td>
      <td><div align="right">
          <input name="saveaktuell" type="submit" id="saveaktuell" value="Suchen">
        </div></td>
    </tr>
  </table>
  <p><em>M&ouml;gliche Debitordaten</em></p>
  <?php if ($totalRows_rstR > 0) { // Show if recordset not empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr bgcolor="#FFFFFF"> 
      <td width="215">Name</td>
      <td width="271">Stra&szlig;e</td>
      <td width="244">PLZ - Ort</td>
    </tr>
    <?php do { ?>
    <tr bgcolor="#FFFFFF"> 
      <td><?php echo utf8_decode($row_rstR['firma']); ?></td>
      <td><?php echo utf8_decode($row_rstR['strasse']); ?></td>
      <td><?php echo utf8_decode($row_rstR['plz']); ?> <?php echo utf8_decode($row_rstR['ort']); ?>
        <input name="hurl_kunden" type="hidden" id="hurl_kunden" value="<?php echo $row_rstR['idk']; ?>"></td>
    </tr>
    <?php } while ($row_rstR = mysql_fetch_assoc($rstR)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rstR == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine Kundendaten gefunden.</font></p>
  <?php } // Show if recordset empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="200">&nbsp;</td>
      <td width="340">&nbsp;</td>
      <td width="190"><div align="right"> 
          <input name="saveaktuell" type="submit" id="saveaktuell" value="Speichern">
        </div></td>
    </tr>
  </table>
  <br>
  <table width="730" height="205" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="190">Rechnung</td>
      <td > <select name="select2">
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
      <td><textarea name="rechnung" cols="35"><?php echo ($row_rst4['rechnung']); ?></textarea></td>
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
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="190">Lieferung</td>
      <td ><select name="select3">
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
      <td><textarea name="lieferung" cols="35"><?php echo ($row_rst4['lieferung']); ?></textarea></td>
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
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p><br>
  </p>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="200">angelegt von </td>
      <td width="340"><?php echo $row_rst4['fmuser']; ?></td>
      <td width="190">&nbsp;</td>
    </tr>
    <tr> 
      <td>zuletzt gespeichert um</td>
      <td><?php echo $row_rst4['fmdatum']; ?></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>
    <input type="hidden" name="MM_update" value="form2">
  </p>
</form>
<p>
  <input type="hidden" name="MM_insert" value="form1">
</p>
<p> 
  <?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
  <font color="#FF0000">noch keine Fehler gespeichert.</font> 
  <?php } // Show if recordset empty ?>
</p>
<?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <?php do { ?>
  <tr> 
    <td width="8">&nbsp; </td>
    <td width="193">&nbsp;</td>
    <td width="529"> <?php echo $row_rst6['fkurz']; ?>- <?php echo $row_rst6['fname']; ?> 
      <input name="hurl_fid" type="hidden" id="hurl_fid2" value="<?php echo $row_rst6['fid']; ?>"> 
      <input name="hurl_user2" type="hidden" id="hurl_user4" value="<?php echo $row_rst1['id']; ?>"> 
      <input name="hurl_fmid2" type="hidden" id="hurl_fmid2" value="<?php echo $row_rst4['fmid']; ?>"> 
      <input name="hurl_lffid" type="hidden" id="hurl_lffid2" value="<?php echo $row_rst6['lffid']; ?>"></td>
  </tr>
  <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
</table>
<?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
  <p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rst7);

mysql_free_result($rstkunden);

mysql_free_result($rstR);

mysql_free_result($rstkundenR);
?>
</p>

  <?php include("footer.tpl.php"); ?>
