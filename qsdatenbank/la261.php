<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la261";
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];

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

if ($HTTP_POST_VARS['suche']> 0 ){
$suche = " and  materialbewegung.mbid=$HTTP_POST_VARS[suche] ";
}else{ 
$suche ="";
$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
	if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
	  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
	}
	$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;


}

$maxRows_rst2 = 50;;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM materialbewegung WHERE materialbewegung.martikelid = '$url_artikelid' $suche ORDER BY materialbewegung.mdatum DESC";
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
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung,artikeldaten.Bestand,artikeldaten.Reservierung,artikeldaten.Nummer FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

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
$updateGoTo = "la26.php?url_user=".$row_rst1['id'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la262.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_fid=".$HTTP_POST_VARS["hurl_fid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["view"])) ) {
$updateGoTo = "la263.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_fid=".$HTTP_POST_VARS["hurl_fid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}


if ((isset($HTTP_POST_VARS["neu"])) ) {
$updateGoTo = "la260.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["url_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}


include("function.tpl.php");



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<style type="text/css">
<!--
.checkboxstyle {
	background-color: #CCCCCC;
}
-->
</style>



<form name="form2" method="post" action="">
  <table width="830" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><strong>la261- Materialbewegung anzeigen</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="173">Aktionen W&auml;hlen:</td>
      <td width="280"> <div align="left"> <img src="picture/b_drop.png" width="16" height="16"> 
          <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck">
        </div></td>
      <td width="277"> <div align="right"> <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="neu" type="submit" id="neu" value="neu anlegen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hltes Material:</td>
      <td><strong><?php echo $row_rst4['Bezeichnung']; ?>- <?php echo $row_rst4['Nummer']; ?></strong></td>
      <td><input name="url_artikelid" type="hidden" id="url_artikelid" value="<?php echo $url_artikelid; ?>">
        Bestand aktuell: <strong><?php echo $row_rst4['Bestand']; ?></strong></td>
    </tr>
    <tr> 
      <td>Bewegung finden:</td>
      <?php 
	  if (isset($HTTP_POST_VARS['von'])){
$url_sn_von=$HTTP_POST_VARS['von'];
 }else {$url_sn_von=($row_rst2['fsn']+1);}
	
 if (isset($HTTP_POST_VARS['bis'])){
$url_sn_bis=$HTTP_POST_VARS['bis'];
	  }else {$url_sn_bis=($row_rst2['fsn']+21);}
	  ?>
      <td> <input name="suche" type="text" id="suche" value="<?php echo $HTTP_POST_VARS['suche']; ?>"> 
        <input name="suchen" type="submit" id="suchen" value="suchen"> </td>
      <td>Reservierung: <strong><?php echo $row_rst4['Reservierung']; ?></strong></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  </form>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Fertigungsmeldungen<br>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
Fertigungsmeldung von <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?>- 
insgesamt: <?php echo $totalRows_rst2 ?> Fertigungsmeldungen erfasst.<br>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="113"><em><strong>Belegnummer</strong></em></td>
    <td width="167"><em><strong><font size="2">Text</font></strong></em></td>
    <td width="171"><strong><em><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
      Benutzer</em></strong></td>
    <td width="112"><div align="center"><em><strong>Datum</strong></em></div></td>
    <td width="167">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['mbid']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php echo utf8_decode($row_rst2['mbdiff']); ?>-<?php echo utf8_decode($row_rst2['mbwa']); ?> <?php echo utf8_decode($row_rst2['mbtxt']); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php
do {  
?>
        <div align="left">
          <?php if (!(strcmp($row_rst3['id'], $row_rst2['user']))) {echo $row_rst3['name'];}?>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?></select>
          </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['mdatum']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $url_user;?>">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['martikelid']; ?>">
          <input name="hurl_fid" type="hidden" id="hurl_fid" value="<?php echo $row_rst2['mbid']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="bearbeiten">
          <input name="view" type="submit" id="edit22" value="Ansicht">
        </div></td>
  </form></tr>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
Fertigungsmeldung von <?php echo ($startRow_rst2 + 1) ?>bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?>- insgesamt: <?php echo $totalRows_rst2 ?> Fertigungsmeldungen erfasst.<br>
<?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
mysql_free_result($rst4);
?>
<?php include("footer.tpl.php"); ?>
