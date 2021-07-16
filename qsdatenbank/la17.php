<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "la17";
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

if ((isset($HTTP_POST_VARS["new"]))) {
  $insertSQL = sprintf("INSERT INTO pp_www (betriebsmittel, materialnummer, datensatzname, datum, `user`, pos1, pos2, pos3, pos4, pos5, pos6, pos7, pos8, pos9, pos10, pos11, pos12, pos13, pos14, notes) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['betriebsmittel'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['materialnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datensatzname'], "text"),
                      "now()",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox1']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox2']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox3']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox4']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox5']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox6']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox7']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox8']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox9']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox10']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox11']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox12']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox13']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox14']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['notes'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  $oktext="Datensatz angelegt.";
}

if ((isset($HTTP_POST_VARS["save"] ))) {
  $updateSQL = sprintf("UPDATE pp_www SET betriebsmittel=%s, materialnummer=%s, datensatzname=%s, datum=%s, `user`=%s, pos1=%s, pos2=%s, pos3=%s, pos4=%s, pos5=%s, pos6=%s, pos7=%s, pos8=%s, pos9=%s, pos10=%s, pos11=%s, pos12=%s, pos13=%s, pos14=%s, notes=%s WHERE www_id=%s",
                       GetSQLValueString($HTTP_POST_VARS['betriebsmittel'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['materialnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datensatzname'], "text"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox1']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox2']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox3']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox4']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox5']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox6']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox7']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox8']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox9']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox10']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox11']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox12']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox13']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['checkbox14']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['notes'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_www_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  $oktext="Datensatz gespeichert.";
}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$url_artikelid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM pp_www WHERE pp_www.www_id='$url_www_id'";
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM arbeitsplatz WHERE arbeitsplatz.arb_id='$url_arb_id'";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = $goback.".php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>



