<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "w4";
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

if ((isset($HTTP_POST_VARS["add"] ))) {
if ($HTTP_POST_VARS['ursache']==""){$HTTP_POST_VARS['ursache']="keine Angabe";}
  $insertSQL = sprintf("INSERT INTO sworkflow (name, bereich,ursache, beschreibung, erstellt, datum, status) VALUES (%s,%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['select'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['ursache'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user2'], "text"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['status'], "int"));

  mysql_select_db($database_nachrichten, $nachrichten);
  $Result1 = mysql_query($insertSQL, $nachrichten) or die(mysql_error());
  
  $lastinsertid=0;
  $lastinsertid=mysql_insert_id();
  if ($lastinsertid>0 ){
$insertSQL = sprintf("INSERT INTO sworkflowdaten (beschreibung,  datum, `user`, id_work) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),					    
                       "now()", 
                       GetSQLValueString($HTTP_POST_VARS['hurl_user2'], "text"),
                       $lastinsertid);

  mysql_select_db($database_nachrichten, $nachrichten);
  $Result1 = mysql_query($insertSQL, $nachrichten) or die(mysql_error());
 }
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

$maxRows_rst2 = 50;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_nachrichten, $nachrichten);
if ($HTTP_POST_VARS['suchtext']<>""){
$suchtext=utf8_decode($HTTP_POST_VARS['suchtext']);
$query_rst2 = "SELECT * FROM sworkflow where sworkflow.name like '%$suchtext%' or sworkflow.beschreibung like '%$suchtext%' ORDER BY sworkflow.datum desc";
}else{
$query_rst2 = "SELECT * FROM sworkflow where sworkflow.datum > subdate(now(),interval 1 Month) or (sworkflow.status <> 3)  ORDER BY sworkflow.datum desc";
}
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $nachrichten) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

if (isset($HTTP_GET_VARS['totalRows_rst2'])) {
  $totalRows_rst2 = $HTTP_GET_VARS['totalRows_rst2'];
} else {
  $all_rst2 = mysql_query($query_rst2);
  $totalRows_rst2 = mysql_num_rows($all_rst2);
}
$totalPages_rst2 = ceil($totalRows_rst2/$maxRows_rst2)-1;

$queryString_rst2 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst2") == false && 
        stristr($param, "totalRows_rst2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst2 = "&" . implode("&", $newParams);
  }
}
$queryString_rst2 = sprintf("&totalRows_rst2=%d%s", $totalRows_rst2, $queryString_rst2);

if ((isset($HTTP_POST_VARS["cancel"])) ) {
		if ($url_kundenummer>0){
			$updateGoTo = "kundenstart.php?url_user=".$row_rst1['id'];
		}else{
			$updateGoTo = "start.php?url_user=".$row_rst1['id'];
		}
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}else{$errtxt = "Aktion wählen. Bitte erneut versuchen.";}

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "w42.php?url_user=".$row_rst1['id']."&url_id_work=".$HTTP_POST_VARS["hurl_id_work"];

  header(sprintf("Location: %s", $updateGoTo));
}



include("function.tpl.php");
include("header.tpl.php");?>
     
<script type="text/javascript" language="JavaScript">
<!--
var offsetx=20
var offsety=0

function InfoBoxAusblenden() {
      document.getElementById('infobox').style.visibility = "hidden";
}

function InfoBoxAnzeigen(e,txt,offsetX,offsetY)
{
        if (offsetX) {offsetx=offsetX;} else {offsetx=20;}
        if (offsetY) {offsety=offsetY;} else {offsety=0;}
        var PositionX = 0;
        var PositionY = 0;
        if (!e) var e = window.event;
        if (e.pageX || e.pageY)
        {
                PositionX = e.pageX;
                PositionY = e.pageY;
        }
        else if (e.clientX || e.clientY)
        {
                PositionX = e.clientX + document.body.scrollLeft;
                PositionY = e.clientY + document.body.scrollTop;
        }
        document.getElementById("text").innerHTML=txt;
        document.getElementById('infobox').style.left = (PositionX+offsetx);
        document.getElementById('infobox').style.top = (PositionY+offsety);
        document.getElementById('infobox').style.visibility = "visible";
}
// -->

</script>



<style type="text/css">
<!--

a.blau:link{
font-family:Arial,Helvetica,sans-serif;
font-size:12px;
font-weight:normal;
text-decoration:none;
color: #0000FF;
}

