<?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
        <strong><font color="#FF0000">Bitte melden Sie sich an. </font></strong><strong><font color="#FF0000"><br>
        <?php echo $errtxt ?></font></strong> 
        <?php } // Show if recordset empty ?> 
<?php if ($oktxt !="") { // Show if recordset not empty ?>
<strong><font color="#00CC33"><?php echo htmlentities($oktxt);?> </font></strong> 
<?php } // Show if recordset not empty ?>
<?php if ($errtxt !="") { // Show if recordset not empty ?>
<strong><font color="#FF0000"><?php echo htmlentities($errtxt); ?> </font></strong> 
<?php } // Show if recordset not empty ?>
