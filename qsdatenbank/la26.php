<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la26";
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
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE  (artikeldaten.bks=0 or artikeldaten.bks='$row_rst1[werk]' ) and  artikeldaten.aktiviert=1 ORDER BY artikeldaten.Gruppe asc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["neu"])) ) {
$updateGoTo = "la261.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>



  
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong>la26 Bestand&uuml;bersicht</strong></td>
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
    <td rowspan="3"><img src="picture/icon_gr_fertigungsmeldung.gif" width="55" height="75"></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
  Liste der Artikeltypen<br>
  
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr class="value"> 
    <td width="120"><em><strong>Bezeichnung</strong></em></td>
    <td width="142">StL</td>
    <td width="142"><em><strong>Materialnummer</strong></em></td>
    <td width="94"><em><strong>Bestand</strong></em></td>
    <td width="103"><em><strong>Reservierung</strong></em></td>
    <td width="103"><em><strong>Vorkontierung</strong></em></td>
    <td width="168">Preis</td>
    <td width="168">Bestandwert</td>
    <td width="168">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php   if ($row_rst2['Gruppe']!=$gruppe){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="12" nowrap="nowrap"    bgcolor="#EEEEEE"><em><br>
      <?php echo $row_rst2['Gruppe']; ?></em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Bezeichnung']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if ($row_rst2['stuli']>1){ ?>
        <img src="picture/pattern.gif" width="16" height="16"> 
        <?php } ?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Nummer']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Bestand']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Reservierung']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Kontierung']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Einzelpreis']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right">
        <?php $lagerwert=$row_rst2['Einzelpreis']*$row_rst2['Bestand'];
	  echo ($lagerwert); 
	  $summelagerwert=$summelagerwert+$lagerwert;
	  ?>
        &euro; </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="neu" type="submit" id="neu" value="Materialbewegung">
        </div></td>
    </tr>
  </form>
  <?php
    $gruppe=$row_rst2['Gruppe'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><strong><?php echo $summelagerwert; ?>&nbsp;&euro;</strong></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
  </tr>
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

?>
<?php include("footer.tpl.php"); ?>
