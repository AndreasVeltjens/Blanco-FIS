<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php  $la = "la2";
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

/* if ($HTTP_POST_VARS['lagerplatzliste']<>"") {$HTTP_POST_VARS['radiobutton']=1;
$oktxt="Lagerortanzeige aktiv";
} */

if ((isset($HTTP_POST_VARS["umlagern"]))) {
  $updateSQL = sprintf("UPDATE artikeldaten SET lagerplatz=%s WHERE artikelid=%s",
						 GetSQLValueString($HTTP_POST_VARS['lagerplatzliste'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_artikelid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

$oktxt="Umlagerung auf ".$HTTP_POST_VARS['lagerplatz']."<br>";
}
	
	
if (isset($HTTP_POST_VARS['suchtext'])){
$url_kontierung;
$materialnr=round($HTTP_POST_VARS['suchtext'],0);
$suchtxt=" AND (artikeldaten.Bezeichnung like '%$HTTP_POST_VARS[suchtext]%' or artikeldaten.Nummer ='$materialnr' )";
} else {$suchtxt="";}

if (isset($HTTP_POST_VARS['lagerplatzliste'])<>0){

$suchtxt.=" AND (artikeldaten.lagerplatz like '%$HTTP_POST_VARS[lagerplatzliste]%')";
} 

if ($HTTP_POST_VARS['radiobutton']==1){
	if ($url_kontierung<>""){
		$suchtxt=$suchtxt." AND artikeldaten.lagerplatz like '%$url_kontierung%' ";
	} 
	$ordertxt="ORDER BY artikeldaten.lagerplatz desc, artikeldaten.Bezeichnung asc";
}else{	
	if ($url_kontierung<>""){
		$suchtxt=$suchtxt." AND artikeldaten.Kontierung like '$url_kontierung' ";
	} 
	$ordertxt="ORDER BY artikeldaten.Kontierung,artikeldaten.Gruppe, artikeldaten.Bezeichnung asc";
}	
	
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert<=1 $suchtxt $ordertxt ";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstlagerort = "SELECT distinct artikeldaten.lagerplatz FROM artikeldaten ORDER BY artikeldaten.lagerplatz";
$rstlagerort = mysql_query($query_rstlagerort, $qsdatenbank) or die(mysql_error());
$row_rstlagerort = mysql_fetch_assoc($rstlagerort);
$totalRows_rstlagerort = mysql_num_rows($rstlagerort);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if (isset($HTTP_POST_VARS["stamm"])) {
$updateGoTo = "la202.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Sprung auf LA202. Bitte erneut versuchen.";}

if (isset($HTTP_POST_VARS["edit"])) {
$updateGoTo = "la21.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
  
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Sprung auf LA21. Bitte erneut versuchen.";}

if (isset($HTTP_POST_VARS["zuruck"])) {
$updateGoTo = "la2.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Sprung auf LA2 fehlerhaft";}


if ((isset($HTTP_POST_VARS["add"])) ) {
$updateGoTo = "la23.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["virtuell"])) ) {
$updateGoTo = "la25.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}


if ((isset($HTTP_POST_VARS["bilderladen"])) ) {

		do {
		$insertSQL = sprintf("UPDATE artikeldaten SET link_dokument=%s WHERE artikelid=%s",
							   GetSQLValueString($row_rst2['Nummer'].".png", "text"),
							   GetSQLValueString($row_rst2['artikelid'], "int"));
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
		$oktxt.=" Bilder importiert.".$row_rst2['artikelid'];
		} while ($row_rst2 = mysql_fetch_assoc($rst2));
}else{$errtxt = "";}

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>



<form name="form2" method="post" action="">
  <table width="980" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong>LA2- Artikelstammdaten</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?>
        <font color="#009900"><?php echo $oktxt ?></font> </td>
    </tr>
    <tr> 
      <td width="164">Aktionen W&auml;hlen:</td>
      <td width="300"><img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur Startseite">
        <input name="zuruck" type="submit" id="zuruck" value="Zur&uuml;cksetzen"> </td>
      <td width="366">
<div align="right"> 
          <input name="bilderladen" type="submit" id="bilderladen2" value="Bilder laden">
          <img src="picture/bssctoc1.gif" width="32" height="16"> 
          <input name="virtuell" type="submit" id="virtuell" value="virtuelle Baugruppen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"><a href="pdfla2.php?url_idk=238&url_user_id=<?php echo $url_user; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" width="17" height="18" border="0"> 
          Transferpreisliste drucken</a> </div></td>
    </tr>
    <tr> 
      <td><img src="picture/icon_gr_einstellungen.gif" width="52" height="36">Suche</td>
      <td>:<img src="picture/icon_scan.gif" alt="Dieses Feld ist Barcodelesef&auml;hig">
<input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
        <input name="suche" type="submit" id="suche" value="Suchen"></td>
      <td><div align="right"><a href="la24.php?url_user=<?php echo $url_user; ?>" target="_self">Fehlerlisten anzeigen</a></div></td>
    </tr>
    <tr> 
      <td>Anzeige nach</td>
      <td>
        <input <?php if (!(strcmp($HTTP_POST_VARS['radiobutton']," "))) {echo "CHECKED";} ?> name="radiobutton" type="radio" value=" " checked>
        
        Kontierung/Warengruppe 
        <input <?php if (!(strcmp($HTTP_POST_VARS['radiobutton'],"1"))) {echo "CHECKED";} ?> type="radio" name="radiobutton" value="1">
        Lagerort</td>
      <td>Lagerplatzliste 
        <select name="lagerplatzliste">
          <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['lagerplatzliste']))) {echo "SELECTED";} ?>>keine 
          Auswahl</option>
          <option value="WH1" <?php if (!(strcmp("WH1", $HTTP_POST_VARS['lagerplatzliste']))) {echo "SELECTED";} ?>>WH1</option>
          <option value="WH2" <?php if (!(strcmp("WH2", $HTTP_POST_VARS['lagerplatzliste']))) {echo "SELECTED";} ?>>WH2</option>
          <option value="WH3" <?php if (!(strcmp("WH3", $HTTP_POST_VARS['lagerplatzliste']))) {echo "SELECTED";} ?>>WH3</option>
          <option value="WH4" <?php if (!(strcmp("WH4", $HTTP_POST_VARS['lagerplatzliste']))) {echo "SELECTED";} ?>>WH4</option>
          <option value="--------------" <?php if (!(strcmp("--------------", $HTTP_POST_VARS['lagerplatzliste']))) {echo "SELECTED";} ?>>--------------------</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rstlagerort['lagerplatz']?>"<?php if (!(strcmp($row_rstlagerort['lagerplatz'], $HTTP_POST_VARS['lagerplatzliste']))) {echo "SELECTED";} ?>><?php echo $row_rstlagerort['lagerplatz']?></option>
          <?php
} while ($row_rstlagerort = mysql_fetch_assoc($rstlagerort));
  $rows = mysql_num_rows($rstlagerort);
  if($rows > 0) {
      mysql_data_seek($rstlagerort, 0);
	  $row_rstlagerort = mysql_fetch_assoc($rstlagerort);
  }
?>
        </select></td>
    </tr>
  </table>
  </form>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<br>

<?php if ($HTTP_POST_VARS['radiobutton']==1){?>
<?php include("la2.lagerplatz.php"); ?>
<?php }else{?>
<?php include("la2.kontierung.php"); ?>
<?php }?>

<?php } // Show if recordset not empty ?>
<p>&nbsp;</p><?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
  <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rstlagerort);
?>
<?php include("footer.tpl.php"); ?>
