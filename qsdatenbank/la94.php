<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la94";
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

if ((isset($HTTP_POST_VARS["save"])) ) {
  $updateSQL = sprintf("UPDATE user SET username=%s, passwort=%s, name=%s, recht=%s, aktiviert=%s, geburtstag=%s, idcard=%s, idzeitausweis=%s, rst611401=%s, rst611402=%s, rst611403=%s, rst611404=%s, rst611405=%s, rst611406=%s, rst611407=%s, rst611408=%s, rst611301=%s, rst611302=%s, rst611303=%s, rst611101=%s , rst611102=%s WHERE id=%s",
                       GetSQLValueString($HTTP_POST_VARS['benutzername'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['passwort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['right'], "int"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['idcard'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['idzeitausweis'], "text"),
					   GetSQLValueString(isset($HTTP_POST_VARS['rst611401']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611402']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611403']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611404']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611405']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611406']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611407']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611408']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611301']) ? "true" : "", "defined","1","0"),
					GetSQLValueString(isset($HTTP_POST_VARS['rst611302']) ? "true" : "", "defined","1","0"),
					GetSQLValueString(isset($HTTP_POST_VARS['rst611303']) ? "true" : "", "defined","1","0"),
					
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611101']) ? "true" : "", "defined","1","0"),
					 GetSQLValueString(isset($HTTP_POST_VARS['rst611102']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['husid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la99.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["kopieren"])) ) {
  $insertSQL = sprintf("INSERT INTO user (username, passwort, name, recht, aktiviert, idcard, rst611401, rst611402, rst611403, rst611404, rst611405, rst611406, rst611407, rst611408, rst611301, rst611101, idzeitausweis) VALUES (%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['benutzername'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['passwort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['right'], "int"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['idcard'], "text"),
					GetSQLValueString(isset($HTTP_POST_VARS['rst611401']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611402']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611403']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611404']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611405']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611406']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611407']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611408']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611301']) ? "true" : "", "defined","1","0"),
                    GetSQLValueString(isset($HTTP_POST_VARS['rst611101']) ? "true" : "", "defined","1","0"),				   
					   
                       GetSQLValueString($HTTP_POST_VARS['idzeitausweis'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $updateGoTo = "la99.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


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
$query_rst2 = "SELECT * FROM `user` WHERE `user`.id='$url_usid'";
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
$query_rst3 = "SELECT userprofile.upid, userprofile.name FROM userprofile ORDER BY userprofile.name";
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
$updateGoTo = "la99.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>


<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
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
          <input name="kopieren" type="submit" id="kopieren" value="Kopieren">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="husid" type="hidden" id="husid" value="<?php echo $row_rst2['id']; ?>">
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
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="172">ID</td>
      <td width="367">&nbsp; <?php echo $row_rst2['id']; ?></td>
      <td width="191">&nbsp;</td>
    </tr>
    <tr> 
      <td>Name</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_rst2['name']; ?>" size="50"></td>
      <td rowspan="9"><div align="center"><img src="user/<?php echo $row_rst2['idcard'] ?>.jpg" width="200"></div></td>
    </tr>
    <tr> 
      <td height="24">Geburtstag</td>
      <td><input name="datum" type="text" id="datum" value="<?php echo $row_rst2['geburtstag']; ?>"></td>
    </tr>
    <tr> 
      <td>angelegt von User-ID</td>
      <td><?php echo $row_rst2['user']; ?></td>
    </tr>
    <tr> 
      <td>User aktiviert</td>
      <td> <input <?php if (!(strcmp($row_rst2['aktiviert'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox"></td>
    </tr>
    <tr> 
      <td>ID Card- FIS</td>
      <td><input name="idcard" type="text" id="idcard" value="<?php echo $row_rst2['idcard']; ?>"></td>
    </tr>
    <tr> 
      <td>ID Zeitausweis-SAP</td>
      <td><input name="idzeitausweis" type="text" id="idzeitausweis" value="<?php echo $row_rst2['idzeitausweis']; ?>"></td>
    </tr>
    <tr> 
      <td>Benutzername</td>
      <td><input name="benutzername" type="text" id="benutzername" value="<?php echo $row_rst2['username']; ?>"></td>
    </tr>
    <tr> 
      <td>Passwort</td>
      <td><input name="passwort" type="text" id="passwort" value="<?php echo $row_rst2['passwort']; ?>"></td>
    </tr>
    <tr> 
      <td>Rechtesystem nach</td>
      <td><select name="right" id="right">
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['upid']?>"<?php if (!(strcmp($row_rst3['upid'], $row_rst2['recht']))) {echo "SELECTED";} ?>><?php echo $row_rst3['name']?></option>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </select></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><img src="picture/rechnerschutz.gif" width="52" height="36"></td>
      <td><img src="picture/zugang.png" width="53" height="36"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><strong>Montage</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>LZ611401</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611401'],1))) {echo "checked";} ?> type="checkbox" name="rst611401" value="1"></td>
      <td>Montage</td>
    </tr>
    <tr> 
      <td>LZ611402</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611402'],1))) {echo "checked";} ?> type="checkbox" name="rst611402" value="1"></td>
      <td>Heizmodul</td>
    </tr>
    <tr> 
      <td>LZ611403</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611403'],1))) {echo "checked";} ?> type="checkbox" name="rst611403" value="1"></td>
      <td>FAUF-Freigabe</td>
    </tr>
    <tr> 
      <td>LZ611404</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611404'],1))) {echo "checked";} ?> type="checkbox" name="rst611404" value="1"></td>
      <td>Kommissionierwagen Ersatzteile</td>
    </tr>
    <tr> 
      <td>LZ611405</td>
      <td><input <?php if (!(strcmp($row_rst2['rst611405'],1))) {echo "checked";} ?> type="checkbox" name="rst611405" value="1"></td>
      <td>Kundenreparartur</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><strong>Reparatur</strong></td>
      <td>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>LZ611406</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611406'],1))) {echo "checked";} ?> type="checkbox" name="rst611406" value="1"></td>
      <td>Pr&uuml;fplatz Elektromontage</td>
    </tr>
    <tr> 
      <td>LZ611407</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611407'],1))) {echo "checked";} ?> type="checkbox" name="rst611407" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>LZ611408</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611408'],1))) {echo "checked";} ?> type="checkbox" name="rst611408" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><font color="#000000"><strong>Sch&auml;umen</strong></font></td>
      <td><font color="#000000">&nbsp;</font></td>
      <td><font color="#000000">&nbsp;</font></td>
    </tr>
    <tr> 
      <td>LZ611301</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611301'],1))) {echo "checked";} ?> type="checkbox" name="rst611301" value="1"></td>
      <td>Maschinenbuch PU80P</td>
    </tr>
    <tr> 
      <td>LZ611302</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611302'],1))) {echo "checked";} ?> type="checkbox" name="rst611302" value="1"></td>
      <td>Maschinenbuch Schwei&szlig;anlagen</td>
    </tr>
    <tr> 
      <td>LZ611303</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611303'],1))) {echo "checked";} ?> type="checkbox" name="rst611303" value="1"></td>
      <td>Maschinenbuch Fr&auml;sanlage</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><strong>Thermoformen</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>LZ611101</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611101'],1))) {echo "checked";} ?> type="checkbox" name="rst611101" value="1"></td>
      <td>Maschinenbuch T8</td>
    </tr>
    <tr> 
      <td>LZ611102</td>
      <td> <input <?php if (!(strcmp($row_rst2['rst611102'],1))) {echo "checked";} ?> type="checkbox" name="rst611102" value="1"></td>
      <td>Maschinenbuch T5+T6</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2">
  <input type="hidden" name="MM_insert" value="form2">
</form>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
