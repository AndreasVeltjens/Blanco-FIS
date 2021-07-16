<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php $la = "kundenstart";
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
$query_rst1 = "SELECT * FROM user WHERE user.id = $url_user";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM fehler";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rstmi = "SELECT * FROM qmbmitteilungen WHERE (qmbmitteilungen.datum)>= date(curdate()) and (qmbmitteilungen.lokz=0) ORDER BY qmbmitteilungen.datum";
$rstmi = mysql_query($query_rstmi, $nachrichten) or die(mysql_error());
$row_rstmi = mysql_fetch_assoc($rstmi);
$totalRows_rstmi = mysql_num_rows($rstmi);



if ((isset($HTTP_POST_VARS["la5"])) ) {
$updateGoTo = "la5.php?url_user=".$row_rst1['id']."&url_kundenummer=".$HTTP_POST_VARS['url_kundenummer'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["qmb4"])) ) {
$updateGoTo = "qmb4.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["la6"])) ) {
$updateGoTo = "la6.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["fhm1"])) ) {
$updateGoTo = "fhm1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["la99"])) ) {
$updateGoTo = "la99.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["w1"])) ) {
$updateGoTo = "w1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["vis3"])) ) {
$updateGoTo = "vis3.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["vis10"])) ) {
$updateGoTo = "vis10.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["mi1"])) ) {
$updateGoTo = "mi1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["passwd"])) ) {
$updateGoTo = "kundenpasswd.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}
if ((isset($HTTP_POST_VARS["p1"])) ) {
$updateGoTo = "p1.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["la7"])) ) {
$updateGoTo = "la7.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["vl3"])) ) {
$updateGoTo = "vl3.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?><form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="0" cellspacing="1">
    <tr> 
      <td colspan="4"><strong>Kundenportal - Start</strong></td>
    </tr>
    <tr> 
      <td colspan="4"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td colspan="4"> <?php if ($totalRows_rstmi > 0) { // Show if recordset not empty ?>
        <img src="picture/ur.gif" width="18" height="18"> Liste der Mitteilungen 
        <br>
        <table width="780" border="0" cellpadding="0" cellspacing="0">
          <tr bgcolor="#CCCCFF" class="value"> 
            <td width="229"><em><strong>Information</strong></em></td>
            <td width="551"><em></em></td>
          </tr>
          <?php
  $i=1;
   do { 
  $i=$i+1;
$d=intval($i/2);
  
  ?>
          <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?> > 
            <td> 
              <img src="picture/iconMessage_16x16.gif" width="16" height="16" align="absmiddle"> 
              <?php 
	  if ($row_rstmi['datum']== date("Y-m-d",time() )){
	  echo "heute";
	  } else{
	  
	  
	  echo $row_rstmi['datum'];}
	   ?>
              - <?php echo substr($row_rstmi['Zeit'],0,5); ?> Uhr</td>
            <td    ><?php echo nl2br(utf8_decode($row_rstmi['name'])); ?> 
            </td>
          </tr>
          <?php } while ($row_rstmi = mysql_fetch_assoc($rstmi)); ?>
        </table>
        <br>
        <br>
        <?php } // Show if recordset not empty ?> </td>
    </tr>
    <tr> 
      <td colspan="2"><em><strong>allgemeine Aktionen w&auml;hlen:</strong></em></td>
      <td width="58">&nbsp;</td>
      <td width="275">&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td width="81"><img src="picture/ordnerverwaltung.gif" width="70" height="50"></td>
      <td width="316"><input name="la7" type="submit" id="la7" value="&Uuml;bersicht">
        <br>
        Status Vorgangs&uuml;bersicht<br> </td>
      <td><img src="picture/security.gif" width="52" height="36"></td>
      <td><input name="w1" type="submit" id="w14" value="&Uuml;bersicht">
        Meldungen und Workflow's</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td rowspan="2"><img src="picture/icon_gr_auftragsstatus.gif" width="55" height="75"></td>
      <td rowspan="2"> <p> 
          <input name="la5" type="submit" id="la5" value="&Uuml;bersicht">
          <br>
          <br>
          Status Auftragsabwicklung<br>
          Reparatur &amp; Fehlermeldungen<br>
          Kostenvoranschl&auml;ge <br>
        </p>
        </td>
      <td><img src="picture/email.gif" width="52" height="36"></td>
      <td><input name="mi1" type="submit" id="w1" value="&Uuml;bersicht">
        Nachrichten</td>
    </tr>
    <tr bgcolor="#CCCCFF"> 
      <td><img src="picture/myaccount.gif" width="52" height="36"></td>
      <td><input name="passwd" type="submit" id="w125" value="&Uuml;bersicht">
        Meine Kundendaten</td>
    </tr>
  </table>
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  <input name="url_kundenummer" type="hidden" id="url_kundenummer" value="<?php echo $url_kundenummer; ?>">
</form>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rstmi);
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php include("footer.tpl.php"); ?>
