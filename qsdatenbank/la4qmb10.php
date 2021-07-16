<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php  $la = "la4qmb10";
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rstmi = "SELECT * FROM qmbmitteilungen WHERE (qmbmitteilungen.datum)>= date(curdate()) and (qmbmitteilungen.lokz=0) ORDER BY qmbmitteilungen.datum";
$rstmi = mysql_query($query_rstmi, $nachrichten) or die(mysql_error());
$row_rstmi = mysql_fetch_assoc($rstmi);
$totalRows_rstmi = mysql_num_rows($rstmi);


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<?php if ($totalRows_rstmi > 0) { // Show if recordset not empty ?>

<img src="picture/email.gif" width="52" height="36" align="absmiddle"> Liste der Mitteilungen<br>
<table width="780" border="0" cellpadding="0" cellspacing="0">
  <tr bgcolor="#CCCCFF" class="value"> 
    <td width="238"><em><strong>Information</strong></em></td>
    <td width="492"><em></em></td>
  </tr>
  <?php
  $i=1;
   do { 
  $i=$i+1;
$d=intval($i/2);
  
  ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?> > 
      <td height="18"    nowrap="nowrap"><div align="left"><font size="4"><strong> 
          <font size="2"> <img src="picture/iconMessage_16x16.gif" width="16" height="16" align="absmiddle"> 
          <?php 
	  if ($row_rstmi['datum']== date("Y-m-d",time() )){
	  echo "heute";
	  } else{
	  
	  
	  echo $row_rstmi['datum'];}
	   ?>
          </font></strong></font><font size="2"> - </font><font size="2"> <?php echo substr($row_rstmi['Zeit'],0,5); ?> 
          Uhr</font><font size="6"> </font></div></td>
      <td    nowrap="nowrap"><div align="left"><font size="6"><strong><font size="2"><?php echo nl2br(utf8_decode($row_rstmi['name'])); ?></font> 
          </strong></font></div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form1">
  </form>
  <?php } while ($row_rstmi = mysql_fetch_assoc($rstmi)); ?>
</table>
<?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rstmi == 0) { // Show if recordset empty ?>
  
<p>Keine weiteren Informationen vom Qualit&auml;tsbeauftragten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  <?php
mysql_free_result($rst1);

mysql_free_result($rstmi);

?>
</p>
<?php include("footer.tpl.php"); ?>
