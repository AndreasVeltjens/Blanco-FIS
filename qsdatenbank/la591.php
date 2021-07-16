<?php require_once('Connections/qsdatenbank.php'); ?><?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
if ($HTTP_POST_VARS['skalierung']==""){$HTTP_POST_VARS['skalierung']=0.25;}
 $la = "la59";
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

if (isset($HTTP_POST_VARS['artikelid'])){
if ($HTTP_POST_VARS["artikelid"]>0){$artikel="and fertigungsmeldungen.fartikelid=".$HTTP_POST_VARS[artikelid];}
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT artikeldaten.artikelid, artikeldaten.bezeichnung,date_format(fertigungsmeldungen.fdatum,\"%Y %M\") as fehlerdatum, count(fertigungsmeldungen.fid) as anzahl FROM artikeldaten, fertigungsmeldungen WHERE artikeldaten.artikelid=fertigungsmeldungen.fartikelid $artikel  AND fertigungsmeldungen.ffrei =1  GROUP BY date_format(fertigungsmeldungen.fdatum,\"%Y %M\") ORDER BY fertigungsmeldungen.fdatum desc limit 0,200";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
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
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

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
      <td colspan="3"> <div align="left"> 
          <p align="right"> 
            <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
            <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
            <?php echo $errtxt ?></font></strong> 
            <?php } // Show if recordset empty ?>
            <strong><font size="4">Fertigungsmneldungen als Excel- Dowbnload</font></strong> 
          </p>
        </div></td>
    </tr>
    <tr> 
      <td width="158">Aktionen W&auml;hlen:</td>
      <td width="431"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"></td>
      <td width="139"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>Ger&auml;tetyp:</td>
      <td> <select name="artikelid" id="artikelid">
          <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['artikelid']))) {echo "SELECTED";} ?>>Bitte 
          ausw&auml;hlen</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['artikelid']?>"<?php if (!(strcmp($row_rst3['artikelid'], $HTTP_POST_VARS['artikelid']))) {echo "SELECTED";} ?>><?php echo $row_rst3['Bezeichnung']?></option>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </select>
      </td>
      <td><div align="right"> 
          <input name="suchen" type="submit" id="suchen" value="aktualisieren">
        </div></td>
    </tr>
    <tr>
      <td>Anzeigeformat</td>
      <td><select name="timeline" id="timeline">
          <option value="0" <?php if (!(strcmp(0, $_POST['timeline']))) {echo "SELECTED";} ?>>Jahr-Monat</option>
          <option value="1" <?php if (!(strcmp(1, $_POST['timeline']))) {echo "SELECTED";} ?>>Jahr-Produktionswoche</option>
        </select></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar. Bitte Ger&auml;tegruppe 
  ausw&auml;hlen. </font></strong> <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    
 
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

?>
<?php if ($_POST[timeline]){?>
<p><a href="getexcel4.php?artikelid=<?php echo $_POST[artikelid] ?>&timeline=<?php echo $_POST[timeline] ?>" target="_blank">Download Excelliste </a></p>
<?php }?>
<?php include("footer.tpl.php"); ?>
