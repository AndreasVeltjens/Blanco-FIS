<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la471s";
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



if ((isset($HTTP_POST_VARS["save"])) && ($HTTP_POST_VARS['kst_auftrag']>0) ) {
	$kst_auftrag=$HTTP_POST_VARS['kst_auftrag'];
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm7=%s WHERE fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm4=1 AND fehlermeldungen.sappcna<>0 and fehlermeldungen.fm7 < 2  and fehlermeldungen.sappcna='$kst_auftrag'",
                       GetSQLValueString($HTTP_POST_VARS['selectstatus'], "int"));
                  

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  $oktxt.="Kostensammler Auftrag $kst_auftrag mit allen offenen Rückmeldungen verbucht - Status $HTTP_POST_VARS[selectstatus]. ";
}



if (($HTTP_POST_VARS["hurl_fmid"]>0) && (isset($HTTP_POST_VARS["editfm"])) ) {
  $updateGoTo = "la43.php?url_user=$url_user&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&goback=la471s.php&editabrechnung=1";
  header(sprintf("Location: %s", $updateGoTo)); 
}

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm7=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fm7'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

 $suchtxt=""; 
 $suchtxt1=" AND fehlermeldungen.fm4=1 "; 
if ((isset($HTTP_POST_VARS['suchen'])) && ($HTTP_POST_VARS['fmidsuche']>0)){
	$suchtxt="AND fehlermeldungen.fmid='".$HTTP_POST_VARS['fmidsuche']."' ";
	}
	
if ((isset($HTTP_POST_VARS['suchen'])) && ($HTTP_POST_VARS['checkbox']>0)){
	$suchtxt1="";
	}

if ((isset($HTTP_POST_VARS['suchen'])) && ($HTTP_POST_VARS['fauf']>0)){
	$suchtxt=$suchtxt."AND fehlermeldungen.sappcna=".$HTTP_POST_VARS['fauf'];
	}else{
	
	if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;
	
	
}

$maxRows_rst2 = 100;
$pageNum_rst2 = 0;

