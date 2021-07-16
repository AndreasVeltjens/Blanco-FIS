<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if (isset($HTTP_POST_VARS["wartungspeichern"])) {
if (($HTTP_POST_VARS['checkbox1']>0) or ($HTTP_POST_VARS['checkbox2']>0) or ($HTTP_POST_VARS['checkbox3']>0) or ($HTTP_POST_VARS['checkbox4']>0) or ($HTTP_POST_VARS['checkbox5']>0) or ($HTTP_POST_VARS['checkbox6']>0) or($HTTP_POST_VARS['checkbox7']>0) or($HTTP_POST_VARS['checkbox8']>0) or($HTTP_POST_VARS['checkbox9']>0) or($HTTP_POST_VARS['checkbox10']>0)) {

	if ($HTTP_POST_VARS['checkbox1']==1) {$Wartungstext.="Soll -Luftdruck geprüft und eingestellt. ";}	
	if ($HTTP_POST_VARS['checkbox2']==1) {$Wartungstext.="Druckluftöl nachgef&uumlllt. ";}
	if ($HTTP_POST_VARS['checkbox3']==1) {$Wartungstext.="Wasserabscheider gelehrt. ";}
	if ($HTTP_POST_VARS['checkbox4']==1) {$Wartungstext.="Kühlmittelstand Temperierung i.O.. ";}
	if ($HTTP_POST_VARS['checkbox5']==1) {$Wartungstext.="Klebeband erneuert. ";}
	if ($HTTP_POST_VARS['checkbox6']==1) {$Wartungstext.="Maschine gereinigt. ";}
	if ($HTTP_POST_VARS['checkbox7']==1) {$Wartungstext.="Führungselemente und Zentrierung geschmiert.  ";}
	if ($HTTP_POST_VARS['checkbox8']==1) {$Wartungstext.="Zentrierung i.O. ";}
	if ($HTTP_POST_VARS['checkbox9']==1) {$Wartungstext.="Beschichtung gereinigt. ";}
	if ($HTTP_POST_VARS['checkbox10']==1) {$Wartungstext.="  ";}

  $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, datum, `user`, beschreibung) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['url_id_fhm'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['url_id_fhmea'], "int"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['url_user'], "int"),
                       GetSQLValueString($Wartungstext,"text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $oktxt="Wartung erfolgreich gespeichert.";
  $action="";
} else {
 $errtxt="Sie m&uuml;ssen schon mal einen Wartungspunkt ausw&auml;hlen. Erst machen, dann melden!";

} /* ende checkbox ungleich null */
}


