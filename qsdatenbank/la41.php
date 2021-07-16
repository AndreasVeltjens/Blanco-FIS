<?php require_once('Connections/qsdatenbank.php'); ?>
<?php function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
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
   $la = "la41";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($_POST["cancel"])) ) {
	if (isset($goback) && (strlen($goback)>0) ) {$GoTo = $goback."?url_user=".$row_rst1['id'];}
	else{
	$GoTo = "la4.php?url_user=".$row_rst1['id'];
	}
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($_POST["cancel2"])) or (isset($_POST["save"])) or (isset($_POST["save1"])) or (isset($_POST["save2"])) or (isset($_POST["save3"])) or (isset($_POST["save4"])) or (isset($_POST["save5"])) or  (isset($_POST["save6"] )) or  (isset($_POST["save7"] )) or  (isset($_POST["save8"] )) or  (isset($_POST["save9"] )) ) {
if ($row_rst4['fm1']==""){$_POST['eingangsdatum']==date("Y-m-d",time());}

if ($_POST['fm1plus']==1){ $_POST['fm1']=2;}

if ($_POST['hurl_rows_rst6']==0){$_POST['fm1']=0;}


  $updateSQL = sprintf("UPDATE fehlermeldungen SET 
  fm1=%s, materialnummer=%s, reklamenge=%s, lokz=%s, 
  fm1user=%s,  fm1notes=%s, fm11notes=%s, fm12notes=%s, fm0=%s, 
  fmnotes=%s,  intern=%s, gewichtvorher=%s WHERE fmid=%s",
                       
                       GetSQLValueString($_POST['fm1'],"int"),
                      GetSQLValueString($_POST['materialnummer'],"text"),
					  GetSQLValueString($_POST['reklamenge'],"text"),
					  GetSQLValueString($_POST['lokz'],"int"),
					  
                       GetSQLValueString($_POST['hurl_user'], "int"),
                       GetSQLValueString($_POST['debitor'], "text"),
					   GetSQLValueString($_POST['endkunde'], "text"),
					   GetSQLValueString($_POST['kommision'], "text"),
					   
                       GetSQLValueString($_POST['fm0'],"int"),
                       GetSQLValueString($_POST['textarea'], "text"),
					   GetSQLValueString($_POST['intern'], "text"),
					   GetSQLValueString(str_replace(",",".",$_POST['gewichtvorher']), "text"),
					   GetSQLValueString($_POST['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
	
	/* bei oldset werden Daten in separates Feld geschrieben */
	/* oldset =0 Fehleraufnahme abgeschlossen, und fm=0 */
	if (($_POST["oldset"]=="0")) {
  $updateSQL = sprintf("UPDATE fehlermeldungen SET  fm1text=%s, fm1bnotes=%s WHERE fmid=%s",

                       GetSQLValueString($_POST['fm3user'],"text")." - ".GetSQLValueString($_POST['eingangsdatum'], "text"),
                       GetSQLValueString($_POST['textarea'], "text"),
                       GetSQLValueString($_POST['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

/* ENDE oldset */

		if (isset($goback) && (strlen($goback)>0) ){$updateGoTo = $goback."?url_user=".$row_rst1['id'];}
		else{
		if (isset($_POST["save"])){
		if (isset($qmb)){$updateGoTo = "qmb4.php";}else{$updateGoTo = "la4.php";}
		}
		if (isset($_POST["save2"])){$updateGoTo = "la42.php";}
		if (isset($_POST["save3"])){$updateGoTo = "la43.php";}
		if (isset($_POST["save4"])){$updateGoTo = "la44.php";}
		if (isset($_POST["save5"])){$updateGoTo = "la45.php";}
		if (isset($_POST["save6"])){$updateGoTo = "la46.php";}
		if (isset($_POST["save7"])){$updateGoTo = "la47.php";}
		if (isset($_POST["save8"])){$updateGoTo = "la48.php";}
		if (isset($_POST["save9"])){$updateGoTo = "la49.php";}
		if (isset($_POST["cancel2"])){$updateGoTo = "la402.php";}
		}
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST['hurl_lffid'])) && ($_POST['hurl_lffid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM linkfehlerfehlermeldung WHERE lffid=%s",
                       GetSQLValueString($_POST['hurl_lffid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
}



if ((isset($_POST["add"]))) {
  $insertSQL = sprintf("INSERT INTO linkfehlerfehlermeldung (fmid, fid, lffuser) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['hurl_fmid'], "int"),
                       GetSQLValueString($_POST['hurl_fid'], "int"),
                       GetSQLValueString($_POST['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}





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
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<link href="fis.css" rel="stylesheet" type="text/css">



<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?> 
        - Schritt 3 von 3</strong></td>
    </tr>
    <tr> 
      <td colspan="3"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="152">Aktionen W&auml;hlen:</td>
      <td width="241"> <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> </td>
      <td width="337"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          <input name="save" type="submit" id="save" value="Speichern und zur Liste">
        </div></td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"> <?php  include("la4.status.php");?> </td>
    </tr>
  </table>
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr bgcolor="#FFFFFF"> 
      <td height="24">Materialnummer</td>
      <td><input name="materialnummer" type="text" id="materialnummer" value="<?php echo $row_rst4['materialnummer']; ?>" size="20" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="24">Reklamierte Menge</td>
      <td><input name="reklamenge" type="text" id="reklamenge" value="<?php echo $row_rst4['reklamenge']; ?>" size="12" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="245" height="24"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        Debitor /H&auml;ndler/ Endkunde</td>
      <td width="468"> <input name="debitor" type="text" id="debitor" value="<?php echo $row_rst4['fm1notes']; ?>" size="50" maxlength="255"></td>
      <td width="237"><input name="fm3user" type="hidden" id="fm3user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_rows_rst6" type="hidden" id="hurl_rows_rst6" value="<?php echo $totalRows_rst6; ?>"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><img src="picture/versandlabel.jpg" width="19" height="20">Verlabel 
        (GLS-Nr.)/FAUF-Nr.</td>
      <td> <input name="endkunde" type="text" id="endkunde" value="<?php echo $row_rst4['fm11notes']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>Kommision</td>
      <td><input name="kommision" type="text" id="kommision" value="<?php echo $row_rst4['fm12notes']; ?>" size="50" maxlength="255"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Gewicht des Ger&auml;tes</td>
      <td><input name="gewichtvorher" type="text" id="gewichtvorher" value="<?php echo $row_rst4['gewichtvorher']; ?>" size="10" maxlength="255">
        kg </td>
      <td><div align="right"><a href="pdfetikett1.php?url_fmid=<?php echo $row_rst4['fmid']; ?>" target="_blank"><img src="picture/printetikett.png" width="120" height="16" border="0"></a></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3"><hr></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><p><img src="picture/feed.png" width="16" height="16"> Zustand des Ger&auml;tes/Verpackung 
          / </p>
        <p>Ger&auml;t vollst&auml;ndig (oder nur Deckel oder nur Grundk&ouml;rper, 
          mit Kabel ?)</p></td>
      <td colspan="2"><textarea name="intern" cols="70" rows="5" id="intern"><?php echo $row_rst4['intern']; ?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/pickreturn.gif" alt="Retoure ist angelegt , Wareneingang noch nicht erfolgt." width="16" height="16"></font> 
        Retoure aus R&uuml;ckholungsliste entfernen</td>
      <td> <input <?php if (!(strcmp($row_rst4['fm0'],1))) {echo "checked";} ?> name="fm0" type="checkbox" id="fm02" value="1"> 
        &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td> <div align="right"> 
          <input name="oldset" type="hidden" id="oldset" value="<?php echo $row_rst4['fm1']; ?>">
          <input <?php if ($row_rst4['fm1']>=1) {echo "checked";} ?> name="fm1" type="checkbox" id="fm1" value="1">
          Fehleraufnahme abgeschlossen <br>
		   <input <?php if ($row_rst4['fm1']>1) {echo "checked";} ?> name="fm1plus" type="checkbox" id="fm12" value="1">
          Trocknung  notwendig 
          <input name="hurl_fmid" type="hidden" id="hurl_fmid3" value="<?php echo $row_rst4['fmid']; ?>">
		  <input name="goback" type="hidden" id="goback" value="<?php echo $goback ?>">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19"><img src="picture/error.gif" width="16" height="16"> L&ouml;schkennzeichen</td>
      <td colspan="2"><input <?php if (!(strcmp($row_rst4['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz2" value="1"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19"><p> 
          <input name="imageField" type="image" src="picture/92d999eafe.gif" width="30" height="30" border="0">
          Fehlerbeschreibung</p>
        <p> 
          <input name="imageField2" type="image" src="picture/0d1e0a6a3e.gif" width="30" height="31" border="0">
          Reparaturvorschlag</p></td>
      <td colspan="2"><textarea name="textarea" cols="70" rows="5"><?php echo $row_rst4['fmnotes']; ?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="19"><input name="imageField3" type="image" src="picture/eo.png" width="15" height="15" border="0">
        Schutzleiterwiderstand</td>
      <td colspan="2"><?php echo $row_rst4['schutzleiter']; ?> Ohm</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
  <hr>
</form>
<?php if ($totalRows_rst5 == 0) { // Show if recordset empty ?>
<font color="#FF0000">Fehlerdefinition f&uuml;r diesen Artikel gespeichert. Fehlerdefinition 
in Modul la24 pflegen.</font><br>
<a href="la23.php?url_user=<?php echo $url_user ?>&url_artikelid=<?php echo $url_artikelid ?>" target="_blank">&ouml;ffnen</a> 
<?php } // Show if recordset empty ?>

<?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
<em>m&ouml;gliche Fehler</em><br>
<table width="950" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<table width="365" border="0" align="left" cellpadding="0" cellspacing="0">
<?php 
$i=0;

do { 
$zeilensprung=round($totalRows_rst5/2,0);
$i=$i+1;
?>
<?php if ($i==$zeilensprung+1){ ?>
</tr>
</table>
<table width="365" border="0" align="right" cellpadding="0" cellspacing="0">
<?php }?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
  <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
      <td    bgcolor="#EEEEEE" nowrap="nowrap" width="10"> 
        <input name="add" type="submit" id="add3" value="Auswahl"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap" width="260">
	  <?php if ($row_rst5['fid']==21){ ?>
        <img src="picture/fertig.gif" width="15" height="15">
        <?php } ?>
	  
	  <?php echo $row_rst5['fkurz']; ?>- <?php echo $row_rst5['fname']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap" width="5"><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>"> 
        <input name="hurl_fid" type="hidden" id="hurl_fid" value="<?php echo $row_rst5['fid']; ?>"> 
        <input type="hidden" name="MM_insert" value="form2"> 
    </form>
    <td width="3"></td>
  </tr>

<?php } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
</table>
</td>
  </tr>
</table>



<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
<font color="#FF0000">noch keine Fehler gespeichert.</font> 
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
<br>
<em>aufgetretene Fehler (bei mehrmaligem Auftreten des Fehler:entspricht Anzahl 
des gleichen Fehlers) <a href="\\lznt01\werklz\Elektroabteilung\Aufnahme  Foto" target="_blank">Bilder 
ansehen</a></em> 
<table width="950" border="0" cellpadding="0" cellspacing="0">
  <?php do { ?>
  <form name="form3" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td  bgcolor="#EEEEEE" nowrap="nowrap" width="115"><img src="picture/b_drop.png" width="16" height="16"> 
        <input name="delete" type="submit" id="delete" value="L&ouml;schen"></td>
      <td  bgcolor="#EEEEEE" nowrap="nowrap" width="95"><?php echo $row_rst6['fkurz']; ?></td>
      <td   bgcolor="#EEEEEE" nowrap="nowrap" width="520"> 
        <?php echo $row_rst6['fname']; ?> <input name="hurl_fid" type="hidden" id="hurl_fid" value="<?php echo $row_rst6['fid']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>"> 
        <input name="hurl_lffid" type="hidden" id="hurl_lffid" value="<?php echo $row_rst6['lffid']; ?>">
        <?php if ($row_rst6['fid']==21){ ?>
        <img src="picture/Bild.gif" width="22" height="9"> <img src="picture/s_host.png"> 
        <a href="la44.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst4['fmid']; ?>">VDE-Pr&uuml;fung 
        erstellen</a> <img src="picture/fertig.gif" width="15" height="15">
        <?php } ?>
      </td>
    </tr>
  </form>
  <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
</table>
<?php } // Show if recordset not empty ?>
<br>
<?php include("la4.picture.php");?>
<p>
<br>
<?php include("la4.rstuserst.php");?>
<p>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rsttyp);
?>
</p>
  <?php include("footer.tpl.php"); ?>