if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmsn, fehlermeldungen.fmdatum, fehlermeldungen.fmartikelid, fehlermeldungen.fmfd, fehlermeldungen.fm1, fehlermeldungen.fm2, fehlermeldungen.fm3, fehlermeldungen.fm4, fehlermeldungen.fm5, fehlermeldungen.fm0, fehlermeldungen.fm6, fehlermeldungen.fm7, fehlermeldungen.fm1notes, fehlermeldungen.sappcna, fehlermeldungen.sapsd, fehlermeldungen.sapkst, fehlermeldungen.fm4notes, fehlermeldungen.vip, fehlermeldungen.vkz, fehlermeldungen.fm7, fehlermeldungen.fm4user, fehlermeldungen.fm4datum FROM fehlermeldungen WHERE fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1  $suchtxt1 AND fehlermeldungen.sappcna<>0 $suchtxt and fehlermeldungen.fm7 < 2 ORDER BY fehlermeldungen.fmid";
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
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.username, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_kst = "SELECT *  FROM abrechnungsvorschriften WHERE abrechnungsvorschriften.kst_datum_ab <= date(now( ) ) AND abrechnungsvorschriften.kst_datum_bis > date( now( ) )  AND abrechnungsvorschriften.lokz =0 LIMIT 0 , 30 ";
$rst_kst = mysql_query($query_rst_kst, $qsdatenbank) or die(mysql_error());
$row_rst_kst = mysql_fetch_assoc($rst_kst);
$totalRows_rst_kst = mysql_num_rows($rst_kst);

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
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}
if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la43.php?url_user=".$row_rst1['id']."&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&goback=la471s.php";
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<table width="1000" border="0" cellspacing="0" cellpadding="0">
  <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <strong><font color="#00CC00"><?php echo $oktxt ?></font></strong><br>
      </td>
    </tr>
    <tr> 
      <td width="165">Aktionen W&auml;hlen:</td>
      <td width="152"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="683"> <div align="right"><a href="sap/openco11.sap" target="_blank"><img src="picture/add.gif" width="15" height="14" border="0"></a><a href="sap/opencoois.sap">Liste 
          PCNA Auftr&auml;ge</a> --- <a href="sap/openco11.sap" target="_blank"> 
          <img src="picture/add.gif" width="15" height="14" border="0">&ouml;ffne 
          PCNA R&uuml;ckmeldung</a> <img src="sap/SAPLogo.gif" width="73" height="36"><a href="../documents/FIS%20Hilfe/hla471s.pdf" target="_blank"><img src="picture/help_24x24.png" alt="Hilfe anzeigen" width="24" height="24" border="0"></a> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>Fehlermeldenummer:</td>
      <td><input name="fmidsuche" type="text" id="fmidsuche" value="<?php echo $HTTP_POST_VARS['fmidsuche'] ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Fertigungsauftragsnummer:</td>
      <td> <input name="fauf" type="text" id="fauf" value="<?php echo $HTTP_POST_VARS['fauf'] ?>">
      </td>
      <td><div align="right">
          <input <?php if (!(strcmp($HTTP_POST_VARS['checkbox'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1">
          zeige alle aktiven PCNA-Auftr&auml;ge im FIS 
          <input name="suchen" type="submit" id="suchen" value="suchen">
        </div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </form>
</table>
  
  
<?php if ($totalRows_rst_kst > 0) { // Show if recordset not empty ?>
<table width="1000" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="20"><font size="1"><strong>Auftrags-<br>
      nummer</strong></font></td>
    <td width="20"><font size="1"><strong>Abrechnung auf Kostenstelle</strong></font></td>
    <td><font size="1"><strong>Bezeichnung</strong></font></td>
    <td><font size="1"><strong>Download</strong></font></td>
    <td><div align="center"><strong><font size="1">offner Aufwand</font></strong></div></td>
    <td><font size="1"><strong>Status wechseln</strong></font></td>
    <td><font size="1"><strong>Speichern</strong></font></td>
  </tr>

    <?php do { 
	
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_kstzeit = "SELECT  sum(fm4notes) as summearbeit, count(fmid) as anzahl FROM fehlermeldungen  WHERE fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm4=1 AND fehlermeldungen.sappcna<>0 and fehlermeldungen.fm7 < 2 and  fehlermeldungen.sappcna='$row_rst_kst[kst_auftrag]'";
$rst_kstzeit= mysql_query($query_rst_kstzeit, $qsdatenbank) or die(mysql_error());
$row_rst_kstzeit = mysql_fetch_assoc($rst_kstzeit);
$totalRows_rst_kstzeit = mysql_num_rows($rst_kstzeit);
	
	
	?>  <form name="form3" method="post" action="">
    <tr> 
      <td><img src="picture/delicious.png" alt="Zeitraum gültig von <?php echo $row_rst_kst['kst_datum_ab']; ?> bis <?php echo $row_rst_kst['kst_datum_bis']; ?>" width="16" height="16"> 
        <?php echo $row_rst_kst['kst_auftrag']; ?></td>
      <td><div align="center"><?php echo $row_rst_kst['kst_nummer']; ?></div></td>
      <td><?php echo $row_rst_kst['kst_name']; ?></td>
      <td><a href="getexcelauftrag.php?url_auftrag=<?php echo $row_rst_kst['kst_auftrag']; ?>"><img src="picture/ruckmeldung.png" alt="Download Warenbewegungen " width="100" height="16" border="0"></a></td>
      <td><?php if ($row_rst_kstzeit['summearbeit']>0 or $row_rst_kstzeit['anzahl']>0){?><?php echo round($row_rst_kstzeit['summearbeit']/60,1); ?> Stunden und <?php echo $row_rst_kstzeit['anzahl']; ?> Komponenten <?php }?></td>
      <td><select name="selectstatus" id="selectstatus">
          <option value="0" >noch nicht r&uuml;ckgeldet</option>
          <option value="" >Fehler</option>
          <option value="2" >R&Uuml;CK endr&uuml;ckgemeldet</option>
          <option value="1" >TR&Uuml;CK teilr&uuml;ckgemeldet</option>
          <option value="3" >EKS R&uuml;ckmeldung</option>
        </select></td>
      <td> 
        <input name="kst_auftrag" type="hidden" id="kst_auftrag" value="<?php echo $row_rst_kst['kst_auftrag']; ?>"> 
        <input name="save" type="submit" id="save" value="Speichern"> </td>
    </tr></form>
    <?php } while ($row_rst_kst = mysql_fetch_assoc($rst_kst)); ?>
  
</table>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rst_kst == 0) { // Show if recordset empty ?>
<p><font color="#FF0000"><strong>Bitte Eintr&auml;ge in Tabelle Abrechnungsvorschriften 
  f&uuml;r den neuen Zeitraum anlegen.</strong></font> </p>
<?php } // Show if recordset empty ?>
<p><br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
- Retourenabwicklung: <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?> 
<table width="795" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td width="282"><strong>Bezeichnung</strong></td>
    <td width="98">Kostenstelle-FAUF-Nr.</td>
    <td width="289">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['sappcna']; ?> </font></div></td>
	  <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
          	  <?php if ($row_rst2['fmdatum']>=date("Y-m-d H:i:s",time()-100)) {echo "<img src=\"picture/iconAuction_16x16.gif\" alt=\"Achtung. Dieser Datensatz innerhalb der letzten 5 min von einem Benutzer bearbeitet.\">";}?>
		  <?php echo $row_rst2['fmid']; ?> 
          <?php
do {  
?>
          <?php if (!(strcmp($row_rsttyp['artikelid'], $row_rst2['fmartikelid']))) {echo $row_rsttyp['Bezeichnung'];} ?>
          <?php
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?>
          -<?php echo $row_rst2['fmsn']; ?>-<?php echo $row_rst2['sapsd']; ?> </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['sapkst']; ?> - 
        <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_fmid" type="hidden" id="hurl_fmid2" value="<?php echo $row_rst2['fmid']; ?>">
        </font></td>
		
		
		
		
		
		
		      <td    bgcolor="#EEEEcc" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"> 
          </font></div></td>
		  
	<td    bgcolor="#EEEEcc" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          
          <img src="picture/<?php if (!(strcmp($row_rst2['fm3'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparatur + VDE-Pr&uuml;fung" width="15" height="15"> 
          
          </font></div></td>	  
		  
		  
      <td    bgcolor="#EEEEcc" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <?php if  ($row_rst2['vkz']==1){ echo "<img src=\"picture/b_drop.png\" "; }else{ ?>
          <img src="picture/<?php if (!(strcmp($row_rst2['fm4'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Freigabe vom Kunden" width="15" height="15"> 
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

		
		
		
		
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <font size="1" face="Arial, Helvetica, sans-serif"> <a href="getexcel.php?url_fmid=<?php echo $row_rst2['fmid']; ?>"><img src="picture/ruckmeldung.png" width="100" height="16" border="0"></a> 
        <select name="fm7" id="fm7">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['fm7']))) {echo "SELECTED";} ?>>noch 
          nicht r&uuml;ckgeldet</option>
          <option value="" <?php if (!(strcmp("", $row_rst2['fm7']))) {echo "SELECTED";} ?>>Fehler</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst2['fm7']))) {echo "SELECTED";} ?>>R&Uuml;CK 
          endr&uuml;ckgemeldet</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst2['fm7']))) {echo "SELECTED";} ?>>TR&Uuml;CK 
          teilr&uuml;ckgemeldet</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst2['fm7']))) {echo "SELECTED";} ?>>EKS 
          R&uuml;ckmeldung</option>
        </select>
        <?php echo $row_rst2['fm4notes'];
		  $ruckmeldezeit = $ruckmeldezeit+$row_rst2['fm4notes'];
		  
		  
		  ?> <img src="picture/b_edit.png" width="16" height="16"><?php echo $row_rst2['fm4datum']; ?> <img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst3['id'], $row_rst2['fm4user']))) {echo $row_rst3['name'];}?>
        <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"> 
