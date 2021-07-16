<?php require_once('Connections/qsdatenbank.php'); ?><?php $la = "la262";
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
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
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
$query_rst2 = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert=1  AND artikeldaten.artikelid='$url_artikelid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);
if ((isset($HTTP_POST_VARS["save"]) )) {


if ($HTTP_POST_VARS['mdatum']==""){$datumaktuell = "now()";
}else{ $datumaktuell= GetSQLValueString($HTTP_POST_VARS['mdatum'], "date");

}

  $updateSQL = sprintf("UPDATE materialbewegung SET user=%s, mdatum=%s, mbdiff=%s, mbwa=%s, lokz=%s, mbtxt=%s WHERE mbid=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       $datumaktuell,
                       GetSQLValueString($HTTP_POST_VARS['mbdiff'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['mbwa'], "int"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['mbtxt'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_mbid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());





  $updateGoTo = "la261.php";
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
$query_rst3 = "SELECT * FROM materialbewegung WHERE materialbewegung.mbid='$url_fid'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM `user`";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la261.php?url_user=".$row_rst1['id'];
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
  <table width="614" border="0" cellspacing="0" cellpadding="0">
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
      <td width="339"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"></td>
      <td width="123"> <div align="right"> 
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hltes Material</td>
      <td><strong><?php echo $row_rst2['Bezeichnung']; ?></strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>"> 
        <input name="hurl_mbid" type="hidden" id="hurl_mbid" value="<?php echo $row_rst3['mbid']; ?>"></td>
    </tr>
    <tr> 
      <td>St&uuml;ckzahl</td>
      <td><input name="mbdiff" type="text" id="sn7" value="<?php echo $row_rst3['mbdiff']; ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Bewegungsart</td>
      <td><select name="mbwa" id="mbwa">
          <option value="101" <?php if (!(strcmp(101, $row_rst3['mbwa']))) {echo "SELECTED";} ?>>Fertigungsmeldung</option>
          <option value="201" <?php if (!(strcmp(201, $row_rst3['mbwa']))) {echo "SELECTED";} ?>>Wareneingang</option>
          <option value="301" <?php if (!(strcmp(301, $row_rst3['mbwa']))) {echo "SELECTED";} ?>>Warenausgang</option>
          <option value="701" <?php if (!(strcmp(701, $row_rst3['mbwa']))) {echo "SELECTED";} ?>>Inventur</option>
          <option value="102" <?php if (!(strcmp(102, $row_rst3['mbwa']))) {echo "SELECTED";} ?>>Storno-Fertigungsmeldung</option>
          <option value="202" <?php if (!(strcmp(202, $row_rst3['mbwa']))) {echo "SELECTED";} ?>>Storno-Wareneingang</option>
          <option value="302" <?php if (!(strcmp(302, $row_rst3['mbwa']))) {echo "SELECTED";} ?>>Storno-Warenausgang</option>
          <option value="702" <?php if (!(strcmp(702, $row_rst3['mbwa']))) {echo "SELECTED";} ?>>Storno-Inventur</option>
        </select></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p><br>
  </p>
  <p>Pr&uuml;fung</p>
  <table width="614" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="152">Pr&uuml;fung i.O.</td>
      <td width="435"> <input <?php if (!(strcmp($row_rst3['ffrei'],1))) {echo "checked";} ?> name="frei" type="checkbox" id="frei" value="checkbox"> 
        <img src="picture/st1.gif" width="15" height="15"> </td>
      <td width="27">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td> <input type="text" name="mdatum" value="<?php echo $row_rst3['mdatum']; ?>" size="20" maxlength="99" class="textfield" onchange="return unNullify('fdatum', '0')" tabindex="10" id="field_3_3" /> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="Kalender" href="javascript:openCalendar(\'lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci\', \'insertForm\', \'field_3_3\', \'date\')"><img class="calendar" src="./themes/darkblue_orange/img/b_calendar.png" alt="Kalender"/></a>');
                    //-->
                    </script></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schkennzeichen</td>
      <td> <input <?php if (!(strcmp($row_rst3['fsonder'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Grund:<br> &Auml;nderungen:<br> <br> <br> <br> </td>
      <td><textarea name="mbtxt" cols="50" rows="5" id="mbtxt"><?php echo $row_rst3['mbtxt']; ?></textarea></td>
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
  </table>
  <p>&nbsp; </p>
  <input type="hidden" name="MM_insert" value="form2">
  <input type="hidden" name="MM_update" value="form2">
</form>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst9);

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
