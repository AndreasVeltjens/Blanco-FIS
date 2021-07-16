<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/nachrichten.php'); ?>
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

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM workflow WHERE workflow.status=0 or workflow.status=2 or workflow.status=1 or (workflow.status=3 and workflow.datum>subdate(now(),9)) ORDER BY workflow.status";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

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
}else{$errtxt = $errtxt."<br>alternativ Benutzername und Passwort eingeben.";}



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
        Mitarbeiter-Anmeldung</strong></td>
    </tr>
    <tr> 
      <td colspan="5"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td width="134" rowspan="5"><div align="right"> </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="94" rowspan="4"><div align="center"><img src="picture/W7_Img_Activate.jpg" width="66" height="66"></div></td>
      <td bgcolor="#FFFFFF">ID-Card</td>
      <td bgcolor="#FFFFFF"><img src="picture/icon_scan.gif" alt="Dieses Feld ist Barcodelesef&auml;hig"> 
        <input name="suchtext" type="password" id="suchtext" value="" size="17" maxlength="10" onload="sf()"></td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr> 
      <td width="134" bgcolor="#FFFFFF">Benutzername</td>
      <td width="184" bgcolor="#FFFFFF"> <input name="benutzername" type="text" id="benutzername" size="20" maxlength="20" > 
      </td>
      <td width="298" bgcolor="#FFFFFF"><input name="hiddenField" type="hidden" value="<?php echo $row_rst1['id']; ?>"></td>
    </tr>
    <tr> 
      <td bgcolor="#FFFFFF">Passwort</td>
      <td bgcolor="#FFFFFF"> <input name="passwort" type="password" id="passwort" size="20" maxlength="20"></td>
      <td bgcolor="#FFFFFF"><input name="anmelden" type="submit" id="anmelden" value="Anmelden">
      </td>
    </tr>
    <tr> 
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
  </table>
  <p><img src="picture/ASPResponseWrite.gif" width="18" height="18"> Liste der 
    Mitteilungen 
    <?php if ($totalRows_rstmi > 0) { // Show if recordset not empty ?>
  </p>
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
  <p>Keine neuen Meldungen vorhanden.</p>
  <?php } // Show if recordset empty ?>
  <p>&nbsp;</p>
  <p><strong><em><img src="picture/s_process.png" width="16" height="16"> &Uuml;bersicht 
    Workflows</em></strong></p>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr class="value"> 
      <td width="100"><em>Status</em></td><td width="50"><em> Nummer</em></td><td width="200"><em>Bezeichnung</em></td><td width="100"><em>Seit</em></td><td>User</td><td width="0">&nbsp;</td>
    </tr>
    <?php do { ?>
    <?php   if ($row_rst2['typ']!=$typ){?>
    <tr bgcolor="#CCCCCC" > 
      <td colspan="9" nowrap="nowrap">&nbsp;</td></tr>
    <?php 	}?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
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
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['id_work']; ?></td><td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['name']; ?></td><td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['datum']; ?></td><td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><?php echo utf8_decode($row_rst2['erstellt']); ?></div></td><td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"></div></td>
    </tr>
    <?php
    $typ=$row_rst2['typ'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
  </table>
  <p>&nbsp;</p>
  <p> 
    <input type="hidden" name="MM_update" value="form1">
  </p>
</form>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rstmi);
?>
<?php include("footer.tpl.php"); ?>
