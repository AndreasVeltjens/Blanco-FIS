<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "co11";
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

if (isset($HTTP_POST_VARS['suchtext'])){
$suchtxt=" AND (arbeitsplatz.arb_name like '%$HTTP_POST_VARS[suchtext]%' or arbeitsplatz.arb_kz ='$HTTP_POST_VARS[suchtext]' )";
} else {$suchtxt="";}

mysql_select_db($database_qsdatenbabk, $qsdatenbank);
$query_rst2 = "SELECT * FROM arbeitsplatz WHERE arbeitsplatz.lokz=0 $suchtxt ORDER BY arbeitsplatz.arb_kostenstelle asc ";
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
$updateGoTo = $HTTP_POST_VARS["hurl_vis"].".php?url_user=".$row_rst1['id']."&url_arb_id=".$HTTP_POST_VARS["hurl_artikelid"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<script language="JavaScript" type="text/JavaScript">
function sf(){document.form2.suchtext.focus()}
</script> 

  
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong>co11-Fertigungsmeldungen nach Arbeitsplatz</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr><form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <tr> 
    <td width="152">Aktionen W&auml;hlen:</td>
    <td width="339"> 
        <img src="picture/error.gif" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
    <td width="123" rowspan="4"> <div align="right"><img src="picture/icon_gr_fertigungsmeldung.gif" width="55" height="75"> 
      </div></td>
  </tr>
  <tr> 
    <td rowspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>Suche:<img src="picture/icon_scan.gif" alt="Dieses Feld ist Barcodelesef&auml;hig"> 
      <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>"> 
      <input type="submit" name="Submit" value="finden"></td>
  </tr></form>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="630" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td>Liste der Artikeltypen </td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><em><strong>Arbeitsplatz - Kostenstelle</strong></em></td>
    <td><em><strong>Arbeitsplatz - Kostenstelle</strong></em></td>
  </tr>
  <tr> 
    <td width="300"> <table width="255" border="0" cellpadding="1" cellspacing="0">
        <?php $i=0;?>
        <?php do { 
		
				if (round($totalRows_rst2/2,0)==$i){
				 ?>
      </table></td>
    <td> <table width="325" border="0" cellpadding="0" cellspacing="1">
        <?php }?>
        <?php   if ($row_rst2['arb_kostenstelle']!=$gruppe){?>
        <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
          <td colspan="8" nowrap="nowrap"    bgcolor="#EEEEEE"><em><br>
            <?php echo $row_rst2['arb_kostenstelle']; ?></em></td>
        </tr>
        <?php 	}?>
        <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/b_newdb.png" width="16" height="16"> 
              <img src="../documents/arbeitsplatzbilder/<?php echo $row_rst2['arb_kz'] ?>.png" width="80" height="80"> </td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['arb_name']; ?><br>
                <font size="1"><?php echo $row_rst2['arb_kz']; ?></font></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"></div></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap">
<input name="neu" type="submit" id="neu" value="bearbeiten"></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"> 
                <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
                <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['arb_id']; ?>">
                <input name="hurl_vis" type="hidden" id="hurl_vis" value="<?php echo $row_rst2['arb_visualisierungstyp']; ?>">
              </div></td>
          </tr>
        </form>
        <?php
    $gruppe=$row_rst2['arb_kostenstelle'];
	
	$i=$i+1;
	
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
      </table></td>
  </tr>
</table>
<br>
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
