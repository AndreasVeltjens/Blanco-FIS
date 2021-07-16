<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "fhm551";
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

if ($url_suchtext<>"" && !isset($HTTP_POST_VARS['suchtext'])){$HTTP_POST_VARS['suchtext']=$url_suchtext;}
if ($HTTP_POST_VARS['select']>0){
$suchwere=$suchwere." AND fhm.id_fhm_gruppe='".$HTTP_POST_VARS["select"]."' AND fhm_gruppe.id_fhm_gruppe='".$HTTP_POST_VARS["select"]."' ";
}

if ($HTTP_POST_VARS['select2']>0){
$suchwere=$suchwere." AND fhm.artikelid='".$HTTP_POST_VARS["select2"]."' ";
}

if ($HTTP_POST_VARS['select3']>0){
$suchwere=$suchwere;
} else{
$suchwere=$suchwere." AND fhm.lokz='0' ";
}

if ($HTTP_POST_VARS['suchtext']<>""){
$suchwhere=$suchwere." and (fhm.sn like '".$HTTP_POST_VARS['suchtext']."' or fhm.name like '".$HTTP_POST_VARS['suchtext']."' or fhm.Inventarnummer like '%".$HTTP_POST_VARS['suchtext']."' )";}
else
{$suchwhere=$suchwere."";}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM fhm_gruppe  WHERE fhm_gruppe.funktion = 1";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

$maxRows_rst2 = 100;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT fhm_gruppe.id_fhm_gruppe, fhm_gruppe.piclink, fhm.id_fhm, fhm.id_fhm_gruppe, fhm.name, fhm.lokz, fhm.ortsveraend, fhm.still, fhm.lagerort, fhm.lastmod, fhm.Inventarnummer, fhm.werk, fhm_gruppe.funktion, fhm.sn,fhm.suchgruppierung, fhm.artikelid FROM fhm , fhm_gruppe WHERE fhm.id_fhm_gruppe=fhm_gruppe.id_fhm_gruppe $suchwhere AND fhm.suchgruppierung like '%tausch%' AND fhm_gruppe.funktion=1 ORDER BY fhm.suchgruppierung,fhm.artikelid,fhm.sn";
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
$query_rst4 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
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
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "fhm12.php?url_user=".$row_rst1['id']."&url_id_fhm=".$HTTP_POST_VARS["hurl_id_fhm"]."&url_suchtext=".$HTTP_POST_VARS["suchtext"]."&goback=".$la;
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["ereignis"])) ) {
$updateGoTo = "fhm121.php?url_user=".$row_rst1['id']."&url_id_fhm=".$HTTP_POST_VARS["hurl_id_fhm"]."&url_suchtext=".$HTTP_POST_VARS["suchtext"]."&goback=".$la;
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["new"])) ) {
$updateGoTo = "fhm11.php?url_user=".$row_rst1['id']."&goback=fhm551.php";
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="4"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="4"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <br> </td>
    </tr>
    <tr> 
      <td width="60" rowspan="3"><img src="picture/icon_gr_servicegeraete.png" width="55" height="75"></td>
      <td width="120">Aktionen W&auml;hlen:</td>
      <td width="226"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="324"> <div align="right"></div></td>
    </tr>
    <tr> 
      <td>FHM Gruppe</td>
      <td colspan="2"> <select name="select">
          <option value="0">alle Gruppen anzeigen</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['id_fhm_gruppe']?>"<?php if (!(strcmp($row_rst3['id_fhm_gruppe'], $HTTP_POST_VARS["select"]))) {echo "SELECTED";} ?>><?php echo (utf8_encode($row_rst3['name']));?></option>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </select>
      </td>
    </tr>
    <tr> 
      <td>Einschr&auml;nkung</td>
      <td colspan="2">
<select name="select2">
          <option value="0" <?php if (!(strcmp(0, $row_rst4['artikelid']))) {echo "SELECTED";} ?>>keine 
          Einschr&auml;nkung</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $HTTP_POST_VARS['select2']))) {echo "SELECTED";} ?>><?php echo $row_rst4['Bezeichnung']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select>
        <select name="select3">
          <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['select3']))) {echo "SELECTED";} ?>>nur 
          verf&uuml;gbare zeigen</option>
          <option value="1" <?php if (!(strcmp(1, $HTTP_POST_VARS['select3']))) {echo "SELECTED";} ?>>auch 
          gel&ouml;schte und stillgelegte zeigen</option>
        </select> </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Suchtext</td>
      <td colspan="2"> <div align="left">
          <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>" size="50">
          <input name="suchen" type="submit" id="suchen2" value="Suchen">
          Tauschpool anzeigen</div></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
  Anzeige von <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?>
  

