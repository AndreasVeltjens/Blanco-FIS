<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la33";
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



if ((isset($HTTP_POST_VARS["insertartikel"]))) {
  $insertSQL = sprintf("INSERT INTO artikeldaten_entscheidung_link (id_ent, id_artikel, datum) VALUES (%s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_entid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['id_artikel'], "text"),
                       "now()");

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}











$maxRows_rst2 = 20;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM entscheidungsvarianten WHERE entscheidungsvarianten.entid='$url_entid'";
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
$query_rst3 = "SELECT entscheidung.entid, entscheidung.entname FROM entscheidung";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT artikeldaten.artikelid, artikeldaten.bezeichnung, artikeldaten.Nummer FROM artikeldaten  ORDER BY artikeldaten.Bezeichnung";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten_entscheidung_link.id_ent , artikeldaten_entscheidung_link.datum, artikeldaten_entscheidung_link.lokz FROM artikeldaten, artikeldaten_entscheidung_link WHERE artikeldaten.artikelid=artikeldaten_entscheidung_link.id_artikel and artikeldaten_entscheidung_link.id_ent='$url_entid'";
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
$updateGoTo = "la3.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}
if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la35.php?url_user=".$row_rst1['id']."&url_entid=".$HTTP_POST_VARS["hurl_entid"]."&url_varid=".$HTTP_POST_VARS["hurl_varid"];;
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["new"])) ) {
$updateGoTo = "la34.php?url_user=".$row_rst1['id']."&url_entid=".$HTTP_POST_VARS["hurl_entid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}






include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="763" border="0" cellspacing="0" cellpadding="0">
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
      <td width="148">Aktionen W&auml;hlen:</td>
      <td width="192"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="274"> <div align="right">
          <input name="hurl_entid" type="hidden" id="hurl_entid" value="<?php echo $url_entid; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="new" type="submit" id="new" value="Neue Variante anlegen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Prozess</td>
      <td><select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['entid']?>"<?php if (!(strcmp($row_rst3['entid'], $url_entid))) {echo "SELECTED";} ?>><?php echo $row_rst3['entname']?></option>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </select> </td>
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
<table width="915" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="72"><strong>ID</strong></td>
    <td width="98"><strong>Name</strong></td>
    <td width="249">Beschreibung</td>
    <td width="94"><div align="center">Kosten extern</div></td>
    <td width="94"><div align="center">Kosten intern</div></td>
    <td width="39"><strong>L&ouml;KZ</strong></td>
    <td width="90"><strong>Datum</strong></td>
    <td width="170">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['varid']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['varname']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(($row_rst2['varbeschreibung']),60,"<br>",200); ?> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['Kosten1']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['Kosten2']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst2['Datum']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_entid" type="hidden" id="hurl_entid" value="<?php echo $row_rst2['entid']; ?>">
          <input name="hurl_varid" type="hidden" id="hurl_varid" value="<?php echo $row_rst2['varid']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<?php } // Show if recordset not empty ?>

<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
</p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Varianten angelegt.</p>
<p>&nbsp;</p>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="763" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="148">Aktionen W&auml;hlen:</td>
      <td width="192">&nbsp; </td>
      <td width="274"> <div align="right"> 
          <input name="hurl_entid" type="hidden" id="hurl_entid" value="<?php echo $url_entid; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="insertartikel" type="submit" id="insertartikel" value="Neue Artikelzurordnung anlegen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Artikel</td>
      <td> 
        <select name="id_artikel" id="id_artikel">
          <?php
do {  
?>
          <option value="<?php echo $row_rst5['artikelid']?>"<?php if (!(strcmp($row_rst5['artikelid'], $url_entid))) {echo "SELECTED";} ?>><?php echo $row_rst5['bezeichnung']?> - <?php echo $row_rst5['Nummer']?></option>
          <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
        </select> </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
<?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
<table width="764" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="92"><strong>Varianten-ID</strong></td>
    <td width="151"><strong>Name</strong></td>
    <td width="98">&nbsp;</td>
    <td width="100">&nbsp;</td>
    <td width="46"><strong>L&ouml;KZ</strong></td>
    <td width="110"><strong>Datum</strong></td>
    <td width="167">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst4['artikelid']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst4['Bezeichnung']; ?> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst4['lokz']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <input <?php if (!(strcmp($row_rst4['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox2" value="1"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst4['datum']; ?> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user2" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_entid2" type="hidden" id="hurl_entid3" value="<?php echo $row_rst4['id_lae']; ?>">
          <input name="hurl_varid2" type="hidden" id="hurl_varid3" value="<?php echo $row_rst4['varid']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit2" type="submit" id="edit3" value="Bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
</table>
<?php } // Show if recordset not empty ?>
<p>&nbsp;<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, 0, $queryString_rst4); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, max(0, $pageNum_rst4 - 1), $queryString_rst4); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, min($totalPages_rst4, $pageNum_rst4 + 1), $queryString_rst4); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, $totalPages_rst4, $queryString_rst4); ?>">Last</a> 
</p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst4 ?> Varianten angelegt.</p>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst5);

mysql_free_result($rst4);
?>
</p>

  <?php include("footer.tpl.php"); ?>
