<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la402";
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

if (isset($HTTP_POST_VARS['vorauswahl'])){ $HTTP_POST_VARS['select2']=$HTTP_POST_VARS['vorauswahl_id'];}
if (isset($HTTP_POST_VARS['vorauswahl'])){ $HTTP_POST_VARS['select3']=$HTTP_POST_VARS['vorauswahl_id'];}

if ($HTTP_POST_VARS['select2']>0){
				mysql_select_db($database_qsdatenbank, $qsdatenbank);
				$suche =$HTTP_POST_VARS['select2'];
				
				$query_rstkundenR = "SELECT * FROM kundendaten WHERE kundendaten.idk=$suche";
				$rstkundenR = mysql_query($query_rstkundenR, $qsdatenbank) or die(mysql_error());
				$row_rstkundenR = mysql_fetch_assoc($rstkundenR);
				$totalRows_rstkundenR = mysql_num_rows($rstkundenR);
				
				$HTTP_POST_VARS['debitorennummer'] = utf8_decode($row_rstkundenR['kundenummer']);
				
				
				$HTTP_POST_VARS['rechnung'] = utf8_decode($row_rstkundenR['firma']);
				$HTTP_POST_VARS['rstrasse'] = utf8_decode($row_rstkundenR['strasse']);
				$HTTP_POST_VARS['rplz'] = utf8_decode($row_rstkundenR['plz']);
				$HTTP_POST_VARS['rort'] = utf8_decode($row_rstkundenR['ort']);
				$HTTP_POST_VARS['rland'] = utf8_decode($row_rstkundenR['land']);
				$HTTP_POST_VARS['rfax'] = utf8_decode($row_rstkundenR['faxnummer']);
				$HTTP_POST_VARS['fm2notes'] =$HTTP_POST_VARS['fm2notes']." - ".utf8_decode($row_rstkundenR['bemerkung']);
				
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
					
					$HTTP_POST_VARS['lieferung'] = utf8_decode($row_rstkundenL['firma']);
					$HTTP_POST_VARS['lstrasse'] = utf8_decode($row_rstkundenL['strasse']);
					$HTTP_POST_VARS['lplz'] = utf8_decode($row_rstkundenL['plz']);
					$HTTP_POST_VARS['lort'] = utf8_decode($row_rstkundenL['ort']);
					$HTTP_POST_VARS['lland'] = utf8_decode($row_rstkundenL['land']);
					$HTTP_POST_VARS['fm2notes'] =$HTTP_POST_VARS['fm2notes']." - ".utf8_decode($row_rstkundenL['bemerkung']);

}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save2"])) or (isset($HTTP_POST_VARS["save3"])) or (isset($HTTP_POST_VARS["save1"])) or  (isset($HTTP_POST_VARS["saveaktuell"])) ) {
  
  
  if ($HTTP_POST_VARS["checkbox"]==1){
  $HTTP_POST_VARS['lieferung']=$HTTP_POST_VARS['rechnung'];
  $HTTP_POST_VARS['lstrasse']=$HTTP_POST_VARS['rstrasse'];
  $HTTP_POST_VARS['lplz']=$HTTP_POST_VARS['rplz'];
  $HTTP_POST_VARS['lort']=$HTTP_POST_VARS['rort'];
  $HTTP_POST_VARS['lland']=$HTTP_POST_VARS['rland'];
 }
  
  $updateSQL = sprintf("UPDATE fehlermeldungen SET  fm1notes=%s, fm11notes=%s, fm12notes=%s, 
  debitorennummer=%s, rechnung=%s, rstrasse=%s, rplz=%s, rort=%s, rland=%s, lieferung=%s, lstrasse=%s, lplz=%s, lort=%s, lland=%s, 
  faxnummer=%s, fm2notes=%s, gewichtvorher=%s WHERE fmid=%s",
		   
		   				GetSQLValueString($HTTP_POST_VARS['debitor'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['debitor2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['debitor3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['debitorennummer'], "text"),
					   				
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
					   GetSQLValueString($HTTP_POST_VARS['fm2notes'], "text"),
					   GetSQLValueString(str_replace(",",".",$HTTP_POST_VARS['gewichtvorher']), "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

$oktxt="Formulardaten gespeichert";
if (!isset($HTTP_POST_VARS["saveaktuell"])){
if (isset($goback)){$updateGoTo = "la42s.php";}else{
if (isset($HTTP_POST_VARS["save2"])){$updateGoTo = "la4.php";}
if (isset($HTTP_POST_VARS["save"])){$updateGoTo = "la403.php";}
if (isset($HTTP_POST_VARS["save1"])){$updateGoTo = "la403.php";}
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
$query_rstR = "SELECT kundendaten.idk, kundendaten.firma, kundendaten.strasse, kundendaten.plz, kundendaten.ort FROM kundendaten WHERE kundendaten.firma like '%$suchtext%' or kundendaten.ort like '%$suchtext%' or kundendaten.plz like '$suchtext' ORDER BY kundendaten.firma";
$rstR = mysql_query($query_rstR, $qsdatenbank) or die(mysql_error());
$row_rstR = mysql_fetch_assoc($rstR);
$totalRows_rstR = mysql_num_rows($rstR);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmid ='$HTTP_POST_VARS[datenfm]'";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fm1notes, fehlermeldungen.fm11notes, fehlermeldungen.fm12notes FROM fehlermeldungen  ORDER BY fehlermeldungen.fmid desc limit 0,100";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

if (isset($HTTP_POST_VARS['vorauswahl']) && $HTTP_POST_VARS['debitor_name']<>""){ $row_rst4['fm1notes']=utf8_decode($HTTP_POST_VARS['debitor_name']);}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form1" method="POST">
  <table width="700" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?> 
        - Schritt 2 von 3</strong></td>
    </tr>
    <tr> 
      <td colspan="3"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="185">Aktionen W&auml;hlen:</td>
      <td width="196">&nbsp; </td>
      <td width="349"> <div align="right"><?php if ($comefrom<>"la40"){ ?><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          4. 
          <input name="save" type="submit" id="save" value="weiter zur Fehleraufnahme">
        </div> <?php }else{?>
		<input name="save2" type="submit" id="save2" value="nur Retoure speichern">
		<?php }?>
		</td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"> 
        <?php  include("la4.status.php");?>
      </td>
    </tr>
  </table>
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr bgcolor="#FFFFFF"> 
      <td height="24">Daten&uuml;bernahme von FM</td>
      <td colspan="2"> <select name="datenfm" id="datenfm">
          <option value="" <?php if (!(strcmp("", $HTTP_POST_VARS['datenfm']))) {echo "SELECTED";} ?>>keine 
          Auswahl treffen</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst9['fmid']?>"<?php if (!(strcmp($row_rst9['fmid'], $HTTP_POST_VARS['datenfm']))) {echo "SELECTED";
		  
		  /* Auswahl und überschreibend der Anschrift und Kommision */
		  if (isset($HTTP_POST_VARS['datenfmsubmit'])){
		  $row_rst4['fm1notes']=$row_rst8['fm1notes'];
		  $row_rst4['fm11notes']=$row_rst8['fm11notes'];
		  $row_rst4['fm12notes']=$row_rst8['fm12notes'];
		  $row_rst4['rechnung']=$row_rst8['rechnung'];
		  $row_rst4['rstrasse']=$row_rst8['rstrasse'];
		  $row_rst4['rplz']=$row_rst8['rplz'];
		  $row_rst4['rort']=$row_rst8['rort'];
		  $row_rst4['rland']=$row_rst8['rland'];
		  $row_rst4['faxnummer']=$row_rst8['faxnummer'];
		  $row_rst4['debitorennummer']=$row_rst8['debitorennummer'];
		  $row_rst4['lieferung']=$row_rst8['lieferung'];
		  $row_rst4['lstrasse']=$row_rst8['lstrasse'];
		  $row_rst4['lplz']=$row_rst8['lplz'];
		  $row_rst4['lort']=$row_rst8['lort'];
		  $row_rst4['lland']=$row_rst8['lland'];
		  
		  $row_rst4['fm2notes']=$row_rst8['fm2notes'];
		  
		  $oktxt="Daten von FM $row_rst8[fm1notes] &uuml;bernommen";
		  $HTTP_POST_VARS['select2']=0;
		  $HTTP_POST_VARS['select3']=0;
		  
		  }
		  
		  
		  } ?>><?php echo $row_rst9['fmid']?> <?php echo $row_rst9['fm1notes']?></option>
          <?php
} while ($row_rst9 = mysql_fetch_assoc($rst9));
  $rows = mysql_num_rows($rst9);
  if($rows > 0) {
      mysql_data_seek($rst9, 0);
	  $row_rst9 = mysql_fetch_assoc($rst9);
  }
?>
        </select>
        <img src="picture/help2.gif" alt="Anschrift und Versandlabel von FM &uuml;bernehmen. letzte FM-Nummer steht oben. Zuerst Auswahl treffen und dann speichern klicken." width="16" height="16"> 
        <input name="datenfmsubmit" type="submit" id="datenfmsubmit" value="Vorlage">
        <strong><font color="#009900"> <?php echo $oktxt ?></font></strong> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="165" height="24"><img src="picture/iconchance_16x16.gif" alt="H&auml;ndler od.Endkunde. Sie k&ouml;nnen zun&auml;chst nur die ersten Buchstaben eingeben und auf suchen dr&uuml;cken. Anschlie&szlig;end erfolgt eine Auswahl von Kundendaten." width="16" height="16"> 
        Debitor <br> </td>
      <td width="392"> <input name="debitor" type="text" id="debitor2" value="<?php echo $row_rst4['fm1notes']; ?>" size="50" maxlength="255"> 
        <img src="picture/help2.gif" alt="Nur; erste Buchstaben eingeben und dann auf Suchen dr&uuml;cken, um vorhandene Kundendaten zu &uuml;bernehmen" width="16" height="16"></td>
      <td width="173"><input name="fm3user" type="hidden" id="fm3user2" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><img src="picture/versandlabel.jpg" alt="Hier bitte GLS oder DHL Strichcode einscannen" width="20" height="23">Versandlabel<br>
        GLS-Nr./ DHL-Nr.<br> </td>
      <td> <input name="debitor2" type="text" id="debitor2" value="<?php echo $row_rst4['fm11notes']; ?>" size="50" maxlength="255"> 
        <img src="picture/help2.gif" alt="Mit Barcodescanner Strichcode vom Versandetikett einscannen oder per Hand eintragen" width="16" height="16"><br> 
      </td>
      <td><p><br>
        </p></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Kommision</td>
      <td> <input name="debitor3" type="text" id="debitor3" value="<?php echo $row_rst4['fm12notes']; ?>" size="50" maxlength="255"> 
        <img src="picture/help2.gif" alt="Ger&auml;t mit Grundk&ouml;rper/ oder mit Deckel geliefert worden ?" width="16" height="16"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Gewicht</td>
      <td><input name="gewichtvorher" type="text" id="gewichtvorher" value="<?php echo $row_rst4['gewichtvorher']; ?>" size="10" maxlength="255">
        kg </td>
      <td><div align="right">1. 
          <input name="saveaktuell" type="submit" id="saveaktuell" value="Suchen">
        </div></td>
    </tr>
  </table>
  <p><em>M&ouml;gliche Debitordaten oder neue Kunden hinzuf&uuml;gen <a href="la6.php?url_user=<?php echo $row_rst1['id']; ?>" target="_blank"><img src="picture/kundendatenanlegen.png" alt="Das Anlgen eines neuen Kunden erfolgt in einem neuen Fenster" width="120" height="16" border="0"></a></em></p>
  <?php if ($totalRows_rstR > 0) { // Show if recordset not empty ?>
  <img src="picture/myaccount.gif" width="52" height="36"> 
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr bgcolor="#FFFFFF"> 
      <td width="161">Name</td>
      <td width="199">Stra&szlig;e</td>
      <td width="257">PLZ - Ort</td>
      <td width="113"><div align="right">2. 
          <input name="vorauswahl" type="submit" id="vorauswahl2" value="Vorauswahl">
        </div></td>
    </tr>
    <?php do { ?>
    <tr onmouseover="setPointer(this, 3, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 3, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 3, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td><a href="la62.php?url_user=<?php echo $row_rst1['id']; ?>&url_idk=<?php echo utf8_decode($row_rstR['idk']);?>" target="_blank"><?php echo utf8_decode($row_rstR['firma']); ?></a></td>
      <td><?php echo utf8_decode($row_rstR['strasse']); ?></td>
      <td><?php echo utf8_decode($row_rstR['plz']); ?> <?php echo utf8_decode($row_rstR['ort']); ?> <input name="hurl_kunden" type="hidden" id="hurl_kunden2" value="<?php echo $row_rstR['idk']; ?>"></td>
      <td> 
        <div align="center"> 
          <input <?php if (!(strcmp($Http_post_vars['select2'],"radiobutton"))) {echo "CHECKED";} ?> type="radio" name="vorauswahl_id" value="<?php echo $row_rstR['idk']; ?>">
          <img src="picture/s_tbl.png" alt="Kundendaten f&uuml;r Rechnung und Lieferung &uuml;bernehmen" width="16" height="16"> 
          + 
          <input type="radio" name="debitor_name" value="<?php echo $row_rstR['firma']; ?>">
          <img src="picture/iconchance_16x16.gif" alt="zus&auml;tzlich den Debitorennamen &uuml;berschreiben" width="16" height="16"></div></td>
    </tr>
    <?php } while ($row_rstR = mysql_fetch_assoc($rstR)); ?>
  </table>
  <br>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rstR == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine Kundendaten gefunden.</font></p>
  <?php } // Show if recordset empty ?>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="190">SAP-Kundenummer</td>
      <td width="350">
<input name="debitorennummer" type="text" id="debitorennummer" value="<?php echo $row_rst4['debitorennummer']; ?>"></td>
      <td width="190"><div align="right">3. 
          <input name="saveaktuell" type="submit" id="saveaktuell" value="Kontaktdaten Speichern">
        </div></td>
    </tr>
  </table>
  <br>
  <table width="950" height="205" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="226"><img src="picture/s_tbl.png" width="16" height="16"> Rechnung</td>
      <td width="724" > <select name="select2">
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
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="190"><img src="picture/s_tbl.png" width="16" height="16"> Lieferung</td>
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
    <tr>
      <td><p>interne Bemerkung f&uuml;r Kostenkl&auml;rung</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <td><textarea name="fm2notes" cols="50" rows="6" id="textarea"><?php echo $row_rst4['fm2notes']; ?></textarea></td>
    </tr>
  </table>
  <p>
    <input type="hidden" name="MM_update" value="form2">
  </p>
  </form>
<p> 
  <input type="hidden" name="MM_insert" value="form1">
</p>
<?php include("la4.rstuserst.php");?>
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

mysql_free_result($rsttyp);

mysql_free_result($rst8);

mysql_free_result($rst9);

/* mysql_free_result($rstkundenR); */
?>
</p>

  <?php include("footer.tpl.php"); ?>
