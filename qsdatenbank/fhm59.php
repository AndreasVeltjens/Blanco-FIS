<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "fhm59";
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
$query_rst2 = "SELECT date_format(fhm_ereignis.datum,\"%Y %M\") as fehlerdatum, count(fhm_ereignis.id_fhme) as anzahl, sum(fhm_ereignis.kosten1) as kosten1, sum(fhm_ereignis.kosten2) as kosten2, fhm_ereignisart.wert FROM fhm, fhm_ereignis, fhm_gruppe, fhm_ereignisart WHERE fhm.id_fhm=fhm_ereignis.id_fhm and fhm_gruppe.id_fhm_gruppe=fhm.id_fhm_gruppe $gruppe  and fhm_ereignis.id_fhmea=fhm_ereignisart.id_fhmea AND fhm_ereignis.lokz =0 GROUP BY date_format(fhm_ereignis.datum,\"%Y %M\") ORDER BY fhm_ereignis.datum asc limit 0,200";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT fhm_gruppe.id_fhm_gruppe, fhm_gruppe.name FROM fhm_gruppe ORDER BY fhm_gruppe.name";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM fehler ORDER BY fehler.fkurz";
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
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen.";}

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?> 
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="728" border="0" cellspacing="0" cellpadding="0">
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
      <td width="158">Aktionen W&auml;hlen:</td>
      <td width="318"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="252"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>Ger&auml;tegruppe:</td>
      <td> <select name="gruppe" id="gruppe">
          <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['gruppe']))) {echo "SELECTED";} ?>>Bitte 
          ausw&auml;hlen</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['id_fhm_gruppe']?>"<?php if (!(strcmp($row_rst3['id_fhm_gruppe'], $HTTP_POST_VARS['gruppe']))) {echo "SELECTED";} ?>><?php echo utf8_encode(($row_rst3['name']));?></option>
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
    <tr> 
      <td colspan="2">Interne und externe Kosten Instandhaltung/Reparaturen + 
        Wartung </td>
      <td><input name="suchen" type="submit" id="suchen" value="suchen"></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar. Bitte Ger&auml;tegruppe 
  ausw&auml;hlen. </font></strong> <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    
  
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="737"  border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="23" bgcolor="#FFFFCC">Zeit</td>
    <td width="739"> 
	<table width="714" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td>Datum</td>
          <td>Saldo</td>
          <td>interne Aufwendungen</td>
          <td> externe theoret. Aufwendungen</td>
        </tr>
        <?php $i=1;
		do { 
		$i=$i+1;
		$d=intval($i/2);
		$kosten=$kosten+$row_rst2['kosten2']-$row_rst2['kosten1'];
		$kosten1=$kosten1+$row_rst2['kosten1'];
		$kosten2=$kosten2+$row_rst2['kosten2'];
		?>
        <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?>> 
          <td width="127"><?php echo $row_rst2['fehlerdatum']; ?></td>
          <td width="188"><img src="picture/g_blue.png" width="<?php echo $kosten/100; ?>" height="15"><?php echo $kosten; ?>&euro;</td>
          <td width="172"><img src="picture/g_blue.png" width="<?php echo $row_rst2['kosten1']/100; ?>" height="15"><?php echo $row_rst2['kosten1']; ?>&euro;</td>
          <td width="180"><img src="picture/g_blue.png" width="<?php echo $row_rst2['kosten2']/100; ?>" height="15"><?php echo $row_rst2['kosten2']; ?>&euro;</td>
        </tr>
        <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
        <tr> 
          <td><div align="center"><strong>Summe</strong></div></td>
          <td><div align="center"><strong><?php echo $kosten ?>&nbsp;&euro;</strong></div></td>
          <td> <div align="center"><strong><?php echo $kosten1 ?>&nbsp;&euro;</strong></div></td>
          <td><div align="center"><strong><?php echo $kosten2 ?> &nbsp;&euro;</strong></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="36" bgcolor="#FFFFCC">&nbsp;</td>
    <td bgcolor="#FFFFCC">Zusammenfassung</td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
<br>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

?>
<p>&nbsp;</p>
<?php include("footer.tpl.php"); ?>
