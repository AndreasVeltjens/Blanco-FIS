<?php require_once('Connections/qsdatenbank.php'); ?>
<?php  $la = "la23";
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

if ((isset($HTTP_POST_VARS["new"] ))) {
  $insertSQL = sprintf("INSERT INTO linkfehlerartikel (fid, artikelid, lfauser) VALUES (%s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['fehler'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['artikel'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM linkfehlerartikel WHERE linkfehlerartikel.artikelid = '$url_artikelid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung FROM artikeldaten";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM fehler WHERE fehler.lokz  =0 ORDER BY fehler.fname";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la2.php?url_user=".$row_rst1['id'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la22.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_id=".$HTTP_POST_VARS["hurl_id"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["add"])) ) {
$updateGoTo = "la23.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_id=".$HTTP_POST_VARS["hurl_id"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["err"])) ) {
$updateGoTo = "la24.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>


<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="702" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="152">Aktionen W&auml;hlen:</td>
      <td width="339"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="123"> <div align="right">
          <input name="err" type="submit" id="err" value="Fehlerlisten">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Artikel</td>
      <td> <select name="artikel" id="artikel">
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $url_artikelid))) {echo "SELECTED";} ?>><?php echo $row_rst4['Bezeichnung']?></option>
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
      <td>Fehlerauswahl:</td>
      <td> <select name="fehler" id="fehler">
          <?php
do {  
?>
          <option value="<?php echo $row_rst5['fid']?>"><?php echo $row_rst5['fname']?></option>
          <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
        </select></td>
      <td><div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="new" type="submit" id="new" value="Fehler zum Artikel zuordnen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>
<input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
</form>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Fehlerzuordnung<br>
 
  
<table width="719" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="105"><em><strong>Bezeichnung</strong></em></td>
    <td width="179"><em><strong>Artikel</strong></em></td>
    <td width="81"><strong><em>Benutzer</em></strong></td>
    <td width="95">ID</td>
    <td width="100">lokz</td>
    <td width="159"><div align="right"></div></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> <td    bgcolor="#EEEEEE" nowrap="nowrap">
        <select name="select3">
          <?php
do {  
?>
          <option value="<?php echo $row_rst5['fid']?>"<?php if (!(strcmp($row_rst5['fid'], $row_rst2['fid']))) {echo "SELECTED";} ?>><?php echo $row_rst5['fname']?></option>
          <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
        </select></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <select name="select4">
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $row_rst2['artikelid']))) {echo "SELECTED";} ?>><?php echo $row_rst4['Bezeichnung']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['id']?>"<?php if (!(strcmp($row_rst3['id'], $row_rst2['lfauser']))) {echo "SELECTED";} ?>><?php echo $row_rst3['name']?></option>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </select></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['lfaid']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="1"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $row_rst2['id']; ?>">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
  </form></tr>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
 
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

mysql_free_result($rst5);
?>
<?php include("footer.tpl.php"); ?>
