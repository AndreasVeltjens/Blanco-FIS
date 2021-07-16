<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php
$oktxt="";
$errtxt="";
if ((isset($HTTP_POST_VARS['bildloeschen'])) && ($HTTP_POST_VARS['fmp_id'] != "") ) {
  $deleteSQL = sprintf("DELETE FROM fehlermeldungenpicture WHERE fmp_id=%s",
                       GetSQLValueString($HTTP_POST_VARS['fmp_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
$oktxt.=" Bild entfernt.";
}

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}




if ((isset($HTTP_POST_VARS["Bild"]))&&  $HTTP_POST_FILES['imageFile']['name']<>"") {
	// Bild hochgeladen
            $originalFileName = $HTTP_POST_FILES['imageFile']['name'];
            $uploadFileName = "$url_fmid_".uniqid($originalFileName).".".substr($originalFileName,-3);
			$oktxt.= "neues Logo hochgeladen und temporär gespeichert. ".$HTTP_POST_FILES['imageFile']['name']."\n ";
		
		 // Wenn 2.Checkbox angeklickt aber kein neues Bild hochgeladen wird
        if ($HTTP_POST_FILES['imageFile']['name']=="")
        {  $uploadFileName = $HTTP_POST_VARS['orig_file_name'];
			$oktxt.= "Logo-Datei wurde nicht erneuert. Alte Datei $uploadFileName wird verwendet und beibehalten.\n";
        }

$insertSQL = sprintf("INSERT INTO fehlermeldungenpicture (fmp_datum, fmp_user, link_dokument, fmp_bemerkung, fmp_fm_id) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['fmp_datum'], "date"),
                       GetSQLValueString($HTTP_POST_VARS['fmp_user'], "text"),
                       GetSQLValueString($uploadFileName , "text"),
                       GetSQLValueString($HTTP_POST_VARS['fmp_bemerkung']." ", "text"),
                       GetSQLValueString($HTTP_POST_VARS['fmp_fm_id'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
  // *** UPLOAD ***
    	
    	// Nur wenn Upload-file und Brancheneintrag in DB geklappt hat dann kopiere
    	// Upload-File ins upload-verz.
    	if ( $originalFileName<>"" && $Result1)
    	{
    	    $destPath = "../documents/fehlermeldungsbilder";
    	    copy($HTTP_POST_FILES['imageFile']['tmp_name'], $destPath."/".$uploadFileName);
			$oktxt.= "Bilddatei im Ordner Fehlermeldungsbilder gespeichert.";
    	}
    	else {
    	    @unlink($HTTP_POST_FILES['imageFile']['tmp_name']);
    	
    	// *** ENDE ***
		
		$oktxt.= "unlink Bilddatei";
		}

} /* ende anlegen */



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstfmp = "SELECT * FROM fehlermeldungenpicture WHERE fehlermeldungenpicture.fmp_fm_id='$url_fmid'";
$rstfmp = mysql_query($query_rstfmp, $qsdatenbank) or die(mysql_error());
$row_rstfmp = mysql_fetch_assoc($rstfmp);
$totalRows_rstfmp = mysql_num_rows($rstfmp);



?>
<hr>
<p><strong><font color="#FF0000"><?php echo $errtxt; ?></font></strong></p>
<font color="#006600"><strong><?php echo $oktxt; ?> </strong></font> 
<form name="formfmp" method="POST" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
  <p><strong><em>Bild ausw&auml;hlen</em></strong></p>
  <table width="980" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr bgcolor="#FFFFCC"> 
      <td width="214"><font size="2" face="Arial, Helvetica, sans-serif">Materialbild 
        zum Server hochladen:</font></td>
      <td width="766"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <INPUT name="imageFile" type="file" size="30" accept="image/*">
          <BR>
          </font></div></td>
    </tr>
    <tr bgcolor="#FFFFCC"> 
      <td>Beschreibung hinzuf&uuml;gen</td>
      <td><input name="fmp_bemerkung" type="text" id="fmp_bemerkung" size="120" maxlength="255"></td>
    </tr>
    <tr bgcolor="#FFFFCC">
      <td>Datum</td>
      <td><input name="fmp_datum" type="text" value="<?php echo date("Y-m-d",time());?>">
            <font size="1"></td>
    </tr>
    <tr bgcolor="#FFFFCC"> 
      <td width="214" rowspan="14">&nbsp;</td>
      <td width="766" rowspan="14"> 
        <input name="fmp_user" type="hidden" id="fmp_user" value="<?php echo $row_rst1['id']; ?>">
        <input name="fmp_fm_id" type="hidden" value="<?php echo $url_fmid; ?>">
        <div align="right">
          <input name="Bild" type="submit" id="Bild" value="Bild hochladen">
        </div></td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr> </tr>
    <tr> </tr>
    <tr> </tr>
    <tr> </tr>
    <tr> </tr>
    <?php if ($totalRows_rstppp == 0) { // Show if recordset empty ?>
    <?php } // Show if recordset empty ?>
    <?php if ($totalRows_rstppp > 0) { // Show if recordset not empty ?>
    <?php } // Show if recordset not empty ?>
  </table>

  <input type="hidden" name="MM_insert" value="formfmp">
</form>
<hr>
<p><em><strong>Liste der Dokumente und Bilder</strong></em></p>
<?php if ($totalRows_rstfmp > 0) { // Show if recordset not empty ?>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="215"><em>Bild</em></td>
    <td width="340"><em>Bemerkung</em></td>
    <td width="175"><em>Aktion</em></td>
  </tr>
    <?php do { ?>
    <tr> 
	 <form name="formfmp_edit" method="post" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
       <td><font size="2" face="Arial, Helvetica, sans-serif"><a href="<? echo "../documents/fehlermeldungsbilder/".$row_rstfmp['link_dokument']?>" target="_blank"><img src="<? echo "../documents/fehlermeldungsbilder/".$row_rstfmp['link_dokument']?>" alt="Anzeigen Vollbild" width="150" border="0"></a></font></td>
      <td><p><?php echo $row_rstfmp['fmp_bemerkung']; ?></p>
        <p>&nbsp;</p>
        <p>&nbsp;<?php echo $row_rstfmp['fmp_datum']; ?><br>
          <?php
	  
	  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuserst = "SELECT * FROM `user`";
$rstuserst = mysql_query($query_rstuserst, $qsdatenbank) or die(mysql_error());
$row_rstuserst = mysql_fetch_assoc($rstuserst);
$totalRows_rstuserst = mysql_num_rows($rstuserst);
	  
do {  
?>
          <?php if (!(strcmp($row_rstuserst['id'], $row_rstfmp['fmp_user']))) {echo $row_rstuserst['name'];}?>
          <?php
} while ($row_rstuserst = mysql_fetch_assoc($rstuserst));
  $rows = mysql_num_rows($rstuserst);
  if($rows > 0) {
      mysql_data_seek($rstuserst, 0);
	  $row_rstuserst = mysql_fetch_assoc($rstuserst);
  }
?>
        </p></td>
      <td><p><font size="2" face="Arial, Helvetica, sans-serif"> 
          <? if ($row_rstfmp[link_dokument] <> "") { ?>
          <input name="bildloeschen" type="submit" value="Bild entfernen">
          <? } ?>
          <br>
          <br>
          <input type="hidden" name="orig_file_name" value="<? echo $row_rstfmp['link_dokument']?>">
          </font> <br>
          <font size="1"> 
          <?php $openprint="../documents/fehlermeldungsbilder/".$row_rstfmp['link_dokument']; ?>
          <script type="text/javascript">
                    <!--
                    document.write('<a title="Druckansicht Bild. " href="javascript:openprint(\'<?php echo $openprint;?>\')"><img class="calendar" src="<?php echo $pmaThemeImage; ?>./picture/btn_r7_c1.gif" alt="Druckansicht Materialbild"/></a>');
                    //-->
                    </script>
          <a href="../documents/fehlermeldungsbilder/<?php echo $row_rstfmp['Nummer'] ?>.png"></a></font></p>
        <input name="fmp_id" type="hidden" value="<?php echo $row_rstfmp['fmp_id']; ?>"></td></form>
    </tr>
    <?php } while ($row_rstfmp = mysql_fetch_assoc($rstfmp)); ?>
    
  
</table>
<?php } // Show if recordset not empty ?>
<p>
  <?php if ($totalRows_rstfmp == 0) { // Show if recordset empty ?>
  
  <strong><font color="#FF0000">Keine Bilder gespeichert.</font></strong>
  <?php } // Show if recordset empty ?>
</p>

<font size="1">insgesamt <?php echo $totalRows_rstfmp ?> Bilder vorhanden.</font> 
<hr>
<?php
mysql_free_result($rstfmp);
?>