<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        </font></strong> 
        <?php } // Show if recordset empty ?> <br> <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Kein Datensa<?php echo $errtxt ?>tz verf&uuml;gbar.</font></strong> 
        <?php } // Show if recordset empty ?>
        <font color="#009900"><strong><?php echo $oktext; ?> </strong></font></td>
    </tr>
    <tr> 
      <td width="152">Aktionen W&auml;hlen:</td>
      <td width="318"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"></td>
      <td width="144"> <div align="right"> 
	  <?php if ($row_rst3['www_id']==""){ ?>
          <input name="new" type="submit" id="new" value="Neu anlegen">
		  <?php } else { ?>
          <input name="save" type="submit" id="save" value="Speichern">
		  <?php } ?>
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Beschreibung::</td>
      <td><strong><?php echo $row_rst2['Bezeichnung']; ?> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
        <input name="hurl_www_id" type="hidden" id="hurl_www_id" value="<?php echo $row_rst3['www_id']; ?>">
        <input name="goback" type="hidden" id="goback" value="<?php echo $goback ?>">
        </strong></td>
      <td rowspan="4"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer'] ?>.png" alt="EAN/UPC Nummer: <?php echo $row_rst2['ean13'] ?>" width="140" height="140" border="0"></a> 
      </td>
    </tr>
    <tr> 
      <td>Material-Nr.</td>
      <td><strong><?php echo $row_rst2['Nummer']; ?></strong> </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <input name="materialnummer" type="hidden" id="materialnummer" value="<?php echo $row_rst2['artikelid']; ?>"> 
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
      <td><img src="picture/b_tblops.png" width="16" height="16"> Betriebsmittel</td>
	  
	  <?php if ($row_rst3['betriebsmittel']==""){$row_rst3['betriebsmittel']=$row_rst4['arb_name'];} ?>
	  
      <td> <input name="betriebsmittel" type="text" id="betriebsmittel" value="<?php echo $row_rst3['betriebsmittel']; ?>" size="60"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><img src="picture/ico_anruflisten.gif" width="16" height="16"> Datensatzname</td>
      <td> <input name="datensatzname" type="text" id="datensatzname" value="<?php echo $row_rst3['datensatzname']; ?>" size="60"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><img src="picture/b_calendar.png" width="16" height="16"> Datum </td>
      <td><?php echo $row_rst3['datum']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><img src="picture/iconchance_16x16.gif" width="16" height="16"> Mitarbeiter</td>
      <td> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst9['id'], $row_rst3['user']))) {echo $row_rst9['name'];}?> 
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#99CCFF"> 
      <td> <div align="center"><strong><em>Pos.</em></strong></div></td>
      <td> <div align="center"><strong><em>Beschreibung</em></strong></div></td>
      <td> <div align="center"><strong><em>in Ordnung</em></strong></div></td>
    </tr>
    <tr> 
      <td><div align="center">1.</div></td>
      <td><div align="left">Datensatz laden</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos1'],1))) {echo "checked";} ?> name="checkbox1" type="checkbox" id="checkbox1" value="1">
        </div></td>
    </tr>
    <tr bgcolor="#99CCFF"> 
      <td><div align="center">2.</div></td>
      <td><div align="left">Werkzeugschutz abnehmen</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos2'],1))) {echo "checked";} ?> name="checkbox2" type="checkbox" id="checkbox2" value="1">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center">3. </div></td>
      <td><div align="left">Werkzeug Datumstempel auf Fertigungsdatum einstellen</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos3'],1))) {echo "checked";} ?> name="checkbox3" type="checkbox" id="checkbox3" value="1">
        </div></td>
    </tr>
    <tr bgcolor="#99CCFF"> 
      <td><div align="center">4.</div></td>
      <td><div align="left">Zentrierung Oberstempel pr&uuml;fen</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos4'],1))) {echo "checked";} ?> name="checkbox4" type="checkbox" id="checkbox4" value="1">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center">5.</div></td>
      <td><div align="left">Zuschnitt aus Formstation entnehmen</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos5'],1))) {echo "checked";} ?> name="checkbox5" type="checkbox" id="checkbox5" value="1">
        </div></td>
    </tr>
    <tr bgcolor="#99CCFF"> 
      <td><div align="center">6.</div></td>
      <td><div align="left">Vortemperierung abkoppeln</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos6'],1))) {echo "checked";} ?> name="checkbox6" type="checkbox" id="checkbox6" value="1">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center">7.</div></td>
      <td><div align="left">Gabel ausw&auml;hlen + Automatischen Werkzeugwechsel 
          starten</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos7'],1))) {echo "checked";} ?> name="checkbox7" type="checkbox" id="checkbox7" value="1">
        </div></td>
    </tr>
    <tr bgcolor="#99CCFF"> 
      <td><div align="center">8.</div></td>
      <td><div align="left">Einbau erfolgt automatisch</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos8'],1))) {echo "checked";} ?> name="checkbox8" type="checkbox" id="checkbox8" value="1">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center">9.</div></td>
      <td><div align="left">Andocken Oberstempel</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos9'],1))) {echo "checked";} ?> name="checkbox9" type="checkbox" id="checkbox9" value="1">
        </div></td>
    </tr>
    <tr bgcolor="#99CCFF"> 
      <td><div align="center">10.</div></td>
      <td><div align="left">Grundstellung anfahren</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos10'],1))) {echo "checked";} ?> name="checkbox10" type="checkbox" id="checkbox10" value="1">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center">11.</div></td>
      <td><div align="left">Werkzeugwechselwagen auf Parkstation fahren</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos11'],1))) {echo "checked";} ?> name="checkbox11" type="checkbox" id="checkbox11" value="1">
        </div></td>
    </tr>
    <tr bgcolor="#99CCFF"> 
      <td><div align="center">12.</div></td>
      <td><div align="left">Fertigungsauftragsmenge eingeben</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos12'],1))) {echo "checked";} ?> name="checkbox12" type="checkbox" id="checkbox12" value="1">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center">13.</div></td>
      <td><div align="left">Beschickungsmaschine auff&uuml;llen:</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos13'],1))) {echo "checked";} ?> name="checkbox13" type="checkbox" id="checkbox13" value="1">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center">
          <p>Fehlermeldung</p>
          <p><img src="picture/c_chat.gif" width="25" height="25"></p>
        </div></td>
      <td><div align="left">Materialcharge-Nr.:<br>
          <textarea name="notes" cols="60" rows="5" id="notes"><?php echo $row_rst3['notes']; ?></textarea>
        </div></td>
      <td><div align="center"></div></td>
    </tr>
    <tr bgcolor="#99CCFF"> 
      <td><div align="center">14.</div></td>
      <td><div align="left">Erstteilfreigabe mit Letztteil von Vorserie durchf&uuml;hren</div></td>
      <td> <div align="center"> 
          <input <?php if (!(strcmp($row_rst3['pos14'],1))) {echo "checked";} ?> name="checkbox14" type="checkbox" id="checkbox14" value="1">
        </div></td>
    </tr>
  </table>
  <p><br>
  </p>
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

mysql_free_result($rst4);

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
