<?php require_once('Connections/psdatenbank.php'); ?>
<?php require_once('Connections/qsdatenbank.php'); ?><?php


$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];

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

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO faehigkeiten (id_fae, id_user, datum, art, durchgefuehrt) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['fae_id'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_userid'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['art_id'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"));

  mysql_select_db($database_psdatenbank, $psdatenbank);
  $Result1 = mysql_query($insertSQL, $psdatenbank) or die(mysql_error());
}
 $la = "hr22";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

$maxRows_rst2 = 20;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM `user` WHERE `user`.id='$url_usid'";
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
$query_rst3 = "SELECT userprofile.upid, userprofile.name FROM userprofile ORDER BY userprofile.name";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


$maxRows_rst4 = 25;
$pageNum_rst4 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst4'])) {
  $pageNum_rst4 = $HTTP_GET_VARS['pageNum_rst4'];
}
$startRow_rst4 = $pageNum_rst4 * $maxRows_rst4;

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst4 = "SELECT taetigkeiten.tat_id, taetigkeiten.bezeichnung, taetigkeiten.lokz, taetigkeiten.datum, taetigkeiten.werk FROM taetigkeiten WHERE taetigkeiten.lokz=0 ORDER BY taetigkeiten.bezeichnung";
$query_limit_rst4 = sprintf("%s LIMIT %d, %d", $query_rst4, $startRow_rst4, $maxRows_rst4);
$rst4 = mysql_query($query_limit_rst4, $psdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);

if (isset($HTTP_GET_VARS['totalRows_rst4'])) {
  $totalRows_rst4 = $HTTP_GET_VARS['totalRows_rst4'];
} else {
  $all_rst4 = mysql_query($query_rst4);
  $totalRows_rst4 = mysql_num_rows($all_rst4);
}
$totalPages_rst4 = ceil($totalRows_rst4/$maxRows_rst4)-1;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM `user`";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst6 = "SELECT art.art_id, art.beschreibung FROM art WHERE art.lokz=0 ORDER BY art.beschreibung";
$rst6 = mysql_query($query_rst6, $psdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst7 = "SELECT taetigkeiten.tat_id, taetigkeiten.bezeichnung FROM taetigkeiten WHERE taetigkeiten.lokz=0 ORDER BY taetigkeiten.bezeichnung";
$rst7 = mysql_query($query_rst7, $psdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);



$queryString_rst4 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst4") == false && 
        stristr($param, "totalRows_rst4") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst4 = "&" . implode("&", $newParams);
  }
}
$queryString_rst4 = sprintf("&totalRows_rst4=%d%s", $totalRows_rst4, $queryString_rst4);

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
$updateGoTo = "hr1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");

