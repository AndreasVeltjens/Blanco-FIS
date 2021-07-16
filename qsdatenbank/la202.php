<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php  $la = "la202";
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

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO linkfehlerartikel (fid, artikelid) VALUES (%s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['select2'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hartikelid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS['hlfaid'])) && ($HTTP_POST_VARS['hlfaid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM linkfehlerartikel WHERE lfaid=%s",
                       GetSQLValueString($HTTP_POST_VARS['hlfaid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["insertpp"])) && ($HTTP_POST_VARS["selectpp"] >= 1)) {
  $insertSQL = sprintf("INSERT INTO produktionsprog (nummer, typ, bezeichnung, zeit, fuser, datum) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['Nummer'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['selectpp'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['Bezeichnung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['Arbeitszeit'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_url_user'], "int"),
                        GetSQLValueString($HTTP_POST_VARS['pdatum'], "date"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["save"]))) {
	// Bild hochgeladen
            $originalFileName = $HTTP_POST_FILES['imageFile']['name'];
            $uploadFileName = uniqid($originalFileName).".".substr($originalFileName,-3);
			$oktxt.= "neues Logo hochgeladen und temporär gespeichert. ".$HTTP_POST_FILES['imageFile']['name']."\n ";
		
		 // Wenn 2.Checkbox angeklickt aber kein neues Bild hochgeladen wird
        if ($HTTP_POST_FILES['imageFile']['name']=="")
        {  $uploadFileName = $HTTP_POST_VARS['orig_file_name'];
			$oktxt.= "Logo-Datei wurde nicht erneuert. Alte Datei $uploadFileName wird verwendet und beibehalten.\n";
        }



  $updateSQL = sprintf("UPDATE artikeldaten SET Bezeichnung=%s, Nummer=%s, Beschreibung=%s, Zeichnungsnummer=%s, aktiviert=%s, aktivrep=%s, Datum=%s, stuli=%s, lagerplatz=%s, lagerplatzalternative=%s, lagerplatzalternative2=%s, Gruppe=%s, 
  anzahlp=%s,version=%s, gewicht=%s, Arbeitszeit=%s, Einzelpreis=%s, Kontierung=%s, Bestand=%s, Reservierung=%s, verpackung=%s,wiederbeschaffungszeit=%s,leistungsangabe=%s, pruefzeichen=%s, traglast=%s,
  hinweis=%s, logo=%s, repvorgabezeit=%s, ean13=%s, bks=%s, link_dokument=%s , anzahl_etikett_verpackung=%s,anzahl_etikett_typenschild=%s,dokuset=%s, reichweite=%s, zusatzreichweite=%s, visualisierung=%s WHERE artikelid=%s",
                       GetSQLValueString($HTTP_POST_VARS['Bezeichnung'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['Nummer'], "text"),
						 GetSQLValueString($HTTP_POST_VARS['Beschreibung'], "text"),
						 GetSQLValueString($HTTP_POST_VARS['Zeichnungsnummer'], "text"),
						  GetSQLValueString($HTTP_POST_VARS['aktiviert'], "int"), 
						    GetSQLValueString($HTTP_POST_VARS['aktivrep'], "int"), 
                       GetSQLValueString($HTTP_POST_VARS['Datum'], "date"),
					   GetSQLValueString($HTTP_POST_VARS['stuli'], "text"),
					     GetSQLValueString($HTTP_POST_VARS['lagerplatz'], "text"),  
						 GetSQLValueString($HTTP_POST_VARS['lagerplatzalternative'], "text"), 
						 GetSQLValueString($HTTP_POST_VARS['lagerplatzalternative2'], "text"), 
						 GetSQLValueString($HTTP_POST_VARS['Gruppe'], "text"),
						  GetSQLValueString($HTTP_POST_VARS['anzahlp'], "int"),
						  GetSQLValueString($HTTP_POST_VARS['version'], "text"), 
					   GetSQLValueString($HTTP_POST_VARS['gewicht'], "text"), 
						 GetSQLValueString($HTTP_POST_VARS['Arbeitszeit'], "text"),
						 
						  GetSQLValueString($HTTP_POST_VARS['Einzelpreis'], "date"),
					   GetSQLValueString($HTTP_POST_VARS['Kontierung'], "text"), 
						 GetSQLValueString($HTTP_POST_VARS['Bestand'], "text"),
						 GetSQLValueString($HTTP_POST_VARS['Reservierung'], "text"),
						 
						 GetSQLValueString($HTTP_POST_VARS['verpackung'], "text"), 
						 GetSQLValueString($HTTP_POST_VARS['wiederbeschaffungszeit'], "text"),
						 
						  GetSQLValueString($HTTP_POST_VARS['leistungsangabe'], "text"),
						   GetSQLValueString($HTTP_POST_VARS['pruefzeichen'], "text"),
						    GetSQLValueString($HTTP_POST_VARS['traglast'], "text"),
							 GetSQLValueString($HTTP_POST_VARS['hinweis'], "text"),
							 GetSQLValueString($HTTP_POST_VARS['logo'], "text"),
						  GetSQLValueString($HTTP_POST_VARS['repvorgabezeit'], "text"),
						  GetSQLValueString($HTTP_POST_VARS['ean13'], "text"),
						  	  GetSQLValueString($HTTP_POST_VARS['bks'], "text"),
							  	 GetSQLValueString($uploadFileName, "text"),
								GetSQLValueString($HTTP_POST_VARS['anzahl_etikett_verpackung'], "int"), 
								GetSQLValueString($HTTP_POST_VARS['anzahl_etikett_typenschild'], "int"), 
								GetSQLValueString($HTTP_POST_VARS['dokuset'], "int"), 
								GetSQLValueString($HTTP_POST_VARS['reichweite'], "int"), 
								GetSQLValueString($HTTP_POST_VARS['zusatzreichweite'], "int"), 
								GetSQLValueString($HTTP_POST_VARS['visualisierung'], "int"), 
                       GetSQLValueString($HTTP_POST_VARS['hurl_artikelid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
// *** UPLOAD ***
    	
    	// Nur wenn Upload-file und Brancheneintrag in DB geklappt hat dann kopiere
    	// Upload-File ins upload-verz.
    	if ( $originalFileName<>"" && $Result1)
    	{
    	    $destPath = "../documents/materialbilder";
    	    copy($HTTP_POST_FILES['imageFile']['tmp_name'], $destPath."/".$uploadFileName);
			$oktxt.= "Bilddatei im Ordner Materialbilder gespeichert.";
    	}
    	else {
    	    @unlink($HTTP_POST_FILES['imageFile']['tmp_name']);
    	
    	// *** ENDE ***
		
		$oktxt.= "unlink Bilddatei";
		}

/* <?php   $updateGoTo = "la2.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));?> */
}


if ((isset($HTTP_POST_VARS["dublicate"]))) {
  $updateSQL = sprintf("INSERT INTO artikeldaten ( 
  Bezeichnung, Nummer, Beschreibung, Zeichnungsnummer, aktiviert, aktivrep,  
  Datum, stuli, lagerplatz,lagerplatzalternative,lagerplatzalternative2,
  Gruppe, anzahlp, gewicht, Arbeitszeit, Einzelpreis, Kontierung, 
  Bestand, Reservierung, verpackung, wiederbeschaffungszeit, leistungsangabe,
  pruefzeichen, traglast, hinweis, logo, repvorgabezeit, 
  ean13, bks, anzahl_etikett_verpackung,anzahl_etikett_typenschild, dokuset, 
  reichweite, zusatzreichweite, visualisierung) 
  VALUES (%s, %s, %s, %s,%s,      %s,%s, %s, %s, %s,%s,    %s, %s, %s, %s, %s,    %s,%s, %s, %s, %s,     %s, %s, %s,%s, %s,    %s, %s, %s, %s, %s,   %s, %s, %s, %s)",
					GetSQLValueString($HTTP_POST_VARS['Bezeichnung']."(Kopie)", "text"),
					GetSQLValueString($HTTP_POST_VARS['Nummer'], "text"),
					GetSQLValueString($HTTP_POST_VARS['Beschreibung'], "text"),
					GetSQLValueString($HTTP_POST_VARS['Zeichnungsnummer'], "text"),
					GetSQLValueString($HTTP_POST_VARS['aktiviert'], "int"),  
					GetSQLValueString($HTTP_POST_VARS['aktivrep'], "int"),  
					GetSQLValueString($HTTP_POST_VARS['Datum'], "date"),
					GetSQLValueString($HTTP_POST_VARS['stuli'], "text"),
					GetSQLValueString($HTTP_POST_VARS['lagerplatz'], "text"),  
					GetSQLValueString($HTTP_POST_VARS['lagerplatzalternative'], "text"), 
					GetSQLValueString($HTTP_POST_VARS['lagerplatzalternative2'], "text"), 
					
					GetSQLValueString($HTTP_POST_VARS['Gruppe'], "text"),
					GetSQLValueString($HTTP_POST_VARS['anzahlp'], "date"),
					GetSQLValueString($HTTP_POST_VARS['gewicht'], "text"), 
					GetSQLValueString($HTTP_POST_VARS['Arbeitszeit'], "text"),
					GetSQLValueString($HTTP_POST_VARS['Einzelpreis'], "date"),
					
					GetSQLValueString($HTTP_POST_VARS['Kontierung'], "text"), 
					GetSQLValueString($HTTP_POST_VARS['Bestand'], "text"),
					GetSQLValueString($HTTP_POST_VARS['Reservierung'], "text"),
					GetSQLValueString($HTTP_POST_VARS['verpackung'], "text"), 
					GetSQLValueString($HTTP_POST_VARS['wiederbeschaffungszeit'], "text"),					
					
					GetSQLValueString($HTTP_POST_VARS['leistungsangabe'], "text"),
					GetSQLValueString($HTTP_POST_VARS['pruefzeichen'], "text"),
					GetSQLValueString($HTTP_POST_VARS['traglast'], "text"),
					GetSQLValueString($HTTP_POST_VARS['hinweis'], "text"),
					GetSQLValueString($HTTP_POST_VARS['logo'], "text"),
					
					GetSQLValueString($HTTP_POST_VARS['repvorgabezeit'], "text"),
					GetSQLValueString($HTTP_POST_VARS['ean13'], "text"),
					GetSQLValueString($HTTP_POST_VARS['bks'], "text"),
					GetSQLValueString($HTTP_POST_VARS['anzahl_etikett_verpackung'], "int"),
					GetSQLValueString($HTTP_POST_VARS['anzahl_etikett_typenschild'], "int"),
					
					GetSQLValueString($HTTP_POST_VARS['dokuset'], "int"),
					GetSQLValueString($HTTP_POST_VARS['reichweite'], "int"), 
					GetSQLValueString($HTTP_POST_VARS['zusatzreichweite'], "int"), 
					GetSQLValueString($HTTP_POST_VARS['visualisierung'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
	$oktxt= "Kopie der Artikelnummer angelegt. Sie bearbeiten jetzt die Kopie.";
	$lastinsertid=mysql_insert_id();
  $updateGoTo = "la202.php?url_user=".$HTTP_POST_VARS['hurl_url_user']."&url_artikelid=".$lastinsertid."&oktxt=".$oktxt;
 
  header(sprintf("Location: %s", $updateGoTo));
  
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid = '$url_artikelid' ";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten order by artikeldaten.Bezeichnung";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM artikelversionen WHERE artikelversionen.artikelid='$url_artikelid' ORDER BY artikelversionen.version";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstpp = "SELECT * FROM arbeitsplatz ORDER BY arbeitsplatz.arb_name";
$rstpp = mysql_query($query_rstpp, $qsdatenbank) or die(mysql_error());
$row_rstpp = mysql_fetch_assoc($rstpp);
$totalRows_rstpp = mysql_num_rows($rstpp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstppp = "SELECT * FROM produktionsprog WHERE produktionsprog.nummer='$row_rst2[Nummer]'";
$rstppp = mysql_query($query_rstppp, $qsdatenbank) or die(mysql_error());
$row_rstppp = mysql_fetch_assoc($rstppp);
$totalRows_rstppp = mysql_num_rows($rstppp);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la2.php?url_user=".$row_rst1['id'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la21.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["la260"])) ) {
$updateGoTo = "la260.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["la11"])) ) {
$updateGoTo = "la11.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}
if ((isset($HTTP_POST_VARS["co11"])) ) {
$updateGoTo = "co11.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}
include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?> 
<style type="text/css">
<!--
-->
</style>
<form name="form2" method="post" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
  <table width="980" border="0" cellpadding="0" cellspacing="0" class="button">
    <tr bgcolor="#FFFFFF"> 
      <td colspan="6"><?php include('headtxt.tbl.php');?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="6"><?php include('head.tbl.php');?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><div align="right"><img src="picture/fisstartseite.png" width="55" height="55"></div></td>
      <td width="387"><div align="center"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" target="_blank"><img src="picture/aktualisieren.png" width="55" height="55" border="0"></a></div></td>
      <td width="387"><div align="center"><img src="picture/neuanlegen.png" width="55" height="55"></div></td>
      <td width="387"><div align="center"><img src="picture/gss_sticky_panel_rtags.png" width="55" height="55"></div></td>
      <td width="300"><img src="picture/projektordner.png" width="55" height="55"></td>
      <td width="300"><div align="center"><img src="picture/alleberechnungen.png" width="55" height="55"></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="174"><div align="right">
          <input name="cancel" type="submit" class="button" id="cancel2" value="Zur&uuml;ck">
        </div></td>
      <td> <p align="center"> 
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
          <input name="save2" type="submit" class="button" id="save2" value="aktualisieren ">
        </p></td>
      <td><div align="center"> 
          <input name="la11" type="submit" class="button" id="la112" value="Fertigungsmeldungen">
        </div></td>
      <td><div align="center"> 
          <input name="edit" type="submit" class="button" id="edit2" value="Versionen bearbeiten">
        </div></td>
      <td><input name="co11" type="submit" class="button" id="co115" value="Produktionsplan"></td>
      <td><div align="center"> 
          <input name="la260" type="submit" class="button" id="la260" value="Bestands-Bedarfsliste">
          <br>
        </div></td>
    </tr>
  </table>
  
  

  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
    <tr> 
      <td>ausgew&auml;hler Typ:</td>
      <td><select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $row_rst2['artikelid']))) {echo "SELECTED";} ?>><?php echo $row_rst4['Bezeichnung']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select></td>
      <td><div align="right"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" target="_blank"><img src="picture/save.png" border="0"></a> 
          <input name="save" type="submit" class="button" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td width="352"><div align="right"></div></td>
    </tr>
    <tr> 
      <td>FIS-IDA</td>
      <td><?php echo $row_rst2['artikelid']; ?> <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>"> 
        <input name="hurl_url_user" type="hidden" id="hurl_url_user" value="<?php echo $row_rst1['id']; ?>"></td>
      <td width="352"><div align="right"><font size="2" face="Arial, Helvetica, sans-serif">Materialbild: 
          <INPUT name="imageFile" type="file" size="20" accept="image/*">
          <BR>
          </font></div></td>
    </tr>
    <tr> 
      <td width="164">Bezeichnung</td>
      <td width="464"> <input name="Bezeichnung" type="text" size="60" value="<?php echo $row_rst2['Bezeichnung']; ?>"> 
      <td width="352" rowspan="10"><div align="right"> 
          <p><font size="2" face="Arial, Helvetica, sans-serif"> 
            <? if ($row_rst2[link_dokument] <> "") { ?>
            <span class="text10pt">bisheriges Bild:</span><br>
            <img src="<? echo "../documents/materialbilder/".$row_rst2['link_dokument']?>" width="250" border="0"> 
            <? } ?>
            <input type="hidden" name="orig_file_name" value="<? echo $row_rst2['link_dokument']?>">
            </font> <br>
          </p>
          Ausdruckfunktionen: 
          <p><a href="pdfetikett11.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"> 
            <img src="picture/anschreiben.gif" alt="Lagerplatzbeschriftung" width="18" height="18" border="0"></a> 
            <a href="pdfetikett12.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="Lagerplatz A4 mit Bild" width="18" height="18" border="0"></a> 
            <a href="pdfla2k.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialkennzeichnung A4 " width="17" height="18" border="0"></a><a href="pdfla2p.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialdatenblatt mit Bild" width="17" height="18" border="0"></a><a href="pdfla2.php?url_idk=238&url_user_id=<?php echo $url_user; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Materialdaten ohne Bild" width="17" height="18" border="0"></a><a href="pdfetikett13.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="Lagerplatz Aufkleber mit Bild" width="18" height="18" border="0"></a> 
            <a href="pdfetikett13.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>&alternative=1" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="alternativer Lagerplatz 1 - Aufkleber mit Bild" width="18" height="18" border="0"></a>-<a href="pdfetikett13.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>&alternative=2" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="alternativer Lagerplatz 2- Aufkleber mit Bild" width="18" height="18" border="0"></a> 
            <a href="pdfetikett12.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>&alternative=2" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="alternativer Lagerplatz 2 - A4 mit Bild" width="18" height="18" border="0"></a></p>
        </div></td>
    </tr>
    <tr> 
      <td>Nummer</td>
      <td> <input name="Nummer" type="text" size="60" value="<?php echo $row_rst2['Nummer']; ?>"> 
    </tr>
    <tr> 
      <td>Zeichnungsnummer</td>
      <td><input name="Zeichnungsnummer" type="text" size="60" value="<?php echo $row_rst2['Zeichnungsnummer']; ?>"></td>
    </tr>
    <tr> 
      <td>Beschreibung</td>
      <td><textarea name="Beschreibung" cols="55" rows="12"><?php echo $row_rst2['Beschreibung']; ?></textarea></td>
    </tr>
    <tr> 
      <td colspan="2"><hr></td>
    </tr>
    <tr> 
      <td colspan="2"><em><strong>Visualisierung &amp; Aktivierung</strong></em></td>
    </tr>
    <tr> 
      <td>Visualisierung </td>
      <td><select name="visualisierung" id="visualisierung">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['visualisierung']))) {echo "SELECTED";} ?>>niemals 
          anzeigen</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst2['visualisierung']))) {echo "SELECTED";} ?>>Montage 
          6101 - 611401</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['visualisierung']))) {echo "SELECTED";} ?>>Ersatzteile 
          6101 -611404</option>
        </select></td>
    </tr>
    <tr> 
      <td>aktiviert f&uuml;r Fertigungsmeldung</td>
      <td><input <?php if (!(strcmp($row_rst2['aktiviert'],1))) {echo "checked";} ?> name="aktiviert" type="checkbox" id="frei" value="1">
        Datum 
        <input name="Datum" type="text" id="Datum" value="<?php echo $row_rst2['Datum']; ?>"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'Datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
    </tr>
    <tr> 
      <td>aktiv f&uuml;r Reparaturr&uuml;ckmeldung</td>
      <td><input <?php if (!(strcmp($row_rst2['aktivrep'],1))) {echo "checked";} ?> name="aktivrep" type="checkbox" id="aktivrep" value="1"> 
      </td>
    </tr>
    <tr> 
      <td><p>StuLi<br>
          redrograde Entnahme mit Bauteil:</p></td>
      <td> <select name="stuli" id="stuli">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['stuli']))) {echo "SELECTED";} ?>>keine 
          Stückliste vorhanden</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $row_rst2['stuli']))) {echo "SELECTED";} ?>><?php echo $row_rst4['Bezeichnung']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select> <br>
        St&uuml;cklistenverweis bei Fertigungsmeldung</td>
    </tr>
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
    <tr> 
      <td colspan="2"><em><strong>Kontierung &amp; Fertigungsmeldungen</strong></em></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Lagerplatz</td>
      <td><input name="lagerplatz" type="text" id="lagerplatz" value="<?php echo $row_rst2['lagerplatz']; ?>" size="60"></td>
      <td>z:Bsp.Lagerort-Warenhaus-Fach-Ebene</td>
    </tr>
    <tr> 
      <td>Lagerplatz Alternative</td>
      <td><input name="lagerplatzalternative" type="text" id="lagerplatzalternative" value="<?php echo $row_rst2['lagerplatzalternative']; ?>" size="60"></td>
      <td>z:Bsp.Lagerort-Warenhaus-Fach-Ebene</td>
    </tr>
    <tr> 
      <td>2. Lagerplatz Alternative</td>
      <td><input name="lagerplatzalternative2" type="text" id="lagerplatzalternative2" value="<?php echo $row_rst2['lagerplatzalternative2']; ?>" size="60"></td>
      <td>z:Bsp.Lagerort-Warenhaus-Fach-Ebene - Fremdlager</td>
    </tr>
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
    <tr> 
      <td> Warengruppe </td>
      <td><input name="Gruppe" type="text" id="Gruppe" value="<?php echo $row_rst2['Gruppe']; ?>" size="60"></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>Anzahlp</td>
      <td><input name="anzahlp" type="text" size="60" value="<?php echo $row_rst2['anzahlp']; ?>"></td>
      <td>Anzahl der pr&uuml;fpflichtigen Bauteile bei Fertigmeldung</td>
    </tr>
    <tr> 
      <td>aktive Version</td>
      <td> <select name="version" id="version">
          <?php
