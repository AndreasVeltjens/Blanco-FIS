<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php  $la = "vl4";
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

if ((isset($HTTP_POST_VARS["add2"]))) {
  $insertSQL = sprintf("INSERT INTO auslieferungen (nummer, bezeichnung, avis, paletten, palettenL,  typ, stueck, stueckblanco, palettenblanco) VALUES (%s, %s,%s, %s, %s,%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['nummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['bezeichnung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['avis'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['paletten'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['palettenL'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['htyp'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['stueck'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['stueckblanco'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['palettenblanco'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}


if ((isset($HTTP_POST_VARS["ausblenden"]))) {

  $updateSQL = sprintf("UPDATE auslieferungen SET lokz=%s WHERE idaus=%s",  
					    GetSQLValueString($HTTP_POST_VARS['lokz'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_idaus'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  $oktxt= $oktxt."Zeile ausgeblendet. <br>";
}


if ((isset($HTTP_POST_VARS['delete']))) {
  $deleteSQL = sprintf("DELETE FROM auslieferungen WHERE idaus=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_idaus'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT VBEST, LBEST,MBEST, artikeldaten.Kontierung, artikeldaten.Gruppe, artikeldaten.verpackung, 
artikeldaten.reichweite,artikeldaten.Bestand,artikeldaten.zusatzreichweite,artikeldaten.Reservierung,
artikeldaten.Zeichnungsnummer, artikeldaten.nummer, artikeldaten.artikelid,artikeldaten.Bezeichnung,
auslieferungen.lokz, auslieferungen.idaus
FROM auslieferungen, artikeldaten 
LEFT JOIN bckfertigware ON bckfertigware.artikelid=artikeldaten.artikelid  
LEFT JOIN lagerbestand ON lagerbestand.artikelid=artikeldaten.artikelid 
LEFT JOIN versandbestand ON versandbestand.artikelid=artikeldaten.artikelid 
WHERE auslieferungen.typ = 1 AND artikeldaten.Nummer=auslieferungen.nummer ORDER BY auslieferungen.lokz, artikeldaten.Gruppe asc , artikeldaten.bezeichnung asc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);


if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["neu"])) ) {
$updateGoTo = "la11.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<style type="text/css">
<!--
.avis1 {
	color: #0033FF;
}
.versteckt {
	background-color: #CCCCCC;
}
-->
</style>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2"><strong>vl4-Auslieferung planen</strong></td>
  </tr>
  <tr> 
    <td colspan="2"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="152">Aktionen W&auml;hlen:</td>
    <td> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <input type="submit" name="Submit" value="Aktualisieren">
        Sicherheitsbestand f&uuml;r 
		<?php if (!isset($HTTP_POST_VARS['selectwochenmaxbestand'])){$HTTP_POST_VARS['selectwochenmaxbestand']=4;}?>
        <select name="selectwochenmaxbestand" id="selectwochenmaxbestand">
          <option value="1" <?php if (!(strcmp(1, $HTTP_POST_VARS['selectwochenmaxbestand']))) {echo "SELECTED";} ?>>1 
          Woche</option>
          <option value="2" <?php if (!(strcmp(2, $HTTP_POST_VARS['selectwochenmaxbestand']))) {echo "SELECTED";} ?>>2 
          Wochen</option>
          <option value="3" <?php if (!(strcmp(3,$HTTP_POST_VARS['selectwochenmaxbestand']))) {echo "SELECTED";} ?>>3 
          Wochen (Standard)</option>
          <option value="4" <?php if (!(strcmp(4, $HTTP_POST_VARS['selectwochenmaxbestand']))) {echo "SELECTED";} ?>>4 
          Wochen</option>
          <option value="5" <?php if (!(strcmp(5, $HTTP_POST_VARS['selectwochenmaxbestand']))) {echo "SELECTED";} ?>>5 
          Wochen</option>
          <option value="6" <?php if (!(strcmp(6, $HTTP_POST_VARS['selectwochenmaxbestand']))) {echo "SELECTED";} ?>>6 
          Wochen</option>
          <option value="7" <?php if (!(strcmp(7, $HTTP_POST_VARS['selectwochenmaxbestand']))) {echo "SELECTED";} ?>>7 
          Wochen</option>
        </select>
      </form>
      <div align="right"> </div></td>
  </tr>
</table>
  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Artikeltypen<br>
<table width="70%" border="0" cellpadding="0" cellspacing="1">
  <tr class="value"> 
    <td width="120"><font size="1"><em><strong>Bezeichnung</strong></em></font></td>
    <td width="50"><font size="1"><em><strong>Material-<br>
      nummer</strong></em></font></td>
    <td width="50"><p><font size="1"><em><strong>Kunden-<br>
        material-<br>
        Nummer</strong></em></font></p></td>
    <td width="50"><em><font size="1">BCK<br>
      fertig montiert</font></em></td>
    <td width="50">&nbsp;</td>
    <td width="50"><p align="center"><font size="1"><em><strong>Gesamt-<br>
        bestand in H011<br>
        BLANCO CS</strong></em></font></p></td>
    <td width="81"><div align="center"><font size="1"><em></em></font></div></td>
    <td width="150"><div align="center"><font size="1"><em><strong>Bestand Versand 
        <br>
        BLANCO CS</strong> </em></font></div></td>
    <td width="60"><div align="center"><em><font size="1">aktuell<br>
        verwendbar<br>
        Lagerbestand</font></em></div></td>
    <td colspan="2" bgcolor="#FFFFFF"><div align="center"><font size="1"><em>Maximal<br>
        bestand</em></font></div></td>
    <td width="80">&nbsp;</td>
    <td width="25" bgcolor="#CCCCFF"><div align="center"><font size="1"><em>Produktions-<br>
        menge</em></font></div></td>
    <td width="20"><div align="right"><font size="1">&nbsp;Anzeige-<br>
        status<br>
        &auml;nderrn</font></div></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <?php if ($row_rst2['Gruppe']<> $gruppe){?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td colspan="14" nowrap="nowrap"    bgcolor="#EEEEEE"> 
        <hr> <br> <img src="picture/add.gif" width="15" height="14" align="absmiddle"> 
        <?php echo $row_rst2['Gruppe']; ?>- <?php echo $row_rst2['Kontierung']; ?> </td>
    </tr>
    <?php }?>
	 <?php 
	 
	 $pmenge=($row_rst2['Bestand']*$HTTP_POST_VARS['selectwochenmaxbestand']);
	  	if ($row_rst2['verpackung']=="" or $row_rst2['verpackung']==0 ){$row_rst2['verpackung']=1;}
		$pmenge1=$pmenge/$row_rst2['verpackung'];
	
	    $pmenge1= ($row_rst2['Bestand'] * $row_rst2['reichweite'])+( $row_rst2['Reservierung'])-$row_rst2['LBEST'];
		$pmenge1=$pmenge1/$row_rst2['verpackung'];
		
		$pmenge=round($pmenge1,0)* $row_rst2['verpackung'];
		if ($pmenge<1){$pmenge="";}
	 
	 
	  ?>
        
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="la202.php?url_user=<?php echo $url_user ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><?php echo substr($row_rst2['Bezeichnung'],0,25); ?></a></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['nummer']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Zeichnungsnummer']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"><?php echo $row_rst2['MBEST']; ?> </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><img src="picture/windows7-arrow.jpg" width="30" height="13"></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"><?php echo $row_rst2['LBEST']; ?> </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"><img src="picture/windows7-arrow.jpg" width="30" height="13"> 
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="center"><font color="#0000FF"><?php echo $row_rst2['VBEST']; ?></font> </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo ($row_rst2['LBEST']); ?> 
        </div></td>
      <td width="102" nowrap="nowrap"    bgcolor="#FFFFFF"> 
        <?php if ($pmenge==0) {?>
        <div align="center"><img src="picture/fertig.gif" width="15" height="15"> 
          <?php }else{ ?>
          <img src="picture/st3.gif" width="15" height="15"> 
          <?php } echo $row_rst2['Bestand'] * $row_rst2['reichweite']; ?><?php if ($row_rst2['Reservierung'] >0){echo "+".$row_rst2['Reservierung'];}  ?>
        </div></td>
      <td width="20" nowrap="nowrap"    bgcolor="#FFFFFF"> 
        <div align="center"></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><img src="picture/windows7-arrow.jpg" width="30" height="13"> 
        </div></td>
      <td    bgcolor="#CCCCFF" nowrap="nowrap">
	 <div align="center"><?php echo round($pmenge,0); ?> </div>
        </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="left">
          <input name="lokz" type="checkbox" id="lokz" value="1" <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?>>
          <img src="picture/b_drop.png" width="16" height="16"> 
          <input name="ausblenden" type="submit" id="ausblenden" value="A">
          <input name="typ" type="hidden" id="typ" value="<?php echo $row_rst2['typ']; ?>">
          <?php echo $row_rst2['verpackung']; ?> - <?php echo $row_rst2['Bestand']; ?>x<?php echo $row_rst2['reichweite']; ?> + <?php echo $row_rst2['Reservierung']; ?> x <?php echo $row_rst2['zusatzreichweite']; ?>
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_idaus" type="hidden" id="hurl_idaus" value="<?php echo $row_rst2['idaus']; ?>">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form1">
  </form>
  <?php   $gruppe=$row_rst2['Gruppe'];?>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
insgesamt <?php echo sprintf("%1.0f",$palettenzahlL) ?> Paletten zur Lieferung. 
insgesamt <?php echo $palettenzahl ?> Paletten vorhanden<br>
<br>
<?php if ($palettenzahl < $palettenzahlL) {echo "<br><strong>Es muss noch gearbeitet werden !</strong>";}
?>
<?php } // Show if recordset not empty ?>
<?php /* <img src="vl41.gantthourex1.php"> */ ?>
<?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
</p>
<hr>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="877" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td colspan="6"><em><img src="picture/arrowClose.gif" width="8" height="9"> 
        neue Materialnummer hinzuf&uuml;gen</em></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="150"><em><strong>Bezeichnung</strong></em></td>
      <td width="124"><em><strong>Materialnummer</strong></em></td>
      <td width="147"><em><strong>Lagerbestand <br>
        Leipzig<br>
        H601<br>
        St&uuml;ck - Paletten</strong></em></td>
      <td width="147"><em><strong>Lagerbestand Oberderdingen<br>
        in Leipzig<br>
        St&uuml;ck - Paletten</strong></em></td>
      <td colspan="2"><strong><em><strong>Transferbestand<br>
        Lieferung nach Oberderdingen<br>
        St&uuml;ck avis- Paletten avis</strong></em></strong></td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap"><input name="bezeichnung" type="text" id="bezeichnung"></td>
      <td nowrap="nowrap"> <input name="nummer" type="text" id="nummer" size="15"></td>
      <td nowrap="nowrap"><input name="stueck" type="text" id="stueck" size="10"> 
        <input name="paletten" type="text" id="paletten" size="3"></td>
      <td nowrap="nowrap"><input name="stueckblanco" type="text" id="stueckblanco" size="10"> 
        <input name="palettenblanco" type="text" id="palettenblanco" size="3"></td>
      <td width="168" nowrap="nowrap">
	  <input name="avis" type="text" id="avis" size="10"> 
        <input name="palettenL" type="text" id="palettenL" size="3"> </td>
      <td width="141" nowrap="nowrap"> <div align="right"> 
          <input name="htyp" type="hidden" id="htyp" value="1">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="add2" type="submit" id="add2" value="hinzuf&uuml;gen">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert2" value="form3">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);


?>
<?php include("footer.tpl.php"); ?>
