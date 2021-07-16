<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la21";
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

if ((isset($HTTP_POST_VARS["new"] ))) {
  $insertSQL = sprintf("INSERT INTO artikelversionen (beschreibung, user, version,  Datum, artikelid, absn, ean13, pruefzeichen, kundenmaterialnummer, 
  materialnummer) VALUES ( %s ,%s,%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['version'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['idartikel'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['absn'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['ean13'], "text"),
						 GetSQLValueString($HTTP_POST_VARS['pruefzeichen'], "text"),
						  GetSQLValueString($HTTP_POST_VARS['kundenmaterialnummer'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['materialnummer'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}
  

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikelversionen WHERE artikelversionen.artikelid = '$url_artikelid' ORDER BY artikelversionen.Datum";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikeldaten WHERE artikelid='$url_artikelid'";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la2.php?url_user=".$row_rst1['id'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la22.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_id=".$HTTP_POST_VARS["hurl_id"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["la11"])) ) {
$updateGoTo = "la11.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["add"])) ) {
$updateGoTo = "la23.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"]."&url_id=".$HTTP_POST_VARS["hurl_id"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["la202"])) ) {
$updateGoTo = "la202.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="980" border="0" cellspacing="0" cellpadding="0">
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3"><strong>LA21- Artikelversionen</strong></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td><img src="picture/fisstartseite.png" width="55" height="55"> <img src="picture/neuanlegen.png" width="55" height="55"></td>
      <td><div align="right"><img src="picture/messwertebearbeiten.png" width="55" height="55"></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="198">Aktionen W&auml;hlen:</td>
      <td width="293"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
        <img src="picture/eo.png" width="15" height="15"> <input name="la11" type="submit" id="la11" value="Fertigungsmeldungen"> 
      </td>
      <td width="230"> <div align="right"> 
          <input name="la202" type="submit" id="la202" value="Stammdaten">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="29">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3"><hr> </td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>ausgew&auml;hlter Produkttyp:</td>
      <td colspan="2"><select name="idartikel" id="select">
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $url_artikelid))) {echo "SELECTED";} ?>><?php echo $row_rst4['Bezeichnung']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select> <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>neue Versions&auml;nderung<br> <br> <br> <br> </td>
      <td> <textarea name="beschreibung" cols="60" rows="6" id="beschreibung"></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Datum</td>
      <td> <input name="datum" type="text" id="datum"> <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>ab Serienummer</td>
      <td> <input name="absn" type="text" id="absn"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>fortl.Version-Nr.</td>
      <td><input name="version" type="text" id="version" value="Version "></td>
      <td><div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="new" type="submit" id="new2" value="Neue Version speichern">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Materialnummer intern:</td>
      <td><input name="materialnummer" type="text" id="materialnummer" value="<?php echo $row_rst4['Nummer']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Kundenmaterial-Nr.:</td>
      <td><input name="kundenmaterialnummer" type="text" id="kundenmaterialnummer" value="<?php echo $row_rst4['kundenmaterialnummer']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td bgcolor="#CCCCFF">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Kennzeichnung</td>
      <td bgcolor="#CCCCFF">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><img src="picture/iconFixedprice_16x16.gif" width="16" height="16"> 
        EAN13-Code</td>
      <td><input name="ean13" type="text" size="60" value="<?php echo $row_rst4['ean13']; ?>"></td>
      <td>auf Etikettdruck von EAN13</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><img src="picture/certificate_ok.gif" width="16" height="16">Pruefzeichen</td>
      <td><input name="pruefzeichen" type="text" size="60" value="<?php echo $row_rst4['pruefzeichen']; ?>"></td>
      <td>im root-Ordner ../picture</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
</form>
<p>&nbsp;</p><?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<em>Liste der Artikelversionen</em><br>
 
  
<table width="980" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="82"><font size="1"><em><strong>Seit</strong></em></font></td>
    <td width="51"><font size="1"><em><strong>Version </strong></em></font></td>
    <td width="86"><font size="1"><strong><em>Materialnummer</em></strong></font></td>
    <td width="86"><font size="1"><strong>K-Material</strong></font></td>
    <td width="237"><font size="1"><em><strong>Beschreibung</strong></em></font></td>
    <td width="76"><font size="1"><strong><em>Pr&uuml;fzeichen</em></strong></font></td>
    <td width="120"><font size="1"><em><strong>&Auml;nderung durch</strong></em></font></td>
    <td width="5"><font size="1">&nbsp;</font></td>
    <td width="80"><font size="1"><em><strong>ab Serien-Nr.</strong></em></font></td>
    <td width="146"><div align="right"><font size="1"><em><strong>Versionen pflegen</strong></em></font></div></td>
  </tr>
  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['Datum']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(nl2br($row_rst2['version']),40,"<br>",255); ?>
	  <?php if ($row_rst2['version']==$row_rst4['version']) {?>
	  <img src="picture/aktivitaet_24x24.png" alt="Diese Version ist gerade freigegeben." width="24" height="24" border="0">
	  <?php }?>
	  </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo ($row_rst2['materialnummer']); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo ($row_rst2['kundenmaterialnummer']); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap(nl2br($row_rst2['beschreibung']),40,"<br>",255); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/eti_<?php echo $row_rst2['pruefzeichen'] ?>.jpg" ></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst3['id'], $row_rst2['user']))) {echo $row_rst3['name'];} ?> 
        <?php
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
  }
?></select>
        </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['absn']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_id" type="hidden" id="hurl_id" value="<?php echo $row_rst2['id']; ?>">
          <input name="hurl_artikelid" type="hidden" id="hurl_artikelid" value="<?php echo $row_rst2['artikelid']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
  </form></tr>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
 
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);
?>
<?php include("footer.tpl.php"); ?>