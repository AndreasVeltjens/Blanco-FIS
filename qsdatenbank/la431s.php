<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la431s";
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

if (($HTTP_POST_VARS["hurl_fmid"]>0) && (isset($HTTP_POST_VARS["save2"])) ) {
  $updateGoTo = "la43.php?url_user=$url_user&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&goback=la431s.php&editabrechnung=1";
  header(sprintf("Location: %s", $updateGoTo)); 
}

if ((isset($HTTP_POST_VARS["save"]))) {
		if ( $HTTP_POST_VARS['sappcna']>=900000){
		  $updateSQL = sprintf("UPDATE fehlermeldungen SET sappcna=%s WHERE fmid=%s",
							   GetSQLValueString($HTTP_POST_VARS['sappcna'], "int"),
							   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
		  $oktxt = "PCNA-Auftragsnummer ".$HTTP_POST_VARS['sappcna']." f&uuml;r FM ".$HTTP_POST_VARS['hurl_fmid']." angelegt.";
		}else{ /* else  */
		$errtxt = "PCNA-Auftragsnummer nicht g&uuml;ltig. Bitte erneut eingeben!";
		}
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if (!isset($HTTP_POST_VARS['fmart'])){$HTTP_POST_VARS['fmart']=1;}

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmart='$HTTP_POST_VARS[fmart]' AND(
(fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1 AND fehlermeldungen.sappcna=0 and fehlermeldungen.vkz =0 ) or 
(fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1 AND fehlermeldungen.sappcna=0 and fehlermeldungen.vkz =3) )
ORDER BY fehlermeldungen.fmartikelid, fehlermeldungen.fmid";
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

if (isset($HTTP_GET_VARS['totalRows_rst2'])) {
  $totalRows_rst2 = $HTTP_GET_VARS['totalRows_rst2'];
} else {
  $all_rst2 = mysql_query($query_rst2);
  $totalRows_rst2 = mysql_num_rows($all_rst2);
}
$totalPages_rst2 = ceil($totalRows_rst2/$maxRows_rst2)-1;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer, artikeldaten.repvorgabezeit FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

$queryString_rst2 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst2") == false && 
        stristr($param, "totalRows_rst2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst2 = "&" . implode("&", $newParams);
  }
}
$queryString_rst2 = sprintf("&totalRows_rst2=%d%s", $totalRows_rst2, $queryString_rst2);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la43.php?url_user=".$row_rst1['id']."&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&goback=la43s.php";
  header(sprintf("Location: %s", $updateGoTo));
}

/* lege alle FAUF-Repararturaufträge automatisch an */
if ((isset($HTTP_POST_VARS["saveall"]))) {

do {
		$hartikelid=$row_rst2['fmartikelid'];
 
 		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rsttyp = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$hartikelid' ";
		$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
		$row_rsttyp = mysql_fetch_assoc($rsttyp);
		$totalRows_rsttyp = mysql_num_rows($rsttyp);
 		
		$repvorgabezeit=$row_rsttyp['repvorgabezeit'];
		$kostenstelle= $row_rst2['sapkst'];
		
		if ($repvorgabezeit>0){ /* suche nach aktueller abrechnungsvorschrift */
					mysql_select_db($database_qsdatenbank, $qsdatenbank);
					$query_rst_kst = "SELECT *  FROM abrechnungsvorschriften WHERE abrechnungsvorschriften.kst_datum_ab <= date(now( ) ) AND abrechnungsvorschriften.kst_datum_bis > date( now( ) )  AND abrechnungsvorschriften.lokz =0 AND kst_nummer='$kostenstelle' AND kst_vorgabezeit='$repvorgabezeit' LIMIT 0 , 30 ";
					$rst_kst = mysql_query($query_rst_kst, $qsdatenbank) or die(mysql_error());
					$row_rst_kst = mysql_fetch_assoc($rst_kst);
					$totalRows_rst_kst = mysql_num_rows($rst_kst);
					
					$pcnaauftrag=$row_rst_kst['kst_auftrag'];
						
		}
		
		
 		if ( (($pcnaauftrag>=900000) && ($row_rst2['sappcna']==0)) ){
					  $updateSQL = sprintf("UPDATE fehlermeldungen SET sappcna=%s WHERE fmid=%s",
										   GetSQLValueString($pcnaauftrag, "int"),
										   GetSQLValueString($row_rst2['fmid'], "int"));
					
					  mysql_select_db($database_qsdatenbank, $qsdatenbank);
					  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
					  $errtxt .= "PCNA-Auftragsnummer ".$pcnaauftrag." f&uuml;r FM ".$row_rst2['fmid']." angelegt.";
		}else{ /* else  */
					$errtxt .= "PCNA-Auftragsnummer -$pcnaauftrag- nicht gefunden.<br>";
					$errtxt .= "Abrechnungsregeln und Zeiträume in der Tabelle ergänzen.<br>";
					 $errtxt .= "<br>PCNA-fmid ".$row_rst2['fmid'];
					 $errtxt .= "<br>PCNA-kst ".$row_rst2['sapkst'];
					 $errtxt .= "<br>PCNA-artikelid ".$hartikelid;
					 $errtxt .= "<br>PCNA-repvorgabezeit ".$row_rsttyp['repvorgabezeit'];
					  $errtxt .= "<br>PCNA-pcnaauftrag ".$pcnaauftrag;
					  $errtxt .= "<br>PCNA-Kostenstelle ".$kostenstelle;
					 $errtxt .= "<br>".$query_rst_kst;
 		}
 
 
  } while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
}
} /* ende saveall */

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmart='$HTTP_POST_VARS[fmart]' AND(
(fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1 AND fehlermeldungen.sappcna=0 and fehlermeldungen.vkz =0 ) or 
(fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1 AND fehlermeldungen.sappcna=0 and fehlermeldungen.vkz =3) )
ORDER BY fehlermeldungen.fmartikelid, fehlermeldungen.fmid";
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

