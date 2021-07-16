<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "vis2";
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

if (isset($HTTP_POST_VARS['gruppe'])&&($HTTP_POST_VARS['gruppe']>0)){
$gruppe="and fhm.id_fhm_gruppe='".$HTTP_POST_VARS[gruppe]."' ";
} else {$gruppe="";}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen_daten WHERE kennzahlen_daten.id_kz='$url_id_kz' ORDER BY kennzahlen_daten.wertx desc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM kennzahlen WHERE kennzahlen.id_kz='$url_id_kz'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?> 
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="728" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo utf8_decode($Modul[$d][0])." "; echo utf8_decode($Modul[$d][1]); ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?>
      </td>
    </tr>
    <tr> 
      <td width="124">Aktionen W&auml;hlen:</td>
      <td width="270"> <img src="picture/voip.gif" width="52" height="36"></td>
      <td width="334"> <div align="right">Visualisierung: <?php echo utf8_decode($row_rst3['gruppe']); ?> - Werk:<?php echo $row_rst3['werk']; ?> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>Kennzahl </td>
      <td colspan="2"><strong><font size="5"><?php echo utf8_decode($row_rst3['name']); ?></font></strong></td>
    </tr>
    <tr> 
      <td>Beschreibung</td>
      <td colspan="2"><?php echo utf8_decode($row_rst3['beschreibung']); ?></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar. Bitte Visualisierungsdaten 
  pr&uuml;fen. </font></strong> <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    
  
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<img src="<?php echo $row_rst3['diagrammtyp']; ?>.php?url_id_kz=<?php echo $url_id_kz ?>">
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td bgcolor="#FFFFCC">&nbsp;</td>
    <td bgcolor="#FFFFCC"><div align="center"><?php echo utf8_decode($row_rst3['yachse']); ?> 
        &nbsp;&nbsp; <?php echo utf8_decode($row_rst3['yachse2']); ?></div></td>
  </tr>
  <tr> 
    <td width="46" bgcolor="#FFFFCC"><?php echo  utf8_decode($row_rst3['xachse']); ?></td>
    <td width="500"> <table width="682" border="0" cellspacing="1" cellpadding="1">
        <?php $i=1;
		do { 
		$i=$i+1;
		$d=intval($i/2);
		?>
        <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?>> 
          <td width="85"><?php echo $row_rst2['wertx']; ?></td>
          <td width="580"> 
            <table width="580" border="0" cellspacing="1" cellpadding="0">
              <tr> 
                <td><strong><img src="picture/g_blue.png" width="<?php echo $row_rst2['wert1y']*$row_rst3['anzeige']; ?>; ?>" height="15"> 
                  <?php echo htmlentities(utf8_decode($row_rst2['wert1y'])); ?></strong></td>
              </tr>
              <tr> 
                <td><img src="picture/g_orange.png" width="<?php echo $row_rst2['wert2y']*$row_rst3['anzeige']; ?>" height="10"> 
                  <font color="#FF9900"><?php echo htmlentities(utf8_decode($row_rst2['wert2y'])); ?></font></td>
              </tr>
			  <?php if ($row_rst2['wert3y']!=" " ){?>
              <tr> 
                <td><img src="picture/g_grey.png" width="<?php echo $row_rst2['wert3y']*$row_rst3['anzeige']; ?>" height="10"> 
                  <font color="#999999"><?php echo htmlentities(utf8_decode($row_rst2['wert3y'])); ?></font></td>
              </tr>
			  <?php } ?>
            </table></td>
        </tr>
        <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
        <tr> 
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFCC">&nbsp;</td>
    <td bgcolor="#FFFFCC"><div align="center"><?php echo  utf8_decode($row_rst3['yachse']); ?></div></td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
<br>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>

<?php include("footer.tpl.php"); ?>
