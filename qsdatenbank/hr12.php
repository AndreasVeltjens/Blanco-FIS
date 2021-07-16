<?php require_once('Connections/psdatenbank.php'); ?>
<?php require_once('Connections/qsdatenbank.php'); ?><?php
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
  $insertSQL = sprintf("INSERT INTO bewertung (id_user, datum, cat1, cat1text, cat2, cat2text, cat3, cat3text, cat4, cat4text, cat5, cat5text, id_userbew) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['cat1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat1text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat2text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat3text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat4'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat4text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat5'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['cat5text'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_userid'], "int"));

  mysql_select_db($database_psdatenbank, $psdatenbank);
  $Result1 = mysql_query($insertSQL, $psdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["del"]))) {
  $updateSQL = sprintf("UPDATE bewertung SET lokz=%s WHERE id_bew=%s",
                       GetSQLValueString($HTTP_POST_VARS['hid_bew2'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hid_bew'], "int"));

  mysql_select_db($database_psdatenbank, $psdatenbank);
  $Result1 = mysql_query($updateSQL, $psdatenbank) or die(mysql_error());
}

$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "hr12";

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


$maxRows_rst4 = 12;
$pageNum_rst4 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst4'])) {
  $pageNum_rst4 = $HTTP_GET_VARS['pageNum_rst4'];
}
$startRow_rst4 = $pageNum_rst4 * $maxRows_rst4;

mysql_select_db($database_psdatenbank, $psdatenbank);
$query_rst4 = "SELECT * FROM bewertung WHERE bewertung.id_user='$url_usid' and bewertung.lokz=0 ORDER BY bewertung.datum desc, bewertung.id_bew desc";
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
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <br> </td>
    </tr>
    <tr> 
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="218"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </td>
      <td width="274"> <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="husid" type="hidden" id="husid" value="<?php echo $row_rst2['id']; ?>">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td>&nbsp;</td>
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
      <td>&nbsp;</td>
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
  </table></form>
  <br>
  <em><strong><img src="picture/b_newdb.png" width="16" height="16"> Neue Bewertung 
  anlegen</strong></em> 
 
  
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  
  <table width="730" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCFF">
    <tr> 
      <td width="218">&nbsp;</td>
      <td width="35" bgcolor="#00CC00"><div align="center">3</div></td>
      <td width="35" bgcolor="#FFFF00"><div align="center">2</div></td>
      <td width="35" bgcolor="#FF6600"><div align="center">1</div></td>
      <td width="35" bgcolor="#FF0000"><div align="center">0</div></td>
      <td width="371">Bemerkungen</td>
    </tr>
    <tr> 
      <td>gemeinsames Denken und Handeln</td>
      <td bgcolor="#00CC00"> <div align="center"> 
          <input name="cat1" type="radio" value="3" checked>
        </div></td>
      <td bgcolor="#FFFF00"> <div align="center"> 
          <input type="radio" name="cat1" value="2">
        </div></td>
      <td bgcolor="#FF6600"> <div align="center"> 
          <input type="radio" name="cat1" value="1">
        </div></td>
      <td bgcolor="#FF0000"> <div align="center"> 
          <input name="cat1" type="radio" value="0">
        </div></td>
      <td><input name="cat1text" type="text" id="cat1text" value=" " size="60" maxlength="255"></td>
    </tr>
    <tr> 
      <td>Umgang miteinander</td>
      <td bgcolor="#00CC00"> <div align="center"> 
          <input name="cat2" type="radio" value="3" checked>
        </div></td>
      <td bgcolor="#FFFF00"> <div align="center"> 
          <input type="radio" name="cat2" value="2">
        </div></td>
      <td bgcolor="#FF6600"> <div align="center"> 
          <input type="radio" name="cat2" value="1">
        </div></td>
      <td bgcolor="#FF0000"> <div align="center"> 
          <input name="cat2" type="radio" value="0">
        </div></td>
      <td><input name="cat2text" type="text" id="cat2text2" value=" " size="60" maxlength="255"> 
      </td>
    </tr>
    <tr> 
      <td>Arbeitsqualit&auml;t<br> </td>
      <td bgcolor="#00CC00"> <div align="center"> 
          <input name="cat3" type="radio" value="3" checked>
        </div></td>
      <td bgcolor="#FFFF00"> <div align="center"> 
          <input type="radio" name="cat3" value="2">
        </div></td>
      <td bgcolor="#FF6600"> <div align="center"> 
          <input type="radio" name="cat3" value="1">
        </div></td>
      <td bgcolor="#FF0000"> <div align="center"> 
          <input name="cat3" type="radio" value="0">
        </div></td>
      <td> 
        <input name="cat3text" type="text" id="cat3text" value=" " size="60" maxlength="255"></td>
    </tr>
    <tr> 
      <td>Ordnung im Arbeitsumfeld</td>
      <td bgcolor="#00CC00"> <div align="center"> 
          <input name="cat4" type="radio" value="3" checked>
        </div></td>
      <td bgcolor="#FFFF00"> <div align="center"> 
          <input type="radio" name="cat4" value="2">
        </div></td>
      <td bgcolor="#FF6600"> <div align="center"> 
          <input type="radio" name="cat4" value="1">
        </div></td>
      <td bgcolor="#FF0000"> <div align="center"> 
          <input name="cat4" type="radio" value="0">
        </div></td>
      <td> <input name="cat4text" type="text" id="cat4text" value=" " size="60" maxlength="255"></td>
    </tr>
    <tr> 
      <td>au&szlig;erordnentliche Leistung</td>
      <td bgcolor="#00CC00"> <div align="center"> 
          <input name="cat5" type="radio" value="3">
        </div></td>
      <td bgcolor="#FFFF00"> <div align="center"> 
          <input type="radio" name="cat5" value="2">
        </div></td>
      <td bgcolor="#FF6600"> <div align="center"> 
          <input type="radio" name="cat5" value="1">
        </div></td>
      <td bgcolor="#FF0000"> <div align="center"> 
          <input name="cat5" type="radio" value="0" checked>
        </div></td>
      <td> <input name="cat5text" type="text" id="cat5text" value=" " size="60" maxlength="255"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td bgcolor="#00CC00"><div align="center"></div></td>
      <td bgcolor="#FFFF00"><div align="center"></div></td>
      <td bgcolor="#FF6600"><div align="center"></div></td>
      <td bgcolor="#FF0000"><div align="center"></div></td>
      <td> <div align="right"> 
          <input name="hurl_userid" type="hidden" id="hurl_userid" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst2['id']; ?>">
          <input name="datum" type="text" id="datum" value="<?php echo date("Y-m-d",time()); ?>">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          <input name="new" type="submit" id="new" value="anlegen">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p> 
  <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
  <font color="#FF0000"><strong>keine Bewertung angelegt. </strong></font> 
  <?php } // Show if recordset empty ?>
  <br>
</p>
<?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
<em><strong> <img src="picture/s_tbl.png" width="16" height="16"> bisherige Bewertungen 
<br>
</strong></em>Anzeige <a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, 0, $queryString_rst4); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, max(0, $pageNum_rst4 - 1), $queryString_rst4); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, min($totalPages_rst4, $pageNum_rst4 + 1), $queryString_rst4); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, $totalPages_rst4, $queryString_rst4); ?>">Last</a> 
<em><strong> <br>
<br>
</strong></em> 
<table width="730" border="0" cellspacing="1" cellpadding="0">
  <tr> 
    <td width="20"><em><strong>ID-B</strong></em></td>
    <td width="50"><em><strong>Datum</strong></em></td>
    <td width="100"><em><strong>Bewertung von</strong></em></td>
    <td width="80" bgcolor="#FFFFCC"><div align="center"><em><strong>geimsames 
        Denken und Handeln</strong></em></div></td>
    <td width="80"><em><strong>Umgang miteinander</strong></em></td>
    <td width="80" bgcolor="#FFFFCC"><em><strong>Arbeitsqualit&auml;t</strong></em></td>
    <td width="80"><em><strong>Ordnung im Arbeitsumfeld</strong></em></td>
    <td width="80" bgcolor="#FFFFCC"><div align="center"><em><strong>au&szlig;erordentliche 
        Leistung</strong></em></div></td>
    <td width="80" bgcolor="#FFFFCC">Punkte</td>
    <td width="80" bgcolor="#FFFFCC"><div align="center"><em>Aktion</em></div></td>
  </tr>
  <?php 
  $cat1=0;
  $cat2=0;
  $cat3=0;
  $cat4=0;
  $cat5=0;
  
  
  do { ?>
  <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td><?php echo $row_rst4['id_bew']; ?></td>
      <td><?php echo $row_rst4['datum']; ?></td>
      <td><img src="picture/iconchance_16x16.gif" width="16" height="16">
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst5['id'], $row_rst4['id_userbew']))) {echo $row_rst5['name'];}?>
          <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
        </select> </td>
      <td bgcolor="#FFFFCC"> 
        <div align="center"> 
          <?php 
		$catmax=$catmax+ (3*5);/* Definition der maximalen Punkte */
		$cat1=$cat1+ $row_rst4['cat1'];?>
          <?php if ($row_rst4['cat1']==3){ ?>
          <img src="picture/st1.gif" alt="Grund: <?php echo $row_rst4['cat1text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat1']==2){ ?>
          <img src="picture/st2.gif" alt="Grund: <?php echo $row_rst4['cat1text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat1']==1){ ?>
          <img src="picture/st3.gif" alt="Grund: <?php echo $row_rst4['cat1text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat1']==0){ ?>
          <img src="picture/st6.gif" alt="Grund: <?php echo $row_rst4['cat1text']; ?>" width="15" height="15"> 
          <?php } ?>
        </div></td>
      <td> 
        <?php $cat2=$cat2+ $row_rst4['cat2'];?>
        <div align="center"> 
          <?php if ($row_rst4['cat2']==3){ ?>
          <img src="picture/st1.gif" alt="Grund: <?php echo $row_rst4['cat2text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat2']==2){ ?>
          <img src="picture/st2.gif" alt="Grund: <?php echo $row_rst4['cat2text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat2']==1){ ?>
          <img src="picture/st3.gif" alt="Grund: <?php echo $row_rst4['cat2text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat2']==0){ ?>
          <img src="picture/st6.gif" alt="Grund: <?php echo $row_rst4['cat2text']; ?>" width="15" height="15"> 
          <?php } ?>
        </div></td>
      <td bgcolor="#FFFFCC"> 
        <div align="center"> 
          <?php $cat3=$cat3+ $row_rst4['cat3'];?>
          <?php if ($row_rst4['cat3']==3){ ?>
          <img src="picture/st1.gif" alt="Grund: <?php echo $row_rst4['cat3text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat3']==2){ ?>
          <img src="picture/st2.gif" alt="Grund: <?php echo $row_rst4['cat3text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat3']==1){ ?>
          <img src="picture/st3.gif" alt="Grund: <?php echo $row_rst4['cat3text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat3']==0){ ?>
          <img src="picture/st6.gif" alt="Grund: <?php echo $row_rst4['cat3text']; ?>" width="15" height="15"> 
          <?php } ?>
        </div></td>
      <td> 
        <div align="center"> 
          <?php $cat4=$cat4+ $row_rst4['cat4'];?>
          <?php if ($row_rst4['cat4']==3){ ?>
          <img src="picture/st1.gif" alt="Grund: <?php echo $row_rst4['cat4text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat4']==2){ ?>
          <img src="picture/st2.gif" alt="Grund: <?php echo $row_rst4['cat4text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat4']==1){ ?>
          <img src="picture/st3.gif" alt="Grund: <?php echo $row_rst4['cat4text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat4']==0){ ?>
          <img src="picture/st6.gif" alt="Grund: <?php echo $row_rst4['cat4text']; ?>" width="15" height="15"> 
          <?php } ?>
        </div></td>
      <td bgcolor="#FFFFCC"> 
        <div align="center"> 
          <?php $cat5=$cat5+ $row_rst4['cat5'];?>
          <?php if ($row_rst4['cat5']==3){ ?>
          <img src="picture/st1.gif" alt="Grund: <?php echo $row_rst4['cat5text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat5']==2){ ?>
          <img src="picture/st2.gif" alt="Grund: <?php echo $row_rst4['cat5text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat5']==1){ ?>
          <img src="picture/st3.gif" alt="Grund: <?php echo $row_rst4['cat5text']; ?>" width="15" height="15"> 
          <?php } ?>
          <?php if ($row_rst4['cat5']==0){ ?>
          <img src="picture/st6.gif" alt="Grund: <?php echo $row_rst4['cat5text']; ?>" width="15" height="15"> 
          <?php } ?>
        </div></td>
      <td bgcolor="#FFFFCC"><div align="center"><?php echo $row_rst4['cat1']+$row_rst4['cat2']+$row_rst4['cat3']+$row_rst4['cat4']+$row_rst4['cat5']; ?>&nbsp;Punkte</div></td>
      <td bgcolor="#FFFFCC"> 
        <div align="center"> 
          <input name="del" type="submit" id="del" value="l&ouml;schen">
          <input name="hid_bew" type="hidden" id="hid_bew" value="<?php echo $row_rst4['id_bew']; ?>">
          <input name="hid_bew2" type="hidden" id="hid_bew2" value="1">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form3">
  </form>
  <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
  <tr> 
    <td colspan="3">Zwischensumme:</td>
    <td bgcolor="#FFFFCC"><div align="center"><strong><?php echo $cat1 ?>&nbsp;</strong></div></td>
    <td><div align="center"><strong><?php echo $cat2 ?></strong></div></td>
    <td bgcolor="#FFFFCC"><div align="center"><strong><?php echo $cat3 ?></strong></div></td>
    <td><div align="center"><strong><?php echo $cat4 ?></strong></div></td>
    <td bgcolor="#FFFFCC"><div align="center"><strong><?php echo $cat5 ?></strong></div></td>
    <td bgcolor="#FFFFCC"><div align="center"></div></td>
    <td bgcolor="#FFFFCC"><div align="center"></div></td>
  </tr>
  <tr> 
    <td colspan="8"><strong>Gesamt:</strong> <div align="center"></div>
      <div align="center"></div>
      <div align="center"></div>
      <div align="center"></div></td>
    <td bgcolor="#FFFFCC"><div align="center"><strong><?php echo $cat1+$cat2+$cat3+$cat4+$cat5;
	 ?>&nbsp;Punkte</strong></div></td>
    <td bgcolor="#FFFFCC"><div align="center"></div></td>
  </tr>
  <tr> 
    <td colspan="8">erreichbare Punkte 
      <div align="center"></div>
      <div align="center"></div>
      <div align="center"></div>
      <div align="center"></div></td>
    <td bgcolor="#FFFFCC"><div align="center"><strong><?php echo $catmax;
	 ?> Punkte</strong></div></td>
    <td bgcolor="#FFFFCC"><div align="center"></div></td>
  </tr>
  <tr> 
    <td colspan="8"><strong>Pr&auml;mienanteil</strong> <div align="center"></div></td>
    <td bgcolor="#FFFFCC"><div align="center"><strong><?php echo round(($cat1+$cat2+$cat3+$cat4+$cat5)/$catmax*100,1);
	 ?> %</strong></div></td>
    <td bgcolor="#FFFFCC">&nbsp;</td>
  </tr>
</table>
<br>
Anzeige <a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, 0, $queryString_rst4); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, max(0, $pageNum_rst4 - 1), $queryString_rst4); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, min($totalPages_rst4, $pageNum_rst4 + 1), $queryString_rst4); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst4=%d%s", $currentPage, $totalPages_rst4, $queryString_rst4); ?>">Last</a> 
<br>
Statistik: insgesamt <?php echo $totalRows_rst4 ?> Bewertungen angelegt. Anzeige von <?php echo ($startRow_rst4 + 1) ?> bis <?php echo min($startRow_rst4 + $maxRows_rst4, $totalRows_rst4) ?> 
<?php } // Show if recordset not empty ?>
<p>Legende:<br>
  Anforderungen sind:<br>
  <img src="picture/st1.gif" width="15" height="15"> erf&uuml;llt<br>
  <img src="picture/st2.gif" width="15" height="15"> teilweise erf&uuml;llt<br>
  <img src="picture/st3.gif" width="15" height="15"> nicht erf&uuml;llt<br>
  <img src="picture/st6.gif" width="15" height="15"> nicht bewertet</p>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

mysql_free_result($rst5);
?>
</p>

  <?php include("footer.tpl.php"); ?>
