<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "co14";
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



if ((isset($HTTP_POST_VARS["set"]) )) {


if ($HTTP_POST_VARS['datum']==""){$datumaktuell = "now()";
}else{ $datumaktuell= GetSQLValueString($HTTP_POST_VARS['datum'], "date");

}

  $updateSQL = sprintf("UPDATE produktionsprog SET  avis=%s, gemg=%s, gema=%s WHERE pp_id=%s ",
                       
					   GetSQLValueString($HTTP_POST_VARS['avis'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['gemg'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['gema'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_pp_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
$oktxt="offene und gemeldete St&uuml;ckzahl sowie Bedarf aktualisiert.";
}



if ((isset($HTTP_POST_VARS["newpp"]))&& ($HTTP_POST_VARS['checkbox']==1) ) {

$datumselect=date("YW",gmmktime(0,0,0,substr($HTTP_POST_VARS['datumselect'],5,2),substr($HTTP_POST_VARS['datumselect'],8,2),substr($HTTP_POST_VARS['datumselect'],0,4)));
/* echo $datumselect; */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM produktionsprog WHERE yearweek(produktionsprog.datum)='$datumselect' ORDER BY produktionsprog.bezeichnung ASC";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);
do{


$insertSQL = sprintf("INSERT INTO `qsdb`.`produktionsprog` (`nummer`, `bezeichnung`, `stueck`, `avis`, `typ`, `paletten`, `palettenL`, `zeit` , `von`, `bis`, `artikel_id`, `lokz`, `fuser`, `datum`) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       
					   	GetSQLValueString($row_rst2['nummer'],"text"),
					   GetSQLValueString($row_rst2['bezeichnung'],"text"),
					   GetSQLValueString($row_rst2['stueck'],"text"),
					   GetSQLValueString($row_rst2['avis'],"text"),
					   GetSQLValueString($row_rst2['typ'],"int"),
					   GetSQLValueString($row_rst2['paletten'],"text"),
					   GetSQLValueString($row_rst2['palettenL'],"text"),
					  GetSQLValueString( $row_rst2['zeit'],"int"),
					   GetSQLValueString($row_rst2['von'],"int"),
						 GetSQLValueString($row_rst2['bis'],"int"),
						 GetSQLValueString($row_rst2['artikelid'],"int"),
						  GetSQLValueString($row_rst2['lokz'],"int"),
						GetSQLValueString(0, "int"),
                       	GetSQLValueString($HTTP_POST_VARS['datumselect2'], "date"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $oktxt=$oktxt."<br>Datensatz $row_rst2[nummer] - $row_rst2[bezeichnung] angelegt.";

} while ($row_rst2 = mysql_fetch_assoc($rst2));


$oktxt=$oktxt."<br>Neues Produktionsprogramm komplett angelegt zum  $HTTP_POST_VARS[datumselect2].";
}






$maxRows_rst2 = 300;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

if ($HTTP_POST_VARS['suche']!=="" ){
$suche = " and  produktionsprog.bezeichnung like '%$HTTP_POST_VARS[suche]%'";
}else{ 
$suche ="";

if ($HTTP_POST_VARS['selectarb']>0 ){
$suche =$suche." and  produktionsprog.typ = '$HTTP_POST_VARS[selectarb]'";
}



$maxRows_rst2 = 300;
$pageNum_rst2 = 0;
	if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
	  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
	}
	$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;


}

$maxRows_rst2 = 300;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

$datumselect=date("YW",gmmktime(0,0,0,substr($HTTP_POST_VARS['datumselect'],5,2),substr($HTTP_POST_VARS['datumselect'],8,2),substr($HTTP_POST_VARS['datumselect'],0,4)));
/* echo $datumselect; */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM produktionsprog WHERE yearweek(produktionsprog.datum)='$datumselect' $suche ORDER BY produktionsprog.bezeichnung ASC";
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM arbeitsplatz ";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

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
      <td colspan="3"><strong>co14- alle Fertigungsr&uuml;ckmeldungen anzeigen</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        </font></strong> 
        <?php } // Show if recordset empty ?>
        <strong><font color="#FF0000"><?php echo $errtxt ?><font color="#009900"><?php echo $oktxt; ?></font> 
        </font></strong></td>
    </tr>
    <tr> 
      <td width="173">Aktionen W&auml;hlen:</td>
      <td colspan="2"> <div align="left"> <img src="picture/error.gif" width="16" height="16"> 
          <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck">
          &nbsp;</div>
        </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td width="240">&nbsp;&nbsp; <input name="datumselect" type="text" value="<?php echo $HTTP_POST_VARS['datumselect']; ?>" size="12"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datumselect\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
      <td width="317"><div align="right">
          <input name="newpp" type="submit" id="newpp2" value="neues Programm anlegen">
          <input name="datumselect2" type="text" value="<?php echo $HTTP_POST_VARS['datumselect2']; ?>" size="12">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datumselect2\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          <input type="checkbox" name="checkbox" value="1">
        </div></td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Arbeitsplatz:</td>
      <?php 
	  if (isset($HTTP_POST_VARS['von'])){
$url_sn_von=$HTTP_POST_VARS['von'];
 }else {$url_sn_von=($row_rst2['fsn']+1);}
	
 if (isset($HTTP_POST_VARS['bis'])){
$url_sn_bis=$HTTP_POST_VARS['bis'];
	  }else {$url_sn_bis=($row_rst2['fsn']+21);}
	  ?>
      <td><select name="selectarb" id="selectarb">
          <option value="" <?php if (!(strcmp("", $HTTP_POST_VARS['selectarb']))) {echo "SELECTED";} ?>>alle 
          Arbeitsplätze</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['arb_id']?>"<?php if (!(strcmp($row_rst4['arb_id'], $HTTP_POST_VARS['selectarb']))) {echo "SELECTED";} ?>><?php echo $row_rst4['arb_name']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select></td>
      <td> <div align="right"> <a href="pdfetikett5.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>" target="_blank"><img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          Hinweis drucken</a><a href="pdfetikett4.php?url_artikelid=<?php echo $row_rst4['artikelid']; ?>&url_bezeichnung=<?php echo $row_rst4['Bezeichnung']; ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>" target="_blank"> 
          <img src="picture/rules.gif" width="13" height="18" border="0" align="absmiddle"> 
          Typenschild drucken</a> </div></td>
    </tr>
    <tr> 
      <td>Material finden:</td>
      <td> <input name="suche" type="text" id="suche" value="<?php echo $HTTP_POST_VARS['suche']; ?>"> 
        <input name="suchen" type="submit" id="suchen" value="suchen"> </td>
      <td><div align="right"><a href="pdfetikett3.php?url_bezeichnung=<?php echo $row_rst4['Bezeichnung'] ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $url_sn_von ?>&url_sn_bis=<?php echo $url_sn_bis ?>" target="_blank"><img src="picture/anschreiben.gif" width="18" height="18" border="0" align="absmiddle"> 
          SN-Aufkleber drucken</a> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="url_artikelid" type="hidden" id="url_artikelid" value="<?php echo $url_artikelid; ?>"></td>
      <td><div align="right">von 
          <input name="von" type="text" id="von" size="10" value="<?php echo $url_sn_von ?>">
          bis 
          <input name="bis" type="text" id="bis" size="10" value="<?php echo $url_sn_bis ?>">
          <input name="suchen2" type="submit" id="suchen2" value="aktualisieren">
        </div></td>
    </tr>
    <tr> 
      <td><input name="zuruck" type="submit" id="zuruck" value="Woche zur&uuml;ck"></td>
      <td>&nbsp;</td>
      <td><div align="right"> 
          <input name="vor" type="submit" id="vor" value="Woche vor">
        </div></td>
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
    <td width="109"><em><strong>Materialbezeichnung</strong></em></td>
    <td width="85">Material</td>
    <td width="15" bgcolor="#CEE1EC"><div align="center">Mo</div></td>
    <td width="15"><div align="center">Di</div></td>
    <td width="15" bgcolor="#CEE1EC">Mi</td>
    <td width="15">Do</td>
    <td width="15" bgcolor="#CEE1EC">Fr</td>
    <td width="15">Sa</td>
    <td width="15" bgcolor="#CEE1EC">So</td>
    <td width="40"><p>offen</p></td>
    <td width="40">gem</td>
    <td width="40"><p>offen</p></td>
    <td width="40">gem</td>
    <td width="40"><div align="center"><em><strong>Bedarf</strong></em></div></td>
    <td width="40"><div align="center"><em><strong>Bestand</strong></em></div></td>
    <td width="30"><em>Status</em></td>
    <td width="50"><em><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
      Benutzer</em></td>
    <td width="0">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php 
	  $summea=$row_rst2['moa1']+$row_rst2['moa2']+$row_rst2['moa3']+
	  		  $row_rst2['dia1']+$row_rst2['dia2']+$row_rst2['dia3']+
			  $row_rst2['mia1']+$row_rst2['mia2']+$row_rst2['mia3']+
			  $row_rst2['doa1']+$row_rst2['doa2']+$row_rst2['doa3']+
			  $row_rst2['fra1']+$row_rst2['fra2']+$row_rst2['fra3']+
			  $row_rst2['saa1']+$row_rst2['saa2']+$row_rst2['saa3']+
			  $row_rst2['soa1']+$row_rst2['soa2']+$row_rst2['soa3'];
			  
	  $summeg=$row_rst2['mog1']+$row_rst2['mog2']+$row_rst2['mog3']+
			  $row_rst2['dig1']+$row_rst2['dig2']+$row_rst2['dig3']+
			  $row_rst2['mig1']+$row_rst2['mig2']+$row_rst2['mig3']+
			  $row_rst2['dog1']+$row_rst2['dog2']+$row_rst2['dog3']+
			  $row_rst2['frg1']+$row_rst2['frg2']+$row_rst2['frg3']+
			  $row_rst2['sag1']+$row_rst2['sag2']+$row_rst2['sag3']+
			  $row_rst2['sog1']+$row_rst2['sog2']+$row_rst2['sog3'];
	  
	  ?>
  <?php if ($summea<>0 or $summeg<>0) {?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="left"> 
          <a href="pdfetikett3.php?url_bezeichnung=<?php echo $row_rst4['Bezeichnung'] ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $row_rst2['fsn'] ?>&url_sn_bis=<?php echo $row_rst2['fsn'] ?>" target="_blank"><strong><img src="picture/anschreiben.gif" alt="SN-Aufkleber drucken" width="18" height="18" border="0" align="absmiddle"></strong></a><?php echo $row_rst2['bezeichnung']; ?><a href="pdfetikett3.php?url_bezeichnung=<?php echo $row_rst4['Bezeichnung'] ?>&url_artikelnummer=<?php echo $row_rst4['Nummer'] ?>&url_sn_ab=<?php echo $row_rst2['fsn'] ?>&url_sn_bis=<?php echo $row_rst2['fsn'] ?>" target="_blank"> 
          </a></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['nummer']; ?></td>
      <td nowrap="nowrap"    bgcolor="#CEE1EC"><?php echo $row_rst2['mog1']+$row_rst2['mog2']+$row_rst2['mog3']; ?>&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['dig1']+$row_rst2['dig2']+$row_rst2['dig3']; ?>&nbsp;</td>
      <td    bgcolor="#CEE1EC" nowrap="nowrap"><?php echo $row_rst2['mig1']+$row_rst2['mig2']+$row_rst2['mig3']; ?>&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['dog1']+$row_rst2['dog2']+$row_rst2['dog3']; ?>&nbsp;</td>
      <td    bgcolor="#CEE1EC" nowrap="nowrap"><?php echo $row_rst2['frg1']+$row_rst2['frg2']+$row_rst2['frg3']; ?>&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['sag1']+$row_rst2['sag2']+$row_rst2['sag3']; ?>&nbsp;</td>
      <td    bgcolor="#CEE1EC" nowrap="nowrap"><?php echo $row_rst2['sog1']+$row_rst2['sog2']+$row_rst2['sog3']; ?>&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><font color="#009900"><?php echo $summeg-$row_rst2['gemg']; ?> 
        St.</font></strong></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="gemg" type="text" id="gemg" value="<?php echo $row_rst2['gemg']; ?>" size="5"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><font color="#FF0000"><?php echo $summea-$row_rst2['gema']; ?>&nbsp; 
        <input name="datumselect" type="hidden" id="datumselect" value="<?php echo $HTTP_POST_VARS['datumselect']; ?>">
        <input name="suche" type="hidden" id="suche" value="<?php echo $HTTP_POST_VARS['suche']; ?>">
        <input name="selectarb" type="hidden" id="selectarb" value="<?php echo $HTTP_POST_VARS['selectarb']; ?>">
        St.</font></strong></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><input name="gema" type="text" id="gema" value="<?php echo $row_rst2['gema']; ?>" size="5"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><strong> 
          <input name="avis" type="text" id="avis" value="<?php echo $row_rst2['avis']; ?>" size="7">
          <input name="set" type="submit" id="set" value="Set">
          </strong></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="center"><?php echo substr(utf8_decode($row_rst2['fnotes']),0,50); ?><?php echo $row_rst2['stueck']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <img src="picture/<?php if ($row_rst2['stueck']-$row_rst2['avis']<0) {echo "st1.gif";}else{echo "st3.gif";} ?>" alt="<?php echo "Fertigungsfreigabe ".$row_rst2['signatur']; ?>" width="15" height="15" align="absmiddle"> 
        - <img src="picture/<?php if (!(strcmp($row_rst2['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" alt="<?php echo "Pr&uuml;f-Signatur: ".$row_rst2['signatur']; ?>" width="15" height="15" align="absmiddle"> 
        - </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php
do {  
?>
        <div align="left"></div>
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
      <td    bgcolor="#EEEEEE" nowrap="nowrap">
<div align="right"> 
          <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $url_user;?>">
          <input name="hurl_arb_id" type="hidden" id="hurl_arb_id" value="<?php echo $row_rst2['typ']; ?>">
          <input name="hurl_pp_id" type="hidden" id="hurl_pp_id" value="<?php echo $row_rst2['pp_id']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
  
  </form></tr>
  <?php } /* ende anzeige von summe >0 */?>
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
  aktuelle Kalenderwoche festlegen.
  <?php include("footer.tpl.php"); ?>
  </font></p>
<?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
mysql_free_result($rst4);
?>
