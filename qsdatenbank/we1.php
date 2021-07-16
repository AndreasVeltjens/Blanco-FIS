<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "we1";
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

if (!isset($HTTP_POST_VARS['datum']) or strlen($HTTP_POST_VARS['datum'])<=8){$HTTP_POST_VARS['datum']=date("Y-m-d",time()-6640000);}
if (!isset($HTTP_POST_VARS['we_lokz']) or $HTTP_POST_VARS['we_lokz']<>1 ) {$HTTP_POST_VARS['we_lokz']=0;}


if ((isset($HTTP_POST_VARS["neu"] ))) {
  $insertSQL = sprintf("INSERT INTO wareneingang (we_user, we_datum, we_bezeichnung, we_nummer) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user2'], "int"),
                       GetSQLValueString(date("Y-m-d",time()), "date"),
					   GetSQLValueString("neue Wareneingangspr&uuml;fung", "text"),
                       GetSQLValueString(" Neu", "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ($HTTP_POST_VARS['suchtext']<>""){$suchtext=" AND ( wareneingang.we_lieferant like '%$HTTP_POST_VARS[suchtext]%' or wareneingang.we_bezeichnung like '%$HTTP_POST_VARS[suchtext]%' or wareneingang.we_nummer like '%$HTTP_POST_VARS[suchtext]%') ";}

$maxRows_rst2 = 25;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM wareneingang WHERE wareneingang.we_vorlage ='0' AND wareneingang.we_lokz='$HTTP_POST_VARS[we_lokz]' $suchtext AND we_datum> '$HTTP_POST_VARS[datum]' ORDER BY we_nummer";
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

$maxRows_rst3 = 50;
$pageNum_rst3 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst3'])) {
  $pageNum_rst3 = $HTTP_GET_VARS['pageNum_rst3'];
}
$startRow_rst3 = $pageNum_rst3 * $maxRows_rst3;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM wareneingang WHERE wareneingang.we_vorlage ='1' AND wareneingang.we_lokz='$HTTP_POST_VARS[we_lokz]'  $suchtext AND we_datum> '$HTTP_POST_VARS[datum]' ORDER BY we_lieferant , we_datum desc";
$query_limit_rst3 = sprintf("%s LIMIT %d, %d", $query_rst3, $startRow_rst3, $maxRows_rst3);
$rst3 = mysql_query($query_limit_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);

if (isset($HTTP_GET_VARS['totalRows_rst3'])) {
  $totalRows_rst3 = $HTTP_GET_VARS['totalRows_rst3'];
} else {
  $all_rst3 = mysql_query($query_rst3);
  $totalRows_rst3 = mysql_num_rows($all_rst3);
}
$totalPages_rst3 = ceil($totalRows_rst3/$maxRows_rst3)-1;

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "we11.php?url_user=".$row_rst1['id']."&url_we_id=".$HTTP_POST_VARS['hurl_we_id'];
  header(sprintf("Location: %s", $updateGoTo));
}


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>





<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="4"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="4"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td colspan="3">Suche: 
        <input name="suchtext" type="text" id="suchtext2" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>"> 
        <input name="suche" type="submit" id="suche" value="suchen"> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="110"><img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"> 
      </td>
      <td colspan="3"> <div align="right">&nbsp;&nbsp; 
          <input name="aktualisieren" type="submit" id="aktualisieren" value="aktualisieren">
          &nbsp; 
          <input name="neu" type="submit" id="neu" value="neue Wareneingangspr&uuml;fung anlegen">
          <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $url_user; ?>">
          <input type="hidden" name="MM_insert" value="form2">
        </div>
        <div align="right"> </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td rowspan="3"><img src="picture/W7_Img_Install.jpg" width="66" height="66"></td>
      <td width="426">ab Datum 
        <input name="datum" type="text" id="datum3" value="<?php echo $HTTP_POST_VARS['datum']; ?>"></td>
      <td width="440"><a href="../documents/AA,%20PA,%20BA,%20PP%20als%20PDFs/AA-1364%20Warenannahme%20und%20Weiterleitung.pdf" target="_blank"><img src="picture/pdf_icon.gif" width="16" height="19" border="0">1344 
        AA Wareneingang <img src="picture/pdf_icon.gif" width="16" height="19" border="0">1364 
        AA Weiterleitung <br>
        </a> <img src="picture/pdf_icon.gif" width="16" height="19" border="0"><a href="../documents/AA,%20PA,%20BA,%20PP%20als%20PDFs/PA-1346%20Prufung%20prufpflichtiger%20Ware.pdf" target="_blank">1346 
        WE Pr&uuml;fanweisung</a> <a href="../documents/AA-1364%2001-02-2008%20Warenannahme%20und%20Weiterleitung.pdf" target="_blank"><img src="picture/pdf_icon.gif" width="16" height="19" border="0"></a><a href="../documents/AA,%20PA,%20BA,%20PP%20als%20PDFs/AA-1345%20Lenkung%20fehlerhafter%20Produkte%20(allgemein).pdf" target="_blank">1345 
        Lenkung u. Leitung fehlerhafter Produkte</a></td>
      <td width="4">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF">nur gel&ouml;schte anzeigen 
<input <?php if (!(strcmp(1,$HTTP_POST_VARS['we_lokz']))) {echo "checked";} ?> name="we_lokz" type="checkbox" id="we_lokz" value="1"></td>
      <td bgcolor="#FFFFFF">&nbsp; </td>
      <td width="4" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF"><font size="1">Suche nach Lieferant, Materialnummer 
        oder Bezeichnung</font></td>
      <td width="4" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
  </table>
</form>
<hr>

  
<?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
<font color="#FF0000">Liste der pr&uuml;fpflichtigen QS Wareneingangspr&uuml;fungen</font><br>
<table width="980" border="0" cellpadding="0" cellspacing="1">
  <tr bgcolor="#FFFFFF" class="value"> 
    <td width="125"><em><strong>Bezeichnung</strong></em></td>
    <td width="151"><em><strong>Lieferant</strong></em></td>
    <td width="123"><em><strong>Pr&uuml;ffrist bzw. Umfang</strong></em></td>
    <td width="107"><div align="center"><strong>Datum</strong></div></td>
    <td width="145">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php   if ($row_rst3['we_lieferant']!=$gruppe){?>
  <tr bgcolor="#CCCCCC" > 
    <td colspan="8" nowrap="nowrap"><em><br>
      <img src="picture/iconFixedprice_16x16.gif" width="16" height="16"><?php echo $row_rst3['we_lieferant']; ?></em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if  ($row_rst3['we_lokz']==1){?>
        <img src="picture/del.gif" width="15" height="15"><img src="picture/error.gif" alt="Wareneingang gel&ouml;scht" width="16" height="16"> 
        <?php } ?>
        <?php if  ($row_rst3['we_frei']==1){?>
        <img src="picture/st1.gif" width="15" height="15"> 
        <?php } elseif ($row_rst3['we_frei']==0) { ?>
        <img src="picture/st2.gif" width="15" height="15"> 
         <?php } elseif ($row_rst3['we_frei']==2) { ?>
        <img src="picture/st3.gif" width="15" height="15"> 

		<?php }else{?>
        <img src="picture/st5.gif" width="15" height="15"> 
        <?php }?>
        <?php echo wordwrap($row_rst3['we_nummer'],30,"<br>",250); ?>- <?php echo ($row_rst3['we_bezeichnung']); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo ($row_rst3['we_lieferant']); ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst3['we_pruefumfang'],30,"<br>",250); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfetikett9.php?url_we_id=<?php echo $row_rst3['we_id']; ?>" target="_blank"> 
        </a><a href="pdfetikett9.php?url_we_id=<?php echo $row_rst3['we_id']; ?>" target="_blank"><img src="picture/information.gif" width="16" height="16" border="0"></a><?php echo $row_rst3['we_datum']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_we_id" type="hidden" id="hurl_we_id" value="<?php echo $row_rst3['we_id']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php
    $gruppe=$row_rst3['we_lieferant'];
   } while ($row_rst3 = mysql_fetch_assoc($rst3)); ?>