include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form2" method="POST">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3">
        <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?>
      </td>
    </tr>
    <tr> 
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="218"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="274"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="husid" type="hidden" id="husid" value="<?php echo $row_rst2['id']; ?>">
          <img src="picture/my-gmx.gif" width="70" height="50"> </div></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td colspan="2"><em><strong><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        Benutzerdaten</strong></em></td>
      <td><div align="right"></div></td>
    </tr>
    <tr> 
      <td width="172">ID</td>
      <td width="252"> <?php echo $row_rst2['id']; ?></td>
      <td width="306"><?php echo $row_rst2['email']; ?></td>
    </tr>
    <tr> 
      <td><strong>Name</strong></td>
      <td> <strong><?php echo $row_rst2['name']; ?></strong></td>
      <td><?php echo $row_rst2['telefon']; ?></td>
    </tr>
    <tr> 
      <td height="24">Datum angelegt am</td>
      <td> <?php echo $row_rst2['datum']; ?></td>
      <td><?php echo $row_rst2['fax']; ?></td>
    </tr>
    <tr> 
      <td>angelegt von User-ID</td>
      <td><?php echo $row_rst2['user']; ?></td>
      <td><?php echo $row_rst2['anschrift']; ?></td>
    </tr>
    <tr> 
      <td>User aktiviert</td>
      <td> <input <?php if (!(strcmp($row_rst2['aktiviert'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox"></td>
      <td><?php echo $row_rst2['plz']; ?> <?php echo $row_rst2['ort']; ?></td>
    </tr>
    <tr> 
      <td>Benutzername</td>
      <td><?php echo $row_rst2['username']; ?></td>
      <td>Werk: <?php echo $row_rst2['werk']; ?></td>
    </tr>
    <tr> 
      <td colspan="2"><strong><em><img src="picture/s_rights.png" width="16" height="16"> 
        Benutzerrechte </em></strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Rechtesystem nach</td>
      <td colspan="2"><select name="right" id="right">
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['upid']?>"<?php if (!(strcmp($row_rst3['upid'], $row_rst2['recht']))) {echo "SELECTED";} ?>><?php echo $row_rst3['name']?></option>
          <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?>
        </select></td>
    </tr>
  </table>
</form>
  <br>
<em><strong><img src="picture/b_newdb.png" width="16" height="16"> Neue Schulung 
anlegen</strong></em> 
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  
  <table width="730" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCFF">
    <tr> 
      <td width="505"> <select name="art_id" id="art_id">
          <?php
do {  
?>
          <option value="<?php echo $row_rst6['art_id']?>"><?php echo $row_rst6['beschreibung']?></option>
          <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </select></td>
      <td width="12"><div align="center"></div>
        <div align="center"></div>
        <div align="center"></div>
        <div align="center"> </div></td>
      <td width="209"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_userid" type="hidden" id="hurl_userid" value="<?php echo $row_rst2['id']; ?>">
          <input name="datum" type="text" id="datum" value="<?php echo date("Y-m-d",time()); ?>" size="20" maxlength="20">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        </div></td>
    </tr>
    <tr> 
      <td><select name="fae_id" id="fae_id">
          <?php
do {  
?>
          <option value="<?php echo $row_rst7['tat_id']?>"><?php echo ($row_rst7['bezeichnung']);?></option>
          <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
        </select></td>
      <td>&nbsp;</td>
      <td><div align="right">
          <input name="new" type="submit" id="new2" value="anlegen">
        </div></td>
    </tr>
  </table>
  
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p> 
  <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
  <font color="#FF0000"><strong>keine Schulungen angelegt. </strong></font> 
  <?php } // Show if recordset empty ?>
  <br>
</p>
<?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
<em><strong> <img src="picture/s_tbl.png" width="16" height="16"> bisherige Schulungen, 
Kenntnisse, Belehrungen<br>
</strong></em>Anzeige <a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, 0, $queryString_rst4); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, max(0, $pageNum_rst4 - 1), $queryString_rst4); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, min($totalPages_rst4, $pageNum_rst4 + 1), $queryString_rst4); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, $totalPages_rst4, $queryString_rst4); ?>">Last</a> 
<em><strong> <br>
<br>
</strong></em> 
<table width="730" border="0" cellspacing="1" cellpadding="0">
  <tr> 
    <td width="80"><em><strong>T&auml;tigkeiten</strong></em></td>
    <td width="242"><em></em></td>
    <td width="77"><em></em></td>
    <td width="91" >&nbsp;</td>
    <td width="22">&nbsp;</td>
    <td width="113">&nbsp;</td>
    <td width="97" ><div align="center"><em>Aktion</em></div></td>
  </tr>
  <?php 
  $cat1=0;
  $cat2=0;
  $cat3=0;
  $cat4=0;
  $cat5=0;
  
  
  do { 
  
mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst8 = "SELECT faehigkeiten.id_fael, faehigkeiten.id_fae, faehigkeiten.id_user, faehigkeiten.datum, faehigkeiten.art, faehigkeiten.durchgefuehrt, art.art_id, art.beschreibung, art.pic FROM faehigkeiten, art WHERE faehigkeiten.lokz=0 AND art.art_id=faehigkeiten.art AND faehigkeiten.id_fae='$row_rst4[tat_id]' AND faehigkeiten.id_user='$row_rst2[id]' ORDER BY faehigkeiten.datum desc ";
$rst8 = mysql_query($query_rst8, $psdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);
  
  
  
  
  ?>
  <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
    <tr> 
      <td><img src="picture/folder_open.gif" width="16" height="16"> 
        <?php echo $row_rst4['tat_id']; ?></td>
      <td><strong><?php echo $row_rst4['bezeichnung']; ?></strong></td>
      <td>&nbsp;</td>
      <td > 
        <div align="center"> </div></td>
      <td>&nbsp;</td>
      <td ><div align="center"></div></td>
      <td > 
        <div align="center"> 
          <input name="hid_bew" type="hidden" id="hid_bew" value="<?php echo $row_rst4['id_bew']; ?>">
          <input name="hid_bew2" type="hidden" id="hid_bew2" value="1">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form3">
    <tr> 
      <td colspan="7"><?php if ($totalRows_rst8 > 0) { // Show if recordset not empty ?>
        <?php if ($row_rst8['id_fael']!=$HTTP_POST_VARS['detail']){ /* start von letzter Aktion zur Tätigkeit */?>
        <table width="730" border="0" cellspacing="1" cellpadding="0">
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td width="110"> <img src="picture/bssctoc1.gif" width="32" height="16"> 
              <input type="submit" name="Submit" value="+"> <?php echo $row_rst8['id_fael']; ?></td>
            <td width="142"><?php echo $row_rst8['datum']; ?></td>
            <td width="152"><img src="picture/<?php echo $row_rst8['pic']; ?>" width="15" height="15" ><?php echo $row_rst8['beschreibung']; ?></td>
            <td width="197"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
              <?php
do {  
?>
              <?php if (!(strcmp($row_rst5['id'], $row_rst8['durchgefuehrt']))) {echo $row_rst5['name'];}?> 
              <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
            </td>
            <td width="40"> <input name="detail" type="radio" value="<?php echo $row_rst8['id_fael']; ?>" checked></td>
          </tr>
        </table>
        <?php }else {  /* start detailsicht */?>
        <br>
        <table width="730" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="#CCCCCC" > 
            <td width="123" height="20">Id</td>
            <td width="159">Datum</td>
            <td width="172">Art</td>
            <td width="209">durchgef&uuml;hrt am</td>
            <td width="59">Aktion</td>
          </tr>
          <?php do { ?>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td><img src="picture/bssctoc2.gif" width="32" height="16"> 
              <?php echo $row_rst8['id_fael']; ?></td>
            <td><?php echo $row_rst8['datum']; ?></td>
            <td><img src="picture/<?php echo $row_rst8['pic']; ?>" width="15" height="15" ><?php echo $row_rst8['beschreibung']; ?></td>
            <td><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
              <?php
do {  
?>
              <?php if (!(strcmp($row_rst5['id'], $row_rst8['durchgefuehrt']))) {echo $row_rst5['name'];}?> 
              <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <?php } while ($row_rst8 = mysql_fetch_assoc($rst8)); ?>
        </table>
        <?php }  /* ende von detail */?>
        <br>
        <br>
        <?php } // Show if recordset not empty ?> <?php if ($totalRows_rst8 == 0) { // Show if recordset empty ?>
        <p><font color="#FF0000">keine Aktivit&auml;ten vorhanden.</font></p>
        <?php } // Show if recordset empty ?> 
        <?php mysql_free_result($rst8);?>
      </td>
    </tr>
  </form>
  <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
</table>
<br>
Anzeige <a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, 0, $queryString_rst4); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, max(0, $pageNum_rst4 - 1), $queryString_rst4); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, min($totalPages_rst4, $pageNum_rst4 + 1), $queryString_rst4); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, $totalPages_rst4, $queryString_rst4); ?>">Last</a> 
<br>
Statistik: insgesamt <?php echo $totalRows_rst4 ?> m&ouml;gliche F&auml;higkeiten angelegt. Anzeige von <?php echo ($startRow_rst4 + 1) ?> bis <?php echo min($startRow_rst4 + $maxRows_rst4, $totalRows_rst4) ?> 
<?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Legende:<br>
  <img src="picture/st1.gif" width="15" height="15"> Kenntnis vorhanden<br>
  <img src="picture/st2.gif" width="15" height="15"> Grundkenntnisse vorhanden<br>
  <img src="picture/st3.gif" width="15" height="15"> geplante Schulung<br>
  <img src="picture/st4.gif" width="15" height="15"> Schulung<br>
  <img src="picture/st5.gif" width="15" height="15"> Belehrung<br>
  <img src="picture/st6.gif" width="15" height="15"> Wiederholung<br>
</p>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rst7);


?>
</p>

  <?php include("footer.tpl.php"); ?>
