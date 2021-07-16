<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php  $la = "vl10";
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT VBEST, LBEST,MBEST, artikeldaten.Kontierung, artikeldaten.Gruppe, artikeldaten.verpackung, 
artikeldaten.reichweite,artikeldaten.Bestand,artikeldaten.zusatzreichweite,artikeldaten.Reservierung,
artikeldaten.Zeichnungsnummer, artikeldaten.nummer, artikeldaten.artikelid,artikeldaten.Bezeichnung,
auslieferungen.lokz
FROM auslieferungen, artikeldaten 
LEFT JOIN bckfertigware ON bckfertigware.artikelid=artikeldaten.artikelid  
LEFT JOIN lagerbestand ON lagerbestand.artikelid=artikeldaten.artikelid 
LEFT JOIN versandbestand ON versandbestand.artikelid=artikeldaten.artikelid 
WHERE (auslieferungen.typ = 1 AND artikeldaten.Nummer=auslieferungen.nummer AND auslieferungen.lokz=0) AND artikeldaten.Kontierung like '%fertig%'   ORDER BY  artikeldaten.Gruppe asc , artikeldaten.bezeichnung asc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<table width="980" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2"><strong>Lieferplan</strong></td>
  </tr>
  <tr> 
    <td width="152"> 
      <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Infosystem</font><font color="#FF0000">.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?>
    </td>
    <td> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <div align="right"> 
          <input name="suchtext" type="text" id="suchtext">
          <input name="aktualisieren" type="submit" id="aktualisieren" value="aktualisieren">
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
          <font size="5"><strong> - Produktionsplanung</strong></font> </div>
      </form>
      <div align="right"> </div></td>
  </tr>
</table>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Artikeltypen 
<table width="980" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
  <tr> 
    <td height="24"><strong>Fertigware</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><table width="300" border="0" cellpadding="0" cellspacing="1">
        <tr class="value"> 
          <td width="100"><font size="1"><em><strong>Bezeichnung</strong></em></font></td>
          <td width="50"><font size="1"><em><strong>Material-<br>
            nummer</strong></em></font></td>
          <td width="25" bgcolor="#FFFF00"><em><font size="1">BCK<br>
            fertig<br>
            H601</font></em></td>
          <td width="25" bgcolor="#00CC00"><p align="center"><font size="1"><em><strong>Bestand<br>
              in H011<br>
              </strong></em></font></p></td>
          <td width="25" bgcolor="#66CCFF"><div align="center"><font size="1"><em><strong>Bestand<br>
              Versand <br>
              </strong></em></font></div></td>
          <td width="25" bgcolor="#FF6600"><div align="center"><font size="1"><em>Pro-duktions-<br>
              menge</em></font></div></td>
        </tr>
        <?php do { ?>
        <?php if ($zeile==round($totalRows_rst2/2)){ 
		echo "</table></td><td><table width=\"300\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\"><tr><td>";
		
		 ?>
        <tr class="value"> 
          <td width="100"><font size="1"><em><strong>Bezeichnung</strong></em></font></td>
          <td width="50"><font size="1"><em><strong>Material-<br>
            nummer</strong></em></font></td>
          <td width="25" bgcolor="#FFFF00"><p><em><font size="1">BCK<br>
              fertig<br>
              </font></em><em><font size="1">H601</font></em></p></td>
          <td width="25" bgcolor="#00CC00"><p align="center"><font size="1"><em><strong>Bestand<br>
              in H011<br>
              </strong></em></font></p></td>
          <td width="25" bgcolor="#66CCFF"><div align="center"><font size="1"><em><strong>Bestand<br>
              Versand <br>
              </strong></em></font></div></td>
          <td width="25" bgcolor="#FF6600"><div align="center"><font size="1"><em>Pro-duktions-<br>
              menge</em></font></div></td>
        </tr>
        <?php
		
		} ?>
        <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
          <?php if ($row_rst2['Gruppe']<> $gruppe){?>
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td colspan="6" nowrap="nowrap"    bgcolor="#EEEEEE"> <hr> <img src="picture/add.gif" width="15" height="14" align="absmiddle"> 
              <?php echo $row_rst2['Gruppe']; ?>- <?php echo $row_rst2['Kontierung']; ?></td>
          </tr>
          <?php }?>
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="la202.php?url_user=<?php echo $url_user ?>&url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><?php echo substr($row_rst2['Bezeichnung'],0,25); ?></a></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['nummer']; ?></td>
            <td    bgcolor="#FFFF00" nowrap="nowrap"> <div align="center"><strong><?php echo $row_rst2['MBEST']; ?></strong> 
              </div></td>
            <td    bgcolor="#00CC00" nowrap="nowrap"> <div align="center"><strong><?php echo $row_rst2['LBEST']; ?></strong> 
              </div></td>
            <td    bgcolor="#66CCFF" nowrap="nowrap"> <div align="center"><font color="#0000FF"><strong><?php echo $row_rst2['VBEST']; ?></strong></font> 
              </div></td>
            <td    bgcolor="#FF6600" nowrap="nowrap"> <strong> 
              <?php $pmenge=($row_rst2['Bestand']*$row_rst2['reichweite'])+$row_rst2['Reservierung']-$row_rst2['LBEST'];
	  
	  	
			if ($row_rst2['verpackung']=="" or $row_rst2['verpackung']==0 ){$row_rst2['verpackung']=1;}
		
		$pmenge1=$pmenge/$row_rst2['verpackung'];
		$pmenge=round($pmenge1,0)*$row_rst2['verpackung'];
		if ($pmenge<1){$pmenge="";}
	  ?>
              </strong> <div align="center"><strong>
                <?php if (round($pmenge,0)<>0){echo round($pmenge,0);} ?>
                </strong></div></td>
          </tr>
          <input type="hidden" name="MM_update" value="form1">
        </form>
        <?php   $gruppe=$row_rst2['Gruppe'];
				$zeile=$zeile+1; ?>
        <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
      </table>
      &nbsp;</td>
  </tr>
</table>
<?php } ?>
<?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
<p>Keine Daten vorhanden.</p>
<?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
</p>
<p>&nbsp;</p>
<?php include("footer.tpl.php"); ?>
<?php
mysql_free_result($rst2);
?>
