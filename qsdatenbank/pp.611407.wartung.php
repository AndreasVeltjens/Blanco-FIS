<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); 
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}



if (isset($HTTP_POST_VARS["wartungspeichern"])) {
if (($HTTP_POST_VARS['checkbox1']>0) or ($HTTP_POST_VARS['checkbox2']>0) or ($HTTP_POST_VARS['checkbox3']>0) or ($HTTP_POST_VARS['checkbox4']>0) or ($HTTP_POST_VARS['checkbox5']>0) or ($HTTP_POST_VARS['checkbox6']>0) or($HTTP_POST_VARS['checkbox7']>0) or($HTTP_POST_VARS['checkbox8']>0) or($HTTP_POST_VARS['checkbox9']>0) or($HTTP_POST_VARS['checkbox10']>0)) {

	if ($HTTP_POST_VARS['checkbox1']==1) {$Wartungstext.="Einschlaghilfe i.O. ";}	
	if ($HTTP_POST_VARS['checkbox2']==1) {$Wartungstext.="Bits ohne Besch?digung ";}
	if ($HTTP_POST_VARS['checkbox3']==1) {$Wartungstext.="Druckluftschrauber i.O. ";}
	if ($HTTP_POST_VARS['checkbox4']==1) {$Wartungstext.="Drehmomenteinstellung ?berpr?ft i.O. ";}
	if ($HTTP_POST_VARS['checkbox5']==1) {$Wartungstext.="Kabel-Crimpzangen i.O. ";}
	if ($HTTP_POST_VARS['checkbox6']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox7']==1) {$Wartungstext.="Messmittel ?berwacht.";}
	if ($HTTP_POST_VARS['checkbox8']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox9']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox10']==1) {$Wartungstext.=" ";}

  $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, datum, `user`, beschreibung) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['url_id_fhm'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['url_id_fhmea'], "int"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['url_user'], "int"),
                       GetSQLValueString($Wartungstext,"text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $oktxt.="Wartung erfolgreich gespeichert.";
  $action="";
} else {
 $errtxt="Sie m&uuml;ssen schon mal einen Wartungspunkt ausw&auml;hlen. Erst machen, dann melden!";

} /* ende checkbox ungleich null */
}


if (isset($HTTP_POST_VARS["stoerungspeichern"])) {
if (($HTTP_POST_VARS['checkbox1']>0) or ($HTTP_POST_VARS['checkbox2']>0) or ($HTTP_POST_VARS['checkbox3']>0) or ($HTTP_POST_VARS['checkbox4']>0) or ($HTTP_POST_VARS['checkbox5']>0) or ($HTTP_POST_VARS['checkbox6']>0) or($HTTP_POST_VARS['checkbox7']>0) or($HTTP_POST_VARS['checkbox8']>0) or($HTTP_POST_VARS['checkbox9']>0) or($HTTP_POST_VARS['checkbox10']>0) or($HTTP_POST_VARS['checkbox11']>0)) {

	if ($HTTP_POST_VARS['checkbox1']==1) {$Wartungstext.="Einschlaghilfe defekt ";}	
	if ($HTTP_POST_VARS['checkbox2']==1) {$Wartungstext.="Bits besch?digt. Ersatz notwendig. ";}
	if ($HTTP_POST_VARS['checkbox3']==1) {$Wartungstext.="Werkzeug nicht vollst?ndig bzw. n.i.O. ";}
	if ($HTTP_POST_VARS['checkbox4']==1) {$Wartungstext.="Druckluftschrauber n.i.O. ";}
	if ($HTTP_POST_VARS['checkbox5']==1) {$Wartungstext.="Einstellung Drehmoment nicht m?glich.";}
	if ($HTTP_POST_VARS['checkbox6']==1) {$Wartungstext.="FIS funktioniert nicht ";}
	if ($HTTP_POST_VARS['checkbox7']==1) {$Wartungstext.="Ettikettendrucker ohne Funktion ";}
	if ($HTTP_POST_VARS['checkbox8']==1) {$Wartungstext.="Stammdaten fehlerhaft ";}
	if ($HTTP_POST_VARS['checkbox9']==1) {$Wartungstext.="Kabel-Crimpzange defekt. ";}
	if ($HTTP_POST_VARS['checkbox10']==1) {$Wartungstext.="Messmittel nicht i.O.";}
	if ($HTTP_POST_VARS['checkbox11']==1) {$Wartungstext.="unbekanntes Problem, Montage nicht m?glich ";}

  $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, datum, `user`, beschreibung) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['url_id_fhm'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['url_id_fhmea'], "int"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['url_user'], "int"),
                       GetSQLValueString($Wartungstext,"text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $oktxt.="Wartung erfolgreich gespeichert.";
  $action="";
} else {
 $errtxt="Sie m&uuml;ssen schon mal eine Auswahl treffen, sonst geht es nicht.";

} /* ende checkbox ungleich null */
}

