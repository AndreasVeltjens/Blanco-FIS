<?php require_once('Connections/qsdatenbank.php'); ?><?php 
$la = "la441";

/* Reparatur von Geräten wird verwendet
in LA4 Retourenabwicklung und LA1 Fertigmeldung */

/* wenn aus LA14 eine wiederholungsprüfung gestartet wird */
if (isset($hurl_wiederholung_id) && ($hurl_wiederholung_id>0)) {$url_sn=$hurl_wiederholung_id;}


function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
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
$query_rstservice = "SELECT fhm.id_fhm, fhm.name, fhm.artikelid, fhm.sn, fhm.lokz FROM fhm WHERE fhm.artikelid = '$url_artikelid' and fhm.sn='$url_sn' ";
$rstservice = mysql_query($query_rstservice, $qsdatenbank) or die(mysql_error());
$row_rstservice = mysql_fetch_assoc($rstservice);
$totalRows_rstservice = mysql_num_rows($rstservice);



if ((isset($HTTP_POST_VARS["save"])) && $HTTP_POST_VARS["version"]<>"") {
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstservice = "SELECT fhm.id_fhm, fhm.name, fhm.artikelid, fhm.sn, fhm.lokz FROM fhm WHERE fhm.artikelid = $HTTP_POST_VARS[hurl_artikelid] and fhm.sn=$HTTP_POST_VARS[sn] ";
$rstservice = mysql_query($query_rstservice, $qsdatenbank) or die(mysql_error());
$row_rstservice = mysql_fetch_assoc($rstservice);
$totalRows_rstservice = mysql_num_rows($rstservice);

/* wenn Austauschgerät angelegt wird */
		if (($HTTP_POST_VARS['sn']=="999999") or ($HTTP_POST_VARS['austauschgeraet']==1)){
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rstfid = "SELECT fertigungsmeldungen.fsn FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid = '$url_artikelid' AND fertigungsmeldungen.fsn< 100000 ORDER BY fertigungsmeldungen.fsn desc";
		$rstfid = mysql_query($query_rstfid, $qsdatenbank) or die(mysql_error());
		$row_rstfid = mysql_fetch_assoc($rstfid);
		$totalRows_rstfid = mysql_num_rows($rstfid);
		
		
		$note="Ersatzger&auml;t für FM ".$HTTP_POST_VARS['hurl_fmid']." SN-Eingabe bei Aufnahme war SN".$HTTP_POST_VARS['sn'];
		$HTTP_POST_VARS['sn']=$row_rstfid['fsn']+1;
		
		/* Ändern der SN in der Fehlermeldung */
		 $updateSQL = sprintf("UPDATE fehlermeldungen SET fmsn=%s, fm4notes=%s, fm5notes=%s WHERE fmid=%s",
							   GetSQLValueString($HTTP_POST_VARS['sn'],"int"),                    
							   GetSQLValueString($note." - ".$HTTP_POST_VARS['notes'], "text"),
							   GetSQLValueString($note." - ".$HTTP_POST_VARS['notes'], "text"),
							   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'],"int"));
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
		
		}/* ende Ersatzgerät =1 */
/* Neue SN vergeben, da SN zur Fehleraufnahme nicht bekannt war oder 
              nicht */
		if ($HTTP_POST_VARS['austauschgeraet']==2){
		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rstfid = "SELECT fertigungsmeldungen.fsn FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid = '$HTTP_POST_VARS[select2]' AND fertigungsmeldungen.fsn=$HTTP_POST_VARS[textfield2] ORDER BY fertigungsmeldungen.fsn desc";
		$rstfid = mysql_query($query_rstfid, $qsdatenbank) or die(mysql_error());
		$row_rstfid = mysql_fetch_assoc($rstfid);
		$totalRows_rstfid = mysql_num_rows($rstfid);
		
		
		$note="Ersatzger&auml;t für FM ".$HTTP_POST_VARS['hurl_fmid']." SN-Eingabe bei Aufnahme war SN".$HTTP_POST_VARS['sn'];
		$HTTP_POST_VARS['sn']=$row_rstfid['fsn'];
		
		/* Ändern der SN in der Fehlermeldung */
		 $updateSQL = sprintf("UPDATE fehlermeldungen SET fmsn=%s, fm4notes=%s, fm5notes=%s WHERE fmid=%s",
							   GetSQLValueString($HTTP_POST_VARS['sn'],"int"),                    
							   GetSQLValueString($note." - ".$HTTP_POST_VARS['notes'], "text"),
							   GetSQLValueString($note." - ".$HTTP_POST_VARS['notes'], "text"),
							   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'],"int"));
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
		  
		  /* Ändern der Bemerkung in der Fertigungsmeldung  */
		 $updateSQL = sprintf("UPDATE fehlermeldungen SET  fnotes=%s WHERE fid=%s",
							   GetSQLValueString($note." - ".$HTTP_POST_VARS['notes'], "text"),
							   GetSQLValueString($row_rstfid['fid'],"int"));
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

		}/* ende Ersatzgerät mit vorhandener SN=2 */
		

		

if ($HTTP_POST_VARS['sn2']==""){$HTTP_POST_VARS['sn2']="none";}
if ($HTTP_POST_VARS['sn3']==""){$HTTP_POST_VARS['sn3']="none";}
if ($HTTP_POST_VARS['sn4']==""){$HTTP_POST_VARS['sn4']="none";}

  $insertSQL = sprintf("INSERT INTO fertigungsmeldungen (fartikelid, fuser, fdatum, freigabeuser, freigabedatum, verkauftuser, verkauftam, versendetuser, versendetam, history, fsn, version, fsn1, fsn2, fsn3, fsn4, pgeraet, signatur, farbeitsplatz, fsonder, ffrei, fnotes,fnotes1,frfid) VALUES (%s, %s,%s, %s,%s,%s, %s,%s, %s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_artikelid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       "now()",
					   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       "now()",
					   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       "now()",
					   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       "now()",
					   GetSQLValueString("Retourenabwicklung FM ".$HTTP_POST_VARS['hurl_fmid']." - ".$note, "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['version'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn4'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['pgeraet'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['signatur'], "text"),
					   GetSQLValueString("611405", "text"),					   
                       GetSQLValueString(isset($HTTP_POST_VARS['sonder']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['frei']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($note.$HTTP_POST_VARS['notes'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['notes1'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['frfid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

if (($totalRows_rstservice > 0)) { /* FHM Meldung wenn Servicegerät in Reparaturprüfung */

if (($HTTP_POST_VARS['frei'])==1){ /* wenn Prüfung i.O., dann Meldung anlgegen */


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst22 = "SELECT * FROM fhm_ereignis WHERE id_fhm = $row_rstservice[id_fhm] and beschreibung like '%$HTTP_POST_VARS[hurl_fmid]%' ";
$rst22 = mysql_query($query_rst22, $qsdatenbank) or die(mysql_error());
$row_rst22 = mysql_fetch_assoc($rst22);
$totalRows_rst22= mysql_num_rows($rst22);


     $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, datum, ereigniszeitstempel, user, beschreibung, id_ref_fhme ) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($row_rstservice['id_fhm'], "int"),
					   GetSQLValueString("12", "int"),/* Störungs ID aus fhm_ereignisart */
                       "now()",
					   "now()",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString("VDE i.O. elektrische Geräteüberprüfung aufgrund Retourenabwicklung $HTTP_POST_VARS[hurl_fmid]", "text"),
					   GetSQLValueString($row_rst22['id_fhme'], "int"));
}
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
   
   }/* ende of FHM Meldung */

		/* wenn aus LA14 eine wiederholungsprüfung gestartet wird */
		if ($HTTP_POST_VARS['hurl_fmid']<>""){
			$insertGoTo = "la44.php?url_user=".$HTTP_POST_VARS["hurl_user"]."&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&url_vde_ok=".$HTTP_POST_VARS["frei"];
		}else{
		$insertGoTo = "la11.php?url_user=".$HTTP_POST_VARS["hurl_user"]."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
	
		  	}
  header(sprintf("Location: %s", $insertGoTo));
}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert=1  AND artikeldaten.artikelid='$url_artikelid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM artikelversionen WHERE artikelversionen.artikelid='$url_artikelid' ORDER BY artikelversionen.version";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
/* wenn aus LA14 eine wiederholungsprüfung gestartet wird */
			if ($HTTP_POST_VARS['hurl_fmid']<>""){
				$insertGoTo = "la44.php?url_user=".$HTTP_POST_VARS["hurl_user"]."&url_fmid=".$HTTP_POST_VARS["hurl_fmid"]."&url_vde_ok=".$HTTP_POST_VARS["frei"];
			}else{
			$insertGoTo = "la11.php?url_user=".$HTTP_POST_VARS["hurl_user"]."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
			}
	  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
	}else{
	$errtxt = "Aktion wählen. Bitte erneut versuchen.";
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
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
      <td width="152">Aktionen W&auml;hlen:</td>
      <td width="307"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"></td>
      <td width="521"> <div align="right"> 
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>Sie bearbeiten:</td>
      <td> 
        <?php if ($url_fmid<>""){?>
        <strong>FM: <?php echo $url_fmid ?> </strong> 
        <?php }else{?>
        <strong>Nachpr&uuml;fung SN:<?php echo $url_sn ?></strong> 
        <?php }?>
      </td>
      <td><div align="right">&Uuml;berpr&uuml;fung nach einer Instandsetzung</div></td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Ger&auml;tetyp:</td>
      <td><strong><?php echo $row_rst2['Bezeichnung']; ?> 
        <input name="anzahlp" type="hidden" id="anzahlp" value="<?php echo $row_rst2['anzahlp']; ?>">
        </strong></td>
      <td><div align="right">gem&auml;&szlig; VDE 0701</div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $url_fmid ?>"></td>
    </tr>
    <tr> 
      <td>Seriennummer:</td>
      <td><p><strong> 
          <input name="sn" type="hidden" id="sn7" value="<?php echo $url_sn; ?>">
          <?php echo $url_sn; ?></strong> 
          <?php if  ($url_sn==999999) {
		
		echo " Beim Speichern wird eine neue SN vergeben.";
		}
		
		?>
        </p>
        <p>&nbsp; </p></td>
      <td bgcolor="#CCCCCC"> <table width="503">
          <tr> 
            <td><label> 
              <input name="austauschgeraet" type="radio" value="0" checked>
              Nur Reparatur</label></td>
          </tr>
          <tr> 
            <td><label> 
              <input type="radio" name="austauschgeraet" value="1">
              Neue SN vergeben, da SN zur Fehleraufnahme nicht bekannt war oder 
              nicht mehr identifizierbar (z.Bsp. SN 999999)</label></td>
          </tr>
          <tr> 
            <td><p> 
                <label> 
                <input type="radio" name="austauschgeraet" value="2">
                Ersatzgerät mit bestehender SN, ist bereits im FIS angelegt worden</label>
                <br>
                SN: 
                <input name="textfield2" type="text" size="10" maxlength="6">
                <label></label>
              </p></td>
          </tr>
          <tr> 
            <td><label> 
              <input type="radio" name="austauschgeraet" value="4">
              Ersatzger&auml;t wird gerade gebaut und ben&ouml;tigt eine neue 
              SN, da alte SN wird automatisch verschrottet.</label></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td><font color="#990000"><strong>Reparatur auf Version:</strong></font></td>
      <td colspan="2"><select name="version" id="version">
          <option value="" > bitte Version zuordnen</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst5['version']?>"<?php if (!(strcmp($row_rst5['version'], $row_rst2['version']))) {echo "SELECTED";} ?>><?php echo $row_rst5['version']?>- 
          <?php echo substr($row_rst5['beschreibung'],0,100);?></option>
          <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
        </select></td>
    </tr>
  </table>
  <hr> Bauteildaten
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <?php if ($row_rst2['anzahlp']>=1){ ?>
    <tr> 
      <td width="176">Serienummer Heizung</td>
      <td width="527"> <input name="sn1" type="text" id="sn1">
        <font size="1">Eingabeformat: 2501BG , 08/11 oder 4021 2011/07/01</font></td>
      <td width="277" rowspan="4"><div align="right"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" alt="EAN/UPC Nummer: <?php echo $row_rst2['ean13'] ?>" width="180" height="140" border="0"></div></td>
    </tr>
    <?php }?>
    <?php if ($row_rst2['anzahlp']>=2){ ?>
    <tr> 
      <td>Serienummer Regler</td>
      <td> <input name="sn2" type="text" id="sn2">
        <font size="1">Eingabeformat: 110304/77 oder 106823</font> </td>
    </tr>
    <?php }?>
    <?php if ($row_rst2['anzahlp']>=3){ ?>
    <tr> 
      <td>Serienummer L&uuml;fter</td>
      <td> <input name="sn3" type="text" id="sn3">
        <font size="1">Eingabeformat: 08/11</font></td>
    </tr>
    <?php }?>
    <?php if ($row_rst2['anzahlp']>=4){ ?>
    <tr> 
      <td>sonstiges</td>
      <td><input name="sn4" type="text" id="sn4"></td>
    </tr>
    <?php }?>
  </table>
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
    <tr> 
      <td width="175">VDE-Pr&uuml;fung i.O.</td>
      <td width="765"><input name="frei" type="checkbox" id="frei" value="1"> 
        <img src="picture/st1.gif" width="15" height="15"></td>
      <td width="40">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td> automatisch 
        <script>
                    <!--
                    document.write('<a title="Kalender" href="javascript:openCalendar(\'lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci\', \'insertForm\', \'datum\', \'date\')"><img class="calendar" src="./picture/b_calendar.png" alt="Kalender"/></a>');
                    //-->
                    </script></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Sonderfreigabe erforderlich</td>
      <td><input name="sonder" type="checkbox" id="sonder" value="1"> <img src="picture/st2.gif" width="15" height="15"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><p>Grund:<br>
          &Auml;nderungen: </p>
        <p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <td><textarea name="notes" cols="80" rows="7" id="notes"><?php echo $url_fmnotes; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><p>Funktionstest und Messwerte:<br>
        </p>
        <p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <?php 
		
		$url_fmnotes1="Schutzleiterwiderstand\n-GW RPE <=0,300 Ohm   RPE= ...... Ohm\n-RPE max ............  \n-Pruefstrom ........ mA";
		$url_fmnotes1=$url_fmnotes1."\nIsolationswiderstand\n-GW RISO>= 1,0 MOhm   RISO-1LN-PE .......... > 100,0 MOhm\n-RISO-min .......... > 100,0 MOhm\n-Pruefstrom ........ mA";
		$url_fmnotes1=$url_fmnotes1."\nHochspannungspruefung [1,25 kV][mA] SOLL<=5,0 mA   \n";
				
		?>
      <td><textarea name="notes1" cols="80" rows="10" id="notes"><?php echo $url_fmnotes1; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>verwendete Pr&uuml;fger&auml;te:</td>
      <td colspan="2"><select name="pgeraet" id="select">
          <option value="Kompaktpr&uuml;fger&auml;t BENNING">Kompaktpr&uuml;fger&auml;t 
         BENNING</option>
          <option value="-------------------------">--------------------------</option>
         <option value="Kompaktpr&uuml;fger&auml;t ATS X8">Kompaktpr&uuml;fger&auml;t 
         ATS X8</option>
          <option value="-------------------------">---------------------------</option>
          <option value="Sichtpr&uuml;fung gem&auml;&szlig; Pr&uuml;fplan">Sichtpr&uuml;fung 
          gem&auml;&szlig; Pr&uuml;fplan</option>
        </select></td>
    </tr>
    <tr> 
      <td>Signatur</td>
      <td><img src="picture/s_process.png" width="16" height="16"> <input name="signatur" type="hidden" id="signatur" value="<?php echo md5(time()); ?>"> 
        &nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>RFID</td>
      <td><img src="picture/smextlink.gif" width="16" height="9"> <input name="frfid" type="text" id="frfid"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
  </table>
  <p>
    <input type="hidden" name="MM_insert" value="form2">
  </p>
</form>

<?php if ($totalRows_rstservice > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="237"><strong>M&ouml;gliche Serviceger&auml;te:</strong></td>
    <td width="96">Status </td>
    <td width="210">Seriennummer</td>
    <td width="187">Bemerkungen </td>
  </tr>
  <?php do { ?>
  <form name="form2" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td bgcolor="#EEEEEE" nowrap="nowrap">&nbsp; 
        <?php echo $row_rstservice['name']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rstservice['lokz'],0))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
        - <img src="picture/<?php if (!(strcmp($row_rst5['fm3'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rstservice['sn']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="fhm121.php?url_user=<?php echo $row_rst1['id']; ?>&url_id_fhm=<?php echo $row_rstservice['id_fhm']; ?>&goback=la40" target="_blank">weitere 
        Informationen anzeigen</a></td>
    </tr>
  </form>
  <?php } while ($row_rstservice = mysql_fetch_assoc($rstservice)); ?>
</table>
<p> insgesamt <?php echo $totalRows_rstservice ?> Serviceger&auml;te gefunden.<br>
  <font color="#009900"><strong>Beim Anlegen einer i.O.-VDE-Pr&uuml;fung wird 
  automatisch eine Meldung <br>
  &quot;i.O. elektrische Ger&auml;te&uuml;berpr&uuml;fung aufgrund Retourenabwicklung&quot; 
  angelegt.</strong></font></p>
<p>&nbsp;</p>
<?php } // Show if recordset not empty ?>
   <?php if ($totalRows_rstservice == 0) { // Show if recordset empty ?>
  
<p><font color="#FF0000">Das Ger&auml;t ist kein Serviceger&auml;t..</font></p>
  <?php } // Show if recordset empty ?>
<?php
mysql_free_result($rst1);

mysql_free_result($rst2);

?></p>

  <?php 
  
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst200 = "SELECT * FROM fhmprueschritt, link_artikeldaten_pruef WHERE fhmprueschritt.id_p=link_artikeldaten_pruef.pruef_id and link_artikeldaten_pruef.artikelid='$row_rst2[artikelid]' ORDER BY fhmprueschritt.gruppe, fhmprueschritt.reihenfolge";
$rst200 = mysql_query($query_rst200, $qsdatenbank) or die(mysql_error());
$row_rst200 = mysql_fetch_assoc($rst200);
$totalRows_rst200 = mysql_num_rows($rst200);
  
  
  ?>
  <?php if ($totalRows_rst200 > 0) { // Show if recordset not empty ?>
  Liste der Pr&uuml;fungen<br>

<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="242"><strong><em>Bezeichnung</em></strong></td>
    <td width="213"><strong><em>Grenzwert</em></strong></td>
    <td width="114"><strong><em>Einheit</em></strong></td>
    <td width="98"><strong><em>Art</em></strong></td>
    <td width="63">&nbsp;</td>
  </tr>
  <?php 
  $gruppe="";
  do { ?>
  <?php   if ($row_rst200['gruppe']!=$gruppe){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td colspan="8" nowrap="nowrap"    bgcolor="#EEEEEE"><em><br>
      <img src="picture/s_tbl.png" width="16" height="16"> <?php echo $row_rst200['gruppe']; ?></em></td>
  </tr>
  <?php 	}?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['name']; ?></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['sollwert']; ?> 
    </td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['ergebnis']; ?></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst200['istwert']; ?></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"> 
        <input name="hurl_user2" type="hidden" id="hurl_user23" value="<?php echo $url_user; ?>">
        <input name="hurl_id_p" type="hidden" id="hurl_id_p" value="<?php echo $row_rst200['id_p']; ?>">
        <input name="hurl_id_ps" type="hidden" id="hurl_id_ps" value="<?php echo $row_rst200['id_ps']; ?>">
      </div></td>
  </tr>
  <?php
    $gruppe=$row_rst200['gruppe'];
   } while ($row_rst200 = mysql_fetch_assoc($rst200)); ?>
</table>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rst200 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Daten vorhanden. Pr&uuml;fplanung f&uuml;r den 
  Artikel nicht notwendig.</font></p>
<?php } // Show if recordset empty ?>
<input type="hidden" name="MM_insert2" value="form2">
<input type="hidden" name="MM_update" value="form2">
<?php include("footer.tpl.php"); ?>