a.blau:visited {color:#0000FF;}
a.blau:hover{color:#FF007F;}


.infoBox {
background-color:#F9FCFF;
border:2px solid #0090E0;
font-family:Arial,Helvetica,sans-serif;
font-size:12px;
color:#5F5F5F;
padding:15px;
}

-->
</style>


<?php include("menu.tpl.php");?>

<!-- Anfang DIV-Layer -->
<div id="infobox" style="position:absolute;left:180; top:25;z-index:1; visibility:hidden;">
<!-- Formatierung: Tabellenrand Hintergrundfarbe Schrift -->
<p class="infoBox"><span id="text"></span></P>
</div>
<!-- Ende DIV-Layer -->


          <table width="830" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td colspan="11"><strong>W4 Schicht&uuml;bergabe- Betriebsmeldungen</strong></td>
            </tr>
            <tr> 
              <td colspan="11"><?php if ($totalRows_rst1 == 0) { // Show if recordset empty ?>
                <strong><font color="#FF0000">Bitte melden Sie sich an.<br>
                <?php echo $errtxt ?></font></strong> 
                <?php } // Show if recordset empty ?><?php echo $oktxt; ?></td>
            </tr>
            <tr bgcolor="#FFFFFF"> 
              <td colspan="2"><div align="center"><a href="start.php?url_user=<?php echo $url_user ?>">Zur&uuml;ck 
                  zur <br>
                  Startseite</a></div></td>
              <td width="165"> 
                  <img src="picture/W7_Img_Upgrade.jpg" width="66" height="66"> 
                </td>
              <td width="222"><div align="right"><a href="w1.php?url_user=<?php echo $url_user ?>&ursache=Verbesserung">Verbesserung 
                  <br>
                  anlegen</a></div></td>
              <td width="220"><p><img src="picture/light.gif" width="40" height="50"> 
                </p></td>
              <td width="221"><p align="right"><a href="vl10.php?url_user=<?php echo $url_user ?>">Versandfertige<br>
                  Ware </a></p></td>
              <td width="221"><img src="picture/weltweit_handeln.png" width="61" height="45"></td>
              <td width="221"><p align="right"><a href="co14.php?url_user=<?php echo $url_user ?>">Fertigungsmeldungen<br>
                  anzeigen</a></p></td>
              <td width="85"><img src="picture/anschrift.gif" width="70" height="50"></td>
              <td width="154"><p align="right"><a href="fhm1.php?url_user=<?php echo $url_user ?>">Wartungen<br>
                  r&uuml;ckmelden</a></p></td>
              <td width="110"> <div align="right"></div></td>
            </tr>
            <tr bgcolor="#FFFFFF"> 
              <td colspan="2">&nbsp;</td>
              <td>Suche</td>
              <td colspan="4"><form name="form2" method="post" action="<?php echo $editFormAction; ?>">
                  <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
                  <input name="suchen" type="submit" id="suchen" value="suchen">
				  <script language="JavaScript" type="text/JavaScript">
function sf(){document.form2.suchtext.focus()}
</script>
                </form></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
  
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
            <table width="830" border="0" cellspacing="0" cellpadding="0">
              <tr bgcolor="#CCCCFF"> 
                <td colspan="2"><img src="picture/add.gif" width="15" height="14"> 
                  <em>neue Schichtmeldung anlegen </em></td>
                <td>&nbsp;</td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td width="137"><p>Schicht&uuml;bergabe- Meldungen</p>
                  <p>Wartungsarbeiten<br>
                    Maschinen oder Anlagenst&ouml;rungen<font size="1">.</font><br>
                    <br>
                  </p>
                  </td>
				  <?php $url_param = "Besonderes /Probleme in der Fertigung:
-
-
Sauberkeit und Ordnung, T&uuml;ren geschlossen- i.O.
F&uuml;llstand der Warenh&auml;user- i.O.
vorhandensein der Warenbegleitscheine und Inhalt- i.O.
Anwesenheit der Mitarbeiter / Krankenstand und Fehlzeiten- i.O.
Infrastruktur (Maschinenpark, Beleuchtung, Heizung…)- i.O.
Sichtkontrolle der Müllcontainer und Ladehof- i.O.
";
?>
				  
                <td width="432"> <textarea name="textarea" cols="80" rows="12"><?php echo $url_param; ?></textarea> 
                </td>
                <td width="159"> <div align="right"> <font size="1"><img src="picture/rechnungen.gif" width="70" height="50" align="absmiddle"></font>
<input name="add" type="submit" id="add" value="Meldung speichern">
                  </div></td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td>Schichtname</td>
                <td> 
                  <select name="beschreibung" id="beschreibung">
                    <option>bitte w&auml;hlen</option>
                    <option value="Fr&uuml;hschicht">Fr&uuml;hschicht</option>
                    <option value="Sp&auml;tschicht">Sp&auml;tschicht</option>
                    <option value="Nachtschicht">Nachtschicht</option>
                  </select> <img src="picture/help2.gif" alt="Schicht ausw&auml;hlen" width="16" height="16"></td>
                <td><input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['name']; ?>"> 
                  <input name="status" type="hidden" id="status" value="0"></td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td>&nbsp;</td>
                <td> <input name="ursache" type="hidden" id="beschreibung" value="<?php echo $ursache; ?>" size="50" maxlength="255">
                </td>
                <td><input name="hurl_user2" type="hidden" id="hurl_user2" value="<?php echo $row_rst1['name']; ?>"> 
                  <input name="status" type="hidden" id="status" value="0"></td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td>Bereich</td>
                <td><select name="select">
                    <option value="6">Bereichskostenstelle 61160</option>
                    <option value="1">FIS-Datenbank</option>
                    <option value="2">Montage Kostenstelle 61140</option>
                    <option value="3">Sch&auml;umen+Schwei&szlig;en Kostenstelle 
                    61130</option>
                    <option value="4">Thermoformen Kostenstelle 61110</option>
                    <option value="5">Zeiterfassung - Urlaubsantrag - Freischicht</option>
                    <option value="0">allgemeine Betriebsorganisation</option>
                  </select> <img src="picture/help2.gif" alt="Bitte immer ausw&auml;hlen" width="16" height="16"></td>
                <td>&nbsp;</td>
              </tr>
            </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>
<p>&nbsp;</p>
<?php if ($totalRows_rst2 > 0) { // Show if recordset not empty ?>
Liste der Meldungen <a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, 0, $queryString_rst2); ?>">First</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, max(0, $pageNum_rst2 - 1), $queryString_rst2); ?>">Previous</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, min($totalPages_rst2, $pageNum_rst2 + 1), $queryString_rst2); ?>">Next</a> 
<a href="<?php printf("%s?pageNum_rst2=%d%s", $currentPage, $totalPages_rst2, $queryString_rst2); ?>">Last</a><br>
  
<table width="830" border="0" cellpadding="0" cellspacing="0">
  <tr class="value"> 
    <td width="358"><em><strong>Status</strong></em></td>
              <td width="74"><em><strong>Schicht</strong></em></td>
    <td width="667"><em><strong>Beschreibung</strong></em></td>
              <td width="86"><em><strong>Bereich</strong></em></td>
              <td width="157"><strong>Schichtverantwortlicher</strong></td>
    <td width="119">&nbsp;</td>
  </tr>
  <?php do { ?>
  <?php   if ($row_rst2['datum']!=$bereich){?>
  <tr bgcolor="#CCCCCC" > 
    <td colspan="9" nowrap="nowrap"><em><br>
      <img src="picture/s_tbl.png" width="16" height="16"><?php echo $row_rst2['datum']; ?>- 
	  
	  <?php if ($row_rst2['bereich']==1){ echo "FIS-Datenbank";}?> 
	 <?php if ($row_rst2['bereich']==2){ echo "Montage Kostenstelle 61140";}?> 
	  <?php if ($row_rst2['bereich']==3){ echo "Sch&auml;umen+Schwei&szlig;en - Kostenstelle 61130";}?> 
	  <?php if ($row_rst2['bereich']==4){ echo "Thermoformen - Kostenstelle 61110";}?> 
         <?php if ($row_rst2['bereich']==0){ echo "allgemeine Betriebsorganisation - Kostenstelle 61730";}?>     
	  
	  
	  </em></td>
  </tr>
  <?php 	}?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 


	  <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <?php if  ($row_rst2['status']==0 ){?>
        <a href="<?php echo $editFormAction; ?>&detail=<?php echo $row_rst2['id_work'] ?>"><img src="picture/st3.gif" width="15" height="15" border="0"></a> 
        <?php } else  { 
        
		if ($row_rst2['status']==3 ){?>
        <a href="<?php echo $editFormAction; ?>&detail=<?php echo $row_rst2['id_work'] ?>"><img src="picture/st1.gif" width="15" height="15" border="0"> 
        </a> 
        <?php } else { ?>
        <a href="<?php echo $editFormAction; ?>&detail=<?php echo $row_rst2['id_work'] ?>"><img src="picture/st2.gif" width="15" height="15" border="0"> 
        </a> 
        <?php } 
		 if (($row_rst2['status']>=1)&& ($row_rst2['status']<3)){; ?>
        <img src="picture/b_tipp.png" width="16" height="16"> 
        <?php }?>
        <?php if (($row_rst2['status']>=2)&& ($row_rst2['status']<3)){; ?>
        <img src="picture/b_tipp.png" width="16" height="16"> 
        <?php }?>
        <?php }?>
        <?php echo $row_rst2['id_work']; ?></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['name']; ?></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo substr($row_rst2['beschreibung'],0,20); ?>...<a class="blau" onMouseOver="InfoBoxAnzeigen(event,'<img src=picture/rechnungen.gif width=70 height=50 align=absmiddle> <br><?php echo ($row_rst2['name'])."<br>"; echo str_replace("\r","-",str_replace("\n","<br>",str_replace("\"","-",$row_rst2['beschreibung']))); ?>',20,-30);" onMouseOut="InfoBoxAusblenden();" href="javascript:void(0);">mehr</a></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['bereich']; ?></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"><div align="right"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
          <?php echo $row_rst2['erstellt']; ?></div></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
        <div align="right"> 
          <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
          <input name="hurl_id_work" type="hidden" id="hurl_id_work" value="<?php echo $row_rst2['id_work']; ?>">
          <input name="edit" type="submit" id="edit" value="bearbeiten">
        </div></td>
    </tr>
    <?php 
	
	mysql_select_db($database_nachrichten, $nachrichten);
