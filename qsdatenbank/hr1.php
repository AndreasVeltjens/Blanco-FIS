<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/psdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "hr1";
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

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM `user` ORDER BY `user`.werk, `user`.gruppe, `user`.name";
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT `user`.id, `user`.name FROM `user`";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

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
$updateGoTo = "hr12.php?url_user=".$row_rst1['id']."&url_usid=".$HTTP_POST_VARS["hurl_usid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["schulungedit"])) ) {
$updateGoTo = "hr22.php?url_user=".$row_rst1['id']."&url_usid=".$HTTP_POST_VARS["hurl_usid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["personalakte"])) ) {
$updateGoTo = "hr3.php?url_user=".$row_rst1['id']."&url_usid=".$HTTP_POST_VARS["hurl_usid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["profile"])) ) {
$updateGoTo = "hr14.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["schulung"])) ) {
$updateGoTo = "hr23.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["tatigkeit"])) ) {
$updateGoTo = "hr4.php?url_user=".$row_rst1['id'];
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
      <td width="81"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <img src="picture/b_drop.png" width="16" height="16"> </td>
      <td width="504"> <div align="right"><img src="picture/iconFixedprice_16x16.gif" width="16" height="16"> 
          <input name="tatigkeit" type="submit" id="tatigkeit" value="T&auml;tigkeitsmatrix">
          <img src="picture/Cfchart.gif" width="18" height="18"> 
          <input name="schulung" type="submit" id="schulung" value="Schulung">
          <img src="picture/pattern.gif" width="16" height="16"> 
          <input name="profile" type="submit" id="profile" value="Bewertung">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>"></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="73"><em><strong>Benutzer-ID</strong></em></td>
    <td width="112"><em><strong>Name</strong></em></td>
    <td width="83"><em><strong>aktiv/ Status</strong></em></td>
    <td width="90"><em><strong>Profil/Rolle</strong></em></td>
    <td width="107"><em><strong>Anmeldename</strong></em></td>
    <td width="265">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
  <?php if ($row_rst2['werk']!=$group){
 
     ?>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="5" nowrap="nowrap"><img src="picture/s_tbl.png" width="16" height="16"> 
        <em>Werk: <?php echo $row_rst2['werk']; ?> </em></td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
	<?php $group2="";?>
	<?php }?>
	<?php if ($row_rst2['gruppe']!=$group2){
 
     ?>
	<tr bgcolor="#CCCCCC"> 
      <td colspan="5" nowrap="nowrap"> 
        - <img src="picture/s_tbl.png" width="16" height="16"> <em>Vorgesetzter: 
        <img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst4['id'], $row_rst2['gruppe']))) {echo $row_rst4['name'];}?>
        <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </em></td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr><?php }?>
	
	
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfetihr2.php?url_user_id=<?php echo $row_rst2['id'] ?>" target="_blank"><img src="picture/anschreiben.gif" width="18" height="18" border="0"></a><?php echo $row_rst2['id']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16">&nbsp; 
        <?php echo $row_rst2['name']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input <?php if (!(strcmp($row_rst2['aktiviert'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox">
        <?php 
		
		mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst3 = "SELECT bewertung.id_bew FROM bewertung WHERE bewertung.id_user='$row_rst2[id]' and bewertung.datum >=date(curdate()-14)";
$rst3 = mysql_query($query_rst3, $psdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


		
		?>
		
		
		<?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
        <img src="picture/st1.gif" alt="letzte Bewertung innerhalb der letzten Tage durchgef&uuml;hrt" width="15" height="15"> 
        <?php } // Show if recordset not empty ?> 
		<?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
        <img src="picture/st3.gif" alt="letzte Bewertung &auml;lter als 14 Tage" width="15" height="15">
        <?php } // Show if recordset empty ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['recht']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst2['username']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="personalakte" type="submit" id="schulungedit" value="Personalakte">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_usid" type="hidden" id="hurl_usid" value="<?php echo $row_rst2['id']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="schulungedit" type="submit" id="edit4" value="Schulung">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit2" value="Bewertung">
		  
		  <?php mysql_free_result($rst3); ?>
        </div></td>
    </tr>
  </form>
  <?php $group = $row_rst2['werk'];?>
  <?php $group2 = $row_rst2['gruppe'];?>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  
</table>
<?php } // Show if recordset not empty ?>

<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
</p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Benutzer angelegt.</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst4);


?>
</p>

  <?php include("footer.tpl.php"); ?>
