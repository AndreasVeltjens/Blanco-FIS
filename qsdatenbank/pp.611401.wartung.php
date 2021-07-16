<?php require_once('../Connections/qsdatenbank.php');
 require_once('../Connections/qsdatenbank.php');

$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}



if (isset($_POST["wartungspeichern"])) {
if (($_POST['checkbox1']>0) or ($_POST['checkbox2']>0) or ($_POST['checkbox3']>0) or ($_POST['checkbox4']>0) or ($_POST['checkbox5']>0) or ($_POST['checkbox6']>0) or($_POST['checkbox7']>0) or($_POST['checkbox8']>0) or($_POST['checkbox9']>0) or($_POST['checkbox10']>0)) {

	if ($_POST['checkbox1']==1) {$Wartungstext.="Einschlaghilfe i.O. ";}	
	if ($_POST['checkbox2']==1) {$Wartungstext.="Bits ohne Besch�digung ";}
	if ($_POST['checkbox3']==1) {$Wartungstext.="Druckluftschrauber i.O. ";}
	if ($_POST['checkbox4']==1) {$Wartungstext.="Drehmomenteinstellung �berpr�ft i.O. ";}
	if ($_POST['checkbox5']==1) {$Wartungstext.=" ";}
	if ($_POST['checkbox6']==1) {$Wartungstext.=" ";}
	if ($_POST['checkbox7']==1) {$Wartungstext.=" ";}
	if ($_POST['checkbox8']==1) {$Wartungstext.=" ";}
	if ($_POST['checkbox9']==1) {$Wartungstext.=" ";}
	if ($_POST['checkbox10']==1) {$Wartungstext.=" ";}

  $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, datum, `user`, beschreibung) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['url_id_fhm'], "int"),
                       GetSQLValueString($_POST['url_id_fhmea'], "int"),
                       "now()",
                       GetSQLValueString($_POST['url_user'], "int"),
                       GetSQLValueString($Wartungstext,"text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $oktxt.="Wartung erfolgreich gespeichert.";
  $action="";
} else {
 $errtxt="Sie m&uuml;ssen schon mal einen Wartungspunkt ausw&auml;hlen. Erst machen, dann melden!";

} /* ende checkbox ungleich null */
}


if (isset($_POST["stoerungspeichern"])) {
if (($_POST['checkbox1']>0) or ($_POST['checkbox2']>0) or ($_POST['checkbox3']>0) or ($_POST['checkbox4']>0) or ($_POST['checkbox5']>0) or ($_POST['checkbox6']>0) or($_POST['checkbox7']>0) or($_POST['checkbox8']>0) or($_POST['checkbox9']>0) or($_POST['checkbox10']>0) or($_POST['checkbox11']>0)) {

	if ($_POST['checkbox1']==1) {$Wartungstext.="Einschlaghilfe defekt ";}	
	if ($_POST['checkbox2']==1) {$Wartungstext.="Bits besch�digt. Ersatz notwendig. ";}
	if ($_POST['checkbox3']==1) {$Wartungstext.="Werkzeug nicht vollst�ndig bzw. n.i.O. ";}
	if ($_POST['checkbox4']==1) {$Wartungstext.="Druckluftschrauber n.i.O. ";}
	if ($_POST['checkbox5']==1) {$Wartungstext.="Einstellung Drehmoment nicht m�glich.";}
	if ($_POST['checkbox6']==1) {$Wartungstext.="FIS funktioniert nicht ";}
	if ($_POST['checkbox7']==1) {$Wartungstext.="Ettikettendrucker ohne Funktion ";}
	if ($_POST['checkbox8']==1) {$Wartungstext.="Stammdaten fehlerhaft ";}
	if ($_POST['checkbox9']==1) {$Wartungstext.="  ";}
	if ($_POST['checkbox10']==1) {$Wartungstext.=" ";}
	if ($_POST['checkbox11']==1) {$Wartungstext.="unbekanntes Problem, Montage nicht m�glich ";}

  $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, datum, `user`, beschreibung) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['url_id_fhm'], "int"),
                       GetSQLValueString($_POST['url_id_fhmea'], "int"),
                       "now()",
                       GetSQLValueString($_POST['url_user'], "int"),
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