if (isset($HTTP_GET_VARS['totalRows_rst2'])) {
  $totalRows_rst2 = $HTTP_GET_VARS['totalRows_rst2'];
} else {
  $all_rst2 = mysql_query($query_rst2);
  $totalRows_rst2 = mysql_num_rows($all_rst2);
}
$totalPages_rst2 = ceil($totalRows_rst2/$maxRows_rst2)-1;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer, artikeldaten.repvorgabezeit FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

$queryString_rst2 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst2") == false && 
        stristr($param, "totalRows_rst2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst2 = "&" . implode("&", $newParams);
  }
}
$queryString_rst2 = sprintf("&totalRows_rst2=%d%s", $totalRows_rst2, $queryString_rst2);


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        </font></strong> 
        <?php } // Show if recordset empty ?> <strong><font color="#FF0000"><?php echo $errtxt ?></font></strong> 
        <strong><font color="#00CC00"><?php echo $oktxt ?></font></strong><br> 
      </td>
    </tr>
    <tr> 
      <td width="141">Aktionen W&auml;hlen:</td>
      <td width="135"> <img src="picture/error.gif" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="370"> <div align="right">
          <input name="saveall" type="submit" id="saveall" value="alle automatisch anlegen">
          <img src="picture/sap.png" width="16" height="16"> PCNA-Auftr&auml;ge 
          anlegen 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td><div align="right">zeige 
          <select name="fmart">
            <option value="1" <?php if (!(strcmp(1, $HTTP_POST_VARS['fmart']))) {echo "SELECTED";} ?>>Debitoren 
            Reklamation</option>
            <option value="2" <?php if (!(strcmp(2, $HTTP_POST_VARS['fmart']))) {echo "SELECTED";} ?>>Kreditoren 
            Reklamation</option>
            <option value="3" <?php if (!(strcmp(3, $HTTP_POST_VARS['fmart']))) {echo "SELECTED";} ?>>Eigenfertigte 
            Teile</option>
          </select>
          <input name="Ansicht aktualisieren" type="submit" id="Ansicht aktualisieren" value="Ansicht aktualisieren">
          <img src="picture/help_24x24.png" alt="Hilfe anzeigen" width="24" height="24" border="0"></div></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>

  
    
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>

  <script language="JavaScript" type="text/JavaScript">
