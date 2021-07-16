<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "la11";
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];

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

$maxRows_rst2 = 30;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

if ($HTTP_POST_VARS['nurimlager']==1){
	$suchenurimlager=" AND versendetam='0000-00-00 00:00:00' ";
}

if ($HTTP_POST_VARS['suche']> 0 ){
$suche = " and  fertigungsmeldungen.fsn=$HTTP_POST_VARS[suche] ";
}elseif ($HTTP_POST_VARS['bauteil']> 0 ){
$suche = " and  (fertigungsmeldungen.fsn1=$HTTP_POST_VARS[bauteil] or fertigungsmeldungen.fsn1=$HTTP_POST_VARS[bauteil] or fertigungsmeldungen.fsn1=$HTTP_POST_VARS[bauteil])";

}else{

$suche ="and fertigungsmeldungen.fsn<900000";
$maxRows_rst2 = 30;
$pageNum_rst2 = 0;
	if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
	  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
	}
	$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;


}

$maxRows_rst2 = 30;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid = '$url_artikelid' $suche $suchenurimlager ORDER BY fertigungsmeldungen.fsn DESC,  fertigungsmeldungen.fid desc";
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
$query_rst4 = "SELECT *  FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid' ";
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
$updateGoTo = "la1.php?url_user=".$row_rst1['id'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["cancel2"])) ) {
$updateGoTo = "la1.php?url_user=".$row_rst1['id']."&url_kontierung=".$row_rst4['Kontierung'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la12.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_fid=".$HTTP_POST_VARS["hurl_fid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["view"])) ) {
$updateGoTo = "la14.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_fid=".$HTTP_POST_VARS["hurl_fid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["la202"])) ) {
$updateGoTo = "la202.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["url_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["la21"])) ) {
$updateGoTo = "la21.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["url_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Sprintf)";}


