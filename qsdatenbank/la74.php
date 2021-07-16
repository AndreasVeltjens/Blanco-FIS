<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la74";
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

if ((isset($HTTP_POST_VARS["add"]))) {

if ($HTTP_POST_VARS['anzahlwerte']==1){
$HTTP_POST_VARS['wert2']=1;
$HTTP_POST_VARS['wert3']=1;
$HTTP_POST_VARS['wert4']=1;
$HTTP_POST_VARS['wert5']=1;
}
if ($HTTP_POST_VARS['anzahlwerte']==2){
$HTTP_POST_VARS['wert3']=1;
$HTTP_POST_VARS['wert4']=1;
$HTTP_POST_VARS['wert5']=1;
}
if ($HTTP_POST_VARS['anzahlwerte']==3){
$HTTP_POST_VARS['wert4']=1;
$HTTP_POST_VARS['wert5']=1;
}
if ($HTTP_POST_VARS['anzahlwerte']==4){
$HTTP_POST_VARS['wert5']=1;
}

  $insertSQL = sprintf("INSERT INTO pruefungdaten (datum, `user`, messwert1, messwert2, messwert3, messwert4, messwert5, id_pruef) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user2'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['wert1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wert4'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['wert5'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_pruef'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
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
$query_rst2 = "SELECT * FROM pruefungdaten WHERE pruefungdaten.id_pruef='$url_id_pruef' ORDER BY pruefungdaten.datum desc";
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
$query_rst3 = "SELECT * FROM pruefungen WHERE pruefungen.id_pruef='$url_id_pruef'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM `user`";
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
$updateGoTo = "la72.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["back"])) ) {
$updateGoTo = "la7.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la75.php?url_user=".$row_rst1['id']."&url_id_pruef=".$HTTP_POST_VARS["hurl_id_pruef"]."&url_id_pruefd=".$HTTP_POST_VARS["hurl_id_pruefd"];
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
      <td width="145">Aktionen W&auml;hlen:</td>
      <td width="234"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck zum Pr&uuml;fungskopf"> 
      </td>
      <td width="351"> <div align="right">
          <input name="back" type="submit" id="back" value="zur&uuml;ck zur &Uuml;bersicht">
          <input name="hurl_id_pruef" type="hidden" id="hurl_id_pruef" value="<?php echo $url_id_pruef; ?>">
        </div></td>
    </tr>
    <tr> 
      <td height="20"><strong>Messreihe</strong></td>
      <td colspan="2"><strong><?php echo $row_rst3['name']; ?></strong></td>
    </tr>
    <tr> 
      <td> Beschreibung &amp;<br>
        Pr&uuml;fanweisung 
        <input name="anzahlwerte" type="hidden" id="anzahlwerte" value="<?php echo $row_rst3['anzahlwerte']; ?>"></td>
      <td colspan="2"><?php echo nl2br($row_rst3['memo']); ?> <br>
      </td>
    </tr>
  </table>
  <table width="731" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCFF">
    <tr> 
      <td width="118" bgcolor="#CCCCFF"><?php echo $row_rst3['wert1text']; ?></td>
      <td width="127" bgcolor="#CCCCFF"><?php echo $row_rst3['wert2text']; ?></td>
      <td width="130" bgcolor="#CCCCFF"><?php echo $row_rst3['wert3text']; ?></td>
      <td width="118" bgcolor="#CCCCFF"><?php echo $row_rst3['wert4text']; ?></td>
      <td width="117" bgcolor="#CCCCFF"><?php echo $row_rst3['wert5text']; ?></td>
      <td width="108" bgcolor="#CCCCFF">&nbsp;</td>
      <td width="1">&nbsp;</td>
      <td width="1">&nbsp;</td>
      <td width="1">&nbsp;</td>
      <td width="10">&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#CCCCFF"> <input name="wert1" type="text" id="wert1" size="10" maxlength="10">
        <?php echo $row_rst3['wert1unit']; ?> </td>
      <td bgcolor="#CCCCFF"> <input name="wert2" type="text" id="wert2" size="10" maxlength="10">
        <?php echo $row_rst3['wert2unit']; ?></td>
      <td bgcolor="#CCCCFF"> <input name="wert3" type="text" id="wert3" size="10" maxlength="10">
        <?php echo $row_rst3['wert3unit']; ?> </td>
      <td bgcolor="#CCCCFF"> <input name="wert4" type="text" id="wert4" size="10" maxlength="10">
        <?php echo $row_rst3['wert4unit']; ?> </td>
      <td bgcolor="#CCCCFF">
<input name="wert5" type="text" id="wert5" size="10" maxlength="10">
        <?php echo $row_rst3['wert5unit']; ?></td>
      <td bgcolor="#CCCCFF"> <div align="right">
          <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
          <input name="add" type="submit" id="add" value="hinzuf&uuml;gen">
        </div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p><br>
    <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
    <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
    <strong><font color="#FF0000"></font></strong> 
    <?php } // Show if recordset empty ?>
  </p>
  <input type="hidden" name="MM_insert" value="form2">
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
Anzeige von Messwert <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?> <br>
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="10"><strong>ID</strong></td>
    <td width="80"><strong>Datum</strong></td>
    <td width="100"><div align="right"><strong><?php echo $row_rst3['wert1text']; ?></strong></div></td>
    <td width="100"><div align="right"><strong><?php echo $row_rst3['wert2text']; ?></strong></div></td>
    <td width="100"><div align="right"><strong><?php echo $row_rst3['wert3text']; ?></strong></div></td>
    <td width="100"><div align="right"><strong><?php echo $row_rst3['wert4text']; ?></strong></div></td>
    <td width="100"><div align="right"><strong>ISTwert</strong></div></td>
    <td width="100"><div align="right"><strong>SOLLwert</strong></div></td>
    <td width="100"><div align="right"><strong>Abweichung</strong></div></td>
    <td width="27"><div align="right"><strong>lokz</strong></div></td>
    <td width="0"><div align="right"><strong>User</strong></div></td>
    <td width="100">&nbsp;</td>
    <td width="100"><div align="right"></div></td>
  </tr>
  <?php do { ?>
  <?php 

  
if ($row_rst3['formelid']==1){
			if ($row_rst2['messwert1']==0 || $row_rst2['messwert2']==0 || $row_rst2['messwert3']==0){
			$ist = "Error";
			}
			else
			{
			$ist=($row_rst2['messwert4']*$row_rst3['Umrechnungsfaktor'])/($row_rst2['messwert1']* $row_rst2['messwert2']*$row_rst2['messwert3']);
			}
		}
		
elseif ($row_rst3['formelid']==2){
		if ($row_rst2['messwert4']==0){$row_rst2['messwert4']=1;}
		$ist=$row_rst2['messwert1']/$row_rst2['messwert4']*$row_rst3['Umrechnungsfaktor'];
		
}else{
		$ist=$row_rst2['messwert1']*$row_rst3['Umrechnungsfaktor'];
		}

			
  if (round($row_rst2['messwert1'],2)==0) { $row_rst2['messwert1']=($row_rst2['messwert1']);}	else {$row_rst2['messwert1']=round($row_rst2['messwert1'],2);}
  if (round($row_rst2['messwert2'],2)==0) { $row_rst2['messwert2']=($row_rst2['messwert2']);}	else {$row_rst2['messwert2']=round($row_rst2['messwert2'],2);}
  if (round($row_rst2['messwert3'],2)==0) { $row_rst2['messwert3']=($row_rst2['messwert3']);}	else {$row_rst2['messwert3']=round($row_rst2['messwert3'],2);}
  if (round($row_rst2['messwert4'],2)==0) { $row_rst2['messwert4']=($row_rst2['messwert4']);}	else {$row_rst2['messwert4']=round($row_rst2['messwert4'],2);}
  if (round($row_rst2['messwert5'],2)==0) { $row_rst2['messwert5']=($row_rst2['messwert5']);}	else {$row_rst2['messwert5']=round($row_rst2['messwert5'],2);}
			
?>
  <form name="form1" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="-7"><?php echo $row_rst2['id_pruefd']; ?></font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="-7">&nbsp;<?php echo $row_rst2['datum']; ?> </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><?php echo $row_rst2['messwert1']; ?> <?php echo $row_rst3['wert1unit']; ?></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><?php echo $row_rst2['messwert2']; ?> <?php echo $row_rst3['wert2unit']; ?></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><?php echo $row_rst2['messwert3']; ?> <?php echo $row_rst3['wert3unit']; ?></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><?php echo $row_rst2['messwert4']; ?><?php echo $row_rst3['wert4unit']; ?></font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><?php echo round($ist,2) ?><?php echo $row_rst3['einheit'];?>&nbsp; </font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><?php echo round($row_rst3['soll'],2); ?> <?php echo $row_rst3['einheit']; ?>&nbsp;</font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><font size="-7"><?php echo round($ist-$row_rst3['soll'],2) ?>&nbsp; 
          <?php echo $row_rst3['einheit']; ?>&nbsp;</font></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <font size="-7"> 
        <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/iconchance_16x16.gif" width="16" height="16">
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst4['id'], $row_rst2['user']))) {echo $row_rst4['name'];}?> 
        <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <font size="-7"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_pruef" type="hidden" id="hurl_id_pruef" value="<?php echo $row_rst2['id_pruef']; ?>">
          <input name="hurl_id_pruefd" type="hidden" id="hurl_id_pruefd" value="<?php echo $row_rst2['id_pruefd']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
          </font></div></td>
    </tr>
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>


<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
  Anzeige von Messwert <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?></p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Messwerte angelegt.</p>
<?php } // Show if recordset not empty ?>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);
?>
</p>

  <?php include("footer.tpl.php"); ?>
