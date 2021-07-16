<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php  $la = "infomat1";
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


if (isset($HTTP_POST_VARS['suchtext'])) {
	$suchtxt=" WHERE artikeldaten.Bezeichnung like '%".$HTTP_POST_VARS['suchtext']."%' OR artikeldaten.Nummer like '%".$HTTP_POST_VARS['suchtext']."%' ";
	}
else{
	$suchtxt="";
	}
if (!isset($HTTP_POST_VARS['seriennummer'])){$HTTP_POST_VARS[seriennummer]=0;}

	
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM artikeldaten $suchtxt ORDER BY artikeldaten.Bezeichnung";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM artikeldaten WHERE artikeldaten.aktiviert=1 AND artikeldaten.Kontierung like 'Fertig%' ORDER BY artikeldaten.Bezeichnung";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM artikelversionen WHERE artikelversionen.absn>'$HTTP_POST_VARS[seriennummer]' AND artikelversionen.lokz=0 AND artikelversionen.artikelid='$HTTP_POST_VARS[typ]' ORDER BY artikelversionen.Datum";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT * FROM fehler, linkfehlerartikel WHERE linkfehlerartikel.fid =fehler.fid AND fehler.lokz=0 AND linkfehlerartikel.artikelid='$HTTP_POST_VARS[typ]' ORDER BY fehler.fkurz";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if (isset($HTTP_POST_VARS["stamm"])) {
$updateGoTo = "la202.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Sprung auf LA202. Bitte erneut versuchen.";}

if (isset($HTTP_POST_VARS["edit"])) {
$updateGoTo = "la21.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Sprung auf LA21. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["add"])) ) {
$updateGoTo = "la23.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}

if ((isset($HTTP_POST_VARS["virtuell"])) ) {
$updateGoTo = "la25.php?url_user=".$row_rst1['id']."&url_artikelid=".$HTTP_POST_VARS["hurl_artikelid"];
    header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Sprintf)";}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT * FROM `user`";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);

include("function.tpl.php");
include("header.tpl.php");
/* include("menu.tpl.php"); */
?>
<form name="form2" method="post" action="">
  <table width="702" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong>Info Materialdaten - infomat1</strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="139">Aktionen W&auml;hlen:</td>
      <td width="302"><img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="261"> <div align="right"></div></td>
    </tr>
  </table>
  <table width="1200" border="1" cellpadding="1" cellspacing="0" bordercolor="#999999">
    <tr> 
      <td width="300" bgcolor="#F0F0FF"><strong>Ger&auml;tidentifikation</strong></td>
      <td width="300" bgcolor="#D5D5FF"><strong>Fertigungsversionen</strong></td>
      <td width="300" bgcolor="#B3B3FF"><strong>Fehlerbilder/ Diagnose</strong></td>
      <td width="300" bgcolor="#8484FF"><strong>Reparatur/ Umbau- Ersatzteilempfehlung</strong></td>
    </tr>
    <tr> 
      <td>
	  
	  <?php include('infomat.suche.php'); ?>
	  
	  <table width="300" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td colspan="2"><em><font color="#006600"><img src="picture/arrowClose.gif" width="8" height="9"></font></em>Komponenten&uuml;bersicht</td>
          </tr>
          <tr> 
            <td width="99">&nbsp;</td>
            <td width="201">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"> <?php include('infomat.fd.php'); ?> </td>
          </tr>
          <tr> 
            <td height="20">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>aktuelle Version des Ger&auml;tes</td>
            <td><input name="version" type="text" id="version" value="<?php echo $HTTP_POST_VARS['version']; ?>"> 
            </td>
          </tr>
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><em><font color="#006600"><img src="picture/arrowClose.gif" width="8" height="9"></font></em>Reparaturen</td>
          </tr>
          <tr> 
            <td colspan="2"> 
              <?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst52 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fmartikelid, fehlermeldungen.fmuser, fehlermeldungen.fm1datum, fehlermeldungen.fmsn, fehlermeldungen.fm3, fehlermeldungen.fm6, fehlermeldungen.fm1notes FROM fehlermeldungen WHERE fehlermeldungen.fmartikelid='$HTTP_POST_VARS[typ]' and fehlermeldungen.fmsn='$HTTP_POST_VARS[seriennummer]'  ORDER BY fehlermeldungen.fmdatum desc";
$rst52 = mysql_query($query_rst52, $qsdatenbank) or die(mysql_error());
$row_rst52 = mysql_fetch_assoc($rst52);
$totalRows_rst52 = mysql_num_rows($rst52);


