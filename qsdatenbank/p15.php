<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/fedatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php $la = "p15";
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
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if (!isset($HTTP_POST_VARS['nuraktive'])){$HTTP_POST_VARS['nuraktive']=1;}

if ($HTTP_POST_VARS['nuraktive']==1){ $where=" AND projektdaten.abgeschlossen=0 ";}
if (strlen($HTTP_POST_VARS['suche'])>1){ $where=$where." AND projektdaten.prj_ueberschrift like '%".$HTTP_POST_VARS['suche']."%' ";}

if (($HTTP_POST_VARS['id_kundennummer'])>0){ $where=$where." AND projektdaten.id_kundennummer ='".$HTTP_POST_VARS['id_kundennummer']."' ";}
$datumtxt=" AND projektdatendaten.datum >= '$HTTP_POST_VARS[datumab]' and projektdatendaten.datum <='$HTTP_POST_VARS[datumbis]' ";

/* mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rst2 = "SELECT projektklassen.beschreibung as typbeschreibung, projektdaten.id_prj, projektdaten.prj_nummer, projektdaten.prj_datum, projektdaten.prj_beschreibung, projektdaten.prj_erstellt, projektdaten.prj_ueberschrift, projektdaten.abgeschlossen, projektdaten.eingestellt, projektdaten.typ, projektdaten.prioritaet, projektdaten.link, projektdaten.qualitaetsverbesserung, projektdaten.kostenreduzierung, projektdaten.fertigungsoptimierung, projektdaten.zeichnungsanpassung, projektdaten.teileanpassung, projektdaten.servicefreundlichkeit, projektdaten.sonstiges, projektdatendaten.datum, projektdatendaten.erstellt, projektdatendaten.beschreibung, projektdatendaten.arbeitszeit, projektdatendaten.stundensatz, projektdaten.id_kundennummer FROM projektdaten, projektklassen, projektdatendaten WHERE projektklassen.id_prj_typ= projektdaten.typ $where AND projektdatendaten.prj_id =projektdaten.id_prj $datumtxt AND projektdaten.id_kundennummer ORDER BY projektdaten.typ,  projektdaten.id_prj, projektdatendaten.datum asc";
$rst2 = mysql_query($query_rst2, $fedatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2); */

mysql_select_db($database_fedatenbank, $fedatenbank);
$query_rst2 = "SELECT projektklassen.beschreibung as typbeschreibung, projektdaten.id_prj, projektdaten.id_kundennummer, projektdaten.prj_nummer, projektdaten.prj_datum, projektdaten.prj_beschreibung, projektdaten.prj_erstellt, projektdaten.prj_ueberschrift, projektdaten.abgeschlossen, projektdaten.eingestellt, projektdaten.typ, projektdaten.prioritaet, projektdaten.link, projektdaten.qualitaetsverbesserung, projektdaten.kostenreduzierung, projektdaten.fertigungsoptimierung, projektdaten.zeichnungsanpassung, projektdaten.teileanpassung, projektdaten.servicefreundlichkeit, projektdaten.sonstiges, projektdatendaten.datum, projektdatendaten.erstellt, projektdatendaten.beschreibung, projektdatendaten.arbeitszeit, projektdatendaten.stundensatz FROM projektdaten, projektklassen, projektdatendaten WHERE projektklassen.id_prj_typ= projektdaten.typ $where AND projektdatendaten.prj_id =projektdaten.id_prj $datumtxt ORDER BY projektdaten.typ, projektdaten.id_prj, projektdatendaten.datum asc";
$rst2 = mysql_query($query_rst2, $fedatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2); 

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstkunde = "SELECT kundendaten.idk, kundendaten.firma, kundendaten.plz, kundendaten.ort, kundendaten.kundenummer FROM kundendaten";
$rstkunde = mysql_query($query_rstkunde, $qsdatenbank) or die(mysql_error());
$row_rstkunde = mysql_fetch_assoc($rstkunde);
$totalRows_rstkunde = mysql_num_rows($rstkunde);

if ((isset($HTTP_POST_VARS["add"])) ) {
$updateGoTo = "p11.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "p2.php?url_user=".$row_rst1['id']."&url_prj_id=".$HTTP_POST_VARS["hurl_prj_id"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong>p15-Projekt&uuml;bersicht - Stundenabrechnung</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="111">Aktionen W&auml;hlen:</td>
      <td width="281"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="338"> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td rowspan="3"><img src="picture/rechnerschutz.gif" width="52" height="36"></td>
      <td>&nbsp;</td>
      <td width="338">
<div align="right">von 
          <input name="datumab" type="text" id="datumab" value="<?php echo $HTTP_POST_VARS['datumab']; ?>">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datumab\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          jjjj-mm-tt</div></td>
    </tr>
    <tr> 
      <td>Anzeige 
        <select name="nuraktive" id="nuraktive">
          <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['nuraktive']))) {echo "SELECTED";} ?>>zeige 
          alle Projekte</option>
          <option value="1" <?php if (!(strcmp(1, $HTTP_POST_VARS['nuraktive']))) {echo "SELECTED";} ?>>zeige 
          nur aktive Projekte</option>
        </select></td>
      <td width="338">
