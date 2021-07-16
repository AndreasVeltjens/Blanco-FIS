<?php require_once('Connections/qsdatenbank.php'); ?>
<?php   $la = "la40";




if ($HTTP_POST_VARS["reklamenge"]==""){$HTTP_POST_VARS["reklamenge"]="1 St&uuml;ck";}
if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "la4.php?url_user=".$url_user;
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}
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

/* Zuordnung der Variablen erweiterte Suche */
if(isset($name)){
$HTTP_POST_VARS[name]=$name;
$HTTP_POST_VARS[select]=$select;
$HTTP_POST_VARS[fmfd]=$fmfd;
}/* erweiterte Suche */


if ($HTTP_POST_VARS[name]==""){$HTTP_POST_VARS[name]="0";}
if ($HTTP_POST_VARS[select]==""){$HTTP_POST_VARS[select]=0.1;}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstservice = "SELECT fhm.id_fhm, fhm.name, fhm.artikelid, fhm.sn, fhm.lokz FROM fhm WHERE fhm.artikelid = $HTTP_POST_VARS[select] and fhm.sn=$HTTP_POST_VARS[name] ";
$rstservice = mysql_query($query_rstservice, $qsdatenbank) or die(mysql_error());
$row_rstservice = mysql_fetch_assoc($rstservice);
$totalRows_rstservice = mysql_num_rows($rstservice);


