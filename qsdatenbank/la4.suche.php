<?php  
include ('fis.functions.php');

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstland = "SELECT kundendaten.land, count( kundendaten.idk ) AS anzahl
					FROM `kundendaten` 
					WHERE kundendaten.land<>''
					GROUP BY kundendaten.land
					ORDER BY kundendaten.land ASC 
					LIMIT 0 , 30";
$rstland = mysql_query($query_rstland, $qsdatenbank) or die(mysql_error());
$row_rstland = mysql_fetch_assoc($rstland);
$totalRows_rstland = mysql_num_rows($rstland);

?>

<tr> 
      
  <td width="350"><img src="picture/icon_gr_auftragsstatus.gif" alt="Suche nach Seriennummer, Debitor, Versandlabel, Kommision, SAP-Debitorennummer, SAP-Liefernummer, SAP Rechnungsnummer " width="55" height="75" align="absmiddle"><font size="1"><br>
    f&uuml;r die Suche mit unbekannten Zeichen % verwenden</font><br>
<script language="JavaScript" type="text/JavaScript">
function sf(){document.form2.suchtext.focus()}
</script> 
</td>
      <td width="150"><img src="picture/icon_scan.gif" alt="Dieses Feld ist Barcodelesef&auml;hig"><input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
        <input name="suchen" type="submit" id="suchen" value="suchen"></td>
      
  <td width="500"> 
  <?php if ($la=="la4") { ?>

  Fehlereinzeldaten 
    <input <?php if (!(strcmp($HTTP_POST_VARS['zeigefehler'],1))) {echo "checked";} ?> type="checkbox" name="zeigefehler" value="1">
    erweitert
    <input name="addon" type="checkbox" id="addon" value="1" <?php if (!(strcmp($HTTP_POST_VARS['addon'],1))) {echo "checked";} ?>>
    <br>
    Land: 
    
      
      <select name="selectland" onChange="Javascript:document.form2.submit();">
	  <option value="0"> alle anzeigen </option>
	  <?php do{ ?>
        <option value="<?php echo $row_rstland['land']; ?>" <?php if (!(strcmp($row_rstland['land'], $_POST['selectland']))) {echo "SELECTED";} ?>><?php echo utf8_decode($row_rstland['land'])." - ".$row_rstland['anzahl']." Kunden"; ?></option>
      <?php } while ($row_rstland=mysql_fetch_assoc($rstland) );?>
	  </select>
    <select name="selectorder" id="selectorder" onChange="Javascript:document.form2.submit();">
      <option value="0" <?php if (!(strcmp(0, $_POST['selectorder']))) {echo "SELECTED";} ?>> 
      alle anzeigen </option>
      <?php do{ ?>
      <option value="1" <?php if (!(strcmp(1, $_POST['selectorder']))) {echo "SELECTED";} ?>>nach 
      Typ und Seriennummer aufsteigend</option>
	  <option value="1" <?php if (!(strcmp(2, $_POST['selectorder']))) {echo "SELECTED";} ?>>nach 
      Typ und Seriennummer absteigend</option>
      <?php } while ($row_rstland=mysql_fetch_assoc($rstland) );?>
    </select>
	<br>
	    Material: 
    <select name="selectartikelid" id="selectartikelid" onChange="Javascript:form2.submit();">
	  <option value="0">alle anzeigen</option>;
	  <?php
	  $GetArtikelDataList = GetArtikelDataList();
	  if (is_array($GetArtikelDataList)){
	  foreach ($GetArtikelDataList as $row=>$a){
	       echo "<option value=\"".$row."\" ";
		   if ($row==$_POST['selectartikelid']){
		   echo "selected"; 
		   }
		   echo ">".$a."</option>";
	  }
	  }
	  ?>
      </select>
    <font size="1"><br>
    <a href="getexcelfehlermeldungen.php?suchtext=<?php echo $_POST['suchtext']; ?>&selectartikelid=<?php echo $_POST['selectartikelid']; ?>&zeigefehler=<?php echo $_POST['zeigefehler']; ?>&selectidk=<?php echo $_POST['selectidk']; ?>&selectland=<?php echo $_POST['selectland']; ?>&selectorder=<?php echo $_POST['selectorder']; ?>&addon=<?php echo $_POST['addon']; ?>" target="_blank">Download 
    als Excel <img src="picture/W7_Img_Install.jpg" width="20" height="20" border="0"></a></font>
	<?php } ?>
	</td>
</tr>