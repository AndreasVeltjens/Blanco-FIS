<?php require_once('Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la70";
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
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
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

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM pruefungen WHERE pruefungen.lokz=0 ORDER BY pruefungen.gruppe, pruefungen.name ";
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
$updateGoTo = "la72.php?url_user=".$row_rst1['id']."&url_id_pruef=".$HTTP_POST_VARS["hurl_id_pruef"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["new"])) ) {
$updateGoTo = "la71.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["diagramm"])) ) {
$updateGoTo = "la73.php?url_user=".$row_rst1['id']."&url_id_pruef=".$HTTP_POST_VARS["hurl_id_pruef"]."&goback=la7";
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>


<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo utf8_decode($Modul[$d][1]); ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo utf8_decode($errtxt) ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td colspan="3"> <div align="right"> 
          <p> 
            <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo utf8_decode($row_rst1['id']); ?>">
            <font size="5"><strong>&Uuml;bersicht Statistische Prozesskontrolle 
            (SPC)</strong></font></p>
        </div></td>
    </tr>
    <tr> 
      <td colspan="3">Legende: </td>
    </tr>
    <tr>
      <td colspan="3"><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/st1.gif" alt="Pr&uuml;fstatus" width="15" height="15"></font> 
        Pr&uuml;fung dokumentiert, Pr&uuml;fplanung eingehalten. <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/st3.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        </font> Pr&uuml;fung &uuml;berf&auml;llig. Sofort bearbeiten. <img src="picture/b_tipp.png">Achtung 
        Parameter / Istwert ausserhalb der zul&auml;ssigen Toleranz.</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr> 
    <td width="42"><strong>Status</strong></td>
    <td width="200"><strong>Name</strong></td>
    <td width="101"><div align="right"><strong>Sollwert</strong></div></td>
    <td width="88"><div align="right"><strong>letzter Wert</strong></div></td>
    <td width="119"><div align="right"><strong>UGW</strong></div></td>
    <td width="118"><div align="right"><strong>OGW</strong></div></td>
    <td width="1">&nbsp;</td>
    <td width="27">&nbsp;</td>
  </tr>
  <?php do { 
  
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM pruefungdaten WHERE pruefungdaten.id_pruef=$row_rst2[id_pruef] and pruefungdaten.datum >=now()-interval $row_rst2[intervall] day AND pruefungdaten.lokz =0";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);
  

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT pruefungdaten.datum, pruefungdaten.messwert1, pruefungdaten.messwert2, pruefungdaten.messwert3, pruefungdaten.messwert4, pruefungdaten.messwert5, pruefungdaten.lokz, pruefungdaten.id_pruef FROM pruefungdaten WHERE pruefungdaten.id_pruef=$row_rst2[id_pruef] AND pruefungdaten.lokz =0 ORDER BY pruefungdaten.datum asc";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

do{
if ($row_rst2['formelid']==1){
		$ist=($row_rst6['messwert4']*$row_rst2['Umrechnungsfaktor'])/($row_rst6['messwert1']* $row_rst6['messwert2']*$row_rst6['messwert3']);
		}else{
		$ist=$row_rst6['messwert1']*$row_rst2['Umrechnungsfaktor'];
		}
 } while ($row_rst6 = mysql_fetch_assoc($rst6));
  $ist=round($ist,2)
  ?>
  <form name="form1" method="post" action="">
    <?php   if ($row_rst2['gruppe']!=$gruppe){?>
    <tr > 
      <td colspan="8" nowrap="nowrap"    bgcolor="#CCCCCC"><em><img src="picture/s_tbl.png" width="16" height="16"><?php echo utf8_decode($row_rst2['gruppe']); ?></em></td>
    </tr>
    <?php 	}?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
        <img src="picture/st1.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        <?php } // Show if recordset not empty ?>
        </font><font size="1" face="Arial, Helvetica, sans-serif"> 
        <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
        <img src="picture/st3.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        <?php } // Show if recordset empty ?>
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="-7">&nbsp; <?php echo utf8_decode($row_rst2['name']); ?></font> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><?php echo utf8_decode($row_rst2['soll']); ?><?php echo utf8_decode($row_rst2['einheit']); ?></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><strong><?php echo utf8_decode($ist." ".$row_rst2['einheit']); ?></strong></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"><font size="-7" face="Arial, Helvetica, sans-serif"> 
          <?php if (($ist<=$row_rst2['ugw'])) {echo "<img src=\"picture/b_tipp.png\">";}?>
          <?php echo utf8_decode($row_rst2['ugw']); ?> <?php echo utf8_decode($row_rst2['einheit']); ?></font><font size="-7" face="Arial, Helvetica, sans-serif"> 
          </font> </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><font face="Arial, Helvetica, sans-serif"> 
          <?php if (($ist>=$row_rst2['ogw'])) {echo "<img src=\"picture/b_tipp.png\">";}?>
          </font><?php echo utf8_decode($row_rst2['ogw']); ?><?php echo utf8_decode($row_rst2['einheit']); ?><font face="Arial, Helvetica, sans-serif"> </font></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    </tr>
  </form>
  <?php 
  $gruppe=$row_rst2['gruppe'];  
  } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<br>
<?php } // Show if recordset not empty ?>

<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
</p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> SPC angelegt.</p>
<p>Legende: </p>
<p><font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/st1.gif" alt="Pr&uuml;fstatus" width="15" height="15"></font> 
  Pr&uuml;fung dokumentiert, Pr&uuml;fplanung eingehalten.<br>
  <font size="1" face="Arial, Helvetica, sans-serif"><img src="picture/st3.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
  </font> Pr&uuml;fung &uuml;berf&auml;llig. Sofort bearbeiten.<br>
  <img src="picture/b_tipp.png">Achtung Parameter / Istwert ausserhalb der zul&auml;ssigen 
  Toleranz.</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst6);
?>
</p>

  <?php include("footer.tpl.php"); ?>
