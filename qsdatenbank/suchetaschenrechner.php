<?php
/* $Id: calendar.php,v 2.5 2004/09/07 11:45:46 nijel Exp $ */



require_once('./libraries/grab_globals.lib.php');
require_once('./libraries/common.lib.php');
require_once('./libraries/header_http.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $GLOBALS['available_languages'][$GLOBALS['lang']][2]; ?>" lang="<?php echo $GLOBALS['available_languages'][$GLOBALS['lang']][2]; ?>" dir="<?php echo $GLOBALS['text_dir']; ?>">

<head>
<title>Taschenrechner</title>
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
    txt = Rechner.Display.value;
   

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


<script type="text/javascript">
function Check (Eingabe) {
  var nur_das = "0123456789[]()-+*%/.";
  for (var i = 0; i < Eingabe.length; i++)
    if (nur_das.indexOf(Eingabe.charAt(i)) < 0)
      return false;
  return true;
}

function Ergebnis () {
  var x = 0;
  if (Check(window.document.Rechner.Display.value))
    x = eval(window.document.Rechner.Display.value);
  window.document.Rechner.Display.value = x;
}

function Hinzufuegen (Zeichen) {
  window.document.Rechner.Display.value = window.document.Rechner.Display.value + Zeichen;
}

function Sonderfunktion (Funktion) {
  if (Check(window.document.Rechner.Display.value)) {
    if (Funktion == "sqrt") {
      var x = 0;
      x = eval(window.document.Rechner.Display.value);
      window.document.Rechner.Display.value = Math.sqrt(x);
    }
    if (Funktion == "pow") {
      var x = 0;
      x = eval(window.document.Rechner.Display.value);
      window.document.Rechner.Display.value = x * x;
    }
    if (Funktion == "ln") {
      var x = 0;
      x = eval(window.document.Rechner.Display.value);
      window.document.Rechner.Display.value = Math.log(x);
    }
  } else
    window.document.Rechner.Display.value = 0}
</script>
<style type="text/css">
.button {  width:60px; text-align:center;
           font-family:System,sans-serif;
           font-size:100%; }
.display { width:100%; text-align:right;
           font-family:System,sans-serif;
           font-size:100%; }
</style>
</head>
<body bgcolor="#FFFFE0">

<form name="Rechner" action="" onsubmit="Ergebnis();return false;">
  <table width="346" border="0" cellpadding="10" cellspacing="0">
    <tr> 
      <td width="221" bgcolor="#C0C0C0"><div align="center"> 
          <input type="text" name="Display" align="right" class="display" />
        </div></td>
      <td width="119" bgcolor="#C0C0C0"><div align="center"><a href="Javascript:returnKd();"><img src="picture/b_insrow.png" width="16" height="16" border="0" align="absmiddle" /> 
          Daten<br />
          &uuml;bernehmen</a> </div></td>
    </tr>
    <tr> 
      <td  bgcolor="#E0E0E0"><table width="216" height="142" border="0" cellpadding="1" cellspacing="2">
          <tr> 
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('7')" value="  7   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('8')" value="  8   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('9')" value="  9   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('+')" value="  +   " width="60" /></td>
          </tr>
          <tr> 
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('4')" value="  4   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('5')" value="  5   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('6')" value="  6   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('-')" value="  -   " width="60" /></td>
          </tr>
          <tr> 
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('1')" value="  1   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('2')" value="  2   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('3')" value="  3   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('*')" value="  *   " width="60" /></td>
          </tr>
          <tr> 
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('0')" value="  0   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('.')" value="  .   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Ergebnis()" value="  =   " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Hinzufuegen('/')" value="  /   " width="60" /></td>
          </tr>
          <tr> 
            <td colspan="2"><input name="button" type="button" class="button" onclick="Sonderfunktion('sqrt')" value="wurzel " width="60" />
              <input name="button" type="button" class="button" onclick="Sonderfunktion('pow')" value=" ≤ " width="60" /></td>
            <td><input name="button" type="button" class="button" onclick="Sonderfunktion('ln')" value=" ln " width="60" /></td>
            <td><input name="reset" type="reset" class="button" value="  C  "  width="60" /></td>
          </tr>
        </table></td>
      <td  bgcolor="#E0E0E0"><div align="center"><a href="javascript:window.close()"><img src="picture/error.gif" width="16" height="16" border="0" align="absmiddle" />Fenster 
          <br />
          schlieﬂen </a> </div></td>
    </tr>
  </table>  
</form>






</body>
</html>
