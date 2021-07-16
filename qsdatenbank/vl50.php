<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
if (($suchtext<>"")){$HTTP_POST_VARS["suchtext"]=$suchtext;}
if (($idk<>"")){$HTTP_POST_VARS["idk"]=$idk;}
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
  $la = "vl50";
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

if ((isset($HTTP_POST_VARS["add"]))) {
	if($HTTP_POST_VARS['datum']==""){
	$datum="now()";} else {$datum=GetSQLValueString($HTTP_POST_VARS['datum'], "date");}
  
  $insertSQL = sprintf("INSERT INTO rueckholung (sendungsid, sendungstext, datum, idk, sonstiges, packstuecke, werk,`user`) VALUES (%s, %s, %s, %s, %s,  %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['senungsid'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sendungstext'], "text"),
                       $datum,
                       GetSQLValueString($HTTP_POST_VARS['idk'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['sonstiges'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['packstuecke'], "text"),
   					   GetSQLValueString($HTTP_POST_VARS['werk'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}


$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}




mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM `user`";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstkunden = "SELECT * FROM kundendaten ORDER BY kundendaten.firma";
$rstkunden = mysql_query($query_rstkunden, $qsdatenbank) or die(mysql_error());
$row_rstkunden = mysql_fetch_assoc($rstkunden);
$totalRows_rstkunden = mysql_num_rows($rstkunden);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM `user` WHERE `user`.id='$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

$suchtxt1="AND rueckholung.sendungsid like'%".substr($HTTP_POST_VARS['suchtext'],0,11)."%' ";

if  ($HTTP_POST_VARS[idk]>0) {$suchtxt1=$suchtxt1." AND rueckholung.idk='$HTTP_POST_VARS[idk]' ";   }

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT `kundendaten`.*, `rueckholung`.* FROM kundendaten, rueckholung WHERE (`kundendaten`.`idk` =rueckholung.idk) $suchtxt1 ORDER BY `rueckholung`.`datum` DESC ";
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

if (isset($HTTP_GET_VARS['totalRows_rst2'])) {
  $totalRows_rst2 = $HTTP_GET_VARS['totalRows_rst2'];
} else {
  $all_rst2 = mysql_query($query_rst2);
  $totalRows_rst2 = mysql_num_rows($all_rst2);
}
$totalPages_rst2 = ceil($totalRows_rst2/$maxRows_rst2)-1;

$queryString_rst2 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst2") == false && 
        stristr($param, "totalRows_rst2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst2 = "&" . implode("&", $newParams);
  }
}
$queryString_rst2 = sprintf("&totalRows_rst2=%d%s", $totalRows_rst2, $queryString_rst2);


if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "vl52.php?url_user=".$row_rst1['id']."&url_rueckid=".$HTTP_POST_VARS["hrueckid"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2"method="POST" action="<?php echo $editFormAction; ?>">
<table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr> 
    <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="111">Aktionen W&auml;hlen:</td>
    <td width="610"> 
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <img src="picture/btn_r1_c1.gif" width="133" height="26" align="absmiddle">
<input name="aktualisieren" type="submit" id="aktualisieren" value="aktualisieren"> </td>
    <td width="9" rowspan="4"> <div align="right"> </div></td>
  </tr>
  <tr> 
      <td rowspan="3"><img src="picture/icon_gr_einst_telefonie.gif" width="82" height="75"></td>
      <td>&nbsp;</td>
  </tr>
  <tr> 
      <td>Suche: <img src="picture/icon_scan.gif" alt="Scan Eingabe mit Barcodeleser" width="16" height="16"> 
        <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>"> 
        <input type="submit" name="Submit" value="Finden">
        Sendungs-ID und Kunden-ID </td>
  </tr>
  <tr> 
      <td>
        <select name="idk" id="idk">
          <option value="%" <?php if (!(strcmp("%", $HTTP_POST_VARS['idk']))) {echo "SELECTED";} ?>>- alle anzeigen -</option>
          <?php
do {  




?>
          <option value="<?php echo $row_rstkunden['idk']?>"<?php if (!(strcmp($row_rstkunden['idk'], $HTTP_POST_VARS['idk']))) {echo "SELECTED";} ?>><?php echo substr(utf8_decode($row_rstkunden['firma']),0,60)." - KD-Nr. ".utf8_decode($row_rstkunden['kundenummer']);?></option>
          <?php
} while ($row_rstkunden = mysql_fetch_assoc($rstkunden));
  $rows = mysql_num_rows($rstkunden);
  if($rows > 0) {
      mysql_data_seek($rstkunden, 0);
	  $row_rstkunden = mysql_fetch_assoc($rstkunden);
  }
?>
        </select>
       </td>
  </tr>
</table>
 </form> 
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td height="24" colspan="2"><em>neue R&uuml;ckholung hinzuf&uuml;gen</em></td>
      <td height="24"> <div align="right"> 
          <input name="werk" type="hidden" id="werk2" value="<?php echo $row_rst1['werk']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="add" type="submit" id="add2" value="hinzuf&uuml;gen">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="150"><em><strong>Sendungs ID</strong></em></td>
      <td width="124"><em><strong> 
        <input name="senungsid" type="text" id="zeit2" size="80">
        </strong></em></td>
      <td width="141">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap">Text</td>
      <td nowrap="nowrap"> <textarea name="sendungstext" cols="80" rows="3" id="textarea2"></textarea></td>
      <td nowrap="nowrap"> <div align="right"></div></td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');">
      <td nowrap="nowrap">Packst&uuml;cke</td>
      <td nowrap="nowrap"><input name="packstuecke" type="text" id="packstuecke" value="1" size="10">
        Paket</td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap">Interne Bemerkung</td>
      <td nowrap="nowrap"><textarea name="sonstiges" cols="60" rows="3" id="textarea">keine</textarea></td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap">Datum </td>
      <td nowrap="nowrap"><input name="datum" type="text" id="datum2" size="20" maxlength="10"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap">Kunde</td>
      <td nowrap="nowrap"><select name="idk" id="idk">
          <option value="""" <?php if (!(strcmp("", $HTTP_POST_VARS['idk']))) {echo "SELECTED";} ?>>keine 
          &Auml;nderung</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rstkunden['idk']?>"<?php if (!(strcmp($row_rstkunden['idk'], $HTTP_POST_VARS['idk']))) {echo "SELECTED";} ?>><?php echo substr(utf8_decode($row_rstkunden['firma']),0,60)." - KD-Nr. ".utf8_decode($row_rstkunden['kundenummer']);?></option>
          <?php
} while ($row_rstkunden = mysql_fetch_assoc($rstkunden));
  $rows = mysql_num_rows($rstkunden);
  if($rows > 0) {
      mysql_data_seek($rstkunden, 0);
	  $row_rstkunden = mysql_fetch_assoc($rstkunden);
  }
?>
        </select></td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>

<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der R&uuml;ckholungen <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a><br>
<table width="980" border="0" cellpadding="0" cellspacing="1">
  <tr class="value"> 
    <td width="117"><em><strong>Datum</strong></em></td>
    <td width="126"><em><strong>Kunde</strong></em></td>
    <td width="162"><em><strong>Sendung-ID</strong></em></td>
    <td width="71"><div align="right"> 
        <p>Benutzer</p>
      </div></td>
    <td width="113"><div align="center">Status</div></td>
    <td width="113">&nbsp;</td>
    <td width="113"><div align="right"></div></td>
    <td width="243">&nbsp;</td>
  </tr>
  <?php do {
						  
						/* Sendungsnummer beinhaltetet nur die ersten 11 Stellen der gesamten Retouren  */
						$suchtxt1=substr($row_rst2['sendungsid'],0,11); 
						
						  
												if (isset($HTTP_GET_VARS['pageNum_rst3'])) {
												  $pageNum_rst3 = $HTTP_GET_VARS['pageNum_rst3'];
												}
						
						
						$maxRows_rst3 = 15;
						$pageNum_rst3 = 0;
								
						$startRow_rst3 = $pageNum_rst3 * $maxRows_rst3;
						
						mysql_select_db($database_qsdatenbank, $qsdatenbank);
						$query_rst3 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fm11notes like '%$suchtxt1%'";
						$query_limit_rst3 = sprintf("%s LIMIT %d, %d", $query_rst3, $startRow_rst3, $maxRows_rst3);
						$rst3 = mysql_query($query_limit_rst3, $qsdatenbank) or die(mysql_error());
						$row_rst3 = mysql_fetch_assoc($rst3);
						$totalRows_rst3=mysql_num_rows($rst3);
						
						   ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/pickreturn.gif" alt="<?php echo $row_rst2['sendungstext']; ?>" width="16" height="16" border="0" align="absmiddle"> 
        <?php echo $row_rst2['datum']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <a href="la62.php?url_user=<?php echo $row_rst1['id']; ?>&url_idk=<?php echo $row_rst2['idk']; ?>&goback=vl50" target="_blank"> 
        <?php echo substr(utf8_decode($row_rst2['firma']),0,50); ?> </a> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo nl2br($row_rst2['sendungsid']); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="typ" type="hidden" id="typ2" value="<?php echo $row_rst2['typ']; ?>">
          <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $url_user; ?>">
          <input name="hurl_idaus" type="hidden" id="hurl_idaus2" value="<?php echo $row_rst2['idaus']; ?>">
          <img src="picture/iconchance_16x16.gif" width="16" height="16"> 
          <?php
											do {  
											?>
          <?php if (!(strcmp($row_rst9['id'], $row_rst2['user']))) {echo $row_rst9['name'];}?>
          <?php
											} while ($row_rst9 = mysql_fetch_assoc($rst9));
											  $rows = mysql_num_rows($rst9);
											  if($rows > 0) {
												  mysql_data_seek($rst9, 0);
												  $row_rst9 = mysql_fetch_assoc($rst9);
												}
						?>
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"> 
          <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
          <img src="picture/st3.gif" alt="geplante Retoure " width="15" height="15"> 
          Retoure noch nicht eingetroffen. 
          <?php } // Show if recordset empty ?>
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp; </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"> <img src="picture/b_newdb.png" width="16" height="16"> 
        </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"> 
          <input name="edit" type="submit" id="edit2" value="&auml;ndern">
          <input name="hrueckid" type="hidden" id="hrueckid" value="<?php echo $row_rst2['rueckid']; ?>">
        </div></td>
    </tr><?php if ($totalRows_rst3 > 0) { // Show if recordset not empty  ?>
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td colspan="6" nowrap="nowrap"    bgcolor="#EEEEEE">
        
        <?php do { ?>
        <img src="picture/st1.gif" alt="Sendung zur Reparaturaufnahme eingetroffen" width="15" height="15" border="0"> 
        <a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst3['fmid']; ?>" target="_blank"><?php echo $row_rst3['fmid']; ?></a><a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst3['fmid']; ?>" target="_blank"><img src="picture/ico_bearbeiten.gif" alt="Fehlermeldung anzeigen" width="17" height="18" border="0"></a> 
        <a href="pdfkv1.php?url_user_id=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst3['fmid']; ?>" target="_blank"><img src="picture/bt_download_PDF.gif" alt="Kostenvoranschlag anzeigen" width="17" height="18" border="0"></a> 
        <?php echo $row_rst3['fm1notes']; ?> - <?php echo $row_rst3['fm11notes']; ?> - <?php echo $row_rst3['fm12notes']; ?><br> 
        <?php } while ($row_rst3 = mysql_fetch_assoc($rst3)); 
											$rows = mysql_num_rows($rst3);
											  if($rows > 0) {
												  mysql_data_seek($rst3, 0);
												  $row_rst3 = mysql_fetch_assoc($rst3);
												} ?>
        
      </td>
    </tr>
	<?php } // Show if recordset not empty  rst3?>
    <input type="hidden" name="MM_update" value="form1">
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
Liste der R&uuml;ckholungen <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous 
</a><a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
<?php } // Show if recordset not empty rst2 ?>
<?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
			  
			<p><strong><font color="#FF0000">Keine Daten f&uuml;r die Suche vorhanden.</font></strong></p>
			  <?php } // Show if recordset empty ?>
			<p> 
						  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
						</p>


<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rstkunden);



mysql_free_result($rst9);

mysql_free_result($rst3);
include("footer.tpl.php"); ?>
