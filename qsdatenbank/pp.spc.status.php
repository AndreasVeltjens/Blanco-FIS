<?php 
/* Parameter  $fpruef_id als Bassisdaten*/
$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM pruefungen WHERE pruefungen.id_pruef='$pruef_id' ORDER BY pruefungen.gruppe, pruefungen.name";
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


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM pruefungdaten WHERE pruefungdaten.id_pruef=$row_rst2[id_pruef] and pruefungdaten.datum >=now()-interval $row_rst2[intervall] day AND pruefungdaten.lokz =0";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);
  

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT pruefungdaten.datum, pruefungdaten.messwert1, pruefungdaten.messwert2, pruefungdaten.messwert3, pruefungdaten.messwert4, pruefungdaten.messwert5, pruefungdaten.lokz, pruefungdaten.id_pruef FROM pruefungdaten WHERE pruefungdaten.id_pruef=$row_rst2[id_pruef] AND pruefungdaten.lokz =0 ORDER BY pruefungdaten.datum desc";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

do{
if ($row_rst2['formelid']==1){
		$ist=($row_rst6['messwert4']*$row_rst2['Umrechnungsfaktor'])/($row_rst6['messwert1']* $row_rst6['messwert2']*$row_rst6['messwert3']);
		}else{
		$ist=$row_rst6['messwert1']*$row_rst2['Umrechnungsfaktor'];
		}
 } while ($row_rst6 = mysql_fetch_assoc($rst6));
$ist=round($ist,2);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_wartungsuser = "SELECT * FROM `user` ORDER BY `user`.id";
$wartungsuser = mysql_query($query_wartungsuser, $qsdatenbank) or die(mysql_error());
$row_wartungsuser = mysql_fetch_assoc($wartungsuser);
$totalRows_wartungsuser = mysql_num_rows($wartungsuser);

?> 



<p><img src="picture/arrowClose.gif" width="8" height="9"> Pr&uuml;fobjekt<br>
  <font size="-7"><?php echo $row_rst2['name']; ?></font> <img src="picture/btnHelp.gif" alt="<?php echo $row_rst2['memo']; ?>" width="16" height="16"> 
  <br>
  <font size="1">Sollwert: <?php echo utf8_decode($row_rst2['soll']); ?><?php echo utf8_decode($row_rst2['einheit']); ?></font></p>
<img src="picture/arrowClose.gif" width="8" height="9"> letzter Wert<br>
  <?php if ($totalRows_rst3> 0) { // Show if recordset empty ?>

<table width="100" border="0" cellspacing="1" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td><font size="1">Datum</font></td>
    <td><div align="center"><font size="1">UWG</font></div></td>
    <td><div align="center">IST </div></td>
    <td><div align="center"><font size="1">OWG</font></div></td>
  </tr>
  <?php do { ?>
  <tr> 
    <td bgcolor="#00CC00"><font size="1"><?php echo substr($row_rst3['datum'],5,15); ?> <br>
      <?php
do {  
?>
      <?php if (!(strcmp($row_wartungsuser['id'], $row_rst3['user']))) {echo  utf8_decode($row_wartungsuser['name']);}?>
      <?php
} while ($row_wartungsuser = mysql_fetch_assoc($wartungsuser));
  $rows = mysql_num_rows($wartungsuser);
  if($rows > 0) {
      mysql_data_seek($wartungsuser, 0);
	  $row_wartungsuser = mysql_fetch_assoc($wartungsuser);
  }
?>
      </font></td>
    <td bgcolor="#00CC00"><div align="center"><font size="1">&nbsp;</font><font size="-7" face="Arial, Helvetica, sans-serif"> 
        <?php if (($ist<=$row_rst2['ugw'])) {echo "<img src=\"picture/b_tipp.png\" alt=\"$row_rst2[uegtxt]\">";}?>
        <?php echo utf8_decode($row_rst2['ugw']); ?> <?php echo utf8_decode($row_rst2['einheit']); ?></font></div></td>
    <td bgcolor="#00CC00"><div align="center"><font size="-7"><strong><?php echo utf8_decode($ist." ".$row_rst2['einheit']); ?></strong></font></div></td>
    <td bgcolor="#00CC00"><div align="center"><font size="1">&nbsp; 
        </font><font size="-7"><font face="Arial, Helvetica, sans-serif"> 
        <?php if (($ist>=$row_rst2['ogw'])) {echo "<img src=\"picture/b_tipp.png\" alt=\"$row_rst2[oegtxt]\">";}?>
        </font><?php echo utf8_decode($row_rst2['ogw']); ?><?php echo utf8_decode($row_rst2['einheit']); ?></font><font size="1">&nbsp; </font></div></td>
  </tr>
  <tr> 
    <td colspan="4"><hr></td>
  </tr>
  <?php } while ($row_rst3 = mysql_fetch_assoc($rst3)); ?>
</table>
      <?php } // Show if recordset not empty ?>
      <?php if ($totalRows_rst3 == 0) { // Show if recordset empty ?>
      <?php $errtxt=$errtxt."<br>Mindestens eine Wartung einer Anlage wurde nicht durchgef&uuml;hrt.";?>
      <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr> 
    <td bgcolor="#FF0000"><strong><font color="#FFFFFF" size="3"><img src="picture/sicherheitsicon_35x35.gif" width="35" height="35" align="absmiddle"> 
      <font size="2">keine <br>
      Pr&uuml;fung<br>
      dokumentiert.</font></font></strong></td>
        </tr>
      </table>
      <p class="blinkandred"><strong></strong></p>
      <?php } // Show if recordset empty ?>
