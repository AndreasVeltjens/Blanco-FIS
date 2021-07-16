<?php require_once('Connections/nachrichten.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "inventur";
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
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE aktiviert=1 ORDER BY name";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);


if ($HTTP_POST_VARS['chk_user']>0 && $HTTP_POST_VARS['lager']<>"" && $HTTP_POST_VARS['suchtext']>=100000 ) {

$query_rst2 = "SELECT * FROM check_fid WHERE chk_fid='$HTTP_POST_VARS[suchtext]'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

  $insertSQL = sprintf("INSERT INTO check_fid (chk_fid, chk_user, chk_lager, chk_datum) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['suchtext'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['chk_user'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['lager'], "text"),
                                            "now()");

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
	$oktxt.="Datensatz angelegt.";
	
	
	if ($totalRows_rst2==0){
	$status="#00FF00";
	}else{
		$status="#FFCC00";
	}
	
} else {
$status="#FFFFFF";
$errtxt.="keine Daten angelegt.";}

if ($HTTP_POST_VARS['suchtext3']<300000) {
$status="#FF0000";
$errtxt.="FID ungültig.";}


?>
<style type="text/css">
<!--
.senden {
	height: 50px;
	width: 150px;
}
-->
</style>
<script language="JavaScript" type="text/javascript">
function sf(){document.form2.suchtext.focus()}
</script>
<title>Inventurz&auml;hlung</title>
<body bgcolor="<?php echo $status ?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="sf()">
<p align="center"><strong>ifis Inventurprogramm FID<br>
  </strong><font color="#009900" size="1"><?php echo $oktxt; ?></font> <font color="#990000" size="1"><?php echo $errtxt; ?></font></p>
<form name="form2" method="post" action="">
  <p align="center">FID: 
    <input name="suchtext" type="text" id="suchtext" maxlength="12">
    <br>
    <br>
    <input name="senden" type="submit" class="senden" id="senden" value="Senden">
    <br>
    <font size="1">Mitarbeiter und Lager</font><br>
    <select name="chk_user" id="chk_user">
      <option value="" <?php if (!(strcmp("", $HTTP_POST_VARS['chk_user']))) {echo "SELECTED";} ?>>bitte 
      wählen</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rst1['id']?>"<?php if (!(strcmp($row_rst1['id'], $HTTP_POST_VARS['chk_user']))) {echo "SELECTED";} ?>><?php echo utf8_decode($row_rst1['name']);?></option>
      <?php
} while ($row_rst1 = mysql_fetch_assoc($rst1));
  $rows = mysql_num_rows($rst1);
  if($rows > 0) {
      mysql_data_seek($rst1, 0);
	  $row_rst1 = mysql_fetch_assoc($rst1);
  }
?>
    </select>
    <select name="lager" id="lager">
      <option value="" <?php if (!(strcmp("", $HTTP_POST_VARS['lager']))) {echo "SELECTED";} ?>>bitte 
      wählen</option>
      <option value="H601" <?php if (!(strcmp("H601", $HTTP_POST_VARS['lager']))) {echo "SELECTED";} ?>>H601 
      </option>
      <option value="H011" <?php if (!(strcmp("H011", $HTTP_POST_VARS['lager']))) {echo "SELECTED";} ?>>H011</option>
      <option value="R601" <?php if (!(strcmp("R601", $HTTP_POST_VARS['lager']))) {echo "SELECTED";} ?>>R601</option>
      <option value="P602" <?php if (!(strcmp("P602", $HTTP_POST_VARS['lager']))) {echo "SELECTED";} ?>>P602</option>
      <?php

?>
    </select>
  </p>
  </form>