if (isset($HTTP_POST_VARS["stoerungspeichern"])) {
if (($HTTP_POST_VARS['checkbox1']>0) or ($HTTP_POST_VARS['checkbox2']>0) or ($HTTP_POST_VARS['checkbox3']>0) or ($HTTP_POST_VARS['checkbox4']>0) or ($HTTP_POST_VARS['checkbox5']>0) or ($HTTP_POST_VARS['checkbox6']>0) or($HTTP_POST_VARS['checkbox7']>0) or($HTTP_POST_VARS['checkbox8']>0) or($HTTP_POST_VARS['checkbox9']>0) or($HTTP_POST_VARS['checkbox10']>0) or($HTTP_POST_VARS['checkbox11']>0)) {

	if ($HTTP_POST_VARS['checkbox1']==1) {$Wartungstext.="Heizung ohne Funktion. ";}
	if ($HTTP_POST_VARS['checkbox2']==1) {$Wartungstext.="Beschichtung Heizelement an Verschleissgrenze ";}
	if ($HTTP_POST_VARS['checkbox3']==1) {$Wartungstext.="Steuerungsfehler. ";}
	if ($HTTP_POST_VARS['checkbox4']==1) {$Wartungstext.="Zentrierung Formaufnahme defekt. ";}
	if ($HTTP_POST_VARS['checkbox5']==1) {$Wartungstext.="Fehler Temperierung, Lecktage ";}
	if ($HTTP_POST_VARS['checkbox6']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox7']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox8']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox9']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox10']==1) {$Wartungstext.=" ";}
	if ($HTTP_POST_VARS['checkbox11']==1) {$Wartungstext.="unbekanntes Problem, Schweißen nicht möglich ";}

  $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, datum, `user`, beschreibung) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['url_id_fhm'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['url_id_fhmea'], "int"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['url_user'], "int"),
                       GetSQLValueString($Wartungstext,"text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $oktxt="Wartung erfolgreich gespeichert.";
  $action="";
} else {
 $errtxt="Sie m&uuml;ssen schon mal eine Auswahl treffen, sonst geht es nicht.";

} /* ende checkbox ungleich null */
}


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
	height: 150px;
	width: 150px;
	cursor: hand;
}
-->
</style>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr bgcolor="#CCCCCC"> 
    <td width="25%"><img src="picture/b_calendar.png" width="16" height="16"> Wartung am Arbeitsplatz</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%"><div align="right"></div></td>
  </tr>
  <?php if ($url_user<>"" and $action==""){?>
  <?php $oktxt="Mitarbeiter ".$row_rstuser['name']." angemeldet. Bitte w&auml;hlen Sie aus den Funktionen.";?>
  <tr bgcolor="#FFFFFF"> 
    <td><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a> 
      <div align="center"></div></td>
    <td width="357"><div align="center"><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=anleitung"><img src="picture/formular.gif" width="29" height="40" border="0" align="absmiddle"> 
        <font size="+1">Anleitung </font></a></div></td>
    <td width="375"><div align="center"><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=auftragbearbeiten"><strong><font size="+2"><img src="picture/icon_gr_tarifkonditionen.gif" width="65" height="75" border="0" align="absmiddle"> 
        </font></strong><font size="+2">Auftr&auml;ge </font></a></div></td>
    <td width="375"><div align="center"></div></td>
  </tr>
  <?php } /* ende Mitarbeiter angemeldet und aktiv. */?>
  <?php if ($action==""){?>
  <tr bgcolor="#FFFFFF"> 
    <td><font size="4">Schwei&szlig;maschine -1- Grundk&ouml;rper</font></td>
    <td><font size="4">Schwei&szlig;maschine -2- Deckel</font></td>
    <td><font size="4">Durchbruchschwei&szlig;en -4- Deckel</font></td>
    <td><font size="4">Schwei&szlig;vorrichtung -3- Grundk&ouml;rper 
      <?php $fhm_id=6;?>
      </font></td>
  </tr>
   <?php if ($url_user<>"" and $action==""){?>
  <tr bgcolor="#FFFFFF"> 
    <td><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=wartunganlegen"><font size="4"> 
      <?php $fhm_id=21;?>
      </font><img src="picture/anschrift.gif" alt="Wartung melden" width="70" height="50" border="0" align="absmiddle"></a><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=wartunganlegen&url_fhm_id=<?php echo $fhm_id ?>"><font size="+2">Wartung<strong> 
      </strong></font></a><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=stoerunganlegen&url_fhm_id=<?php echo $fhm_id ?>"><font size="+2"><img src="picture/allg-einstellungen.gif" alt="Meldung &uuml;ber Maschinenst&ouml;rung anlegen" width="70" height="50" border="0" align="absmiddle">St&ouml;rung 
      </font></a></td>
    <td><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=wartunganlegen"><font size="4"> 
      <?php $fhm_id=20;?>
      </font><img src="picture/anschrift.gif" alt="Wartung melden" width="70" height="50" border="0" align="absmiddle"></a><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=wartunganlegen&url_fhm_id=<?php echo $fhm_id ?>"><font size="+2">Wartung<strong> 
      </strong></font></a><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=stoerunganlegen&url_fhm_id=<?php echo $fhm_id ?>"><font size="+2"><img src="picture/allg-einstellungen.gif" alt="Meldung &uuml;ber Maschinenst&ouml;rung anlegen" width="70" height="50" border="0" align="absmiddle">St&ouml;rung 
      </font></a></td>
    <td><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=wartunganlegen"><font size="4"> 
      <?php $fhm_id=1253;?>
      </font><img src="picture/anschrift.gif" alt="Wartung melden" width="70" height="50" border="0" align="absmiddle"></a><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=wartunganlegen&url_fhm_id=<?php echo $fhm_id ?>"><font size="+2">Wartung<strong> 
      </strong></font></a><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=stoerunganlegen&url_fhm_id=<?php echo $fhm_id ?>"><font size="+2"><img src="picture/allg-einstellungen.gif" alt="Meldung &uuml;ber Maschinenst&ouml;rung anlegen" width="70" height="50" border="0" align="absmiddle">St&ouml;rung 
      </font></a></td>
    <td><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=wartunganlegen"><font size="4"> 
      <?php $fhm_id=6;?>
      </font><img src="picture/anschrift.gif" alt="Wartung melden" width="70" height="50" border="0" align="absmiddle"></a><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=wartunganlegen&url_fhm_id=<?php echo $fhm_id ?>"><font size="+2">Wartung<strong> 
      </strong></font></a><a href="pp.611302.php?url_user=<?php echo $url_user ?>&action=stoerunganlegen&url_fhm_id=<?php echo $fhm_id ?>"><font size="+2"><img src="picture/allg-einstellungen.gif" alt="Meldung &uuml;ber Maschinenst&ouml;rung anlegen" width="70" height="50" border="0" align="absmiddle">St&ouml;rung 
      </font></a></td>
  </tr>
  <?php }?>
  <tr bgcolor="#FFFFFF"> 
    <td><font size="4"> 
      <?php $fhm_id=21;?>
      </font> 
      <?php include("pp.fhm.status.php"); ?>
    </td>
    <td><font size="4"> 
      <?php $fhm_id=20;?>
      </font> <?php include("pp.fhm.status.php"); ?></td>
    <td><font size="4"> 
      <?php $fhm_id=1253;?>
      </font> <?php include("pp.fhm.status.php"); ?></td>
    <td><font size="4"> 
      <?php $fhm_id=6;?>
      </font> 
      <?php include("pp.fhm.status.php"); ?></td>
  </tr>
  <?php } ?>
