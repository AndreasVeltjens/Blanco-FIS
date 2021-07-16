<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "co12";
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];

/* wenn kein Selectionsdatum gefunden wird, dann nehme Datensatzvorlage  */
if ($HTTP_POST_VARS['datumselect']=="" ){$HTTP_POST_VARS['datumselect']=date("Y-m-d",time());}
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
$query_rst4 = "SELECT * FROM arbeitsplatz WHERE arbeitsplatz.arb_id='$url_arb_id' ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;

if ($row_rst4['arb_plan']==1){
$order = " produktionsprog.reihenfolge , ";
} else {
$order = " (produktionsprog.stueck - produktionsprog.avis) , ";
}

if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

if ($HTTP_POST_VARS['suche']!=="" ){
$suche = " and  produktionsprog.bezeichnung like '%$HTTP_POST_VARS[suche]%'";
}else{ 
$suche ="";





$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
	if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
	  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
	}
	$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;


}

$maxRows_rst2 = 50;;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

$datumselect=date("YW",gmmktime(0,0,0,substr($HTTP_POST_VARS['datumselect'],5,2),substr($HTTP_POST_VARS['datumselect'],8,2),substr($HTTP_POST_VARS['datumselect'],0,4)));
/* echo $datumselect; */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM produktionsprog WHERE produktionsprog.typ = '$url_arb_id' and yearweek(produktionsprog.datum)='$datumselect' $suche ORDER BY $order produktionsprog.bezeichnung ASC";
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);





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