<?php if ($row_rst2['fm4datum']!="0000-00-00"){?>
        <input name="save" type="submit" id="save" value="speichern">
        <input name="editfm" type="submit" id="editfm" value="&Auml;ndern"> 
        <?php }?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if ((($row_rst2['vip']>=1))) {echo "<img src=\"picture/b_tipp.png\" alt=\"Dringlichkeitskennzeichen. Terminreparatur. Schnelle Reparatur erforderlich.\">" ;}?>
        <?php if (($row_rst2['vip']>=2)) {echo "<img src=\"picture/b_tipp.png\">";}?>
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fmid']; ?> <a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst2['fmid']; ?>"><img src="picture/b_edit.png" alt="bearbeiten" width="16" height="16" border="0"></a></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rsttyp['artikelid'], $row_rst2['fmartikelid']))) {echo $row_rsttyp['Bezeichnung'];} ?></select> 
        <?php
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?>
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fmsn']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo htmlentities(utf8_decode(substr($row_rst2['fm1notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm11notes'],0,25))); echo " / ".htmlentities(utf8_decode(substr($row_rst2['fm12notes'],0,25)));?></font><font size="1" face="Arial, Helvetica, sans-serif"> 
        <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_fmid" type="hidden" id="hurl_fmid2" value="<?php echo $row_rst2['fmid']; ?>">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fmfd']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fm1datum']; ?></font></td>
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
          <img src="picture/<?php if (!(strcmp($row_rst2['fm4'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparatur + VDE-Pr&uuml;fung" width="15" height="15"> 
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
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
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
  - Retourenabwicklung: <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?></p>