$old_sn= $row_rst2["fsn"];
if ((isset($HTTP_POST_VARS["neu"])) ) {
$updateGoTo = "la13.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["url_artikelid"]."&old_sn=".$old_sn;
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["chargen"])) ) {
$updateGoTo = "la15.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["url_artikelid"]."&old_sn=".$old_sn;
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["co11"])) ) {
$updateGoTo = "co11.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["url_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen. (Sprintf)";}
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
  <table width="930" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong>Fertigungsdaten anzeigen</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="175">Aktionen W&auml;hlen:</td>
      <td width="650"> <div align="left"> <img src="picture/error.gif" width="16" height="16"> 
          <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
		    <input name="cancel2" type="submit" id="cancel2" value="Zur&uuml;ck zur Liste">
          <img src="picture/memory.gif" width="16" height="16"> 
          <input name="la202" type="submit" id="la202" value="Stammdaten">
          <img src="picture/move.gif" width="22" height="21">
<input name="la21" type="submit" id="la21" value="Versionen">
          <img src="picture/orderlist_icon.gif" width="15" height="19"> 
          <input name="co11" type="submit" id="co11" value="R&uuml;ckmeldung">
        </div></td>
      <td width="186"> <div align="right"><img src="picture/bildergalerie.gif" width="9" height="10"> 
          <input name="chargen" type="submit" id="chargen" value="Chargen">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="neu" type="submit" id="neu" value="Neu">
        </div></td>
    </tr>
    <tr> 
      <td rowspan="8"> <div align="left"><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>" target="_blank"> 
          </a><a href="../documents/materialbilder/<?php echo $row_rst4['Nummer'] ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst4['Nummer'] ?>.png" alt="EAN/UPC Nummer: <?php echo $row_rst4['ean13'] ?>" width="180" height="140" border="0"></a></div></td>
      <td>&nbsp;</td>
      <td><div align="right"><a href="pdfetikett5.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>" target="_blank"><img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          GWL-Hinweis drucken</a></div></td>
    </tr>
    <tr> 
      <td height="21"> <input name="url_artikelid" type="hidden" id="url_artikelid" value="<?php echo $url_artikelid; ?>">
        Aufkleberdruck </td>
      <td height="21"><div align="right"></div></td>
      <?php 
	  if (isset($HTTP_POST_VARS['von'])){
$url_sn_von=$HTTP_POST_VARS['von'];
 }else {$url_sn_von=($row_rst2['fsn']+1);}
	
 if (isset($HTTP_POST_VARS['bis'])){
$url_sn_bis=$HTTP_POST_VARS['bis'];
	  }else {$url_sn_bis=($row_rst2['fsn']+21);}
	  ?>
    </tr>
    <tr> 
      <td>von 
        <input name="von" type="text" id="von2" size="10" value="<?php echo $url_sn_von ?>">
        bis 
        <input name="bis" type="text" id="bis2" size="10" value="<?php echo $url_sn_bis ?>"> 
        <input name="suchen2" type="submit" id="suchen22" value="aktualisieren"></td>
      <td><div align="right"><a href="pdfetikett3.php?url_bezeichnung=<?php echo $row_rst4['Bezeichnung'] ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>" target="_blank"><img src="picture/anschreiben.gif" width="18" height="18" border="0" align="absmiddle"> 
          SN-Aufkleber drucken</a> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <div align="right"><a href="pdfetikett10.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>&url_ean13=<?php echo $row_rst4['ean13'] ?>" target="_blank"><img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          QS-Ettikett drucken</a></div></td>
    </tr>
    <tr> 
      <td><div align="right"></div>
        ausgew&auml;hlter Ger&auml;tetyp:</td>
      <td><div align="right"><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>&logo=1&url_ean13=<?php echo $row_rst4['ean13'] ?>&logo=1" target="_blank"><img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          Typenschild eckig drucken</a> </div></td>
    </tr>
    <tr> 
      <td><strong><?php echo $row_rst4['Bezeichnung']; ?>- <?php echo $row_rst4['Nummer']; ?></strong></td>
      <td><div align="right"><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>&url_ean13=<?php echo $row_rst4['ean13'] ?>&logo=1/h/p" target="_blank"><img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          Typenschild oval drucken</a></div></td>
    </tr>
    <tr> 
      <td>Seriennummer finden: nur im Lager suchen 
        <input <?php if (!(strcmp($HTTP_POST_VARS['nurimlager'],1))) {echo "checked";} ?> name="nurimlager" type="checkbox" id="nurimlager" value="1"></td>
      <td><div align="right"><a href="pdfetikett7.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>&url_ean13=<?php echo $row_rst4['ean13'] ?>" target="_blank"><img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          Kartonettikett drucken</a></div></td>
    </tr>
    <tr> 
      <td> <input name="suche" type="text" id="suche" value="<?php echo $HTTP_POST_VARS['suche']; ?>"> 
        <input name="suchen" type="submit" id="suchen" value="suchen">
        <img src="picture/windows7-arrow.jpg" width="30" height="13"> Bauteil-ID 
        <input name="bauteil" type="text" id="bauteil" value="<?php echo $HTTP_POST_VARS[bauteil]; ?>" size="15" maxlength="255"> 
        <img src="picture/help.gif" alt="Wenn Seriennummer vom Typenschild nicht erkennbar ist, dann Bauteilnummer eingeben und auf Suchen klicken. bei Umluftheizungsmodellen wird zun&auml;chst nach der Heizungsmodulnummer gesucht." width="17" height="17"> 
      </td>
      <td><div align="right"><a href="pdfetikett31.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>&url_ean13=<?php echo $row_rst4['ean13'] ?> /h /p" target="_blank"><img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          Chargenettikett drucken</a></div></td>
	 
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
<table width="1049" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="102"><em><strong>Seriennummer</strong></em></td>
    <td width="45">Status</td>
    <td width="60">Version</td>
    <td width="189"><em><strong><font size="2">i</font>.O. Pr&uuml;fung/ Sonderfreigabe</strong></em></td>
    <td width="136">Bauteile</td>
	<td width="20">Barcode</td>
    <td width="136"><strong><em><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
      erstellt</em></strong></td>
    <td width="84"><strong><em><img src="picture/iconchance_16x16.gif" width="16" height="16">freigegeben</em></strong></td>
    <td width="84"><div align="center"><strong><em>Datum</em></strong></div></td>
    <td width="138">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $row_rst2['fsn']; ?>&url_sn_bis=<?php echo $row_rst2['fsn']; ?>&logo=1/h/p" target="_blank"><img src="picture/rules.gif" alt="Typenschild drucken" width="13" height="18" border="0" align="absmiddle"></a> 
          <a href="pdfetikett7.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $row_rst2['fsn']; ?>&url_sn_bis=<?php echo $row_rst2['fsn']; ?>&url_ean13=<?php echo $row_rst4['ean13'] ?>/h/p" target="_blank"> 
          <img src="picture/iconShipBlue_16x16.gif" width="16" height="16" border="0"> 
          </a> 
          <?php if ($row_rst2['fsn']==$altsn){   ?>
          <img src="picture/1.gif" width="20" height="10">WD 
          <?php }else{
		  ?>
          <?php echo $row_rst2['fsn']; } ?> 
		  <a href="pdfetikett3.php?url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $row_rst2['fsn'] ?>&url_sn_bis=<?php echo $row_rst2['fsn'] ?>" target="_blank"> 
          <img src="picture/anschreiben.gif" alt="SN-Aufkleber drucken" width="18" height="18" border="0" align="absmiddle"></a>
		  
		  
		  
		  <a href="dokuset.tmp1.php?url_artikelid=<?php echo $row_rst4['artikelid'] ?>&url_sn=<?php echo $row_rst2['fsn'] ?>&fid=<?php echo $row_rst2['fid'] ?>" target="_blank"> 
          <img src="picture/pdf_icon.gif" alt="SN-Aufkleber drucken" width="16" height="19" border="0" align="absmiddle"></a>
		  
		  
		  
		  </div></td>
      
	  
	  
	  
	  
	  
	  
	  <td bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if ($row_rst2['status']==2){ ?>
        <a href="la14.php?url_user=<?php echo $row_rst1['id'];?>&url_artikelid=<?php echo $row_rst2['fartikelid']; ?>&url_fid=<?php echo $row_rst2['fid']; ?>"> 
        <img src="picture/iconReadOnly_16x16.gif" alt="Ansehen" width="16" height="16" border="0"> 
        </a> 
        <?php } elseif ($row_rst2['status']==0){ ?>
        <img src="picture/b_drop.png" width="16" height="16"> 
        <?php } elseif ($row_rst2['status']==1){ ?>
        <a href="la14.php?url_user=<?php echo $row_rst1['id'];?>&url_artikelid=<?php echo $row_rst2['fartikelid']; ?>&url_fid=<?php echo $row_rst2['fid']; ?>"><img src="picture/b_edit.png" alt="bearbeiten und Status &auml;ndern" width="16" height="16" border="0"></a> 
        <?php } ?>
		
		 <?php if ($row_rst2['verkauftam']>"0000-00-00 00:00:00"){ ?>
        <a href="la14.php?url_user=<?php echo $row_rst1['id'];?>&url_artikelid=<?php echo $row_rst2['fartikelid']; ?>&url_fid=<?php echo $row_rst2['fid']; ?>"> 
        <img src="picture/euro.gif" alt="Ansehen" width="20" height="20" border="0"> 
        </a> 
        <?php } ?>
		<?php if ($row_rst2['versendetam']>"0000-00-00 00:00:00"){ ?>
        <a href="la14.php?url_user=<?php echo $row_rst1['id'];?>&url_artikelid=<?php echo $row_rst2['fartikelid']; ?>&url_fid=<?php echo $row_rst2['fid']; ?>"> 
        <img src="picture/orderstatus.gif" alt="Ansehen" width="22" height="12" border="0"> 
        </a> 
        <?php } ?>
		
		
		
		
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['version']; ?><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $row_rst2['fsn']; ?>&url_sn_bis=<?php echo $row_rst2['fsn']; ?>&logo=1/h/p" target="_top"><img src="picture/rules.gif" alt="Typenschild drucken" width="13" height="18" border="0" align="absmiddle"></a></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/<?php if (!(strcmp($row_rst2['ffrei'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" alt="<?php echo "Pr&uuml;f-Signatur: ".$row_rst2['signatur']; ?>" width="15" height="15" align="absmiddle"> 
        - <img src="picture/<?php if (!(strcmp($row_rst2['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" alt="<?php echo "Pr&uuml;f-Signatur: ".$row_rst2['signatur']; ?>" width="15" height="15" align="absmiddle"> 
        - <?php echo wordwrap(utf8_decode($row_rst2['fnotes']),40,"<br>",255); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['fsn1']; ?>- <?php echo $row_rst2['fsn2']; ?>-<?php echo $row_rst2['fsn3']; ?></td>
      <td><a href="pdf.barcodegenerator.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_fid=<?php echo $row_rst2['fid'] ?>" target="_blank">
	  <img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle" alt="Barcode-R&uuml;ckmeldung drucken"> 
	  </a>
	  </td>
	  <td  bgcolor="#EEEEEE" nowrap="nowrap"> 
	    
        <?php
do {  
?>
        <div align="center"> 
          <?php if (!(strcmp($row_rst3['id'], $row_rst2['fuser']))) {echo $row_rst3['name'];}?>
          <?php
		  $altsn=$row_rst2['fsn'];
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?></select>
          </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">
        <?php
do {  
?>
        <div align="center"> 
          <?php if (!(strcmp($row_rst3['id'], $row_rst2['freigabeuser']))) {echo $row_rst3['name'];}?>
          <?php
		 
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </div>

          <?php echo wordwrap($row_rst2['configprofil'],30,"<br>"); ?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst2['fdatum']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $url_user;?>">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['fartikelid']; ?>">
          <input name="hurl_fid" type="hidden" id="hurl_fid" value="<?php echo $row_rst2['fid']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="view" type="submit" id="edit22" value="A">
          <input name="edit" type="submit" id="edit" value="B">
        </div></td>
		
		
		
  </form></tr>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
Fertigungsmeldung von <?php echo ($startRow_rst2 + 1); ?>bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2); ?>- insgesamt: <?php echo $totalRows_rst2; ?> Fertigungsmeldungen erfasst.<br>
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