<img src="picture/arrowClose.gif" width="8" height="9">Pr&uuml;fung neu anlegen<br>
<?php if ($url_user>0 && $HTTP_POST_VARS['hurl_id_pruef']<>$pruef_id) {?>
<form name="formneupruefung" method="post" action="<?php echo $editFormAction; ?>&action=NEUSPC">
 
    <input name="neumesswert" type="submit" class="buttongross" id="neu" value="Neu anlegen">
    <input name="hurl_id_pruef" type="hidden" id="hurl_id_pruef" value="<?php echo $pruef_id ?>">

  </form>
  <?php }?>
  
  <?php if (isset($HTTP_POST_VARS['neumesswert']) && $HTTP_POST_VARS['hurl_id_pruef']==$pruef_id) {?>
  <form action="" method="post" name="speichernmesswert" id="speichernmesswert">
  <table width="200" border="0" cellpadding="0" cellspacing="0" bgcolor="#99CCFF">
    <tr> 
      <td><?php echo $row_rst2['wert1text']; ?></td>
      <td> <input name="wert1" type="text" id="messwert1" value="<?php echo $row_rst4['messwert1']; ?>" size="12"> 
        <?php echo utf8_decode($row_rst2['wert1unit']); ?></td>
    </tr>
    <tr> 
      <td><?php echo $row_rst2['wert2text'] ?></td>
      <td> <input name="wert2" type="text" id="messwert2" value="<?php echo $row_rst4['messwert2']; ?>" size="12"> 
        <?php echo utf8_decode($row_rst2['wert2unit']); ?></td>
    </tr>
    <tr> 
      <td height="25"><?php echo $row_rst2['wert3text']; ?></td>
      <td> <input name="wert3" type="text" id="messwert3" value="keine" size="12"> 
        <?php echo utf8_decode($row_rst2['wert3unit']); ?></td>
    </tr>
    <tr> 
      <td><?php echo $row_rst2['wert4text']; ?></td>
      <td> <input name="wert4" type="text" id="messwert4" value="<?php echo $row_rst4['messwert4']; ?>" size="12"> 
        <?php echo utf8_decode($row_rst2['wert4unit']); ?></td>
    </tr>
    <tr> 
      <td><?php echo $row_rst2['wert5text']; ?></td>
      <td> <input name="wert5" type="text" id="messwert5" value="<?php echo $row_rst4['messwert5']; ?>" size="12"> 
        <?php echo utf8_decode($row_rst2['wert5unit']); ?></td>
    </tr>
  </table>
  <p> 
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
    <input name="anzahlwerte" type="hidden" id="anzahlwerte" value="<?php echo $row_rst2['anzahlwerte']; ?>">
  </p>
  <p>
    <input name="neuermesswert" type="submit" class="buttongross" id="neu" value="Speichern">
    <input name="hurl_id_pruef" type="hidden" id="hurl_id_pruef" value="<?php echo $pruef_id ?>">
  </p>
  </form>
  
  <?php }?>
<?php 
mysql_free_result($rst3);

mysql_free_result($rst6);

mysql_free_result($wartungsuser);
?>
