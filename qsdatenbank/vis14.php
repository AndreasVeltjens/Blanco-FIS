<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];

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
  $la = "vis14";

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$GoTo = "vis12.php?url_user=".$row_rst1['id']."&url_id_kz=".$HTTP_POST_VARS['hurl_id_kz'];
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion w&auml;hlen. Bitte erneut versuchen. (Cancel)";}
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "ordnungsgrad")) {
 
 $ordnungsgrad=($HTTP_POST_VARS['op1']+$HTTP_POST_VARS['op2']+$HTTP_POST_VARS['op3']+$HTTP_POST_VARS['op4']+$HTTP_POST_VARS['op5']+$HTTP_POST_VARS['op6']+$HTTP_POST_VARS['op7']+$HTTP_POST_VARS['op8']+$HTTP_POST_VARS['op9']+$HTTP_POST_VARS['op10']);
 
  $updateSQL = sprintf("UPDATE kennzahlen_daten SET wert1y=%s, op1=%s, op2=%s, op3=%s, op4=%s, op5=%s, op6=%s, op7=%s, op8=%s, op9=%s, op10=%s WHERE id_kzd=%s",
                       GetSQLValueString($ordnungsgrad, "text"),
                       GetSQLValueString($HTTP_POST_VARS['op1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op3'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op4'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op5'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op6'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op7'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op8'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op9'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['op10'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_kzd'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["add"]) )) {
  $updateSQL = sprintf("UPDATE kennzahlen_daten SET id_kz=%s, wertx=%s, wert1y=%s, wert2y=%s, wert3y=%s, wert4y=%s, lokz=%s WHERE id_kzd=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_kz'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['xachse'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['yachse1'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['yachse2'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['yachse3'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['yachse4'], "text"),
						GetSQLValueString($HTTP_POST_VARS['lokz'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_id_kzd'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen WHERE kennzahlen.id_kz='$url_id_kz'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

$maxRows_rst3 = 12;
$pageNum_rst3 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst3'])) {
  $pageNum_rst3 = $HTTP_GET_VARS['pageNum_rst3'];
}
$startRow_rst3 = $pageNum_rst3 * $maxRows_rst3;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM kennzahlen_daten WHERE kennzahlen_daten.id_kzd='$url_id_kzd' ORDER BY kennzahlen_daten.wertx desc";
$query_limit_rst3 = sprintf("%s LIMIT %d, %d", $query_rst3, $startRow_rst3, $maxRows_rst3);
$rst3 = mysql_query($query_limit_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);

if (isset($HTTP_GET_VARS['totalRows_rst3'])) {
  $totalRows_rst3 = $HTTP_GET_VARS['totalRows_rst3'];
} else {
  $all_rst3 = mysql_query($query_rst3);
  $totalRows_rst3 = mysql_num_rows($all_rst3);
}
$totalPages_rst3 = ceil($totalRows_rst3/$maxRows_rst3)-1;

$queryString_rst3 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst3") == false && 
        stristr($param, "totalRows_rst3") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst3 = "&" . implode("&", $newParams);
  }
}
$queryString_rst3 = sprintf("&totalRows_rst3=%d%s", $totalRows_rst3, $queryString_rst3);



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<style type="text/css">
<!--
.auswahlbox {
	height: 30px;
	width: 600px;
	font-size: 16px;
}
-->
</style>

<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  <table width="950" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="4"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="4"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="164">Aktionen W&auml;hlen:</td>
      <td width="330"><img src="picture/b_drop.png" width="16" height="16"> <input name="cancel" type="submit" id="cancel2" value="Zur&uuml;ck"> 
        <input name="hurl_id_kz3" type="hidden" id="hurl_id_kz32" value="<?php echo $row_rst2['id_kz']; ?>">
        <a href="vis2.php?url_user=<?php $row_rst1['id']?>&url_id_kz=<?php echo $row_rst2['id_kz']; ?>&goback=vis1" target="_blank"><img src="picture/column-chart.gif" alt="Diagramm anzeigen" width="16" height="16" border="0"></a> 
      </td>
      <td width="127"><strong>IST: <?php echo $row_rst3['wert1y']; ?></strong></td>
      <td width="329"> <div align="left"><strong>SOLL: <?php echo $row_rst3['wert2y']; ?> 
          </strong></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>geh&ouml;rt zur Gruppe :</td>
      <td><?php echo $row_rst2['gruppe']; ?></td>
    </tr>
    <tr> 
      <td>Beschreibung</td>
      <td><strong><?php echo $row_rst2['beschreibung']; ?></strong></td>
      <td><strong> </textarea>&Uuml;berschrift:</strong></td>
      <td><?php echo $row_rst2['name']; ?> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>
<hr>
<p class="auswahlbox"><strong><em><img src="picture/b_insrow.png" width="16" height="16"> Ordnungsgrad 
  bewerten</em></strong></p>
<form name="ordnungsgrad" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="950" border="0" cellpadding="1" cellspacing="1">
    <tr bgcolor="#CCCCCC"> 
      <td width="14"><div align="center"><strong> 1<br>
          <font size="1"><strong><img src="picture/usergruppe_16x16.png" width="16" height="16" align="absmiddle"></strong></font><br>
          </strong></div></td>
      <td width="172"><div align="center"><font size="1"><strong> <br>
          Kenntnisstand der Mitarbeiter zu den 6S<br>
          </strong></font></div></td>
      <td width="612"> <select name="op1" class="auswahlbox" id="op1">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op1']))) {echo "SELECTED";} ?>>keine 
          Kenntnis</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op1']))) {echo "SELECTED";} ?>>keine 
          Kenntnis+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op1']))) {echo "SELECTED";} ?>>l&uuml;ckenhafte 
          Kenntnis</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op1']))) {echo "SELECTED";} ?>>l&uuml;ckenhafte 
          Kenntnis+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op1']))) {echo "SELECTED";} ?>>l&uuml;ckenhafte 
          Kenntnis++</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op1']))) {echo "SELECTED";} ?>>Kenntnis 
          der Hauptinhalte</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op1']))) {echo "SELECTED";} ?>>Kenntnis 
          der Hauptinhalte+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op1']))) {echo "SELECTED";} ?>>Kenntnis 
          der Hauptinhalte++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op1']))) {echo "SELECTED";} ?>>richtige 
          Beantwortung</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op1']))) {echo "SELECTED";} ?>>richtige 
          Beantwortung+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op1']))) {echo "SELECTED";} ?>>sofortige 
          und richtige Beantwortung (sinngem&auml;&szlig;)</option>
        </select> </td>
      <td width="20"><input type="submit" name="Submit" value="sichern"></td>
      <td width="156"> <div align="center"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_kz" type="hidden" id="hurl_id_kz" value="<?php echo $row_rst2['id_kz']; ?>">
          <input name="hurl_id_kzd" type="hidden" id="hurl_id_kzd" value="<?php echo $row_rst3['id_kzd']; ?>">
          <?php echo $row_rst3['op1']; ?></div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><div align="center"><strong> 2<br>
          <font size="1"><strong><img src="picture/certificate_ok.gif" width="16" height="16" align="absmiddle"></strong></font><br>
          </strong></div></td>
      <td><div align="center"><font size="1"><strong> <br>
          Situation auf den Bereitstellfl&auml;chen / Regale<br>
          </strong></font></div></td>
      <td> <select name="op2" class="auswahlbox" id="op2">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op2']))) {echo "SELECTED";} ?>>ohne 
          Sorgfalt un&uuml;bersichtlich, Beh&auml;lter teilweise besch&auml;digt</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op2']))) {echo "SELECTED";} ?>>ohne 
          Sorgfalt un&uuml;bersichtlich, Beh&auml;lter teilweise besch&auml;digt+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op2']))) {echo "SELECTED";} ?>>einfach 
          auf dem Boden abgestellt und gestapelt</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op2']))) {echo "SELECTED";} ?>>einfach 
          auf dem Boden abgestellt und gestapelt+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op2']))) {echo "SELECTED";} ?>>einfach 
          auf dem Boden abgestellt und gestapelt++</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op2']))) {echo "SELECTED";} ?>>Kennzeichnung 
          der Stellfl&uuml;chen, Beschriftung mit Teilebezeichnung</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op2']))) {echo "SELECTED";} ?>>Kennzeichnung 
          der Stellfl&uuml;chen, Beschriftung mit Teilebezeichnung+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op2']))) {echo "SELECTED";} ?>>Kennzeichnung 
          der Stellfl&uuml;chen, Beschriftung mit Teilebezeichnung++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op2']))) {echo "SELECTED";} ?>>Klares, 
          strukturiertes Adressensystem</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op2']))) {echo "SELECTED";} ?>>Klares, 
          strukturiertes Adressensystem+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op2']))) {echo "SELECTED";} ?>>systematische 
          Lagerung, sofortige Auslieferung jederzeit m&ouml;glich</option>
        </select></td>
      <td><input type="submit" name="Submit2" value="sichern"></td>
      <td><div align="center">&nbsp;<?php echo $row_rst3['op2']; ?> </div></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><div align="center"><strong> 3<br>
          <font size="1"><strong><img src="picture/del.gif" width="18" height="18" align="absmiddle"></strong></font></strong></div></td>
      <td><div align="center"><font size="1"><strong><br>
          Situation am Arbeitsplatz/ -umfeld &amp; M&uuml;lleimer<br>
          </strong></font></div></td>
      <td> <select name="op3" class="auswahlbox" id="op3">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op3']))) {echo "SELECTED";} ?>>Un&uuml;bersichtlich, 
          Abfall und Privatutensilien liegen herum</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op3']))) {echo "SELECTED";} ?>>Un&uuml;bersichtlich, 
          Abfall und Privatutensilien liegen herum+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op3']))) {echo "SELECTED";} ?>>&uuml;berfl&uuml;ssige 
          Teile vorhanden, viel Mateial vor Ort</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op3']))) {echo "SELECTED";} ?>>&uuml;berfl&uuml;ssige 
          Teile vorhanden, viel Mateial vor Ort+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op3']))) {echo "SELECTED";} ?>>&uuml;berfl&uuml;ssige 
          Teile vorhanden, viel Mateial vor Ort</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op3']))) {echo "SELECTED";} ?>>gute 
          Kennzeichung und Beschriftung</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op3']))) {echo "SELECTED";} ?>>gute 
          Kennzeichung und Beschriftung+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op3']))) {echo "SELECTED";} ?>>gute 
          Kennzeichung und Beschriftung++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op3']))) {echo "SELECTED";} ?>>keine 
          &uuml;berfl&uuml;ssigen Gegenst&auml;nde vorhanden</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op3']))) {echo "SELECTED";} ?>>keine 
          &uuml;berfl&uuml;ssigen Gegenst&auml;nde vorhanden+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op3']))) {echo "SELECTED";} ?>>optimaler 
          Zugriff auf Material, Werkzeuge, Vorrichtungen usw.</option>
        </select></td>
      <td><input type="submit" name="Submit3" value="sichern"></td>
      <td><div align="center"><?php echo $row_rst3['op3']; ?></div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><div align="center"><strong>4<br>
          <font size="1"><strong><img src="picture/column-chart.gif" width="16" height="16" align="absmiddle"></strong></font><br>
          </strong></div></td>
      <td><div align="center"><font size="1"><strong> <br>
          Maschinenbuch Wartungspl&auml;ne<br>
          </strong></font></div></td>
      <td> <select name="op4" class="auswahlbox" id="op4">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op4']))) {echo "SELECTED";} ?>>Dokumente 
          teilweise besch&auml;digt, Inhalte unklar</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op4']))) {echo "SELECTED";} ?>>Dokumente 
          teilweise besch&auml;digt, Inhalte unklar+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op4']))) {echo "SELECTED";} ?>>veraltete 
          Dokumente vorhanden</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op4']))) {echo "SELECTED";} ?>>veraltete 
          Dokumente vorhanden+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op4']))) {echo "SELECTED";} ?>>veraltete 
          Dokumente vorhanden++</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op4']))) {echo "SELECTED";} ?>>Dokumente 
          befinden sich an definierten Stellen</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op4']))) {echo "SELECTED";} ?>>Dokumente 
          befinden sich an definierten Stellen+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op4']))) {echo "SELECTED";} ?>>Dokumente 
          befinden sich an definierten Stellen++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op4']))) {echo "SELECTED";} ?>>Die 
          Informationen sind f&uuml;r jeden gut einsehbar</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op4']))) {echo "SELECTED";} ?>>Die 
          Informationen sind f&uuml;r jeden gut einsehbar+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op4']))) {echo "SELECTED";} ?>>Nur 
          aktuelle Informationen h&auml;ngen aus</option>
        </select></td>
      <td><input type="submit" name="Submit4" value="sichern"></td>
      <td> <div align="center"><?php echo $row_rst3['op4']; ?> </div></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><div align="center"><strong>5<br>
          <font size="1"><strong><img src="picture/orderstatus.gif" width="22" height="12"></strong></font><br>
          </strong></div></td>
      <td><div align="center"><font size="1"><strong><br>
          Fahrwege<br>
          </strong></font></div></td>
      <td><select name="op5" class="auswahlbox" id="select">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op5']))) {echo "SELECTED";} ?>>viele 
          Hindernisse auf den Fahrwegen, keine klare Kennzeichnung</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op5']))) {echo "SELECTED";} ?>>einige 
          Hindernisse auf den Fahrwegen, keine klare Kennzeichnung+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op5']))) {echo "SELECTED";} ?>>Gegenst&auml;nde 
          auf den Fahrwegen</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op5']))) {echo "SELECTED";} ?>>Gegenst&auml;nde 
          auf den Fahrwegen+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op5']))) {echo "SELECTED";} ?>>Gegenst&auml;nde 
          auf den Fahrwegen++</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op5']))) {echo "SELECTED";} ?>>klare 
          Trennung zwischen Fahrwegen und sonstigen Fl&auml;chen</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op5']))) {echo "SELECTED";} ?>>klare 
          Trennung zwischen Fahrwegen und sonstigen Fl&auml;chen+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op5']))) {echo "SELECTED";} ?>>klare 
          Trennung zwischen Fahrwegen und sonstigen Fl&auml;chen++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op5']))) {echo "SELECTED";} ?>>keine 
          Gegenst&auml;nde auf den Wegen, eindeutige Kennzeichnung</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op5']))) {echo "SELECTED";} ?>>keine 
          Gegenst&auml;nde auf den Wegen, eindeutige Kennzeichnung+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op5']))) {echo "SELECTED";} ?>>keine 
          Gegenst&auml;nde auf den Fahrwegen, tadelloses Erscheinungsbild</option>
        </select></td>
      <td><input type="submit" name="Submit5" value="sichern"></td>
      <td><div align="center"><?php echo $row_rst3['op5']; ?> </div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><div align="center"><strong><br>
          6<br>
          <font size="1"><strong><img src="picture/anforderung_24x24.png" width="24" height="24" align="absmiddle"></strong></font><br>
          </strong></div></td>
      <td><div align="center"><font size="1"><strong><br>
          Ablagen f&uuml;r<br>
          Werkzeuge / <br>
          Vorrichtungen<br>
          </strong></font></div></td>
      <td><select name="op6" class="auswahlbox" id="select">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op6']))) {echo "SELECTED";} ?>>unklar, 
          wo sich die Werkzeuge befinden.</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op6']))) {echo "SELECTED";} ?>>unklar, 
          wo sich die Werkzeuge befinden.+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op6']))) {echo "SELECTED";} ?>>alle 
          Werkzeuge liegen unsystematisch herum.</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op6']))) {echo "SELECTED";} ?>>einige 
          Werkzeuge liegen unsystematisch herum.+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op6']))) {echo "SELECTED";} ?>>Werkzeuge 
          liegen unsystematisch herum.++</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op6']))) {echo "SELECTED";} ?>>Werkzeuge 
          in Werkzeugkisten</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op6']))) {echo "SELECTED";} ?>>einige 
          Werkzeuge in Werkzeugkisten+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op6']))) {echo "SELECTED";} ?>>alle 
          Werkzeuge in Werkzeugkisten++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op6']))) {echo "SELECTED";} ?>>Werkzeuge 
          auf definierten Pl&auml;tzen (Schattenbretter)</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op6']))) {echo "SELECTED";} ?>>Werkzeuge 
          auf definierten Pl&auml;tzen (Schattenbretter)+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op6']))) {echo "SELECTED";} ?>>Alle 
          Werkzeuge im optimalen Greifpunkt (Best Point)</option>
        </select></td>
      <td><input type="submit" name="Submit6" value="sichern"></td>
      <td><div align="center"><?php echo $row_rst3['op6']; ?></div></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><div align="center"><strong> 7<br>
          <font size="1"><strong><img src="picture/iconFixedprice_16x16.gif" width="16" height="16" align="absmiddle"></strong></font><br>
          </strong></div></td>
      <td><div align="center"><font size="1"><strong><br>
          Zeichnungen, <br>
          standardisierte <br>
          Arbeitsablaufbl&auml;tter,<br>
          Arbeitsanweisungen<br>
          </strong></font></div></td>
      <td><select name="op7" class="auswahlbox" id="op7">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op7']))) {echo "SELECTED";} ?>>unklar, 
          wo sich die Dokumente befinden.</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op7']))) {echo "SELECTED";} ?>>unklar, 
          wo sich die Dokumente befinden.+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op7']))) {echo "SELECTED";} ?>>alle 
          Dokumente liegen unsystematisch herum.</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op7']))) {echo "SELECTED";} ?>>einige 
          Dokumente liegen unsystematisch herum.+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op7']))) {echo "SELECTED";} ?>>Dokumente 
          liegen unsystematisch herum.++</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op7']))) {echo "SELECTED";} ?>>Dokumente 
          in Hefter</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op7']))) {echo "SELECTED";} ?>>einige 
          Dokumente inm Hefter sortiert+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op7']))) {echo "SELECTED";} ?>>alle 
          Dokumente im Hefter sortiert++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op7']))) {echo "SELECTED";} ?>>Dokumente 
          auf definierten Platzen</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op7']))) {echo "SELECTED";} ?>>Dokumente 
          auf definierten Platzen +</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op7']))) {echo "SELECTED";} ?>>Alle 
          Dokumente im optimalen Greifpunkt (Best Point)</option>
        </select></td>
      <td><input type="submit" name="Submit7" value="sichern"></td>
      <td><div align="center"><?php echo $row_rst3['op7']; ?></div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><div align="center"><strong> 8<br>
          </strong></div></td>
      <td><div align="center"><font size="1"><strong>Fenster, W&auml;nde,<br>
          Boden<br>
          </strong></font></div></td>
      <td><select name="op8" class="auswahlbox" id="select2">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op8']))) {echo "SELECTED";} ?>>Besch&auml;digungen, 
          nur provisorische Ausbesserung</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op8']))) {echo "SELECTED";} ?>>Besch&auml;digungen, 
          nur provisorische Ausbesserung+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op8']))) {echo "SELECTED";} ?>>Verschmutzung 
          auff&auml;llig</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op8']))) {echo "SELECTED";} ?>>Verschmutzung 
          auff&auml;llig+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op8']))) {echo "SELECTED";} ?>>Verschmutzung 
          auff&auml;llig++</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op8']))) {echo "SELECTED";} ?>>unregelm&auml;ssige 
          Reinigung</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op8']))) {echo "SELECTED";} ?>>unregelm&auml;ssige 
          Reinigung+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op8']))) {echo "SELECTED";} ?>>unregelm&auml;ssige 
          Reinigung++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op8']))) {echo "SELECTED";} ?>>regelm&auml;ssige 
          Ausbesserung und Reinigung</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op8']))) {echo "SELECTED";} ?>>regelm&auml;ssige 
          Ausbesserung und Reinigung+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op8']))) {echo "SELECTED";} ?>>st&auml;ndiger 
          sauberer Zustand, t&auml;gliche Reinigung</option>
        </select></td>
      <td><input type="submit" name="Submit8" value="sichern"></td>
      <td><div align="center"><?php echo $row_rst3['op8']; ?></div></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td><div align="center"><strong>9<br>
          </strong></div></td>
      <td><div align="center"><font size="1"><strong>WC-, Umkleide- u.<br>
          Pausenr&auml;ume<br>
          </strong> </font></div></td>
      <td><select name="op9" class="auswahlbox" id="op9">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op9']))) {echo "SELECTED";} ?>>verschmutzt 
          und unordentlich.</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op9']))) {echo "SELECTED";} ?>>verschmutzt 
          und unordentlich.+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op9']))) {echo "SELECTED";} ?>>Bereich 
          wirkt unordentlich.</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op9']))) {echo "SELECTED";} ?>>Bereich 
          wirkt unordentlich.+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op9']))) {echo "SELECTED";} ?>>Bereich 
          wirkt unordentlich.++</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op9']))) {echo "SELECTED";} ?>>Es 
          wird irgendwie gereinigt.</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op9']))) {echo "SELECTED";} ?>>Es 
          wird irgendwie gereinigt.+</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op9']))) {echo "SELECTED";} ?>>Es 
          wird irgendwie gereinigt.++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op9']))) {echo "SELECTED";} ?>>ordentlicher 
          Zustand</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op9']))) {echo "SELECTED";} ?>>ordentlicher 
          Zustand+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op9']))) {echo "SELECTED";} ?>>permanent 
          ordentlich sauberer Zustand</option>
        </select></td>
      <td><input type="submit" name="Submit9" value="sichern"></td>
      <td><div align="center"><?php echo $row_rst3['op9']; ?></div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><div align="center"><strong> 10<br>
          <br>
          </strong></div></td>
      <td><div align="center"><font size="1"><strong>Innenhof<br>
          Werkshallen gesamt<br>
          Ladebereich<br>
          </strong> </font></div></td>
      <td><select name="op10" class="auswahlbox" id="op10">
          <option value="0" <?php if (!(strcmp(0, $row_rst3['op10']))) {echo "SELECTED";} ?>>unordentlich, 
          unsauberer Gesamteindruck</option>
          <option value="1" <?php if (!(strcmp(1, $row_rst3['op10']))) {echo "SELECTED";} ?>>unordentlich, 
          unsauberer Gesamteindruck+</option>
          <option value="2" <?php if (!(strcmp(2, $row_rst3['op10']))) {echo "SELECTED";} ?>>undefniertes 
          Erscheinungsbild</option>
          <option value="3" <?php if (!(strcmp(3, $row_rst3['op10']))) {echo "SELECTED";} ?>>undefniertes 
          Erscheinungsbild+</option>
          <option value="4" <?php if (!(strcmp(4, $row_rst3['op10']))) {echo "SELECTED";} ?>>defniertes 
          Erscheinungsbild aber nicht gut</option>
          <option value="5" <?php if (!(strcmp(5, $row_rst3['op10']))) {echo "SELECTED";} ?>>normales 
          Erscheinungsbild</option>
          <option value="6" <?php if (!(strcmp(6, $row_rst3['op10']))) {echo "SELECTED";} ?>>normales 
          Erscheinungsbild, Kantine i.O.</option>
          <option value="7" <?php if (!(strcmp(7, $row_rst3['op10']))) {echo "SELECTED";} ?>>normales 
          Erscheinungsbild++</option>
          <option value="8" <?php if (!(strcmp(8, $row_rst3['op10']))) {echo "SELECTED";} ?>>guter 
          Gesamteindruck</option>
          <option value="9" <?php if (!(strcmp(9, $row_rst3['op10']))) {echo "SELECTED";} ?>>guter 
          Gesamteindruck+</option>
          <option value="10" <?php if (!(strcmp(10, $row_rst3['op10']))) {echo "SELECTED";} ?>>ordentlich 
          sauber und gepflegt.</option>
        </select></td>
      <td><input type="submit" name="Submit10" value="sichern"></td>
      <td><div align="center"><?php echo $row_rst3['op10']; ?></div></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>&nbsp;</td>
      <td><font size="1">&nbsp;</font></td>
      <td> <input name="add2" type="submit" class="auswahlbox" id="add25" value="&auml;ndern"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="ordnungsgrad">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<hr>
<p><strong><em><img src="picture/b_insrow.png" width="16" height="16"> Datensatz 
  &auml;ndern</em></strong></p>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF"> 
      <td width="224">x Achse <?php echo $row_rst2['xachse']; ?></td>
      <td width="350"><input name="xachse" type="text" id="xachse" value="<?php echo $row_rst3['wertx']; ?>"></td>
      <td width="156"> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_id_kz" type="hidden" id="hurl_id_kz" value="<?php echo $row_rst2['id_kz']; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>1. Wert - <?php echo $row_rst2['yachse']; ?></td>
      <td><input name="yachse1" type="text" id="yachse1" value="<?php echo $row_rst3['wert1y']; ?>"></td>
      <td> <input name="hurl_id_kzd" type="hidden" id="hurl_id_kzd" value="<?php echo $row_rst3['id_kzd']; ?>"></td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>2. Wert - <?php echo $row_rst2['yachse2']; ?></td>
      <td><input name="yachse2" type="text" id="yachse2" value="<?php echo $row_rst3['wert2y']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td>3. Wert - <?php echo $row_rst2['yachse3']; ?></td>
      <td><input name="yachse3" type="text" id="yachse3" value="<?php echo $row_rst3['wert3y']; ?>"></td>
      <td> <div align="right"><img src="picture/b_newdb.png" width="16" height="16"> 
          <input name="add" type="submit" id="add" value="&auml;ndern">
        </div></td>
    </tr>
    <tr bgcolor="#CCCCFF">
      <td>4. Wert - <?php echo $row_rst2['yachse4']; ?></td>
      <td><input name="yachse4" type="text" id="yachse4" value="<?php echo $row_rst3['wert4y']; ?>"></td>
      <td><img src="picture/b_drop.png" width="16" height="16" border="0" align="absmiddle"> 
        l&ouml;schen ? 
        <input <?php if (!(strcmp($row_rst3['id_kz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="0"></td>
    </tr>
  </table>
  
  <br>
  <input type="hidden" name="MM_update" value="form2">
</form>
<strong><em><img src="picture/s_tbl.png" width="16" height="16"> Liste der gespeicherten 
Werte</em></strong> 
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <br>
  <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
<?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, 0, $queryString_rst3); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, max(0, $pageNum_rst3 - 1), $queryString_rst3); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, min($totalPages_rst3, $pageNum_rst3 + 1), $queryString_rst3); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, $totalPages_rst3, $queryString_rst3); ?>">Last</a> 
Anzeige von <?php echo ($startRow_rst3 + 1) ?>bis <?php echo min($startRow_rst3 + $maxRows_rst3, $totalRows_rst3) ?> 
<table width="730" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="122"><strong>Status</strong></td>
    <td width="87"><strong>ID</strong></td>
    <td width="83"><div align="left"><?php echo $row_rst2['xachse']; ?></div></td>
    <td width="84"><div align="left"><?php echo $row_rst2['yachse']; ?></div></td>
    <td width="91"><div align="right"><?php echo $row_rst2['yachse2']; ?></div></td>
    <td width="91"><div align="right"><?php echo $row_rst2['yachse3']; ?></div></td>
    <td width="91"><div align="center"><?php echo $row_rst2['yachse4']; ?></div></td>
    <td width="27"><strong>lokz</strong></td>
    <td width="164">&nbsp;</td>
  </tr>
  <?php do { ?>
  <form name="form1" method="post" action="">
    <?php   if ($row_rst3['gruppe']!=$gruppe){?>
    <tr > 
      <td colspan="8" nowrap="nowrap"    bgcolor="#CCCCCC"><em><img src="picture/s_tbl.png" width="16" height="16"><?php echo $row_rst2['gruppe']; ?></em></td>
      <td    bgcolor="#CCCCCC" nowrap="nowrap">&nbsp;</td>
    </tr>
    <?php 	}?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if (!(strcmp($row_rst2['lokz'],0))) { // Show if recordset not empty ?>
        <img src="picture/st1.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        <?php }  ?>
        <?php if (!(strcmp($row_rst2['lokz'],1))) { // Show if recordset empty ?>
        <img src="picture/st3.gif" alt="Pr&uuml;fstatus" width="15" height="15"> 
        <?php }  ?>
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><font size="-7">&nbsp; </font> 
        <?php echo $row_rst3['id_kzd']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"></div>
        <?php echo $row_rst3['wertx']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"></div>
        <?php echo $row_rst3['wert1y']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <div align="right"><?php echo $row_rst3['wert2y']; ?><font size="-7" face="Arial, Helvetica, sans-serif"> 
          </font> </div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst3['wert3y']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo $row_rst3['wert4y']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> <font size="-7"> 
        <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> type="checkbox" name="checkbox" value="checkbox">
        </font></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_id_kzd2" type="hidden" id="hurl_id_kzd2" value="<?php echo $row_rst3['id_kzd']; ?>">
          <input name="hurl_user2" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_id_kz2" type="hidden" id="hurl_id_kz2" value="<?php echo $row_rst2['id_kz']; ?>">
          <img src="picture/b_edit.png" width="16" height="16"> </div></td>
    </tr>
  </form>
  <?php 
  $gruppe=$row_rst3['gruppe'];  
  } while ($row_rst3 = mysql_fetch_assoc($rst3)); ?>
</table>
<br>
<p>&nbsp;<a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, 0, $queryString_rst3); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, max(0, $pageNum_rst3 - 1), $queryString_rst3); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, min($totalPages_rst3, $pageNum_rst3 + 1), $queryString_rst3); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst3=%d%s", $currentPage, $totalPages_rst3, $queryString_rst3); ?>">Last</a> 
  Anzeige von <?php echo ($startRow_rst3 + 1) ?>bis <?php echo min($startRow_rst3 + $maxRows_rst3, $totalRows_rst3) ?></p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst3 ?> Visualisierungen angelegt.</p>
<?php } // Show if recordset not empty ?>
<p>Legende: </p>
<p><font size="1" face="Arial, Helvetica, sans-serif"></font></p>
<p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);
?>
</p>

  <?php include("footer.tpl.php"); ?>
