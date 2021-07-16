<?php require_once('Connections/qsdatenbank.php'); ?><?php $la = "co13";
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

if ((isset($HTTP_POST_VARS["save"]) )) {


if ($HTTP_POST_VARS['datum']==""){$datumaktuell = "now()";
}else{ $datumaktuell= GetSQLValueString($HTTP_POST_VARS['datum'], "date");

}

  $updateSQL = sprintf("UPDATE produktionsprog SET mog1=%s, moa1=%s, mog2=%s, moa2=%s, mog3=%s, moa3=%s, dig1=%s, dia1=%s, dig2=%s, dia2=%s, dig3=%s, dia3=%s, mig1=%s, mia1=%s, mig2=%s, mia2=%s, mig3=%s, mia3=%s, dog1=%s, doa1=%s, dog2=%s, doa2=%s, dog3=%s, doa3=%s, frg1=%s, fra1=%s, frg2=%s, fra2=%s, frg3=%s, fra3=%s, sag1=%s, saa1=%s, sag2=%s, saa2=%s, sag3=%s, saa3=%s, sog1=%s, soa1=%s, sog2=%s, soa2=%s, sog3=%s, soa3=%s, bemerkung=%s , stueck=%s, paletten=%s, fuser=%s WHERE pp_id=%s ",
                       
					   GetSQLValueString($HTTP_POST_VARS['mog1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['moa1'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['mog2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['moa2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['mog3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['moa3'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['dig1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['dia1'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['dig2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['dia2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['dig3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['dia3'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['mig1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['mia1'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['mig2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['mia2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['mig3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['mia3'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['dog1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['doa1'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['dog2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['doa2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['dog3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['doa3'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['frg1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['fra1'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['frg2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['fra2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['frg3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['fra3'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['sag1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['saa1'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['sag2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['saa2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['sag3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['saa3'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['sog1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['soa1'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['sog2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['soa2'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['sog3'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['soa3'], "text"),
					   
					   GetSQLValueString($HTTP_POST_VARS['bemerkung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['stueck'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['paletten'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_pp_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "co12.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM arbeitsplatz WHERE arbeitsplatz.arb_id='$url_arb_id'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM produktionsprog WHERE produktionsprog.pp_id='$url_pp_id'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst9 = "SELECT * FROM `user`";
$rst9 = mysql_query($query_rst9, $qsdatenbank) or die(mysql_error());
$row_rst9 = mysql_fetch_assoc($rst9);
$totalRows_rst9 = mysql_num_rows($rst9);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "co12.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?> 
<style type="text/css">
<!--
.roteliste {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FF0000;
	background-color: #FFDFDF;
	border: #FF0000;
}
-->
</style>


<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> <br> <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="152">Aktionen W&auml;hlen:</td>
      <td width="340"> <img src="picture/error.gif" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
        <input type="submit" name="Submit" value="Eingaben zur&uuml;cksetzen"></td>
      <td width="122"> <div align="right"> 
          <input name="save" type="submit" id="save" value="Speichern">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlte Arbeitsplatz</td>
      <td><strong><?php echo $row_rst2['arb_name']; ?></strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_artikelid" type="hidden" id="hurl_artikelid2" value="<?php echo $row_rst2['artikelid']; ?>"> 
        <input name="hurl_pp_id" type="hidden" id="hurl_pp_id" value="<?php echo $row_rst3['pp_id']; ?>"> 
        <input name="hurl_arb_id" type="hidden" id="hurl_arb_id" value="<?php echo $row_rst2['arb_id']; ?>"></td>
    </tr>
    <tr> 
      <td rowspan="4"><a href="../documents/materialbilder/<?php echo $row_rst3['nummer'] ?>.png" target="_blank"><img src="../documents/materialbilder/<?php echo $row_rst3['nummer'] ?>.png" alt="Materialbeispiel" width="180" height="140" border="0" align="absmiddle"></a></td>
      <td><strong>Material: <?php echo $row_rst3['nummer']; ?></strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2"><strong>Bezeichnung: <?php echo $row_rst3['bezeichnung']; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo $row_rst3['beschreibung']; ?></td>
    </tr>
    <tr> 
      <td> <p>Mindestbestand: <strong><font color="#FF0000"><?php echo $row_rst3['palettenL']; ?> St&uuml;ck</font></strong><br>
          Maximalbestand:<font color="#006600"><strong><?php echo $row_rst3['avis']; ?> St&uuml;ck</strong></font> </p></td>
      <td><p>g&uuml;ltig ab:</p>
        <p> 
          <input name="textfield" type="text" value="<?php echo $row_rst3['datum'] ?>">
        </p></td>
    </tr>
  </table>
  <p> 
    <input name="imageField" type="image" src="picture/bildergalerie.gif" width="9" height="10" border="0">
    aktueller Bestand: 
    <input name="stueck" type="text" id="stueck" value="<?php echo $row_rst3['stueck']; ?>">
    Palettenstellpl&auml;tze/Packst&uuml;cke: 
    <input name="stueck2" type="text" id="stueck2" value="<?php echo $row_rst3['paletten']; ?>">
  </p>
  <p>Fertigungsmeldung</p>
  
  <table width="630" border="1" cellpadding="3" cellspacing="1" bordercolor="#666666">
    <tr bgcolor="#CCCCCC"> 
      <td width="59">Schicht</td>
      <td width="124">Teile</td>
      <td width="89"> <div align="center"><strong>Mo.</strong></div></td>
      <td width="59"> <div align="center"><strong>Di.</strong></div></td>
      <td width="70"> <div align="center"><strong>Mi.</strong></div></td>
      <td width="78"> <div align="center"><strong>Do.</strong></div></td>
      <td width="74"> <div align="center"><strong>Fr.</strong></div></td>
      <td width="82"> <div align="center"><strong>Sa.</strong></div></td>
      <td width="75"> <div align="center"><strong>So</strong></div></td>
      <td width="124">Teile</td>
    </tr>
    <tr bgcolor="#CEE1EC"> 
      <td rowspan="2"><strong>Fr&uuml;h</strong></td>
      <td>Gut</td>
      <td> <select name="mog1" id="mog1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mog1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mog1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mog1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td> <select name="dig1" id="dig1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dig1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dig1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dig1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td> <select name="mig1" id="mig1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mig1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mig1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mig1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td> <select name="dog1" id="dog1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dog1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dog1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dog1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td> <select name="frg1" id="frg1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['frg1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['frg1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['frg1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td> <select name="sag1" id="sag1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['sag1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['sag1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['sag1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td> <select name="sog1" id="sog1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['sog1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['sog1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['sog1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td>Gut</td>
    </tr>
    <tr> 
      <td>Ausschu&szlig;</td>
      <td><select name="moa1" class="roteliste" id="moa1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['moa1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['moa1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['moa1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="dia1" class="roteliste" id="dia1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dia1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dia1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dia1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="mia1" class="roteliste" id="mia1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mia1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mia1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mia1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="doa1" class="roteliste" id="doa1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['doa1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['doa1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['doa1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="fra1" class="roteliste"  id="fra1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['fra1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['fra1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['fra1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="saa1" class="roteliste"  id="saa1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['saa1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['saa1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['saa1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="soa1" class="roteliste" id="soa1">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['soa1']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['soa1']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['soa1']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td>Ausschu&szlig;</td>
    </tr>
    <tr bgcolor="#9EC5DA"> 
      <td rowspan="3"><strong>Sp&auml;t</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#9EC5DA"> 
      <td>Gut</td>
      <td><select name="mog2" id="mog2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mog2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mog2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mog2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="dig2" id="dig2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dig2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dig2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dig2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="mig2" id="mig2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mig2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mig2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mig2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="dog2" id="dog2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dog2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dog2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dog2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="frg2" id="frg2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['frg2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['frg2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['frg2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="sag2" id="sag2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['sag2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['sag2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['sag2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="sog2" id="sog2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['sog2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['sog2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['sog2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td>Gut</td>
    </tr>
    <tr> 
      <td>Ausschu&szlig;</td>
      <td><select name="moa2"  class="roteliste" id="moa2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['moa2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['moa2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['moa2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="dia2"  class="roteliste" id="dia2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dia2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dia2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dia2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="mia2"  class="roteliste" id="mia2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mia2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mia2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mia2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="doa2"  class="roteliste" id="doa2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['doa2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['doa2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['doa2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="fra2"  class="roteliste" id="fra2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['fra2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['fra2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['fra2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="saa2"  class="roteliste" id="saa2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['saa2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['saa2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['saa2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="soa2"  class="roteliste" id="soa2">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['soa2']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['soa2']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['soa2']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td>Ausschu&szlig;</td>
    </tr>
    <tr bgcolor="#30617A"> 
      <td rowspan="3"><strong>Nacht</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#30617A"> 
      <td>Gut</td>
      <td><select name="mog3" id="mog3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mog3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mog3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mog3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="dig3" id="dig3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dig3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dig3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dig3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="mig3" id="mig3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mig3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mig3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mig3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="dog3" id="dog3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dog3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dog3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dog3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="frg3" id="frg3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['frg3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['frg3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['frg3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="sag3" id="sag3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['sag3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['sag3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['sag3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="sog3" id="sog3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['sog3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['sog3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['sog3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td>Gut</td>
    </tr>
    <tr> 
      <td>Ausschu&szlig;</td>
      <td><select name="moa3"  class="roteliste" id="moa3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['moa3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['moa3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['moa3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="dia3" class="roteliste" id="dia3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['dia3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['dia3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['dia3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="mia3" class="roteliste"  id="mia3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['mia3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['mia3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['mia3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="doa3" class="roteliste" id="doa3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['doa3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['doa3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['doa3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="fra3"  class="roteliste" id="fra3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['fra3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['fra3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['fra3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="saa3"  class="roteliste" id="saa3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['saa3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['saa3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['saa3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td><select name="soa3"  class="roteliste" id="soa3">
          <option value="neu" <?php if (!(strcmp("neu", $row_rst3['soa3']))) {echo "SELECTED";} ?>>neu</option>
          <option value="0" <?php if (!(strcmp(0, $row_rst3['soa3']))) {echo "SELECTED";} ?>>0 
          St&uuml;ck</option>
          <?php $i=$row_rst3['von'];
		  do {
		  ?>
          <option value="<?php echo $i ?>" <?php if (!(strcmp($i, $row_rst3['soa3']))) {echo "SELECTED";} ?>><?php echo $i ?> 
          St&uuml;ck</option>
          <?php 
		  $i=$i+1;
		  }while ($row_rst3['bis']>$i)?>
        </select></td>
      <td>Ausschu&szlig;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="614" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td><p>Ausschu&szlig;gr&uuml;nde:<br>
          &Auml;nderungen:</p>
        <p><br>
          <br>
          <br>
          <br>
        </p>
        </td>
      <td><textarea name="bemerkung" cols="70" rows="7" id="textarea"><?php echo $row_rst3['bemerkung']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>Pr&uuml;fung</p>
  <table width="614" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="152">VDE-Pr&uuml;fung i.O.</td>
      <td width="435"> <input <?php if (!(strcmp($row_rst3['ffrei'],1))) {echo "checked";} ?> name="frei" type="checkbox" id="frei" value="checkbox"> 
        <img src="picture/st1.gif" width="15" height="15"> </td>
      <td width="27">&nbsp;</td>
    </tr>
    <tr> 
      <td>Datum</td>
      <td> <input type="text" name="fields[multi_edit][+%60upid%60+%3D+0][datum]" value="<?php echo $row_rst3['fdatum']; ?>" size="20" maxlength="99" class="textfield" onchange="return unNullify('fdatum', '0')" tabindex="10" id="field_3_3" /> 
        <script type="text/javascript">
                    <!--
                    document.write('<a title="Kalender" href="javascript:openCalendar(\'lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci\', \'insertForm\', \'field_3_3\', \'date\')"><img class="calendar" src="./themes/darkblue_orange/img/b_calendar.png" alt="Kalender"/></a>');
                    //-->
                    </script></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Sonderfreigabe erforderlich</td>
      <td> <input <?php if (!(strcmp($row_rst3['fsonder'],1))) {echo "checked";} ?> name="sonder" type="checkbox" id="sonder" value="checkbox"> 
        <img src="picture/st2.gif" width="15" height="15"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Signatur</td>
      <td><img src="picture/s_process.png" width="16" height="16"> <?php echo $row_rst3['signatur']; ?>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pr&uuml;fer:</td>
      <td><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
        <?php
do {  
?>
        <?php if (!(strcmp($row_rst9['id'], $row_rst3['fuser']))) {echo $row_rst9['name'];}?> 
        <?php
} while ($row_rst9 = mysql_fetch_assoc($rst9));
  $rows = mysql_num_rows($rst9);
  if($rows > 0) {
      mysql_data_seek($rst9, 0);
	  $row_rst9 = mysql_fetch_assoc($rst9);
  }
?>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>letzte &Auml;nderung:</td>
      <td><?php echo $row_rst3['change']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Pr&uuml;fger&auml;te:</td>
      <td><?php echo utf8_decode($row_rst3['pgeraet']); ?></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp; </p>
  <input type="hidden" name="MM_insert" value="form2">
  <input type="hidden" name="MM_update" value="form2">
</form>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst9);

?>
</p>
<script type="text/javascript">
<!--
    var forceQueryFrameReload = false;
    var dbBoxSetupDone = false;
    function dbBoxSetup() {
        if (dbBoxSetupDone != true) {
            if (parent.frames.queryframe && parent.frames.queryframe.document.left && parent.frames.queryframe.document.left.lightm_db) {
                parent.frames.queryframe.document.left.lightm_db.value = 'qsdb';
                dbBoxSetupDone = true;
            } else {
                setTimeout("dbBoxSetup();",500);
            }
        }
    }
    if (parent.frames.queryframe && parent.frames.queryframe.document && parent.frames.queryframe.document.queryframeform) {
        parent.frames.queryframe.document.queryframeform.db.value = "qsdb";
        parent.frames.queryframe.document.queryframeform.table.value = "userprofile";
    }
    if (parent.frames.queryframe && parent.frames.queryframe.document && parent.frames.queryframe.document.left && parent.frames.queryframe.document.left.lightm_db) {
        selidx = parent.frames.queryframe.document.left.lightm_db.selectedIndex;
        if (parent.frames.queryframe.document.left.lightm_db.options[selidx].value == "qsdb" && forceQueryFrameReload == false) {
            parent.frames.queryframe.document.left.lightm_db.options[selidx].text =
                parent.frames.queryframe.document.left.lightm_db.options[selidx].text.replace(/(.*)\([0-9]+\)/,'$1(21)');
        } else {
            parent.frames.queryframe.location.reload();
            setTimeout("dbBoxSetup();",2000);
        }
    }
    
    function reload_querywindow () {
        if (parent.frames.queryframe && parent.frames.queryframe.querywindow && !parent.frames.queryframe.querywindow.closed && parent.frames.queryframe.querywindow.location) {
                        if (!parent.frames.queryframe.querywindow.document.sqlform.LockFromUpdate || !parent.frames.queryframe.querywindow.document.sqlform.LockFromUpdate.checked) {
                parent.frames.queryframe.querywindow.document.querywindow.db.value = "qsdb";
                parent.frames.queryframe.querywindow.document.querywindow.query_history_latest_db.value = "qsdb";
                parent.frames.queryframe.querywindow.document.querywindow.table.value = "userprofile";
                parent.frames.queryframe.querywindow.document.querywindow.query_history_latest_table.value = "userprofile";

                parent.frames.queryframe.querywindow.document.querywindow.query_history_latest.value = "SELECT+%2A+FROM+%60userprofile%60";

                parent.frames.queryframe.querywindow.document.querywindow.submit();
            }
                    }
    }

    function focus_querywindow(sql_query) {
        if (parent.frames.queryframe && parent.frames.queryframe.querywindow && !parent.frames.queryframe.querywindow.closed && parent.frames.queryframe.querywindow.location) {
            if (parent.frames.queryframe.querywindow.document.querywindow.querydisplay_tab != 'sql') {
                parent.frames.queryframe.querywindow.document.querywindow.querydisplay_tab.value = "sql";
                parent.frames.queryframe.querywindow.document.querywindow.query_history_latest.value = sql_query;
                parent.frames.queryframe.querywindow.document.querywindow.submit();
                parent.frames.queryframe.querywindow.focus();
            } else {
                parent.frames.queryframe.querywindow.focus();
            }

            return false;
        } else if (parent.frames.queryframe) {
            new_win_url = 'querywindow.php?sql_query=' + sql_query + '&lang=de-utf-8&server=1&collation_connection=utf8_general_ci&db=qsdb&table=userprofile';
            parent.frames.queryframe.querywindow=window.open(new_win_url, '','toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=yes,resizable=yes,width=600,height=400');

            if (!parent.frames.queryframe.querywindow.opener) {
               parent.frames.queryframe.querywindow.opener = parent.frames.queryframe;
            }

            // reload_querywindow();
            return false;
        }
    }

    reload_querywindow();

//-->
</script>
<script type="text/javascript" language="javascript" src="libraries/tooltip.js"></script>

  <?php include("footer.tpl.php"); ?>
