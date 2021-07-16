<?php require_once('Connections/qsdatenbank.php'); ?>
<?php $la = "la412";
include('fis.functions.php');
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

if ((isset($_GET["action"])) && $_GET['fdd_id']>0) {
  $updateSQL = sprintf("UPDATE fehlermeldungenadddata  SET fdd_lokz=%s WHERE fdd_id=%s",
                       GetSQLValueString(1, "int"),
                       GetSQLValueString($_GET['fdd_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la412.php?url_user=".$url_user."&url_fmid=".$url_fmid."&goback=".$goback;
  header(sprintf("Location: %s", $updateGoTo));
}


if ((isset($HTTP_POST_VARS["delete"] ))) {
  $updateSQL = sprintf("DELETE * FROM fehlermeldungenadddata WHERE fdd_id=%s",
                       GetSQLValueString($HTTP_POST_VARS['hurl_fdd_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la412.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["insert_weight"] ))) {
	$_POST['hurl_fdd_group']=2;  //mechansische Werte
	$_POST['hurl_fdd_class']=4; //Gewicht

  $insertSQL = sprintf("INSERT INTO fehlermeldungenadddata (fdd_fmid, fdd_class, fdd_group, fdd_value, fdd_date, fdd_user) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hurl_fmid'], "int"),
                       GetSQLValueString($_POST['hurl_fdd_class'], "int"),
					   GetSQLValueString($_POST['hurl_fdd_group'], "int"),
					   GetSQLValueString($_POST['hurl_fdd_value'], "text"),
					   "now()",
                       GetSQLValueString($_POST['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la412.php";
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($HTTP_POST_VARS["insert_electrical"] ))) {
	$_POST['hurl_fdd_group']=1;  //elektrische Werte
	$_POST['hurl_fdd_class1']=1; //Schutzleiterwiderstand
	$_POST['hurl_fdd_class2']=2; //Isolationswiderstand
	$_POST['hurl_fdd_class3']=3; //Kurzschlussstrom

  $insertSQL = sprintf("INSERT INTO fehlermeldungenadddata (fdd_fmid, fdd_class, fdd_group, fdd_value, fdd_date, fdd_user) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hurl_fmid'], "int"),
                       GetSQLValueString($_POST['hurl_fdd_class1'], "int"),
					   GetSQLValueString($_POST['hurl_fdd_group'], "int"),
					   GetSQLValueString($_POST['hurl_fdd_value1'], "text"),
					   "now()",
                       GetSQLValueString($_POST['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  
   $insertSQL = sprintf("INSERT INTO fehlermeldungenadddata (fdd_fmid, fdd_class, fdd_group, fdd_value, fdd_date, fdd_user) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hurl_fmid'], "int"),
                       GetSQLValueString($_POST['hurl_fdd_class2'], "int"),
					   GetSQLValueString($_POST['hurl_fdd_group'], "int"),
					   GetSQLValueString($_POST['hurl_fdd_value2'], "text"),
					   "now()",
                       GetSQLValueString($_POST['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

$insertSQL = sprintf("INSERT INTO fehlermeldungenadddata (fdd_fmid, fdd_class, fdd_group, fdd_value, fdd_date, fdd_user) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hurl_fmid'], "int"),
                       GetSQLValueString($_POST['hurl_fdd_class3'], "int"),
					   GetSQLValueString($_POST['hurl_fdd_group'], "int"),
					   GetSQLValueString($_POST['hurl_fdd_value3'], "text"),
					   "now()",
                       GetSQLValueString($_POST['hurl_user'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());

  $updateGoTo = "la412.php";
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
$query_rst2 = "SELECT * FROM fehlermeldungenadddata WHERE fehlermeldungenadddata.fdd_fmid='$url_fmid' AND fehlermeldungenadddata.fdd_lokz=0 ORDER BY fehlermeldungenadddata.fdd_group,fehlermeldungenadddata.fdd_date ";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);


if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la41.php?url_user=".$row_rst1['id']."&url_fmid=".$HTTP_POST_VARS['hurl_fmid']."&goback=".$HTTP_POST_VARS['goback'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}



include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
  <table width="980" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?></td>
    </tr>
    <tr> 
      <td width="114">Aktionen W&auml;hlen:</td>
      <td width="428"> <img src="picture/error.gif" width="16" height="16"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="438"> <div align="right"></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>FM-ID</td>
      <td>&nbsp; <?php echo $url_fmid ?></td>
      <td><input name="hurl_fmid" type="hidden" id="hurl_fmid" value="<?php echo $url_fmid; ?>"> 
        <input name="goback" type="hidden" value="<?php echo $goback ?>"> 
      </td>
    </tr>
    <tr> 
      <td>neu Anlegen:</td>
      <td><table width="423" border="0" cellspacing="0" cellpadding="0">
          <tr bgcolor="#9999FF"> 
            <td colspan="3"><strong>Elekropr&uuml;fwerte </strong> <div align="right"> 
              </div></td>
          </tr>
          <tr bgcolor="#9999FF"> 
            <td width="148">Datum:</td>
            <td width="185"> <input name="datum1" type="text" id="datum1" value="<?php echo $row_rst2['Datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'datum1\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script> </td>
            <td width="90">&nbsp;</td>
          </tr>
          <tr bgcolor="#9999FF"> 
            <td>&nbsp;</td>
            <td>&nbsp; </td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#9999FF"> 
            <td height="19">Schutzleiterwiderstand<br> </td>
            <td><input name="hurl_fdd_value1" type="text" id="hurl_fdd_value13" size="10">
              Ohm </td>
            <td><?php echo GetFehlermeldungAddonDataClass("allowed",1);?></td>
          </tr>
          <tr bgcolor="#9999FF"> 
            <td height="19">Isolationswiderstand </td>
            <td> <input name="hurl_fdd_value2" type="text" id="hurl_fdd_value23" size="10">
              MOhm </td>
            <td><?php echo GetFehlermeldungAddonDataClass("allowed",2);?></td>
          </tr>
          <tr bgcolor="#9999FF"> 
            <td height="19">Kurzschlussstrom <br> </td>
            <td><input name="hurl_fdd_value3" type="text" id="hurl_fdd_value33" size="10" maxlength="255">
              mA</td>
            <td><?php echo GetFehlermeldungAddonDataClass("allowed",3);?></td>
          </tr>
          <tr bgcolor="#9999FF"> 
            <td height="19">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="insert_electrical" type="submit" id="insert_electrical2" value="Speichern"></td>
          </tr>
        </table></td>
      <td><table width="475" border="0" cellspacing="0" cellpadding="0">
          <tr bgcolor="#FFCCFF"> 
            <td colspan="3"><strong>Gewichtsmessung durchf&uuml;hren</strong> 
              <div align="right"> </div></td>
          </tr>
          <tr bgcolor="#FFCCFF"> 
            <td width="154">Datum:</td>
            <td width="229"> <input name="datum2" type="text" id="datum2" value="<?php echo $row_rst2['Datum']; ?>"> 
              <script type="text/javascript">
                    <!--
                    document.write('<a title="<?php echo $strCalendar;?>" href="javascript:openCalendar(\'<?php echo $editFormAction;?>\', \'form1\', \'datum2\', \'date\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/b_calendar.png" alt="<?php echo $strCalendar; ?>"/></a>');
                    //-->
                    </script> </td>
            <td width="231">&nbsp;</td>
          </tr>
          <tr bgcolor="#FFCCFF"> 
            <td>&nbsp;</td>
            <td> <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>"></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#FFCCFF"> 
            <td height="19">Gewichtsmessung </td>
            <td><input name="hurl_fdd_value" type="text" id="hurl_fdd_value">
              kg </td>
            <td><?php echo GetFehlermeldungAddonDataClass("allowed",4);?></td>
          </tr>
          <tr bgcolor="#FFCCFF"> 
            <td height="19">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#FFCCFF"> 
            <td height="19">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="insert_weight" type="submit" id="insert_weight" value="Speichern"></td>
          </tr>
        </table>
        <br> </td>
    </tr>
    <tr> 
      <td colspan="3"><div align="right"><a href="getexcelfehlermeldungenadddata.php?suchtext=<?php echo $url_fmid;?>&addon=1" target="_blank">Download 
          als Excel</a></div></td>
    </tr>
    <tr> 
      <td colspan="3"> 
        <?php if ($totalRows_rst2>0){?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td width="14%"><strong>Gruppe</strong></td>
            <td width="16%"><strong>Art</strong></td>
            <td width="17%"><strong>Messwert</strong></td>
            <td width="14%"><strong>Grenzwert</strong></td>
            <td width="12%"><strong>Datum</strong></td>
            <td width="17%"><strong>Mitarbeiter</strong></td>
            <td width="10%">&nbsp;</td>
          </tr>
          <?php do {  ?>
          <tr> 
            <td><?php echo GetFehlermeldungAddonDataGroup("text",$row_rst2['fdd_group']); ?></td>
            <td><?php echo GetFehlermeldungAddonDataClass("text",$row_rst2['fdd_class']); ?></td>
            <td><?php echo $row_rst2['fdd_value']; ?> <?php echo GetFehlermeldungAddonDataClass("unit",$row_rst2['fdd_class']); ?></td>
            <td><?php echo GetFehlermeldungAddonDataClass("allowed",$row_rst2['fdd_class']); ?></td>
            <td><?php echo $row_rst2['fdd_date']; ?></td>
            <td><?php echo GetUserName($row_rst2['fdd_user']); ?></td>
            <td>
			<?php if ($row_rst1['id']==$row_rst2['fdd_user']){?>
			<div align="right"><a href="la412.php?url_user=<?php echo $url_user; ?>&url_fmid=<?php echo $url_fmid; ?>&action=delete&fdd_id=<?php echo $row_rst2['fdd_id']; ?>">entfernen</a></div></td>
          	<?php } ?>
		  </tr>
          <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
        </table>
        <?php } else {
		echo utf8_encode('Keine weiteren Messwerte für diese Fehlermeldung gefunden.');
		
		}?>
      </td>
    </tr>
    <tr>
      <td colspan="3"><div align="right"></div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p> 
    <input type="hidden" name="MM_insert" value="form1">
    <input type="hidden" name="MM_update" value="form1">
  </p>
</form>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);
?>
</p>

  <?php include("footer.tpl.php"); ?>
