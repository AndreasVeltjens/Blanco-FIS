<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php  $la = "mi11";
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
<table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td><p align="center"><br>
        <font size="9"> </font></p>
      <p align="center"><font size="9"><font size="10">Herzlich Willkommen</font> 
        </font></p>
      <p align="center"><font size="9">bei <strong><font color="#003399"><br>
        <font size="10">BL</font></font><font color="#003399"><font size="10">ANCO</font></font> 
        <font color="#666666"> <font color="#333333" size="6">Professional</font> 
        </font></strong><font color="#000000" size="6"> Kunststofftechnik GmbH</font></font></p>
      <p align="center"><font color="#999999" size="5">ein Unternehmen der </font></p>
      <p align="center"><font color="#333333" size="6">BLANCO Professional Gruppe</font></p>
      <p align="center"><img src="media/proffesionalgroup.jpg" width="980"></p>
      <p> 
        <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
        <?php
mysql_free_result($rst1);

mysql_free_result($rstmi);

?>
      </p>
</td>
  </tr>
</table>
<?php include("footer.tpl.php"); ?>
