<?php require_once('Connections/psdatenbank.php'); ?>
<?php require_once('Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "hr13";
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

if ((isset($HTTP_POST_VARS["edit1"]))) {
  $updateSQL = sprintf("UPDATE pramien SET von=%s, bis=%s, datum=%s, lokz=%s, erstellt=%s, pramie=%s, gruppe=%s WHERE pra_id=%s",
                       GetSQLValueString($HTTP_POST_VARS['von'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['bis'], "date"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['lokz'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['textfield'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['bewertungsgruppe'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hpra_id'], "int"));

  mysql_select_db($database_psdatenbank, $psdatenbank);
  $Result1 = mysql_query($updateSQL, $psdatenbank) or die(mysql_error());
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

$maxRows_rst2 = 20;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst5 = "SELECT * FROM pramien WHERE pramien.pra_id='$url_pra_id'";
$rst5 = mysql_query($query_rst5, $psdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM `user` WHERE `user`.gruppe=$row_rst5[gruppe] ORDER BY `user`.werk, `user`.username";
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT `user`.id, `user`.name FROM `user`";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "hr14.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "hr12.php?url_user=".$row_rst1['id']."&url_usid=".$HTTP_POST_VARS["hurl_usid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
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
      <td width="145">Aktionen W&auml;hlen:</td>
      <td width="374"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <img src="picture/b_drop.png" width="16" height="16"> </td>
      <td width="211"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <img src="picture/Cfinclude.gif" width="18" height="18"> 
          <input name="edit1" type="submit" id="edit1" value="&auml;ndern">
        </div></td>
    </tr>
    <tr> 
      <td>Bewertungsgruppe</td>
      <td><select name="bewertungsgruppe" id="bewertungsgruppe">
          <?php
do {  
?>
          <option value="<?php echo $row_rst6['id']?>"<?php if (!(strcmp($row_rst6['id'], $row_rst5['gruppe']))) {echo "SELECTED";} ?>><?php echo $row_rst6['name']?></option>
          <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </select></td>
      <td><div align="left">L&ouml;schenkennzeichen 
          <input <?php if (!(strcmp($row_rst5['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1">
        </div></td>
    </tr>
    <tr> 
      <td>Zeitraum</td>
      <td><input name="von" type="text" id="von" value="<?php echo $row_rst5['von']; ?>" size="20" maxlength="10"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'von\', \'<?php echo (PMA_MYSQL_INT_VERSION >= 40100 && substr($type, 0, 9) == 'date') ? 'date' : substr($type, 0, 9); ?>\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        bis 
        <input name="bis" type="text" id="bis" value="<?php echo $row_rst5['bis']; ?>" size="20" maxlength="10"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'bis\', \'<?php echo (PMA_MYSQL_INT_VERSION >= 40100 && substr($type, 0, 9) == 'date') ? 'date' : substr($type, 0, 9); ?>\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script> </td>
      <td>erstellt durch: <img src="picture/iconchance_16x16.gif" width="16" height="16"> 
              <?php
do {  
?>
              <?php if (!(strcmp($row_rst6['id'], $row_rst5['erstellt']))) {echo $row_rst6['name'];}?> <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
</td>
    </tr>
    <tr> 
      <td>Pr&auml;mie</td>
      <td> <input name="textfield" type="text" value="<?php echo $row_rst5['pramie']; ?>">
        &euro; 
        <input name="hpra_id" type="hidden" id="hpra_id" value="<?php echo $row_rst5['pra_id']; ?>"></td>
      <td>erstellt am: <?php echo $row_rst5['datum']; ?></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
  <input type="hidden" name="MM_update" value="form2">
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="68"><em><strong>Benutzer-ID</strong></em></td>
    <td width="104"><em><strong>Name</strong></em></td>
    <td width="131"><em><strong>aktiv/ Status</strong></em></td>
    <td width="27"><em></em></td>
    <td width="232"><em> <strong>erworbener Pr&auml;mienanteil im Zeitraum</strong></em></td>
    <td width="168"> <div align="right">Aktion</div></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <?php if ($row_rst2['gruppe']!=$group){
 
     ?>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="5" nowrap="nowrap"><img src="picture/s_tbl.png" width="16" height="16"> 
        <em>Werk: 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst6['id'], $row_rst2['gruppe']))) {echo $row_rst6['name']; }
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </em></td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
    <?php }?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['id']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16">&nbsp; 
        <?php echo $row_rst2['name']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input <?php if (!(strcmp($row_rst2['aktiviert'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox"> 
        <?php 
		
		mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst3 = "SELECT bewertung.id_bew FROM bewertung WHERE bewertung.id_user='$row_rst2[id]' and bewertung.datum >=date(curdate()-14)";
$rst3 = mysql_query($query_rst3, $psdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

$maxRows_rst4 = 12;
$pageNum_rst4 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst4'])) {
  $pageNum_rst4 = $HTTP_GET_VARS['pageNum_rst4'];
}
$startRow_rst4 = $pageNum_rst4 * $maxRows_rst4;

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst4 = "SELECT * FROM bewertung WHERE bewertung.id_user='$row_rst2[id]'  AND bewertung.datum >='$row_rst5[von]' AND bewertung.datum<='$row_rst5[bis]' AND bewertung.lokz=0";
$query_limit_rst4 = sprintf("%s LIMIT %d, %d", $query_rst4, $startRow_rst4, $maxRows_rst4);
$rst4 = mysql_query($query_limit_rst4, $psdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);

if (isset($HTTP_GET_VARS['totalRows_rst4'])) {
  $totalRows_rst4 = $HTTP_GET_VARS['totalRows_rst4'];
} else {
  $all_rst4 = mysql_query($query_rst4);
  $totalRows_rst4 = mysql_num_rows($all_rst4);
}
$totalPages_rst4 = ceil($totalRows_rst4/$maxRows_rst4)-1;

	
		?>
        <?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
        <img src="picture/st1.gif" alt="letzte Bewertung innerhalb der letzten Tage durchgef&uuml;hrt" width="15" height="15"> 
        <?php } // Show if recordset not empty ?> <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
        <img src="picture/st3.gif" alt="letzte Bewertung &auml;lter als 14 Tage" width="15" height="15"> 
        <?php } // Show if recordset empty ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php 
	  /* Bewerechnungsprämienanteil */
	  
	  
	  
	  
	  
	 /* Bewerechnungsprämienanteil */
	  
	  ?>
        <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
        keine Bewertungen gefunden&nbsp; 
        <?php $pramienanteil=0;?>
        <br>
        <?php } // Show if recordset empty ?> <?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
        
		<?php 
		$pramienpunkte=0; 
		$pramienmax=0;
		?>
		
		<?php do { ?>
        <?php $pramienpunkte= $pramienpunkte+$row_rst4['cat1']+$row_rst4['cat2']+$row_rst4['cat3']+$row_rst4['cat4']+$row_rst4['cat5']; 
  			$pramienmax=15+$pramienmax; 
		/* 	echo "z".$pramienpunkte." - ";    Test*/
			?>
			
		
        <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
		<?php  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }?>
		
		
		
		
        <?php $pramienanteil=$pramienpunkte/$pramienmax*100;?>
        <?php $gesamtpramienanteil= $gesamtpramienanteil+$pramienanteil;?>
        <?php echo round($pramienanteil,1) ?> % <?php echo round($pramienpunkte) ?> 
        Pkt. von max.<?php echo round($pramienmax,1) ?> Pkt 
        <?php } // Show if recordset not empty ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_usid" type="hidden" id="hurl_usid" value="<?php echo $row_rst2['id']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit2" value="Bewertung">
          <?php mysql_free_result($rst3); ?>
        </div></td>
    </tr>
  </form>
  <?php $group = $row_rst2['gruppe'];?>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
 <?php  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }?>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Summe: <?php echo round($gesamtpramienanteil,1);?> % Anteile</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
<p><br>
</p>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="67"><em><strong>Benutzer-ID</strong></em></td>
    <td width="102"><em><strong>Name</strong></em></td>
    <td width="118"><em><strong>aktiv/ Status</strong></em></td>
    <td width="22"><em></em></td>
    <td width="257"><em> <strong>erworbener Pr&auml;mienanteil im Zeitraum</strong></em></td>
    <td width="164"> <div align="right">Aktion</div></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <?php if ($row_rst2['gruppe']!=$group){
 
     ?>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="5" nowrap="nowrap"><img src="picture/s_tbl.png" width="16" height="16"> 
        <em>Werk: 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst6['id'], $row_rst2['gruppe']))) {echo $row_rst6['name']; }
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </em></td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
    <?php }?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['id']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16">&nbsp; 
        <?php echo $row_rst2['name']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <input <?php if (!(strcmp($row_rst2['aktiviert'],1))) {echo "checked";} ?> type="checkbox" name="checkbox2" value="checkbox"> 
        <?php 
		
		mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst3 = "SELECT bewertung.id_bew FROM bewertung WHERE bewertung.id_user='$row_rst2[id]' and bewertung.datum >=date(curdate()-14)";
$rst3 = mysql_query($query_rst3, $psdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

$maxRows_rst4 = 12;
$pageNum_rst4 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst4'])) {
  $pageNum_rst4 = $HTTP_GET_VARS['pageNum_rst4'];
}
$startRow_rst4 = $pageNum_rst4 * $maxRows_rst4;

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst4 = "SELECT * FROM bewertung WHERE bewertung.id_user='$row_rst2[id]'  AND bewertung.datum >='$row_rst5[von]' AND bewertung.datum<='$row_rst5[bis]' AND bewertung.lokz=0";
$query_limit_rst4 = sprintf("%s LIMIT %d, %d", $query_rst4, $startRow_rst4, $maxRows_rst4);
$rst4 = mysql_query($query_limit_rst4, $psdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);

if (isset($HTTP_GET_VARS['totalRows_rst4'])) {
  $totalRows_rst4 = $HTTP_GET_VARS['totalRows_rst4'];
} else {
  $all_rst4 = mysql_query($query_rst4);
  $totalRows_rst4 = mysql_num_rows($all_rst4);
}
$totalPages_rst4 = ceil($totalRows_rst4/$maxRows_rst4)-1;

	
		?>
        <?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
        <img src="picture/st1.gif" alt="letzte Bewertung innerhalb der letzten Tage durchgef&uuml;hrt" width="15" height="15"> 
        <?php } // Show if recordset not empty ?>
        <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
        <img src="picture/st3.gif" alt="letzte Bewertung &auml;lter als 14 Tage" width="15" height="15"> 
        <?php } // Show if recordset empty ?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php 
	  /* Bewerechnungsprämienanteil */
	  
	  
	  
	  
	  
	 /* Bewerechnungsprämienanteil */
	  
	  ?>
        <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
        keine Bewertungen gefunden&nbsp; 
        <?php $pramienanteil=0;
		$auszahlung=0;
		?>
        <br> 
        <?php } // Show if recordset empty ?>
        <?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
        <?php 	$pramienpunkte=0; 
				$pramienmax=0;
				$pramienanteil=0;
		?>
        <?php do { ?>
        <?php $pramienpunkte= $pramienpunkte+$row_rst4['cat1']+$row_rst4['cat2']+$row_rst4['cat3']+$row_rst4['cat4']+$row_rst4['cat5']; 
  			$pramienmax=15+$pramienmax; ?>
    
        <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
        <?php  $rows = mysql_num_rows($rst4);
		  if($rows > 0) {
			  mysql_data_seek($rst4, 0);
			  $row_rst4 = mysql_fetch_assoc($rst4);
		  }?>
        <?php $pramienanteil=$pramienpunkte/$pramienmax*100;?>
        <?php $auszahlung = $pramienanteil/$gesamtpramienanteil* $row_rst5['pramie'];?>
        <?php echo round($pramienanteil/$gesamtpramienanteil*100,1) ?> % = <strong><?php echo round($auszahlung,2); ?> 
        &euro;</strong> 
        <?php $gesamtauszahlung=$gesamtauszahlung+$auszahlung;?>
		
		<?php } // Show if recordset not empty ?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><a href="pdfhr13.php?url_user=<?php echo $row_rst1['id']; ?>&url_userid=<?php echo $row_rst2['id']; ?>&url_pramie=<?php echo round($auszahlung,2); ?>&url_praid=<?php echo $row_rst5['pra_id']; ?>" target="_blank">Bewertung 
          drucken</a></div></td>
    </tr>
  </form>
  <?php $group = $row_rst2['gruppe'];?>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Umrechnung 100 % Anteile = <?php echo round($gesamtauszahlung,2) ?> &euro; 
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
<p><br>
  <br>
  <br>
  &nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
</p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Mitarbeiter f&uuml;r diese Gruppe angelegt.</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst4);

mysql_free_result($rst6);


mysql_free_result($rst5);


?>
</p>

  <?php include("footer.tpl.php"); ?>
