<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
   $la = "fhm121";
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
if ((isset($goback))&& $goback!=""){$GoTo = $goback.".php";}else{
  $GoTo = "fhm1.php";
  }
$GoTo = $GoTo."?url_user=".$row_rst1['id']."&url_suchtext=".$HTTP_POST_VARS['suchtext'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}


$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$GoTo = "fhm122.php?url_user=".$row_rst1['id']."&url_id_fhm=".$HTTP_POST_VARS["hurl_id_fhm"]."&url_id_fhme=".$HTTP_POST_VARS["hurl_id_fhme"]."&url_suchtext=".$HTTP_POST_VARS['suchtext']."&goback=".$HTTP_POST_VARS['goback'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}


if ((isset($HTTP_POST_VARS["speichern"]))) {
  $insertSQL = sprintf("INSERT INTO fhm_ereignis (id_fhm, id_fhmea, id_p, datum, `user`, beschreibung, ereigniszeitstempel, id_ref_fhme) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_fhm'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['id_fhmea'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['select4'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['datum']." ".date("H:i:s",time()), "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
					   "now()",
                       GetSQLValueString($HTTP_POST_VARS['stoerung'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
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
$query_rst4 = "SELECT fhm_ereignisart.id_fhmea, fhm_ereignisart.name, fhm_ereignisart.piclink FROM fhm_ereignisart WHERE fhm_ereignisart.lokz=0 ORDER BY fhm_ereignisart.name";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

if (isset($_POST['selectereignisart']) && $_POST['selectereignisart']>0) {$suchtxt="AND fhm_ereignis.id_fhmea='".$_POST['selectereignisart']."' ";}
if (isset($_POST['datum2']) && $_POST['datum2']>0) {$suchtxtdate="AND fhm_ereignis.datum>'".$_POST['datum2']."' ";} else {$_POST['datum2']=date("Y-m-d",time()-60*60*24*365); }

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT fhm_ereignis.id_fhme, fhm_ereignis.id_fhmea, fhm_ereignis.datum,fhm_ereignis.versandlabel, fhm_ereignis.`user`, fhm_ereignis.lastmod, fhm_ereignis.beschreibung, 
fhm_ereignis.id_contact FROM fhm_ereignis 
WHERE fhm_ereignis.lokz=0 AND fhm_ereignis.id_fhm=$url_id_fhm $suchtxt $suchtextdate
ORDER BY fhm_ereignis.datum desc, fhm_ereignis.ereigniszeitstempel desc,  fhm_ereignis.id_fhme desc";
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
$query_rst8 = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_fhm='$url_id_fhm' AND fhm_ereignis.lokz=0 ORDER BY fhm_ereignis.datum desc LIMIT 0,100";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM `user`";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
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
      <td width="199">Aktionen W&auml;hlen:</td>
      <td width="497"> <img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="204"> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $url_suchtext; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><strong>ID</strong></td>
      <td><strong><?php echo $row_rst2['id_fhm']; ?> 
        <input name="hurl_id_fhm" type="hidden" id="hurl_id_fhm" value="<?php echo $row_rst2['id_fhm']; ?>">
        </strong></td>
      <td><div align="right">
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <strong> <a href="pdfetifhm1.php?url_id_fhm=<?php echo $row_rst2['id_fhm']?>" target="_blank"><img src="picture/information.gif" alt="Aufkleber drucken" width="16" height="16" border="0" align="absmiddle"> 
          Aufkleber drucken</a> </strong></div></td>
    </tr>
    <tr> 
      <td>Name und Seriennummer</td>
      <td><strong> 
        <?php echo $row_rst2['name']; ?> - SN: <?php echo $row_rst2['sn']; ?></strong></td>
      <td rowspan="5"><div align="right"><img src="picture/<?php echo $row_rst6['piclink']; ?>"></div></td>
    </tr>
    <tr> 
      <td>Gruppe</td>
      <td>
          <?php
do {  
    if (!(strcmp($row_rst3['id_fhm_gruppe'], $row_rst2['id_fhm_gruppe']))) {echo utf8_encode($row_rst3['name']);}
} while ($row_rst3 = mysql_fetch_assoc($rst3));
  $rows = mysql_num_rows($rst3);
  if($rows > 0) {
      mysql_data_seek($rst3, 0);
	  $row_rst3 = mysql_fetch_assoc($rst3);
}
?>
        - <?php echo $row_rst2['suchgruppierung']; ?></td>
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
  <?php 
require_once('./libraries/grab_globals.lib.php');
$js_to_run = 'tbl_change.js';
?>
  <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="900" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td><em><strong><em><img src="picture/b_insrow.png" width="16" height="16"></em></strong> 
        neue Meldung erfassen:</em></td>
      <td width="377">&nbsp;</td>
      <td width="320"><div align="right"><strong>
          <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $url_suchtext; ?>">
          <input name="goback" type="hidden" id="goback" value="<?php echo $url_goback; ?>">
          <input name="hurl_id_user" type="hidden" id="hurl_id_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_fhme" type="hidden" id="hurl_id_fhme" value="<?php echo $row_rst5['id_fhme']; ?>">
          <input name="hurl_id_fhm" type="hidden" id="hurl_id_fhm" value="<?php echo $row_rst2['id_fhm']; ?>">
          </strong><img src="picture/save.gif" width="18" height="18"> 
          <input name="speichern" type="submit" id="speichern" value="speichern">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td width="203">Art der Meldung</td>
      <td> 
        <?php
			  $i=0;
do {  
$i=$i+1;?>
        <input <?php if (!(strcmp($row_rst5['id_fhmea'],$row_rst4['id_fhmea']))) {echo "CHECKED";} ?> type="radio" name="id_fhmea" value="<?php echo $row_rst4['id_fhmea']?>"> 
        <?php echo "<img src=\"picture/".$row_rst4['piclink']."\"> ".$row_rst4['name'];?> 
        <br> 
        <?php if (round($totalRows_rst4/2,0)==$i){ ?>
      </td>
      <td> 
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
    <tr bgcolor="#CCCCFF"> 
      <td>bei R&uuml;ckmeldung auf Ereignis:</td>
      <td colspan="2"><select name="stoerung" id="stoerung">
          <option value="0" <?php if (!(strcmp(0, 0))) {echo "SELECTED";} ?>>keine 
          Rückmeldung</option>
          <?php
do {  
?>
          <option value=" <?php echo $row_rst8['id_fhme']?>" ><?php echo $row_rst8['datum']."-".substr($row_rst8['beschreibung'],0,80);?></option>
          <?php
} while ($row_rst8 = mysql_fetch_assoc($rst8));
  $rows = mysql_num_rows($rst8);
  if($rows > 0) {
      mysql_data_seek($rst8, 0);
	  $row_rst8 = mysql_fetch_assoc($rst8);
  }
?>
          <?php
do {  
?>
          <option value="<?php echo $row_rst8['id_fhme']?>"<?php if (!(strcmp($row_rst8['id_fhme'], 0))) {echo "SELECTED";} ?>><?php echo $row_rst8['id_fhme']?></option>
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
      <td colspan="2"><textarea name="textarea" cols="80" rows="10"></textarea></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>Datum</td>
      <td><input name="datum" type="text" id="datum"> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form2\', \'datum\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        jjjj-mm-tt </td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>bei Wartung Pr&uuml;fplan :</td>
      <td colspan="2"><select name="select4">
          <option value="0">keine Angabe</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst7['id_p']?>"><?php echo utf8_decode($row_rst7['name']);?></option>
          <?php
} while ($row_rst7 = mysql_fetch_assoc($rst7));
  $rows = mysql_num_rows($rst7);
  if($rows > 0) {
      mysql_data_seek($rst7, 0);
	  $row_rst7 = mysql_fetch_assoc($rst7);
  }
?>
        </select></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
</form>
<p><em>erfasste Meldungen: (Sortierung letzte Meldung steht zuerst)</em></p>
<table width="900" border="0" cellpadding="0" cellspacing="0">
  <tr bgcolor="#CCCCFF"> 
    <td width="231">nach Ereignissen suchen</td>
    <td width="495"><form name="form4" method="post" action="">
        <div align="right">ab 
          <input name="datum2" type="text" id="datum2" value="<?php echo $_POST['datum2']; ?>"> 
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form4\', \'datum2\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          jjjj-mm-tt 
          <select name="selectereignisart" id="selectereignisart">
		  <option value="0">alle anzeigen</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rst4['id_fhmea']?>"<?php if (!(strcmp($row_rst4['id_fhmea'], $_POST['selectereignisart']))) {echo "SELECTED";} ?>><?php echo $row_rst4['name']?></option>
            <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
          </select>
          <input type="submit" name="suchen" value="suchen">
        </div>
      </form></td>
    <td width="4">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>

<script src="libraries/functions.js" type="text/javascript" language="javascript"></script>
<?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
<table width="900" border="0" cellpadding="0" cellspacing="1">
  <tr bgcolor="#CCCCCC"> 
    <td width="79">Datum</td>
    <td width="124">Art</td>
    <td width="117">Beschreibung</td>
    <td width="158">letzte &Auml;nderung</td>
    <td width="140">User</td>
    <td width="133">Versand</td>
    <td width="141"><div align="right"> </div></td>
  </tr>
  <?php do { ?>
  <?php 
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst10 = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_ref_fhme='$row_rst5[id_fhme]'";
$rst10 = mysql_query($query_rst10, $qsdatenbank) or die(mysql_error());
$row_rst10 = mysql_fetch_assoc($rst10);
$totalRows_rst10 = mysql_num_rows($rst10);
  
  ?>
  
  
  
  <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 3, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 3, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 3, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td     bgcolor="#EEEEEE" nowrap="nowrap" ><?php echo $row_rst5['datum']; ?></td>
      <td     bgcolor="#EEEEEE" nowrap="nowrap" > 
      <?php if  ($totalRows_rst10>0){ ?> <img src="picture/fertig.gif" width="15" height="15"> <?php }?>
	  
	          <?php
do {  
?>
        <?php if (!(strcmp($row_rst4['id_fhmea'], $row_rst5['id_fhmea']))) {echo "<img src=\"picture/".$row_rst4['piclink']."\">".wordwrap($row_rst4['name'],12,"<br>",24);} ?>
        <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?></select>
        </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap" ><?php echo wordwrap(nl2br($row_rst5['beschreibung']),30,"<br>",255); ?> 
        <input name="suchtext" type="hidden" id="suchtext" value="<?php echo $url_suchtext; ?>"> 
        <input name="goback" type="hidden" id="suchtext3" value="<?php echo $goback; ?>"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst5['lastmod'],10,"<br>",16); ?> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_id_fhm" type="hidden" id="hurl_id_fhm" value="<?php echo $row_rst2['id_fhm']; ?>"></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap" > 
        <img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst9['id'], $row_rst5['user']))) {echo $row_rst9['name'];}?> 
        <?php
} while ($row_rst9 = mysql_fetch_assoc($rst9));
  $rows = mysql_num_rows($rst9);
  if($rows > 0) {
      mysql_data_seek($rst9, 0);
	  $row_rst9 = mysql_fetch_assoc($rst9);
  }
?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap" ><div align="center"><a href="pdfetifhm122.php?url_id_fhme=<?php echo $row_rst5['id_fhme'] ?>&url_user_id=<?php echo $row_rst1['id'] ?>" target="_blank"><img src="picture/information.gif" alt="Dokument ansehen" width="16" height="16" border="0" align="absmiddle"></a><?php echo $row_rst5['versandlabel']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap" > 
        <div align="right"> 
          <input name="hlokz" type="hidden" id="hlokz" value="1">
          <input name="hurl_id_fhme" type="hidden" id="hurl_id_fhme" value="<?php echo $row_rst5['id_fhme']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> 
          <input name="edit" type="submit" id="edit" value="Bearbeiten">
        </div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form3">
  </form>
  <?php } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
</table>
<?php } // Show if recordset not empty ?>




<?php if ($totalRows_rst5 == 0) { // Show if recordset empty ?>
<font color="#FF0000">keine Meldungen angelegt.</font> 
<?php } // Show if recordset empty ?>

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
</p>

  <?php include("footer.tpl.php"); ?>

