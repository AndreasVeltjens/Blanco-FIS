<?php require_once('Connections/qsdatenbank.php'); ?>
<?php require_once('Connections/nachrichten.php'); ?>
<?php
$currentPage = $HTTP_SERVER_VARS["PHP_SELF"];
 $la = "w1";
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
			if ($HTTP_POST_VARS['select']<>5){
				if ($HTTP_POST_VARS['von']==""){$HTTP_POST_VARS['von']=date("Y-m-d",time());}
				if ($HTTP_POST_VARS['bis']==""){$HTTP_POST_VARS['bis']=date("Y-m-d",time());}
			}else{

			if ($HTTP_POST_VARS['von']==""){$errtxt.="Wählen Sie ein Datum von aus.<br>";}
			if ($HTTP_POST_VARS['bis']==""){$errtxt.="Wählen Sie bis Datum von aus.<br>";}
			}

if ($errtxt==""){
  $insertSQL = sprintf("INSERT INTO workflow (name, bereich, beschreibung, erstellt, von, bis, param, ursache, datum, status) VALUES (%s, %s,%s, %s, %s, %s, %s,%s,%s,%s)",
                       GetSQLValueString($HTTP_POST_VARS['beschreibung'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['select'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),
                       GetSQLValueString($HTTP_POST_VARS['hurl_user'], "text"),
					   GetSQLValueString($HTTP_POST_VARS['von'], "date"),
					   GetSQLValueString($HTTP_POST_VARS['bis'], "date"),
					   GetSQLValueString("http://lznt02".$HTTP_POST_VARS['param'], "text"),
					    GetSQLValueString($HTTP_POST_VARS['ursache']." ", "text"),
                       "now()",
                       GetSQLValueString($HTTP_POST_VARS['status'], "int"));

  mysql_select_db($database_nachrichten, $nachrichten);
  $Result1 = mysql_query($insertSQL, $nachrichten) or die(mysql_error());
  
  $lastinsertid=0;
  $lastinsertid=mysql_insert_id();
			  if ($lastinsertid>0 ){
			$insertSQL = sprintf("INSERT INTO workflowdaten (beschreibung,  datum, `user`, id_work) VALUES (%s, %s, %s, %s)",
								   GetSQLValueString($HTTP_POST_VARS['textarea'], "text"),					    
								   "now()", 
								   GetSQLValueString($HTTP_POST_VARS['hurl_user'], "text"),
								   $lastinsertid);
			
			  mysql_select_db($database_nachrichten, $nachrichten);
			  $Result1 = mysql_query($insertSQL, $nachrichten) or die(mysql_error());
			 }
			 
			 $oktxt="Meldung gespeichert.";
			 }else{
			 $errtxt.="Meldung wurde nicht gespeichert.";}
}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

$maxRows_rst2 = 100;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_nachrichten, $nachrichten);
if ($HTTP_POST_VARS['suchtext']<>""){
$suchtext=utf8_decode($HTTP_POST_VARS['suchtext']);
$query_rst2 = "SELECT * FROM workflow where workflow.name like '%$suchtext%' or workflow.beschreibung like '%$suchtext%' ORDER BY workflow.bereich, workflow.status, id_work desc";
}else{
$query_rst2 = "SELECT * FROM workflow where workflow.datum > subdate(now(),interval 1 Month) or (workflow.status <> 3)  ORDER BY workflow.bereich, workflow.status, id_work desc";
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
}else

