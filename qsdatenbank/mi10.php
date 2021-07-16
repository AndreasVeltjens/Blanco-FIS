<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php  $la = "mi10";
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_nachrichten, $nachrichten);
$query_rstmi = "SELECT * FROM mitteilung WHERE (mitteilung.datum)>= date(curdate()) and (mitteilung.lokz=0) ORDER BY mitteilung.datum";
$rstmi = mysql_query($query_rstmi, $nachrichten) or die(mysql_error());
$row_rstmi = mysql_fetch_assoc($rstmi);
$totalRows_rstmi = mysql_num_rows($rstmi);


include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>
<?php if ($totalRows_rstmi > 0) { // Show if recordset not empty ?>
<br>
<table width="980" border="0" cellpadding="5" cellspacing="0">
  <tr bgcolor="#FFFFFF" class="value"> 
    <td colspan="2"><a href="http://lznt02"><img src="picture/email.gif" width="52" height="36" border="0" align="absmiddle"></a> 
      Liste der Mitteilungen</td>
  </tr>
  <tr bgcolor="#CCCCFF" class="value"> 
    <td width="273"><em><strong>Datum und Zeit</strong></em></td>
    <td width="707"><em></em></td>
  </tr>
  <?php
  $i=1;
   do { 
  $i=$i+1;
$d=intval($i/2);
  
  ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr <?php if ($i/$d==2) {echo "bgcolor=\"#CCCCFF\"";}?> > 
      <td    nowrap="nowrap"><div align="center"><font size="4"><strong> 
          <?php 
	  if ($row_rstmi['datum']== date("Y-m-d",time() )){
	  echo "heute";
	  } else{
	  
	  
	  echo $row_rstmi['datum'];}
	   ?>
          </strong></font><font size="6"><br>
          <?php echo substr($row_rstmi['Zeit'],0,5); ?> Uhr<br>
          <br>
          </font></div></td>
      <td    nowrap="nowrap"><div align="center"><font size="6"><strong><font face="Times New Roman, Times, serif"><?php echo nl2br(utf8_decode($row_rstmi['name'])); ?></font><font size="3"><br><?php echo nl2br(utf8_decode($row_rstmi['beschreibung'])); ?></font>
          </strong></font></div></td>
    </tr>
    <input type="hidden" name="MM_update" value="form1">
  </form>
  <?php } while ($row_rstmi = mysql_fetch_assoc($rstmi)); ?>
</table>
<br>
<?php if ($palettenzahl < $palettenzahlL) {echo "<br><strong>Es muss noch gearbeitet werden !</strong>";}
?>
<?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rstmi == 0) { // Show if recordset empty ?>
<p align="center"><font size="9"><br>
  <br>
  <font size="10">Herzlich Willkommen</font></font><font size="9"><strong><br>
  </strong>bei <strong><font color="#003399"><br>
  <font size="10">BL</font></font><font color="#003399"><font size="10">ANCO</font></font> 
  <font color="#666666"> <font color="#333333" size="6">Professional</font> </font></strong><font color="#000000" size="6"> 
  Kunststofftechnik GmbH</font></font></p>
<p align="center"><font color="#999999" size="5">ein Unternehmen der </font></p>
<p align="center"><font color="#333333" size="6">BLANCO Professional Gruppe</font></p>
<p align="center"><font size="6"><img src="media/proffesionalgroup.jpg" width="980"></font></p>
<?php } // Show if recordset empty ?>
<p> 
  <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  <?php
mysql_free_result($rst1);

mysql_free_result($rstmi);

?>
</p>
<?php include("footer.tpl.php"); ?>