function sf(){document.form2.suchtext.focus()}
</script> 
<table width="980" border="0" cellspacing="0" cellpadding="4">
  <tr bgcolor="#CCCCFF"> 
    <td colspan="3"><strong><em>PCNA-Auftragseingabe</em></strong></td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td width="163">Fehlermeldenummerbez.</td>
    <td width="495"><input name="suchtext" type="text" id="suchtext" value="<?php echo $row_rst2['fmid']."-"; 
do {  
if (!(strcmp($row_rsttyp['artikelid'], $row_rst2['fmartikelid']))) {echo $row_rsttyp['Bezeichnung']."-";
	$vorgabezeit=$row_rsttyp['repvorgabezeit'];	
}
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?><?php echo $row_rst2['fmsn']."-"; ?><?php echo $row_rst2['sapsd']."-"; ?><?php echo htmlentities(utf8_decode(substr($row_rst2['fm1notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm11notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm12notes'],0,25)));?>" size="120" maxlength="120"></td>
    <td width="48">&nbsp;</td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Kostenstelle</td>
    <td><input name="kostenstelle" type="text" id="kostenstelle" value="<?php echo $row_rst2['sapkst']; ?>">
        <font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if ($row_rst2['sapkst']=="61612"){ ?>
        <img src="picture/euro.gif" width="20" height="20"> 
		
		<?php if ($vorgabezeit==100 ){ ?>
		
		9011818<?php }else { ?> 9011820 <?php }?>
        <?php } else { ?>
		<?php if ($vorgabezeit==100 ){ ?>
        9011817 <?php }else { ?> 9011819 <?php }}?></font> </td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Arbeitsplatz</td>
    <td> <input name="arbeitsplatz" type="text" id="arbeitsplatz" value="krep"></td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>Vorgabezeit</td>
      <td> <input name="vorgabezeit" type="text" id="vorgabezeit" value="<?php echo $vorgabezeit; ?>">
        DMI</td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>&nbsp;</td>
      <td><font size="1" face="Arial, Helvetica, sans-serif">
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst2['fmid']; ?>">
        </font></td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#CCCCFF"> 
    <td>R&uuml;ckmeldung PCNA-Nr.</td>
    <td><input name="sappcna" type="text" id="sappcna" value="<?php echo $row_rst2['sappcna']; ?>"> <input name="save" type="submit" id="save" value="PCNA-Auftrag speichern">
        <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; </font></td>
    <td>&nbsp;</td>
  </tr>
</table>

</form>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>"><br>
<br>
<br>
<br>
First</a> <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
- Retourenabwicklung: <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?> 
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Plan</font></div></td>
    <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Fehler-<br>
        aufnahme</font></div></td>
    <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Kosten</font></div></td>
    <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Reparatur-<br>
        freigabe</font></div></td>
    <td><div align="center"><font size="1">SAP</font></div></td>
    <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">End-<br>
        pr&uuml;fung</font></div></td>
    <td><font size="1" face="Arial, Helvetica, sans-serif">Kommision</font></td>
    <td><div align="right"><font size="1" face="Arial, Helvetica, sans-serif">erledigt</font></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td width="44">&nbsp;</td>
    <td width="44"><strong>Bezeichnung</strong></td>
    <td width="147">Kostenstelle - Vorlageauftrag</td>
    <td width="45"><div align="center"><img src="picture/b_newdb.png" width="16" height="16"></div></td>
    <td width="45"><p align="center"><img src="picture/b_tblops.png" width="16" height="16"></p></td>
    <td width="45"><div align="center"><img src="picture/money.gif" width="15" height="15"></div></td>
    <td width="45"><div align="center"><img src="picture/b_insrow.png" width="16" height="16"></div></td>
    <td width="45"><div align="center"><img src="picture/sap.png" width="16" height="16"></div></td>
    <td width="45"><div align="center"><img src="picture/s_host.png" width="16" height="16"></div></td>
    <td width="45"><div align="center"><img src="picture/s_db.png" width="16" height="16"></div></td>
    <td width="45"><div align="center"><img src="picture/fertig.gif" width="15" height="15"></div></td>
    <td width="70">PCNA-Nr. eingeben</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap">	
        <?php if ($row_rst2['fmdatum']>=date("Y-m-d H:i:s",time()-100)) {echo "<img src=\"picture/iconAuction_16x16.gif\" alt=\"Achtung. Dieser Datensatz innerhalb der letzten 5 min von einem Benutzer bearbeitet.\">";}?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
          <?php echo $row_rst2['fmid']; ?> 
          <?php
do {  
?>
          <?php if (!(strcmp($row_rsttyp['artikelid'], $row_rst2['fmartikelid']))) {echo $row_rsttyp['Bezeichnung'];
																					$vorgabezeit=$row_rsttyp['repvorgabezeit'];
		  		} ?>
          <?php
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?>
          -<?php echo $row_rst2['fmsn']; ?>-<?php echo $row_rst2['sapsd']; ?></font><font size="1">-<?php echo htmlentities(utf8_decode(substr($row_rst2['fm1notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm11notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm12notes'],0,25)));?></font><font size="1" face="Arial, Helvetica, sans-serif"> 
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['sapkst']; ?> 
       
		<?php if ($row_rst2['sapkst']=="61612"){ ?>
        <img src="picture/euro.gif" width="20" height="20"> 
		
		
        
		<?php if ($vorgabezeit==100 ){ ?>
		
		9011818<?php }else { ?> 9011820 <?php }?>
        <?php } else { ?>
		<?php if ($vorgabezeit==100 ){ ?>
        9011817 <?php }else { ?> 9011819 <?php }}?>
		
		
		
    
        </font> <font size="1" face="Arial, Helvetica, sans-serif"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst2['fmid']; ?>">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst2['fm0'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="geplante Retoure noch nicht aufgenommen" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEcc" nowrap="nowrap"><div align="center"> 
          <font size="1" face="Arial, Helvetica, sans-serif"> <img src="picture/<?php if (!(strcmp($row_rst2['fm1'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Fehleraufnahme" width="15" height="15"></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/<?php if (!(strcmp($row_rst2['fm2'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kostenkl&auml;rung" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEcc" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <?php if  ($row_rst2['vkz']>0){ 
					  if ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
          <img src="picture/<?php if (!(strcmp($row_rst2['fm3'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparaturfreigabe" width="15" height="15"> 
          <?php }?>
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
          <?php if  ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
          <img src="picture/<?php if ($row_rst2['sappcna']>1) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="SAP Fertigungsauftr&auml;ge als PCNA angelegt" width="15" height="15"> 
          <?php }?>
          </font></div></td>
      <td    bgcolor="#EEEEcc" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <?php if  ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
          <img src="picture/<?php if (!(strcmp($row_rst2['fm4'],1))) {echo "st1.gif";}else{
		  
		  $kostenklarung = $row_rst2["fm2variante"];
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst7 = "SELECT entscheidung.entid, entscheidung.entname, entscheidung.entmemo, entscheidung.gvwl FROM entscheidung WHERE entscheidung.entid=$kostenklarung";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);  
		  
		  
		  if ($row_rst7['gvwl']==0){
		  echo "st2.gif";
		  } else {
		  echo "st3.gif";
		  } 
		  }
		  ?>" alt="Reparatur + VDE-Pr&uuml;fung" width="15" height="15"> 
          <?php }?>
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <?php if  ($row_rst2['vkz']>0){ 
					  if ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; 
						}else{ 
						echo "<img src=\"picture/icon_gr_servicegeraete.png\" width=\"15\" height=\"15\">";
						}
						  

			  }else{ ?>
          <img src="picture/<?php if (!(strcmp($row_rst2['fm5'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kommissionierstatus" width="15" height="15"> 
          <?php }?>
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"> 
          <font size="1" face="Arial, Helvetica, sans-serif"> <img src="picture/<?php if (!(strcmp($row_rst2['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen" width="15" height="15"></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <font size="1" face="Arial, Helvetica, sans-serif"> 
          <input name="sappcna" type="text" id="sappcna" value="<?php echo $row_rst2['sappcna']; ?>" size="10">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="save" type="submit" id="save" value="speichern">
          <input name="save2" type="submit" id="save2" value="automatisch">
          </font></div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form1">
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<?php } // Show if recordset not empty ?>
<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
  - Retourenabwicklung: <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?> 
  <font size="2" face="Arial, Helvetica, sans-serif"> </font> </p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Retouren zur SAP-Fertigungsauftragsanlage Auftragsart: PCNA angelegt.</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rsttyp);
?>
</p>

  <?php include("footer.tpl.php"); ?>