if ((isset($HTTP_POST_VARS["ww"])) ) {
$updateGoTo = "la17.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS['hurl_artikelid']."&goback=$la&url_arb_id=$HTTP_POST_VARS[hurl_arb_id]";
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "co11.php?url_user=".$row_rst1['id'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "co13.php?url_user=".$row_rst1['id']."&url_arb_id=".$HTTP_POST_VARS["hurl_arb_id"]."&url_pp_id=".$HTTP_POST_VARS["hurl_pp_id"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}






include("function.tpl.php");



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<style type="text/css">
<!--
.checkboxstyle {
	background-color: #CCCCCC;
}
-->
</style>



<form name="form2" method="post" action="">
  <table width="800" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong>co12- Fertigungsr&uuml;ckmeldung anzeigen</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="229" rowspan="5"><img src="../documents/arbeitsplatzbilder/<?php echo $row_rst4['arb_kz'] ?>.png" width="200" height="160"></td>
      <td width="307"> <div align="left"> <img src="picture/error.gif" width="16" height="16"> 
          <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck">
          <input name="imageField" type="image" src="picture/iconRelistBlu_16x16.gif" width="16" height="16" border="0">
          <input type="submit" name="Submit" value="Ansicht aktualisieren">
        </div></td>
      <td width="264"> <div align="right"> 
          <input name="datumselect" type="text" value="<?php echo $HTTP_POST_VARS['datumselect']; ?>">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datumselect\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          jjjj-mm-tt </div></td>
    </tr>
    <tr> 
      <td><strong><?php echo $row_rst4['arb_kz']; ?></strong></td>
      <td><div align="right">geplanter Arbeitsplatz: <strong><?php echo $row_rst4['arb_plan']; ?></strong></div></td>
    </tr>
    <tr> 
      <?php 
	  if (isset($HTTP_POST_VARS['von'])){
$url_sn_von=$HTTP_POST_VARS['von'];
 }else {$url_sn_von=($row_rst2['fsn']+1);}
	
 if (isset($HTTP_POST_VARS['bis'])){
$url_sn_bis=$HTTP_POST_VARS['bis'];
	  }else {$url_sn_bis=($row_rst2['fsn']+21);}
	  ?>
      <td><strong><?php echo $row_rst4['arb_name']; ?><br>
        Kostenstelle <?php echo $row_rst4['arb_kostenstelle']; ?></strong></td>
      <td><div align="right"><a href="co15.gantthourex1.php?datum=<?php echo $HTTP_POST_VARS['datumselect']; ?>&arbeitsplatztyp=<?php echo $row_rst4['arb_id']; ?>" target="_blank">
          <img src="picture/column-chart.gif" width="16" height="16" border="0">Diagramm 
          anzeigen </a></div></td>
    </tr>
    <tr> 
      <td>Material finden: </td>
      <td><div align="right"><a href="../documents/Vorlagen/ww-checkliste.doc" target="_blank"><img src="picture/bt_download_PDF.gif" width="17" height="18" border="0"> 
          WW-Checkliste</a> </div></td>
    </tr>
    <tr> 
      <td><input name="url_artikelid" type="hidden" id="url_artikelid" value="<?php echo $url_artikelid; ?>"> 
        <input name="suche" type="text" id="suche" value="<?php echo $HTTP_POST_VARS['suche']; ?>"> 
        <input name="suchen" type="submit" id="suchen" value="suchen"></td>
      <td><div align="right"><a href="pdfetikett5.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>" target="_blank"><img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          Hinweis drucken</a></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"> </div></td>
    </tr>
  </table>
  </form>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Fertigungsmeldungen<br>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
Fertigungsmeldung von <?php echo ($startRow_rst2 + 1) ?> bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?>- 
insgesamt: <?php echo $totalRows_rst2 ?> Fertigungsmeldungen erfasst.<br>
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr>
    <td width="85">Folge</td>
    <td width="85">Bezeichnung</td>
    <td width="85">Material</td>
    <td width="15" bgcolor="#CEE1EC"><div align="center">Mo</div></td>
    <td width="15"><div align="center">Di</div></td>
    <td width="15" bgcolor="#CEE1EC">Mi</td>
    <td width="15">Do</td>
    <td width="15" bgcolor="#CEE1EC">Fr</td>
    <td width="15">Sa</td>
    <td width="15" bgcolor="#CEE1EC">So</td>
    <td width="40"><div align="center"><em><strong>min<br>
        Bestand</strong></em></div></td>
    <td width="40"> <p align="center"><em><strong>max<br>
        </strong></em><em><strong>Bestand</strong></em></p></td>
    <td width="40"><div align="center"><em><strong>Bestand<br>
        aktuell</strong></em></div></td>
    <td width="30"><em>Status</em></td>
    <td width="50"><em><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
      Benutzer</em></td>
    <td width="0">&nbsp;</td>
  </tr>
  <?php do { 
  
  
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM artikeldaten, produktionsprog WHERE produktionsprog.nummer=artikeldaten.Nummer AND produktionsprog.pp_id='$row_rst2[pp_id]'";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT * FROM pp_www, artikeldaten, produktionsprog WHERE produktionsprog.pp_id='$row_rst2[pp_id]' AND yearweek(pp_www.datum)='$datumselect'  AND produktionsprog.nummer=artikeldaten.Nummer AND artikeldaten.artikelid =pp_www.materialnummer";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);?>
  
  
  
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');">
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['reihenfolge']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><em><a href="../documents/materialbilder/<?php echo $row_rst2['nummer'] ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst2['nummer'] ?>.png" alt="<?php echo $row_rst2['beschreibung'] ?>" width="30" height="20" border="0"></a> 
        </em><?php echo substr($row_rst2['bezeichnung'],0,50); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['nummer']; ?></td>
      <td nowrap="nowrap"    bgcolor="#CEE1EC"><?php echo $row_rst2['mog1']+$row_rst2['mog2']+$row_rst2['mog3']; ?>&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['dig1']+$row_rst2['dig2']+$row_rst2['dig3']; ?>&nbsp;</td>
      <td    bgcolor="#CEE1EC" nowrap="nowrap"><?php echo $row_rst2['mig1']+$row_rst2['mig2']+$row_rst2['mig3']; ?>&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['dog1']+$row_rst2['dog2']+$row_rst2['dog3']; ?>&nbsp;</td>
      <td    bgcolor="#CEE1EC" nowrap="nowrap"><?php echo $row_rst2['frg1']+$row_rst2['frg2']+$row_rst2['frg3']; ?>&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['sag1']+$row_rst2['sag2']+$row_rst2['sag3']; ?>&nbsp;</td>
      <td    bgcolor="#CEE1EC" nowrap="nowrap"><?php echo $row_rst2['sog1']+$row_rst2['sog2']+$row_rst2['sog3']; ?>&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><strong><?php echo $row_rst2['palettenL']; ?></strong></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><strong><?php echo $row_rst2['avis']; ?></strong></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo $row_rst2['stueck']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/<?php if ($row_rst2['stueck']-$row_rst2['avis']<0) {echo "st1.gif";}else{echo "st3.gif";} ?>" alt="<?php echo "Fertigungsfreigabe ".$row_rst2['signatur']; ?>" width="15" height="15" align="absmiddle"> 
        - <img src="picture/<?php if ($row_rst2['stueck']-$row_rst2['palettenL']<0) {echo "st2.gif";}else{echo "st6.gif";} ?>" alt="<?php echo "Mindestbestandskennzeichen.".$row_rst2['signatur']; ?>" width="15" height="15" align="absmiddle"> 
        - <?php echo substr(utf8_decode($row_rst2['fnotes']),0,50); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php
do {  
?>
        <div align="center"> 
          <?php if (!(strcmp($row_rst3['id'], $row_rst2['fuser']))) {echo $row_rst3['name'];}?>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?></select>
          </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <?php if ($row_rst2['bemerkung']<>"") {?>
          <img src="picture/icon_info.gif" alt="<?php $row_rst2['bemerkung']?>" width="14" height="14"><?php echo substr($row_rst2['bemerkung'],0,60); ?> 
          <?php }?>
          <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $url_user;?>">
          <input name="hurl_arb_id" type="hidden" id="hurl_arb_id" value="<?php echo $row_rst2['typ']; ?>">
          <input name="hurl_pp_id" type="hidden" id="hurl_pp_id" value="<?php echo $row_rst2['pp_id']; ?>">
          <input name="hurl_artikelid" type="hidden" id="hurl_pp_id" value="<?php echo $row_rst5['artikelid']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
          <a href="la17.php?url_user=<?php echo $row_rst1['id'] ?>&url_artikelid=<?php echo $row_rst5['artikelid']; ?>&goback=<?php echo $la ?>&url_arb_id=<?php echo $url_arb_id; ?>&url_www_id=<?php echo $row_rst6['www_id']; ?>"><img src="picture/ico_anruflisten.gif" width="16" height="16" border="0"></a> 
          <?php } // Show if recordset not empty ?>
          <?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
          <input name="ww" type="submit" id="ww" value="WW">
          <?php } // Show if recordset empty ?>
          
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
  </form></tr>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
Fertigungsmeldung von <?php echo ($startRow_rst2 + 1) ?>bis <?php echo min($startRow_rst2 + $maxRows_rst2, $totalRows_rst2) ?>- insgesamt: <?php echo $totalRows_rst2 ?> Fertigungsmeldungen erfasst.<br>
<?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Daten vorhanden. Bitte Produktionsprogamm f&uuml;r 
  aktuelle Kalenderwoche festlegen.  </font>
  <?php } // Show if recordset empty ?>
<p>Legende:</p>
<p><img src="picture/st1.gif"  width="15" height="15" align="absmiddle"> Fertigungsfreigabe<br>
  <img src="picture/st3.gif"  width="15" height="15" align="absmiddle"> Fertigung 
  nicht freigegeben<br>
  <img src="picture/st2.gif"  width="15" height="15" align="absmiddle"> Mindestbestand 
  unterschritten<br>
  <img src="picture/icon_info.gif" alt="<?php $row_rst2['bemerkung']?>" width="14" height="14"> 
  Hinweis zum Fertigungsauftrag vorhanden</p>
<?php include("footer.tpl.php"); ?>
</p>

<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst5);

mysql_free_result($rst6);
mysql_free_result($rst4);
?>