<table width="900" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="16"><font size="1"><strong>Typ</strong></font></td>
    <td width="21"><div align="center"><font size="1"><strong>OV</strong></font></div></td>
    <td width="21"><div align="center"><font size="1"><strong>S</strong></font></div></td>
    <td width="21"><div align="center"><font size="1"><strong>L</strong></font></div></td>
    <td width="60"><strong>ID</strong></td>
    <td width="63">&nbsp;</td>
    <td width="108"><strong>Name</strong></td>
    <td width="114"><strong>Inventar-<br>
      nummer</strong></td>
    <td width="48"><strong>SN</strong></td>
    <td width="126"><strong>Typ</strong></td>
    <td width="46">Gruppe</td>
    <td width="212">letzte &Auml;nderung</td>
    <td width="383"><strong>Lagerort-Werk</strong></td>
  </tr>
  <?php do { 
  $id_fhm=$row_rst2['id_fhm']; 
  
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rststatus = "SELECT fhm_ereignis.id_fhme, fhm_ereignis.id_fhm, fhm_ereignis.beschreibung, fhm_ereignis.id_fhmea, fhm_ereignis.ereigniszeitstempel, fhm_ereignisart.piclink, fhm_ereignisart.name,fhm_ereignis.beschreibung  
FROM fhm_ereignis, fhm_ereignisart WHERE fhm_ereignis.id_fhm=$id_fhm AND fhm_ereignis.lokz=0 AND fhm_ereignisart.id_fhmea=fhm_ereignis.id_fhmea 
ORDER BY fhm_ereignis.datum desc,  fhm_ereignis.ereigniszeitstempel, fhm_ereignis.id_fhme desc";
$rststatus = mysql_query($query_rststatus, $qsdatenbank) or die(mysql_error());
$row_rststatus = mysql_fetch_assoc($rststatus);
$totalRows_rststatus = mysql_num_rows($rststatus);
  
  
  ?>
  <form name="form1" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php echo $row_rst2['piclink']; ?>" width="15" height="15"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if (!(strcmp($row_rst2['ortsveraend'],1))) {echo "<img src=\"picture/eo.gif\" >";}else{echo "<img src=\"picture/st6.gif\" >";}  ?>
        <font size="1"><a href="pdfetifhm1.php?url_id_fhm=<?php echo $row_rst2['id_fhm']?>" target="_blank"><img src="picture/information.gif" alt="Aufkleber drucken" width="16" height="16" border="0" align="absmiddle"></a></font> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if (!(strcmp($row_rst2['still'],1))) {echo "<img src=\"picture/st3.gif\" >";}else{echo "<img src=\"picture/st6.gif\" >";} ?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "<img src=\"picture/b_drop.png\" >";}else{echo "<img src=\"picture/st6.gif\" >";} ?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;<?php echo $row_rst2['id_fhm']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="ereignis" type="submit" id="ereignis" value="Ereignis"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <font size="1" face="Arial, Helvetica, sans-serif"> <?php echo wordwrap($row_rst2['name'],40,"<br>",240); ?> 
        <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $row_rst2['Inventarnummer']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo $row_rst2['sn']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1">&nbsp; 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst4['artikelid'], $row_rst2['artikelid']))) {echo utf8_decode($row_rst4['Bezeichnung']);}?>
        <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
	  mysql_free_result($rststatus);
  }
?></select>
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo $row_rst2['suchgruppierung']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><?php echo "<img src=\"picture/".$row_rststatus['piclink']."\">".$row_rststatus['name']." - ".substr($row_rststatus['beschreibung'],0,30)." - "; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"><font size="1"><?php echo substr($row_rst2['lagerort'],0,15)." - ".$row_rst2['werk']; ?></font><font size="1" face="Arial, Helvetica, sans-serif"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_fhm" type="hidden" id="hurl_id_fhm" value="<?php echo $row_rst2['id_fhm']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> </font><font size="1" face="Arial, Helvetica, sans-serif"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
          </font></div></td>
    </tr>
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<font size="1" face="Arial, Helvetica, sans-serif">
<input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
</font>
<?php } // Show if recordset not empty ?>

<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
  Anzeige von <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?></p>
<p>Statistik: <?php echo $totalRows_rst2 ?> Serviceger&auml;te oder Equipment angelegt.<br>
  Funktionsebene 1</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst4);


mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