<p><strong>Summe R&uuml;ckmeldezeit: <?php echo $ruckmeldezeit; ?>&nbsp; min</strong><font size="2" face="Arial, Helvetica, sans-serif"> 
  </font> </p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Retouren zur SAP-Fertigungsauftragsanlage Auftragsart: PCNA angelegt.</p>
<?php include("la4.legende.php"); ?>

<form name="form11" method="post" action="">
Sammelr&uuml;ckmeldung Kostenstelle 61611 : <a href="getexcel2.php?url_fmid=<?php echo $row_rst2['fmid']; ?>"><img src="picture/ruckmeldung.png" width="100" height="16" border="0"></a> 
  - Status setzen alle 
  <select name="select11" id="select11">
    <option value="0" <?php if (!(strcmp(0, $row_rst2['fm7']))) {echo "SELECTED";} ?>>noch 
    nicht r&uuml;ckgeldet</option>
    <option value="" <?php if (!(strcmp("", $row_rst2['fm7']))) {echo "SELECTED";} ?>>Fehler</option>
    <option value="2" <?php if (!(strcmp(2, $row_rst2['fm7']))) {echo "SELECTED";} ?>>R&Uuml;CK 
    endr&uuml;ckgemeldet</option>
    <option value="1" <?php if (!(strcmp(1, $row_rst2['fm7']))) {echo "SELECTED";} ?>>TR&Uuml;CK 
    teilr&uuml;ckgemeldet</option>
    <option value="3" <?php if (!(strcmp(3, $row_rst2['fm7']))) {echo "SELECTED";} ?>>EKS 
    R&uuml;ckmeldung</option>
  </select>
  <input name="Submit1" type="submit" id="Submit1" value="Kostenstelle 61611">
</p>

</form>
<form name="form12" method="post" action="">
<p>Sammelr&uuml;ckmeldung Kostenstelle 61612 : <a href="getexcel3.php?url_fmid=<?php echo $row_rst2['fmid']; ?>"><img src="picture/ruckmeldung.png" width="100" height="16" border="0"></a><font size="2" face="Arial, Helvetica, sans-serif"> 
  - Status setzen alle 
  <select name="select12" id="select12">
    <option value="0" <?php if (!(strcmp(0, $row_rst2['fm7']))) {echo "SELECTED";} ?>>noch 
    nicht r&uuml;ckgeldet</option>
    <option value="" <?php if (!(strcmp("", $row_rst2['fm7']))) {echo "SELECTED";} ?>>Fehler</option>
    <option value="2" <?php if (!(strcmp(2, $row_rst2['fm7']))) {echo "SELECTED";} ?>>R&Uuml;CK 
    endr&uuml;ckgemeldet</option>
    <option value="1" <?php if (!(strcmp(1, $row_rst2['fm7']))) {echo "SELECTED";} ?>>TR&Uuml;CK 
    teilr&uuml;ckgemeldet</option>
    <option value="3" <?php if (!(strcmp(3, $row_rst2['fm7']))) {echo "SELECTED";} ?>>EKS 
    R&uuml;ckmeldung</option>
  </select>
  <input type="submit" name="Submit2" value="Kostenstelle 61612">
  </font></p>

</form>
 <?php include("footer.tpl.php"); ?>
<?php
mysql_free_result($rst_kst);
?>
