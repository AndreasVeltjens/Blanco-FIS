<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la403";
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = (($theValue != "") ? "'" . $theValue . "'" : "NULL");
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

if (isset($HTTP_POST_VARS['save']) or isset($HTTP_POST_VARS['save1']) or isset($HTTP_POST_VARS['save3']) or isset($HTTP_POST_VARS['saveok'])  ){

if ($HTTP_POST_VARS["checkbox"]==1){$HTTP_POST_VARS['schutzleiter']=999999999;}
$updateSQL = sprintf("UPDATE fehlermeldungen SET schutzleiter=%s WHERE fmid=%s",
									GetSQLValueString($HTTP_POST_VARS['schutzleiter'], "int"),   
									   GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));
				
				  mysql_select_db($database_qsdatenbank, $qsdatenbank);
				  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
				
				$oktxt="Formulardaten gespeichert";
if ((($HTTP_POST_VARS['schutzleiter']<= 100 ) and ($HTTP_POST_VARS['schutzleiter']>=0)) or ($HTTP_POST_VARS["checkbox"]==1) or (isset($HTTP_POST_VARS["saveok"]))){
				
				if (!isset($HTTP_POST_VARS["saveaktuell"])){
				if (isset($goback)){$updateGoTo = "la41s.php";}else{
				if (isset($HTTP_POST_VARS["save"])){$updateGoTo = "la41.php";}
				if (isset($HTTP_POST_VARS["save1"])){$updateGoTo = "la41.php";}
				
				if (isset($HTTP_POST_VARS["saveok"]) or ($HTTP_POST_VARS["checkbox"]==1) ){$updateGoTo = "la41.php";}
				 }
				  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
					$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
					$updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
				  }
				  header(sprintf("Location: %s", $updateGoTo));
				}
				
				} else {
				$oktxt="Achtung Schutzleiter defekt!";
				if ($HTTP_POST_VARS['schutzleiter']==0){ $oktxt="Schutzleiterwiderstand messen!";}
				}
} /* end saveaktuell */



if ((isset($HTTP_POST_VARS["cancel"])) ) {
if (isset($goback)){$GoTo = "la42s.php?url_user=".$row_rst1['id'];}
else{ 
$GoTo = "la4.php?url_user=".$row_rst1['id'];
}
 header(sprintf("Location: %s", $GoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}




mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten ORDER BY artikeldaten.Bezeichnung";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);





mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmid='$url_fmid'";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);
$url_artikelid= $row_rst4['fmartikelid'];

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT linkfehlerartikel.lfaid, fehler.fid, fehler.fkurz, fehler.fname, fehler.lokz, linkfehlerartikel.fid, linkfehlerartikel.artikelid, linkfehlerartikel.lokz FROM fehler, linkfehlerartikel WHERE fehler.lokz=0 AND linkfehlerartikel.lokz=0 AND linkfehlerartikel.fid=fehler.fid AND linkfehlerartikel.artikelid='$url_artikelid' ORDER BY fehler.fkurz";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT fehler.fname, fehler.fkurz, fehler.fid, fehler.lokz, linkfehlerfehlermeldung.fmid, linkfehlerfehlermeldung.fid, linkfehlerfehlermeldung.lffid FROM linkfehlerfehlermeldung, fehler WHERE linkfehlerfehlermeldung.lokz=0 AND linkfehlerfehlermeldung.fmid='$url_fmid' AND fehler.fid =linkfehlerfehlermeldung.fid ORDER BY fehler.fkurz";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst7 = "SELECT entscheidung.entid, entscheidung.entname, entscheidung.lokz FROM entscheidung WHERE entscheidung.lokz = 0 ORDER BY entscheidung.entname";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstkunden = "SELECT * FROM kundendaten WHERE kundendaten.lokz=0 ORDER BY kundendaten.firma";
$rstkunden = mysql_query($query_rstkunden, $qsdatenbank) or die(mysql_error());
$row_rstkunden = mysql_fetch_assoc($rstkunden);
$totalRows_rstkunden = mysql_num_rows($rstkunden);

$suchtext=utf8_encode($row_rst4['fm1notes']);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstR = "SELECT kundendaten.idk, kundendaten.firma, kundendaten.strasse, kundendaten.plz, kundendaten.ort FROM kundendaten WHERE kundendaten.firma like '%$suchtext%' or kundendaten.ort like '%$suchtext%' or kundendaten.plz like '$suchtext' ORDER BY kundendaten.firma";
$rstR = mysql_query($query_rstR, $qsdatenbank) or die(mysql_error());
$row_rstR = mysql_fetch_assoc($rstR);
$totalRows_rstR = mysql_num_rows($rstR);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form name="form1" method="POST">
  <table width="700" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?> 
        - Schritt 2 von 3</strong></td>
    </tr>
    <tr> 
      <td colspan="3"> <?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> </td>
    </tr>
    <tr> 
      <td width="222">Aktionen W&auml;hlen:</td>
      <td width="159">&nbsp; </td>
      <td width="349"> <div align="right"><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
          <input name="save" type="submit" id="save" value="weiter">
        </div></td>
    </tr>
    <tr> 
      <td>Status:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"> <?php  include("la4.status.php");?> </td>
    </tr>
  </table>


  <p>&nbsp;</p>
  <table width="733" border="0" cellspacing="0" cellpadding="4">
    <tr> 
      <td width="336"><img src="picture/schutzleiter.gif" width="127" height="84"></td>
      <td width="251">&nbsp;</td>
      <td width="122"><input name="fm3user" type="hidden" id="fm3user2" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_user" type="hidden" id="hurl_user3" value="<?php echo $row_rst1['id']; ?>"> 
        <input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $row_rst4['fmid']; ?>"> 
      </td>
    </tr>
    <tr>
      <td>Ger&auml;t hat keinen Schutzleiter </td>
      <td><input type="checkbox" name="checkbox" value="1"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>Schutzleiterwiderstand, sofern vorhanden pr&uuml;fen</td>
      <td> <input name="schutzleiter" type="text" id="schutzleiter" value="<?php echo $row_rst4['schutzleiter']; ?>">
        Ohm </td>
      <td><img src="picture/b_newdb.png" width="16" height="16" align="absmiddle"> 
        <input name="save" type="submit" id="save" value="weiter"> </td>
    </tr>
    <tr> 
      <td colspan="2"><strong><font color="#FF0000" size="5"><?php echo $oktxt ?></font></strong></td>
      <td>
        <?php if (($row_rst4['schutzleiter'] > 100) and isset($HTTP_POST_VARS['save'])) { // Show if recordset not empty ?>
        <input name="saveok" type="submit" id="saveok" value="weiter"> 
        <?php } // Show if recordset not empty ?>
      </td>
    </tr>
  </table>
  <p><br>
  </p>
  <p>
    <input type="hidden" name="MM_update" value="form2">
  </p>
  </form>
<p> 
  <input type="hidden" name="MM_insert" value="form1">
</p>
<?php include("la4.rstuserst.php");?>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst6);

mysql_free_result($rst7);

mysql_free_result($rstkunden);

mysql_free_result($rstR);

mysql_free_result($rsttyp);



/* mysql_free_result($rstkundenR); */
?>
</p>

  <?php include("footer.tpl.php"); ?>
