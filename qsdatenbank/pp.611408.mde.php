<?php require_once('Connections/nachrichten.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "mde611408";
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
$query_rst1 = "SELECT * FROM user WHERE aktiviert=1 AND rst611408=1 ORDER BY name";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);


/* Versenden */

if ($HTTP_POST_VARS['storno']<>1 or $storno<>1) {

if ( $HTTP_POST_VARS['chk_user']>0 &&  $HTTP_POST_VARS['lager']<>"" && $HTTP_POST_VARS['suchtext']>=100000 ) {


$suchtxt=$HTTP_POST_VARS["suchtext"];
$suchtxtid=$HTTP_POST_VARS["suchtext"]+0;


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_lastverkauf = "SELECT * FROM fertigungsmeldungen WHERE (fertigungsmeldungen.fid ='$suchtxtid' OR fertigungsmeldungen.frfid='$suchtxt') AND fertigungsmeldungen.verkauftam<>'0000-00-00 00:00:00' ";
$rst_lastverkauf = mysql_query($query_rst_lastverkauf, $qsdatenbank) or die(mysql_error());
$row_rst_lastverkauf = mysql_fetch_assoc($rst_lastverkauf);
$totalRows_rst_lastverkauf = mysql_num_rows($rst_lastverkauf);

		if ($totalRows_rst_lastverkauf==1) {
		
			$updateSQL = sprintf("UPDATE fertigungsmeldungen SET versendetam=%s, versendetuser=%s, history=%s WHERE fid=%s",
							   "now()",
							   GetSQLValueString($HTTP_POST_VARS['chk_user'],"int"),
							   GetSQLValueString("User ".$HTTP_POST_VARS['chk_user']." - Versand per MDE 611408;".$rst_lastverkauf['history'], "text"),
							   GetSQLValueString($HTTP_POST_VARS['suchtext'], "int"));
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
		  $oktxt.="Artikel mit  FID $suchtxt versendet.";
		  $status="#00FF00";
				  if ($row_rst_lastverkauf['versendetam']<>'0000-00-00 00:00:00') {
				  $oktxt.="Warnung: Artikel mit  FID $suchtxt wurde bereits am ".$row_rst_lastverkauf['verkauftam']." versendet.";
				  $status="#FFFF00";
				  }
			} else { 
			$errtxt=$errtxt." Artikel mit FID $suchtxt nicht im Lager gefunden.";
			$status="#FF0000";
			}
	
			
	$oktxt.="Datensatz angelegt.";
	
} else { 
			$errtxt=$errtxt." Artikel mit FID $suchtxt nicht gefunden. Lagerort ausw?hlen und Mitarbeiter pr?fen.";
			if ($storno==1) {$status="#9966CC";} else {$status="#FF0000";}
}

/* ende versenden */
 } elseif ($HTTP_POST_VARS['storno']==1 or $storno==1) {



/* Versenden storno */

if ($HTTP_POST_VARS['chk_user']>0 && $HTTP_POST_VARS['lager']<>"" && $HTTP_POST_VARS['suchtext']>=100000 ) {


$suchtxt=$HTTP_POST_VARS["suchtext"];
$suchtxtid=$HTTP_POST_VARS["suchtext"]+0;


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_lastverkauf = "SELECT * FROM fertigungsmeldungen WHERE (fertigungsmeldungen.fid ='$suchtxtid' OR fertigungsmeldungen.frfid='$suchtxt') AND fertigungsmeldungen.verkauftam >'0000-00-00 00:00:00' ";
$rst_lastverkauf = mysql_query($query_rst_lastverkauf, $qsdatenbank) or die(mysql_error());
$row_rst_lastverkauf = mysql_fetch_assoc($rst_lastverkauf);
$totalRows_rst_lastverkauf = mysql_num_rows($rst_lastverkauf);

		if ($totalRows_rst_lastverkauf==1) {
		
			$updateSQL = sprintf("UPDATE fertigungsmeldungen SET versendetam=%s, versendetuser=%s, history=%s WHERE fid=%s",
							   "0",
							   GetSQLValueString($HTTP_POST_VARS['chk_user'],"int"),
							   GetSQLValueString("User ".$HTTP_POST_VARS['chk_user']."-".$HTTP_POST_VARS['selectgrund']."-Storno-Versand per MDE 611408;".$rst_lastverkauf['history'], "text"),
							   GetSQLValueString($HTTP_POST_VARS['suchtext'], "int"));
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
		  $oktxt.="Artikel mit  FID $suchtxt wieder eingelagert.";
		  $status="#00FF00";
				  if ($row_rst_lastverkauf['versendetam']=='0000-00-00 00:00:00') {
				  $oktxt.="Warnung: Artikel mit  FID $suchtxt wurde bereits am ".$row_rst_lastverkauf['verkauftam']." eingelagert.";
				  $status="#FFFF00";
				  }
			} else { 
			$errtxt=$errtxt." Artikel mit FID $suchtxt nicht als versendete Ware gefunden.";
			$status="#9966CC";
			}
	
			
	$oktxt.="Datensatz angelegt.";
	
} else { 
			$errtxt=$errtxt." Artikel mit FID $suchtxt nicht gefunden. Lagerort ausw?hlen und Mitarbeiter pr?fen.";
			if ($storno==1) {$status="#9966CC";} else {$status="#FF0000";}
}

/* ende versenden */

} /* ende $storno */

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_anzahlverkauft = "SELECT count(fid) as anzahl FROM fertigungsmeldungen WHERE versendetam>=DATE_SUB(now(),interval '5:00' MINUTE_SECOND) AND verkauftam > '0000-00-00 00:00:00' ";
$anzahlverkauft = mysql_query($query_anzahlverkauft, $qsdatenbank) or die(mysql_error());
$row_anzahlverkauft = mysql_fetch_assoc($anzahlverkauft);
$totalRows_anzahlverkauft = mysql_num_rows($anzahlverkauft);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_anzahlverkauft2 = "SELECT count(fid) as anzahl FROM fertigungsmeldungen WHERE versendetam>=DATE_SUB(now(),interval '840:00' MINUTE_SECOND) AND verkauftam > '0000-00-00 00:00:00' ";
$anzahlverkauft2 = mysql_query($query_anzahlverkauft2, $qsdatenbank) or die(mysql_error());
$row_anzahlverkauft2 = mysql_fetch_assoc($anzahlverkauft2);
$totalRows_anzahlverkauft2 = mysql_num_rows($anzahlverkauft2);

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
<title>Versand zum Kunden</title>
<body bgcolor="<?php echo $status ?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="sf()">
<p align="center"><strong><a href="pp.611408.mde.php">ifis Versand zum Kunden</a> 
  -<a href="pp.611408.mde.php?storno=1"> Storno</a><br>
  </strong><font color="#009900" size="1"><?php echo $oktxt; ?></font> <font color="#990000" size="1"><?php echo $errtxt; ?></font></p>