<div align="right">bis 
          <input name="datumbis" type="text" id="datumbis" value="<?php echo $HTTP_POST_VARS['datumbis']; ?>">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datumbis\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          jjjj-mm-tt</div></td>
    </tr>
    <tr> 
      <td>Suche 
        <input name="suche" type="text" id="suche" value="<?php echo $HTTP_POST_VARS['suche']; ?>"> 
        <input type="submit" name="Submit" value="anzeigen"></td>
      <td width="338"><div align="right">Kunde: 
          <select name="id_kundennummer" id="id_kundennummer">
            <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['id_kundennummer']))) {echo "SELECTED";} ?>>keine 
            Zuordnung</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rstkunde['idk']?>"<?php if (!(strcmp($row_rstkunde['idk'], $HTTP_POST_VARS['id_kundennummer']))) {echo "SELECTED";} ?>><?php echo substr($row_rstkunde['firma'],0,40)." - ".$row_rstkunde['kundenummer'];?></option>
            <?php
} while ($row_rstkunde = mysql_fetch_assoc($rstkunde));
  $rows = mysql_num_rows($rstkunde);
  if($rows > 0) {
      mysql_data_seek($rstkunde, 0);
	  $row_rstkunde = mysql_fetch_assoc($rstkunde);
  }
?>
          </select>
        </div></td>
    </tr>
  </table>
</form>
  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Projekte<br>
  