</table>


<?php if ($url_user>0 and $action=="wartunganlegen"){?>
<?php $oktxt="Zum Speichern bitte Wartungspunkte ausw&auml;hlen und speichern dr&uuml;cken.";?></p> 
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
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
        <font size="4"> Soll-Luftdruck gepr&uuml;ft und eingestellt.</font></td>
      <td><font size="+2"><img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"> 
        vor Schichtbeginn durchf&uuml;hren</font></td>
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
        <font size="4"> Druckluft&ouml;lstand gespr&uuml;ft und aufgef&uuml;llt.</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><input name="checkbox3" type="checkbox" class="checkboxgross" id="checkbox3" value="1"> 
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"></td>
      <td colspan="3"><div align="center"> </div>
        <font size="4"> Wasserabscheider Druckluftversorgung entleert.</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox4" type="checkbox" class="checkboxgross" id="checkbox4" value="1"> 
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"></td>
      <td colspan="3"> <div align="center"> </div>
        <font size="4"> K&uuml;hlmittelstand Temperierung gepr&uuml;ft und aufgef&uuml;llt</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><input name="checkbox5" type="checkbox" class="checkboxgross" id="checkbox5" value="1">
      </td>
      <td colspan="3"><div align="center"> </div>
        <font size="4">Klebeband Formrahmen erneuert.</font></td>
      <td rowspan="8"> <div align="center"> 
          <input name="wartungspeichern" type="submit" class="buttongross" id="wartungspeichern" value="Wartung Speichern">
        </div></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox6" type="checkbox" class="checkboxgross" id="checkbox6" value="1">
      </td>
      <td colspan="3"> <div align="center"> </div>
        <font size="4"> Maschine gereinigt.</font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="left"> 
          <input name="checkbox7" type="checkbox" class="checkboxgross" id="checkbox7" value="1">
          <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"></div></td>
      <td colspan="3"><div align="center"> </div>
        <font size="4"> F&uuml;hrungselemente und Zentrierzapfen der Vorrichtung 
        geschmiert.</font></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox8" type="checkbox" class="checkboxgross" value="1">
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"></td>
      <td colspan="3"><font size="4">Zentrierung der Form/ Werkzeuge Oberteil 
        zu Unterteil gepr&uuml;ft.</font></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td colspan="3" bgcolor="#CCCCCC"><font size="4">&nbsp;</font></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td><input name="checkbox9" type="checkbox" class="checkboxgross" id="checkbox9" value="1">
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"> 
      </td>
      <td colspan="3"> <div align="center"> </div>
        <font size="4"> Beschichtung mit PTFE-Spray gereinigt.</font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><input name="checkbox10" type="checkbox" class="checkboxgross" id="checkbox10" value="1">
        <img src="picture/star_yellow_preferences.gif" width="16" height="16" align="absmiddle"> 
      </td>
      <td colspan="3"><div align="center"> </div>
        <font size="4"> Kontrolle der Befestigung f&uuml;r Heizrahmen und Formrahmen 
        OT + UT</font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td> <input name="url_user" type="hidden" id="url_user" value="<?php echo $url_user; ?>"> 
        <input name="url_id_fhm" type="hidden" id="url_id_fhm" value="<?php echo $url_fhm_id ?>">
        <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="2"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php } /* ende Wartunganlegen */