if (isset($materialnummer2) or $materialnummer2==1){ $warengruppe="BLT K"; $abfrage=" or artikeldaten.Gruppe like '%ET%' ";}
if (isset($materialnummer3) or $materialnummer3==1){ $warengruppe="BLT KB%";}
if (isset($materialnummer4) or $materialnummer4==1){ $warengruppe="sonst%";}
if (isset($materialnummer5) or $materialnummer5==1){ $warengruppe="industrie%";}

$maxRows_rst_material = 6;
$pageNum_rst_material = 0;
if (isset($HTTP_GET_VARS['pageNum_rst_material'])) {
  $pageNum_rst_material = $HTTP_GET_VARS['pageNum_rst_material'];
}
$startRow_rst_material = $pageNum_rst_material * $maxRows_rst_material;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst_material = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert=1 and artikeldaten.Kontierung like '%fert%' and ( artikeldaten.Gruppe like '$warengruppe' $abfrage ) ORDER BY artikeldaten.Gruppe, artikeldaten.Bezeichnung ";
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
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
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
	font-size: 30px;
	height: 40px;
	width: 100px;
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
      Wartung am Arbeitsplatz</td>
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
      Maschinenst�rungen<br> 
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
<?php $oktxt.=" Zum Speichern bitte Wartungspunkte ausw&auml;hlen und speichern dr&uuml;cken.";?></p> 
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
      <td><input name="checkbox5" type="checkbox" class="checkboxgross" id="checkbox5" value="checkbox">
      </td>
      <td colspan="3"><div align="center"></div>
        
      </td>
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
        </div></td>
      <td colspan="3"><div align="center"> </div>
        <font size="4">&nbsp; </font></td>
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
      <td colspan="3">FIS funktioniert nicht </td>
      <td rowspan="6"> <div align="center"> 
          <input name="stoerungspeichern" type="submit" class="buttongross" id="stoerungspeichern" value="St&ouml;rung Speichern">
        </div></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td height="31" bgcolor="#FF9900"> <div align="center"> 
          <input name="checkbox7" type="checkbox" class="checkboxgross" id="checkbox7" value="1">
        </div></td>
      <td colspan="3" bgcolor="#FF9900">Ettikettendrucker ohne Funktion</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox8" type="checkbox" class="checkboxgross" id="checkbox8" value="1">
        </div></td>
      <td colspan="3">Stammdatenfehler</td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td bgcolor="#FF9900"> <div align="center"> 
          <input name="checkbox9" type="checkbox" class="checkboxgross" id="checkbox9" value="1">
        </div></td>
      <td colspan="3" bgcolor="#FF9900">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox10" type="checkbox" class="checkboxgross" id="checkbox10" value="1">
        </div></td>
      <td colspan="3">&nbsp;</td>
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
<?php } /* ende St�rung anlegen */
?>


