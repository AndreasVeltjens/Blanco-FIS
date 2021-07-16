<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "vis44";
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
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><strong><?php echo utf8_decode($Modul[$d][0])." "; echo utf8_decode($Modul[$d][1]); ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="124">&nbsp;</td>
      <td width="270">&nbsp; </td>
      <td width="334"> <div align="right">Visualisierung: <?php echo utf8_decode($row_rst3['gruppe']); ?> - Werk:<?php echo $row_rst3['werk']; ?> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center"><img src="picture/dokument.gif" width="50" height="50"></div></td>
      <td colspan="2"><strong><font size="5" face="Arial, Helvetica, sans-serif">unsere 
        Qualit&auml;tspolitik</font></strong></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"></td>
    </tr>
    <tr> 
      <td><div align="left"> 
          <p><br>
          </p>
        </div></td>
      <td colspan="2"><p><font color="#333333" size="5">Unser Anspruch als Qualit&auml;tsf&uuml;hrer 
          beinhaltet h&ouml;chste <br>
          Leistungsbereitschaft vom ersten bis zum letzten Kontakt mit <br>
          unseren Kunden und Gesch&auml;ftspartnern.</font></p>
        <p><font color="#333333" size="5">&quot;Made by BLANCO&quot; steht f&uuml;r<strong><br>
          <br>
          - innovative Produkte<br>
          - Gebrauchssicherheit<br>
          - Langlebigkeit<br>
          - Funktionalit&auml;t<br>
          - &Auml;sthetik<br>
          - Serviceorientierung<br>
          <br>
          </strong>Wir wollen zufriedene Kunden, die sich auf unsere <br>
          Qualit&auml;t verlassen k&ouml;nnen. </font></p></td>
    </tr>
  </table>
  <br>
  
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>
</form>
  
    
  
<?php include("footer.tpl.php"); ?>
