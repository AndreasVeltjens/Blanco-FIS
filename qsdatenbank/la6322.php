<?php require_once('Connections/qsdatenbank.php'); ?><?php require_once('Connections/vsdatenbank.php'); ?>
<?php $la = "la6322";
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

if ((isset($HTTP_POST_VARS["speichern"]))) {
if ($HTTP_POST_VARS["bruttoVK"]==""){
	$HTTP_POST_VARS["bruttoVK"]=$HTTP_POST_VARS["nettoVK"]*$HTTP_POST_VARS["menge"];
	$oktxt="Neue Preisfindung durchgeführt";
	}
  $updateSQL = sprintf("UPDATE vorgangsdaten SET pos=%s, beschreibung=%s, menge=%s, nettoVK=%s, bruttoVK=%s, lieferschein=%s, lokz=%s, datum=%s, kontierung=%s, kurztxt=%s, material=%s WHERE id_vgd=%s",
                       GetSQLValueString($HTTP_POST_VARS['pos'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['menge'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['nettoVK'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['bruttoVK'], "double"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lieferschein']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['vorkontierung'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['kurztext'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['material'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_vgd'], "int"));

  mysql_select_db($database_vsdatenbank, $vsdatenbank);
  $Result1 = mysql_query($updateSQL, $vsdatenbank) or die(mysql_error());
  $oktxt=$oktxt." Daten gespeichert.";
}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "la632.php?url_user=".$row_rst1['id']."&url_id_vg=".$url_id_vg."&url_idk=".$url_is_kd;
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "";}


mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst6 = "SELECT * FROM material ORDER BY material.kurzname";
$rst6 = mysql_query($query_rst6, $vsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);


$HTTP_POST_VARS["suchtext"]=$row_rst6['suchbegriff'];
if (isset($HTTP_POST_VARS["suchtext"])){
if ($HTTP_POST_VARS["suchtext"]==""){$HTTP_POST_VARS["suchtext"]="unbekannt";}
$suchtext=" WHERE fehlermeldungen.fmid like '".$HTTP_POST_VARS["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$HTTP_POST_VARS["suchtext"]."%' OR fehlermeldungen.fmsn like '".$HTTP_POST_VARS["suchtext"]."' ";}
else{$suchtext="";}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT `user`.id, `user`.name FROM `user`";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst4 = "SELECT * FROM vorgangsart ORDER BY vorgangsart.anzeigetxt";
$rst4 = mysql_query($query_rst4, $vsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst3 = "SELECT * FROM vorgange WHERE vorgange.id_vg='$url_id_vg' ";
$rst3 = mysql_query($query_rst3, $vsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst5 = "SELECT * FROM vorgangsdaten WHERE vorgangsdaten.id_vgd='$url_id_vgd' ";
$rst5 = mysql_query($query_rst5, $vsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst7 = "SELECT * FROM kontierung ORDER BY kontierung.datevtxt";
$rst7 = mysql_query($query_rst7, $vsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_vsdatenbank, $vsdatenbank);
$query_rst8 = "SELECT * FROM material WHERE material.id_mat='$HTTP_POST_VARS[selectmaterial]'";
$rst8 = mysql_query($query_rst8, $vsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

if ((isset($HTTP_POST_VARS["vorlage"])) ) {
$row_rst5['beschreibung']=$row_rst8['beschreibung'];
$row_rst5['material']=utf8_decode($row_rst8['id_mat']);
$row_rst5['nettoVK']=round(utf8_decode($row_rst8['nettoVK'])/$row_rst8['preiseinheit'],2);
$row_rst5['bruttoVK']=round($row_rst8['nettoVK']*$row_rst5['menge']/$row_rst8['preiseinheit'],2);
$row_rst5['kurztxt']=utf8_decode($row_rst8['kurzname']);

$oktxt = "Hinweis: gewählte Vorlagedaten aus dem Materialdatenstamm übernommen.";
}


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
        <?php } // Show if recordset empty ?> 
        <?php if ($oktxt !="") { // Show if recordset not empty ?>
        <strong><font color="#00CC33"><?php echo htmlentities($oktxt);?> </font></strong> 
        <?php } // Show if recordset not empty ?>
        <?php if ($errtxt !="") { // Show if recordset not empty ?>
        <strong><font color="#FF0000"><?php echo htmlentities($errtxt); ?> </font></strong> 
        <?php } // Show if recordset not empty ?>
      </td>
    </tr>
    <tr> 
      <td width="122">Aktionen W&auml;hlen:</td>
      <td width="369"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="123"><div align="right"></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><strong><em><img src="picture/s_tbl.png" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst4['id_vga'], $row_rst3['vorgangsart']))) {echo htmlentities($row_rst4['anzeigetxt']);}?></option>
        <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?></select>
        - <?php echo $row_rst3['lfd_nr']; ?>- Positionsdaten f&uuml;r Pos. <?php echo $row_rst5['pos']; ?></em></strong> </td>
    </tr>
  </table>
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>Vorlage verwenden:</td>
      <td colspan="2"><select name="selectmaterial" id="selectmaterial">
          <?php
do {  
?>
          <option value="<?php echo $row_rst6['id_mat']?>"<?php if (!(strcmp($row_rst6['id_mat'], $HTTP_POST_VARS['selectmaterial']))) {echo "SELECTED";} ?>><?php echo $row_rst6['kurzname']?></option>
          <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
          
        </select>
        <input name="vorlage" type="submit" id="vorlage" value="Vorlage verwenden"></td>
    </tr>
    <tr> 
      <td width="148">Beschreibung<br> <br> <br> <br> </td>
      <td width="440"> <textarea name="beschreibung" cols="50" id="beschreibung"><?php echo utf8_decode($row_rst5['beschreibung']); ?></textarea></td>
      <td width="142"><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>Kurztext</td>
      <td> <input name="kurztext" type="text" id="kurztext" value="<?php echo $row_rst5['kurztxt']; ?>" size="50"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Material</td>
      <td><input name="material" type="text" id="material" value="<?php echo $row_rst5['material']; ?>" size="50">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Menge</td>
      <td><input name="menge" type="text" id="menge" value="<?php echo $row_rst5['menge']; ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>NettoVK</td>
      <td><input name="nettoVK" type="text" id="nettoVK" value="<?php echo $row_rst5['nettoVK']; ?>">
        &euro; </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Gesamt VK</td>
      <td><input name="bruttoVK" type="text" id="bruttoVK" value="<?php echo $row_rst5['bruttoVK']; ?>">
        &euro; </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Positionsummer</td>
      <td><input name="pos" type="text" id="pos" value="<?php echo $row_rst5['pos']; ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Vorkontierung</td>
      <td><select name="vorkontierung" id="vorkontierung">
          <?php
do {  
?>
          <option value="<?php echo $row_rst7['id_k']?>"<?php if (!(strcmp($row_rst7['id_k'], $row_rst5['kontierung']))) {echo "SELECTED";} ?>><?php echo $row_rst7['datevtxt']?></option>
          <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
        </select> </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Liefertermin</td>
      <td> <input name="datum" type="text" id="datum" value="<?php echo $row_rst5['datum']; ?>">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ID </td>
      <td> <input name="hurl_id_vg" type="hidden" id="hurl_id_vg" value="<?php echo $row_rst3['id_vg']; ?>">
        <input name="hurl_id_vgd" type="hidden" id="hurl_id_vgd" value="<?php echo $row_rst5['id_vgd']; ?>">
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <?php echo $row_rst3['is_kd']; ?> - <?php echo $row_rst3['id_vg']; ?>- <?php echo $row_rst5['id_vgd']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>L&ouml;schkennzeichen </td>
      <td> 
<input <?php if (!(strcmp($row_rst5['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>Lieferschein</td>
      <td><input <?php if (!(strcmp($row_rst6['lokz'],1))) {echo "checked";} ?> name="lieferschein" type="checkbox" id="lieferschein" value="1"></td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td><div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="speichern" type="submit" id="speichern" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
mysql_free_result($rst3);

mysql_free_result($rst5);

mysql_free_result($rst7);

mysql_free_result($rst8);

mysql_free_result($rst6);

mysql_free_result($rst4);
?>
<?php include("footer.tpl.php"); ?>
