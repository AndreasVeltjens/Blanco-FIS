<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "vis45";
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT fmid, fmartikelid, fm9notes, fm10, fm10notes, fmart, fm20, fm30, fmpareto, artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer, fehlermeldungen.materialnummer, fehlermeldungen.fm12notes FROM fehlermeldungen, artikeldaten WHERE artikeldaten.artikelid=fehlermeldungen.fmartikelid  AND fehlermeldungen.fmpareto=1  ORDER BY fehlermeldungen.fmid desc";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

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
  <table width="98%" border="0" cellspacing="0" cellpadding="0">
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3"><strong><?php echo utf8_decode($Modul[$d][0])." "; echo utf8_decode($Modul[$d][1]); ?></strong></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="63"><img src="picture/light.gif" width="40" height="50"></td>
      <td width="549"><strong><font size="5" face="Arial, Helvetica, sans-serif">Paretopalette 
        Fallbeispiel</font></strong> <strong><font size="5" face="Arial, Helvetica, sans-serif"><?php echo $show_pareto; ?> 
        </font></strong></td>
      <td width="215"> <div align="right">
<input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
  </table>
  <hr>
</form>
  


  
    
  
<table width="98%" border="0" cellspacing="4" bgcolor="#FFFFFF">
  <?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
  <?php $i=1;?>
  <?php do { ?>
  <?php if ($i==$show_pareto) { ?>
    <?php
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstfmp = "SELECT * FROM fehlermeldungenpicture WHERE fehlermeldungenpicture.fmp_fm_id='$row_rst4[fmid]'";
$rstfmp = mysql_query($query_rstfmp, $qsdatenbank) or die(mysql_error());
$row_rstfmp = mysql_fetch_assoc($rstfmp);
$totalRows_rstfmp = mysql_num_rows($rstfmp);
?>
  <tr> 
    <td width="27%" rowspan="4">
<?php if ($totalRows_rstfmp > 0) { // Show if recordset not empty ?>
      <table width="215" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="215">&nbsp;</td>
        </tr>
        <?php do { ?>
        <tr> 
          <form name="formfmp_edit" method="post" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
            <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="<? echo "../documents/fehlermeldungsbilder/".$row_rstfmp['link_dokument']?>" target="_blank"><img src="<? echo "../documents/fehlermeldungsbilder/".$row_rstfmp['link_dokument']?>" alt="Anzeigen Vollbild" width="300" border="0"></a><br>
              <?php echo utf8_decode($row_rstfmp['fmp_bemerkung']); ?><br>
              <?php echo $row_rstfmp['fmp_datum']; ?>- 
              <?php
	  
	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuserst = "SELECT * FROM `user`";
$rstuserst = mysql_query($query_rstuserst, $qsdatenbank) or die(mysql_error());
$row_rstuserst = mysql_fetch_assoc($rstuserst);
$totalRows_rstuserst = mysql_num_rows($rstuserst);
	  
do {  
?>
              <?php if (!(strcmp($row_rstuserst['id'], $row_rstfmp['fmp_user']))) {echo $row_rstuserst['name'];}?>
              <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
              </font></td>
          </form>
        </tr>
        <?php } while ($row_rstfmp = mysql_fetch_assoc($rstfmp)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
      <p> 
        <?php if ($totalRows_rstfmp == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Keine Bilder gespeichert.</font></strong> 
        <?php } // Show if recordset empty ?>
      </p>

</td>
   	
	<td width="9%" bgcolor="#FFFFFF"><div align="center"><font size="4">Was 
        ? </font></div></td>
    <td width="64%" bgcolor="#FFFFFF"><font size="3">FM 
      <?php echo $row_rst4['fmid']; ?>- <?php echo $row_rst4['Bezeichnung']; ?></font><font size="5">- <br>
      <?php echo $row_rst4['materialnummer']; ?>- <font size="4"><?php echo utf8_decode($row_rst4['fm12notes']); ?></font></font></td>
  </tr>
  <tr> 
    <td bgcolor="#FF0000"><div align="center"><font size="4">Wieso 
        ? </font></div></td>
    <td bgcolor="#FF0000"><font size="5"><?php echo utf8_decode($row_rst4['fm9notes']); ?></font></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFF00"><div align="center"><font color="#000000" size="4">Sofort 
        ! </font></div></td>
    <td bgcolor="#FFFF00"><font color="#000000" size="5"><?php echo utf8_decode($row_rst4['fm10notes']); ?></font></td>
  </tr>
  <tr> 
    <td bgcolor="#00CC00"><p align="center"><font size="4">Q 
        - </font></p>
      <p align="center"><font size="4">Ma&szlig;nahme ! </font></p></td>
    <td bgcolor="#00CC00"><font size="5"><?php echo utf8_decode($row_rst4['fm30']); ?></font></td>
  </tr>
  <?php }
			$i=$i+1;?>
  <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
  <tr> 
    <td height="40"><font color="#FF0000">Keine 
      Daten gefunden. Paretokennzeichen bei Fehlermeldungen setzen.</font></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php } // Show if recordset empty ?>
</table>
<?php 
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);?>

<?php include("footer.tpl.php"); ?>
