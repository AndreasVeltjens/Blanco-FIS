<?php
/* $Id: calendar.php,v 2.5 2004/09/07 11:45:46 nijel Exp $ */
include('../Connections/polexactdb.php'); 


mysql_select_db($database_polexactdb, $polexactdb);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $polexactdb) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

/* if (isset($HTTP_POST_VARS["suchtext"])){
if ($HTTP_POST_VARS["suchtext"]==""){$HTTP_POST_VARS["suchtext"]="%";}

$HTTP_POST_VARS["suchtext"]=utf8_encode($HTTP_POST_VARS["suchtext"]);
$suchtext=" AND (artikeldaten.bks=0 or artikeldaten.bks='$row_rst1[werk]' )  and (artikeldaten.Nummer like '".$HTTP_POST_VARS["suchtext"]."' OR artikeldaten.Zeichnungsnummer  like '%".$HTTP_POST_VARS["suchtext"]."%' OR artikeldaten.Beschreibung like '%".$HTTP_POST_VARS["suchtext"]."%' OR artikeldaten.Bezeichnung like '%".$HTTP_POST_VARS["suchtext"]."%' )";}
else{$suchtext="AND (artikeldaten.bks=0 or artikeldaten.bks='$row_rst1[werk]' ) ";}
 */

mysql_select_db($database_polexactdb, $polexactdb);
$query_rst2 = "SELECT * FROM ada_basic_main_material_tbl, ada_basic_material_tbl WHERE ada_basic_material_tbl.bm_bmm_id=ada_basic_main_material_tbl.bmm_id AND ada_basic_material_tbl.bm_id='$url_bm_id'";
$rst2 = mysql_query($query_rst2, $polexactdb) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);
require_once('./libraries/grab_globals.lib.php');
require_once('./libraries/common.lib.php');
require_once('./libraries/header_http.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $GLOBALS['available_languages'][$GLOBALS['lang']][2]; ?>" lang="<?php echo $GLOBALS['available_languages'][$GLOBALS['lang']][2]; ?>" dir="<?php echo $GLOBALS['text_dir']; ?>">

<head>
<title>Suche Materialdaten</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $GLOBALS['charset']; ?>" />
<?php
if (!empty($GLOBALS['cfg']['PmaAbsoluteUri'])) {
    echo '<base href="' . $GLOBALS['cfg']['PmaAbsoluteUri'] . '" />' . "\n";
}
?>
<script language="JavaScript" type="text/javascript">
<!--
    /* added 2004-06-10 by Michael Keck
     *       we need this for Backwards-Compatibility and resolving problems
     *       with non DOM browsers, which may have problems with css 2 (like NC 4)
    */
    var isDOM      = (typeof(document.getElementsByTagName) != 'undefined'
                      && typeof(document.createElement) != 'undefined')
                   ? 1 : 0;
    var isIE4      = (typeof(document.all) != 'undefined'
                      && parseInt(navigator.appVersion) >= 4)
                   ? 1 : 0;
    var isNS4      = (typeof(document.layers) != 'undefined')
                   ? 1 : 0;
    var capable    = (isDOM || isIE4 || isNS4)
                   ? 1 : 0;
    // Uggly fix for Opera and Konqueror 2.2 that are half DOM compliant
    if (capable) {
        if (typeof(window.opera) != 'undefined') {
            var browserName = ' ' + navigator.userAgent.toLowerCase();
            if ((browserName.indexOf('konqueror 7') == 0)) {
                capable = 0;
            }
        } else if (typeof(navigator.userAgent) != 'undefined') {
            var browserName = ' ' + navigator.userAgent.toLowerCase();
            if ((browserName.indexOf('konqueror') > 0) && (browserName.indexOf('konqueror/3') == 0)) {
                capable = 0;
            }
        } // end if... else if...
    } // end if
    document.writeln('<link rel="stylesheet" type="text/css" href="<?php echo defined('PMA_PATH_TO_BASEDIR') ? PMA_PATH_TO_BASEDIR : './'; ?>css/phpmyadmin.css.php?lang=<?php echo $GLOBALS['lang']; ?>&amp;js_frame=right&amp;js_isDOM=' + isDOM + '" />');
//-->
</script>
<style type="text/css">
<!--
.liste400 {
	height: 220px;
	width: 400px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	border: thin solid #FFFFFF;
}
-->
</style>
<style type="text/css">
<!--
.menu400 {
	width: 400px;
}
-->
</style>
<noscript>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PMA_PATH_TO_BASEDIR') ? PMA_PATH_TO_BASEDIR : './'; ?>css/phpmyadmin.css.php?lang=<?php echo $GLOBALS['lang']; ?>&amp;js_frame=right" />
</noscript>
<script>
function returnKd() {
    txt = form1.artikeldatenselect.value;
   

    window.opener.dateField.value = txt;
    window.close();
}
</script>
<script type="text/javascript" src="./libraries/tbl_change.js"></script>

<script type="text/javascript">
<!--
var month_names = new Array("<?php echo implode('","', $month); ?>");
var day_names = new Array("<?php echo implode('","', $day_of_week); ?>");
//-->
</script>
</head>
<body onload="initCalendar();">
<form name="form1" id="form1" method="post" action="">
  <table width="758" height="583">
    <tr> 
      <td><div align="center">Werkstoffdaten suchen<br />
          <img src="../picture/wdn.gif" width="150" height="100" /><br />
          Suchbegriff:<br />
          <br />
          <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext'] ?>">
          <br />
          <br />
          <br />
          <input name="suchen" type="submit" id="suchen2" value="suchen" />
        </div></td>
      <td> <span class="liste400">Materialart</span><br />
          <select name="select" class="menu400" id="select2" onclick="form1.submit()" ondblclick="Javascript:returnKd();">
            <option value="0">keine Auswahl</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rst2['artikelid']?>" <?php if ($HTTP_POST_VARS['artikeldatenselect']==$row_rst2['artikelid']){ echo "selected"; }?>><?php echo $row_rst2['Nummer']." - ".$row_rst2['Bezeichnung']." - ".$row_rst2['Einzelpreis']?></option>
            <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
          </select>
          <br />
          <br />
        <span class="liste400">Materialgruppe</span> <br />
          <select name="selectgruppen" class="menu400" id="select5" onclick="form1.submit()" ondblclick="Javascript:returnKd();">
            <option value="0">keine Auswahl</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rst2['artikelid']?>" <?php if ($HTTP_POST_VARS['artikeldatenselect']==$row_rst2['artikelid']){ echo "selected"; }?>><?php echo $row_rst2['Nummer']." - ".$row_rst2['Bezeichnung']." - ".$row_rst2['Einzelpreis']?></option>
            <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
          </select>
        </p>
        <p class="liste400">Handelsname<br />
          <select name="select2" class="menu400" id="select3" onclick="form1.submit()" ondblclick="Javascript:returnKd();">
            <option value="0">keine Auswahl</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rst2['artikelid']?>" <?php if ($HTTP_POST_VARS['artikeldatenselect']==$row_rst2['artikelid']){ echo "selected"; }?>><?php echo $row_rst2['Nummer']." - ".$row_rst2['Bezeichnung']." - ".$row_rst2['Einzelpreis']?></option>
            <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
          </select>
          <br />
          <br />
          Hersteller<br />
          <select name="select3" class="menu400" id="select4" onclick="form1.submit()" ondblclick="Javascript:returnKd();">
            <option value="0">keine Auswahl</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rst2['artikelid']?>" <?php if ($HTTP_POST_VARS['artikeldatenselect']==$row_rst2['artikelid']){ echo "selected"; }?>><?php echo $row_rst2['Nummer']." - ".$row_rst2['Bezeichnung']." - ".$row_rst2['Einzelpreis']?></option>
            <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
          </select>
          <br />
          <br />
          Werkstoff 
          <select name="artikeldatenselect" size="20" class="liste400" id="artikeldatenselect" onclick="form1.submit()" ondblclick="Javascript:returnKd();">
            <option value="0">keine Auswahl</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rst2['artikelid']?>" <?php if ($HTTP_POST_VARS['artikeldatenselect']==$row_rst2['artikelid']){ echo "selected"; }?>><?php echo $row_rst2['Nummer']." - ".$row_rst2['Bezeichnung']." - ".$row_rst2['Einzelpreis']?></option>
            <?php
} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
?>
          </select>
        </p>
        </td>
      <td><p><a href="Javascript:returnKd();"><img src="picture/b_insrow.png" width="16" height="16" border="0" align="absmiddle" /> 
          Daten<br />
          &uuml;bernehmen</a></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p><a href="javascript:window.close()"><img src="picture/error.gif" width="16" height="16" border="0" align="absmiddle" />Fenster 
          <br />
          schlie�en </a></p></td>
    </tr>
    <?php do {  
	if ($row_rst2['artikelid']==$HTTP_POST_VARS['artikeldatenselect']){
	?>
    <tr> 
      <td rowspan="2"><a href="../documents/materialbilder/<?php echo $row_rst2['Nummer']?>.png"><img src="../documents/materialbilder/<?php echo $row_rst2['Nummer']?>.png" width="160" height="160" border="0" /></a><br />
        <br />
        <br />
        <br />
        <br />
      </td>
      <td><p>Kurzzeichen:<strong> <?php echo $row_rst2['bm_short'];?><br />
          Materialbezeichnung: </strong><strong><?php echo utf8_decode($row_rst2['bm_name']); ?></strong></p>
        </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="21"><p><?php echo $row_rst2['bm_id']." ".$row_rst2['Beschreibung'] ?></p>
        </td>
      <td>&nbsp;</td>
    </tr>
    
    <?php 
	}
	} while ($row_rst2 = mysql_fetch_assoc($rst2));
  $rows = mysql_num_rows($rst2);
  if($rows > 0) {
      mysql_data_seek($rst2, 0);
	  $row_rst2 = mysql_fetch_assoc($rst2);
  }
	
	
	?>
	<?php if (!isset($HTTP_POST_VARS['artikeldatenselect'])){?>
	<tr>
      <td>&nbsp;</td>
      <td>Zur Voransicht w&auml;hlen Sie aus der Liste aus.</td>
      <td>&nbsp;</td>
    </tr>
	<?php }?>
  </table>

  
  
</form>

</body>
</html>
