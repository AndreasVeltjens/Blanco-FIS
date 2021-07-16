<?php require_once('Connections/qsdatenbank.php'); ?><?php $la = "la61";
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
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);



if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "la6.php?url_user=".$row_rst1['id'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["save"]))) {
  $insertSQL = sprintf("INSERT INTO kundendaten (firma, strasse, plz, ort, faxnummer, telefon, anrede, ansprechpartner, datum, erstellt, land, email, suchbegriff,lokz) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['firma'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['strasse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['plz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['faxnummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['telefon'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['anrede'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['ansprechpartner'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['land'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['email'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['suchbegriff'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

  $insertGoTo = "la6.php?url_user=" . $row_rst1['id'] . "";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "la6.php?url_user=".$row_rst1['id'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if (isset($HTTP_POST_VARS['firma'])){$url_firma=utf8_encode($HTTP_POST_VARS['firma']);}
if (isset($HTTP_POST_VARS['strasse'])){$url_strasse=utf8_encode($HTTP_POST_VARS['strasse']);}
if (isset($HTTP_POST_VARS['ort'])){$url_ort=utf8_encode($HTTP_POST_VARS['ort']);}
if (isset($HTTP_POST_VARS['plz'])){$url_plz=utf8_encode($HTTP_POST_VARS['plz']);}



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kundendaten WHERE kundendaten.firma like '%$url_firma%' and kundendaten.strasse like '%$url_strasse%' and kundendaten.ort like '%$url_ort%' and kundendaten.plz like '%$url_plz%'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT fehlermeldungen.fmid, fehlermeldungen.rechnung, fehlermeldungen.rstrasse, fehlermeldungen.rort, fehlermeldungen.rland, fehlermeldungen.rplz, fehlermeldungen.anrede, fehlermeldungen.faxnummer, fehlermeldungen.ansprechpartner FROM fehlermeldungen WHERE fehlermeldungen.fmid='$url_fmid'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

if (isset($HTTP_POST_VARS['firma'])){$url_firma=($HTTP_POST_VARS['firma']);}
if (isset($HTTP_POST_VARS['strasse'])){$url_strasse=($HTTP_POST_VARS['strasse']);}
if (isset($HTTP_POST_VARS['ort'])){$url_ort=($HTTP_POST_VARS['ort']);}
if (isset($HTTP_POST_VARS['plz'])){$url_plz=($HTTP_POST_VARS['plz']);}

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
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="369"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="123"> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><img src="picture/addressbook.gif" width="52" height="36"></td>
      <td>&nbsp;</td>
      <td>
<div align="right">
          <input name="check" type="submit" id="check" value="Namen pr&uuml;fen">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><p>Firmenname</p>
        <p>&nbsp;</p></td>
      <td> <textarea name="firma" cols="50" id="firma"><?php echo $row_rst3['rechnung']; ?></textarea></td>
      <td><div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>Ansprechpartner (im Briefkopf)</td>
      <td> <input name="ansprechpartner" type="text" id="ansprechpartner" value="<?php echo $row_rst3['ansprechpartner']; ?>" size="50"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="180">Anrede</td>
      <td width="324"> <input name="anrede" type="text" id="anrede" value="<?php echo $row_rst3['anrede']; ?>" size="50"> 
      </td>
      <td width="110">&nbsp;</td>
    </tr>
    <tr> 
      <td>Stra&szlig;e</td>
      <td><input name="strasse" type="text" id="strasse" value="<?php echo $row_rst3['rstrasse']; ?>" size="50"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>PLZ</td>
      <td><input name="plz" type="text" id="plz" value="<?php echo $row_rst3['rplz']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Ort</td>
      <td><input name="ort" type="text" id="ort" value="<?php echo $row_rst3['rort']; ?>" size="50"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Land</td>
      <td><input name="land" type="text" id="land" value="<?php echo $row_rst3['rland']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Faxnummer</td>
      <td><input name="faxnummer" type="text" id="faxnummer" value="<?php echo $row_rst3['faxnummer']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Telefon</td>
      <td><input name="telefon" type="text" id="telefon" value="<?php echo $row_rst2['telefon']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum:</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo $row_rst2['datum']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Kunden-ID</td>
      <td> <input name="hurl_idk" type="hidden" id="hurl_idk" value="<?php echo $row_rst2['idk']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <?php echo $row_rst2['idk']; ?> wird automatisch vergeben</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schkennzeichen </td>
      <td> <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>Suchbegriff</td>
      <td><input name="suchbegriff" type="text" id="suchbegriff"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Email-Adresse</td>
      <td><input name="email" type="text" id="email"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
  <p><br>
    Gefundene Eintr&auml;ge im Kundendatenstamm</p>
  
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="266"><em><strong>Firma</strong></em></td>
    <td width="179"><em><strong>Stra&szlig;e</strong></em></td>
    <td width="63"><em><strong>PLZ</strong></em></td>
    <td width="108"><em><strong>Ort</strong></em></td>
  </tr>
  <?php do { ?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo urldecode(utf8_decode($row_rst2['firma'])); ?></td>
    <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo urldecode(utf8_decode($row_rst2['strasse'])); ?></td>
    <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo urldecode(utf8_decode($row_rst2['plz'])); ?></td>
    <td bgcolor="#EEEEEE" nowrap="nowrap"><?php echo urldecode(utf8_decode($row_rst2['ort'])); ?></td>
  </tr>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
  <p>insgesamt <?php echo $totalRows_rst2 ?> gefundene Eintr&auml;ge</p>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p><font color="#FF0000">keine vergleichbaren Kundendaten vorhanden, Eventuell 
    Suchbegriffe k&uuml;rzen und Pr&uuml;fung nochmals durchf&uuml;hren.</font></p>
  <?php } // Show if recordset empty ?>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
