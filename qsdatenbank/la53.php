<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la53";
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

if (!isset($HTTP_POST_VARS["artikelid"])){$HTTP_POST_VARS["artikelid"]=0;}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT date_format(fehlermeldungen.fm1datum,\"%Y %M\") as fehlerdatum, count(fehlermeldungen.fmid) as anzahl FROM fehlermeldungen GROUP BY date_format(fehlermeldungen.fm1datum,\"%Y %M\") ORDER BY fehlermeldungen.fm1datum desc limit 0,50";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
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
      <td width="238"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="332"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>Gesamtretouren</td>
      <td>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar. </font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    
  
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>

<p><img src="la53.tmp1.php?artikelid=<?php echo $HTTP_POST_VARS['artikelid']; ?>&datum1=<?php echo $HTTP_POST_VARS['datum1'] ?>&datum2=<?php echo $HTTP_POST_VARS['datum2'] ?>"> 
</p>
<br>
<br>
<br>
<br>
<br>



<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="46" bgcolor="#FFFFCC">Zeit</td>
    <td width="684"> <table width="682" border="0" cellspacing="1" cellpadding="1">
	
        <?php $i=1;
		do { 
		$i=$i+1;
		$d=intval($i/2);
		$gesamt=$gesamt+$row_rst2['anzahl'];
		?>
        <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?>> 
          <td width="112"><?php echo $row_rst2['fehlerdatum']; ?></td>
          <td width="563"><img src="picture/g_blue.png" width="<?php echo $row_rst2['anzahl']; ?>" height="15"><?php echo $row_rst2['anzahl']; ?></td>
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
    <td bgcolor="#FFFFCC">Anzahl gesamt <?php echo $gesamt ?></td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
<br>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

?>
<p>&nbsp;</p>
<?php include("footer.tpl.php"); ?>
