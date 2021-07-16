<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/psdatenbank.php'); ?>
<?php require_once('Connections/psdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "hr13";
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

if ((isset($HTTP_POST_VARS["add"] ))) {
  $insertSQL = sprintf("INSERT INTO pramien (von, bis, datum, erstellt, pramie, gruppe) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['von'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['bis'], "date"),
                      " now() ",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['textfield'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['bewertungsgruppe'], "text"));

  mysql_select_db($database_psdatenbank, $psdatenbank);
  $Result1 = mysql_query($insertSQL, $psdatenbank) or die(mysql_error());
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

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst2 = "SELECT * FROM pramien ORDER BY pramien.gruppe, pramien.bis asc";
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $psdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

if (isset($HTTP_GET_VARS['totalRows_rst2'])) {
  $totalRows_rst2 = $HTTP_GET_VARS['totalRows_rst2'];
} else {
  $all_rst2 = mysql_query($query_rst2);
  $totalRows_rst2 = mysql_num_rows($all_rst2);
}
$totalPages_rst2 = ceil($totalRows_rst2/$maxRows_rst2)-1;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT `user`.id, `user`.name FROM `user`";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);



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
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "hr13.php?url_user=".$row_rst1['id']."&url_usid=".$HTTP_POST_VARS["hurl_usid"]."&url_pra_id=".$HTTP_POST_VARS["hurl_pra_id"];
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
      <td width="374"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <img src="picture/b_drop.png" width="16" height="16"> </td>
      <td width="211"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="2"><em>neue Bewertung anlegen</em></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Bewertungsgruppe</td>
      <td><select name="bewertungsgruppe" id="bewertungsgruppe">
          <?php
do {  
?>
          <option value="<?php echo $row_rst6['id']?>"<?php if (!(strcmp($row_rst6['id'], $row_rst5['gruppe']))) {echo "SELECTED";} ?>><?php echo $row_rst6['name']?></option>
          <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </select></td>
      <td><div align="right"> </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Zeitraum von</td>
      <td><input name="von" type="text" id="von" value="<?php echo date("Y-m-d",time()-90*60*60*24); ?>" size="20" maxlength="20">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'von\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        bis 
        <input name="bis" type="text" id="bis" value="<?php echo date("Y-m-d",time()); ?>" size="20" maxlength="20">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'bis\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script> </td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Pr&auml;mie</td>
      <td> <input name="textfield" type="text"> <input name="huser_id" type="hidden" id="huser_id" value="<?php echo $row_rst1['id']; ?>"></td>
      <td> <div align="right"> 
          <input name="setback" type="reset" id="setback" value="zur&uuml;cksetzen">
          <input name="add" type="submit" id="add" value="Anlegen">
        </div></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
  <input type="hidden" name="MM_insert" value="form2">
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="81"><em><strong>Pr&auml;mien-ID</strong></em></td>
    <td width="141"><em><strong>Zeitraum</strong></em></td>
    <td width="76"><div align="center"><em><strong>lokz</strong></em></div></td>
    <td width="131"><em>Pr&auml;mie</em></td>
    <td width="138"><em> Gruppe</em></td>
    <td width="163">
<div align="right">Aktion</div></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
  <?php if ($row_rst2['gruppe']!=$group){
 
     ?>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="5" nowrap="nowrap"><img src="picture/s_tbl.png" width="16" height="16"> 
        <em>Gruppe: 
		
		<?php
do {  
?>
    <?php if (!(strcmp($row_rst6['id'], $row_rst2['gruppe']))) {echo $row_rst6['name']; }
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </em></td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
	<?php }?>
	
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/bssctoc1.gif" width="32" height="16"><?php echo $row_rst2['pra_id']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp; 
        <?php echo $row_rst2['von']; ?>- <?php echo $row_rst2['bis']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center">
          <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1">
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['pramie']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['gruppe']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_pra_id" type="hidden" id="hurl_pra_id" value="<?php echo $row_rst2['pra_id']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit2" value="bearbeiten">
		  
		  
        </div></td>
    </tr>
  </form>
  <?php $group = $row_rst2['gruppe'];?>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  
</table>
<?php } // Show if recordset not empty ?>
<br>
&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Pr&auml;mienberechnungen angelegt.</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst6);


?>
</p>

  <?php include("footer.tpl.php"); ?>