<table width="651" border="0" cellpadding="1" cellspacing="1">
  <tr class="value"> 
    <td width="120"><em><strong>Nummer</strong></em></td>
    <td width="142"><strong><em>Typ</em></strong></td>
    <td width="142"><em><strong>Datum</strong></em></td>
    <td width="94"><em><strong>Beschreibung</strong></em></td>
    <td width="103">Zeit [h]</td>
    <td width="168">Stundensatz</td>
    <td width="168">Kosten</td>
    <td width="168">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php if ($row_rst2['id_prj']!=$prjkt){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><?php echo $posgesamtstdprj; ?></strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong>Projektkosten	
      </strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><strong><?php echo $posgesamtprj; ?>&nbsp;&euro;</strong></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
  </tr>
  <?php $posgesamtprj1=$posgesamtprj1+$posgesamtprj;?>
   <?php $posgesamtstdprj1=$posgesamtstdprj1+$posgesamtstdprj;?>
  <?php $posgesamtprj=0;?>
  <?php $posgesamtstdprj=0;?>
  <?php }?>
  <?php   if ($row_rst2['typ']!=$typ){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;	
    </td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><?php echo $posgesamtstdprj1; ?></strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong>Kosten 
      Gruppe</strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><strong><?php echo $posgesamtprj1; ?>&nbsp;&euro;</strong></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
  </tr>
  <?php $posgesamtprj3=$posgesamtprj3+$posgesamtprj1; $posgesamtstdprj3=$posgesamtstdprj3+$posgesamtstdprj1;}?>
  <?php   if ($row_rst2['typ']!=$typ){?>
  <?php $posgesamtprj1=0;?>
  <?php $posgesamtstdprj1=0;?>
  <tr bgcolor="#CCCCCC" > 
    <td colspan="11" nowrap="nowrap"><em><br>
      <img src="picture/s_tbl.png" width="16" height="16"><?php echo htmlentities($row_rst2['typ']); ?>- <?php echo htmlentities($row_rst2['typbeschreibung']); ?> </em></td>
  </tr>
  <?php 	}?>
  <?php   if ($row_rst2['id_prj']!=$prjkt){?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
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
        <?php echo $row_rst2['id_prj']; ?>- <?php echo substr($row_rst2['prj_nummer'],0,20); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><p> 
          <?php if ($row_rst2['qualitaetsverbesserung']==1){ ?>
          <img src="picture/certificate_ok.gif" alt="Qualit&auml;tsverbesserung" width="16" height="16"> 
          <?php }?>
          <?php if ($row_rst2['kostenreduzierung']==1){ ?>
          <img src="picture/iconBestOffer_16x16.gif" alt="Kostenreduzierung"> 
          <?php }?>
          <?php if ($row_rst2['zeichnungsanpassung']==1){ ?>
          <img src="picture/screenshots.gif" alt="Zeichnungsanpassung" width="16" height="16"> 
          <?php }?>
          <?php if ($row_rst2['teileanpassung']==1){ ?>
          <img src="picture/icon_tray_view_close.gif" alt="Teileanpassung" width="20" height="14"> 
          <?php }?>
          <?php if ($row_rst2['fertigungsoptimierung']==1){ ?>
          <img src="picture/stumbleupon.gif" alt="Fertigungsoptimierung" width="16" height="16"> 
          <?php }?>
          <?php if ($row_rst2['servicefreundlichkeit']==1){ ?>
          <img src="picture/iconFdbkBlu_16x16.gif" alt="Servicefreundlichkeit" width="16" height="16"> 
          <?php }?>
          <?php if ($row_rst2['sonstiges']==1){ ?>
          <img src="picture/iconFixedprice_16x16.gif" alt="Sonstiges" width="16" height="16"> 
          <?php }?>
        </p></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="<?php echo $row_rst2['link']; ?>"><strong><?php echo $row_rst2['prj_datum']; ?></strong></a></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><a href="<?php echo $row_rst2['link']; ?>"><strong><?php echo substr($row_rst2['prj_ueberschrift'],0,80)." ..."; ?></strong></a></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/btnHelp.gif" alt="Projektbeschreibung:<?php echo $row_rst2['prj_beschreibung']; ?>" width="16" height="16"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $row_rst2['id_prj']; ?>">
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
    </tr>
    <?php }?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/1.gif" width="20" height="10"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['datum']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo substr($row_rst2['beschreibung'],0,100); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['arbeitszeit']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['stundensatz']; ?></td>
      <?php 
	 $posgesamt=$row_rst2['arbeitszeit']*$row_rst2['stundensatz']; 
	 $posgesamtprj=$posgesamtprj+$posgesamt;
	 
	  $posgesamtstd=$row_rst2['arbeitszeit']; 
	 $posgesamtstdprj=$posgesamtstdprj+$posgesamtstd;
	  
	  ?>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $posgesamt; ?>&euro;&nbsp;</div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    </tr>
  </form>
  <?php
    $typ=$row_rst2['typ'];
	$prjkt=$row_rst2['id_prj'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  <?php if ($row_rst2['id_prj']!=$prjkt){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">Stunden</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><?php echo $posgesamtstdprj; ?></strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong>Projektkosten </strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><strong><?php echo $posgesamtprj; ?>&nbsp;&euro;</strong></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
  </tr>
    <?php $posgesamtstdprj1=$posgesamtstdprj1+$posgesamtstdprj;?>
  <?php $posgesamtstdprj=0;?>
  <?php $posgesamtprj1=$posgesamtprj1+$posgesamtprj;?>
  <?php $posgesamtprj=0;?>
  <?php }?>
  <?php   if ($row_rst2['typ']!=$typ){?>
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp; </td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><?php echo $posgesamtstdprj1; ?></strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong>Kosten Gruppe</strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><strong><?php echo $posgesamtprj1; ?>&nbsp;&euro;</strong></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
  </tr><?php $posgesamtstdprj3=$posgesamtstdprj3+$posgesamtstdprj1;?>
  <?php $posgesamtprj3=$posgesamtprj3+$posgesamtprj1; }?>
    
  <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong>Aufwand</strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong><?php echo $posgesamtstdprj3; ?></strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><strong>Gesamt</strong></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><strong><?php echo $posgesamtprj3; ?>&nbsp;&euro;</strong></div></td>
    <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
  </tr>
</table>

  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
</p>
<p><strong><em>Status:<br>
  </em></strong><img src="picture/st1.gif" width="15" height="15"> Projekt aktiv<br>
  <img src="picture/st1.gif" width="15" height="15"> <img src="picture/b_tipp.png" width="16" height="16"> 
  Projekt aktiv, Priotrit&auml;t Stufe I<br>
  <img src="picture/st1.gif" width="15" height="15"> <img src="picture/b_tipp.png" width="16" height="16"><img src="picture/b_tipp.png" width="16" height="16"> 
  Projekt aktiv, Priorit&auml;t Stufe II<br>
  <img src="picture/st3.gif" width="15" height="15"> Projekt eingestellt bzw. 
  abgeschlossen</p>
<p><strong><em>Typ:</em></strong><br>
  <img src="picture/certificate_ok.gif" width="16" height="16"> Qualit&auml;tsverbesserung<br>
  <img src="picture/iconBestOffer_16x16.gif"> Kostenreduzierung<br>
  <img src="picture/screenshots.gif" width="16" height="16"> Zeichnungsanpassung<br>
  <img src="picture/stumbleupon.gif" width="16" height="16"> Fertigungsoptimierung<br>
  <img src="picture/icon_tray_view_close.gif" width="20" height="14">Teileanpassung<br>
  <img src="picture/iconFdbkBlu_16x16.gif" width="16" height="16"> Servicefreundlichkeit<br>
  <img src="picture/iconFixedprice_16x16.gif" width="16" height="16"> Sonstiges</p>
<p><br>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rstkunde);

?>
</p>
<?php include("footer.tpl.php"); ?>