$maxRows_rstletztewartung = 5;
$pageNum_rstletztewartung = 0;
if (isset($HTTP_GET_VARS['pageNum_rstletztewartung'])) {
  $pageNum_rstletztewartung = $HTTP_GET_VARS['pageNum_rstletztewartung'];
}
$startRow_rstletztewartung = $pageNum_rstletztewartung * $maxRows_rstletztewartung;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstletztewartung = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_fhmea=2 and fhm_ereignis.id_fhm='$fhm_id' and fhm_ereignis.lokz=0 and (datum)>=DATE_SUB(now(),interval '480:10' MINUTE_SECOND) ORDER BY fhm_ereignis.datum desc, fhm_ereignis.id_fhme desc";
$query_limit_rstletztewartung = sprintf("%s LIMIT %d, %d", $query_rstletztewartung, $startRow_rstletztewartung, $maxRows_rstletztewartung);
$rstletztewartung = mysql_query($query_limit_rstletztewartung, $qsdatenbank) or die(mysql_error());
$row_rstletztewartung = mysql_fetch_assoc($rstletztewartung);

if (isset($HTTP_GET_VARS['totalRows_rstletztewartung'])) {
  $totalRows_rstletztewartung = $HTTP_GET_VARS['totalRows_rstletztewartung'];
} else {
  $all_rstletztewartung = mysql_query($query_rstletztewartung);
  $totalRows_rstletztewartung = mysql_num_rows($all_rstletztewartung);
}
$totalPages_rstletztewartung = ceil($totalRows_rstletztewartung/$maxRows_rstletztewartung)-1;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letztestoerung = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_fhmea=6 and fhm_ereignis.id_fhm='$fhm_id' and fhm_ereignis.lokz=0 and (datum)>=subdate(now(),interval 1 Month) ORDER BY fhm_ereignis.datum desc, fhm_ereignis.id_fhme desc";
$letztestoerung = mysql_query($query_letztestoerung, $qsdatenbank) or die(mysql_error());
$row_letztestoerung = mysql_fetch_assoc($letztestoerung);
$totalRows_letztestoerung = mysql_num_rows($letztestoerung);
















mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_wartungsuser = "SELECT * FROM `user` ORDER BY `user`.id";
$wartungsuser = mysql_query($query_wartungsuser, $qsdatenbank) or die(mysql_error());
$row_wartungsuser = mysql_fetch_assoc($wartungsuser);
$totalRows_wartungsuser = mysql_num_rows($wartungsuser);


 $kontierung="nichts"; $warengruppe="nichts"; 
if (isset($materialnummer1) or $materialnummer1==1){ $kontierung="fert"; $warengruppe="sonstige"; $materialindex=1;}
if (isset($materialnummer2) or $materialnummer2==1){ $kontierung="Ersatz"; $warengruppe="Beschlagteile"; $materialindex=2; }
if (isset($materialnummer3) or $materialnummer3==1){ $kontierung="Ersatz"; $warengruppe="Elektro"; $materialindex=3; }

$maxRows_rst_material = 8;
$pageNum_rst_material = 0;
if (isset($HTTP_GET_VARS['pageNum_rst_material'])) {
  $pageNum_rst_material = $HTTP_GET_VARS['pageNum_rst_material'];
}
$startRow_rst_material = $pageNum_rst_material * $maxRows_rst_material;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_material = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert=1 and artikeldaten.Kontierung like '%$kontierung%' and ( artikeldaten.Gruppe like '%$warengruppe%' $abfrage ) ORDER BY artikeldaten.Gruppe, artikeldaten.Bezeichnung ";
$query_limit_rst_material = sprintf("%s LIMIT %d, %d", $query_rst_material, $startRow_rst_material, $maxRows_rst_material);
$rst_material = mysql_query($query_limit_rst_material, $qsdatenbank) or die(mysql_error());
$row_rst_material = mysql_fetch_assoc($rst_material);

