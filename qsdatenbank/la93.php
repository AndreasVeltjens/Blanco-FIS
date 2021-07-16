<?php require_once('Connections/qsdatenbank.php'); ?><?php
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

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE userright SET la=%s, `user`=%s, datum=%s, lokz=%s WHERE urid=%s",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_urid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la95.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la96";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

$maxRows_rst2 = 20;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM userright WHERE userright.urid='$url_urid'";
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
$updateGoTo = "la95.php?url_user=".$row_rst1['id']."&url_upid=".$HTTP_POST_VARS["hurl_upid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?> 
<form action="<?php echo $editFormAction; ?>" name="form2" method="POST">
  
  <table width="614" border="0" cellspacing="0" cellpadding="0">
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
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="218"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="274"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hupid" type="hidden" id="hupid" value="<?php echo $row_rst2['upid']; ?>">
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Zum Bearbeiten eines Benutzerrechte alle Felder 
  ausf&uuml;llen.</font></strong> <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
  <table width="614" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="145">ID</td>
      <td width="466"><?php echo $row_rst2['urid']; ?>&nbsp; wird automatisch vergeben.</td>
      <td width="3">&nbsp;</td>
    </tr>
    <tr> 
      <td>Name</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_rst2['la']; ?>" size="50"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="24">Datum</td>
      <td><input name="datum" type="text" id="datum" value="<?php echo $row_rst2['datum']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>User</td>
      <td><?php echo $row_rst2['user']; ?>wird automatisch vergeben.</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schen Kennzeichen</td>
      <td> <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Profil-ID</td>
      <td><?php echo $row_rst2['upid']; ?></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  
  
  <input type="hidden" name="MM_update" value="form2">
  <input name="hurl_user" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>">
  <input name="hurl_upid" type="hidden" id="hurl_upid" value="<?php echo $row_rst2['upid']; ?>">
  <input name="hurl_urid" type="hidden" id="hurl_urid" value="<?php echo $row_rst2['urid']; ?>">
  <input name="hurl_urid" type="hidden" id="hurl_urid" value="<?php echo $row_rst2['urid']; ?>">
</form>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);
?>
</p>

  <?php include("footer.tpl.php"); ?>