<?php
if ($artikelid>0 AND $url_user>0 AND $action=="auftragbearbeiten"){
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $query_rstv = "SELECT artikeldaten.anzahlp, artikeldaten.artikelid, artikeldaten.link_dokument, artikeldaten.version, artikeldaten.Bezeichnung,
                artikeldaten.Nummer, artikelversionen.configprofil  FROM  artikeldaten, artikelversionen
                  WHERE artikeldaten.artikelid = '$artikelid'
                  and artikeldaten.artikelid= artikelversionen.artikelid
                  AND artikeldaten.version= artikelversionen.version";
  $rstv = mysql_query($query_rstv, $qsdatenbank) or die(mysql_error());
  $row_rstv = mysql_fetch_assoc($rstv);
  $totalRows_rstv = mysql_num_rows($rstv);

  $configprofilv = new IfisArtikelVersionConfig($row_rstv['configprofil']);




  ?>

  <table width="100%" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">

  <tr>
    <td>
     <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
       <table width="100%" border="0" cellspacing="5"><tr><td width="500">
             <?php echo $configprofilv->ShowControlFields(); ?>
             </td><td width="120" bgcolor="#FFFFFF">
                   <table><tr><td>

                               <a href="pp.<?php echo $location ?>.php?url_user=<?php echo $url_user ?>&action=auftragbearbeiten" target="_self"><font size="5"><img src="picture/zurueck.png" border="0" align="absmiddle">
                                       Auswahlliste</font></a><hr>
                       </td></tr>
                       <tr><td width="120" bgcolor="#00CC00">




             Konfigprofil: <?php echo $configprofilv->ShowProfilStatus(1); ?><hr>
             <a href="<?php echo $editFormAction; ?>">
               <img src="../documents/materialbilder/<?php echo $row_rstv['link_dokument'] ?>" height="110" border="0" ></a><br>
             <strong><?php echo $row_rstv['Bezeichnung']; ?></strong><br> <?php echo $row_rstv['Nummer']." - ".$row_rstv['version'] ; ?>

                <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>"><hr>
                <input name="suchtext" type="text" class="eingabefeld" id="suchtext" value="" size="12" maxlength="12">
                <input type="Submit" name="neugeraet" class="buttongross" value=" Neues Ger&auml;t ">
                <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rstv['artikelid']; ?>">
                <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
                           </td></tr></table>

               </td></tr></table>

     </form>


      <?php echo $oktxt?> </td>
  </tr>
  </table>
<?php } ?>




 <?php if ($url_user>0 and $action=="auftragbearbeiten"){?>

  <?php $oktxt.=" Zum Speichern Artikel anw�hlen und Neues Ger&auml;t dr&uuml;cken.";?>

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
    <?php if (!isset($materialnummer) and !isset($materialnummer2) and !isset($materialnummer3) and !isset($materialnummer4) and !isset($materialnummer5)){ ?>
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
      <td><input name="url_user" type="hidden" id="url_user" value="<?php echo $url_user; ?>">
        <input name="url_id_fhm" type="hidden" id="url_id_fhm" value="<?php echo $fhm_id ?>">
        <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="6">
        <input name="nachdrucken" type="submit" class="buttongross" id="nachdrucken" value="Nachdrucken"></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5"><hr></td>
    </tr>
    <?php }?>
    <?php if (isset($materialnummer) or isset($nachdrucken)) {?>
    <tr bgcolor="#CCCCFF">
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16">
        Liste der Materialummern</td>
    </tr></td></tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td><input name="materialnummer2" type="submit" class="buttongross" id="materialnummer2" value="unbeheizte BLT"></td>
      <td>&nbsp;</td>
      <td><input name="materialnummer3" type="submit" class="buttongross" id="materialnummer3" value="beheizte BLT"></td>
      <td><input name="materialnummer4" type="submit" class="buttongross" id="materialnummer4" value="Zubeh&ouml;r"></td>
      <td><input name="materialnummer5" type="submit" class="buttongross" id="materialnummer5" value="Industrieteile"></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">


  <table width="100%" border="0" cellpadding="0" cellspacing="0">

    <?php }?>
	</form>


  <?php if (isset($materialnummer2)) {?>
    <tr bgcolor="#CCCCFF">
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16">
        Liste der unbeheizte Materialummern</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">&nbsp;</td>
    </tr><tr bgcolor="#CCCCFF">
<td colspan="5">
<?php if ($totalRows_rst_material > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php  if ($artikelid==$row_rst_material['artikelid']){?>

  <table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#00CC00">
    <?php }else{?>
	<table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<?php }?>
    <tr>
          <td width="120"><a href="<?php echo $editFormAction."&artikelid=".$row_rst_material['artikelid']."&materialnummer2=1"; ?>">
		  <img src="../documents/materialbilder/<?php echo $row_rst_material['link_dokument'] ?>" height="110" border="0" ></a><br>
        <strong><?php echo $row_rst_material['Bezeichnung']; ?></strong><br> <?php echo $row_rst_material['Nummer']; ?> </td>
    </tr>
    <?php  if ($artikelid==$row_rst_material['artikelid']){?>
    <tr>
      <td><form name="form5" method="POST" action="<?php echo $editFormAction; ?>">
          <input type="Submit" name="neugeraet" class="buttongross" value=" Neues Ger&auml;t ">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst_material['artikelid']; ?>">
          <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
          <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">
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
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF">

  <td colspan="5"><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer2=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5">&nbsp;<img src="picture/up.gif" width="20" height="20" border="0">
    Erste Seite </font></a> ----------------------von <?php echo ($startRow_rst_material + 1) ?> bis <?php echo min($startRow_rst_material + $maxRows_rst_material, $totalRows_rst_material) ?> insgesamt: <?php echo $totalRows_rst_material ?> Ger&auml;tevarianten --------------------- <a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer2=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="5">n&auml;chste
    Seite <img src="picture/down.gif" width="20" height="20" border="0"></font></a>
  </td>
  </tr>
  <?php }?>
  <?php if (isset($materialnummer3)) {?>
  <tr bgcolor="#CCCCFF">
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16">
        Liste der unbeheizte Materialummern</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">
 <?php if ($totalRows_rst_material > 0) { // Show if recordset not empty ?>
<?php do { ?>

        <?php  if ($artikelid==$row_rst_material['artikelid']){?>

  <table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#00CC00">
    <?php }else{?>
	<table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<?php }?>
    <tr>
          <td width="120"><a href="<?php echo $editFormAction."&artikelid=".$row_rst_material['artikelid']."&materialnummer3=1"; ?>">
		  <img src="../documents/materialbilder/<?php echo $row_rst_material['link_dokument'] ?>" height="110" border="0" ></a><br>
        <strong><?php echo $row_rst_material['Bezeichnung']; ?></strong><br> <?php echo $row_rst_material['Nummer']; ?> </td>
    </tr>
    <?php  if ($artikelid==$row_rst_material['artikelid']){?>
    <tr>
      <td>



	  <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">

        <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>"><hr>
        <input name="suchtext" type="text" class="eingabefeld" id="suchtext" value="" size="12" maxlength="12">
        <input type="Submit" name="neugeraet" class="buttongross" value=" Neues Ger&auml;t ">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst_material['artikelid']; ?>">
          <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
      </form>


      <?php echo $oktxt?> </td>
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
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF">

  <td colspan="5"><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer3=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5">&nbsp;</font></a><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer2=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5"><img src="picture/up.gif" width="20" height="20" border="0"></font></a>
    <a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer3=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5">Erste
    Seite </font></a> ----------------------von <?php echo ($startRow_rst_material + 1) ?> bis <?php echo min($startRow_rst_material + $maxRows_rst_material, $totalRows_rst_material) ?> insgesamt: <?php echo $totalRows_rst_material ?> Ger&auml;tevarianten --------------------- <a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer3=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="5">n&auml;chste
    Seite </font></a><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer2=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="5"><img src="picture/down.gif" width="20" height="20" border="0"></font></a>
  </td>
  </tr>
  <?php }?>




    <?php if (isset($materialnummer4)) {?>
  <tr bgcolor="#CCCCFF">
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16">
        Liste der unbeheizte Materialummern</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">
 <?php if ($totalRows_rst_material > 0) { // Show if recordset not empty ?>

<?php do { ?>
<?php  if ($artikelid==$row_rst_material['artikelid']){?>

  <table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#00CC00">
    <?php }else{?>
	<table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<?php }?>
    <tr>
          <td width="120"><a href="<?php echo $editFormAction."&artikelid=".$row_rst_material['artikelid']."&materialnummer4=1"; ?>">
		  <img src="../documents/materialbilder/<?php echo $row_rst_material['link_dokument'] ?>" height="110" border="0" ></a><br>
        <strong><?php echo $row_rst_material['Bezeichnung']; ?></strong><br> <?php echo $row_rst_material['Nummer']; ?> </td>
    </tr>
        <?php  if ($artikelid==$row_rst_material['artikelid']){



          ?>

    <tr>


      <td>
        <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
        <table>
          <tr>
            <td>

            </td>
            <td>

                <input type="Submit" name="neugeraet" class="buttongross" value=" Neues Ger&auml;t ">
                <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst_material['artikelid']; ?>">
                <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
                <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">

            </td>
          </tr>
        </table>
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
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF">

  <td colspan="5"><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer4=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5">&nbsp;</font></a><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer2=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5"><img src="picture/up.gif" width="20" height="20" border="0"></font></a>
    <a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer4=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5">Erste
    Seite </font></a> ----------------------von <?php echo ($startRow_rst_material + 1) ?> bis <?php echo min($startRow_rst_material + $maxRows_rst_material, $totalRows_rst_material) ?>
	  insgesamt: <?php echo $totalRows_rst_material ?> Ger&auml;tevarianten --------------------- <a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer4=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="5">n&auml;chste
    Seite</font></a><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer2=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="5">
    <img src="picture/down.gif" width="20" height="20" border="0"></font></a>
  </td>
  </tr>
  <?php }?>

    <?php if (isset($materialnummer5)) {?>
  <tr bgcolor="#CCCCFF">
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16">
        Liste der unbeheizte Materialummern</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td colspan="5">
 <?php if ($totalRows_rst_material > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php  if ($artikelid==$row_rst_material['artikelid']){?>

  <table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#00CC00">
    <?php }else{?>
	<table width="120" border="1" align="left" cellpadding="2" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
	<?php }?>
    <tr>
          <td width="120"><a href="<?php echo $editFormAction."&artikelid=".$row_rst_material['artikelid']."&materialnummer5=1"; ?>">
		  <img src="../documents/materialbilder/<?php echo $row_rst_material['link_dokument'] ?>" height="110" border="0" ></a><br>
        <strong><?php echo $row_rst_material['Bezeichnung']; ?></strong><br> <?php echo $row_rst_material['Nummer']; ?> </td>
    </tr>
    <?php  if ($artikelid==$row_rst_material['artikelid']){?>
    <tr>
      <td>



        <form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
          <input type="Submit" name="neugeraet" class="buttongross" value=" Neues Ger&auml;t ">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst_material['artikelid']; ?>">
          <input name="hurl_user_id" type="hidden" id="hurl_user_id" value="<?php echo $url_user; ?>">
          <input name="hurl_seite" type="hidden" id="hurl_seite" value="<?php echo $editFormAction; ?>">
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
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td colspan="5"><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer2=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5"><img src="picture/up.gif" width="20" height="20" border="0"></font></a><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer5=1", $currentPage, 0, $queryString_rst_material); ?>"><font size="5">&nbsp;Erste
      Seite </font></a> ----------------------von <?php echo ($startRow_rst_material + 1) ?> bis <?php echo min($startRow_rst_material + $maxRows_rst_material, $totalRows_rst_material) ?> insgesamt:
	  <?php echo $totalRows_rst_material ?> Ger&auml;tevarianten --------------------- <a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer5=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="5">n&auml;chste
      Seite</font></a><a href="<?php printf("%s?pageNum_rst_material=%d%s&materialnummer2=1", $currentPage, min($totalPages_rst_material, $pageNum_rst_material + 1), $queryString_rst_material); ?>"><font size="5">
      <img src="picture/down.gif" width="20" height="20" border="0"></font></a>
    </td>
  </tr>
  <?php }?>

</table>












<?php } /* ende Auftrag bearbeiten  */

if ($url_user>0 and $action=="anleitung"){?>
<?php $oktxt="Zum Anzeigen das entsprechende Thema ausw�hlen und Auswahl dr&uuml;cken.";?>
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
?>