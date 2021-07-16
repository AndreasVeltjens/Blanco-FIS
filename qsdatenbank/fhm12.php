<?php require_once('Connections/qsdatenbank.php'); ?>
<?php
   $la = "fhm12";
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
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);
if ((isset($HTTP_POST_VARS["cancel"])) ) {
if (isset($goback)){$GoTo = $goback.".php";}else{
  $GoTo = "fhm1.php";
  }
$GoTo = $GoTo."?url_user=".$row_rst1['id'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO fhm_link_pruef (id_p, id_fhm) VALUES (%s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['select2'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_fhm'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE fhm_link_pruef SET lokz=%s WHERE id_link=%s",
                       GetSQLValueString($HTTP_POST_VARS['hlokz'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_fhmlink'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["save"] ))) {
  $updateSQL = sprintf("UPDATE fhm SET id_fhm_gruppe=%s, name=%s, beschreibung=%s, lokz=%s, still=%s, `user`=%s, baujahr=%s, Inventarnummer=%s, lagerort=%s, history=%s, ortsveraend=%s, ortsfest=%s, gewicht=%s, wbz=%s, werk=%s, timeyn=%s, sn=%s, suchgruppierung=%s, artikelid=%s WHERE id_fhm=%s",
                       GetSQLValueString($HTTP_POST_VARS['select'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['still']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['baujahr'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['inventarnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['lagerort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['history'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['ortsveraend']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['ortsfest']) ? "true" : "", "defined","1","0"),
                       
					   GetSQLValueString($HTTP_POST_VARS['gewicht'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wbz'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['timeyn'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['sn'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['suchgruppierung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['artikelid'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['hurl_id_fhm'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
if (isset($goback)){$updateGoTo = $goback.".php";}else{
  $updateGoTo = "fhm1.php";
  }
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["savecopy"]))) {
 $insertSQL = sprintf("INSERT INTO fhm (id_fhm_gruppe, name, beschreibung, lokz, still, `user`, baujahr, Inventarnummer, lagerort, history, ortsveraend, ortsfest, gewicht, wbz, werk, timeyn, sn, suchgruppierung, artikelid) VALUES (%s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($HTTP_POST_VARS['select'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['still']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['baujahr'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['inventarnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['lagerort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['history'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['ortsveraend']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['ortsfest']) ? "true" : "", "defined","1","0"),
                       
					   GetSQLValueString($HTTP_POST_VARS['gewicht'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['wbz'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "int"),
					   GetSQLValueString(isset($HTTP_POST_VARS['timeyn']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($HTTP_POST_VARS['sn'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['suchgruppierung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['artikelid'], "int"));


  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

  $insertGoTo = "fhm1.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fhm WHERE fhm.id_fhm=$url_id_fhm";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT fhm_gruppe.id_fhm_gruppe, fhm_gruppe.name, fhm_gruppe.piclink FROM fhm_gruppe ";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT fhm_pruef.id_p, fhm_pruef.name FROM fhm_pruef WHERE fhm_pruef.lokz=0";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT fhm_pruef.name, fhm_pruef.dauer, fhm_pruef.tage, fhm_link_pruef.id_link, fhm_link_pruef.lokz, fhm_link_pruef.id_fhm FROM fhm_link_pruef, fhm_pruef WHERE fhm_link_pruef.lokz=0 AND fhm_link_pruef.id_fhm=$url_id_fhm AND fhm_link_pruef.id_p=fhm_pruef.id_p";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fhm.id_fhm, fhm_gruppe.id_fhm_gruppe, fhm_gruppe.piclink, fhm.id_fhm_gruppe FROM fhm, fhm_gruppe WHERE fhm.id_fhm_gruppe=fhm_gruppe.id_fhm_gruppe and fhm.id_fhm=$url_id_fhm";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst7 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="159">Aktionen W&auml;hlen:</td>
      <td width="435"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <input name="savecopy" type="submit" id="savecopy" value="FHM Kopieren und Neuanlegen"> 
      </td>
      <td width="136"> <div align="right"> 
          <input name="save" type="submit" id="save" value="Speichern">
          <img src="picture/b_newdb.png" width="16" height="16"> </div></td>
    </tr>
    <tr> 
      <td><img src="picture/gmxtools.gif" width="52" height="36"></td>
      <td> <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $url_suchtext; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><strong>ID</strong></td>
      <td><strong><?php echo $row_rst2['id_fhm']; ?> 
        <input name="hurl_id_fhm" type="hidden" id="hurl_id_fhm" value="<?php echo $row_rst2['id_fhm']; ?>">
        </strong></td>
      <td><input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr> 
      <td>Name</td>
      <td><strong> 
        <textarea name="name" cols="50" rows="3" id="name"><?php echo $row_rst2['name']; ?></textarea>
        </strong></td>
      <td rowspan="5"><div align="right"><img src="picture/<?php echo $row_rst6['piclink']; ?>"></div></td>
    </tr>
    <tr> 
      <td>Gruppe</td>
      <td><select name="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst3['id_fhm_gruppe']?>"<?php if (!(strcmp($row_rst3['id_fhm_gruppe'], $row_rst2['id_fhm_gruppe']))) {echo "SELECTED";} ?>><?php echo utf8_encode($row_rst3['name']);?></option>
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
    <tr> 
      <td>g&uuml;ltig im Werk:</td>
      <td><input name="werk" type="text" id="werk" value="<?php echo $row_rst2['werk']; ?>" size="50" maxlength="255"></td>
    </tr>
    <tr> 
      <td>letzte &Auml;nderung:</td>
      <td><?php echo $row_rst2['lastmod']; ?></td>
    </tr>
    <tr> 
      <td>Baujahr</td>
      <td> <input name="baujahr" type="text" id="baujahr" value="<?php echo $row_rst2['baujahr']; ?>"></td>
    </tr>
    <tr> 
      <td>Inventarnummer</td>
      <td> <input name="inventarnummer" type="text" id="inventarnummer" value="<?php echo $row_rst2['Inventarnummer']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Lagerort</td>
      <td> <input name="lagerort" type="text" id="lagerort" value="<?php echo $row_rst2['lagerort']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Beschreibung<br> <br> <br> <br> </td>
      <td> <textarea name="beschreibung" cols="50" rows="10" id="beschreibung"><?php echo $row_rst2['beschreibung']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Gewicht</td>
      <td> <input name="gewicht" type="text" id="gewicht" value="<?php echo $row_rst2['gewicht']; ?>">
        kg </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <input name="user" type="hidden" id="user" value="<?php echo $row_rst1['id']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>integrierte Schaltuhr</td>
      <td><input <?php if (!(strcmp($row_rst2['timeyn'],1))) {echo "checked";} ?> name="timeyn" type="checkbox" id="timeyn" value="1"></td>
      <td>Selektion f&uuml;r Abfrage Zeitumstellung</td>
    </tr>
    <tr> 
      <td>Ortsver&auml;nderliches Ger&auml;t:</td>
      <td><input <?php if (!(strcmp($row_rst2['ortsveraend'],1))) {echo "checked";} ?> name="ortsveraend" type="checkbox" id="ortsveraend" value="1"></td>
      <td>Selektion f&uuml;r Abfrage Ortsver&auml;nderliche Ger&auml;te</td>
    </tr>
    <tr> 
      <td>Ortsfestes Ger&auml;t:</td>
      <td> <input <?php if (!(strcmp($row_rst2['ortsfest'],1))) {echo "checked";} ?> name="ortsfest" type="checkbox" id="ortsfest" value="1"></td>
      <td>Selektion f&uuml;r Abfrage Ortsfeste Ger&auml;te</td>
    </tr>
    <tr> 
      <td>Wiederbeschaffungswert:</td>
      <td><input name="wbz" type="text" id="wbz" value="<?php echo $row_rst2['wbz']; ?>">
        &euro; </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>History:<br> <br> <br> <br> </td>
      <td><textarea name="history" cols="50" rows="20" id="history"><?php echo $row_rst2['history']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Typ Serivceger&auml;t</td>
      <td> <select name="artikelid" id="artikelid">
          <option value="0" <?php if (!(strcmp(0, $row_rst2['artikelid']))) {echo "SELECTED";} ?>>kein 
          Serviceger&auml;t</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst7['artikelid']?>"<?php if (!(strcmp($row_rst7['artikelid'], $row_rst2['artikelid']))) {echo "SELECTED";} ?>><?php echo $row_rst7['Bezeichnung']?></option>
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
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Serienummer</td>
      <td> <input name="sn" type="text" id="sn" value="<?php echo $row_rst2['sn']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Suchgruppierung</td>
      <td colspan="2"> <input name="suchgruppierung" type="text" id="suchgruppierung" value="<?php echo $row_rst2['suchgruppierung']; ?>" size="50" maxlength="150">
        z.Bsp.&quot; Tauschpool Italien&quot;</td>
    </tr>
    <tr> 
      <td>Stillgelegt</td>
      <td><input <?php if (!(strcmp($row_rst2['still'],1))) {echo "checked";} ?> name="still" type="checkbox" id="still" value="checkbox"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schkennzeichen</td>
      <td> <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="checkbox"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  </form>
  <p>&nbsp;</p>
  
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="614" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="161">Pr&uuml;fung zuordnen</td>
      <td width="321"> <select name="select2">
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['id_p']?>"><?php echo $row_rst4['name']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select></td>
      <td width="132"><div align="right">
          <input name="hurl_id_fhm" type="hidden" id="hurl_id_fhm" value="<?php echo $row_rst2['id_fhm']; ?>">
          <input type="submit" name="Submit" value="hinzuf&uuml;gen">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
</form>
<p><em>zugeordnete Pr&uuml;fung:</em></p>
<?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
<table width="614" border="0" cellpadding="0" cellspacing="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="161">Pr&uuml;fname</td>
    <td width="321">Pr&uuml;ffrist </td>
    <td width="132"><div align="right"> </div></td>
  </tr>
  <?php do { ?>
  <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
    <tr> 
      <td><?php echo $row_rst5['name']; ?></td>
      <td>Pr&uuml;ffrist: 
        <?php echo $row_rst5['tage']; ?> Tage - Dauer:<?php echo $row_rst5['dauer']; ?> Tage</td>
      <td> 
        <div align="right"> 
          <input name="hlokz" type="hidden" id="hlokz" value="1">
          <input name="hurl_id_fhmlink" type="hidden" id="hurl_id_fhmlink" value="<?php echo $row_rst5['id_link']; ?>">
          <input type="submit" name="Submit2" value="l&ouml;schen">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form3">
  </form>
  <?php } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
</table>
<?php } // Show if recordset not empty ?>




<?php if ($totalRows_rst5 == 0) { // Show if recordset empty ?>
<font color="#FF0000">keine Pr&uuml;fungen angelegt.</font> 
<?php } // Show if recordset empty ?>
<p><br>
</p>

<p>&nbsp;</p>
  <p>&nbsp;</p>
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