?>
              <?php if ($totalRows_rst52 > 0) { // Show if recordset not empty ?>
              <table width="300" border="0" cellpadding="0" cellspacing="1">
                <tr> 
                  <td width="50"><font size="1">Datum</font></td>
                  <td width="20"><font size="1">FMID</font></td>
                  <td><font size="1">User</font></td>
                </tr>
                <?php do { ?>
                <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                  <td bgcolor="#EEEEEE" nowrap="nowrap"> 
                    <font size="1"><img src="picture/folder_open.gif" width="16" height="16"><?php echo $row_rst52['fm1datum']; ?> </font></td>
                  <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><a href="la42.php?url_user=<?php echo $url_user ;?>&url_fmid=<?php echo $row_rst52['fmid']; ?>" target="_blank"><?php echo $row_rst52['fmid']; ?></a></font></td>
                  <td nowrap="nowrap"    bgcolor="#EEEEEE"><font size="1"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
                    <?php
do {  
?>
                    <?php if (!(strcmp($row_rstuser['id'], $row_rst52['fmuser']))) {echo $row_rstuser['name'];}?>
                    <?php
} while ($row_rstuser = mysql_fetch_assoc($rstuser));
  $rows = mysql_num_rows($rstuser);
  if($rows > 0) {
      mysql_data_seek($rstuser, 0);
	  $row_rstuser = mysql_fetch_assoc($rstuser);
  }
?>
                    </font><font size="1">&nbsp;</font></td>
                  <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
                  <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
                </tr>
                <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                  <td bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
                  <td colspan="4" nowrap="nowrap"    bgcolor="#EEEEEE"><font size="1"><?php echo $row_rst52['fm1notes']; ?></font></td>
                </tr>
                <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                  <td bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><img src="picture/1.gif" >Fehler</font></td>
                  <td colspan="4" nowrap="nowrap"    bgcolor="#EEEEEE"> 
                    <font size="1"> 
                    <?php 
	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst53 = "SELECT fehler.fid, fehler.fkurz, fehler.fname, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid FROM fehler, linkfehlerfehlermeldung WHERE linkfehlerfehlermeldung.fmid=$row_rst52[fmid] and linkfehlerfehlermeldung.fid =fehler.fid ";
$rst53 = mysql_query($query_rst53, $qsdatenbank) or die(mysql_error());
$row_rst53 = mysql_fetch_assoc($rst53);
$totalRows_rst53 = mysql_num_rows($rst53);


	  ?>
                    </font> <table width="130" border="0" cellpadding="0" cellspacing="0">
                      <?php do { ?>
                      <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                        <td width="50" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><font size="1"><img src="picture/<?php if (!(strcmp($row_rst53['fm6'],1))) {echo "st1.gif";}else{echo "st3.gif";} ?>" width="15" height="15">- 
                            <?php echo $row_rst53['fkurz']; ?> </font></div></td>
                        <td width="100" nowrap="nowrap"  bgcolor="#EEEEEE"   > 
                          <font size="1"><?php echo $row_rst53['fname']; ?> </font></td>
                      </tr>
                      <?php } while ($row_rst53 = mysql_fetch_assoc($rst53)); ?>
                    </table></td>
                </tr>
                <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                  <td bgcolor="#EEEEEE" nowrap="nowrap"><font size="1"><img src="picture/1.gif" >Reparatur</font></td>
                  <td colspan="4" nowrap="nowrap"    bgcolor="#EEEEEE"> <font size="1"> 
                    <?php 
	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT komponenten.id_ruck, komponenten.kompnr, komponenten.anzahl, komponenten.lagerort, komponenten.lokz FROM komponenten WHERE komponenten.id_fm = $row_rst52[fmid] and  komponenten.lokz=0 ORDER BY komponenten.id_ruck DESC";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);
	  
	  ?>
                    <?php if ($totalRows_rst9 > 0) { // Show if recordset not empty ?>
                    <?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM komponenten_vorlage";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);






?>
                    <table width="130" border="0" cellpadding="0" cellspacing="0">
                      <?php do { ?>
                      <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
                        <td width="40" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><font size="1"><?php echo $row_rst9['kompnr']; ?></font></div></td>
                        <td width="105" nowrap="nowrap"  bgcolor="#EEEEEE"   > 
                          <div align="left"> <font size="1"> 
                            <?php do {  
?>
                            <?php if (!(strcmp($row_rst8['kompnr'], $row_rst9['kompnr']))) {echo htmlentities($row_rst8['kompname']);}?>
                            <?php
} while ($row_rst8 = mysql_fetch_assoc($rst8));
  $rows = mysql_num_rows($rst8);
  if($rows > 0) {
      mysql_data_seek($rst8, 0);
	  $row_rst8 = mysql_fetch_assoc($rst8);
  }
?>
                            </font></div></td>
                        <td width="40" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><font size="1"><?php echo $row_rst9['anzahl']; ?> ST </font></div></td>
                        <td width="20" nowrap="nowrap"  bgcolor="#EEEEEE"   ><div align="left"><font size="1"><?php echo $row_rst9['lagerort']; ?></font></div></td>
                      </tr>
                      <input type="hidden" name="MM_update2" value="form3">
                      <?php } while ($row_rst9 = mysql_fetch_assoc($rst9)); ?>
                    </table>
                    <?php } // Show if recordset not empty ?>
                    <p> <font size="1"> 
                      <?php if ($totalRows_rst9 == 0) { // Show if recordset empty ?>
                      <font color="#FF0000">noch keine Teile r&uuml;ckmeldet.</font> 
                      <?php } // Show if recordset empty ?>
                      </font></p>
                    </font></td>
                </tr>
                <?php } while ($row_rst52 = mysql_fetch_assoc($rst52)); ?>
              </table>
              <?php } // Show if recordset not empty ?> <?php if ($totalRows_rst52 == 0) { // Show if recordset empty ?>
              <p><font color="#FF0000">Keine Reparaturdaten gefunden.</font></p>
              <?php } // Show if recordset empty ?> </td>
          </tr>
        </table></td>
      <td>
	  
	  <?php
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM artikelversionen WHERE artikelversionen.absn<='$HTTP_POST_VARS[seriennummer]' AND artikelversionen.lokz=0 AND artikelversionen.artikelid='$HTTP_POST_VARS[typ]' ORDER BY artikelversionen.Datum";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);
 ?>
