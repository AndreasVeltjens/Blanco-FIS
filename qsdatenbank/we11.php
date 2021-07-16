<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "we11";
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



if (isset($_POST['poscopy']) && $url_we_id>0){
	
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rst2 = "SELECT * FROM wareneingangsdaten WHERE wareneingangsdaten.we_id='$url_we_id' ORDER BY wareneingangsdaten.wed_bezeichnung";
	$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
	$row_rst2 = mysql_fetch_assoc($rst2);
	$totalRows_rst2 = mysql_num_rows($rst2);
	
	if ($totalRows_rst2>0){
				do {
					$insertSQL = sprintf("INSERT INTO wareneingangsdaten (we_id, wed_bezeichnung, wed_grenze, wed_wert, wed_toleranz, wed_notes, wed_status) VALUES (%s,  %s,%s, %s, %s, %s, %s)",
									   GetSQLValueString($url_we_id, "int"),
									   GetSQLValueString($row_rst2['wed_bezeichnung'], "text"),
									   GetSQLValueString($row_rst2['wed_grenze'], "text"),
									   GetSQLValueString("neu", "text"),
									   GetSQLValueString($row_rst2['wed_toleranz'], "text"),
									   GetSQLValueString($row_rst2['wed_notes'], "text"),
									   GetSQLValueString("0", "int"));
				
				  mysql_select_db($database_qsdatenbank, $qsdatenbank);
				  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
				  
				} while ($row_rst2 = mysql_fetch_assoc($rst2));
	
	$oktxt.="Daten vollst&auml;ndig kopiert<br>";
	
	}else{
	$errtxt.= "Keine Pr&uuml;plandaten gefunden nund kopiert.<br>";
	}

}


