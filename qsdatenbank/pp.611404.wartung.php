<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
?><?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}



if (isset($HTTP_POST_VARS["wartungspeichern"])) {
if (($HTTP_POST_VARS['checkbox1']>0) or ($HTTP_POST_VARS['checkbox2']>0) or ($HTTP_POST_VARS['checkbox3']>0) or ($HTTP_POST_VARS['checkbox4']>0) or ($HTTP_POST_VARS['checkbox5']>0) or ($HTTP_POST_VARS['checkbox6']>0) or($HTTP_POST_VARS['checkbox7']>0) or($HTTP_POST_VARS['checkbox8']>0) or($HTTP_POST_VARS['checkbox9']>0) or($HTTP_POST_VARS['checkbox10']>0)) {

	if ($HTTP_POST_VARS['checkbox1']==1) {$Wartungstext.="Einschlaghilfe i.O. ";}	
	if ($HTTP_POST_VARS['checkbox2']==1) {$Wartungstext.="Bits ohne Beschädigung ";}
	if ($HTTP_POST_VARS['checkbox3']==1) {$Wartungstext.="Druckluftschrauber i.O. ";}
	if ($HTTP_POST_VARS['checkbox4']==1) {$Wartungstext.="Drehmomenteinstellung überprüft i.O. ";}
	if ($HTTP_POST_VARS['checkbox5']==1) {$Wartungstext.="Kabel-Crimpzangen i.O. ";}
	if ($HTTP_POST_VARS['checkbox6']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox7']==1) {$Wartungstext.="Messmittel überwacht.";}
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
	if ($HTTP_POST_VARS['checkbox2']==1) {$Wartungstext.="Bits beschädigt. Ersatz notwendig. ";}
	if ($HTTP_POST_VARS['checkbox3']==1) {$Wartungstext.="Werkzeug nicht vollständig bzw. n.i.O. ";}
	if ($HTTP_POST_VARS['checkbox4']==1) {$Wartungstext.="Druckluftschrauber n.i.O. ";}
	if ($HTTP_POST_VARS['checkbox5']==1) {$Wartungstext.="Einstellung Drehmoment nicht möglich.";}
	if ($HTTP_POST_VARS['checkbox6']==1) {$Wartungstext.="FIS funktioniert nicht ";}
	if ($HTTP_POST_VARS['checkbox7']==1) {$Wartungstext.="Ettikettendrucker ohne Funktion ";}
	if ($HTTP_POST_VARS['checkbox8']==1) {$Wartungstext.="Stammdaten fehlerhaft ";}
	if ($HTTP_POST_VARS['checkbox9']==1) {$Wartungstext.="Kabel-Crimpzange defekt. ";}
	if ($HTTP_POST_VARS['checkbox10']==1) {$Wartungstext.="Messmittel nicht i.O.";}
	if ($HTTP_POST_VARS['checkbox11']==1) {$Wartungstext.="unbekanntes Problem, Montage nicht möglich ";}

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
if (isset($materialnummer4) or $materialnummer4==1){ $kontierung="Ersatz"; $warengruppe="Deckel"; $materialindex=4; }
if (isset($materialnummer5) or $materialnummer5==1){ $kontierung="Ersatz"; $warengruppe="grund"; $materialindex=5; }

if (isset($materialnummer6) or $materialnummer6==1){ $kontierung="fert"; $warengruppe="BLT K"; $materialindex=6;}
if (isset($materialnummer7) or $materialnummer7==1){ $kontierung="fert"; $warengruppe="BLT KB%"; $materialindex=7; }
if (isset($materialnummer8) or $materialnummer8==1){ $kontierung="fert"; $warengruppe="sonst%"; $materialindex=8; }
if (isset($materialnummer9) or $materialnummer9==1){ $kontierung="fert"; $warengruppe="industrie%"; $materialindex=9; }
if (isset($materialnummer10) or $materialnummer10==1){ $kontierung="fert"; $warengruppe="ET%"; $materialindex=10; }

$maxRows_rst_material = 10;
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

$query_auftragsberbeitung= "SELECT * FROM `bearbeitungsbestand` ORDER BY `bearbeitungsbestand`.fid";
$auftragsberbeitung = mysql_query($query_auftragsberbeitung, $qsdatenbank) or die(mysql_error());
$row_auftragsberbeitung = mysql_fetch_assoc($auftragsberbeitung);
$totalRows_auftragsberbeitung = mysql_num_rows($auftragsberbeitung);


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
        </font></strong><font size="+2">Auftr&auml;ge </font></a></div></td>
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
      Maschinenstörungen<br> 
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
    <td colspan="3"><?php include('pp.letztegeraete.php'); ?>&nbsp;</td>
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
<?php } /* ende Störung anlegen */
?>

 <?php if ($url_user>0 and $action=="auftragbearbeiten"){?>
  <?php $oktxt.=" Zum Speichern Artikel anwählen und Neues Ger&auml;t dr&uuml;cken.";?>

<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="224" bgcolor="#FFFFFF"><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a></td>
      <td width="224" bgcolor="#FFFFFF"><a href="pp.<?php echo $location ?>.php?url_user=<?php echo $url_user ?>&action=auftragbearbeiten" target="_self"><font size="5"><img src="picture/formular.gif" width="29" height="40" border="0" align="absmiddle"> 
        Auswahlliste</font></a></td>
      <td width="270" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="296" bgcolor="#CCCCFF"><img src="picture/icon_gr_tarifkonditionen.gif" width="65" height="75" align="absmiddle"> 
        <strong>Auftrag bearbeiten</strong></td>
      <td width="230" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <?php if (!isset($materialnummer) and !isset($auftragsliste) and (!isset($materialnummer1) and !isset($materialnummer2) and !isset($materialnummer3) and !isset($materialnummer4) and !isset($materialnummer5))  ){ ?>
    <tr bgcolor="#CCCCFF"> 
      <td><img src="picture/b_calendar.png" width="16" height="16"> R&uuml;ckmeldung 
        &uuml;ber </td>
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
      <td><input name="rfid" type="submit" class="buttongross" id="rfid" value="RFID Lesen"></td>
      <td><input name="auftragsliste" type="submit" class="buttongross" id="auftragsliste" value="Auftragsliste"></td>
      <td colspan="2"><input name="materialnummer" type="submit" class="buttongross" id="materialnummer" value="Materialnummer"></td>
      <td><input name="nachdrucken" type="submit" class="buttongross" id="nachdrucken" value="Nachdrucken">
        <input name="url_user" type="hidden" id="url_user" value="<?php echo $url_user; ?>"> 
        <input name="url_id_fhm" type="hidden" id="url_id_fhm" value="<?php echo $fhm_id ?>"> 
        <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="6"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><hr></td>
    </tr>
    <?php }?>
    <?php if (isset($materialnummer)) {?>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16"> 
        Liste der Materialummern</td>
    </tr></td></tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><input name="materialnummer1" type="submit" class="buttongross" id="materialnummer1" value="Zubehör"></td>
      <td><input name="materialnummer4" type="submit" class="buttongross" id="materialnummer4" value="Deckel"></td>
      <td><input name="materialnummer2" type="submit" class="buttongross" id="materialnummer2" value="Ersatzteile Beschlagteile"></td>
      <td><input name="materialnummer3" type="submit" class="buttongross" id="materialnummer3" value="Ersatzteile Elektro"></td>
      <td><input name="materialnummer5" type="submit" class="buttongross" id="materialnummer5" value="Grundkörper"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
	
	
	
	
	
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
    <?php }?>
    <?php if (isset($auftragsliste)) {?>
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16"> 
        Liste der Auftragsliste</td>
    </tr></td></tr>
    <tr bgcolor="#CCCCFF"> 
	
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td>FID</td>
      <td>SN</td>
      <td>Bezeichnung</td>
      <td>Mitarbeiter</td>
      <td>Aktion</td>
    </tr>
	<?php do {
	$i=$i+1;
	if ($i%2==0){$color = "#CCCCFF";}else{$color = "#FFFFFF";}
	if ($HTTP_POST_VARS['hfid']==$row_auftragsberbeitung['fid']){$color="FFCC00";}?>
	<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
    <tr bgcolor="<?php echo $color ?>"> 
      <td class="buttonmiddle"><?php echo $row_auftragsberbeitung['fid']; ?></td>
      <td class="buttonmiddle">
    <?php echo $row_auftragsberbeitung['fsn']; ?>xxx</td>
      <td><?php echo utf8_decode($row_auftragsberbeitung['bezeichnung']); ?></td>
      <td><?php echo utf8_decode($row_auftragsberbeitung['user']); ?><br><?php echo $row_auftragsberbeitung['datum']; ?>
      </td>
      <td><?php if ($HTTP_POST_VARS['hfid']<>$row_auftragsberbeitung['fid']){?>
	  <input name="auftragsliste" type="submit" class="buttonmiddle" id="freigeben" value="Fertig ?">
	  <?php }?>
        <input name="hfid" type="hidden" value="<?php echo $row_auftragsberbeitung['fid']; ?>"></td>
    </tr>
	<?php if (isset($HTTP_POST_VARS['hfid']) && $HTTP_POST_VARS['hfid']==$row_auftragsberbeitung['fid']){
	$fid=$row_auftragsberbeitung['fid'];
	
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_material = "SELECT * FROM artikeldaten,fertigungsmeldungen WHERE fertigungsmeldungen.fid = '$fid' AND artikeldaten.artikelid=fertigungsmeldungen.fartikelid LIMIT 0,1";
$rst_material = mysql_query($query_rst_material, $qsdatenbank) or die(mysql_error());
$row_rst_material = mysql_fetch_assoc($rst_material);
$totalRows_rst_material = mysql_num_rows($rst_material);
	
	?>
	<tr bgcolor="<?php echo $color ?>"> 
      
    <td colspan="5"><font color="#006600">Freigeben 
      ?</font> 
      <?php if ($row_rst_material['anzahlp']>0){ ?>
      Heizung 
      <input name="fsn1" type="text" class="eingabefeld" id="suchtext" value="" size="20" maxlength="20">
      <?php if ($row_rst_material['anzahlp']>=2){?>
      Regler 
        <textarea name="fsn2" cols="20" rows="2" class="eingabefeld" id="suchtext2"></textarea>
      <?php }?>
      <?php if ($row_rst_material['anzahlp']>=3){?>
      L&uuml;fter 
      <input name="fsn3" type="text" class="eingabefeld" id="suchtext3" value="" size="20" maxlength="20">
      Version 
      <input name="version" type="text" class="eingabefeld" id="fsn3" value="<?php echo $row_rst_material['version'] ?>" size="20" maxlength="20"> 
      <?php }?>
      <input name="hurl_seite" type="hidden" id="hurl_seite2" value="<?php echo $editFormAction; ?>">
  
  
  <?php }?>
 
    <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst_material['artikelid']; ?>">
    <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
    <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">
	<input name="hurl_fsn" type="hidden" id="hurl_fsn" value="<?php echo $row_auftragsberbeitung['fsn']; ?>">
	<input name="fidfreigeben" type="submit" class="buttonmiddle" id="auftragsliste22" value="Freigabe &amp; Druck">
  <hr>
  <?php }?>
  </td>
  </tr>
</form>
	<?php } while ($row_auftragsberbeitung = mysql_fetch_assoc($auftragsberbeitung)); ?>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">


  	
	
    <?php }?>

	
<table width="100%" border="0" cellpadding="0" cellspacing="0">	
    <?php if (isset($materialnummer2)) {?>
	
    
<td colspan="5"> 

    <?php }?>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">	
	

	
	
	
    <?php if (	(isset($materialnummer2)) or 
				(isset($materialnummer1)) or 
				(isset($materialnummer3)) or 
				(isset($materialnummer4)) or 
				(isset($materialnummer5)) or
				(isset($materialnummer6)) or
				(isset($materialnummer7)) or
				(isset($materialnummer8)) or
				(isset($materialnummer9)) ) {?>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      
  <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16">Liste 
    der Materialnummern</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr><tr bgcolor="#CCCCFF">
<td colspan="5"> 
<?php if ($totalRows_rst_material > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php  if ($artikelid==$row_rst_material['artikelid']){?>

  <table width="420" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#00CC00">
    <?php }else{?>
	<table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<?php }?>
    <tr> 
    <td width="120"><a href="<?php echo $editFormAction."&artikelid=".$row_rst_material['artikelid']."&materialnummer$materialindex=1"; ?>">
		  <img src="../documents/materialbilder/<?php echo $row_rst_material['link_dokument'] ?>" height="110" border="0" ></a><br> 
        <strong><?php echo utf8_decode($row_rst_material['Bezeichnung']); ?></strong><br> <?php echo $row_rst_material['Nummer']; ?> </td>
	
	<?php  if ($artikelid==$row_rst_material['artikelid']){?>
	<td width="300"> Stückliste und Versionsinfo für <?php echo $row_rst_material['version'] ?>
<?php if ($row_rst_material['dokuset']==1){
echo "<br><br><a href=\"./dokuset.tmp1.php?url_artikelid=$artikelid&url_sn=0&fid=0\" target=\"_blank\">neutrale Bedienanleitung hier drucken</a>";
 
}else{
 echo "<br><br>Keine Bedienanleitung angelegt.";
}
?>

</td>
	<?php }?>	
		
    </tr>
    <?php  if ($artikelid==$row_rst_material['artikelid']){?>
    <tr> 
      <td><form action="<?php echo $editFormAction; ?>" method="POST" name="form2" >
	  
	  
	   <?php if ($row_rst_material['anzahlp']>0){ ?>
	   
        <input name="suchtext" type="text" class="eingabefeld" id="suchtext" value="" size="20" maxlength="20">
						<?php if ($row_rst_material['anzahlp']>=2){?>
						 <input name="suchtext2" type="text" class="eingabefeld" id="suchtext2" value="" size="20" maxlength="20">
						<?php }?>
						<?php if ($row_rst_material['anzahlp']>=3){?>
						 <input name="suchtext3" type="text" class="eingabefeld" id="suchtext3" value="" size="20" maxlength="20">
						<?php }?>
		
        <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>"><hr>
          <?php }?>
          <p>
            <input type="Submit" name="neugeraet" class="buttongross" value=" Neues Ger&auml;t ">
            <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst_material['artikelid']; ?>">
            <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
            <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">
          </p>
        </form>
		<?php echo $oktxt?>
		</td>
		<?php  if ($artikelid==$row_rst_material['artikelid']){?>
<?php 		

mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst2 = "SELECT artikelversionen.id FROM  artikeldaten, artikelversionen WHERE artikeldaten.artikelid='$row_rst_material[artikelid]' AND artikelversionen.artikelid='$row_rst_material[artikelid]' AND artikelversionen.version = artikeldaten.version ";
		$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
		$row_rst2 = mysql_fetch_assoc($rst2);
		$totalRows_rst2 = mysql_num_rows($rst2);
	

		$maxRows_rst_stueckliste = 50;
		$pageNum_rst_stueckliste = 0;
		if (isset($HTTP_GET_VARS['pageNum_rst_stueckliste'])) {
		  $pageNum_rst_stueckliste = $HTTP_GET_VARS['pageNum_rst_stueckliste'];
		}
		$startRow_rst_stueckliste = $pageNum_rst_stueckliste * $maxRows_rst_stueckliste;

		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rst_stueckliste = "SELECT * FROM artikelversionsstuecklisten WHERE artikelversionsstuecklisten.version_id ='$row_rst2[id]' AND artikelversionsstuecklisten.st_art<>'Bild' ORDER BY artikelversionsstuecklisten.st_art";
		$query_limit_rst_stueckliste = sprintf("%s LIMIT %d, %d", $query_rst_stueckliste, $startRow_rst_stueckliste, $maxRows_rst_stueckliste);
		$rst_stueckliste = mysql_query($query_limit_rst_stueckliste, $qsdatenbank) or die(mysql_error());
		$row_rst_stueckliste = mysql_fetch_assoc($rst_stueckliste);
		
		if (isset($HTTP_GET_VARS['totalRows_rst_stueckliste'])) {
		  $totalRows_rst_stueckliste = $HTTP_GET_VARS['totalRows_rst_stueckliste'];
		} else {
		  $all_rst_stueckliste = mysql_query($query_rst_stueckliste);
		  $totalRows_rst_stueckliste = mysql_num_rows($all_rst_stueckliste);
		}
		$totalPages_rst_stueckliste = ceil($totalRows_rst_stueckliste/$maxRows_rst_stueckliste)-1;

?>
		
		
		
	<td width="200"> 
	
	
	<?php if ($totalRows_rst_stueckliste > 0) { // Show if recordset not empty ?>
		  <?php do { ?> 
		<?php echo utf8_decode($row_rst_stueckliste['menge']); ?> - 
	<?php echo utf8_decode($row_rst_stueckliste['artikelnummer']); ?> - 
		<?php echo utf8_decode($row_rst_stueckliste['bezeichnung']); ?> - 
		<?php echo utf8_decode($row_rst_stueckliste['hinweis']); ?><br>
			
<?php } while ($row_rst_stueckliste = mysql_fetch_assoc($rst_stueckliste)); ?>
	


<?php } else {// Show if recordset not empty ?> 
	 Artikel hat keine St&uuml;ckliste. 



		<br> Es gibt <?php echo $totalRows_rst2 ?> Versionen.


		<?php } // Show if recordset not empty  stückliste ?> 
	<?php }?></td>
	
    </tr>
    <?php } /* wenn artikel ausgewählt */?>



  <?php } while ($row_rst_material = mysql_fetch_assoc($rst_material));  ?>
<?php } // Show if recordset not empty ?>
  </table>
	
	 
  <?php if ($totalRows_rst_material == 0) { // Show if recordset empty ?>
  Keine Materialdaten vorhanden. 
  <?php } // Show if recordset empty ?>
  </font> </td> </tr> 
  <tr bgcolor="#FFCC00"> 
    <td colspan="5" bgcolor="#CCCCFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    
  <td colspan="5"><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer$materialindex=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="7">&nbsp;<img src="picture/up.gif" width="20" height="20" border="0"> 
    Erste Seite </font></a> ----------------------von <?php echo ($startRow_rst_material + 1) ?> bis <?php echo min($startRow_rst_material + $maxRows_rst_material, $totalRows_rst_material) ?> insgesamt: <?php echo $totalRows_rst_material ?> Ger&auml;tevarianten --------------------- <a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer$materialindex=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="7">n&auml;chste 
    Seite <img src="picture/down.gif" width="20" height="20" border="0"></font></a> 
  </td>
  </tr>
  <?php }?>
  
   
  </table>




<?php } /* ende Auftrag bearbeiten  */









 if ($url_user>0 and $action=="nachdrucken"){?>
  <?php $oktxt.=" Zum Nachrucken der Dokusets und Etiketten, Artikel anwählen , SN eingeben und Nachdruck dr&uuml;cken.";?>
</p>  
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>&action=nachdrucken">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="224" bgcolor="#FFFFFF"><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a></td>
      <td width="224" bgcolor="#FFFFFF"><a href="pp.<?php echo $location ?>.php?url_user=<?php echo $url_user ?>&action=auftragbearbeiten" target="_self"><font size="5"><img src="picture/formular.gif" width="29" height="40" border="0" align="absmiddle"> 
        Auswahlliste</font></a></td>
      <td width="270" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="296" bgcolor="#FFCC00"><img src="picture/iconEdit_32x32.gif" width="32" height="32" align="absmiddle"> 
        <strong>Doku nachdrucken</strong></td>
      <td width="230" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <?php if (($action=="nachdrucken") and ($totalRows_rst_material==0)){?>
    <tr bgcolor="#FFCC00"> 
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16"> 
        Liste der Materialummern f&uuml;r Nachdruck</td>
    </tr>
    <tr bgcolor="#FFCC00"></td>
    <tr bgcolor="#FFCC00"></tr>
    <tr bgcolor="#FFCC00"> 
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#FFCC00"> 
      <td><input name="materialnummer1" type="submit" class="buttongross" id="materialnummer1" value="Zubehör"></td>
      <td><input name="materialnummer4" type="submit" class="buttongross" id="materialnummer4" value="Deckel"></td>
      <td><input name="materialnummer2" type="submit" class="buttongross" id="materialnummer2" value="Ersatzteile Beschlagteile"></td>
      <td><input name="materialnummer3" type="submit" class="buttongross" id="materialnummer3" value="Ersatzteile Elektro"></td>
      <td><input name="materialnummer5" type="submit" class="buttongross" id="materialnummer5" value="Grundkörper"></td>
    </tr>
    <tr bgcolor="#FFCC00"> 
      <td><input name="materialnummer6" type="submit" class="buttongross" id="materialnummer6" value="BLT unbeheizt"></td>
      <td><input name="materialnummer7" type="submit" class="buttongross" id="materialnummer7" value="BLT beheizt"></td>
      <td><input name="materialnummer8" type="submit" class="buttongross" id="materialnummer8" value="Zubehör"></td>
      <td><input name="materialnummer9" type="submit" class="buttongross" id="materialnummer9" value="Industrieteile"></td>
      <td><input name="materialnummer10" type="submit" class="buttongross" id="materialnummer10" value="Sonder"></td>
    </tr>
    <tr bgcolor="#FFCC00"> 
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">


  <table width="100%" border="0" cellpadding="0" cellspacing="0">	
	
    <?php }?>
	</form>
	
	
    <?php if (		(isset($materialnummer2)) or 
					(isset($materialnummer1)) or 	
					(isset($materialnummer3)) or 
					(isset($materialnummer4)) or 
					(isset($materialnummer5)) or
					 (isset($materialnummer6)) or
					 (isset($materialnummer7)) or
					 (isset($materialnummer8)) or
					 (isset($materialnummer9)) or
					 (isset($materialnummer10))   ) {?>
    <tr bgcolor="#FFCC00"> 
      
  <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#FFCC00"> 
      
  <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16"> Liste 
    der unbeheizte Materialnummern</td>
    </tr>
zum Nachdruck 
<tr bgcolor="#FFCC00"> 
      <td colspan="5">&nbsp;</td>
    </tr><tr bgcolor="#FFCC00">
<td colspan="5"> 
<?php if ($totalRows_rst_material > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php  if ($artikelid==$row_rst_material['artikelid']){?>

  <table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#00CC00">
    <?php }else{?>
	<table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<?php }?>
    <tr> 
          <td width="120"><a href="<?php echo $editFormAction."&artikelid=".$row_rst_material['artikelid']."&materialnummer$materialindex=1"; ?>">
		  <img src="../documents/materialbilder/<?php echo $row_rst_material['link_dokument'] ?>" height="110" border="0" ></a><br> 
        <strong><?php echo $row_rst_material['Bezeichnung']; ?></strong><br> <?php echo $row_rst_material['Nummer']; ?> </td>
    </tr>
    <?php  if ($artikelid==$row_rst_material['artikelid']){?>
    <tr> 
      <td><form action="<?php echo $editFormAction; ?>" method="POST" name="form2" >
	  
	  
	   <?php if ($row_rst_material['anzahlp']>0){ ?>
	   SN:
        <input name="suchtext" type="text" class="eingabefeld" id="suchtext" value="" size="20" maxlength="20">
        <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>"><hr>
          <?php }?>
          <p>
            <input type="Submit" name="nachdrucken" class="buttongross" value="Nachdrucken">
            <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst_material['artikelid']; ?>">
            <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
            <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">
          </p>
        </form>
		<?php echo $oktxt?>
		</td>
    </tr>
    <?php } ?>
  </table>


  <?php } while ($row_rst_material = mysql_fetch_assoc($rst_material));  ?>
	  <?php } // Show if recordset not empty ?>	
	 
  <?php if ($totalRows_rst_material == 0) { // Show if recordset empty ?>
  Keine Materialdaten vorhanden. 
  <?php } // Show if recordset empty ?>
  </font> </td> </tr> 
  <tr bgcolor="#CCCCFF"> 
    <td colspan="5" bgcolor="#FFCC00">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    
  <td colspan="5"><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer$materialindex=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="7">&nbsp;<img src="picture/up.gif" width="20" height="20" border="0"> 
    Erste Seite </font></a> ----------------------von <?php echo ($startRow_rst_material + 1) ?> bis <?php echo min($startRow_rst_material + $maxRows_rst_material, $totalRows_rst_material) ?> insgesamt: <?php echo $totalRows_rst_material ?> Ger&auml;tevarianten --------------------- <a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer$materialindex=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="7">n&auml;chste 
    Seite <img src="picture/down.gif" width="20" height="20" border="0"></font></a> 
  </td>
  </tr>
  <?php }?>
  
   
  </table>




<?php } /* ende Nachdrucken bearbeiten  */
















if ($url_user>0 and $action=="anleitung"){?>
<?php $oktxt="Zum Anzeigen das entsprechende Thema auswählen und Auswahl dr&uuml;cken.";?>
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
      <td colspan="2"><hr></td>
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
?>