<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "vis4";
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
}else{$errtxt = "Aktion w?hlen. Bitte erneut versuchen.";}

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
      <td width="134">&nbsp;</td>
      <td width="395">&nbsp; </td>
      <td width="451"> <div align="right">Visualisierung: <?php echo utf8_decode($row_rst3['gruppe']); ?> - Werk:<?php echo $row_rst3['werk']; ?> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center"><img src="picture/dokument.gif" width="50" height="50"></div></td>
      <td colspan="2"><strong><font size="5">automatisiertes R&uuml;sten beim 
        Thermoformen</font></strong></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"></td>
    </tr>
    <tr> 
      <td colspan="3"> <div align="center">
          <object codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,2,0 classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 width=730 height=600>
            <param name="_cx" value="15875">
            <param name="_cy" value="15875">
            <param name="FlashVars" value="15875">
            <param name="Movie" value="http://www.geiss-ttt.com/www_geiss/media/technologie.swf">
            <param name="Src" value="http://www.geiss-ttt.com/www_geiss/media/technologie.swf">
            <param name="WMode" value="Window">
            <param name="Play" value="-1">
            <param name="Loop" value="-1">
            <param name="Quality" value="High">
            <param name="SAlign" value="">
            <param name="Menu" value="-1">
            <param name="Base" value="">
            <param name="AllowScriptAccess" value="always">
            <param name="Scale" value="ShowAll">
            <param name="DeviceFont" value="0">
            <param name="EmbedMovie" value="0">
            <param name="BGColor" value="">
            <param name="SWRemote" value="">
            <embed src="media/geiss_www_technologie.swf" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="730" height="600"> 
            </embed> </object>
        </div></td>
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
