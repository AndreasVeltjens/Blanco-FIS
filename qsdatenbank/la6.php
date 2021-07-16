<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la6";
 


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

$maxRows_rst2 = 100;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;
if (isset($HTTP_POST_VARS["suchtext"])){
if ($HTTP_POST_VARS["suchtext"]==""){$HTTP_POST_VARS["suchtext"]="%";}

$HTTP_POST_VARS["suchtext"]=utf8_encode($HTTP_POST_VARS["suchtext"]);
$suchtext=" WHERE kundendaten.deb=1 and (kundendaten.idk like '".$HTTP_POST_VARS["suchtext"]."' 
OR kundendaten.firma like '%".$HTTP_POST_VARS["suchtext"]."%' 
OR kundendaten.ort like '%".$HTTP_POST_VARS["suchtext"]."%' 
OR plz like '".$HTTP_POST_VARS["suchtext"]."' 
OR land like '".$HTTP_POST_VARS["suchtext"]."'
OR kdnummer2 like '".$HTTP_POST_VARS["suchtext"]."' 
OR kundenummer like '".$HTTP_POST_VARS["suchtext"]."' )";}
else{$suchtext="WHERE kundendaten.deb=1";}

$maxRows_rst2 = 100;;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kundendaten $suchtext ORDER BY kundendaten.firma";
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

 if ((isset($HTTP_POST_VARS["fehlermeldungen"])) ) {
$updateGoTo = "la4.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la62.php?url_user=".$row_rst1['id']."&url_idk=".$HTTP_POST_VARS["hurl_idk"]."&goback=$la";
  header(sprintf("Location: %s", $updateGoTo));
}
if ((isset($HTTP_POST_VARS["vorgang"])) ) {
$updateGoTo = "la621.php?url_user=".$row_rst1['id']."&url_idk=".$HTTP_POST_VARS["hurl_idk"]."&goback=$la";
  header(sprintf("Location: %s", $updateGoTo));
}


if ((isset($HTTP_POST_VARS["new"])) ) {
$updateGoTo = "la61.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");

?>
<script>
function sf(){document.form2.suchtext.focus()}
</script> 
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?>
        <?php if ($oktxt !="") { // Show if recordset not empty ?>
        <strong><font color="#00CC33"><?php echo htmlentities($oktxt);?> </font></strong> 
        <?php } // Show if recordset not empty ?>
        <?php if ($errtxt !="") { // Show if recordset not empty ?>
        <strong><font color="#FF0000"><?php echo htmlentities($errtxt); ?> </font></strong> 
        <?php } // Show if recordset not empty ?>
        <br> </td>
    </tr>
    <tr> 
      <td width="158">Aktionen W&auml;hlen:</td>
      <td width="238"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck">
        <input name="fehlermeldungen" type="submit" id="fehlermeldungen" value="Fehlermeldungen anzeigen"></td>
      <td width="332"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16">
          <input name="new" type="submit" id="new" value="Neu anlegen">
        </div></td>
    </tr>
    <tr> 
      <td><img src="picture/addressbook.gif" width="52" height="36"> Suchtext:</td>
      <td>
<input name="suchtext" type="text" id="suchtext" value="<?php echo utf8_decode($HTTP_POST_VARS['suchtext']); ?>">
        <input name="suchen" type="submit" id="suchen" value="suchen"></td>
      <td>Suche in Name, Ort, PLZ, Land und Kundennummer/ Lieferantennummer / 
        Abteilung</td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="1115" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="11"><a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
      <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
      <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
      <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
      - Ansprechpartner: <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?><font size="2" face="Arial, Helvetica, sans-serif">&nbsp; </font></td>
  </tr>
  <tr> 
    <td width="220"><strong>Kunden-Nr.</strong></td>
    <td width="129">andere Nr.</td>
    <td width="129"><strong>Firmenname</strong></td>
    <td width="53"><strong>Stra&szlig;e</strong></td>
    <td width="32">PLZ</td>
    <td width="86">Ort</td>
    <td width="84">Land</td>
    <td width="84">Fax</td>
    <td width="96">Telefon</td>
    <td width="23">&nbsp;</td>
    <td width="167">&nbsp;</td>
  </tr>
  <?php  $buchstabealt ="";?>
  <?php do {  ?>
  <form name="form1" method="post" action="">
    <?php
   if ($buchstabealt!= substr((utf8_decode($row_rst2['firma'])),0,1))  {  ?>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="11" nowrap="nowrap"><img src="picture/s_tbl.png" width="16" height="16"> 
        <?php echo  substr( (utf8_decode($row_rst2['firma'])),0,1) ?> </td>
    </tr>
    <?php } ?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;<?php echo $row_rst2['kundenummer']; ?><a href="la62.php?url_user=<?php echo $row_rst1['id']; ?>&url_idk=<?php echo $row_rst2['idk']; ?>&goback=la6" target="_blank"><img src="picture/b_edit.png" alt="Stammdaten &auml;ndern" width="16" height="16" border="0"></a> 
        <a href="la4.php?url_user=<?php echo $url_user; ?>&suchtext=<?php echo $row_rst2['kundenummer']; ?>"> 
        <img src="picture/iconPos_16x16.gif" alt="&Ouml;ffne Retouren&uuml;bersicht" width="16" height="16" border="0"> 
        </a> <a href="vl50.php?url_user=<?php echo $url_user; ?>&idk=<?php echo $row_rst2['idk']; ?>"> 
        <img src="picture/pickreturn.gif" alt="R&uuml;ckholung organisieren" width="16" height="16" border="0"></a> 
        <a href="pdfetikett2.php?url_idk=<?php echo $row_rst2['idk']; ?>" target="_blank"><img src="picture/information.gif" alt="Versandetikett anzeigen" width="16" height="16" border="0" align="absmiddle"></a> 
        </font><font size="1"><a href="mailto:<?php echo utf8_decode($row_rst2['email']); ?>" target="_blank"><img src="picture/anschreiben.gif" alt="E-Mail erstellen" width="18" height="18" border="0"></a></font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['kdnummer2']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        <?php echo substr((utf8_decode($row_rst2['firma'])),0,25); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo substr((utf8_decode($row_rst2['strasse'])),0,20); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo (utf8_decode($row_rst2['plz'])); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo substr((utf8_decode($row_rst2['ort'])),0,20); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo substr((utf8_decode($row_rst2['land'])),0,20); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo substr($row_rst2['faxnummer'],0,20); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo substr($row_rst2['telefon'],0,20); ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;</font><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="1"> 
        <input name="hurl_idk" type="hidden" id="hurl_idk" value="<?php echo $row_rst2['idk']; ?>">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> <font size="1" face="Arial, Helvetica, sans-serif"> 
          <input name="vorgang" type="submit" id="vorgang" value="Vorg&auml;nge">
          <input name="edit" type="submit" id="edit" value="Stammdaten">
          </font></div></td>
    </tr>
  </form>
  <?php 
  $buchstabealt= substr((utf8_decode($row_rst2['firma'])),0,1);
  } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<?php } // Show if recordset not empty ?>
<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
  - Debitoren: <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?><font size="2" face="Arial, Helvetica, sans-serif"> </font> </p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Debitoren angelegt.</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rsttyp);
?>
</p>

  <?php include("footer.tpl.php"); ?>
