<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la47s";
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

$maxRows_rst2 = 100;
$pageNum_rst2 = 0;
 $suchtxt=""; 
 
if ((isset($HTTP_POST_VARS['suchen'])) && ($HTTP_POST_VARS['fmidsuche']>0)){
	$suchtxt="AND fehlermeldungen.fmid='".$HTTP_POST_VARS['fmidsuche']."' ";
	}
	
if ((isset($HTTP_POST_VARS['showall'])==1)){
	$suchtxt=$suchtxt."AND ( fehlermeldungen.fm7<>'2' and fehlermeldungen.fm7<>'3' ) ";
	}

if ((isset($HTTP_POST_VARS['suchen'])) && ($HTTP_POST_VARS['fauf']>0)){
	$suchtxt="AND fehlermeldungen.sappcna=".$HTTP_POST_VARS['fauf'];
	}else{
	
	if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
  }
  
  
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;
	
	
}

$maxRows_rst2 = 150;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmsn, fehlermeldungen.fmdatum, fehlermeldungen.fmartikelid, fehlermeldungen.fmfd, fehlermeldungen.fm1, fehlermeldungen.fm2, fehlermeldungen.fm3, fehlermeldungen.fm4, fehlermeldungen.fm5, fehlermeldungen.fm0, fehlermeldungen.fm6, fehlermeldungen.fm7, fehlermeldungen.fm1notes, fehlermeldungen.sappcna, fehlermeldungen.sapsd, fehlermeldungen.sapkst FROM fehlermeldungen WHERE fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1  AND fehlermeldungen.sappcna<>0 $suchtxt ORDER BY fehlermeldungen.fmid desc";
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
$updateGoTo = "la43.php?url_user=".$row_rst1['id']."&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&goback=la43s.php";
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="728" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <br> </td>
    </tr>
    <tr> 
      <td width="141">Aktionen W&auml;hlen:</td>
      <td width="135"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="370"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>Fehlermeldenummer:</td>
      <td><input name="fmidsuche" type="text" id="fmidsuche" value="<?php echo $HTTP_POST_VARS['fmidsuche'] ?>"></td>
      <td><input name="showall" type="checkbox" id="showall" value="1">
        erledigte Datens&auml;tze ausblenden, R&uuml;ck und EKS</td>
    </tr>
    <tr> 
      <td>Fertigungsauftragsnummer:</td>
      <td> <input name="fauf" type="text" id="fauf" value="<?php echo $HTTP_POST_VARS['fauf'] ?>"> 
      </td>
      <td><div align="right">
          <input name="suchen" type="submit" id="suchen" value="suchen">
        </div></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
- Retourenabwicklung: <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?> 
<table width="893" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td width="282"><strong>Bezeichnung</strong></td>
    <td width="98">&nbsp;</td>
    <td width="98">Kostenstelle</td>
    <td width="98">PCNA-Auftrag</td>
    <td width="16"><div align="center"><img src="picture/b_newdb.png" width="16" height="16"></div></td>
    <td width="16"><p align="center"><img src="picture/b_tblops.png" width="16" height="16"></p></td>
    <td width="15"><div align="center"><img src="picture/money.gif" width="15" height="15"></div></td>
    <td width="16"><div align="center"><img src="picture/b_insrow.png" width="16" height="16"></div></td>
    <td width="16"><div align="center"><img src="picture/sap.png" width="16" height="16"></div></td>
    <td width="16"><div align="center"><img src="picture/s_host.png" width="16" height="16"></div></td>
    <td width="16"><div align="center"><img src="picture/s_db.png" width="16" height="16"></div></td>
    <td width="15"><div align="center"><img src="picture/fertig.gif" width="15" height="15"></div></td>
    <td width="289">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
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
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['sapkst']; ?> </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['sappcna']; ?> 
        <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_fmid" type="hidden" id="hurl_fmid2" value="<?php echo $row_rst2['fmid']; ?>">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst2['fm0'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="geplante Retoure noch nicht aufgenommen" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"> 
          <font size="1" face="Arial, Helvetica, sans-serif"> <img src="picture/<?php if (!(strcmp($row_rst2['fm1'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Fehleraufnahme" width="15" height="15"></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/<?php if (!(strcmp($row_rst2['fm2'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kostenkl&auml;rung" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/<?php if (!(strcmp($row_rst2['fm3'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparaturfreigabe" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if ($row_rst2['sappcna']>1) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="SAP Fertigungsauftr&auml;ge als PCNA angelegt" width="15" height="15"></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <img src="picture/<?php if (!(strcmp($row_rst2['fm4'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Reparatur + VDE-Pr&uuml;fung" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"> <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/<?php if (!(strcmp($row_rst2['fm5'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Kommissionierstatus" width="15" height="15"> 
          </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"> 
          <font size="1" face="Arial, Helvetica, sans-serif"> <img src="picture/<?php if (!(strcmp($row_rst2['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";}?>" alt="Retoure abgeschlossen" width="15" height="15"></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
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
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="save" type="submit" id="save" value="speichern">
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
