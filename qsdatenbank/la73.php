<?php require_once('Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "la73";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
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
$query_rst2 = "SELECT pruefungdaten.id_pruefd, pruefungdaten.datum, pruefungdaten.messwert1, pruefungdaten.messwert2, pruefungdaten.messwert3, pruefungdaten.messwert4, pruefungdaten.messwert5, pruefungdaten.`user` FROM pruefungdaten WHERE pruefungdaten.lokz=0 AND pruefungdaten.id_pruef='$url_id_pruef' ORDER BY pruefungdaten.datum desc, pruefungdaten.id_pruefd desc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT fhm_gruppe.id_fhm_gruppe, fhm_gruppe.name FROM fhm_gruppe ORDER BY fhm_gruppe.name";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM pruefungen WHERE pruefungen.id_pruef='$url_id_pruef'";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT `user`.name, `user`.id FROM `user`";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);


if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la72.php?url_user=".$row_rst1['id'];
if (isset($goback)){$updateGoTo = "la7.php?url_user=".$row_rst1['id'];}
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
          <font size="+2"><strong>REGELKARTE</strong></font> </div></td>
    </tr>
    <tr> 
      <td><strong>Name</strong></td>
      <td colspan="2"><strong><?php echo $row_rst4['name']; ?></strong></td>
    </tr>
    <tr> 
      <td>Beschreibung</td>
      <td colspan="2"><?php echo $row_rst4['memo']; ?></td>
    </tr>
    <tr> 
      <td><strong>SOLL-Wert</strong></td>
      <td><strong><?php echo $row_rst4['soll']; ?> <?php echo $row_rst4['einheit']; ?></strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Untere Warngrenze</td>
      <td><?php echo $row_rst4['ugw']; ?><?php echo $row_rst4['einheit']; ?></td>
      <td>Werk: <?php echo $row_rst4['werk']; ?></td>
    </tr>
    <tr> 
      <td>Obere Warngrenze</td>
      <td><?php echo $row_rst4['ogw']; ?><?php echo $row_rst4['einheit']; ?></td>
      <td>Formel-ID: <?php echo $row_rst4['formelid']; ?></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar. Bitte Regelkarte 
  ausw&auml;hlen. </font></strong> <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    
  
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="770" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="23" bgcolor="#FFFFCC">Zeit</td>
    <td width="707"> 
	<table width="863" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td height="21">&nbsp;</td>
          <td><div align="right">Abweichung neg.vom Soll</div></td>
          <td><div align="center">pos. Abweichung vom Soll</div></td>
          <td><div align="center">Messwert</div></td>
          <td>Bemerkungen</td>
        </tr>
        <?php $i=1;
		do { 
		$i=$i+1;
		$d=intval($i/2);
		if ($row_rst4['formelid']==1){
		$ist=($row_rst2['messwert4']*$row_rst4['Umrechnungsfaktor'])/($row_rst2['messwert1']* $row_rst2['messwert2']*$row_rst2['messwert3']);
		}else{
		$ist=$row_rst2['messwert1']*$row_rst4['Umrechnungsfaktor'];
  if (round($row_rst2['messwert1'],2)==0) { $row_rst2['messwert1']=($row_rst2['messwert1']);}	else {$row_rst2['messwert1']=round($row_rst2['messwert1'],2);}
  if (round($row_rst2['messwert2'],2)==0) { $row_rst2['messwert2']=($row_rst2['messwert2']);}	else {$row_rst2['messwert2']=round($row_rst2['messwert2'],2);}
  if (round($row_rst2['messwert3'],2)==0) { $row_rst2['messwert3']=($row_rst2['messwert3']);}	else {$row_rst2['messwert3']=round($row_rst2['messwert3'],2);}
  if (round($row_rst2['messwert4'],2)==0) { $row_rst2['messwert4']=($row_rst2['messwert4']);}	else {$row_rst2['messwert4']=round($row_rst2['messwert4'],2);}
  if (round($row_rst2['messwert5'],2)==0) { $row_rst2['messwert5']=($row_rst2['messwert5']);}	else {$row_rst2['messwert5']=round($row_rst2['messwert5'],2);}

		}
		?>
        <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?>> 
          <td width="87"><?php echo $row_rst2['datum']; ?></td>
          <td width="173"><div align="right"> 
              <?php if (($ist-$row_rst4['soll'])<0){;
             echo round($ist-$row_rst4['soll'],3); 
			 if (($ist)<($row_rst4['ugw'])){$picture="g_red.png";}
			 else{$picture="g_green.png";}
			 	echo $row_rst4['einheit']."<img src=\"picture/".$picture."\" width=".(($row_rst4['soll']-$ist)*$row_rst4['skalierung'])."\" height=\"15\">";
              
			  }?>
            </div></td>
          <td width="182"> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php if (($ist-$row_rst4['soll'])>=0){;
			if (($ist)>$row_rst4['ogw']){$picture="g_red.png";}
			 else{$picture="g_green.png";}
            echo "<img src=\"picture/".$picture."\" width=".(($ist-$row_rst4['soll'])*$row_rst4['skalierung'])."\" height=\"15\">".round($ist-$row_rst4['soll'],3); ?>
            <?php echo $row_rst4['einheit']; ?> <?php }?>
          </td>
          <td width="168"> 
            <img src="picture/g_blue.png" width="<?php echo $ist*$row_rst4['skalierung']; ?>" height="15"><?php echo round($ist,2) ?> 
            <?php echo $row_rst4['einheit']; ?> </td>
          <td width="237"><?php echo $row_rst2['messwert1']."-".$row_rst2['messwert2']."-".$row_rst2['messwert3']."-".$row_rst2['messwert4']."-".$row_rst2['messwert5']; ?>-
            <?php
do {  
?>
            <?php if (!(strcmp($row_rstuser['id'], $row_rst2['user']))) {echo $row_rstuser['name'];}?>
            <?php
} while ($row_rstuser= mysql_fetch_assoc($rstuser));
  $rows = mysql_num_rows($rstuser);
  if($rows > 0) {
      mysql_data_seek($rstuser, 0);
	  $row_rstuser = mysql_fetch_assoc($rstuser);
  }
?>
          </td>
        </tr>
        <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
        <tr> 
          <td height="21">&nbsp;</td>
          <td><div align="right"></div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFCC">&nbsp;</td>
    <td bgcolor="#FFFFCC">&nbsp;</td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
<br>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

mysql_free_result($rstuser);

?>
<p>&nbsp;</p>
<?php include("footer.tpl.php"); ?>
