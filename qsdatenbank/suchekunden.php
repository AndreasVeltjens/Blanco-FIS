<?php
/* $Id: calendar.php,v 2.5 2004/09/07 11:45:46 nijel Exp $ */
include('Connections/qsdatenbank.php'); 


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);
$row_rst1[werk]=2;
if (isset($HTTP_POST_VARS["suchtext"])){
if ($HTTP_POST_VARS["suchtext"]==""){$HTTP_POST_VARS["suchtext"]="%";}

$HTTP_POST_VARS["suchtext"]=utf8_encode($HTTP_POST_VARS["suchtext"]);
$suchtext=" AND (kundendaten.bks=0 or kundendaten.bks='$row_rst1[werk]' ) and (kundendaten.deb=1 and (kundendaten.idk like '".$HTTP_POST_VARS["suchtext"]."' OR kundendaten.firma like '%".$HTTP_POST_VARS["suchtext"]."%' OR kundendaten.ort like '%".$HTTP_POST_VARS["suchtext"]."%' OR plz like '".$HTTP_POST_VARS["suchtext"]."' OR kundenummer like '".$HTTP_POST_VARS["suchtext"]."' ))";}
else{$suchtext="AND (kundendaten.bks=0 or kundendaten.bks='$row_rst1[werk]' ) and (kundendaten.deb=1 )";}


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT * FROM kundendaten WHERE kundendaten.bks=$row_rst1[werk] $suchtext ORDER BY kundendaten.firma";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);



require_once('./libraries/grab_globals.lib.php');
require_once('./libraries/common.lib.php');
require_once('./libraries/header_http.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $GLOBALS['available_languages'][$GLOBALS['lang']][2]; ?>" lang="<?php echo $GLOBALS['available_languages'][$GLOBALS['lang']][2]; ?>" dir="<?php echo $GLOBALS['text_dir']; ?>">

<head>
<title>Suche Kundenummer</title>
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
<noscript>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PMA_PATH_TO_BASEDIR') ? PMA_PATH_TO_BASEDIR : './'; ?>css/phpmyadmin.css.php?lang=<?php echo $GLOBALS['lang']; ?>&amp;js_frame=right" />
</noscript>
<script>
function returnKd() {
    txt = form1.selectkd.value;
   

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
<table>
<tr>
      <td><div align="center"><img src="picture/addressbook.gif" width="52" height="36" /><br />
          Suchbegriff:<br />
          <br />
          <input name="suchtext" type="text" id="suchtext" value="<?php echo $HTTP_POST_VARS['suchtext'] ?>">
          <br />
          <br />
          <br />
          <input name="suchen" type="submit" id="suchen2" value="suchen" >
        </div></td>
<td><p class="liste400"> 
          <select name="selectkd" size="100" class="liste400" id="select" ondblclick="Javascript:returnKd();">
            <?php
do {  
?>
            <option value="<?php echo $row_rst6['kundenummer']?>"<?php if (!(strcmp($row_rst6['idk'], $row_rst3['is_kd']))) {echo "SELECTED";} ?>><?php echo substr(utf8_decode($row_rst6['firma']),0,60)." - Kd-Nr.".substr(utf8_decode($row_rst6['kundenummer']),0,10);?></option>
            <?php
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $rows = mysql_num_rows($rst6);
  if($rows > 0) {
      mysql_data_seek($rst6, 0);
	  $row_rst6 = mysql_fetch_assoc($rst6);
  }
?>
          </select>
        </p>
</td>
<td><p><a href="Javascript:returnKd();">Daten &uuml;bernehmen</a></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p><a href="javascript:window.close()">Fenster schlieﬂen </a></p>
      </td>
</tr></table>

  
  
</form>

</body>
</html>
