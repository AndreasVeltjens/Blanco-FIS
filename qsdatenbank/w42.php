<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php $la = "w42";

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

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE sworkflow SET status=%s WHERE id_work=%s",
                       GetSQLValueString($HTTP_POST_VARS['select'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_work'], "int"));

  mysql_select_db($database_nachrichten, $nachrichten);
  $Result1 = mysql_query($updateSQL, $nachrichten) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form4")) {
  $insertSQL = sprintf("INSERT INTO sworkflowdaten (beschreibung, datum, `user`, id_work) VALUES (%s, %s, %s, %s)",
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
$query_rst2 = "SELECT sworkflow.id_work, sworkflow.name, sworkflow.beschreibung, sworkflow.erstellt, sworkflow.datum, sworkflow.status, sworkflowdaten.id_work_d, sworkflowdaten.beschreibung, sworkflowdaten.datum, sworkflowdaten.`user`, sworkflowdaten.id_work FROM sworkflow, sworkflowdaten WHERE sworkflow.id_work=sworkflowdaten.id_work AND sworkflow.id_work = '$url_id_work' ORDER BY sworkflowdaten.datum desc";
$rst2 = mysql_query($query_rst2, $nachrichten) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rst3 = "SELECT * FROM sworkflow WHERE sworkflow.id_work='$url_id_work'";
$rst3 = mysql_query($query_rst3, $nachrichten) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "w4.php?url_user=".$row_rst1['id'];
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
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="3"><strong>W42-Schichtbuch- Details anzeigen</strong></td>
  </tr>
  <tr> 
    <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
      <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
      <?php echo $errtxt ?></font></strong> 
      <?php } // Show if recordset empty ?></td>
  </tr>
  <tr> 
    <td width="152">Aktionen W&auml;hlen:</td>
    <td width="339"> <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <img src="picture/b_drop.png" width="16" height="16"> 
        <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck">
      </form></td>
    <td width="123" rowspan="4"> <div align="right"> </div></td>
  </tr>
  <tr> 
    <td rowspan="3"><font size="1"><img src="picture/rechnungen.gif" width="70" height="50"></font></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><strong>Schicht: <?php echo $row_rst2['name']; ?></strong></td>
  </tr>
  <tr> 
    <td>Workflownummer: <?php echo $row_rst2['id_work']; ?><br> <br> <?php echo $row_rst2['beschreibung']; ?> </td>
  </tr>
  <tr> 
    <td>Status:</td>
    <td> 
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
    <td>&nbsp;</td>
  </tr>
</table>
  
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
    </select>
    <input name="aendern" type="submit" id="aendern" value="setzen">
    <input type="hidden" name="MM_update" value="form3">
  </p>
</form>
<p><em>neue Aktivit&auml;t anf&uuml;gen</em></p>
<form name="form4" method="POST" action="<?php echo $editFormAction; ?>">
  <textarea name="textfield" cols="100" rows="6" id="textfield"></textarea>
  <input name="hurl_id_work2" type="hidden" id="hurl_id_work2" value="<?php echo $row_rst3['id_work']; ?>">
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['name']; ?>">
  <input name="melden" type="submit" id="melden" value="melden">
  <input type="hidden" name="MM_insert" value="form4">
</form>
<p>&nbsp;</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Projektedetails<br>
  
<table width="651" border="0" cellpadding="0" cellspacing="2">
  <tr class="value"> 
    <td width="91"><em><strong>Datum</strong></em></td>
    <td width="280"><em><strong>Beschreibung/ Aktivit&auml;t</strong></em></td>
    <td width="144"><em><strong>Benutzer</strong></em></td>
    <td width="4">&nbsp;</td>
    <td width="120">&nbsp;</td>
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
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst2['ursache'],100,"<br>",255); ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> <?php echo wordwrap($row_rst2['name'],100,"<br>",255); ?>
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