if (isset($HTTP_GET_VARS['totalRows_rst_material'])) {
  $totalRows_rst_material = $HTTP_GET_VARS['totalRows_rst_material'];
} else {
  $all_rst_material = mysql_query($query_rst_material);
  $totalRows_rst_material = mysql_num_rows($all_rst_material);
}
$totalPages_rst_material = ceil($totalRows_rst_material/$maxRows_rst_material)-1;

$queryString_rst_material = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst_material") == false && 
        stristr($param, "totalRows_rst_material") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst_material = "&" . implode("&", $newParams);
  }
}
$queryString_rst_material = sprintf("&totalRows_rst_material=%d%s", $totalRows_rst_material, $queryString_rst_material);


?>
<?php
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_anzahlverkauft = "SELECT count(fid) as anzahl FROM fertigungsmeldungen WHERE verkauftam>=DATE_SUB(now(),interval '60:10' MINUTE_SECOND) AND versendetam = '0000-00-00 00:00:00' AND verkauftuser='$url_user'";
$anzahlverkauft = mysql_query($query_anzahlverkauft, $qsdatenbank) or die(mysql_error());
$row_anzahlverkauft = mysql_fetch_assoc($anzahlverkauft);
$totalRows_anzahlverkauft = mysql_num_rows($anzahlverkauft);
?>
<?php
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_anzahlverkauft2 = "SELECT count(fid) as anzahl FROM fertigungsmeldungen WHERE versendetam>=DATE_SUB(now(),interval '360:00' MINUTE_SECOND) AND versendetam = '0000-00-00 00:00:00' AND versendetuser='$url_user'";
$anzahlverkauft2 = mysql_query($query_anzahlverkauft2, $qsdatenbank) or die(mysql_error());
$row_anzahlverkauft2 = mysql_fetch_assoc($anzahlverkauft2);
$totalRows_anzahlverkauft2 = mysql_num_rows($anzahlverkauft2);
?>

