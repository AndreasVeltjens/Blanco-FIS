<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php 
$la="log";
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

$maxRows_rst2 = 30;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

if ((isset($HTTP_POST_VARS['suchtext'])) && ($HTTP_POST_VARS['suchtext']<>"")){
$idcard=round((($HTTP_POST_VARS['suchtext'])*1)/1,0);
}
$suchtxt="WHERE  `user`.kmtg=1  and `user`.aktiviert=1 ";


$maxRows_rst2 = 30;;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM `user` $suchtxt ORDER BY `user`.name";
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

if (isset($HTTP_GET_VARS['totalRows_rst2'])) {
  $totalRows_rst2 = $HTTP_GET_VARS['totalRows_rst2'];
} else {
  $all_rst2 = mysql_query($query_rst2);
  $totalRows_rst2 = mysql_num_rows($all_rst2);
}
$totalPages_rst2 = ceil($totalRows_rst2/$maxRows_rst2)-1;

$queryString_rst2 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst2") == false && 
        stristr($param, "totalRows_rst2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst2 = "&" . implode("&", $newParams);
  }
}
$queryString_rst2 = sprintf("&totalRows_rst2=%d%s", $totalRows_rst2, $queryString_rst2);



$url_idcard=round((($HTTP_POST_VARS['suchtext'])*1)/1,0);
if (($url_idcard>=200000) or ($url_idcard==999999)){$url_idcard=0;}

$url_user =$HTTP_POST_VARS['benutzername'];
$url_pass = $HTTP_POST_VARS['passwort'];
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.username = '$url_user' AND user.passwort = '$url_pass'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM user WHERE idcard= '$url_idcard' or idzeitausweis= '$url_idcard'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rstmi = "SELECT * FROM mitteilung WHERE (mitteilung.datum)>=date(curdate()) and (mitteilung.lokz=0) ORDER BY mitteilung.datum";
$rstmi = mysql_query($query_rstmi, $nachrichten) or die(mysql_error());
$row_rstmi = mysql_fetch_assoc($rstmi);
$totalRows_rstmi = mysql_num_rows($rstmi);

if ($HTTP_POST_VARS["suchtext"]>30300 && ($totalRows_rst3 > 0)) {
$updateGoTo = "start.php?url_user=".$row_rst3['id'];
  
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = $errtxt."ID-Card-Nummer einscannen und anmelden.";}

if ((isset($HTTP_POST_VARS["anmelden"])) && ($totalRows_rst1 > 0)) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = $errtxt."<br>alternativ: wählen Sie aus der Mitarbeiterliste aus.";}



include("function.tpl.php");
?>

<script>
function sf(){document.form1.suchtext.focus()}
</script>
<?php
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>" onload="sf()">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="5"><strong> <img src="picture/s_rights.png" width="16" height="16"> 
        Mitarbeiter-Anmeldung f&uuml;r die Montage </strong></td>
    </tr>
    <tr> 
      <td colspan="5"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="94" rowspan="2"><div align="center"><img src="picture/W7_Img_Activate.jpg" width="66" height="66"></div></td>
      <td colspan="3" bgcolor="#FFFFFF"> <input name="hiddenField" type="hidden" value="<?php echo $row_rst1['id']; ?>"> 
        <font size="7">Wer bin ich ?</font> </td>
      <td width="134" rowspan="2"><div align="right"> 
          <input name="anmelden" type="submit" id="anmelden" value="Anmelden">
        </div></td>
    </tr>
    <tr> 
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF">Personal od. ID-Card-Nummer eingeben:</td>
      <td bgcolor="#FFFFFF"><img src="picture/icon_scan.gif" alt="Dieses Feld ist Barcodelesef&auml;hig"> 
        <input name="suchtext" type="password" id="suchtext5" value="" size="17" maxlength="10" onload="sf()"></td>
    </tr>
  </table>

</form>
  
<br><img src="picture/ASPResponseWrite.gif" width="18" height="18"> Liste der 
    Mitteilungen<br>
    <?php if ($totalRows_rstmi > 0) { // Show if recordset not empty ?>
 
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#CCCCFF" class="value"> 
      <td width="238"><em><strong>Datum und Zeit</strong></em></td>
      <td width="492"><em></em></td>
    </tr>
    <?php
  $i=1;
   do { 
  $i=$i+1;
$d=intval($i/2);
  
  ?>
    <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?> > 
      <td    nowrap="nowrap"><div align="center"><strong> 
          <?php 
	  if ($row_rstmi['datum']== date("Y-m-d",time() )){
	  echo "heute";
	  } else{
	  
	  
	  echo $row_rstmi['datum'];}
	   ?>
          </strong><br>
          <?php echo substr($row_rstmi['Zeit'],0,5); ?> Uhr<br>
        </div></td>
      <td    nowrap="nowrap"><div align="center"><strong><?php echo utf8_decode(nl2br(($row_rstmi['name']))); ?><br>
          <br>
          </strong></div></td>
    </tr>
    <?php } while ($row_rstmi = mysql_fetch_assoc($rstmi)); ?>
  </table>
  <?php } ?>
  <?php if ($totalRows_rstmi == 0) { // Show if recordset empty ?>
  <br>Keine neuen Meldungen vorhanden.<br>
  <?php } // Show if recordset empty ?>
  <p> 
    <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
    <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
    <strong><font color="#FF0000"></font></strong> 
    <?php } // Show if recordset empty ?>
<br>	
  <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
  
  
 <?php 
 $i=0;
 do {
 $i++;
if ($i==6) {$i=1;}
if ($i==1) { 
  ?>
  <table  width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
 <?php }?>
  <td>
  
    <table width="148" border="0" cellpadding="10" cellspacing="0">
  <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td width="128" nowrap="nowrap"    bgcolor="#EEEEEE"><p align="center"><img src="user/<?php echo $row_rst2['idcard']; ?>.JPG" width="100" border="0"><br>
                <img src="picture/iconchance_16x16.gif" width="16" height="16">&nbsp; 
                <?php echo $row_rst2['name']; ?><br>
                <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
                <input name="edit" type="submit" id="edit" value="Anmelden">
                <br>
                <br>
              </p></td>
    </tr>
     </form>
  </table>
 

   </td>
<?php if ($i==5) {?> 
  </tr>
  </table>
 
<?php }?>
<input name="hurl_usid" type="hidden" id="hurl_usid3" value="<?php echo $row_rst2['id']; ?>">
<?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  <?php } // Show if recordset not empty ?>



    <input type="hidden" name="MM_update" value="form1">
  </p>




  <?php
mysql_free_result($rst1);

mysql_free_result($rstmi);

mysql_free_result($rst2);
?>
<?php include("footer.tpl.php"); ?>
