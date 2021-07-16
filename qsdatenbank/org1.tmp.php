<?php require_once('Connections/codatenbank.php'); ?>
<?php
mysql_select_db($database_codatenbank, $codatenbank);
$query_rst10 = "SELECT * FROM kostenstellengruppen ORDER BY kostenstellengruppen.kurzzeichen";
$rst10 = mysql_query($query_rst10, $codatenbank) or die(mysql_error());
$row_rst10 = mysql_fetch_assoc($rst10);
$totalRows_rst10 = mysql_num_rows($rst10);
?>
<style type="text/css">
<!--
.csslistbox {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	font-style: normal;
	line-height: normal;
	font-weight: normal;
	color: #000066;
	background-color: #CCCCCC;
	background-position: left;
	height: auto;
	width: 100px;
	border: thin none;
	list-style-type: circle;
	padding: 0px;
	float: none;
}
-->
</style>
<? if ($layout==1){ ?>
<tr onmouseover="setPointer(this, 1, 'over', '#CCCCCC', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#CCCCCC', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#CCCCCC', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#CCCCCC" nowrap="nowrap"><?php echo $row_rst2['werk']; ?></td>
      
  <td    bgcolor="#CCCCCC" nowrap="nowrap"><img src="picture/add.gif" width="15" height="14"><img src="picture/0.gif" width="1" height="10">&nbsp; 
    <?php echo $row_rst2['kurzzeichen']; ?></td>
      <td    bgcolor="#CCCCCC" nowrap="nowrap"> 
        <input <?php if (!(strcmp($row_rst2['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"> 
      </td>
      <td    bgcolor="#CCCCCC" nowrap="nowrap"><?php echo $row_rst2['verantwortlicher']; ?></td>
      <td    bgcolor="#CCCCCC" nowrap="nowrap">&nbsp;<?php echo $row_rst2['beschreibung']; ?> </td>
      <td    bgcolor="#CCCCCC" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_usid" type="hidden" class="csslistbox" id="hurl_usid" value="<?php echo $row_rst2['id_ks']; ?>">
      <a href="org2.php?url_id_ks=<?php echo $row_rst2['id_ks']; ?>&url_user=<?php echo $row_rst1['id']; ?>"><img src="picture/b_edit.png" width="16" height="16" border="0"></a> 
      <select name="select" class="csslistbox">
        <option value="0" <?php if (!(strcmp(0, $row_rst2['id_subks']))) {echo "SELECTED";} ?>>Hauptebene</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rst10['id_ks']?>"<?php if (!(strcmp($row_rst10['id_ks'], $row_rst2['id_subks']))) {echo "SELECTED";} ?>><?php echo $row_rst10['kurzzeichen']?></option>
        <?php
} while ($row_rst10 = mysql_fetch_assoc($rst10));
  $rows = mysql_num_rows($rst10);
  if($rows > 0) {
      mysql_data_seek($rst10, 0);
	  $row_rst10 = mysql_fetch_assoc($rst10);
  }
?>
      </select>
      <input name="umgliedern" type="submit" id="umgliedern" value="&gt;&gt;">
    </div></td>
    </tr>
<?php } elseif ($layout==2 and $anzeige>=2){ ?> 	
	 <tr onmouseover="setPointer(this, 1, 'over', '#E4E4E4', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#E4E4E4', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#E4E4E4', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#E4E4E4" nowrap="nowrap"><?php echo $row_rst3['id_ks']; ?></td>
      
  <td    bgcolor="#E4E4E4" nowrap="nowrap"><img src="picture/1.gif" width="20" height="10">&nbsp; 
    <?php echo $row_rst3['kurzzeichen']; ?></td>
      <td    bgcolor="#E4E4E4" nowrap="nowrap"> 
        <input <?php if (!(strcmp($row_rst3['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"> 
      </td>
      <td    bgcolor="#E4E4E4" nowrap="nowrap"><?php echo $row_rst3['verantwortlicher']; ?></td>
      <td    bgcolor="#E4E4E4" nowrap="nowrap">&nbsp;<?php echo $row_rst3['beschreibung']; ?> </td>
      <td    bgcolor="#E4E4E4" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_usid" type="hidden" id="hurl_usid" value="<?php echo $row_rst3['id_ks']; ?>">
      <a href="org2.php?url_id_ks=<?php echo $row_rst3['id_ks']; ?>&url_user=<?php echo $row_rst1['id']; ?>"><img src="picture/b_edit.png" width="16" height="16" border="0"></a> 
      <select name="select" class="csslistbox" id="select">
        <option value="0" <?php if (!(strcmp(0, $row_rst3['id_subks']))) {echo "SELECTED";} ?>>Hauptebene</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rst10['id_ks']?>"<?php if (!(strcmp($row_rst10['id_ks'], $row_rst3['id_subks']))) {echo "SELECTED";} ?>><?php echo $row_rst10['kurzzeichen']?></option>
        <?php
} while ($row_rst10 = mysql_fetch_assoc($rst10));
  $rows = mysql_num_rows($rst10);
  if($rows > 0) {
      mysql_data_seek($rst10, 0);
	  $row_rst10 = mysql_fetch_assoc($rst10);
  }
?>
      </select>
      <input name="umgliedern" type="submit" id="umgliedern" value="&gt;&gt;">
    </div></td>
    </tr>
<?php } elseif ($layout==3 and $anzeige>=3){ ?> 	
	
		 <tr onmouseover="setPointer(this, 1, 'over', '#E1E1E1', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#E1E1E1', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#E1E1E1', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#E1E1E1" nowrap="nowrap"><?php echo $row_rst4['id_ks']; ?></td>
      
  <td    bgcolor="#E1E1E1" nowrap="nowrap"><img src="picture/2.gif" width="40" height="10">&nbsp; 
    <?php echo $row_rst4['kurzzeichen']; ?></td>
      <td    bgcolor="#E1E1E1" nowrap="nowrap"> 
        <input <?php if (!(strcmp($row_rst4['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"> 
      </td>
      <td    bgcolor="#E1E1E1" nowrap="nowrap"><?php echo $row_rst4['verantwortlicher']; ?></td>
      <td    bgcolor="#E1E1E1" nowrap="nowrap">&nbsp;<?php echo $row_rst4['beschreibung']; ?> </td>
      <td    bgcolor="#E1E1E1" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_usid" type="hidden" id="hurl_usid" value="<?php echo $row_rst4['id_ks']; ?>">
      <a href="org2.php?url_id_ks=<?php echo $row_rst4['id_ks']; ?>&url_user=<?php echo $row_rst1['id']; ?>"><img src="picture/b_edit.png" width="16" height="16" border="0"></a> 
      <select name="select" class="csslistbox" id="select">
        <option value="0" <?php if (!(strcmp(0, $row_rst4['id_subks']))) {echo "SELECTED";} ?>>Hauptebene</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rst10['id_ks']?>"<?php if (!(strcmp($row_rst10['id_ks'], $row_rst4['id_subks']))) {echo "SELECTED";} ?>><?php echo $row_rst10['kurzzeichen']?></option>
        <?php
} while ($row_rst10 = mysql_fetch_assoc($rst10));
  $rows = mysql_num_rows($rst10);
  if($rows > 0) {
      mysql_data_seek($rst10, 0);
	  $row_rst10 = mysql_fetch_assoc($rst10);
  }
?>
      </select>
      <input name="umgliedern" type="submit" id="umgliedern" value="&gt;&gt;">
    </div></td>
    </tr>
	<?php } elseif ($layout==4 and $anzeige>=4){ ?> 
		
	 <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst5['id_ks']; ?></td>
      
  <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/3.gif" width="60" height="10">&nbsp; 
    <?php echo $row_rst5['kurzzeichen']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input <?php if (!(strcmp($row_rst5['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst5['verantwortlicher']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst5['beschreibung']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_usid" type="hidden" id="hurl_usid" value="<?php echo $row_rst5['id_ks']; ?>">
      <a href="org2.php?url_id_ks=<?php echo $row_rst5['id_ks']; ?>&url_user=<?php echo $row_rst1['id']; ?>"><img src="picture/b_edit.png" width="16" height="16" border="0"></a> 
      <select name="select" class="csslistbox" id="select">
        <option value="0" <?php if (!(strcmp(0, $row_rst4['id_subks']))) {echo "SELECTED";} ?>>Hauptebene</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rst10['id_ks']?>"<?php if (!(strcmp($row_rst10['id_ks'], $row_rst4['id_subks']))) {echo "SELECTED";} ?>><?php echo $row_rst10['kurzzeichen']?></option>
        <?php
} while ($row_rst10 = mysql_fetch_assoc($rst10));
  $rows = mysql_num_rows($rst10);
  if($rows > 0) {
      mysql_data_seek($rst10, 0);
	  $row_rst10 = mysql_fetch_assoc($rst10);
  }
?>
      </select>
      <input name="umgliedern" type="submit" id="umgliedern" value="&gt;&gt;">
    </div></td>
    </tr>
	
	<?php } elseif ($layout==5 and $anzeige>=5){ ?> 
		
	 <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst6['id_ks']; ?></td>
      
  <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/4.gif" width="80" height="10">&nbsp; 
    <?php echo $row_rst6['kurzzeichen']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <input <?php if (!(strcmp($row_rst6['lokz'],1))) {echo "checked";} ?> name="lokz" type="checkbox" id="lokz" value="1"> 
      </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst6['verantwortlicher']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst6['beschreibung']; ?> </td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
          <input name="hurl_usid" type="hidden" id="hurl_usid" value="<?php echo $row_rst6['id_ks']; ?>">
      <a href="org2.php?url_id_ks=<?php echo $row_rst6['id_ks']; ?>&url_user=<?php echo $row_rst1['id']; ?>"><img src="picture/b_edit.png" width="16" height="16" border="0"></a> 
      <select name="select" class="csslistbox" id="select">
        <option value="0" <?php if (!(strcmp(0, $row_rst5['id_subks']))) {echo "SELECTED";} ?>>Hauptebene</option>
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
      </select>
      <input name="umgliedern" type="submit" id="umgliedern" value="&gt;&gt;">
    </div></td>
    </tr>
	<?php }else{  ?>

		<?php }
		  
mysql_free_result($rst10);
?>