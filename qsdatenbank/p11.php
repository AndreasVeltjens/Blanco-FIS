<?php require_once('Connections/qsdatenbank.php'); ?>
<?php
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
  $la = "p11";

if ((isset($HTTP_POST_VARS["add"]))) {

	if ((strlen($HTTP_POST_VARS['datum'])<1)){
		$datum=$HTTP_POST_VARS['datum']="now()";
	} else{
		$datum=GetSQLValueString($HTTP_POST_VARS['datum'], "date");
	}

  $insertSQL = sprintf("INSERT INTO projektdatendaten (prj_id, datum, erstellt, beschreibung, arbeitszeit) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_prj_id'], "int"),
                       $datum,
                       GetSQLValueString($HTTP_POST_VARS['beteiligte'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['arbeitsaufwand'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}


$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form4")) {
  $insertSQL = sprintf("INSERT INTO projektdaten (prj_nummer, prj_datum, prj_erstellt, prj_ueberschrift, prj_beschreibung, qualitaetsverbesserung, kostenreduzierung, fertigungsoptimierung, zeichnungsanpassung, teileanpassung, servicefreundlichkeit, sonstiges, abgeschlossen, datumabschluss, eingestellt, grund, typ, link) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['nummer'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['erstellt'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ueberschrift'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['qualitaetverbesserung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['kostenreduzierung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['fertigungsoptimierung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['zeichnungsanpassung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['teileanpassung']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['servicefreundlichkeit']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['sonstiges']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($HTTP_POST_VARS['abgeschlossen']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['datumabschluss'], "date"),
                       GetSQLValueString(isset($HTTP_POST_VARS['eingestellt']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['grund'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['typ'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['link'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT projektklassen.beschreibung, projektdaten.id_prj, projektdaten.prj_nummer, projektdaten.prj_datum, projektdaten.prj_erstellt, projektdaten.prj_ueberschrift, projektdaten.abgeschlossen, projektdaten.eingestellt, projektdaten.typ, projektdaten.prioritaet, projektdatendaten.prjd_id, projektdatendaten.datum, projektdatendaten.erstellt, projektdatendaten.beschreibung, projektdatendaten.arbeitszeit FROM projektdaten, projektklassen, projektdatendaten WHERE projektklassen.id_prj_typ= projektdaten.typ AND projektdatendaten.prj_id = projektdaten.id_prj AND projektdatendaten.prj_id = '$url_prj_id' ORDER BY projektdatendaten.datum desc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM projektdaten WHERE projektdaten.id_prj='$url_prj_id'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT projektklassen.id_prj_typ, projektklassen.beschreibung FROM projektklassen ORDER BY projektklassen.beschreibung";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "p1.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "la11.php?url_user=".$row_rst1['id']."&url_prj_id=".$HTTP_POST_VARS["hurl_prj_id"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong>p11-Projektdaten anlegen</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="164">Aktionen W&auml;hlen:</td>
    <td width="472"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </form></td>
    <td width="94" rowspan="6"> <div align="right"> </div></td>
  </tr>
  <tr> 
    <td rowspan="5"><img src="picture/icon_gr_fertigungsmeldung.gif" width="55" height="75"></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><strong>Projektname: <?php echo substr($row_rst2['prj_ueberschrift'],0,80)." ..."; ?></strong></td>
  </tr>
  <tr> 
    <td>Projektnummer: <?php echo $row_rst2['id_prj']; ?>- <?php echo substr($row_rst2['prj_nummer'],0,20); ?></td>
  </tr>
  <tr> 
    <td><?php echo $row_rst3['prj_beschreibung']; ?></td>
  </tr>
  <tr> 
    <td><?php echo $row_rst3['prj_erstellt']; ?>- <?php echo $row_rst3['prj_datum']; ?></td>
  </tr>
  <tr> 
    <td>Status:</td>
    <td> 
      <?php if  ($row_rst2['abgeschlossen']==0 ){?>
      <img src="picture/st1.gif" width="15" height="15"> 
      <?php }else{?>
      <img src="picture/st3.gif" width="15" height="15"> 
      <?php }?>
      <?php if ($row_rst2['prioritaet']>=2){; ?>
      <img src="picture/b_tipp.png" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst2['prioritaet']>=3){; ?>
      <img src="picture/b_tipp.png" width="16" height="16"> 
      <?php }?>
      - <em><img src="picture/s_tbl.png" width="16" height="16"><?php echo $row_rst2['typ']; ?>- <?php echo $row_rst2['beschreibung']; ?></em></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>Typ:</td>
    <td> 
      <?php if ($row_rst3['qualitaetsverbesserung']==1){ ?>
      <img src="picture/iconSIFNW_16x16.gif" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst3['kostenreduzierung']==1){ ?>
      <img src="picture/iconPaidBlue_16x16.gif" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst3['zeichnungsanpassung']==1){ ?>
      <img src="picture/iconFdbkBlu_16x16.gif" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst3['teileanpassung']==1){ ?>
      <img src="picture/iconTealStar_25x25.gif" width="25" height="25"> 
      <?php }?>
      <?php if ($row_rst3['servicefreundlichkeit']==1){ ?>
      <img src="picture/iconYellowStar_25x25.gif" width="25" height="25"> 
      <?php }?>
      <?php if ($row_rst3['sonstiges']==1){ ?>
      <img src="picture/iconFixedprice_16x16.gif" width="16" height="16"> 
      <?php }?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <form action="<?php echo $editFormAction; ?>" name="form4" method="POST">
    <tr> 
      <td>&Uuml;berschrift:</td>
      <td> <input name="ueberschrift" type="text" id="ueberschrift" value="<?php echo $row_rst3['prj_ueberschrift']; ?>"> 
        <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $row_rst3['id_prj']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Beschreibung:<br>
        <br>
        <br>
        <br>
        <br>
        <br> <br> <br> <br> </td>
      <td colspan="2"> <textarea name="beschreibung" cols="80" rows="8" id="beschreibung"><?php echo $row_rst3['prj_beschreibung']; ?></textarea></td>
    </tr>
    <tr> 
      <td>Typ:</td>
      <td><select name="typ" id="typ">
          <?php
do {  
?>
          <option value="<?php echo $row_rsttyp['id_prj_typ']?>"<?php if (!(strcmp($row_rsttyp['id_prj_typ'], $row_rst3['typ']))) {echo "SELECTED";} ?>><?php echo $row_rsttyp['beschreibung']?></option>
          <?php
} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
  $rows = mysql_num_rows($rsttyp);
  if($rows > 0) {
      mysql_data_seek($rsttyp, 0);
	  $row_rsttyp = mysql_fetch_assoc($rsttyp);
  }
?>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>eingestellt:</td>
      <td> <input <?php if (!(strcmp($row_rst3['eingestellt'],1))) {echo "checked";} ?> name="eingestellt" type="checkbox" id="eingestellt" value="1">
        abgeschlossen 
        <input <?php if (!(strcmp($row_rst3['abgeschlossen'],1))) {echo "checked";} ?> name="abgeschlossen" type="checkbox" id="abgeschlossen" value="1">
        Datum: 
        <input name="datumabschluss" type="text" id="datumabschluss" value="<?php echo $row_rst3['datumabschluss']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Grund:</td>
      <td colspan="2"><textarea name="grund" cols="80" rows="4" id="textarea"><?php echo $row_rst3['grund']; ?></textarea></td>
    </tr>
    <tr> 
      <td>Qualit&auml;tsverbesserung:</td>
      <td> <input <?php if (!(strcmp($row_rst3['qualitaetsverbesserung'],1))) {echo "checked";} ?> name="qualitaetverbesserung" type="checkbox" id="qualitaetverbesserung" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Kostenreduzierung:</td>
      <td> <input <?php if (!(strcmp($row_rst3['kostenreduzierung'],1))) {echo "checked";} ?> name="kostenreduzierung" type="checkbox" id="kostenreduzierung" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Fertigungsoptimierung</td>
      <td> <input <?php if (!(strcmp($row_rst3['fertigungsoptimierung'],1))) {echo "checked";} ?> name="fertigungsoptimierung" type="checkbox" id="fertigungsoptimierung" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Zeichnungsanpassung:</td>
      <td> <input <?php if (!(strcmp($row_rst3['zeichnungsanpassung'],1))) {echo "checked";} ?> name="zeichnungsanpassung" type="checkbox" id="zeichnungsanpassung" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Teileanpassung:</td>
      <td> <input <?php if (!(strcmp($row_rst3['teileanpassung'],1))) {echo "checked";} ?> name="teileanpassung" type="checkbox" id="teileanpassung" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Servicefreundlichkeit</td>
      <td> <input <?php if (!(strcmp($row_rst3['servicefreundlichkeit'],1))) {echo "checked";} ?> name="servicefreundlichkeit" type="checkbox" id="servicefreundlichkeit" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Sonstiges</td>
      <td> <input <?php if (!(strcmp($row_rst3['sonstiges'],1))) {echo "checked";} ?> name="sonstiges" type="checkbox" id="sonstiges" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Priorit&auml;t</td>
      <td><select name="select">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['prioritaet']))) {echo "SELECTED";} ?>>nicht 
          festgelegt</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['prioritaet']))) {echo "SELECTED";} ?>>gering</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['prioritaet']))) {echo "SELECTED";} ?>>hoch</option>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Ordner: Projektdaten</td>
      <td><input name="link" type="text" id="link" value="<?php echo $row_rst3['link']; ?>" size="80"></td>
      <td> <input name="save" type="submit" id="save2" value="speichern"></td>
    </tr>
    <tr> 
      <td>Link</td>
      <td colspan="2"><a href="<?php echo $row_rst3['link']; ?>"><?php echo $row_rst3['link']; ?></a></td>
    </tr>
    <tr> 
      <td height="20">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td>Nummer</td>
      <td colspan="2"><input name="nummer" type="text" id="nummer"></td>
    </tr>
    <tr> 
      <td>erstellt:</td>
      <td colspan="2"><input name="erstellt" type="text" id="erstellt"></td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td colspan="2"><input name="datum" type="text" id="datum"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <input type="hidden" name="MM_insert" value="form4">
  </form>
</table>

<?php include("footer.tpl.php"); ?>