<?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
        <em><img src="picture/arrowClose.gif" width="8" height="9">Liste der Fertigungsversionen</em> 
        <table width="300" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="70"><font size="1">Version</font></td>
            <td width="230"><font size="1">Beschreibung</font></td>
          </tr>
          <?php do { ?>
          <tr bgcolor="#D5D5FF"> 
            <td><font size="1">&nbsp;<?php echo $row_rst4['version']; ?></font></td>
            <td><font size="1">&nbsp;<?php echo $row_rst4['beschreibung']; ?></font></td>
          </tr>
          <?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
        </table>
        <?php } // Show if recordset not empty ?>
        <?php if ($totalRows_rst4 == 0) { // Show if recordset empty ?>
        <p><font color="#FF0000">Keine Fertigungsversion</font><font color="#FF0000">en 
          gefunden.</font></p>
        <?php } // Show if recordset empty ?>
		
		
		<br>
        <?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
        
        <em><font color="#006600"><img src="picture/arrowClose.gif" width="8" height="9"><font color="#009900">Liste 
        der Produkt-Updates</font></font></em> 
        <table width="300" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="77"><font size="1">Version</font></td>
            <td width="223"><font size="1">Beschreibung</font></td>
          </tr>
          <?php do { ?>
          <tr bgcolor="#D5D5FF"> 
            <td><font size="1">&nbsp;<?php echo $row_rst5['version']; ?></font></td>
            <td><font size="1">&nbsp;<?php echo $row_rst5['beschreibung']; ?></font></td>
          </tr>
          <?php } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
        </table>
        <?php } // Show if recordset not empty ?>
        <?php if ($totalRows_rst5 == 0) { // Show if recordset empty ?>
        <p><font color="#FF0000">Keine Updates verf&uuml;gbar.</font></p>
        <?php } // Show if recordset empty ?>
      </td>
      <td>
        <?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
        <em><img src="picture/arrowClose.gif" width="8" height="9">Liste der Fehler</em> 
        <table width="300" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="#B3B3FF"> 
            <td width="70"><font size="1">Kurzzeichen</font></td>
            <td width="230"><font size="1">Beschreibung</font></td>
          </tr>
          <?php do { ?>
          <tr> 
            <td><font size="1">
              <input type="checkbox" name="checkbox1" value="<?php echo $row_rst6['fkurz']; ?>">
              &nbsp;<?php echo $row_rst6['fkurz']; ?></font></td>
            <td><font size="1">&nbsp;<?php echo $row_rst6['fname']; ?></font></td>
          </tr>
          <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
        </table>
        <?php } // Show if recordset not empty ?>
        <?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
        <p><font color="#FF0000">Keine Fehlerdefinitionen gefunden.</font></p>
        <?php } // Show if recordset empty ?>
      </td>
      <td><p><em><img src="picture/arrowClose.gif" width="8" height="9">Liste 
          der verf&uuml;barer Ersatzteile</em></p>
        <p><?php include('infomat.rsl.php'); ?></p>
        <p>&nbsp;</p>
        <p><em><img src="picture/arrowClose.gif" width="8" height="9">Liste Reparaturleitfaden</em></p><?php include('infomat.rlf.php'); ?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>

<p>&nbsp;</p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rst52);

mysql_free_result($rstuser);
?>
<?php include("footer.tpl.php"); ?>
