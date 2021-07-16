<?php require_once('Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "hr3";
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
  $updateSQL = sprintf("UPDATE user SET name=%s, recht=%s, aktiviert=%s, werk=%s, geburtstag=%s, gruppe=%s, personalnummer=%s, anrede=%s, anrede2=%s WHERE id=%s",
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['right'], "int"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['geburtstag'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['gruppe'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['personalnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anrede'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anrede2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['husid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "hr1.php";
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT `user`.id, `user`.name FROM `user`";
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
$updateGoTo = "hr1.php?url_user=".$row_rst1['id'];
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
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="218"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="274"> <div align="right"> 
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
      <td width="294"><?php echo $row_rst2['id']; ?></td>
      <td width="264"><a href="pdfetihr2.php?url_user_id=<?php echo $row_rst2['id'] ?>" target="_blank"><img src="picture/anschreiben.gif" width="18" height="18" border="0">ID-Card 
        drucken</a></td>
    </tr>
    <tr> 
      <td>Name</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_rst2['name']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="24">Datum angelegt am</td>
      <td><input name="datum" type="text" id="datum" value="<?php echo $row_rst2['datum']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script> </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>angelegt von User-ID</td>
      <td><?php echo $row_rst2['user']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>User aktiviert</td>
      <td> <input <?php if (!(strcmp($row_rst2['aktiviert'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Vorgesetzter</td>
      <td> <select name="gruppe" id="gruppe">
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['id']?>"<?php if (!(strcmp($row_rst4['id'], $row_rst2['gruppe']))) {echo "SELECTED";} ?>><?php echo $row_rst4['name']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Geburtstag</td>
      <td> <input name="geburtstag" type="text" id="geburtstag" value="<?php echo $row_rst2['geburtstag']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'geburtstag\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script> </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Werk</td>
      <td> <input name="werk" type="text" id="werk" value="<?php echo $row_rst2['werk']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Personalnummer</td>
      <td> <input name="personalnummer" type="text" id="personalnummer" value="<?php echo $row_rst2['personalnummer']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Anrede</td>
      <td>
<select name="anrede" id="anrede">
          <option value="Herr" <?php if (!(strcmp("Herr", $row_rst2['anrede']))) {echo "SELECTED";} ?>>Herr</option>
          <option value="Frau" <?php if (!(strcmp("Frau", $row_rst2['anrede']))) {echo "SELECTED";} ?>>Frau</option>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Anrede Briefkopf</td>
      <td>
<input name="anrede2" type="text" id="anrede2" value="<?php echo $row_rst2['anrede2']; ?>"></td>
      <td>&nbsp;</td>
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
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2">
</form>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);
?>
</p>

  <?php include("footer.tpl.php"); ?>