<form name="form2" method="post" action="">
  <p align="center">FID: 
    <input name="suchtext" type="text" id="suchtext" maxlength="12">
    <br>
    <br>
    <?php if ($storno==1){?>
    <select name="selectgrund" id="selectgrund">
      <option value="Retoure" <?php if (!(strcmp("Retoure", $HTTP_POST_VARS['selectgrund']))) {echo "SELECTED";} ?>>Retoure</option>
      <option value="falscher Artikel" <?php if (!(strcmp("falscher Artikel", $HTTP_POST_VARS['selectgrund']))) {echo "SELECTED";} ?>>falscher 
      Artikel</option>
      <?php

?>
    </select>
    <input name="senden" type="submit" class="senden" id="senden" value="?? Storno ??">
    <?php }else{?>
    <input name="senden" type="submit" class="senden" id="senden" value="Senden">
    <?php }
?>
    <br>
    <br>
    <font size="1">Mitarbeiter und Lager</font><br>
    <select name="chk_user" id="chk_user">
      <option value="" <?php if (!(strcmp("", $HTTP_POST_VARS['chk_user']))) {echo "SELECTED";} ?>>bitte 
      w?hlen</option>
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
      <option value="H011" <?php if (!(strcmp("H011", $HTTP_POST_VARS['lager']))) {echo "SELECTED";} ?>>H011</option>
      <?php

?>
    </select>
    <input name="storno" type="hidden" value="<?php echo $storno ?>">
    <br>
    <strong>verkauft 5 min: <?php echo $row_anzahlverkauft['anzahl']; ?> - am Tag: <?php echo $row_anzahlverkauft2['anzahl']; ?></strong></p>
  <hr>
  <p align="center">&nbsp; </p>
</form>
