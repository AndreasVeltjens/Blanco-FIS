<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "la12";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = (($theValue != "") ? "'" . $theValue . "'" : "NULL");
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

if ((isset($HTTP_POST_VARS["save"]) )) {

if ($HTTP_POST_VARS['sn3']==""){$HTTP_POST_VARS['sn3']="entf�llt";}
if ($HTTP_POST_VARS['sn4']==""){$HTTP_POST_VARS['sn4']="entf�llt";}
if ($HTTP_POST_VARS['datum']==""){$datumaktuell = "now()";
}else{ $datumaktuell= GetSQLValueString($HTTP_POST_VARS['datum'], "date");

}

  $updateSQL = sprintf("UPDATE fertigungsmeldungen SET fuser=%s, fdatum=%s, fsn=%s, version=%s, fsn1=%s, fsn2=%s, fsn3=%s, fsn4=%s, fsonder=%s, ffrei=%s, 
                        fnotes=%s, fnotes1=%s, configprofil=%s, status=%s , history=%s WHERE fid=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       $datumaktuell,
                       GetSQLValueString($HTTP_POST_VARS['sn'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['version'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['sn4'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['sonder']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['frei']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['notes'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['fnotes1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['configprofil'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['status'], "int"),
					   GetSQLValueString("Datensatzmodifikation $location User $hurl_user  - $datumaktuell; ", "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_fid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la11.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
$query_rst3 = "SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fid='$url_fid'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM `user`";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstversion = "SELECT * FROM artikelversionen WHERE artikelversionen.artikelid='$row_rst2[artikelid]' AND artikelversionen.lokz=0  ORDER BY artikelversionen.version";
$rstversion = mysql_query($query_rstversion, $qsdatenbank) or die(mysql_error());
$row_rstversion = mysql_fetch_assoc($rstversion);
$totalRows_rstversion = mysql_num_rows($rstversion);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_Recordset1 = "SELECT * FROM artikelversionen WHERE artikelversionen.artikelid='$row_rst2[artikelid]' AND artikelversionen.lokz=0  ORDER BY artikelversionen.version";
$Recordset1 = mysql_query($query_Recordset1, $qsdatenbank) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la11.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion w�hlen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>


<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <br> <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="152">Aktionen W&auml;hlen:</td>
      <td width="318"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"></td>
      <td width="144"> <div align="right"> 
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Ger&auml;tetyp:</td>
      <td><strong><?php echo $row_rst2['Bezeichnung']; ?> 
        <input name="hurl_user" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_artikelid" type="hidden" id="hurl_artikelid2" value="<?php echo $row_rst2['artikelid']; ?>">
        <input name="hurl_fid" type="hidden" id="hurl_fid2" value="<?php echo $row_rst3['fid']; ?>">
        </strong></td>
      <td rowspan="4"><div align="right"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" alt="EAN/UPC Nummer: <?php echo $row_rst2['ean13'] ?>" width="180" height="140" border="0"></a> 
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>Seriennummer/ Version: </td>
      <td><input name="sn" type="text" id="sn7" value="<?php echo $row_rst3['fsn']; ?>">
        <input name="version" type="text" id="sn" value="<?php echo $row_rst3['version']; ?>"> 
      </td>
    </tr>
    <tr> 
      <td>Vorlageversion</td>
      <td><select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rstversion['version']?>"<?php if (!(strcmp($row_rstversion['version'], $row_rstversion['version']))) {echo "SELECTED";} ?>><?php echo $row_rstversion['version']?></option>
          <?php
} while ($row_rstversion = mysql_fetch_assoc($rstversion));
  $rows = mysql_num_rows($rstversion);
  if($rows > 0) {
      mysql_data_seek($rstversion, 0);
	  $row_rstversion = mysql_fetch_assoc($rstversion);
  }
?>
        </select> <img src="picture/help.gif" width="17" height="17"></td>
    </tr>
    <tr>
      <td>Status</td>
      <td><p>
          <label>
          <input <?php if (!(strcmp($row_rst3['status'],"0"))) {echo "CHECKED";} ?> type="radio" name="status" value="0">
          L&ouml;schvormerkung</label>
          <br>
          <label>
          <input <?php if (!(strcmp($row_rst3['status'],"1"))) {echo "CHECKED";} ?> type="radio" name="status" value="1">
          
          in Arbeit</label>
          <br>
          <label>
          <input <?php if (!(strcmp($row_rst3['status'],"2"))) {echo "CHECKED";} ?> type="radio" name="status" value="2">
          Freigabe und gepr&uuml;ft</label>
          <br>
          <label>
          <input <?php if (!(strcmp($row_rst3['status']," "))) {echo "CHECKED";} ?> type="radio" name="status" value=" ">
          
          unklar</label>
          <br>
        </p>
        </td>
      <td><a href="etiprogramm.php?url_user=<?php echo $url_user; ?>&hurl_fid=<?php echo $row_rst3['fid']; ?>" target="_blank">weitere Statusauswahl</a></td>
    </tr>
  </table>
  <p><br>
    Bauteildaten</p>
  <table width="950" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFFFFF"> 
      <td width="273">Seriennummer 1 (FD Heizung)</td>
      <td width="357"> <input name="sn1" type="text" id="sn1" value="<?php echo $row_rst3['fsn1']; ?>"></td>
      <td width="320">&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Seriennummer 2 (Nr. Regler/Kabelbaum)</td>
      <td> <input name="sn2" type="text" id="sn2" value="<?php echo $row_rst3['fsn2']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Seriennummer 3 (FD L&uuml;fter)</td>
      <td> <input name="sn3" type="text" id="sn3" value="<?php echo $row_rst3['fsn3']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>sonstiges</td>
      <td><input name="sn4" type="text" id="sn4" value="<?php echo $row_rst3['fsn4']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>Pr&uuml;fung</p>
  <table width="950" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr> 
      <td width="276">VDE-Pr&uuml;fung i.O.</td>
      <td width="656"> <input <?php if (!(strcmp($row_rst3['ffrei'],1))) {echo "checked";} ?> name="frei" type="checkbox" id="frei" value="checkbox"> 
        <img src="picture/st1.gif" width="15" height="15"> </td>
      <td width="18">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td> <input type="text" name="fields[multi_edit][+%60upid%60+%3D+0][datum]" value="<?php echo $row_rst3['fdatum']; ?>" size="20" maxlength="99" class="textfield" onchange="return unNullify('fdatum', '0')" tabindex="10" id="field_3_3" /> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="Kalender" href="javascript:openCalendar(\'lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci\', \'insertForm\', \'field_3_3\', \'date\')"><img class="calendar" src="./themes/darkblue_orange/img/b_calendar.png" alt="Kalender"/></a>');
                    //-->
                    </script></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Sonderfreigabe erforderlich</td>
      <td> <input <?php if (!(strcmp($row_rst3['fsonder'],1))) {echo "checked";} ?> name="sonder" type="checkbox" id="sonder" value="checkbox"> 
        <img src="picture/st2.gif" width="15" height="15"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Grund:<br> &Auml;nderungen:<br> <br> <br> <br> </td>
      <td><textarea name="notes" cols="50" rows="5" id="notes"><?php echo $row_rst3['fnotes']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Messwerte</td>
      <td><textarea name="fnotes1" cols="50" rows="5" id="fnotes1"><?php echo $row_rst3['fnotes1']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
      <tr>
          <td>Konfigurationeigenschaften</td>
          <td><textarea name="configprofil" cols="50" rows="5" id="configprofil"><?php echo $row_rst3['configprofil']; ?></textarea></td>
          <td>&nbsp;</td>
      </tr>



      <tr>
      <td>Signatur</td>
      <td><img src="picture/s_process.png" width="16" height="16"> <?php echo $row_rst3['signatur']; ?>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pr&uuml;fer:</td>
      <td><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst9['id'], $row_rst3['fuser']))) {echo $row_rst9['name'];}?> 
        <?php
} while ($row_rst9 = mysql_fetch_assoc($rst9));
  $rows = mysql_num_rows($rst9);
  if($rows > 0) {
      mysql_data_seek($rst9, 0);
	  $row_rst9 = mysql_fetch_assoc($rst9);
  }
?>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>letzte &Auml;nderung:</td>
      <td><?php echo $row_rst3['change']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pr&uuml;fger&auml;te:</td>
      <td><?php echo utf8_decode($row_rst3['pgeraet']); ?></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp; </p>
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
          <input name="hurl_user2" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
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
  <input type="hidden" name="MM_insert" value="form2">
  <input type="hidden" name="MM_update" value="form2">
</form>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst9);

mysql_free_result($rstversion);

mysql_free_result($Recordset1);

?>
</p>
<script type="text/javascript">
<!--
    var forceQueryFrameReload = false;
    var dbBoxSetupDone = false;
    function dbBoxSetup() {
        if (dbBoxSetupDone != true) {
            if (parent.frames.queryframe && parent.frames.queryframe.document.left && parent.frames.queryframe.document.left.lightm_db) {
                parent.frames.queryframe.document.left.lightm_db.value = 'qsdb';
                dbBoxSetupDone = true;
            } else {
                setTimeout("dbBoxSetup();",500);
            }
        }
    }
    if (parent.frames.queryframe && parent.frames.queryframe.document && parent.frames.queryframe.document.queryframeform) {
        parent.frames.queryframe.document.queryframeform.db.value = "qsdb";
        parent.frames.queryframe.document.queryframeform.table.value = "userprofile";
    }
    if (parent.frames.queryframe && parent.frames.queryframe.document && parent.frames.queryframe.document.left && parent.frames.queryframe.document.left.lightm_db) {
        selidx = parent.frames.queryframe.document.left.lightm_db.selectedIndex;
        if (parent.frames.queryframe.document.left.lightm_db.options[selidx].value == "qsdb" && forceQueryFrameReload == false) {
            parent.frames.queryframe.document.left.lightm_db.options[selidx].text =
                parent.frames.queryframe.document.left.lightm_db.options[selidx].text.replace(/(.*)\([0-9]+\)/,'$1(21)');
        } else {
            parent.frames.queryframe.location.reload();
            setTimeout("dbBoxSetup();",2000);
        }
    }
    
    function reload_querywindow () {
        if (parent.frames.queryframe && parent.frames.queryframe.querywindow && !parent.frames.queryframe.querywindow.closed && parent.frames.queryframe.querywindow.location) {
                        if (!parent.frames.queryframe.querywindow.document.sqlform.LockFromUpdate || !parent.frames.queryframe.querywindow.document.sqlform.LockFromUpdate.checked) {
                parent.frames.queryframe.querywindow.document.querywindow.db.value = "qsdb";
                parent.frames.queryframe.querywindow.document.querywindow.query_history_latest_db.value = "qsdb";
                parent.frames.queryframe.querywindow.document.querywindow.table.value = "userprofile";
                parent.frames.queryframe.querywindow.document.querywindow.query_history_latest_table.value = "userprofile";

                parent.frames.queryframe.querywindow.document.querywindow.query_history_latest.value = "SELECT+%2A+FROM+%60userprofile%60";

                parent.frames.queryframe.querywindow.document.querywindow.submit();
            }
                    }
    }

    function focus_querywindow(sql_query) {
        if (parent.frames.queryframe && parent.frames.queryframe.querywindow && !parent.frames.queryframe.querywindow.closed && parent.frames.queryframe.querywindow.location) {
            if (parent.frames.queryframe.querywindow.document.querywindow.querydisplay_tab != 'sql') {
                parent.frames.queryframe.querywindow.document.querywindow.querydisplay_tab.value = "sql";
                parent.frames.queryframe.querywindow.document.querywindow.query_history_latest.value = sql_query;
                parent.frames.queryframe.querywindow.document.querywindow.submit();
                parent.frames.queryframe.querywindow.focus();
            } else {
                parent.frames.queryframe.querywindow.focus();
            }

            return false;
        } else if (parent.frames.queryframe) {
            new_win_url = 'querywindow.php?sql_query=' + sql_query + '&lang=de-utf-8&server=1&collation_connection=utf8_general_ci&db=qsdb&table=userprofile';
            parent.frames.queryframe.querywindow=window.open(new_win_url, '','toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=yes,resizable=yes,width=600,height=400');

            if (!parent.frames.queryframe.querywindow.opener) {
               parent.frames.queryframe.querywindow.opener = parent.frames.queryframe;
            }

            // reload_querywindow();
            return false;
        }
    }

    reload_querywindow();

//-->
</script>
<script type="text/javascript" language="javascript" src="libraries/tooltip.js"></script>

  <?php include("footer.tpl.php"); ?>