if ((isset($_POST["editpos"]))) {
$ueg=$_POST['hurl_we_grenze']-$_POST['hurl_we_toleranz']/2;
$oeg=$_POST['hurl_we_grenze']+$_POST['hurl_we_toleranz']/2;

if ($_POST['wed_wert']>=$ueg && $ueg>0){
		$_POST['hurl_wed_status']=1;
		$einstufung.="Toleranzgrenze unten erf&uuml;llt.";

		if ($_POST['wed_wert']<=$oeg && $oeg>0){
				$_POST['hurl_wed_status']=1;
				$einstufung.="Toleranzgrenze oben erf&uuml;llt.";
		} else {
			$_POST['hurl_wed_status']=2;
			$einstufung.="Toleranzgrenze oben nicht erf&uuml;llt.";
		}	
} else {
		$_POST['hurl_wed_status']=2;
		$einstufung.="Toleranzgrenze unten nicht erf&uuml;llt.";
}
if ($_POST['wed_wert'] == $_POST['hurl_we_grenze'] && strlen($_POST['wed_wert'])>0 ){ 
		$_POST['hurl_wed_status']=1;
		$einstufung="entspricht SOLL- Wert";
}
if (strlen($_POST['wed_wert'])==0 || $_POST['wed_wert']==" " || $_POST['wed_wert']=="" ){ 
		$_POST['hurl_wed_status']=2;
		$einstufung="Eingabefehler";
}	
if ($_POST['wed_wert']=="Neu" || $_POST['wed_wert']=="neu"){ 
		$_POST['hurl_wed_status']=0;
		$einstufung="noch nicht gepr&uuml;ft";
}

 $updateSQL = sprintf("UPDATE wareneingangsdaten SET wed_wert=%s, wed_status=%s WHERE wed_id=%s",
                       GetSQLValueString($_POST['wed_wert'], "text"),
                       GetSQLValueString($_POST['hurl_wed_status'], "int"),
                       GetSQLValueString($_POST['hurl_wed_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  
$oktxt="Position gespeichert- Status als ".$_POST['hurl_wed_status']." eingestuft. Grund: ".$einstufung. " UEG: $ueg  OEG: $oeg  Wert: ".$_POST['wed_wert'];
}

if ((isset($_POST['delpos'])) && ($_POST['hurl_wed_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM wareneingangsdaten WHERE wed_id=%s",
                       GetSQLValueString($_POST['hurl_wed_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($_POST["save"])) ) {
  $updateSQL = sprintf("UPDATE wareneingang SET we_nummer=%s, we_lieferant=%s, we_stueckzahl=%s, we_bezeichnung=%s, we_pruefumfang=%s, we_user=%s, we_datum=%s, we_frei=%s,we_lokz=%s, we_notes=%s, we_vorlage=%s WHERE we_id=%s",
                       GetSQLValueString($_POST['we_nummer'], "text"),
                       GetSQLValueString($_POST['we_lieferant'], "text"),
                       GetSQLValueString($_POST['we_stueckzahl'], "text"),
                       GetSQLValueString($_POST['we_bezeichnung'], "text"),
                       GetSQLValueString($_POST['we_pruefumfang'], "text"),
                       GetSQLValueString($_POST['hurl_user'], "int"),
                       GetSQLValueString($_POST['we_datum'], "date"),
                       GetSQLValueString($_POST['we_frei'], "int"),
					   GetSQLValueString($_POST['we_lokz'], "int"),
                       GetSQLValueString($_POST['we_notes'], "text"),
                       GetSQLValueString($_POST['we_vorlage'], "int"),
                       GetSQLValueString($_POST['hurl_we_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($_POST["copy"] ))) {
  $insertSQL = sprintf("INSERT INTO wareneingang (we_pruefer, we_nummer, we_lieferant, we_stueckzahl, we_bezeichnung, we_pruefumfang, we_user, we_datum, we_frei, we_notes, we_vorlage) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($row_rst1['name'], "text"),
					   GetSQLValueString($_POST['we_nummer'], "text"),
                       GetSQLValueString($_POST['we_lieferant'], "text"),
                       GetSQLValueString($_POST['we_stueckzahl'], "text"),
                       GetSQLValueString($_POST['we_bezeichnung'], "text"),
                       GetSQLValueString($_POST['we_pruefumfang'], "text"),
                       GetSQLValueString($_POST['hurl_user'], "int"),
                       "now()",
                       0,
                       GetSQLValueString($_POST['we_notes'], "text"),
                       GetSQLValueString($_POST['we_vorlage'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
	$oktxt.="Wareneingang kopiert.";
	/* kopiere auf die Pr&uuml;fdatensätze  */
	$lastinsertid=mysql_insert_id();
	
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rst2 = "SELECT * FROM wareneingangsdaten WHERE wareneingangsdaten.we_id='$url_we_id' ORDER BY wareneingangsdaten.wed_bezeichnung";
	$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
	$row_rst2 = mysql_fetch_assoc($rst2);
	$totalRows_rst2 = mysql_num_rows($rst2);
	
if ($totalRows_rst2>0){
			do {
				$insertSQL = sprintf("INSERT INTO wareneingangsdaten (we_id, wed_bezeichnung, wed_grenze, wed_wert, wed_toleranz, wed_notes, wed_status) VALUES (%s,  %s,%s, %s, %s, %s, %s)",
								   GetSQLValueString($lastinsertid, "int"),
								   GetSQLValueString($row_rst2['wed_bezeichnung'], "text"),
								   GetSQLValueString($row_rst2['wed_grenze'], "text"),
								   GetSQLValueString("neu", "text"),
								   GetSQLValueString($row_rst2['wed_toleranz'], "text"),
								   GetSQLValueString($row_rst2['wed_notes'], "text"),
								   GetSQLValueString("0", "int"));
			
			  mysql_select_db($database_qsdatenbank, $qsdatenbank);
			  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
			  
			} while ($row_rst2 = mysql_fetch_assoc($rst2));

$oktxt.="Daten vollst&auml;ndig kopiert<br>";

}else{
$errtxt.= "Keine Pr&uuml;plandaten gefunden nund kopiert.<br>";
}



$updateGoTo = "we11.php?url_user=".$row_rst1['id']."&url_we_id=".$lastinsertid."&oktxt=".$oktxt."&errtxt=".$errtxt;
  header(sprintf("Location: %s", $updateGoTo));
$errtxt .= "Weiterleitung auf die kopierten Datensatz war nicht erfolgreich. Bitte erneut versuchen.";

}/* kopieren */



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {



$ueg=$_POST['wed_grenze']-$_POST['wed_toleranz']/2;
$oeg=$_POST['wed_grenze']+$_POST['wed_toleranz']/2;

if ($_POST['wed_wert']>=$ueg && $ueg>0){
		$_POST['wed_status']=1;
		$einstufung.="Toleranzgrenze unten erf&uuml;llt.";

		if ($_POST['wed_wert']<=$oeg && $oeg>0){
				$_POST['wed_status']=1;
				$einstufung.="Toleranzgrenze oben erf&uuml;llt.";
		} else {
			$_POST['wed_status']=2;
			$einstufung.="Toleranzgrenze oben nicht erf&uuml;llt.";
		}	
} else {
		$_POST['wed_status']=2;
		$einstufung.="Toleranzgrenze unten nicht erf&uuml;llt.";
}
if ($_POST['wed_wert'] == $_POST['wed_grenze'] && strlen($_POST['wed_wert'])>0 ){ 
		$_POST['wed_status']=1;
		$einstufung="entspricht SOLL- Wert";
}
if (strlen($_POST['wed_wert'])==0 || $_POST['wed_wert']==" " || $_POST['wed_wert']=="" ){ 
		$_POST['wed_status']=2;
		$einstufung="Eingabefehler";
}	
if ($_POST['wed_wert']=="Neu" || $_POST['wed_wert']=="neu"){ 
		$_POST['wed_status']=0;
		$einstufung="noch nicht gepr&uuml;ft";
}


  $insertSQL = sprintf("INSERT INTO wareneingangsdaten (we_id, wed_bezeichnung, wed_grenze, wed_wert, wed_toleranz, wed_notes, wed_status) VALUES (%s,  %s,%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hurl_we_id'], "int"),
                       GetSQLValueString($_POST['wed_bezeichnung'], "text"),
                       GetSQLValueString($_POST['wed_grenze'], "text"),
					   GetSQLValueString($_POST['wed_wert'], "text"),
                       GetSQLValueString($_POST['wed_toleranz'], "text"),
                       GetSQLValueString($_POST['wed_notes'], "text"),
                       GetSQLValueString($_POST['wed_status'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM wareneingangsdaten WHERE wareneingangsdaten.we_id='$url_we_id' ORDER BY wareneingangsdaten.wed_bezeichnung";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM wareneingang WHERE wareneingang.we_id='$url_we_id'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst21 = "SELECT * FROM artikeldaten, link_artikeldaten_pruef WHERE link_artikeldaten_pruef.pruef_id='$url_id_p' and artikeldaten.artikelid=link_artikeldaten_pruef.artikelid ORDER BY artikeldaten.Gruppe";
$rst21 = mysql_query($query_rst21, $qsdatenbank) or die(mysql_error());
$row_rst21 = mysql_fetch_assoc($rst21);
$totalRows_rst21 = mysql_num_rows($rst21);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst20 = "SELECT * FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rst20 = mysql_query($query_rst20, $qsdatenbank) or die(mysql_error());
$row_rst20 = mysql_fetch_assoc($rst20);
$totalRows_rst20 = mysql_num_rows($rst20);

if ((isset($_POST["cancel"])) ) {
$updateGoTo = "we1.php?url_user=".$row_rst1['id'];
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
<table width="980" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?>
      <strong><font color="#009900"><?php echo $oktxt ?></font></strong></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td width="152">Aktionen W&auml;hlen:</td>
    <td width="339"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> &nbsp;&nbsp;
        <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        <input type="submit" name="Submit" value="Seite neu laden">
      </form></td>
    <td width="123" rowspan="4"> <div align="right"><img src="picture/W7_Img_Install.jpg" width="66" height="66"> 
      </div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td rowspan="3">&nbsp; </td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>

</table><hr>
 <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td width="164"><strong>Beschreibung</strong></td>
      <td width="605"> <input name="we_bezeichnung" type="text" id="we_bezeichnung" value="<?php echo $row_rst3['we_bezeichnung']; ?>" size="60"> 
      </td>
      <td width="211"> <input name="hurl_we_id" type="hidden" id="hurl_we_id" value="<?php echo $row_rst3['we_id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><strong>Materialnummer</strong></td>
      <td><input name="we_nummer" type="text" id="we_nummer" value="<?php echo $row_rst3['we_nummer']; ?>"> 
      </td>
      <td><div align="right"><img src="picture/Cfinclude.gif" width="18" height="18"> 
          <input name="save" type="submit" id="save2" value="Speichern">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Lokz</td>
      <td><input <?php if (!(strcmp($row_rst3['we_lokz'],1))) {echo "checked";} ?> name="we_lokz" type="checkbox" id="we_lokz" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Lieferant</td>
      <td><input name="we_lieferant" type="text" id="we_lieferant" value="<?php echo $row_rst3['we_lieferant']; ?>"></td>
      <td><div align="right"><a href="pdfetikett9.php?url_we_id=<?php echo $row_rst3['we_id']; ?>" target="_blank"> 
          <input name="imageField" type="image" src="picture/quote.gif" width="17" height="21" border="0">
          Aufkleber drucken </a></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Gesamtliefermenge</td>
      <td><input name="we_stueckzahl" type="text" id="dauer" value="<?php echo $row_rst3['we_stueckzahl']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><strong>Pr&uuml;fumfang</strong></td>
      <td><input name="we_pruefumfang" type="text" id="we_pruefumfang" value="<?php echo $row_rst3['we_pruefumfang']; ?>" size="80"></td>
      <td><div align="right"><img src="picture/ic_textex_24x24.png" width="24" height="24" align="absmiddle"> 
          <input name="copy" type="submit" id="save" value="WE Kopieren">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Wareneingangsdatum</td>
      <td><input name="we_datum" type="text" id="we_datum" value="<?php echo $row_rst3['we_datum']; ?>">
        - Wareneingangspr&uuml;fung-Nr.: <?php echo $row_rst3['we_id']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td> <p>Grund und Bemerkungen</p>
        <p>Lieferschein-Nummer<br>
          Pr&uuml;fberichtsnummer<br>
          Lieferant</p></td>
      <td><textarea name="we_notes" cols="80" rows="7" id="we_notes"><?php echo $row_rst3['we_notes']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="39">Status Befundung durch den Pr&uuml;fer</td>
      <td> 
        <?php if  ($row_rst3['we_frei']==1){?>
        <img src="picture/st1.gif" width="15" height="15"> 
        <?php } elseif ($row_rst3['we_frei']==0) { ?>
        <img src="picture/st2.gif" width="15" height="15"> 
        <?php } elseif ($row_rst3['we_frei']==2) {?>
        <img src="picture/st3.gif" width="15" height="15"> 
        <?php } else {?>
        <img src="picture/st5.gif" width="15" height="15"> 
        <?php }?>
        <select name="we_frei" id="we_frei">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['we_frei']))) {echo "SELECTED";} ?>>0- 
          in Pr&uuml;fung</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['we_frei']))) {echo "SELECTED";} ?>>1 
          i.O. Befund</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['we_frei']))) {echo "SELECTED";} ?>>2 
          Sonderfreigabe - Fehlermeldung Q2 angelegt</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['we_frei']))) {echo "SELECTED";} ?>>3 
          Sonderfreigabe - ohne Fehlermeldung Q2 angelegt</option>
        </select></td>
      <td><div align="right">alle Einzelpr&uuml;fungen kopieren, wenn mehrere 
          Stichproben notwendig sind</div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Pr&uuml;fpflichtiges Bauteil</td>
      <td> <select name="we_vorlage" id="we_vorlage">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['we_vorlage']))) {echo "SELECTED";} ?>>Normal 
          Wareneingang</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['we_vorlage']))) {echo "SELECTED";} ?>>pruefpflichtiger 
          Wareneingang</option>
        </select></td>
      <td> <div align="right"><img src="picture/ic_textex_24x24.png" width="24" height="24" align="absmiddle"> 
          <input name="poscopy" type="submit" id="copy" value="Pos Kopieren">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>Pr&uuml;fer/-in</td>
      <td><input name="we_pruefer" type="text" id="we_pruefer" value="<?php echo $row_rst3['we_pruefer']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form3">
  <input type="hidden" name="MM_insert" value="form3">