</table>
<?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Vorlage vorhanden.</font></p>
  <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>
<hr>

  <p>&nbsp;</p><?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<font color="#009900">Liste der Wareneingangspr&uuml;fungen</font><br>
  
<table width="980" border="0" cellpadding="0" cellspacing="1">
  <tr bgcolor="#FFFFFF" class="value"> su
    <td width="125"><em><strong>Bezeichnung</strong></em></td>
    <td width="151"><em><strong>Lieferant</strong></em></td>
    <td width="123"><em><strong>Pr&uuml;ffrist bzw. Umfang</strong></em></td>
    <td width="107"><div align="center"><strong>Datum</strong></div></td>
    <td width="145">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php   if ($row_rst2['we_nummer']!=$gruppe){?>
  <tr bgcolor="#CCCCCC" > 
    <td colspan="8" nowrap="nowrap"><em><br>
      <img src="picture/iconFixedprice_16x16.gif" width="16" height="16"><?php echo $row_rst2['we_nummer']; ?></em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if  ($row_rst2['we_frei']==1){?>
        <img src="picture/st1.gif" width="15" height="15"> 
        <?php } elseif ($row_rst2['we_frei']==0) { ?>
        <img src="picture/st2.gif" width="15" height="15"> 
        <?php }else{?>
        <img src="picture/st3.gif" width="15" height="15"> 
        <?php }?>
        <?php echo wordwrap($row_rst2['we_bezeichnung'],30,"<br>",250); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst2['we_lieferant'],30,"<br>",250); ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst2['we_pruefumfang'],30,"<br>",250); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfetikett9.php?url_we_id=<?php echo $row_rst2['we_id']; ?>" target="_blank"><img src="picture/information.gif" width="16" height="16" border="0"></a> 
        <?php echo $row_rst2['we_datum']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_we_id" type="hidden" id="hurl_we_id" value="<?php echo $row_rst2['we_id']; ?>">
          <img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php
    $gruppe=$row_rst2['we_nummer'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
  <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

?>
<?php include("footer.tpl.php"); ?>
