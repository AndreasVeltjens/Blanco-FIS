<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/codatenbank.php'); ?>
<?php require_once('Connections/codatenbank.php'); ?>
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

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["MM_insert"])) && ($HTTP_POST_VARS["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO kostenstellengruppen (kurzzeichen, beschreibung, id_subks, verantwortlicher, werk) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['kurzzeichen'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['id_subks'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['verantwortlicher'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['werk'], "text"));

  mysql_select_db($database_codatenbank, $codatenbank);
  $Result1 = mysql_query($insertSQL, $codatenbank) or die(mysql_error());
}
 $la = "org1";

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["umgliedern"]))) {
  $updateSQL = sprintf("UPDATE kostenstellengruppen SET id_subks=%s, lokz=%s WHERE id_ks=%s",
                       GetSQLValueString($HTTP_POST_VARS['select'], "text"),
                       GetSQLValueString(isset($HTTP_POST_VARS['lokz']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_usid'], "int"));

  mysql_select_db($database_codatenbank, $codatenbank);
  $Result1 = mysql_query($updateSQL, $codatenbank) or die(mysql_error());
  $oktxt="ausgewählte Organisationseinheit umgeliedert.";
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_codatenbank, $codatenbank);
$query_rst2 = "SELECT * FROM kostenstellengruppen WHERE kostenstellengruppen.id_subks=0 or kostenstellengruppen.id_subks=''";
$rst2 = mysql_query($query_rst2, $codatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);



if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "start.php?url_user=".$row_rst1['id'];
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}
if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "hr12.php?url_user=".$row_rst1['id']."&url_usid=".$HTTP_POST_VARS["hurl_usid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["schulungedit"])) ) {
$updateGoTo = "hr22.php?url_user=".$row_rst1['id']."&url_usid=".$HTTP_POST_VARS["hurl_usid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["personalakte"])) ) {
$updateGoTo = "hr3.php?url_user=".$row_rst1['id']."&url_usid=".$HTTP_POST_VARS["hurl_usid"];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}


if ((isset($HTTP_POST_VARS["profile"])) ) {
$updateGoTo = "hr14.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["schulung"])) ) {
$updateGoTo = "hr23.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["tatigkeit"])) ) {
$updateGoTo = "hr4.php?url_user=".$row_rst1['id'];
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if (!isset($HTTP_POST_VARS['anzeige'])){$HTTP_POST_VARS['anzeige']=5;}
$anzeige=$HTTP_POST_VARS['anzeige'];

include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="3"><strong><?php echo $Modul[$d][0]." "; echo $Modul[$d][1]; ?></strong></td>
    </tr>
    <tr> 
      <td colspan="3"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an.</font></strong> <br> <?php } // Show if recordset empty ?>
        <font color="#FF0000"><?php echo $errtxt ?></font> <font color="#009900"><?php echo $oktxt ?></font> 
        <br> </td>
    </tr>
    <tr> 
      <td width="145">Aktionen W&auml;hlen:</td>
      <td width="81"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
        <img src="picture/b_drop.png" width="16" height="16"> </td>
      <td width="504"> <div align="right"><img src="picture/iconFixedprice_16x16.gif" width="16" height="16">
          <select name="anzeige" id="anzeige">
            <option value="1" <?php if (!(strcmp(1, $HTTP_POST_VARS['anzeige']))) {echo "SELECTED";} ?>>nur 
            Hauptebenen</option>
            <option value="2" <?php if (!(strcmp(2, $HTTP_POST_VARS['anzeige']))) {echo "SELECTED";} ?>>bis 
            1. Stufe</option>
            <option value="3" <?php if (!(strcmp(3, $HTTP_POST_VARS['anzeige']))) {echo "SELECTED";} ?>>bis 
            2. Stufe</option>
            <option value="4" <?php if (!(strcmp(4, $HTTP_POST_VARS['anzeige']))) {echo "SELECTED";} ?>>bis 
            3. Stufe</option>
            <option value="5" <?php if (!(strcmp(5, $HTTP_POST_VARS['anzeige']))) {echo "SELECTED";} ?>>alle 
            Stufen anzeigen</option>
          </select>
          <input name="sortieren" type="submit" id="sortieren" value="Sortieren">
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>"></td>
      <td><div align="right"> 
          <script language="JavaScript" type="text/JavaScript">
function sf(){document.form2.suchtext.focus()}
</script> 
          <p><input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
        </p></div></td>
    </tr>
  </table>
  <br>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <strong><font color="#FF0000">Kein Datensatz verf&uuml;gbar.</font></strong> 
  <strong><font color="#FF0000"></font></strong> 
  <?php } // Show if recordset empty ?>
</form>
  
    <?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="73"><em><strong>Org-ID</strong></em></td>
    <td width="112"><em><strong>Name</strong></em></td>
    <td width="83"><em><strong>aktiv/ Status</strong></em></td>
    <td width="90"><em><strong>Verantwortlicher</strong></em></td>
    <td width="107"><em><strong>Beschreibung</strong></em></td>
    <td width="265">&nbsp;</td>
  </tr>
  

  <?php do { ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    
  <?php $layout=1;?>
  <?php include("org1.tmp.php"); ?>
	
					<?php /* 1. Untergruppe */
					mysql_select_db($database_codatenbank, $codatenbank);
				$query_rst3 = "SELECT * FROM kostenstellengruppen WHERE kostenstellengruppen.id_subks=$row_rst2[id_ks]";
				$rst3 = mysql_query($query_rst3, $codatenbank) or die(mysql_error());
				$row_rst3 = mysql_fetch_assoc($rst3);
				$totalRows_rst3 = mysql_num_rows($rst3);
				?>
				
				<?php if ($totalRows_rst3 > 0) { // Show if recordset not empty ?>
				<?php do{ ?>
				<?php $layout=2;?>
				<?php include("org1.tmp.php"); ?>

		
										
							<?php /* 3. Untergruppe */
							
							mysql_select_db($database_codatenbank, $codatenbank);
							$query_rst4 = "SELECT * FROM kostenstellengruppen WHERE kostenstellengruppen.id_subks=$row_rst3[id_ks]";
							$rst4 = mysql_query($query_rst4, $codatenbank) or die(mysql_error());
							$row_rst4 = mysql_fetch_assoc($rst4);
							$totalRows_rst4 = mysql_num_rows($rst4);
							?>
							<?php if ($totalRows_rst4 > 0) { // Show if recordset not empty ?>
							<?php do{ ?>
							<?php $layout=3;?>
							<?php include("org1.tmp.php"); ?>
			
									<?php /* 4. Untergruppe */
									
									mysql_select_db($database_codatenbank, $codatenbank);
									$query_rst5 = "SELECT * FROM kostenstellengruppen WHERE kostenstellengruppen.id_subks=$row_rst4[id_ks]";
									$rst5 = mysql_query($query_rst5, $codatenbank) or die(mysql_error());
									$row_rst5 = mysql_fetch_assoc($rst5);
									$totalRows_rst5 = mysql_num_rows($rst5);
									?>
				
									<?php if ($totalRows_rst5 > 0) { // Show if recordset not empty ?>
 								    <?php do{ ?>
									<?php $layout=4;?>
									<?php include("org1.tmp.php"); ?>	
									
												<?php /* 5. Untergruppe */
												
												mysql_select_db($database_codatenbank, $codatenbank);
												$query_rst6 = "SELECT * FROM kostenstellengruppen WHERE kostenstellengruppen.id_subks=$row_rst5[id_ks]";
												$rst6 = mysql_query($query_rst6, $codatenbank) or die(mysql_error());
												$row_rst6 = mysql_fetch_assoc($rst6);
												$totalRows_rst6 = mysql_num_rows($rst6);
												?>
							
												<?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
												<?php do{ ?>
															<?php $layout=5;?>
															<?php include("org1.tmp.php"); ?>	
															
												<?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
												<?php } ?>

									
									
									
									
									<?php } while ($row_rst5 = mysql_fetch_assoc($rst5)); ?>
									<?php } ?>
						<?php } while ($row_rst4 = mysql_fetch_assoc($rst4)); ?>
						<?php } ?>	
				<?php } while ($row_rst3 = mysql_fetch_assoc($rst3)); ?>
				<?php } ?>
    <input type="hidden" name="MM_update" value="form1">		
								
  </form>
  <?php } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
<?php } // Show if recordset not empty ?>

<p>&nbsp;<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
  <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a> 
</p>
<p>&nbsp;Statistik: <?php echo $totalRows_rst2 ?> Organisationsebenen angelegt.</p>
<p><em><strong><img src="picture/b_insrow.png" width="16" height="16" align="absmiddle"> 
  Neue Organisiationseinheit anlegen:</strong></em></p>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="730" border="0" cellpadding="1" cellspacing="0" bgcolor="#CCCCFF">
    <tr> 
      <td width="144">Kurzzeichen</td>
      <td width="144">Verantwortlicher</td>
      <td width="152">Beschreibung</td>
      <td width="171">Werk und Zuordnung</td>
      <td width="109">&nbsp;</td>
    </tr>
    <tr> 
      <td> <input name="kurzzeichen" type="text" id="kurzzeichen"></td>
      <td> <input name="verantwortlicher" type="text" id="verantwortlicher"></td>
      <td> <input name="beschreibung" type="text" id="beschreibung"></td>
      <td><input name="werk" type="text" id="werk" size="8" maxlength="4"> <select name="id_subks" class="csslistbox" id="id_subks">
          <option value="0" <?php if (!(strcmp(0, $row_rst5['id_subks']))) {echo "SELECTED";} ?>>Hauptebene</option>
          <?php
mysql_select_db($database_codatenbank, $codatenbank);
$query_rst10 = "SELECT * FROM kostenstellengruppen ORDER BY kostenstellengruppen.kurzzeichen";
$rst10 = mysql_query($query_rst10, $codatenbank) or die(mysql_error());
$row_rst10 = mysql_fetch_assoc($rst10);
$totalRows_rst10 = mysql_num_rows($rst10);
?>
		  <?php
do {  
?>
          <option value="<?php echo $row_rst10['id_ks']?>"<?php if (!(strcmp($row_rst10['id_ks'], $row_rst5['id_subks']))) {echo "SELECTED";} ?>><?php echo $row_rst10['kurzzeichen']?></option>
          <?php
} while ($row_rst10 = mysql_fetch_assoc($rst10));
  $rows = mysql_num_rows($rst10);
  if($rows > 0) {
      mysql_data_seek($rst10, 0);
	  $row_rst10 = mysql_fetch_assoc($rst10);
  }
?>
        </select></td>
      <td> <div align="right"> 
          <input name="neu" type="submit" id="neu" value="Neu anlegen">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>
<p>&nbsp;</p>
<p> 
  <?php
mysql_free_result($rst1);

mysql_free_result($rst3);

mysql_free_result($rst2);



?>
</p>

  
<?php include("footer.tpl.php"); ?>