do {  
?>
          <option value="<?php echo $row_rst5['version']?>"<?php if (!(strcmp($row_rst5['version'], $row_rst2['version']))) {echo "SELECTED";} ?>><?php echo $row_rst5['version']?></option>
          <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
        </select></td>
      <td>automat.Vorlage der Version in der Fertigmeldung</td>
    </tr>
    <tr> 
      <td>Gewicht </td>
      <td><input name="gewicht" type="text" size="60" value="<?php echo $row_rst2['gewicht']; ?>"></td>
      <td>Teilegewicht pro St&uuml;ck</td>
    </tr>
    <tr> 
      <td height="23">Verpackung</td>
      <td><input name="verpackung" type="text" id="verpackung" value="<?php echo $row_rst2['verpackung']; ?>" size="60"></td>
      <td>Karton, BOX600x400, BOX400x300</td>
    </tr>
    <tr> 
      <td>Wiederbeschaffungszeit</td>
      <td><input name="wiederbeschaffungszeit" type="text" size="60" value="<?php echo $row_rst2['wiederbeschaffungszeit']; ?>"></td>
      <td>1 Woche od. 1 Monat</td>
    </tr>
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
    <tr> 
      <td>Arbeitszeit</td>
      <td><input name="Arbeitszeit" type="text" size="60" value="<?php echo $row_rst2['Arbeitszeit']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Einzelpreis</td>
      <td><input name="Einzelpreis" type="text" size="60" value="<?php echo $row_rst2['Einzelpreis']; ?>"></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>Kontierung</td>
      <td><input name="Kontierung" type="text" size="60" value="<?php echo $row_rst2['Kontierung']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Sollbestand</td>
      <td><input name="Bestand" type="text" size="40" value="<?php echo $row_rst2['Bestand']; ?>">
        x 
        <select name="reichweite" id="reichweite">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>keine 
          Planung</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>1 
          Woche</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>2 
          Wochen</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>3 
          Wochen (Standard)</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>4 
          Wochen</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>5 
          Wochen</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>6 
          Wochen</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>7 
          Wochen</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst2['reichweite']))) {echo "SELECTED";} ?>>8 
          Wochen</option>
        </select></td>
      <td>Planungsgr&ouml;&szlig;e f&uuml;r die Anzeige im PPP (Sicherheitsbestand)</td>
    </tr>
    <tr> 
      <td>Zusatzbestand</td>
      <td><input name="Reservierung" type="text" size="40" value="<?php echo $row_rst2['Reservierung']; ?>">
        niviliert auf 
        <select name="zusatzreichweite" id="select">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>keine 
          Planung</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>1 
          Woche</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>2 
          Wochen</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>3 
          Wochen </option>
          <option value="4" <?php if (!(strcmp(4, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>4 
          Wochen</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>5 
          Wochen</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>6 
          Wochen</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>7 
          Wochen</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst2['zusatzreichweite']))) {echo "SELECTED";} ?>>8 
          Wochen</option>
        </select></td>
      <td><div align="left">f&uuml;r eine Sonderbedarfsituation: nivilierter Kundeneinzelbedarf 
        </div></td>
    </tr>
    <tr> 
      <td>g&uuml;ltig im Werk:</td>
      <td><input name="bks" type="text" size="60" value="<?php echo $row_rst2['bks']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
    <tr> 
      <td><strong><em>zus&auml;tzliche Angaben</em></strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Reparaturvorgabezeit</td>
      <td> <input name="repvorgabezeit" type="text" id="repvorgabezeit" value="<?php echo $row_rst2['repvorgabezeit']; ?>"></td>
      <td>Zuordnung f&uuml;r PCNA-Auftr&auml;ge bei Reparaturen</td>
    </tr>
    <tr> 
      <td>Anzahl der SN-Etiketten</td>
      <td><select name="anzahl_etikett_verpackung" id="anzahl_etikett_verpackung">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['anzahl_etikett_verpackung']))) {echo "SELECTED";} ?>>kein</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst2['anzahl_etikett_verpackung']))) {echo "SELECTED";} ?>>1</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['anzahl_etikett_verpackung']))) {echo "SELECTED";} ?>>2</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst2['anzahl_etikett_verpackung']))) {echo "SELECTED";} ?>>3</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst2['anzahl_etikett_verpackung']))) {echo "SELECTED";} ?>>4</option>
        </select>
        Etiketten pro Ger&auml;t</td>
      <td>f&uuml;r den automatischen Druck bei Montage</td>
    </tr>
    <tr> 
      <td>Anzahl der Typenschilder</td>
      <td><select name="anzahl_etikett_typenschild" id="anzahl_etikett_typenschild">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['anzahl_etikett_typenschild']))) {echo "SELECTED";} ?>>kein</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst2['anzahl_etikett_typenschild']))) {echo "SELECTED";} ?>>1</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['anzahl_etikett_typenschild']))) {echo "SELECTED";} ?>>2</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst2['anzahl_etikett_typenschild']))) {echo "SELECTED";} ?>>3</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst2['anzahl_etikett_typenschild']))) {echo "SELECTED";} ?>>4</option>
        </select>
        Etikettenreihen pro Ger&auml;t</td>
      <td>f&uuml;r den automatischen Druck bei Montage</td>
    </tr>
    <tr> 
      <td>Anzahl der Dokusets</td>
      <td><select name="dokuset" id="dokuset">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['dokuset']))) {echo "SELECTED";} ?>>kein</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst2['dokuset']))) {echo "SELECTED";} ?>>1</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['dokuset']))) {echo "SELECTED";} ?>>2</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst2['dokuset']))) {echo "SELECTED";} ?>>3</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst2['dokuset']))) {echo "SELECTED";} ?>>4</option>
        </select>
        A4-Bl&auml;tter pro Ger&auml;t</td>
      <td>f&uuml;r den automatischen Druck bei Montage</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pruefzeichen</td>
      <td><input name="pruefzeichen" type="text" size="60" value="<?php echo $row_rst2['pruefzeichen']; ?>"></td>
      <td>im root-Ordner ../picture <img src="./picture/eti_<?php echo $row_rst2['pruefzeichen']; ?>.jpg"></td>
    </tr>
    <tr> 
      <td>Traglast</td>
      <td><input name="traglast" type="text" size="60" value="<?php echo $row_rst2['traglast']; ?>"></td>
      <td>Angabe auf dem Typenschild</td>
    </tr>
    <tr> 
      <td>Leistungsangabe</td>
      <td><input name="leistungsangabe" type="text" size="60" value="<?php echo $row_rst2['leistungsangabe']; ?>"></td>
      <td>Angabe auf dem Typenschild</td>
    </tr>
    <tr> 
      <td>Hinweis</td>
      <td><input name="hinweis" type="text" size="60" value="<?php echo $row_rst2['hinweis']; ?>"></td>
      <td>GWL- Druck </td>
    </tr>
    <tr> 
      <td>logo</td>
      <td><input name="logo" type="text" size="60" value="<?php echo $row_rst2['logo']; ?>"></td>
      <td>logodruck (BCK,BLANCOCS)</td>
    </tr>
    <tr> 
      <td><img src="picture/iconFixedprice_16x16.gif" width="16" height="16"> 
        EAN13-Code</td>
      <td><input name="ean13" type="text" size="60" value="<?php echo $row_rst2['ean13']; ?>"></td>
      <td>auf Etikettdruck von EAN13</td>
    </tr>
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
    <tr> 
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#FFFFFF"><strong><em>Zusatzfunktionen Produktionsplanung</em></strong></td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#FFFFFF"><img src="picture/alleberechnungen.png" width="55" height="55"></td>
      <td bgcolor="#FFFFFF"><input name="dublicate" type="submit" id="dublicate2" value="Kopie der Material-Nr. anlegen"></td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <?php if ($totalRows_rstppp == 0) { // Show if recordset empty ?>
    <tr> 
      <td bgcolor="#FFFFFF"><img src="picture/wiederherstellen.png" width="55" height="55"></td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#FFFFFF">Arbeitsplan 
        anlegen f&uuml;r Arbeitsplatz:</td>
      <td colspan="2" bgcolor="#FFFFFF"><select name="selectpp" id="selectpp">
          <option value="0">keine Auswahl</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rstpp['arb_id']?>"><?php echo $row_rstpp['arb_name']?></option>
          <?php
} while ($row_rstpp = mysql_fetch_assoc($rstpp));
  $rows = mysql_num_rows($rstpp);
  if($rows > 0) {
      mysql_data_seek($rstpp, 0);
	  $row_rstpp = mysql_fetch_assoc($rstpp);
  }