</form>
<hr>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der durchgef&uuml;hrten Pr&uuml;fungen<br>
  
<table width="980" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="261"><strong><em>Bezeichnung</em></strong></td>
    <td width="93"><strong><em>Soll</em></strong></td>
    <td width="92"><em><strong>Toleranz</strong></em></td>
    <td width="92">untere Grenze - obere Grenze</td>
    <td width="92"><strong><em>Istwert</em></strong></td>
    <td width="101"><strong><em>Bemerkungen</em></strong></td>
    <td width="183">&nbsp;</td>
  </tr>
  <?php 
  $gruppe="";
  do { ?>
  <?php   if ($row_rst2['wed_bezeichnung']!=$gruppe){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="10" nowrap="nowrap"    bgcolor="#EEEEEE"><em><img src="picture/s_tbl.png" width="16" height="16"> 
      <?php echo $row_rst2['gruppe']; ?></em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if  ($row_rst2['wed_status']==1){?>
        <img src="picture/st1.gif" width="15" height="15"> 
        <?php } elseif ($row_rst2['wed_status']==0) { ?>
        <img src="picture/st2.gif" width="15" height="15"> 
        <?php }else{?>
        <img src="picture/st3.gif" width="15" height="15"> 
        <?php }?>
        <?php echo wordwrap($row_rst2['wed_bezeichnung'],30,"<br>",250); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['wed_grenze']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['wed_toleranz']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['wed_grenze']-$row_rst2['wed_toleranz']/2 ?> bis <?php echo $row_rst2['wed_grenze']+$row_rst2['wed_toleranz']/2 ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="wed_wert" type="text" id="wed_wert" value="<?php echo $row_rst2['wed_wert']; ?>"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst2['wed_notes'],30,"<br>",250); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="editpos" type="submit" id="editpos" value="&auml;ndern">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_we_id" type="hidden" id="hurl_we_id" value="<?php echo $row_rst2['we_id']; ?>">
          <input name="hurl_we_toleranz" type="hidden" id="hurl_we_id" value="<?php echo $row_rst2['wed_toleranz']; ?>">
          <input name="hurl_we_grenze" type="hidden" id="hurl_we_id" value="<?php echo $row_rst2['wed_grenze']; ?>">
          <input name="hurl_wed_id" type="hidden" id="hurl_wed_id" value="<?php echo $row_rst2['wed_id']; ?>"> 
          <input name="delpos" type="submit" id="edit" value="l&ouml;schen">
        </div></td>
    </tr>
  </form>
  <?php 
    $gruppe=$row_rst2['wed_bezeichnung'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
</p>
<p>&nbsp;</p>

<p><strong><em>Neuen zus&auml;tzlichen Pr&uuml;fschritt anlegen</em></strong></p>
<form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="178">Beschreibung</td>
      <td width="375"> <input name="wed_bezeichnung" type="text" id="wed_bezeichnung" value="<?php echo $_POST['wed_bezeichnung']; ?>" size="80" maxlength="255"></td>
      <td width="180">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Grenzwert</td>
      <td> <input name="wed_grenze" type="text" id="wed_grenze" value="<?php echo $_POST['wed_grenze']; ?>">
        Toleranz: 
        <input name="wed_toleranz" type="text" id="wed_toleranz" value="<?php echo $_POST['wed_toleranz']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Istwert</td>
      <td> <input name="wed_wert" type="text" id="wed_wert" value="<?php echo $_POST['wed_wert']; ?>"> 
      </td>
      <td> <div align="right"> 
          <input name="save2" type="submit" id="save24" value="neu anlegen">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Bewertung</td>
      <td> <select name="wed_status" id="wed_status">
          <option value="0" <?php if (!(strcmp(0, $_POST['wed_status']))) {echo "SELECTED";} ?>>keine 
          Auswahl</option>
          <option value="1" <?php if (!(strcmp(1, $_POST['wed_status']))) {echo "SELECTED";} ?>>Ja, 
          Bedingung erfüllt </option>
          <option value="2" <?php if (!(strcmp(2, $_POST['wed_status']))) {echo "SELECTED";} ?>>Nein, 
          Prüfbeding nicht erfüllt</option>
          <option value="3" <?php if (!(strcmp(3, $_POST['wed_status']))) {echo "SELECTED";} ?>>Sonderfreigabe 
          siehe Text</option>
        </select></td>
      <td><input name="hurl_we_id" type="hidden" id="hurl_id_p22" value="<?php echo $row_rst3['we_id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user3" value="<?php echo $url_user; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Bemerkung</td>
      <td><input name="wed_notes" type="text" id="wed_notes" value="<?php echo $_POST['wed_notes']; ?>" size="80"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form4">
  <input type="hidden" name="MM_update" value="form4">
  
</form>


<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst20);

mysql_free_result($rst3);

?>
<p><strong><em>Legende:</em></strong></p>
<p><img src="picture/st1.gif" width="15" height="15"> i. O. Befund<br>
  <img src="picture/st2.gif" width="15" height="15"> in Pr&uuml;fung<br>
  <img src="picture/st3.gif" width="15" height="15"> nicht i.O. Befund</p>
<?php include("footer.tpl.php"); ?>
