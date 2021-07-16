<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php

/* parameter:
Vorlage richtet sich nach Arbeitsplatz


 */

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ((isset($HTTP_POST_VARS["anmelden"] )) or ($url_user_anmelden>0 && $url_anmelden<>"")) {

		mysql_select_db($database_qsdatenbank, $qsdatenbank);
		$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.id= $url_user_anmelden";
		$rstusernichtangemeldet = mysql_query($query_rstusernichtangemeldet, $qsdatenbank) or die(mysql_error());
		$row_rstusernichtangemeldet = mysql_fetch_assoc($rstusernichtangemeldet);
		$totalRows_rstusernichtangemeldet = mysql_num_rows($rstusernichtangemeldet);


		
		if ($location=="611301"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611301log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611301log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
							   
		}elseif ($location=="611302"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611302log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611302log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
		}elseif ($location=="611303"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611303log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611303log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));					   
	
		}elseif ($location=="611101"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611101log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611101log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
							   
		}elseif ($location=="611102"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611102log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611102log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
		
		}elseif ($location=="611401"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611401log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611401log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
							   
							   
							   
							   
		}elseif ($location=="611402"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611402log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611402log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
		}elseif ($location=="611403"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611403log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611403log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
							   
		}elseif ($location=="611404"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611404log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611404log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
							   
		}elseif ($location=="611405"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611405log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611405log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));
	
		 }elseif ($location=="611406"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611406log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611406log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));					   
		 }elseif ($location=="611407"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611407log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611407log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));					   
		 }elseif ($location=="611408"){
			$url_anzahl=$row_rstusernichtangemeldet['rst611408log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst611408log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));					   
		   
		
		}elseif ($location=="199190"){
			$url_anzahl=$row_rstusernichtangemeldet['rst199190log']+1;
		  	$updateSQL = sprintf("UPDATE user SET angemeldet=%s ,rst199190log=%s WHERE id=%s",
							   GetSQLValueString($url_anmelden, "text"),
							   GetSQLValueString($url_anzahl, "text"),
							   GetSQLValueString($url_user_anmelden, "int"));					   
		   
		}
		
		
		  mysql_select_db($database_qsdatenbank, $qsdatenbank);
		  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
		  $url_user=$url_user_anmelden;
		  $url_user_anmelden="";
		  $url_anmelden="";
		  $oktxt="Mitarbeiter angemeldet und automatisch aktiv auf PC LZ$url_anmelden gesetzt.";
}

if (($aaction=="automatischinaktiv")) {
	if ($location=="611301"){
  		$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611301' ");
	}elseif ($location=="611302"){
		$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611302' ");

	}elseif ($location=="611303"){
		$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611303' ");

	
	}elseif ($location=="611101"){
		$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611101' ");
	}elseif ($location=="611102"){
		$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611102' ");

	}elseif ($location=="611401"){
		$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611401' ");
	}elseif ($location=="611402"){
			$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611402' ");
	}elseif ($location=="611403"){
			$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611403' ");
	}elseif ($location=="611404"){
			$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611404' ");
	}elseif ($location=="611405"){
			$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611405' ");
	}elseif ($location=="611406"){
			$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611406' ");
	}elseif ($location=="611407"){
			$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611407' ");
	}elseif ($location=="611408"){
			$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='611408' ");
    }elseif ($location=="199190"){
			$updateSQL = sprintf("UPDATE user SET angemeldet='' WHERE angemeldet='199190' ");




	}
	
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  $oktxt="angemeldete Mitarbeiter automatisch inaktiv gesetzt.";
}


