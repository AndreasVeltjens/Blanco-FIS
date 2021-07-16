<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la41bs";
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
  $updateSQL = sprintf("UPDATE fehlermeldungen SET fm1text=%s, fm1b=%s WHERE fmid=%s",
                       GetSQLValueString($HTTP_POST_VARS['fm1text'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['fm1b']) ? "true" : "", "defined","1","0"),
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
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmsn, fehlermeldungen.fmartikelid, fehlermeldungen.fmfd, fehlermeldungen.fm1, fehlermeldungen.fm2, fehlermeldungen.fm3, fehlermeldungen.fm4, fehlermeldungen.fm5, fehlermeldungen.fm0, fehlermeldungen.fm6, fehlermeldungen.fm6, fehlermeldungen.fm1notes, fehlermeldungen.sappcna, fehlermeldungen.vkz, fehlermeldungen.vip, fehlermeldungen.fm1b, fehlermeldungen.fm1text, fehlermeldungen.fm1bnotes, fehlermeldungen.fm11notes, fehlermeldungen.fm12notes, fehlermeldungen.fm1notes, fehlermeldungen.fmnotes, fehlermeldungen.fm1text, fehlermeldungen.fmuser, fehlermeldungen.fmdatum, fehlermeldungen.fm1datum, fehlermeldungen.fmfd FROM fehlermeldungen WHERE fehlermeldungen.fm0=1 AND fehlermeldungen.fm1b=0 ORDER BY fehlermeldungen.fmid=0 DESC";
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
$updateGoTo = "start.php?url_user=".$row_rst1['id']."&goback=la41s.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}
if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la41.php?url_user=".$row_rst1['id']."&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&goback=la41s.php";
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["new"])) ) {
$updateGoTo = "la40.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
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
      <td width="370"> <div align="right"><font size="5"><strong>Retoureneingang-Kontrollmodus 
          1 </strong></font> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>
<div align="right"></div></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<p>Legende:<br>
  <img src="picture/b_tipp.png" alt="Achtung Reparaturvorschlag wurde nachtr&auml;glich ge&auml;ndert" width="16" height="16"> 
  Achtung Reparaturvorschlag wurde nachtr&auml;glich ge&auml;ndert.<br>
  <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/iconchance_16x16.gif" width="16" height="16"></font> 
  Mitarbeiter, der die Retoure angelegt und die Fehleraufnahme durchgef&uuml;hrt 
  hat. </p>
<table width="757" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="40">VIP</td>
    <td width="44"><strong>FMID</strong></td>
    <td width="129"><strong>Typ</strong></td>
    <td width="47"><strong>SN</strong></td>
    <td width="120">Debitor</td>
    <td width="54">FD</td>
    <td width="69">Eingang</td>
    <td width="16"><div align="center"><img src="picture/b_newdb.png" width="16" height="16"></div></td>
    <td width="16"><p align="center"><img src="picture/b_tblops.png" width="16" height="16"></p></td>
    <td width="15"><div align="center"><img src="picture/money.gif" width="15" height="15"></div></td>
    <td width="49"><div align="center"><img src="picture/b_insrow.png" width="16" height="16"></div></td>
    <td width="49"><div align="center"><img src="picture/sap.png" width="16" height="16"></div></td>
    <td width="49"><div align="center"><img src="picture/s_host.png" width="16" height="16"></div></td>
    <td width="49"><div align="center"><img src="picture/s_db.png" width="16" height="16"></div></td>
    <td width="15"><div align="center"><img src="picture/fertig.gif" width="15" height="15"></div></td>
    <td width="68"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/b_edit.png" width="16" height="16"> 
      Bemerkung</font></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"> 
	  	  <?php if ($row_rst2['fmdatum']>=date("Y-m-d H:i:s",time()-100)) {echo "<img src=\"picture/iconAuction_16x16.gif\" alt=\"Achtung. Dieser Datensatz innerhalb der letzten 5 min von einem Benutzer bearbeitet.\">";}?>
        <?php if ((($row_rst2['vip']>=1))) {echo "<img src=\"picture/b_tipp.png\" alt=\"Dringlichkeitskennzeichen. Terminreparatur. Schnelle Reparatur erforderlich.\">" ;}?>
        <?php if (($row_rst2['vip']==2)) {echo "<img src=\"picture/b_tipp.png\">";}?>
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fmid']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <font size="1" face="Arial, Helvetica, sans-serif"> 
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
          <input name="fm1b" type="checkbox" id="fm1b" value="fm1b">
          <input name="fm1text" type="text" id="fm1text" value=" <?php echo ($row_rst2['fm1datum']-$row_rst2['fmfd'])." Jahre alt"; ?>">
          <input name="agm" type="submit" id="agm" value="AGM">
          - </font></div></td>
    </tr>
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp; 
        <?php if ($row_rst2['fmnotes']!=$row_rst2['fm1bnotes']){ ?>
        <img src="picture/b_tipp.png" alt="Achtung Reparaturvorschlag wurde nachtr&auml;glich ge&auml;ndert" width="16" height="16"> 
        <?php }?>
      </td>
      <td colspan="14" nowrap="nowrap"    bgcolor="#EEEEEE"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['fm11notes']; ?> - <?php echo $row_rst2['fm12notes']; ?> - <?php echo $row_rst2['fmnotes']; ?> -<img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst3['id'], $row_rst2['fmuser']))) {echo $row_rst3['name'];}?>
        <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        - <?php echo $row_rst2['fm1text']; ?><br>
        <?php echo $row_rst2['fm1bnotes']; ?></font></td>
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
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Retouren zur Aufnahme angelegt.</p>
<p>Legende:<br>
  <img src="picture/b_tipp.png" alt="Achtung Reparaturvorschlag wurde nachtr&auml;glich ge&auml;ndert" width="16" height="16"> 
  Achtung Reparaturvorschlag wurde nachtr&auml;glich ge&auml;ndert.<br>
  <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/iconchance_16x16.gif" width="16" height="16"></font> 
  Mitarbeiter, der die Retoure angelegt und die Fehleraufnahme durchgef&uuml;hrt 
  hat. <br>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rsttyp);

mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