$query_rst3 = "SELECT sworkflow.id_work, sworkflow.name, sworkflow.beschreibung, sworkflow.erstellt, sworkflow.datum, sworkflow.status, sworkflowdaten.id_work_d, sworkflowdaten.beschreibung, sworkflowdaten.datum, sworkflowdaten.`user`, sworkflowdaten.id_work FROM sworkflow, sworkflowdaten WHERE sworkflow.id_work=sworkflowdaten.id_work AND sworkflow.id_work = '$detail' ORDER BY sworkflowdaten.datum desc";
$rst3 = mysql_query($query_rst3, $nachrichten) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

$queryString_rst2 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst2") == false && 
        stristr($param, "totalRows_rst2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst2 = "&" . implode("&", $newParams);
  }
}
$queryString_rst2 = sprintf("&totalRows_rst2=%d%s", $totalRows_rst2, $queryString_rst2);

	if (($totalRows_rst3 > 0)&&( $detail==$row_rst2['id_work'])) {
	?>
    <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
      <td colspan="5" nowrap="nowrap"    bgcolor="#EEEEEE"><img src="picture/bssctoc2.gif" width="32" height="16"> 
        <table width="651" border="0" cellpadding="0" cellspacing="2">
          <tr class="value"> 
            <td width="91"><em><strong>Datum</strong></em></td>
            <td width="420"><em><strong>Beschreibung/ Aktivit&auml;t</strong></em></td>
            <td width="87"><em><strong>Benutzer</strong></em></td>
            <td width="17">&nbsp;</td>
            <td width="24">&nbsp;</td>
          </tr>
          <?php do { ?>
          <?php   if ($row_rst3['typ']!=$typ){?>
          <tr bgcolor="#CCCCCC" > 
            <td colspan="8" nowrap="nowrap"></td>
          </tr>
          <?php 	}?>
          <tr onmouseover="setPointer(this, 1, 'over', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmouseout="setPointer(this, 1, 'out', '#EEEEEE', '#CCCCFF', '#FFCC99');" onmousedown="setPointer(this, 1, 'click', '#EEEEEE', '#CCCCFF', '#FFCC99');"> 
            <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;<?php echo $row_rst3['datum']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo wordwrap($row_rst3['beschreibung'],60,"<br>",255); ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
              <?php echo $row_rst3['user']; ?></td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
            <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
              <div align="right"> </div></td>
          </tr>
          <?php
    $typ=$row_rst3['typ'];
	
   } while ($row_rst3 = mysql_fetch_assoc($rst3)); ?>
        </table></td>
      <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
    </tr>
    <?php } /* ende detailansicht */?>
  </form>
  <?php
    $typ=$row_rst2['typ'];
	 $bereich=$row_rst2['datum'];
   } while ($row_rst2 = mysql_fetch_assoc($rst2)); ?>
</table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rst2 == 0) { // Show if recordset empty ?>
  <p>Keine Daten vorhanden.</p>
  <?php } // Show if recordset empty ?>
<p>&nbsp; </p>
  
        
<p>&nbsp;</p>
<p> <img src="picture/st3.gif" width="15" height="15"> Meldung aktiv<br>
  <img src="picture/st2.gif" width="15" height="15"> Meldung in Bearbeitung<br>
  <img src="picture/b_tipp.png" width="16" height="16"> in Arbeitsbegin<br>
            <img src="picture/b_tipp.png" width="16" height="16"><img src="picture/b_tipp.png" width="16" height="16"> 
            Umsetzungstermin ins Produktivsystem noch offen <br>
  <img src="picture/st1.gif" width="15" height="15"> Meldung abgeschlossen<br>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

?>
</p>
<?php include("footer.tpl.php"); ?>