if ((isset($HTTP_POST_VARS["abmelden"] ))) {
  $updateSQL = sprintf("UPDATE user SET angemeldet=%s WHERE id=%s",
                       GetSQLValueString("", "text"),
                       GetSQLValueString($HTTP_POST_VARS['row_rstangemeldet'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
  $url_user="";
  $url_user_anmelden="";
  $url_anmelden="";
  $url_action="";
}

mysql_select_db($database_qsdatenbank, $qsdatenbank);
if ($location=="611301"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611301=1 ORDER BY `user`.rst611301log desc";
}elseif ($location=="611302"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611302=1 ORDER BY `user`.rst611302log desc";
}elseif ($location=="611303"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611303=1 ORDER BY `user`.rst611303log desc";

}elseif ($location=="611101"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611101=1 ORDER BY `user`.rst611101log desc";
}elseif ($location=="611102"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611102=1 ORDER BY `user`.rst611102log desc";

}elseif ($location=="611401"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611401=1 ORDER BY `user`.rst611401log desc";
}elseif ($location=="611402"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611402=1 ORDER BY `user`.rst611402log desc";
}elseif ($location=="611403"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611403=1 ORDER BY `user`.rst611403log desc";
}elseif ($location=="611404"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611404=1 ORDER BY `user`.rst611404log desc";
}elseif ($location=="611405"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611405=1 ORDER BY `user`.rst611405log desc";
}elseif ($location=="611406"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611406=1 ORDER BY `user`.rst611406log desc";
}elseif ($location=="611407"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611407=1 ORDER BY `user`.rst611407log desc";
}elseif ($location=="611408"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst611408=1 ORDER BY `user`.rst611408log desc";
}elseif ($location=="199190"){
	$query_rstusernichtangemeldet = "SELECT * FROM `user` WHERE `user`.rst199190=1 ORDER BY `user`.rst199190log desc";

}
$rstusernichtangemeldet = mysql_query($query_rstusernichtangemeldet, $qsdatenbank) or die(mysql_error());
$row_rstusernichtangemeldet = mysql_fetch_assoc($rstusernichtangemeldet);
$totalRows_rstusernichtangemeldet = mysql_num_rows($rstusernichtangemeldet);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
if ($location=="611301"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611301=1 and `user`.angemeldet='611301' ORDER BY `user`.rst611301log desc";
}elseif ($location=="611302"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611302=1 and `user`.angemeldet='611302' ORDER BY `user`.rst611302log desc";
}elseif ($location=="611303"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611303=1 and `user`.angemeldet='611303' ORDER BY `user`.rst611303log desc";

}elseif ($location=="611101"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611101=1 and `user`.angemeldet='611101' ORDER BY `user`.rst611101log desc";
}elseif ($location=="611102"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611102=1 and `user`.angemeldet='611102' ORDER BY `user`.rst611102log desc";
	
}elseif ($location=="611401"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611401=1 and `user`.angemeldet='611401' ORDER BY `user`.rst611401log desc";
}elseif ($location=="611402"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611402=1 and `user`.angemeldet='611402' ORDER BY `user`.rst611402log desc";
}elseif ($location=="611403"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611403=1 and `user`.angemeldet='611403' ORDER BY `user`.rst611403log desc";
}elseif ($location=="611404"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611404=1 and `user`.angemeldet='611404' ORDER BY `user`.rst611404log desc";
}elseif ($location=="611405"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611405=1 and `user`.angemeldet='611405' ORDER BY `user`.rst611405log desc";

}elseif ($location=="611406"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611406=1 and `user`.angemeldet='611406' ORDER BY `user`.rst611406log desc";
}elseif ($location=="611407"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611407=1 and `user`.angemeldet='611407' ORDER BY `user`.rst611407log desc";
}elseif ($location=="611408"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst611408=1 and `user`.angemeldet='611408' ORDER BY `user`.rst611408log desc";
}elseif ($location=="199190"){
	$query_rstangemeldet = "SELECT * FROM `user` WHERE `user`.rst199190=1 and `user`.angemeldet='199190' ORDER BY `user`.rst199190log desc";

}
$rstangemeldet = mysql_query($query_rstangemeldet, $qsdatenbank) or die(mysql_error());
$row_rstangemeldet = mysql_fetch_assoc($rstangemeldet);
$totalRows_rstangemeldet = mysql_num_rows($rstangemeldet);

$suchkostenstelle=$location;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_letzterordnungsgrad = "SELECT * FROM kennzahlen, kennzahlen_daten WHERE kennzahlen_daten.id_kz =kennzahlen.id_kz  AND kennzahlen.beschreibung like'% $location%' ORDER BY kennzahlen_daten.wertx desc";
$letzterordnungsgrad = mysql_query($query_letzterordnungsgrad, $qsdatenbank) or die(mysql_error());
$row_letzterordnungsgrad = mysql_fetch_assoc($letzterordnungsgrad);
$totalRows_letzterordnungsgrad = mysql_num_rows($letzterordnungsgrad);





?>
<style type="text/css">
<!--
.abmeldenbutton {
	height: 30px;
	width: 65px;
	background-attachment: scroll;
}
-->
</style>
<?php if ($action==""){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr bgcolor="#CCCCCC">
    <td><img src="picture/iconchance_16x16.gif" width="16" height="16"> Mitarbeiterauswahl<strong></strong></td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
  </tr>
</table>
<table  width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    
    <td bgcolor="#FFFFFF"> <?php if ($totalRows_rstusernichtangemeldet > 0) { // Show if recordset not empty ?>
      <?php do { ?>
      <table width="95" border="1" cellpadding="5" cellspacing="0" align="left">
        <form name="form_anmelden" method="POST" action="<?php echo $editFormAction; ?>">
          <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td width="95" nowrap="nowrap" bordercolor="#FFFFFF"    bgcolor="#EEEEEE"><p align="center"><a href="pp.<?php echo $location ?>.php?url_user_anmelden=<?php echo $row_rstusernichtangemeldet['id']; ?>&url_anmelden=<?php echo $location ?>"><img src="user/<?php echo $row_rstusernichtangemeldet['idcard']; ?>.JPG" alt="<?php echo utf8_decode($row_rstusernichtangemeldet['name']); ?>" width="50" height="60" border="0"></a> 
                
				<?php if ($location=="611301"){?>
				<input name="anmeldenan" type="hidden" value="611301">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611301log']; ?>">
				
				<?php }elseif ($location=="611101"){?>
				<input name="anmeldenan" type="hidden" value="611101">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611101log']; ?>">
				<?php }elseif ($location=="611402"){?>
				<input name="anmeldenan" type="hidden" value="611402">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611402log']; ?>">
				<?php }elseif ($location=="611403"){?>
				<input name="anmeldenan" type="hidden" value="611403">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611403log']; ?>">
			<?php }elseif ($location=="611404"){?>
				<input name="anmeldenan" type="hidden" value="611404">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611404log']; ?>">
			<?php }elseif ($location=="611405"){?>
				<input name="anmeldenan" type="hidden" value="611405">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611405log']; ?>">
			<?php }elseif ($location=="611406"){?>
				<input name="anmeldenan" type="hidden" value="611406">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611406log']; ?>">
			<?php }elseif ($location=="611407"){?>
				<input name="anmeldenan" type="hidden" value="611407">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611407log']; ?>">	
			<?php }elseif ($location=="611408"){?>
				<input name="anmeldenan" type="hidden" value="611407">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst611408log']; ?>">
			<?php }elseif ($location=="199190"){?>
				<input name="anmeldenan" type="hidden" value="199190">
				<input name="url_anzahl_og" type="hidden" value="<?php echo $row_rstusernichtangemeldet['rst199190log']; ?>">
			
			
			
				<?php }?>
                <input name="row_rstnichtangemeldet" type="hidden" id="row_rstnichtangemeldet" value="<?php echo $row_rstusernichtangemeldet['id']; ?>">
              
			  
			  </p></td>
          </tr>
          <input type="hidden" name="MM_update" value="form_anmelden">
        </form>
      </table>
      <?php } while ($row_rstusernichtangemeldet = mysql_fetch_assoc($rstusernichtangemeldet)); ?>
      <?php } // Show if recordset not empty ?> 
      <?php if ($totalRows_rstusernichtangemeldet == 0) { // Show if recordset empty ?>
      <font color="#FF0000"> <strong><font color="#FF0000"><img src="picture/arrowClose.gif" width="8" height="9"> 
      </font>keine Mitarbeiterauswahl m&ouml;glich. Zuordnung zum Arbeitsplatz 
      in der Benutzerverwaltung anlegen. </strong></font> 
      <?php } // Show if recordset empty ?>
</td>
  
  </tr>
</table>
<?php }?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td><img src="picture/c_profile.gif" width="25" height="25" align="absmiddle"> Mitarbeiter angemeldet</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
  </tr>
</table>
<table  width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
 
    <td bgcolor="#FFFFFF" class="abmeldenbutton"> <?php if ($totalRows_rstangemeldet > 0) { // Show if recordset not empty ?>
      <?php do { ?>
      <table width="180" border="0" cellpadding="5" cellspacing="0" align="left">
        <form name="form_angemeldet" method="POST" action="<?php echo $editFormAction; ?>">
          <?php     if ($url_user==$row_rstangemeldet['id']){?>
          <tr bgcolor="#33FF00"> 
            <?php   }else{?>
          <tr bgcolor="#000099"> 
            <?php   }?>
            <td width="90" nowrap="nowrap" bordercolor="#FFFFFF"    ><a href="pp.<?php echo $location ?>.php?url_user=<?php echo $row_rstangemeldet['id']; ?>&url_anmelden=&url_user_anmelden=&lang=de&location=<?php echo $location ?>"><img src="user/<?php echo $row_rstangemeldet['idcard']; ?>.JPG" alt="<?php echo utf8_decode($row_rstangemeldet['name']); ?>" width="60" height="80" border="0" align="absmiddle"></a></td>
            <td width="90" nowrap="nowrap" bordercolor="#FFFFFF"    ><p align="center"> 
                &nbsp; 
                <input name="row_rstangemeldet" type="hidden" id="row_rstangemeldet" value="<?php echo $row_rstangemeldet['id']; ?>">
                <input name="abmelden" type="submit" class="abmeldenbutton" id="anmelden" value="abmelden">
                <br>
                <?php     if ($url_user==$row_rstangemeldet['id']){?>
                <strong><font color="#FFFFFF"> AKTIV</font></strong> </p></td>
            
            <?php }?>
			<td width="5" nowrap="nowrap" bordercolor="#FFFFFF" bgcolor="#FFFFFF"   >&nbsp;</td>
          </tr>
        </form>
      </table>
      <?php } while ($row_rstangemeldet = mysql_fetch_assoc($rstangemeldet)); ?>
      <?php } // Show if recordset not empty ?> <?php if ($totalRows_rstangemeldet == 0) { // Show if recordset empty ?>
      <table width="200" height="67" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td><p><strong><font color="#FF0000"><img src="picture/arrowClose.gif" width="8" height="9"><img src="picture/zugang.gif" width="53" height="36"> 
              <br>
              keine Mitarbeiter an diesem Arbeitsplatz angemeldet.</font></strong></p>
            </td>
        </tr>
      </table>
      <?php } // Show if recordset empty ?>
      <table width="250" height="67" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr> 
          <td><img src="picture/ordnungsgrad.jpg" height="80"></td>
          <td bgcolor="#EBEBEB"><p><font color="#666666" size="1" face="Arial, Helvetica, sans-serif">Ordnungsgrad <?php echo $suchkostenstelle; ?>
              </font></p>
            <p><font color="#666666" size="1" face="Arial, Helvetica, sans-serif">Vorgabe: 
              <?php echo $row_letzterordnungsgrad['wert2y']; ?><br>
              vom <?php echo $row_letzterordnungsgrad['wertx']; ?></font></p></td>
          <td bgcolor="#EBEBEB"><p align="center"><font color="#666666" size="+6" face="Georgia, Times New Roman, Times, serif"><strong> 
             <?php echo $row_letzterordnungsgrad['wert1y']; ?> </strong></font></p>
            </td>
        </tr>
      </table> </td>
   
  </tr>
</table>

  <?php
mysql_free_result($rstusernichtangemeldet);

mysql_free_result($letzterordnungsgrad);

mysql_free_result($rstangemeldet);
?>