?>
        </select>
        und anlegen zum Datum 
        <input name="pdatum" type="text" value="<?php echo date("Y-m-d",time()); ?>"> 
        <input name="insertpp" type="submit" id="insertpp" value="lege Material im Produktionsprogramm an"></td>
    </tr>
    <?php } // Show if recordset empty ?>
    <?php if ($totalRows_rstppp > 0) { // Show if recordset not empty ?>
    <tr> 
      <td bgcolor="#FFFFFF">Produktionsplan 
        Status</td>
      <td bgcolor="#FFFFFF">ist 
        eingeplant.</td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <?php } // Show if recordset not empty ?>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="MM_insert" value="form1">
</form>

  <?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM linkfehlerartikel WHERE linkfehlerartikel.artikelid = '$url_artikelid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM fehler WHERE fehler.lokz  =0 ORDER BY fehler.fname";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);


?>
<p><strong><em>Zusatzfunktionen Retourenbearbeitung - Prozess</em></strong></p><hr>
<p><em><strong>Zusatzfunktion Liste m&ouml;glicher Bauteilfehler </strong></em></p>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <a href="la23.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"> 
  </a><a href="la23.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"> 
  <select name="select2">
    <?php
do {  
?>
    <option value="<?php echo $row_rst5['fid']?>"<?php if (!(strcmp($row_rst5['fid'], $row_rst2['fid']))) {echo "SELECTED";} ?>><?php echo $row_rst5['fname']?></option>
    <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
  </select>
  </a><a href="la23.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"> 
  <input name="hartikelid" type="hidden" id="hartikelid" value="<?php echo $url_artikelid; ?>">
  </a> 
  <input name="zuordnen" type="submit" id="zuordnen" value="Fehler zum Material zuordnen">
  <a href="la23.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"> 
  </a> 
  <input type="hidden" name="MM_insert" value="form3">
</form>
<p>

</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="830" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="434"><em><strong>Bezeichnung</strong></em></td>
    <td width="73">ID</td>
    <td width="33">lokz</td>
    <td width="290"><div align="right"></div></td>
  </tr>
  <?php do { ?>
  <form name="form6" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <select name="select3">
          <?php
do {  
?>
          <option value="<?php echo $row_rst5['fid']?>"<?php if (!(strcmp($row_rst5['fid'], $row_rst2['fid']))) {echo "SELECTED";} ?>><?php echo $row_rst5['fname']?></option>
          <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
        </select></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['lfaid']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="entfernen" type="submit" id="entfernen" value="l&ouml;schen">
          <input name="hlfaid" type="hidden" id="hlfaid" value="<?php echo $row_rst2['lfaid']; ?>">
          <input name="hartikelid" type="hidden" id="hurl_artikelid" value="<?php echo $url_artikelid; ?>">
        </div></td>
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Zuordnung angelegt.</font></p>
<?php } // Show if recordset empty ?>
<p><a href="la23.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $url_artikelid; ?>" target="_blank"> 
  neue Fehler anlegen</a></p>
<p><strong><em>Zusatzfunktionen Qualit&auml;tssicherung - Pr&uuml;fung, WE, Fertigmeldung</em></strong></p>
<p>&nbsp;</p>
<p><em></em></p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst5);

mysql_free_result($rstpp);

mysql_free_result($rstppp);

?>
</p>

  <?php include("footer.tpl.php"); ?>
