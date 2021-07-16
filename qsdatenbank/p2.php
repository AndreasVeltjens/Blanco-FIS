<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/fedatenbank.php'); ?>
<?php  $la = "p2";
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

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form4")) {
  $updateSQL = sprintf("UPDATE projektdaten SET prj_ueberschrift=%s, prj_beschreibung=%s, qualitaetsverbesserung=%s, kostenreduzierung=%s, fertigungsoptimierung=%s, zeichnungsanpassung=%s, teileanpassung=%s, servicefreundlichkeit=%s, sonstiges=%s, abgeschlossen=%s, datumabschluss=%s, eingestellt=%s, grund=%s, typ=%s, prioritaet=%s, link=%s WHERE id_prj=%s",
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
                       GetSQLValueString($HTTP_POST_VARS['select'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['link'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_prj_id'], "int"));

  mysql_select_db($database_fedatenbank, $fedatenbank);
  $Result1 = mysql_query($updateSQL, $fedatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["add"]))) {

	if ((strlen($HTTP_POST_VARS['datum'])<1)){
		$datum=$HTTP_POST_VARS['datum']="now()";
	} else{
		$datum=GetSQLValueString($HTTP_POST_VARS['datum'], "date");
	}

if ((strlen($HTTP_POST_VARS['termin'])<1)){
		$termin=$HTTP_POST_VARS['termin']="now()";
	} else{
		$termin=GetSQLValueString($HTTP_POST_VARS['termin'], "date");
	}

  $insertSQL = sprintf("INSERT INTO projektdatendaten (prj_id, datum, erstellt, beschreibung, status, termin, arbeitszeit) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_prj_id'], "int"),
                       $datum,
                       GetSQLValueString($HTTP_POST_VARS['beteiligte'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['status'], "int"),
					   $termin,
                       GetSQLValueString($HTTP_POST_VARS['arbeitsaufwand'], "text"));

  mysql_select_db($database_fedatenbank, $fedatenbank);
  $Result1 = mysql_query($insertSQL, $fedatenbank) or die(mysql_error());
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

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rst2 = "SELECT projektklassen.beschreibung, projektdaten.id_prj, projektdaten.prj_nummer, projektdaten.prj_datum, projektdaten.prj_erstellt, projektdaten.prj_ueberschrift, projektdaten.abgeschlossen, projektdaten.eingestellt, projektdaten.typ, projektdaten.prioritaet, projektdatendaten.prjd_id, projektdatendaten.datum, projektdatendaten.erstellt, projektdatendaten.beschreibung, projektdatendaten.arbeitszeit, projektdatendaten.status, projektdatendaten.termin FROM projektdaten, projektklassen, projektdatendaten WHERE projektklassen.id_prj_typ= projektdaten.typ AND projektdatendaten.prj_id = projektdaten.id_prj AND projektdatendaten.prj_id = '$url_prj_id' ORDER BY projektdatendaten.datum ";
$rst2 = mysql_query($query_rst2, $fedatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rst3 = "SELECT * FROM projektdaten WHERE projektdaten.id_prj='$url_prj_id'";
$rst3 = mysql_query($query_rst3, $fedatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rsttyp = "SELECT projektklassen.id_prj_typ, projektklassen.beschreibung FROM projektklassen ORDER BY projektklassen.beschreibung";
$rsttyp = mysql_query($query_rsttyp, $fedatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "p1.php?url_user=".$row_rst1['id']."&print=0";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["print"])) ) {
$updateGoTo = "p2.php?url_user=".$row_rst1['id']."&url_prjd_id=".$HTTP_POST_VARS["hurl_prjd_id"]."&print=1";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



if ((isset($HTTP_POST_VARS["editprj"])) ) {
$updateGoTo = "p212.php?url_user=".$row_rst1['id']."&url_prj_id=".$HTTP_POST_VARS["hurl_prj_id"];

  header(sprintf("Location: %s", $updateGoTo));
}
if ((isset($HTTP_POST_VARS["artikel"])) ) {
$updateGoTo = "p23.php?url_user=".$row_rst1['id']."&url_prj_id=".$HTTP_POST_VARS["hurl_prj_id"];

  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "p22.php?url_user=".$row_rst1['id']."&url_prjd_id=".$HTTP_POST_VARS["hurl_prjd_id"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
if ($print!=1){
	include("menu.tpl.php");
}
?>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="4"><strong>p2-Projektdaten - Details</strong></td>
  </tr>
  <tr> 
    <td colspan="4"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="194">Aktionen W&auml;hlen:</td>
    <td colspan="3"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
        <input name="print" type="submit" id="print" value="Druckansicht">
        <input name="editprj" type="submit" id="editprj" value="Projektdaten bearbeiten">
        <input name="artikel" type="submit" id="artikel" value="Artikelversionen">
        <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $row_rst3['id_prj']; ?>">
        <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $url_prj_id; ?>">
      </form>
      <div align="right"> </div></td>
  </tr>
  <tr> 
    <td rowspan="5"><img src="picture/icon_gr_fertigungsmeldung.gif" width="55" height="75"></td>
    <td width="277">&nbsp;</td>
    <td colspan="2"><div align="right"><a href="pdfp2.php?url_user_id=<?php echo $row_rst1['id']; ?>&url_prj_id=<?php echo $row_rst2['id_prj'] ?>" target="_blank">Bericht 
        drucken</a></div></td>
  </tr>
  <tr> 
    <td colspan="3"><strong><?php echo substr($row_rst2['prj_ueberschrift'],0,80)." ..."; ?></strong></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3"><?php echo $row_rst3['prj_beschreibung']; ?></td>
  </tr>
  <tr> 
    <td><?php echo $row_rst3['prj_erstellt']; ?>- <?php echo $row_rst3['prj_datum']; ?></td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td>Status:</td>
    <td colspan="3"> 
      <?php if  ($row_rst3['abgeschlossen']==0 ){?>
      <img src="picture/st1.gif" width="15" height="15"> 
      <?php }else{?>
      <img src="picture/st3.gif" width="15" height="15"> 
      <?php }?>
      <?php if ($row_rst3['prioritaet']>=2){; ?>
      <img src="picture/b_tipp.png" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst3['prioritaet']>=3){; ?>
      <img src="picture/b_tipp.png" width="16" height="16"> 
      <?php }?>
      <?php echo $row_rst3['id_prj']; ?>- <?php echo substr($row_rst3['prj_nummer'],0,20); ?></td>
  </tr>
  <tr> 
    <td>Typ:</td>
    <td colspan="3"> 
      <?php if ($row_rst3['qualitaetsverbesserung']==1){ ?>
      <img src="picture/certificate_ok.gif" alt="Qualit&auml;tsverbesserung" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst3['kostenreduzierung']==1){ ?>
      <img src="picture/iconBestOffer_16x16.gif" alt="Kostenreduzierung"> 
      <?php }?>
      <?php if ($row_rst3['zeichnungsanpassung']==1){ ?>
      <img src="picture/screenshots.gif" alt="Zeichnungsanpassung" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst3['teileanpassung']==1){ ?>
      <img src="picture/icon_tray_view_close.gif" alt="Teileanpassung" width="20" height="14"> 
      <?php }?>
      <?php if ($row_rst3['fertigungsoptimierung']==1){ ?>
      <img src="picture/stumbleupon.gif" alt="Fertigungsoptimierung" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst3['servicefreundlichkeit']==1){ ?>
      <img src="picture/iconFdbkBlu_16x16.gif" alt="Servicefreundlichkeit" width="16" height="16"> 
      <?php }?>
      <?php if ($row_rst3['sonstiges']==1){ ?>
      <img src="picture/iconFixedprice_16x16.gif" alt="Sonstiges" width="16" height="16"> 
      <?php }?>
    </td>
  </tr>
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form4">
    <tr> 
      <td><img src="picture/certificate_ok.gif" width="16" height="16"> Qualit&auml;tsverbesserung:</td>
      <td> <input <?php if (!(strcmp($row_rst3['qualitaetsverbesserung'],1))) {echo "checked";} ?> name="qualitaetverbesserung" type="checkbox" id="qualitaetverbesserung" value="1"></td>
      <td width="173"><img src="picture/screenshots.gif" width="16" height="16"> 
        Zeichnungsanpassung:</td>
      <td width="86"><input <?php if (!(strcmp($row_rst3['zeichnungsanpassung'],1))) {echo "checked";} ?> name="zeichnungsanpassung" type="checkbox" id="zeichnungsanpassung2" value="1"></td>
    </tr>
    <tr> 
      <td><img src="picture/iconBestOffer_16x16.gif"> Kostenreduzierung:</td>
      <td> <input <?php if (!(strcmp($row_rst3['kostenreduzierung'],1))) {echo "checked";} ?> name="kostenreduzierung" type="checkbox" id="kostenreduzierung" value="1"></td>
      <td> <img src="picture/icon_tray_view_close.gif" width="20" height="14">Teileanpassung:</td>
      <td><input <?php if (!(strcmp($row_rst3['teileanpassung'],1))) {echo "checked";} ?> name="teileanpassung" type="checkbox" id="teileanpassung2" value="1"></td>
    </tr>
    <tr> 
      <td><img src="picture/stumbleupon.gif" width="16" height="16"> Fertigungsoptimierung</td>
      <td> <input <?php if (!(strcmp($row_rst3['fertigungsoptimierung'],1))) {echo "checked";} ?> name="fertigungsoptimierung" type="checkbox" id="fertigungsoptimierung" value="1"></td>
      <td><img src="picture/iconFdbkBlu_16x16.gif" width="16" height="16"> Servicefreundlichkeit</td>
      <td><input <?php if (!(strcmp($row_rst3['servicefreundlichkeit'],1))) {echo "checked";} ?> name="servicefreundlichkeit" type="checkbox" id="servicefreundlichkeit2" value="1"></td>
    </tr>
    <tr> 
      <td><img src="picture/iconFixedprice_16x16.gif" width="16" height="16"> 
        Sonstiges</td>
      <td> <input <?php if (!(strcmp($row_rst3['sonstiges'],1))) {echo "checked";} ?> name="sonstiges" type="checkbox" id="sonstiges" value="1"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Link Projekdaten</td>
      <td colspan="3"><a href="<?php echo $row_rst3['link']; ?>"><?php echo $row_rst3['link']; ?></a> </td>
    </tr>
    <input type="hidden" name="MM_update" value="form4">
  </form>
</table>
<br>
<strong><em>neue Aktivit&auml;t anlegen:</em></strong><br>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="176" height="72">Beschreibung/ Aktivit&auml;t<br> <br> <br> <br> 
      </td>
      <td width="279"><em> 
        <textarea name="textarea" cols="50" rows="4"></textarea>
        </em></td>
      <td width="275">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td>Arbeitsaufwand</td>
      <td> <input name="arbeitsaufwand" type="text" id="arbeitsaufwand"> </td>
      <td>Status: 
        <select name="status" id="status">
          <option value="1">erledigt</option>
          <option value="0">geplant</option>
        </select></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td>Datum</td>
      <td> <input name="datum" type="text" id="datum"> <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $url_prj_id; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt</td>
      <td> <div align="left">Termin 
          <input name="termin" type="text" id="termin">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'termin\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF" class="value"> 
      <td>Beteiligte</td>
      <td><input name="beteiligte" type="text" id="beteiligte" value="<?php echo $row_rst1['name']; ?>"></td>
      <td><div align="right">
          <input name="add" type="submit" id="add" value="speichern">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>
<p>&nbsp;</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<em><strong>Liste der Projektdetails</strong></em><br>
<br>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="109">Status</td>
    <td width="109"><em><strong>Datum</strong></em></td>
    <td width="266"><em><strong>Beschreibung/ Aktivit&auml;t</strong></em></td>
    <td width="81"><em><strong>Benutzer</strong></em></td>
    <td width="155"><strong>Arbeitsaufwand</strong></td>
    <td width="119">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php
  $jahraktuell= substr($row_rst2['datum'],0,4);
  if ($jahraktuell==$jahraktuell_1){ 
  $summeaufwand = $row_rst2['arbeitszeit']+$summeaufwand;
  } else {
   ?>
  <tr bgcolor="#99CC66" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td nowrap="nowrap"><strong>Summe 
      Jahresaufwand:</strong></td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap"><strong><?php echo $summeaufwand; ?>&nbsp;Stunden</strong></td>
    <td nowrap="nowrap"><strong>Gesamt 
      kum.<?php echo $summeaufwandkum; ?> Stunden</strong></td>
  </tr>
  <?php
  $summeaufwand = $row_rst2['arbeitszeit'];
  }
  
  $summeaufwandkum= $row_rst2['arbeitszeit']+$summeaufwand;
 
  if ($row_rst2['typ']!=$typ){?>
  <tr bgcolor="#CCCCCC" > 
    <td colspan="9" nowrap="nowrap"><em><br>
      </em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if  ($row_rst2['termin']< (date("Y-m-d",time() ) )){?>
        <img src="picture/st1.gif" width="15" height="15"> 
        <?php }else{?>
        <img src="picture/st3.gif" width="15" height="15"> 
        <?php }?>
        <?php if  ($row_rst2['status']==1 ){?>
        <img src="picture/fertig.gif" width="15" height="15"> 
        <?php }else{?>
        <img src="picture/b_edit.png" width="16" height="16"> 
        <?php }?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst2['datum']; ?> - </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo substr(nl2br($row_rst2['beschreibung']),0,80)." .."; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['erstellt']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['arbeitszeit']; ?> Std.</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_prjd_id" type="hidden" id="hurl_prjd_id" value="<?php echo $row_rst2['prjd_id']; ?>">
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php
    $typ=$row_rst2['typ'];
	$jahraktuell_1= substr($row_rst2['datum'],0,4);
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<br>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <?php do { ?>
  <?php
  $jahraktuell= substr($row_rst2['datum'],0,4);
  if ($jahraktuell==$jahraktuell_1){ 
  $summeaufwand = $row_rst2['arbeitszeit']+$summeaufwand;
  } else {
   ?>
  <tr bgcolor="#99CC66" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td nowrap="nowrap"><strong>Summe Jahresaufwand:</strong></td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap"><strong><?php echo $summeaufwand; ?>&nbsp;Stunden</strong></td>
    <td nowrap="nowrap"><strong>Gesamt kum.<?php echo $summeaufwandkum; ?> Stunden</strong></td>
  </tr>
  <?php
  $summeaufwand = $row_rst2['arbeitszeit'];
  }
  
  $summeaufwandkum= $row_rst2['arbeitszeit']+$summeaufwand;
 
  if ($row_rst2['typ']!=$typ){?>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  </form>
  <?php
    $typ=$row_rst2['typ'];
	$jahraktuell_1= substr($row_rst2['datum'],0,4);
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<?php } // Show if recordset not empty ?>
<font color="#FF0000">
<?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
 <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rsttyp);

?>
<?php include("footer.tpl.php"); ?>