if ((isset($HTTP_POST_VARS["erweitert"]))) {
  $insertGoTo = "la401.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la41.php?url_user=".$row_rst1['id']."&url_fmid=".$HTTP_POST_VARS["hurl_fmid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["save"])) or (isset($HTTP_POST_VARS["save2"]))) {
$fm0=1;
 if (isset($HTTP_POST_VARS["save"])){ $fm0=0;} else{ $fm0=1;}

  $insertSQL = sprintf("INSERT INTO fehlermeldungen (materialnummer,fm0,fmart,reklamenge,fmsn,fmsn2, fmartikelid, fmfd,fm1datum, fmuser) VALUES (%s, %s, %s,%s,%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['materialnummer'], "text"),
					   $fm0,
					   GetSQLValueString($HTTP_POST_VARS['fmart'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['reklamenge'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['name'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['select'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['fmfd'], "date"),
					   "now()",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
   $lastmea =mysql_insert_id();
   
if ($totalRows_rstservice > 0) { /* FHM Meldung wenn Servicegerät in Aufnahme */
     $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, datum, ereigniszeitstempel, user, beschreibung) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($row_rstservice['id_fhm'], "int"),
					   GetSQLValueString("6", "int"),/* Störungs ID aus fhm_ereignisart */
                       "now()",
					    "now()",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString("Retourenabwicklung gestartet mit FM: $lastmea", "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
   
   }/* ende of FHM Meldung */
   
   
if ((isset($HTTP_POST_VARS["save"]))) {
  $insertGoTo = "la402.php?url_fmid=$lastmea&goforward=la4.php&comefrom=la40";
  }else{
    $insertGoTo = "la402.php?url_fmid=$lastmea";
}
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmartikelid, fehlermeldungen.fmuser, fehlermeldungen.fm1datum, fehlermeldungen.fmsn, fehlermeldungen.fm3, fehlermeldungen.fm6, fehlermeldungen.fm1notes FROM fehlermeldungen WHERE fehlermeldungen.fmartikelid=$HTTP_POST_VARS[select] and fehlermeldungen.fmsn=$HTTP_POST_VARS[name] ORDER BY fehlermeldungen.fmdatum desc";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT * FROM `user`";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fm0=0 and fehlermeldungen.lokz=0";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);


$HTTP_POST_VARS[fmfd]=$row_rst4['fdatum'];

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form action="<?php echo $editFormAction; ?>" name="form2" method="POST">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?> 
        - Schritt 1 von 3</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="118">Aktionen W&auml;hlen:</td>
      <td width="333"> <img src="picture/b_drop.png" width="16" height="16" align="absmiddle"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck zur &Uuml;bersicht"> </td>
      <td width="279"> <div align="right"></div></td>
    </tr>
  </table>
  
  

  
  

	  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
	
<?php include("la4.tbl_layout.php"); ?>

<?php } // Show if recordset not empty ?>

<form action="<?php echo $editFormAction; ?>" name="form3" method="POST">
  <p>&nbsp;</p>
  <table width="830" border="0" cellspacing="0" cellpadding="0">
    <tr bgcolor="#CCCCFF"> 
      <td colspan="2"> <?php if ($HTTP_POST_VARS[name]==""){$HTTP_POST_VARS[name]=0;}
if ($HTTP_POST_VARS[select]==""){$HTTP_POST_VARS[select]=0;}
if (isset($HTTP_POST_VARS[ubernehmen])){$HTTP_POST_VARS[fmfd]=$HTTP_POST_VARS[fmfd1];
$HTTP_POST_VARS[name]=$HTTP_POST_VARS[fsn];}


$suchtxt=" fertigungsmeldungen.fsn=$HTTP_POST_VARS[name] ";
if ($HTTP_POST_VARS['bauteil']<>""){$suchtxt=" fertigungsmeldungen.fsn1 = '$HTTP_POST_VARS[bauteil]' or fertigungsmeldungen.fsn2 = '$HTTP_POST_VARS[bauteil]' or fertigungsmeldungen.fsn3 = '$HTTP_POST_VARS[bauteil]'";}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.version,  fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes, fertigungsmeldungen.fsn1, fertigungsmeldungen.fsn2, fertigungsmeldungen.fsn3, fertigungsmeldungen.fsn4 FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid=$HTTP_POST_VARS[select] and ($suchtxt) ORDER BY fertigungsmeldungen.fdatum asc";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

if ($HTTP_POST_VARS['suchbegriff']<>"") {$suchtxtmat= " WHERE artikeldaten.Bezeichnung like '%$HTTP_POST_VARS[suchbegriff]%' or artikeldaten.Nummer like '%$HTTP_POST_VARS[suchbegriff]%' ";}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten $suchtxtmat ORDER BY artikeldaten.Kontierung, artikeldaten.Bezeichnung";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

?>
        <em><img src="picture/add.gif" width="15" height="14"> Neue Retoure anlegen</em></td>
      <td width="257"> <p align="right"><img src="picture/b_tblops.png" width="16" height="16" align="absmiddle"> 
          <input name="save2" type="submit" id="save22" value="Speichern + weiter">
        </p></td>
    </tr>
    <tr bgcolor="#CCCCFF"></p>
    
<tr bgcolor="#CCCCFF">
      <td>Suchbegriff</td>
      <td><input name="suchbegriff" type="text" id="suchbegriff" value="<?php echo $HTTP_POST_VARS['suchbegriff']; ?>">
        <input name="liste" type="submit" id="save2" value="Liste einschr&auml;nken"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Materialnummer</td>
      <?php
do {  
?>
      <?php if (!(strcmp($row_rst2['artikelid'], $HTTP_POST_VARS['select']))) {$HTTP_POST_VARS[materialnummer]=$row_rst2['Nummer'];} ?>
      <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
      <td width="308"><input name="materialnummer" type="text" id="materialnummer" value="<?php echo $HTTP_POST_VARS[materialnummer]; ?>" size="20" maxlength="255">
        <img src="picture/help.gif" alt="SAP-Materialummer, sofern bekannt, Wird bei Ger&auml;tetypauswahl automatisch eingef&uuml;gt." width="17" height="17"> 
      </td>
      <td> <p align="right"> 
          <input name="save" type="submit" id="save" value="Retoure nur anlegen">
        </p>
        <div align="right"></div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Fehlermeldungstyp</td>
      <td> <select name="fmart" id="fmart">
          <option value="1" <?php if (!(strcmp(1, $url_fmart))) {echo "SELECTED";} ?>>Q1 
          - Debitorenreklamation</option>
          <option value="2" <?php if (!(strcmp(2, $url_fmart))) {echo "SELECTED";} ?>>Q2 
          - Kreditorenreklamation</option>
          <option value="3" <?php if (!(strcmp(3, $url_fmart))) {echo "SELECTED";} ?>>Q3 
          - Eigenfertigungsreklamation</option>
        </select> </td>
      <td> <div align="right">Q1, Q2, Q3</div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Anzahl fehlerhafte Menge</td>
      <td><input name="reklamenge" type="text" id="reklamenge" value="<?php echo $HTTP_POST_VARS[reklamenge]; ?>" size="10" maxlength="255">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td width="165">Ger&auml;te Typ</td>
      <td colspan="2"><select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst2['artikelid']?>"<?php if (!(strcmp($row_rst2['artikelid'], $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>><?php echo $row_rst2['Bezeichnung']."- ".$row_rst2['Nummer']." - ".$row_rst2['Kontierung']?></option>
          <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
        </select> <div align="right"></div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Seriennummer/Charge</td>
      <td><input name="name" type="text" id="name" value="<?php echo $HTTP_POST_VARS[name]; ?>" size="15" maxlength="255">
        <img src="picture/windows7-arrow.jpg" width="30" height="13"> Bauteil-ID 
        <input name="bauteil" type="text" id="bauteil" value="<?php echo $HTTP_POST_VARS[bauteil]; ?>" size="15" maxlength="255"> 
        <img src="picture/help.gif" alt="Wenn seriennummer vom typenschild nicht erkennbar ist, dann Bauteil-nummer eingeben und auf fertigungsdaten suchen klicken. bei Umluftheizungsmodellen wird zun&auml;chst nach der Heizungsmodulnummer gesucht." width="17" height="17"></td>
      <td><div align="right">wenn unbekannt, dann 999999 eingeben</div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Fertigungsdatum</td>
      <td> <input name="fmfd" type="text" id="fmfd" value="<?php echo $HTTP_POST_VARS['fmfd']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'fmfd\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td><div align="right"><img src="picture/iconYellowStar_25x25.gif" width="25" height="25"> 
          <input name="erweitert" type="submit" id="suche24" value="erweiterte Suche">
          <br>
          <br>
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
      <td><div align="right"><img src="picture/iconTealStar_25x25.gif" width="25" height="25"> 
          <input name="suche" type="submit" id="suche" value="Fertigungsdaten suchen">
        </div></td>
    </tr>
  </table>
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
<p> insgesamt <?php echo $totalRows_rstservice ?> Serviceger&auml;te gefunden. <br>
  <font color="#009900"></font></p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="298"><div align="center"><font color="#009900"><strong><img src="picture/logo.gif" width="183" height="57"></strong></font></div></td>
    <td width="452"><font color="#009900"><strong>Beim Anlegen einer Retoureabwicklung 
      wird automtisch eine St&ouml;rung des Serviceger&auml;tes erfasst.<br>
      </strong></font><strong><font color="#009900">Die R&uuml;ckmeldung der St&ouml;rung 
      erfolgt &uuml;ber VDE-Pr&uuml;fung der Retourenabwicklung (Modul la441)</font></strong></td>
  </tr>
  <tr>
    <td><div align="center">Achtung.: Eigentum</div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php } // Show if recordset not empty ?>
   <?php if ($totalRows_rstservice == 0) { // Show if recordset empty ?>
  
<p><font color="#FF0000">keine m&ouml;glichen Serviceger&auml;te gefunden.</font></p>
  <?php } // Show if recordset empty ?>
  <br>
  <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine m&ouml;glichen Fertigungsdaten zur Auswahl gefunden.</font></p>
  <?php } // Show if recordset empty ?>

  <br>
  <?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="100"><strong>M&ouml;gliche Fertigungsdaten:</strong></td>
    <td width="50">Status </td>
    <td width="50">User</td>
    <td width="50">Version </td>
    <td width="100">Bauteile</td>
    <td width="100">Bemerkungen </td>
    <td width="80">Anzeigen</td>
  </tr>
 <?php  $row_count=0;?>
  <?php do { 
  $row_count=$row_count+1; /* zeige nur das ältste Fertigungsdatum zur Übernahme in den FMID Datensatz */
  
  /* suche redrograde Baugruppen verwendung */
 			do {  
					if (!(strcmp($row_rst4['fartikelid'], $row_rst2['artikelid']))) {$stuli= $row_rst2['stuli'];	}
				} while ($row_rst2 = mysql_fetch_assoc($rst2));
 		 $rows = mysql_num_rows($rst2);
  		if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  		}
		
		/* suche redrograde Baugruppen Name */
 			do {  
					if (!(strcmp($stuli, $row_rst2['artikelid']))) {$bauteilname= $row_rst2['Bezeichnung'];	}
				} while ($row_rst2 = mysql_fetch_assoc($rst2));
 		 $rows = mysql_num_rows($rst2);
  		if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  		}
  
  
  
  ?>
  <form name="form2" method="post" action="">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php if ($row_count==1){?><input name="ubernehmen" type="submit" id="ubernehmen" value="&uuml;bernehmen"> <br><?php }?>
        <?php echo $row_rst4['fdatum']; ?> <input name="fmfd1" type="hidden" id="fmfd1" value="<?php echo $row_rst4['fdatum']; ?>"> 
        <input name="name" type="hidden" id="name" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
        <input name="select" type="hidden" id="select" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
        <input name="debitor" type="hidden" id="debitor2" value="<?php echo $HTTP_POST_VARS['debitor']?>">
        <input name="fsn" type="hidden" id="fsn" value="<?php echo $row_rst4['fsn']; ?>"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst4['ffrei'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
        - <img src="picture/<?php if (!(strcmp($row_rst4['fsonder'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rstuser['id'], $row_rst4['fuser']))) {echo $row_rstuser['name'];}?> 
        <?php
} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  $rows = mysql_num_rows($rstuser);
  if($rows > 0) {
      mysql_data_seek($rstuser, 0);
	  $row_rst9 = mysql_fetch_assoc($rstuser);
  }
?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst4['version']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">Heizung 
        <?php echo $row_rst4['fsn1']; ?>-<br>
        Regler <?php echo $row_rst4['fsn2']; ?>-<?php echo $row_rst4['fsn3']; ?>- <?php echo $row_rst4['fsn4']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php echo wordwrap(nl2br($row_rst4['fnotes']),40,"<br>",255); ?>--<?php echo $stuli ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst4['fartikelid']; ?>&url_fmid=<?php echo $row_rst5['fmid']; ?>" target="_blank"><img src="picture/primary.gif" width="21" height="17" border="0" align="absmiddle"></a><a href="la14.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst4['fartikelid']; ?>&url_fid=<?php echo $row_rst4['fid']; ?>" target="_blank">Fertigungsdaten</a></td>
    </tr>
	
	<?php
	
	
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst44 = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.fartikelid, fertigungsmeldungen.version,  fertigungsmeldungen.fuser, fertigungsmeldungen.fdatum, fertigungsmeldungen.fsn, fertigungsmeldungen.ffrei, fertigungsmeldungen.fsonder, fertigungsmeldungen.fnotes, fertigungsmeldungen.fsn1, fertigungsmeldungen.fsn2, fertigungsmeldungen.fsn3, fertigungsmeldungen.fsn4 FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='$stuli' and fertigungsmeldungen.fsn='$row_rst4[fsn1]' ORDER BY fertigungsmeldungen.fdatum asc";
$rst44 = mysql_query($query_rst44, $qsdatenbank) or die(mysql_error());
$row_rst44 = mysql_fetch_assoc($rst44);
$totalRows_rst44 = mysql_num_rows($rst44);
	if (($totalRows_rst44>0)&& ($stuli>0)){
	do{?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/add.gif" width="15" height="14"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $bauteilname; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst44['version']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">Platine<?php echo $row_rst44['fsn1']; ?>-<br>
        Heizung<?php echo $row_rst44['fsn2']; ?>-<?php echo $row_rst44['fsn3']; ?>- 
        <?php echo $row_rst44['fsn4']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <?php echo wordwrap(nl2br($row_rst44['fnotes']),40,"<br>",255); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst44['fartikelid']; ?>&url_fmid=<?php echo $row_rst55['fmid']; ?>" target="_blank"><img src="picture/primary.gif" width="21" height="17" border="0" align="absmiddle"></a><a href="la14.php?url_user=<?php echo $row_rst1['id']; ?>&url_artikelid=<?php echo $row_rst4['fartikelid']; ?>&url_fid=<?php echo $row_rst4['fid']; ?>" target="_blank">Fertigungsdaten</a></td>
    </tr>
	 <?php } while ($row_rst44 = mysql_fetch_assoc($rst44)); ?>
<?php 	} // ende totalrows?>
	
	
  </form>
  <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
</table>

<br>
insgesamt <?php echo $totalRows_rst4 ?> Fertigungsmeldungen gefunden. 
<?php } // Show if recordset not empty ?>
<br>
<br>
<?php if ($totalRows_rst5 == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine m&ouml;glichen Reparaturdaten oder Fehlermeldungen zur Auswahl gefunden.</font></p>
  <?php } // Show if recordset empty ?>
    
    <?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
<table width="727" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="277"><strong>M&ouml;gliche Reparaturdaten:</strong></td>
    <td width="48">Status </td>
    <td width="82">User</td>
    <td width="87">Bemerkungen </td>
    <td width="227">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form2" method="post" action="">
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php echo $row_rst5['fm1datum']; ?> <input name="fmfd12" type="hidden" id="fmfd12" value="<?php echo $row_rst5['fm1datum']; ?>"> 
        <input name="name2" type="hidden" id="name2" value="<?php echo $HTTP_POST_VARS['name']; ?>"> 
        <input name="select2" type="hidden" id="select2" value="<?php echo $HTTP_POST_VARS['select']; ?>"> 
        <input name="debitor" type="hidden" id="debitor" value="<?php echo $HTTP_POST_VARS['debitor']?>"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/<?php if (!(strcmp($row_rst5['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15"> 
        - <img src="picture/<?php if (!(strcmp($row_rst5['fm3'],1))) {echo "st2.gif";}else{echo "st6.gif";} ?>" width="15" height="15"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rstuser['id'], $row_rst5['fmuser']))) {echo $row_rstuser['name'];}?>
        <?php
} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  $rows = mysql_num_rows($rstuser);
  if($rows > 0) {
      mysql_data_seek($rstuser, 0);
	  $row_rst9 = mysql_fetch_assoc($rstuser);
  }
?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(nl2br($row_rst5['fm1notes']),40,"<br>",255); ?> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><a href="la41.php?url_user=<?php echo $row_rst1['id']; ?>&url_fmid=<?php echo $row_rst4['fartikelid']; ?>&url_fmid=<?php echo $row_rst5['fmid']; ?>" target="_blank"><img src="picture/primary.gif" width="21" height="17" border="0" align="absmiddle">Retourenabwicklung</a></div></td>
    </tr>
  </form>
  <?php } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
</table>
<p> insgesamt <?php echo $totalRows_rst5 ?> Fertigungsmeldungen gefunden. </p>
<p>&nbsp;</p>
<?php } // Show if recordset not empty ?>

<?php

  
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);
mysql_free_result($rst5);

mysql_free_result($rstretur);

mysql_free_result($rstservice);
?>
</p>

  <?php include("footer.tpl.php"); ?>
