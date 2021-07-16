<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/psdatenbank.php'); ?>
<?php $la = "hr4";
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
  $insertSQL = sprintf("INSERT INTO taetigkeiten (bezeichnung, beschreibung, datum, werk) VALUES ( %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['werk'], "int"));

  mysql_select_db($database_psdatenbank, $psdatenbank);
  $Result1 = mysql_query($insertSQL, $psdatenbank) or die(mysql_error());
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst2 = "SELECT * FROM taetigkeiten ORDER BY taetigkeiten.bezeichnung";
$rst2 = mysql_query($query_rst2, $psdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "hr1.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "w2.php?url_user=".$row_rst1['id']."&url_id_work=".$HTTP_POST_VARS["hurl_id_work"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<table width="730" border="0" cellspacing="0" cellpadding="0">
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
    <td width="339"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </form></td>
    <td width="123" rowspan="4"> <div align="right"> </div></td>
  </tr>
  <tr> 
    <td rowspan="3"><img src="picture/iconFixedprice_16x16.gif" width="16" height="16"></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
  
<em>neue T&auml;tigkeit anlegen</em> 
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="728" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>Beschreibung</td>
      <td> <input name="beschreibung" type="text" id="beschreibung" size="50" maxlength="255"></td>
      <td><input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['name']; ?>"> 
        <input name="status" type="hidden" id="status" value="0"></td>
    </tr>
    <tr> 
      <td width="137">T&auml;tigkeitsbeschreibung<br> <br> <br> </td>
      <td width="347"> <textarea name="textarea" cols="50" rows="3"></textarea> 
      </td>
      <td width="244"> <div align="right"> 
          <input name="add" type="submit" id="add" value="anlegen">
        </div></td>
    </tr>
    <tr>
      <td>Werk</td>
      <td><input name="werk" type="text" id="werk"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>
<p>&nbsp;</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Meldungen<br>
  
<table width="731" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="138"><em><strong>Nummer</strong></em></td>
    <td width="308"><em><strong>Bezeichnung</strong></em></td>
    <td width="83"><em><strong>Seit</strong></em></td>
    <td width="82">&nbsp;</td>
    <td width="120">&nbsp;</td>
  </tr>
<?php $werk=0;?>
  <?php do { ?>
  <?php   if ($row_rst2['werk']!=$werk){?>
  <tr bgcolor="#CCCCCC" > 
    <td colspan="8" nowrap="nowrap"><em><br>
      <img src="picture/s_tbl.png" width="16" height="16"> Werk: <?php echo $row_rst2['werk']; ?></em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/bssctoc1.gif" width="32" height="16"> <?php echo $row_rst2['tat_id']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['bezeichnung']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['datum']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_id_work" type="hidden" id="hurl_id_work" value="<?php echo $row_rst2['id_work']; ?>">
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php
    $werk=$row_rst2['werk'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  <br>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

?>
</p>
<?php include("footer.tpl.php"); ?>