if ((isset($HTTP_POST_VARS["edit"])) ) {
$updateGoTo = "w2.php?url_user=".$row_rst1['id']."&url_id_work=".$HTTP_POST_VARS["hurl_id_work"];

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
              <td colspan="3"><strong>w1-Arbeitsablauf- und Meldungs&uuml;bersicht</strong></td>
            </tr>
            <?php if ($totalRows_rst1 == 0 or $errtxt<>"") { // Show if recordset empty ?>
            <tr> 
              <td colspan="3"> <strong><font color="#FF0000"><br>
                <?php echo $errtxt ?></font></strong> </td>
            </tr>
            <?php } // Show if recordset empty ?>
            <?php if ($oktxt<>"") { // Show if recordset empty ?>
            <tr> 
              <td colspan="3"> <strong><font color="#009900"><br>
                <?php echo $oktxt ?></font></strong> </td>
            </tr>
            <?php } // Show if recordset empty ?>
            <tr bgcolor="#FFFFFF"> 
              <td width="92"><div align="center"><a href="start.php?url_user=<?php echo $url_user ?>">Zur&uuml;ck 
                  zur <br>
                  Startseite</a></div></td>
              <td width="571"><img src="picture/W7_Img_Upgrade.jpg" width="66" height="66"> 
              </td>
              <td width="167"> <div align="right">Hilfe<img src="picture/dhs_support_100x100.jpg" alt="Bitte legen Sie hier St&ouml;rungsmeldungen vom FIS-Programm, Maschinen- und Anlagenst&ouml;rungen sowie Versserungsvorschl&auml;ge an." width="100" height="100" border="0" align="absmiddle"> 
                </div></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td>Suche:</td>
              <td><form name="form2" method="post" action="">
                  <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext']; ?>">
                  <input name="suchen" type="submit" id="suchen" value="suchen">
				  <script language="JavaScript" type="text/JavaScript">
function sf(){document.form2.suchtext.focus()}
</script>
                </form></td>
              <td>&nbsp;</td>
            </tr>
          </table>
  
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>">
            <table width="830" border="0" cellspacing="0" cellpadding="0">
            
              <tr bgcolor="#CCCCFF">
                <td colspan="2">&nbsp;</td>
                <td><input name="param" type="hidden" id="param2" value="<?php echo $url_param; ?>"> 
                  <input name="beschreibung" type="hidden" id="beschreibung" value="<?php echo $ursache; ?>" size="50" maxlength="255"></td>
              </tr>  
<?php if (!isset($HTTP_POST_VARS['select'])){?>
              <tr bgcolor="#CCCCFF"> 
                <td colspan="2"><img src="picture/add.gif" width="15" height="14"> 
                  <em>neue Meldung anlegen 1. Schritt</em></td>
                <td>&nbsp;</td>
              </tr>
              <tr bgcolor="#CCCCFF"> 
                <td>Bereich ausw&auml;hlen:</td>
                <td><select name="select">
                    <option value="1" <?php if (!(strcmp(1, $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>>FIS-Datenbank</option>
                    <option value="2" <?php if (!(strcmp(2, $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>>Montage 
                    Kostenstelle 61140</option>
                    <option value="3" <?php if (!(strcmp(3, $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>>Sch&auml;umen+Schwei&szlig;en 
                    Kostenstelle 61130</option>
                    <option value="4" <?php if (!(strcmp(4, $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>>Thermoformen 
                    Kostenstelle 61110</option>
                    <option value="5" <?php if (!(strcmp(5, $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>>Zeiterfassung 
                    - Urlaubsantrag - Freischicht</option>
                    <option value="0" <?php if (!(strcmp(0, $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>>allgemeine 
                    Betriebsorganisation</option>
                    <option value="6" <?php if (!(strcmp(6, $HTTP_POST_VARS['select']))) {echo "SELECTED";} ?>>Probleme 
                    QS- Retourenabwicklung</option>
                  </select> <img src="picture/help2.gif" alt="Bitte immer ausw&auml;hlen" width="16" height="16"> 
                  <input name="weiter" type="submit" id="weiter" value="weiter"></td>
                <td>&nbsp; </td>
              </tr>
              <?php }?>
              <?php 			  if (($HTTP_POST_VARS['select']<>5 ) && (isset($HTTP_POST_VARS['select']))) {?>
              <?php  include("./w1.tmp1.php");?>
              <?php }?>
              <?php 			  if ($HTTP_POST_VARS['select']==5){?>
              <?php  include("w1.tmp5.php");?>
              <?php }?>
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
              <td width="50"><em><strong>Status</strong></em></td>
              <td width="120"><em><strong>Ursache</strong></em></td>
              <td width="120"><em><strong>Beschreibung</strong></em></td>
              <td width="90"><em><strong>Seit</strong></em></td>
              <td width="108"><strong>User</strong></td>
              <td width="125">Termin ab</td>
              <td width="125">Termin<br>
                bis</td>
              <td width="125">&nbsp;</td>
            </tr>
            <?php do { ?>
            <?php   if ($row_rst2['bereich']!=$bereich){?>
            <tr bgcolor="#CCCCCC" > 
              <td colspan="11" nowrap="nowrap"><em><br>
                <img src="picture/s_tbl.png" width="16" height="16"><strong><?php echo $row_rst2['bereich']; ?>- 
                <?php if ($row_rst2['bereich']==1){ echo "FIS-Datenbank";}?>
                <?php if ($row_rst2['bereich']==2){ echo "Montage Kostenstelle 61140";}?>
                <?php if ($row_rst2['bereich']==3){ echo "Sch&auml;umen+Schwei&szlig;en - Kostenstelle 61130";}?>
                <?php if ($row_rst2['bereich']==4){ echo "Thermoformen - Kostenstelle 61110";}?>
                <?php if ($row_rst2['bereich']==5){ echo "Zeiterfassung";}?>
                <?php if ($row_rst2['bereich']==6){ echo "QS Retourenabwicklung";}?>
                <?php if ($row_rst2['bereich']==0){ echo "allgemeine Betriebsorganisation - Kostenstelle 61730";}?>
                </strong> </em></td>
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
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo substr($row_rst2['name'],0,15); ?></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo substr($row_rst2['beschreibung'],0,30); ?>...<a class="blau" onMouseOver="InfoBoxAnzeigen(event,'<img src=picture/rechnungen.gif width=70 height=50 align=absmiddle> <br><?php echo ($row_rst2['name'])."<br>"; echo str_replace("\r","-",str_replace("\n","<br>",str_replace("\"","-",$row_rst2['beschreibung']))); ?>',20,-30);" onMouseOut="InfoBoxAusblenden();" href="javascript:void(0);">mehr</a></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><?php echo $row_rst2['datum']; ?></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/iconchance_16x16.gif" width="16" height="16"> 
                    <?php echo $row_rst2['erstellt']; ?></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/b_calendar.png" width="16" height="16"><?php echo $row_rst2['von']; ?></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"><img src="picture/b_calendar.png" width="16" height="16"><?php echo $row_rst2['von']; ?></td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap"> 
                  <div align="right"> 
                    <input name="hurl_user" type="hidden" id="hurl_user" value="<?php echo $url_user; ?>">
                    <input name="hurl_id_work" type="hidden" id="hurl_id_work" value="<?php echo $row_rst2['id_work']; ?>">
                    <input name="edit" type="submit" id="edit" value="b">
                  </div></td>
              </tr>
              <?php 
	
	mysql_select_db($database_nachrichten, $nachrichten);
$query_rst3 = "SELECT workflow.id_work, workflow.name, workflow.beschreibung, workflow.erstellt, workflow.datum, workflow.status, workflowdaten.id_work_d, workflowdaten.beschreibung, workflowdaten.datum, workflowdaten.`user`, workflowdaten.id_work FROM workflow, workflowdaten WHERE workflow.id_work=workflowdaten.id_work AND workflow.id_work = '$detail' ORDER BY workflowdaten.datum desc";
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
                <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
                <td    bgcolor="#EEEEEE" nowrap="nowrap">&nbsp;</td>
              </tr>
              <?php } /* ende detailansicht */?>
            </form>
            <?php
    $typ=$row_rst2['typ'];
	 $bereich=$row_rst2['bereich'];
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
  Transport ins Produktivsystem noch offen <br>
  <img src="picture/st1.gif" width="15" height="15"> Meldung abgeschlossen<br>
  <?php
mysql_free_result($rst1);

mysql_free_result($rst2);

?>
</p>
<?php include("footer.tpl.php"); ?>
