<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('../Connections/qsdatenbank.php'); ?>
<?php  $la = "la25";
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

if ((isset($HTTP_POST_VARS['hiddenField3'])) && ($HTTP_POST_VARS['hiddenField3'] != "")) {
  $deleteSQL = sprintf("DELETE FROM komponenten_link_artikeldaten WHERE id_link_aa=%s",
                       GetSQLValueString($HTTP_POST_VARS['hiddenField3'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($deleteSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["MM_update"])) && ($HTTP_POST_VARS["MM_update"] == "form8")) {
  $updateSQL = sprintf("UPDATE artikeldaten_link_reparatur SET zusatztxt=%s WHERE linkaaid=%s",
                       GetSQLValueString($HTTP_POST_VARS['zusatztxt'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['id_link_aa'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["txtedit"] ))) {
  $updateSQL = sprintf("UPDATE komponenten_link_artikeldaten SET zusatztxt=%s WHERE id_link_aa=%s",
                       GetSQLValueString($HTTP_POST_VARS['zusatztxt'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['id_link_aa'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

if ($HTTP_POST_VARS['zeigenur']>0 ){$sucherst6=" AND id_asml='$HTTP_POST_VARS[zeigenur]' ";} else {$sucherst6="";}

if ((isset($HTTP_POST_VARS["addkomplink"]))) {
  $insertSQL = sprintf("INSERT INTO komponenten_link_asml (id_asml, artikelid, abversion, bisversion, zusatztxt) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hiddenField22'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['artikels'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['versionab'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['versionbis'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textfield2'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["addrst6"]))) {
  $updateSQL = sprintf("UPDATE komponenten_asm_vorlagen SET repbeschreibung=%s, name=%s WHERE id_asml=%s",
                       GetSQLValueString($HTTP_POST_VARS['textfield'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['name'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hiddenField2'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["addlink"]))) {
  $insertSQL = sprintf("INSERT INTO komponenten_link_artikeldaten (id_asml, artikelid, giltnur, zusatztxt) VALUES (%s, %s,%s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['hiddenField'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['artikel'], "int"),
                    GetSQLValueString($HTTP_POST_VARS['giltnur'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['zusatztext'], "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["new"] ))) {
  $insertSQL = sprintf("INSERT INTO komponenten_asm ( id_asml, komp_id, datum, anzahl, artikelid ) VALUES (%s, %s, %s, %s, %s)",                     
                       GetSQLValueString($HTTP_POST_VARS['id_asml'], "int"),
                       GetSQLValueString($HTTP_POST_VARS['komp_id'], "text"),
					   "now()",
					   GetSQLValueString($HTTP_POST_VARS['anzahl'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['artikelid'], "int"));
					   

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}

if ((isset($HTTP_POST_VARS["delete"] ))) {
  $insertSQL = sprintf("DELETE FROM `komponenten_asm` WHERE `komponenten_asm`.`id_asm`= '$HTTP_POST_VARS[url_id_asm]' ");
					   

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM linkfehlerartikel WHERE linkfehlerartikel.artikelid = '$url_artikelid'";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT `user`.id, `user`.name FROM `user`";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Beschreibung, artikeldaten.Nummer FROM artikeldaten";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst5 = "SELECT * FROM komponenten_vorlage WHERE komponenten_vorlage.lokz  =0 ORDER BY komponenten_vorlage.kompname";
$rst5 = mysql_query($query_rst5, $qsdatenbank) or die(mysql_error());
$row_rst5 = mysql_fetch_assoc($rst5);
$totalRows_rst5 = mysql_num_rows($rst5);


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT * FROM komponenten_asm_vorlagen WHERE komponenten_asm_vorlagen.lokz  =0 $sucherst6 ORDER BY komponenten_asm_vorlagen.gruppe, komponenten_asm_vorlagen.name";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
$updateGoTo = "la2.php?url_user=".$row_rst1['id'];
   header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen. (Cancel)";}




include("function.tpl.php");
include("header.tpl.php");
include("menu.tpl.php");
?>

<style type="text/css">
<!--
.boxselect {
	width: 400px;
}
-->
</style>
<form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="1030" border="0" cellspacing="0" cellpadding="0">
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
      <td width="163">Aktionen W&auml;hlen:</td>
      <td width="465"> <input name="cancel" type="submit" id="cancel" value="Zur&uuml;ck"> 
      </td>
      <td width="102"> <div align="right"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['id']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3">Es werden in einer Tabelle virtuelle Baugruppen angelegt. 
        Die Baugruppe wird durche eine Leistungsbeschreibung erkl&auml;rt. Anwendung 
        findet die Baugruppe in der Retourenabwicklung la44 und la45 bei der R&uuml;ckmeldung 
        verwendeter Bauteile. <br>
        * Die Zuordnung zu einem bestimmten Artikel ist optional. Diese Zuordnung 
        erscheint in der Retourenabwicklung nur wenn die ID des Artikeltyps mit 
        dem Baugruppen &uuml;bereinstimmt. Standardwert ist jedoch keine Artikelzuordnung.</td>
    </tr>
    <tr>
      <td>zeige nur:</td>
      <td><select name="zeigenur" id="zeigenur" >
          <option value=" " <?php if (!(strcmp(" ", $HTTP_POST_VARS['zeigenur']))) {echo "SELECTED";} ?>>keine 
          Auswahl</option>
          <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['zeigenur']))) {echo "SELECTED";} ?>>alle 
          anzeigen</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst6['id_asml']?>"<?php if (!(strcmp($row_rst6['id_asml'], $HTTP_POST_VARS['zeigenur']))) {echo "SELECTED";} ?>><?php echo $row_rst6['gruppe']?> - <?php echo $row_rst6['name']?></option>
          <?php /*           <option value="<?php echo $row_rst6['id_asml']?>"> 
          <?php echo $row_rst6['gruppe']?>-<?php echo $row_rst6['name']?> */?> 
          <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </select>
        <input name="aktualisieren" type="submit" id="aktualisieren" value="aktualisieren"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="21" colspan="2"><em><strong><img src="picture/b_newdb.png" width="16" height="16"> 
        neue Zuordnung einf&uuml;gen</strong></em></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>ausgew&auml;hlter Artikel:*</td>
      <td> <select name="artikelid" id="artikelid">
          <option value="0" <?php if (!(strcmp(0, $url_artikelid))) {echo "SELECTED";} ?>>keine 
          best. Zuordnung</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $url_artikelid))) {echo "SELECTED";} ?>><?php echo $row_rst4['Bezeichnung']?></option>
          <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><img src="picture/folder_open.gif" width="16" height="16"> Leistungsbeschreibung</td>
      <td><select name="id_asml" id="select2" >
          <?php
do {  
?>
          <option value="<?php echo $row_rst6['id_asml']?>"<?php if (!(strcmp($row_rst6['id_asml'], $HTTP_POST_VARS['id_asml']))) {echo "SELECTED";} ?>><?php echo $row_rst6['gruppe']?>-<?php echo $row_rst6['name']?></option>
          <?php /*           <option value="<?php echo $row_rst6['id_asml']?>">
          <?php echo $row_rst6['gruppe']?>-<?php echo $row_rst6['name']?></option> */?> 
          <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><a href="<?php echo $editFormAction; ?>&url_detail=0"><img src="picture/bssctoc2.gif" width="32" height="16" border="0"></a> 
        Komponente:</td>
      <td> <select name="komp_id" id="komp_id">
          <?php
do {  
?>
          <option value="<?php echo $row_rst5['id_komp']?>"<?php if (!(strcmp($row_rst5['id_komp'], $HTTP_POST_VARS['komp_id']))) {echo "SELECTED";} ?>><?php echo $row_rst5['kompnr']?> 
          - <?php echo $row_rst5['kompname']?></option>
          <?php /* 		  <option value="<?php echo $row_rst5['id_komp']?>">
          <?php echo $row_rst5['kompnr']?> - <?php echo $row_rst5['kompname']?></option> */?> 
          <?php
} while ($row_rst5 = mysql_fetch_assoc($rst5));
  $rows = mysql_num_rows($rst5);
  if($rows > 0) {
      mysql_data_seek($rst5, 0);
	  $row_rst5 = mysql_fetch_assoc($rst5);
  }
?>
        </select> </td>
      <td><div align="right"> </div></td>
    </tr>
    <tr> 
      <td>Menge:</td>
      <td><input name="anzahl" type="text" id="anzahl"></td>
      <td> <div align="right"> 
          <input name="new" type="submit" id="new4" value="anlegen">
        </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2">
</form><br>
<?php if ($totalRows_rst6 > 0) { // Show if recordset not empty ?>
<em><strong>Liste der Virtuellen Baugruppen </strong></em> 
<table width="1030" border="0" cellspacing="1" cellpadding="0">
  <tr> 
    <td colspan="2" ><img src="picture/folder_open.gif" width="16" height="16"> 
      Leistungsbeschreibung mit virtueller Baugruppenvorlage</td>
    <td width="221"><div align="right">Link</div></td>
  </tr>
  <?php do { 
  mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst10 = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer, artikelversionen.id, artikelversionen.beschreibung, artikelversionen.artikelid, artikelversionen.absn, artikelversionen.version  FROM artikelversionen, artikeldaten WHERE artikelversionen.lokz=0 AND artikeldaten.artikelid = artikelversionen.artikelid ORDER BY artikeldaten.Bezeichnung";
$rst10 = mysql_query($query_rst10, $qsdatenbank) or die(mysql_error());
$row_rst10 = mysql_fetch_assoc($rst10);
$totalRows_rst10 = mysql_num_rows($rst10);?>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td width="36" nowrap="nowrap"  bgcolor="#EEEEEE" ><a href="<?php echo $editFormAction; ?>&url_detail=<?php echo $row_rst10['id_asml']; ?>"><img src="picture/bssctoc1.gif" width="32" height="16" border="0"></a> 
    <td width="469"><p><strong><?php echo $row_rst6['gruppe']; ?>- <?php echo $row_rst6['name']; ?></strong> 
        <input name="hurl_id_asml" type="hidden" id="hurl_id_asml2" value="<?php echo $row_rst10['id_asml']; ?>">
        <br>
        <?php echo $row_rst6['repbeschreibung']; ?> <br>
      </p>
      <form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
        <input name="hiddenField2" type="hidden" value="<?php echo $row_rst6['id_asml']; ?>">
        Text &auml;ndern: 
        <input name="textfield" type="text" value="<?php echo $row_rst6['repbeschreibung']; ?>" size="60">
        <input name="addrst6" type="submit" id="addrst6" value="+">
        <br>
        <br>
        Text &auml;ndern: 
        <input name="name" type="text" id="name" value="<?php echo $row_rst6['name']; ?>">
        <input type="hidden" name="MM_update" value="form3">
        <input name="zeigenur" type="hidden" id="zeigenur" value="<?php echo $HTTP_POST_VARS['zeigenur'] ?>">
      </form>
      <form name="form5" method="POST" action="<?php echo $editFormAction; ?>">
        <p> Artikel 
          <input name="hiddenField22" type="hidden" value="<?php echo $row_rst6['id_asml']; ?>">
          <select name="artikels" class="boxselect" id="select11">
            <option value="0" <?php if (!(strcmp(0, $url_artikelid))) {echo "SELECTED";} ?>>keine 
            best. Zuordnung</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $HTTP_POST_VARS['artikel']))) {echo "SELECTED";} ?>><?php echo $row_rst4['Nummer']?>-<?php echo $row_rst4['Beschreibung']?></option>
            <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
          </select>
          <br>
          von 
          <select name="versionab" class="boxselect" id="select9">
            <?php
do {  
?>
            <option value="<?php echo $row_rst10['version']?>"<?php if (!(strcmp($row_rst10['version'], $HTTP_POST_VARS['versionab']))) {echo "SELECTED";} ?>><?php echo $row_rst10['Bezeichnung']?>- 
            <?php echo $row_rst10['version']?>- <?php echo $row_rst10['beschreibung']?></option>
            <?php
} while ($row_rst10 = mysql_fetch_assoc($rst10));
  $rows = mysql_num_rows($rst10);
  if($rows > 0) {
      mysql_data_seek($rst10, 0);
	  $row_rst10 = mysql_fetch_assoc($rst10);
  }
?>
          </select>
          <br>
          bis 
          <select name="versionbis" class="boxselect" id="select12">
            <?php
do {  
?>
            <option value="<?php echo $row_rst10['version']?>"<?php if (!(strcmp($row_rst10['version'], $HTTP_POST_VARS['versionbis']))) {echo "SELECTED";} ?>><?php echo $row_rst10['Bezeichnung']?>- 
            <?php echo $row_rst10['version']?>- <?php echo $row_rst10['beschreibung']?></option>
            <?php
} while ($row_rst10 = mysql_fetch_assoc($rst10));
  $rows = mysql_num_rows($rst10);
  if($rows > 0) {
      mysql_data_seek($rst10, 0);
	  $row_rst10 = mysql_fetch_assoc($rst10);
  }
?>
          </select>
          <br>
          Zusatztext: 
          <input name="textfield2" type="text" size="60">
          <input name="addkomplink" type="submit" id="addkomplink" value="+">
        </p>
        <input type="hidden" name="MM_insert" value="form5">
        <input name="zeigenur" type="hidden" id="zeigenur" value="<?php echo $HTTP_POST_VARS['zeigenur'] ?>">
      </form>
      <p>&nbsp; </p>
      <div align="right"> </div></td>
    <td width="221"><div align="right"> 
        <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
          <p>Ersatzteil: 
            <input name="hiddenField" type="hidden" value="<?php echo $row_rst6['id_asml']; ?>">
            <select name="artikel" class="boxselect" id="select">
              <option value="0" <?php if (!(strcmp(0, $url_artikelid))) {echo "SELECTED";} ?>>keine 
              best. Zuordnung</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $HTTP_POST_VARS['artikel']))) {echo "SELECTED";} ?>><?php echo $row_rst4['Nummer']?>-<?php echo $row_rst4['Beschreibung']?></option>
              <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
            </select>
            Bemerkungstext: 
            <input name="zusatztext" type="text" id="zusatztext" size="60">
            <input type="hidden" name="MM_insert" value="form1">
          </p>
          <p> gilt nur f&uuml;r dieses Material 
            <select name="giltnur" class="boxselect" id="select5">
              <option value="0" <?php if (!(strcmp(0, $url_artikelid))) {echo "SELECTED";} ?>>keine 
              best. Zuordnung</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rst4['artikelid']?>"<?php if (!(strcmp($row_rst4['artikelid'], $HTTP_POST_VARS['giltnur']))) {echo "SELECTED";} ?>><?php echo $row_rst4['Bezeichnung']?></option>
              <?php
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?>
            </select>
            <input name="addlink" type="submit" id="addlink2" value="+">
            <br>
            <br>
            <br>
            <input name="zeigenur" type="hidden" id="zeigenur" value="<?php echo $HTTP_POST_VARS['zeigenur'] ?>">
          </p>
        </form>
      </div></td>
  </tr>
  <?php 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst8 = "SELECT `komponenten_vorlage`.* , komponenten_asm.anzahl, komponenten_asm.id_asm, komponenten_asm.artikelid FROM komponenten_asm, komponenten_vorlage WHERE ((`komponenten_asm`.`id_asml`= '$row_rst6[id_asml]') AND (`komponenten_vorlage`.`id_komp`= `komponenten_asm`.`komp_id`)) AND komponenten_asm.lokz=0";
$rst8 = mysql_query($query_rst8, $qsdatenbank) or die(mysql_error());
$row_rst8 = mysql_fetch_assoc($rst8);
$totalRows_rst8 = mysql_num_rows($rst8);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst7 = "SELECT * FROM artikeldaten, komponenten_link_artikeldaten WHERE komponenten_link_artikeldaten.artikelid=artikeldaten.artikelid AND komponenten_link_artikeldaten.id_asml=$row_rst6[id_asml]";
$rst7 = mysql_query($query_rst7, $qsdatenbank) or die(mysql_error());
$row_rst7 = mysql_fetch_assoc($rst7);
$totalRows_rst7 = mysql_num_rows($rst7);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst11 = "SELECT * FROM komponenten_link_asml, artikeldaten WHERE komponenten_link_asml.id_asml='$row_rst6[id_asml]' and komponenten_link_asml.lokz=0 AND artikeldaten.artikelid=komponenten_link_asml.artikelid";
$rst11 = mysql_query($query_rst11, $qsdatenbank) or die(mysql_error());
$row_rst11 = mysql_fetch_assoc($rst11);
$totalRows_rst11 = mysql_num_rows($rst11);


	
	
	?>
  <?php if ($totalRows_rst11>0 ){?>
  <?php do { ?>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td nowrap="nowrap"  bgcolor="#EEEEEE" >&nbsp; 
    <td>Ger&auml;tetyp:<img src="picture/memory.gif" width="16" height="16"> 
      <?php echo $row_rst11['Bezeichnung']; ?> </td>
    <td><?php echo $row_rst11['abversion']; ?> bis <?php echo $row_rst11['bisversion']; ?> - <?php echo $row_rst11['zusatztxt']; ?> <form name="form7" method="POST" action="<?php echo $editFormAction; ?>">
        <input name="zusatztxt" type="text" id="zusatztxt" value="<?php echo $row_rst11['zusatztxt']; ?>" size="10">
        <input name="txtedit" type="submit" id="txtedit" value="+">
        <input name="id_link_aa" type="hidden" value="<?php echo $row_rst11['id_link_aa']; ?>">
        <input type="hidden" name="MM_update" value="form7">
        <input name="zeigenur" type="hidden" id="zeigenur4" value="<?php echo $HTTP_POST_VARS['zeigenur'] ?>">
      </form></td>
  </tr>
  <?php } while ($row_rst11 = mysql_fetch_assoc($rst11)); ?>
  <?php } /* ende von rst11 */?>
  <?php if ($totalRows_rst7>0 ){?>
  <?php do { ?>
  <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
    <td nowrap="nowrap"  bgcolor="#EEEEEE" >&nbsp; 
    <td><img src="picture/1.gif" width="20" height="10">Ersatzteil:<img src="picture/iconCkoutBlue_16x16.gif" width="16" height="16"> 
      <?php echo $row_rst7['Nummer']; ?>- <?php echo $row_rst7['Bezeichnung']; ?>
      <form name="form6" method="post" action="">
        <input name="hiddenField3" type="hidden" value="<?php echo $row_rst7['id_link_aa']; ?>">
        <input type="submit" name="Submit" value="-">
      </form></td>
    <td><?php echo $row_rst7['zusatztxt']; ?>
	<form name="form8" method="POST" action="<?php echo $editFormAction; ?>">
        <input name="zusatztxt" type="text" id="zusatztxt" value="<?php echo $row_rst7['zusatztxt']; ?>" size="10">
        <input name="txtedit" type="submit" id="txtedit" value="+">
        <input name="id_link_aa" type="hidden" value="<?php echo $row_rst7['id_link_aa']; ?>">
        <input type="hidden" name="MM_update" value="form8">
        <input name="zeigenur" type="hidden" id="zeigenur5" value="<?php echo $HTTP_POST_VARS['zeigenur'] ?>">
      </form>
	
	</td>
  </tr>
  <?php } while ($row_rst7 = mysql_fetch_assoc($rst7)); ?>
  <?php } /* ende von rst7 */?>
  
  
  <?php if ($totalRows_rst8>0 && $row_rst10['id_asml']==$url_detail){
	?>
  <?php do { ?>
  <form name="form4" method="post" action="<?php echo $editFormAction; ?>">
    <tr onMouseOver="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseOut="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onMouseDown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td nowrap="nowrap"  bgcolor="#EEEEEE" >&nbsp; 
      <td><a href="<?php echo $editFormAction; ?>&url_detail=0"><img src="picture/bssctoc2.gif" width="32" height="16" border="0"></a><?php echo $row_rst8['anzahl']; ?> St&uuml;ck - <?php echo $row_rst8['kompnr']; ?> - <?php echo $row_rst8['kompname']; ?> --<?php echo $row_rst8['id_asm']; ?></td>
      <td><div align="right"> 
          <?php if (!(strcmp(0, $row_rst8['artikelid']))) {echo "keine best. Zuordnung";} ?>
          <?php
do {  
if (!(strcmp($row_rst4['artikelid'], $row_rst8['artikelid']))) {
 ?>
          <img src="picture/iconShipBlue_16x16.gif" width="16" height="16"> <?php echo $row_rst4['Bezeichnung']; 
}
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
?> 
          <input name="url_id_asm" type="hidden" id="url_id_asm" value="<?php echo $row_rst8['id_asm']; ?>">
          <input name="delete" type="submit" id="delete" value="-">
        </div></td>
    </tr>
  </form>
  <?php } while ($row_rst8 = mysql_fetch_assoc($rst8)); ?>
  <?php } /* ende von rst8 */?>
  <?php } while ($row_rst6 = mysql_fetch_assoc($rst6)); ?>
</table>

<p class="boxselect">&nbsp;</p>
<?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst6 == 0) { // Show if recordset empty ?>
  
<p><font color="#FF0000">Keine Daten vorhanden.</font></p>
  <?php } // Show if recordset empty ?>
<p>
    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $row_rst1['id']; ?>">
  </p>

  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

mysql_free_result($rst3);

mysql_free_result($rst4);

mysql_free_result($rst5);

mysql_free_result($rst7);

mysql_free_result($rst11);

mysql_free_result($rst6);

mysql_free_result($rst10);
?>
<?php include("footer.tpl.php"); ?>
<?php
mysql_free_result($rst4);
?>
