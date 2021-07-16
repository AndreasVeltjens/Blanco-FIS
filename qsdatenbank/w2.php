<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php $la = "w2";
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
$oktxt="";
if ((isset($HTTP_POST_VARS["aendern"]))) {
  $updateSQL = sprintf("UPDATE workflow SET status=%s, bereich=%s, ursache=%s , name=%s, von=%s, bis=%s WHERE id_work=%s",
                       GetSQLValueString($HTTP_POST_VARS['select'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['select2'], "int"),
					   GetSQLValueString($HTTP_POST_VARS['ursache'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['name'], "text"),
					     GetSQLValueString($HTTP_POST_VARS['von'], "date"),
						   GetSQLValueString($HTTP_POST_VARS['bis'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_work'], "int"));

  mysql_select_db($database_nachrichten, $nachrichten);
  $Result1 = mysql_query($updateSQL, $nachrichten) or die(mysql_error());
  
  $oktxt="Daten gespeichert.<br>";
  @mail("andreas.veltjens@blanco.de","FIS Workflow-Änderung","Änderung \n$updateSQL","Content-type: Text/plain; charset=US-ASCII");
}

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form4")) {
  $insertSQL = sprintf("INSERT INTO workflowdaten (beschreibung, datum, `user`, id_work) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['textfield'], "text"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_work2'], "int"));

  mysql_select_db($database_nachrichten, $nachrichten);
  $Result1 = mysql_query($insertSQL, $nachrichten) or die(mysql_error());
}
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rst2 = "SELECT workflow.id_work, workflow.name, workflow.beschreibung, workflow.erstellt, workflow.datum, workflow.ursache,  workflow.status, workflow.param, workflow.von, workflow.bis, workflowdaten.id_work_d, workflowdaten.beschreibung, workflowdaten.datum, workflowdaten.`user`, workflowdaten.id_work FROM workflow, workflowdaten WHERE workflow.id_work=workflowdaten.id_work AND workflow.id_work = '$url_id_work' ORDER BY workflowdaten.datum desc";
$rst2 = mysql_query($query_rst2, $nachrichten) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rst3 = "SELECT * FROM workflow WHERE workflow.id_work='$url_id_work'";
$rst3 = mysql_query($query_rst3, $nachrichten) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "w1.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "w11.php?url_user=".$row_rst1['id']."&url_id_work_d=".$HTTP_POST_VARS["hurl_id_work_d"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<table width="830" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr> 
    <td colspan="3"><strong>w2-Workflow - Details</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?><?php echo $oktxt; ?></td>
  </tr>
  <tr> 
    <td width="180" bgcolor="#FFFFFF">Aktionen W&auml;hlen:</td>
    <td width="508" bgcolor="#FFFFFF"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </form></td>
    <td width="42" rowspan="4" bgcolor="#FFFFFF"> <div align="right"><a href="pdfw2.php?url_user=<?php echo $url_user ?>&url_id_work=<?php echo $row_rst2['id_work'] ?>&url_cus_id=<?php echo $row_rst3['erstellt'] ?>" target="_blank"><img src="picture/btn_r7_c1.gif" alt="Druckansicht" width="133" height="26" border="0"></a> 
      </div></td>
  </tr>
  <tr> 
    <td rowspan="3" bgcolor="#FFFFFF"><img src="picture/security.gif" width="52" height="36"></td>
    <td bgcolor="#FFFFFF"><?php echo $oktxt; ?></td>
  </tr>
  <tr> 
    <td><strong>Workflowname: <?php echo $row_rst2['name']; ?> - <?php echo $row_rst3['erstellt'] ?></strong></td>
  </tr>
  <tr> 
    <td>Workflownummer: <?php echo $row_rst2['id_work']; ?><br> <br> <?php echo $row_rst2['beschreibung']; ?> </td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFFF">Status:</td>
    <td bgcolor="#FFFFFF"> 
      <?php if  ($row_rst2['status']==0 ){?>
      <a href="<?php echo $editFormAction; ?>&detail=<?php echo $row_rst2['id_work'] ?>"><img src="picture/st3.gif" width="15" height="15" border="0"></a> 
      <?php } else  { 
        
		if ($row_rst2['status']==3 ){?>
      <a href="<?php echo $editFormAction; ?>&detail=<?php echo $row_rst2['id_work'] ?>"><img src="picture/st1.gif" width="15" height="15" border="0"> 
      </a> 
      <?php } else { ?>
      <a href="<?php echo $editFormAction; ?>&detail=<?php echo $row_rst2['id_work'] ?>"><img src="picture/st2.gif" width="15" height="15" border="0"> 
      </a> 
      <?php } 
		 if (($row_rst2['status']>=1)&& ($row_rst2['status']<3)){; ?>
      <img src="picture/b_tipp.png" width="16" height="16"> 
      <?php }?>
      <?php if (($row_rst2['status']>=2)&& ($row_rst2['status']<3)){; ?>
      <img src="picture/b_tipp.png" width="16" height="16"> 
      <?php }?>
      <?php }?>
    </td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>
<table width="830" bgcolor="#FFFFFF"><tr><td>  
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
        <p> 
          <input name="hurl_id_work" type="hidden" id="hurl_id_work" value="<?php echo $row_rst2['id_work']; ?>">
          <em>neuen Status setzen</em><br>
          <select name="select">
            <option value="0" <?php if (!(strcmp(0, $row_rst2['status']))) {echo "SELECTED";} ?>>offen</option>
            <option value="1" <?php if (!(strcmp(1, $row_rst2['status']))) {echo "SELECTED";} ?>>Arbeitsbegin</option>
            <option value="2" <?php if (!(strcmp(2, $row_rst2['status']))) {echo "SELECTED";} ?>>Transport 
            / Umsetzung offen</option>
            <option value="3" <?php if (!(strcmp(3, $row_rst2['status']))) {echo "SELECTED";} ?>>erledigt</option>
            <option value="4" <?php if (!(strcmp(4, $row_rst2['status']))) {echo "SELECTED";} ?>>Wiedervorlage</option>
          </select>
          <input type="hidden" name="MM_update" value="form3">
          <br>
          <br>
          <em>Bereich - Bezeichnung neu zuordnen</em><br>
          <select name="select2">
            <option value="1" <?php if (!(strcmp(1, $row_rst3['bereich']))) {echo "SELECTED";} ?>>FIS-Datenbank</option>
            <option value="2" <?php if (!(strcmp(2, $row_rst3['bereich']))) {echo "SELECTED";} ?>>Montage 
            Kostenstelle 61140</option>
            <option value="3" <?php if (!(strcmp(3, $row_rst3['bereich']))) {echo "SELECTED";} ?>>Sch&auml;umen+Schwei&szlig;en 
            Kostenstelle 61130</option>
            <option value="4" <?php if (!(strcmp(4, $row_rst3['bereich']))) {echo "SELECTED";} ?>>Thermoformen 
            Kostenstelle 61110</option>
            <option value="5" <?php if (!(strcmp(5, $row_rst3['bereich']))) {echo "SELECTED";} ?>>Zeiterfassung 
            - Urlaubsantrag - Freischicht</option>
            <option value="0" <?php if (!(strcmp(0, $row_rst3['bereich']))) {echo "SELECTED";} ?>>allgemeine 
            Betriebsorganisation</option>
            <option value="6" <?php if (!(strcmp(6, $row_rst3['bereich']))) {echo "SELECTED";} ?>>QS 
            Retourenabwicklung</option>
          </select>
          <input name="name" type="text" value="<?php echo $row_rst2['name']; ?>">
          <input name="aendern" type="submit" id="aendern" value="setzen">
          <br>
          <em>Fehlerursache</em> <br>
          <textarea name="ursache" cols="100" rows="2"><?php echo $row_rst2['ursache']; ?></textarea>
          <br>
          Link &ouml;ffnen: <a href="<?php echo str_replace("-","&",$row_rst2['param']); ?>" target="_blank"><?php echo str_replace("-","&",$row_rst2['param']); ?></a><br>
          von <?php echo $row_rst2['von']; ?> bis <?php echo $row_rst2['bis']; ?> 
          <input name="von" type="text" id="von" value="<?php echo $row_rst2['von']; ?>">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'von\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
          bis 
          <input name="bis" type="text" id="bis" value="<?php echo $row_rst2['bis']; ?>">
          <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form3\', \'bis\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script>
        </p>
  </form></td></tr></table>
  <table width="830" bgcolor="#CCCCFF"><tr><td>
<p><em>neue Aktivit&auml;t anf&uuml;gen</em></p>
<form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
  <textarea name="textfield" cols="100" rows="6" id="textfield"></textarea>
  <input name="hurl_id_work2" type="hidden" id="hurl_id_work2" value="<?php echo $row_rst3['id_work']; ?>">
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['name']; ?>">
  <input name="melden" type="submit" id="melden" value="melden">
  <input type="hidden" name="MM_insert" value="form4">
</form>
</td></tr></table>
<p>&nbsp;</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Projektdetails<br>
  
<table width="830" border="0" cellpadding="0" cellspacing="2">
  <tr class="value"> 
    <td width="116"><em><strong>Datum</strong></em></td>
    <td width="357"><em><strong>Beschreibung/ Aktivit&auml;t</strong></em></td>
    <td width="184"><em><strong>Benutzer</strong></em></td>
    <td width="42">&nbsp;</td>
    <td width="119">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php   if ($row_rst2['typ']!=$typ){?>
  <tr bgcolor="#CCCCCC" > 
    <td colspan="8" nowrap="nowrap"><em><br>
      </em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst2['datum']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst2['beschreibung'],100,"<br>",255); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php echo $row_rst2['user']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_prj_id" type="hidden" id="hurl_prj_id" value="<?php echo $row_rst2['id_prj']; ?>">
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
    </tr>
  </form>
  <?php
    $typ=$row_rst2['typ'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
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

?>
<?php include("footer.tpl.php"); ?>
