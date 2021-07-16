<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "fhm122";
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

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "fhm121.php?url_user=".$row_rst1['id']."&url_id_fhm=".$HTTP_POST_VARS["hurl_id_fhm"]."url_suchtext=".$HTTP_POST_VARS['url_suchtext'];
 if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $GoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $GoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}

if ((isset($HTTP_POST_VARS["save"]))) {
  $updateSQL = sprintf("UPDATE fhm_ereignis SET id_fhmea=%s, id_p=%s, datum=%s, `user`=%s, beschreibung=%s, id_contact=%s, lokz=%s, kosten1=%s, kosten2=%s, firma=%s, ansprechpartner=%s, strasse=%s, plz=%s, ort=%s, land=%s, versandlabel=%s, id_contact=%s, id_ref_fhme=%s WHERE id_fhme=%s",
                       GetSQLValueString($HTTP_POST_VARS['id_fhmea'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['id_p'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['id_contact'], "int"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['kosten1'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['kosten2'], "double"),
                       GetSQLValueString($HTTP_POST_VARS['firma'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ansprechpartner'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['strasse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['plz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['ort'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['land'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['versandlabel'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['id_contact'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['stoerung'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_fhme'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  
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
$query_rst4 = "SELECT fhm_ereignisart.id_fhmea, fhm_ereignisart.name,  fhm_ereignisart.piclink FROM fhm_ereignisart WHERE fhm_ereignisart.lokz=0 ORDER BY fhm_ereignisart.name";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_fhme=$url_id_fhme";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fhm.id_fhm, fhm_gruppe.id_fhm_gruppe, fhm_gruppe.piclink, fhm.id_fhm_gruppe FROM fhm, fhm_gruppe WHERE fhm.id_fhm_gruppe=fhm_gruppe.id_fhm_gruppe and fhm.id_fhm=$url_id_fhm";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst7 = "SELECT fhm_link_pruef.id_fhm, fhm_link_pruef.id_p, fhm_pruef.id_p, fhm_pruef.name FROM fhm_link_pruef, fhm_pruef WHERE fhm_link_pruef.lokz=0 AND fhm_pruef.id_p=fhm_link_pruef.id_p AND fhm_link_pruef.id_fhm=$url_id_fhm ORDER BY fhm_pruef.name";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_fhm='$url_id_fhm' AND fhm_ereignis.lokz=0 ORDER BY fhm_ereignis.datum desc ";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM kundendaten ORDER BY kundendaten.firma";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form1" method="POST">
  <table width="900" border="0" cellspacing="0" cellpadding="0">
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
      <td width="163">Aktionen W&auml;hlen:</td>
      <td width="328"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="123"> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $url_suchtext; ?>">
        <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $url_suchtext; ?>"></td>
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
        <?php echo $row_rst2['name']; ?></strong></td>
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
      <td><?php echo $row_rst2['werk']; ?></td>
    </tr>
    <tr> 
      <td>letzte &Auml;nderung:</td>
      <td><?php echo $row_rst2['lastmod']; ?></td>
    </tr>
    <tr> 
      <td>Inventarnummer</td>
      <td><?php echo $row_rst2['Inventarnummer']; ?></td>
    </tr>
  </table>
  </form>
  
<hr>
  
<form action="<?php echo $editFormAction; ?>" name="form2" method="POST">
  <table width="900" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td><em>Meldung bearbeiten</em></td>
      <td><?php echo $row_rst5['lastmod']; ?> - User: <?php echo $row_rst5['user']; ?></td>
      <td width="144"> <div align="right"><img src="picture/save.gif" width="18" height="18"> 
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td width="181">Art der Meldung</td>
      <td width="375"> <table width="106%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="50%"> 
              <?php
			  $i=0;
do {  
$i=$i+1;?>
              <input <?php if (!(strcmp($row_rst5['id_fhmea'],$row_rst4['id_fhmea']))) {echo "CHECKED";} ?> type="radio" name="id_fhmea" value="<?php echo $row_rst4['id_fhmea']?>"> 
              <?php echo "<img src=\"picture/".$row_rst4['piclink']."\"> ".$row_rst4['name'];?> 
              <br> 
              <?php if (($totalRows_rst4/2)==$i){ ?>
            </td>
            <td width="50%"> 
              <?php }?>
              <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
  
?>
            </td>
          </tr>
        </table>
        <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $url_suchtext; ?>"> 
        <input name="goback" type="hidden" id="goback" value="<?php echo $url_goback; ?>"> 
        <strong> 
        <input name="hurl_id_fhm" type="hidden" id="hurl_id_fhm" value="<?php echo $row_rst2['id_fhm']; ?>">
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_id_user" type="hidden" id="hurl_id_user" value="<?php echo $row_rst1['id']; ?>">
        <input name="hurl_id_fhme" type="hidden" id="hurl_id_fhm3" value="<?php echo $row_rst5['id_fhme']; ?>">
        </strong></td>
      <td><div align="right"> 
          <p>&nbsp;</p>
          <p><a href="pdfetifhm122F.php?url_id_fhme=<?php echo $row_rst5['id_fhme'] ?>&url_user_id=<?php echo $row_rst1['id'] ?>" target="_blank"> 
            </a></p>
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>bei R&uuml;ckmeldung auf Ereignis:</td>
      <td colspan="2"><select name="stoerung" id="stoerung">
          <option value="0" <?php if (!(strcmp(0, $row_rst5['id_ref_fhme']))) {echo "SELECTED";} ?>>keine 
          Rückmeldung</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst8['id_fhme']?>"<?php if (!(strcmp($row_rst8['id_fhme'], $row_rst5['id_ref_fhme']))) {echo "SELECTED";} ?>><?php echo $row_rst8['datum']."-".substr($row_rst8['beschreibung'],0,80);?></option>
          <?php
} while ($row_rst8 = mysql_fetch_assoc($rst8));
  $rows = mysql_num_rows($rst8);
  if($rows > 0) {
      mysql_data_seek($rst8, 0);
	  $row_rst8 = mysql_fetch_assoc($rst8);
  }
?>
        </select></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><p>Beschreibung</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <td colspan="2"><textarea name="textarea" cols="80" rows="10"><?php echo $row_rst5['beschreibung']; ?></textarea></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Datum</td>
      <td><input name="datum" type="text" id="datum" value="<?php echo $row_rst5['datum']; ?>">
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
      <td>Systemdatum: <?php echo $row_rst5['datum']; ?></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>bei Wartung Pr&uuml;fplan :</td>
      <td><select name="id_p" id="id_p">
          <option value="0" <?php if (!(strcmp(0, $row_rst5['id_p']))) {echo "SELECTED";} ?>>keine 
          Angabe</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst7['id_p']?>"<?php if (!(strcmp($row_rst7['id_p'], $row_rst5['id_p']))) {echo "SELECTED";} ?>><?php echo $row_rst7['name']?></option>
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
      <td>Servicepartner</td>
      <td><select name="id_contact" id="id_contact">
          <option value="0" <?php if (!(strcmp(0, $row_rst5['id_contact']))) {echo "SELECTED";} ?>>keine 
          Angabe</option>
        </select></td>
      <td><div align="right"><a href="pdfetifhm1.php?url_id_fhm=<?php echo $row_rst2['id_fhm']?>" target="_blank"><img src="picture/information.gif" alt="Aufkleber drucken" width="16" height="16" border="0" align="absmiddle"> 
          Aufkleber drucken</a></div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Kosten intern</td>
      <td> <input name="kosten1" type="text" id="kosten1" value="<?php echo $row_rst5['kosten1']; ?>"> 
        &euro; </td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Kosten extern</td>
      <td> <input name="kosten2" type="text" id="kosten2" value="<?php echo $row_rst5['kosten2']; ?>"> 
        &euro; </td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td colspan="3"><hr> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><img src="picture/b_drop.png" width="16" height="16" align="absmiddle">L&ouml;schkennzeichen</td>
      <td><input <?php if (!(strcmp($row_rst5['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Vorlage verwenden</td>
      <td colspan="2"> <select name="vorlageidk" id="vorlageidk">
          <option value="" <?php if (!(strcmp("", $HTTP_POST_VARS['vorlageidk']))) {echo "SELECTED";  
		  } ?>>bitte w&auml;hlen</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst9['idk']?>"<?php if (!(strcmp($row_rst9['idk'], $HTTP_POST_VARS['vorlageidk']))) {echo "SELECTED";
		  
		   $row_rst5[firma]=utf8_decode($row_rst9[firma]);
		   $row_rst5[ansprechpartner]=utf8_decode($row_rst9[ansprechpartner]);
		   $row_rst5[strasse]=utf8_decode($row_rst9[strasse]);
		   $row_rst5[plz]=utf8_decode($row_rst9[plz]);
		   $row_rst5[ort]=utf8_decode($row_rst9[ort]);
		   $row_rst5[land]=utf8_decode($row_rst9[land]);
		   $row_rst5[id_contact]=utf8_decode($row_rst9[kundenummer]);
		  
		  
		  } ?>><?php echo utf8_decode($row_rst9['firma'])?></option>
          <?php
} while ($row_rst9 = mysql_fetch_assoc($rst9));
  $rows = mysql_num_rows($rst9);
  if($rows > 0) {
      mysql_data_seek($rst9, 0);
	  $row_rst9 = mysql_fetch_assoc($rst9);
  }
?>
        </select> <input name="vorlage" type="submit" id="vorlage" value="&uuml;bernehmen"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Firma</td>
      <td><input name="firma" type="text" id="firma" value="<?php echo $row_rst5['firma']; ?>" size="60"></td>
      <td><div align="right"><a href="pdfversandetikettGLSFHM.php?url_fhme_id=<?php echo $row_rst5['id_fhme']; ?>" target="_blank"><img src="picture/glslabel.png" alt="Versandlabel GLS drucken" width="120" height="16" border="0"></a></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Ansprechpartner</td>
      <td><input name="ansprechpartner" type="text" id="ansprechpartner" value="<?php echo $row_rst5['ansprechpartner']; ?>" size="60"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Stra&szlig;e</td>
      <td><input name="strasse" type="text" id="strasse" value="<?php echo $row_rst5['strasse']; ?>" size="60"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>PLZ</td>
      <td><input name="plz" type="text" id="plz" value="<?php echo $row_rst5['plz']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Ort</td>
      <td><input name="ort" type="text" id="ort" value="<?php echo $row_rst5['ort']; ?>" size="40"></td>
      <td><div align="right"></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Land</td>
      <td><input name="land" type="text" id="land" value="<?php echo $row_rst5['land']; ?>"></td>
      <td><div align="right"></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>Kundenummer</td>
      <td><input name="id_contact" type="text" id="id_contact" value="<?php echo $row_rst5['id_contact']; ?>"></td>
      <td><div align="right"><img src="picture/save.gif" width="18" height="18"> 
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><img src="picture/orderstatus.gif" width="22" height="12">versendet 
        mit Versandlabel-Nr:</td>
      <td><input name="versandlabel" type="text" id="versandlabel" value="<?php echo $row_rst5['versandlabel']; ?>"></td>
      <td> 
        <?php if ($row_rst5['versandlabel']<>""){ ?>
        <p align="right"><a href="pdfetifhm122.php?url_id_fhme=<?php echo $row_rst5['id_fhme'] ?>&url_user_id=<?php echo $row_rst1['id'] ?>" target="_blank"><img src="picture/15x9_germany.gif" width="15" height="9" border="0"></a><a href="pdfetifhm122.php?url_id_fhme=<?php echo $row_rst5['id_fhme'] ?>&url_user_id=<?php echo $row_rst1['id'] ?>" target="_blank"><img src="picture/information.gif" alt="Dokument ansehen" width="16" height="16" border="0" align="absmiddle"> 
          Druckansicht<br>
          </a><a href="pdfetifhm122F.php?url_id_fhme=<?php echo $row_rst5['id_fhme'] ?>&url_user_id=<?php echo $row_rst1['id'] ?>" target="_blank">F 
          <img src="picture/information.gif" alt="Dokument ansehen" width="16" height="16" border="0" align="absmiddle"> 
          Druckansicht</a> 
          <?php }else {?>
          <font color="#FF0000">Lieferschein gibts erst nach Scan der Paketnummer</font> 
          <?php }?>
        </p></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2">
</form>
 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rst7);

mysql_free_result($rst8);

mysql_free_result($rst9);
?>


  <?php include("footer.tpl.php"); ?>