?>






 <?php if ($url_user>0 and $action=="stoerunganlegen"){?>
  <?php $oktxt="Zum Speichern bitte Wartungspunkte ausw&auml;hlen und speichern dr&uuml;cken.";?>
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
          <input name="checkbox1" type="checkbox" class="checkboxgross" id="checkbox12" value="1">
        </div></td>
      <td colspan="3"><font size="4">Heizung ohne Funktion</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox2" type="checkbox" class="checkboxgross" value="1">
        </div></td>
      <td colspan="3"><font size="4">Beschichtung der Heizrahmen an Verschlei&szlig;sgrenze</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FF9900"> 
      <td> <div align="center"> 
          <input name="checkbox3" type="checkbox" class="checkboxgross" id="checkbox32" value="1">
        </div></td>
      <td colspan="4"><font size="4">Steuerung oder Programmfehler</font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox4" type="checkbox" class="checkboxgross" id="checkbox42" value="1">
        </div></td>
      <td colspan="4"><font size="4">Zentrierung der Formaufnahme defekt (asymentrische 
        Verschwei&szlig;ung)</font></td>
    </tr>
    <tr bgcolor="#FF9900"> 
      <td> <div align="center"> 
          <input name="checkbox5" type="checkbox" class="checkboxgross" id="checkbox52" value="1">
        </div></td>
      <td colspan="3"><font size="4">Temperierung (K&uuml;hlger&auml;te/ R&uuml;ckk&uuml;hlger&auml;t) 
        defekt</font></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox6" type="checkbox" class="checkboxgross" id="checkbox62" value="checkbox">
        </div></td>
      <td colspan="3">&nbsp;</td>
      <td rowspan="6"> <div align="center"> 
          <input name="stoerungspeichern" type="submit" class="buttongross" id="stoerungspeichern2" value="St&ouml;rung Speichern">
        </div></td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td bgcolor="#FF9900"> <div align="center"> 
          <input name="checkbox7" type="checkbox" class="checkboxgross" id="checkbox72" value="1">
        </div></td>
      <td colspan="3" bgcolor="#FF9900">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox8" type="checkbox" class="checkboxgross" id="checkbox82" value="1">
        </div></td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr bgcolor="#6699FF"> 
      <td bgcolor="#FF9900"> <div align="center"> 
          <input name="checkbox9" type="checkbox" class="checkboxgross" id="checkbox92" value="1">
        </div></td>
      <td colspan="3" bgcolor="#FF9900">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td> <div align="center"> 
          <input name="checkbox10" type="checkbox" class="checkboxgross" id="checkbox102" value="1">
        </div></td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td bgcolor="#FF9900"> <div align="center"> 
          <input name="checkbox11" type="checkbox" class="checkboxgross" id="checkbox112" value="1">
        </div></td>
      <td colspan="3" bgcolor="#FF9900"><font size="4"> 
        <input name="imageField" type="image" src="picture/error.gif" align="middle" width="16" height="16" border="0">
        Problem nicht aufgef&uuml;hrt. Schwei&szlig;en nicht m&ouml;glich </font> 
        <font size="4"> 
        <input name="url_user" type="hidden" id="url_user" value="<?php echo $url_user; ?>">
        <input name="url_id_fhm" type="hidden" id="url_id_fhm" value="<?php echo $url_fhm_id ?>">
        <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="6">
        </font></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php } /* ende Störung anlegen */
?>

 <?php if ($url_user>0 and $action=="auftragbearbeiten"){?>
  <?php $oktxt="Zum Speichern Artikel auswählen und R&uuml;ckmelden dr&uuml;cken.";?>
</p>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="224" bgcolor="#FFFFFF"><a href="pp.<?php echo $location ?>.php" target="_self"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66" border="0" align="absmiddle">Startseite</a></td>
      <td width="224" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="270" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="296" bgcolor="#CCCCFF"><img src="picture/icon_gr_tarifkonditionen.gif" width="65" height="75" align="absmiddle"> 
        <strong>Auftrag bearbeiten</strong></td>
      <td width="230" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>R&uuml;ckmeldung &uuml;ber </td>
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
        <input name="url_id_fhmea" type="hidden" id="url_id_fhmea" value="6"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><hr></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5"><img src="picture/b_calendar.png" width="16" height="16"> 
        Liste der Materialummern</td>
    </tr></td></tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php } /* ende Auftrag bearbeiten  */

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
        Kalibrierung der Misch</strong></td>
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
        Betankung</strong></td>
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
        Sch&auml;umprogramm- &Auml;nderungen</strong></td>
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
        Temperierung</strong></td>
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