<style type="text/css">
<!--
.blinkandred {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
	color: #FF0000;
	text-decoration: blink;
}
-->
</style>
<style type="text/css">
<!--
.checkboxgross {
	height: 30px;
	width: 100px;
}
.buttongross {
	height: 120px;
	width: 150px;
	cursor: hand;
}
-->
</style>
<style type="text/css">
<!--
.eingabefeld {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 20px;
	height: 30px;
	width: 130px;
	color: #000000;
	background-color: #FFCC00;
	background-position: center center;
	cursor: crosshair;
}
-->
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td colspan="2"><img src="picture/b_calendar.png" width="16" height="16"> 
      Eingabe</td>
    <td width="270">&nbsp;</td>
    <td width="297">&nbsp;</td>
    <td width="231"><div align="right"></div></td>
  </tr>
  <?php if ($url_user<>"" and $action==""){?>
  <?php $oktxt="Mitarbeiter ".$row_rstuser['name']." angemeldet. Bitte w&auml;hlen Sie aus den Funktionen.";?>
  <tr bgcolor="#FFFFFF"> 
    <td width="224"><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a></td>
    <td width="224"><div align="center"><a href="pp.<?php echo $location ?>.php?url_user=<?php echo $url_user ?>&action=anleitung"><img src="picture/formular.gif" width="29" height="40" border="0" align="absmiddle"> 
        <font size="+1">Anleitung </font></a></div></td>
    <td width="270"><div align="center"><a href="pp.<?php echo $location ?>.php?url_user=<?php echo $url_user ?>&action=wartunganlegen"><img src="picture/anschrift.gif" alt="Wartung melden" width="70" height="50" border="0" align="absmiddle"></a>
	<a href="pp.<?php echo $location ?>.php?url_user=<?php echo $url_user ?>&action=wartunganlegen"><font size="+2">Wartung<strong> 
        </strong></font></a></div></td>
    <td width="297"><div align="center"><a href="pp.<?php echo $location ?>.php?url_user=<?php echo $url_user ?>&action=auftragbearbeiten"><strong><font size="+2"><img src="picture/icon_gr_tarifkonditionen.gif" width="65" height="75" border="0" align="absmiddle"> 
        Verkaufen</font></strong></a></div></td>
    <td width="231"><div align="center"><a href="pp.<?php echo $location ?>.php?url_user=<?php echo $url_user ?>&action=stoerunganlegen"><font size="+2"><img src="picture/allg-einstellungen.gif" alt="Meldung &uuml;ber Maschinenst&ouml;rung anlegen" width="70" height="50" border="0" align="absmiddle">St&ouml;rung 
        </font></a></div></td>
  </tr>
  <?php } /* ende Mitarbeiter angemeldet und aktiv. */?>
  <?php if ($action==""){?>
  <tr bgcolor="#FFFFFF"> 
    <td colspan="2"><img src="picture/arrowClose.gif" width="8" height="9"> Wartungsdokumentation<br> 
      <?php if ($totalRows_rstletztewartung > 0) { // Show if recordset empty ?>
      <table width="200" border="0" cellspacing="1" cellpadding="0">
        <tr bgcolor="#CCCCCC"> 
          <td><font size="1">Datum</font></td>
          <td><font size="1">Was</font></td>
          <td><font size="1">Wer</font></td>
        </tr>
        <?php do { ?>
        <tr> 
          <td height="49" bgcolor="#00CC00"><font size="1"><?php echo substr($row_rstletztewartung['datum'],5,15); ?></font></td>
          <td bgcolor="#00CC00"><font size="1"><?php echo substr($row_rstletztewartung['beschreibung'],0,120); ?></font></td>
          <td bgcolor="#00CC00"><font size="1"> 
            <?php
do {  
?>
            <?php if (!(strcmp($row_wartungsuser['id'], $row_rstletztewartung['user']))) {echo  utf8_decode($row_wartungsuser['name']);}?>
            <?php
} while ($row_wartungsuser = mysql_fetch_assoc($wartungsuser));
  $rows = mysql_num_rows($wartungsuser);
  if($rows > 0) {
      mysql_data_seek($wartungsuser, 0);
	  $row_wartungsuser = mysql_fetch_assoc($wartungsuser);
  }
?>
            </font></td>
        </tr>
        <tr> 
          <td colspan="3"><hr></td>
        </tr>
        <?php } while ($row_rstletztewartung = mysql_fetch_assoc($rstletztewartung)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
      <?php if ($totalRows_rstletztewartung == 0) { // Show if recordset empty ?>
      <?php $errtxt="Keine Wartung der Anlage durchgef&uuml;hrt.";?>
      <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td bgcolor="#FF0000"><strong><font color="#FFFFFF" size="5"><img src="picture/sicherheitsicon_35x35.gif" width="35" height="35" align="absmiddle"> 
            keine Wartung dokumentiert.</font></strong></td>
        </tr>
      </table>
      <p class="blinkandred"><strong></strong></p>
      <?php } // Show if recordset empty ?> <img src="picture/arrowClose.gif" width="8" height="9">Liste 
      Maschinenst?rungen<br> 
      <?php if ($totalRows_letztestoerung > 0) { // Show if recordset empty ?>
      <table width="200" border="0" cellspacing="1" cellpadding="0">
        <tr bgcolor="#CCCCCC"> 
          <td><font size="1">Datum</font></td>
          <td><font size="1">Was</font></td>
          <td><font size="1">Wer</font></td>
        </tr>
        <?php do { ?>
        <tr> 
          <td bgcolor="#FF9900"><font size="1"><?php echo $row_letztestoerung['datum']; ?></font></td>
          <td bgcolor="#FF9900"><font size="1"><?php echo substr($row_letztestoerung['beschreibung'],0,120); ?></font></td>
          <td bgcolor="#FF9900"><font size="1">
		  
		  <?php
do {  
?>
            <?php if (!(strcmp($row_wartungsuser['id'], $row_letztestoerung['user']))) {echo  utf8_decode($row_wartungsuser['name']);}?>
            <?php
} while ($row_wartungsuser = mysql_fetch_assoc($wartungsuser));
  $rows = mysql_num_rows($wartungsuser);
  if($rows > 0) {
      mysql_data_seek($wartungsuser, 0);
	  $row_wartungsuser = mysql_fetch_assoc($wartungsuser);
  }
?>
		  
		  </font></td>
        </tr>
        <tr> 
          <td colspan="3"><hr></td>
        </tr>
        <?php } while ($row_letztestoerung = mysql_fetch_assoc($letztestoerung)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
      <?php if ($totalRows_letztestoerung == 0) { // Show if recordset empty ?>
      <?php $oktxt="Anlage arbeitet ohne St&ouml;rung.";?>
      <strong><font color="#006600" size="2">Keine St&ouml;rung am Arbeitsplatz.</font></strong> 
      <?php } // Show if recordset empty ?> </td>
    <td colspan="3"> 
      <?php if (url_user<>""){ ?>
      <div align="center">&nbsp;Bitte w?hlen Sie aus den Funktionen aus. </div>
        <?php } else { ?>
        <?php include('pp.letzteverkauft.php'); ?>
        &nbsp; 
        <?php }?>
      </td>
  </tr>
  <?php } ?>
</table>


<?php if ($url_user>0 and $action=="wartunganlegen"){?>
<?php $oktxt.=" Zum Speichern bitte Wartungspunkte ausw&auml;hlen und speichern dr&uuml;cken.";?>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td width="177"><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a></td>
      <td width="309">&nbsp;</td>
      <td width="293" bgcolor="#6699FF"><img src="picture/anschrift.gif" width="70" height="50" align="absmiddle"><strong>Wartung 
        r&uuml;ckmelden</strong></td>
      <td width="321">&nbsp;</td>
      <td width="250">&nbsp;</td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><p> 
          <input name="checkbox1" type="checkbox" class="checkboxgross" id="checkbox1" value="1">
          <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"> 
        </p></td>
      <td colspan="3"> <div align="center"> </div>
        <font size="4"> Einschlaghilfe Schraubenabdeckungen i.O.</font></td>
      <td><img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"> 
        vor Schichtbeginn durchf&uuml;hren</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td>&nbsp;</td>
      <td colspan="3"><font size="4">&nbsp;</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox2" type="checkbox" class="checkboxgross" id="checkbox2" value="1"> 
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"></td>
      <td colspan="3"> <div align="center"> </div>
        <font size="4"> Bits ohne Besch&auml;digung</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><input name="checkbox3" type="checkbox" class="checkboxgross" id="checkbox3" value="1"> 
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"></td>
      <td colspan="3"><div align="center"> </div>
        <font size="4"> Druckluftzuleitung / Druckluft&ouml;ler / Luftdruck 4 
        bar i.O.</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox4" type="checkbox" class="checkboxgross" id="checkbox4" value="1">
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"> 
      </td>
      <td colspan="3"><div align="left"><font size="4">Drehmomenteinstellung &uuml;berpr&uuml;ft.</font></div></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><input name="checkbox5" type="checkbox" class="checkboxgross" id="checkbox5" value="1">
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"> 
      </td>
      <td colspan="3"><div align="left"><font size="4">Kabel-Crimpzangen Anpresskraft 
          i.O.</font></div></td>
      <td rowspan="8"> <div align="center"> 
          <input name="wartungspeichern" type="submit" class="buttongross" id="wartungspeichern" value="Wartung Speichern">
        </div></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox6" type="checkbox" class="checkboxgross" id="checkbox6" value="1">
      </td>
      <td colspan="3"> <div align="center"> </div>
        <font size="4">&nbsp; </font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="left"> 
          <input name="checkbox7" type="checkbox" class="checkboxgross" id="checkbox7" value="1">
          <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"></div></td>
      <td colspan="3"><div align="center"> </div>
        <font size="4"> Messmittel&uuml;berwachung erfolreich durchgef&uuml;hrt.</font></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox8" type="checkbox" class="checkboxgross" value="1"></td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td colspan="3" bgcolor="#CCCCCC"><font size="4">&nbsp;</font></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox9" type="checkbox" class="checkboxgross" id="checkbox9" value="1">
      </td>
      <td colspan="3"> <div align="center"> </div>
        <font size="4">&nbsp; </font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><input name="checkbox10" type="checkbox" class="checkboxgross" id="checkbox10" value="1">
      </td>
      <td colspan="3"><div align="center"></div>
        </td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td> <input name="url_user" type="hidden" id="url_user" value="<?php echo $url_user; ?>"> 
        <input name="url_id_fhm" type="hidden" id="url_id_fhm" value="<?php echo $fhm_id; ?>"> <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="2"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php } /* ende Wartunganlegen */
?>






 <?php if ($url_user>0 and $action=="stoerunganlegen"){?>
  <?php $oktxt.=" Zum Speichern bitte Wartungspunkte ausw&auml;hlen und speichern dr&uuml;cken.";?>
</p>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td width="147"><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a></td>
      <td width="233">&nbsp;</td>
      <td width="282" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="176">&nbsp;</td>
      <td width="221" bgcolor="#FF9900"><img src="picture/allg-einstellungen.gif" width="70" height="50" align="absmiddle"><strong>St&ouml;rung 
        anlegen </strong></td>
    </tr>
    <tr bgcolor="#FF9900"> 
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#FF9900"> 
      <td> <div align="center"> 
          <input name="checkbox1" type="checkbox" class="checkboxgross" id="checkbox1" value="1">
        </div></td>
      <td colspan="3"><font size="4">Fehler: Einschlaghilfe defekt oder Grahtbildung</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox2" type="checkbox" class="checkboxgross" value="1">
        </div></td>
      <td colspan="3"><div align="center"> </div>
        <font size="4">Bits besch&auml;digt. Ersatz notwendig. </font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FF9900"> 
      <td> <div align="center"> 
          <input name="checkbox3" type="checkbox" class="checkboxgross" id="checkbox3" value="1">
        </div></td>
      <td colspan="4"><div align="center"> </div>
        <font size="4"> Werkzeug nicht vollst&auml;ndig bzw. n.i.O. </font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox4" type="checkbox" class="checkboxgross" id="checkbox4" value="1">
        </div></td>
      <td colspan="4"><div align="center"> </div>
        <font size="4"> Druckluftschrauber n.i.O. </font></td>
    </tr>
    <tr bgcolor="#FF9900"> 
      <td> <div align="center"> 
          <input name="checkbox5" type="checkbox" class="checkboxgross" id="checkbox5" value="1">
        </div></td>
      <td colspan="3"><div align="left"><font size="4">Einstellung Drehmoment 
          nicht m&ouml;glich.</font></div></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox6" type="checkbox" class="checkboxgross" id="checkbox6" value="checkbox">
        </div></td>
      <td colspan="3"><font size="4">FIS funktioniert nicht </font></td>
      <td rowspan="6"> <div align="center"> 
          <input name="stoerungspeichern" type="submit" class="buttongross" id="stoerungspeichern" value="St&ouml;rung Speichern">
        </div></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td height="31" bgcolor="#FF9900"> <div align="center"> 
          <input name="checkbox7" type="checkbox" class="checkboxgross" id="checkbox7" value="1">
        </div></td>
      <td colspan="3" bgcolor="#FF9900"><font size="4">Ettikettendrucker ohne 
        Funktion</font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox8" type="checkbox" class="checkboxgross" id="checkbox8" value="1">
        </div></td>
      <td colspan="3"><font size="4">Stammdatenfehler</font></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td bgcolor="#FF9900"> <div align="center"> 
          <input name="checkbox9" type="checkbox" class="checkboxgross" id="checkbox9" value="1">
        </div></td>
      <td colspan="3" bgcolor="#FF9900"><font size="4">Kabel-Crimpzange funktioniert 
        nicht einwandfrei.</font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox10" type="checkbox" class="checkboxgross" id="checkbox10" value="1">
        </div></td>
      <td colspan="3"><font size="4">Messmittel funktioniert nicht.</font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td bgcolor="#FF9900"> <div align="center"> 
          <input name="checkbox11" type="checkbox" class="checkboxgross" id="checkbox11" value="1">
        </div></td>
      <td colspan="3" bgcolor="#FF9900"><font size="4"> 
        <input name="imageField" type="image" src="picture/error.gif" align="middle" width="16" height="16" border="0">
        Problem nicht aufgef&uuml;hrt. Montage nicht m&ouml;glich </font> <font size="4"> 
        <input name="url_user" type="hidden" id="url_user" value="<?php echo $url_user; ?>">
        <input name="url_id_fhm" type="hidden" id="url_id_fhm" value="<?php echo $fhm_id; ?>">
        <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="6">
        </font></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php } /* ende St?rung anlegen */
?>

 <?php if ($url_user>0 and $action=="auftragbearbeiten"){?>
  <?php $oktxt.=" Zum Speichern Artikel anw?hlen und Neues Ger&auml;t dr&uuml;cken.";?>
</p>

  
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr> 
      <td width="204" bgcolor="#FFFFFF"><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a></td>
      <td width="280" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="103" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="236" bgcolor="#CCCCFF"><img src="picture/icon_gr_tarifkonditionen.gif" width="65" height="75" align="absmiddle"> 
        <strong>Verkauf melden</strong></td>
      <td width="183" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <?php if (!isset($materialnummer) and (!isset($materialnummer1) and !isset($materialnummer2) and !isset($materialnummer3) and !isset($materialnummer4) and !isset($materialnummer5))  ){ ?>
    <tr bgcolor="#CCCCFF"> 
      <td><img src="picture/b_calendar.png" width="16" height="16"> R&uuml;ckmeldung 
        &uuml;ber </td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td height="22">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </form>
  <tr bgcolor="#CCCCFF"> 
    <td><div align="center"><img src="picture/barcodescanner.png" width="150" height="150"></div></td>
    <td bgcolor="<?php echo $color_status ?>"><font size="3"><?php echo $oktxt?> 
      </font>
      <p>&nbsp;</p>
      <p><font size="3"><?php echo $errtxt?></font></p></td>
    <td colspan="3"><input name="url_user" type="hidden" id="url_user" value="<?php echo $url_user; ?>"> 
      <input name="url_id_fhm" type="hidden" id="url_id_fhm" value="<?php echo $fhm_id ?>"> 
      <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="6"> 
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form2" >
        Scan Barcode: 
        <input name="suchtext" type="text" class="eingabefeld" id="suchtext" value="" size="20" maxlength="20">
        <input type="Submit" name="verkaufen" class="buttongross" value="Verkaufen">
        <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">
        <hr>
        <p> 
          <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
          <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">
          Anzahl der Verk&auml;ufe in der letzten Stunde: <font size="7"><strong><?php echo $row_anzahlverkauft['anzahl']; ?></strong></font></p>
        <input type="hidden" name="MM_update" value="form2">
        Anzahl der Verk&auml;ufe in den letzten 12- Stunden des Mitarbeiters: 
        <font size="7"><strong><?php echo $row_anzahlverkauft2['anzahl']; ?></strong></font> 
      </form>
      
    </td>
  </tr>
 <?php } /* Anwahl nach RFID */?>
 
 
</table>
 <?php } /* auftragbearbeiten */?>  
	
	


	
	
   

<?php if ($url_user>0 and $action=="anleitung"){?>
<?php $oktxt="Zum Anzeigen das entsprechende Thema ausw?hlen und Auswahl dr&uuml;cken.";?>
</p>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="224" bgcolor="#FFFFFF"><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a></td>
      <td width="224" bgcolor="#CCCCFF"><div align="center"><img src="picture/formular.gif" width="29" height="40" align="absmiddle"> 
          <strong>Anleitung</strong> </div></td>
      <td width="270" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="296" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="230" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Inhaltsverzeichnis</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><strong><img src="picture/help2.gif" width="16" height="16" align="absmiddle"> 
        Wartungspunkte</strong></td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="4"><strong><img src="picture/help2.gif" width="16" height="16" align="absmiddle"> 
        </strong></td>
      <td><input name="url_user" type="hidden" id="url_user" value="<?php echo $url_user; ?>"> 
        <input name="url_id_fhm" type="hidden" id="url_id_fhm" value="<?php echo $fhm_id ?>"> 
        <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="6"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><strong><img src="picture/help2.gif" width="16" height="16" align="absmiddle"> 
        </strong></td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="4"><strong><img src="picture/help2.gif" width="16" height="16" align="absmiddle"> 
        </strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><strong><img src="picture/help2.gif" width="16" height="16" align="absmiddle"> 
        </strong></td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><hr></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php } /* ende Anleitung */
?>
<?php 
mysql_free_result($rstletztewartung);

mysql_free_result($letztestoerung);

mysql_free_result($wartungsuser);

mysql_free_result($rst_material);

mysql_free_result($anzahlverkauft);
?>