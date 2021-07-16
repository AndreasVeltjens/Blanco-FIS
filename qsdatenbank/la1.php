<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "la1";
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

if (isset($HTTP_POST_VARS['suchtext'])){
$url_kontierung;
$materialnr=round($HTTP_POST_VARS['suchtext'],0);
$suchtxt=" AND (artikeldaten.Bezeichnung like '%$HTTP_POST_VARS[suchtext]%' or artikeldaten.Nummer ='$materialnr' )";
} else {$suchtxt="";}

if ($url_kontierung<>""){
$suchtxt=$suchtxt." and artikeldaten.Kontierung like '$url_kontierung' ";
} 


$maxRows_rst2 = 500;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert=1 $suchtxt ORDER BY artikeldaten.Kontierung,artikeldaten.Gruppe, artikeldaten.Bezeichnung asc ";
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
/* if (($url_kontierung=="")){$url_kontierung=0;} */

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>


<script language="JavaScript" type="text/JavaScript">
function sf(){document.form2.suchtext.focus()}
</script>
 

  
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong>la1-klassische Fertigungsmeldungen</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
    <tr> 
      <td width="174">Aktionen W&auml;hlen:</td>
      <td width="211"> <img src="picture/error.gif" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="345"> <div align="right">Suche:<img src="picture/icon_scan.gif" alt="Dieses Feld ist Barcodelesef&auml;hig"> 
          <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
          <input type="submit" name="Submit" value="finden">
        </div></td>
    </tr>
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
  </form>
</table>
  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellspacing="0" cellpadding="4">
  <tr> 
    <td colspan="2">Liste der Materialnummern: <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?></td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td><em><strong>Bezeichnung - Material</strong></em></td>
    <td><em><strong>Bezeichnung - Material- </strong></em></td>
  </tr>
  <tr> 
    <td width="730"> <table width="350" border="0" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <?php $i=0;?>
        <?php do { 
		
		?>
        <?php   if ($row_rst2['Kontierung']!=$kontierung){?>
        <tr bgcolor="#C9C9C9" onMouseDown="setPointer(this, 1, 'click', '#C9C9C9', '#CCCCFF', '#FFCC99');" onMouseOver="setPointer(this, 1, 'over', '#C9C9C9', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#C9C9C9', '#CCCCFF', '#FFCC99');"> 
          <td colspan="8" nowrap="nowrap"><em><a href="la1.php?url_user=<?php echo $row_rst1['id']  ?>" target="_self"><img src="picture/auftrag.gif" width="20" height="14" border="0" align="absmiddle">alles 
            anzeigen</a><br>
            <img src="picture/add.gif" width="15" height="14"> <strong><a href="la1.php?url_user=<?php echo $row_rst1['id'] ?>&url_kontierung=<?php echo $row_rst2['Kontierung'] ?>" target="_self"><font size="4"><img src="picture/anforderung_24x24.png" width="24" height="24" border="0" align="absmiddle"> 
            <?php echo $row_rst2['Kontierung']; ?></font></a></strong></em></td>
        </tr>
        <?php 	}?>
        <?php 		
				if (round($totalRows_rst2/2,0)==$i){
				 ?>
      </table></td>
    <td> <table width="519"  border="0" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <?php }?>
        <?php if ( ($row_rst2['Kontierung']==$url_kontierung)  or $HTTP_POST_VARS['suchtext']<>""){?>
        <?php   if ($row_rst2['Gruppe']!=$gruppe){?>
        <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
          <td colspan="9" nowrap="nowrap"    bgcolor="#EEEEEE"><em><img src="picture/1.gif" width="20" height="10"><img src="picture/add.gif" width="15" height="14"> 
            <strong><img src="picture/folder_open.gif" width="16" height="16" border="0" align="absmiddle"><?php echo $row_rst2['Gruppe']; ?></strong></em></td>
        </tr>
        <?php 	}?>
        <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td width="170"   bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/2.gif" width="40" height="10"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer']; ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer']; ?>.png" alt="Bild anzeigen" width="40" height="30" border="0" align="texttop"></a></td>
            <td   width="117" bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"><?php echo $row_rst2['Bezeichnung']; ?></div></td>
            <td  width="18"  bgcolor="#EEEEEE" nowrap="nowrap"><a href="pdfetikett11.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/anschreiben.gif" alt="Lagerplatzbeschriftung" width="18" height="18" border="0"></a><a href="pdfetikett12.php?url_artikelid=<?php echo $row_rst2['artikelid']; ?>" target="_blank"><img src="picture/ASPResponseWrite.gif" alt="Lagerplatz Karte" width="18" height="18" border="0"></a></td>
            <td  width="95"  bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst2['Nummer']; ?></div></td>
            <td  width="76"  bgcolor="#EEEEEE" nowrap="nowrap"> <input name="neu" type="submit" id="neu" value="bearbeiten"></td>
            <td   width="24" bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"> 
                <input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $url_user; ?>">
                <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
              </div></td>
          </tr>
        </form>
        <?php
		
		}/* ende kontierung */
    $gruppe=$row_rst2['Gruppe'];
	 $kontierung=$row_rst2['Kontierung'];
	
	$i=$i+1;
	
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
      </table></td>
  </tr>
</table>
<br>
<?php } // Show if recordset not empty ?>
Listanzeige <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?> 
<?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

?>
<?php include("footer.tpl.php"); ?>
