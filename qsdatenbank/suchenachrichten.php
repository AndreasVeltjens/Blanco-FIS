<?php
include('Connections/qsdatenbank.php'); 
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = str_replace(",",".", $theValue);
	  $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = utf8_encode(($theValue != "") ? "'" . $theValue . "'" : "NULL");
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if ($ok<>1){
$GoTo = "pdfeti632email.php?url_user_id=".$url_user."&url_id_vg=".$url_id_vg;
 header(sprintf("Location: %s", $GoTo));
} else {

 $natxt .= "Nachrichtaufbereitung gestartet.";
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT * FROM user WHERE user.id = '$url_user'";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM vorgange WHERE vorgange.id_vg='$url_id_vg' ";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);


/* Beleg per email als Anhang senden */
if ((isset($HTTP_POST_VARS["senden"])&& ($HTTP_POST_VARS["an"]!="") && ($HTTP_POST_VARS['url_id_vg']<>"") )) {

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM vorgange WHERE vorgange.id_vg='$url_id_vg' ";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst4 = "SELECT * FROM vorgangsart ORDER BY vorgangsart.anzeigetxt";
$rst4 = mysql_query($query_rst4, $qsdatenbank) or die(mysql_error());
$row_rst4 = mysql_fetch_assoc($rst4);
$totalRows_rst4 = mysql_num_rows($rst4);


do {   if (!(strcmp($row_rst4['id_vga'], $row_rst3['vorgangsart']))) {$dokumentart=$row_rst4['anzeigetxt'];}
        
} while ($row_rst4 = mysql_fetch_assoc($rst4));
  $rows = mysql_num_rows($rst4);
  if($rows > 0) {
      mysql_data_seek($rst4, 0);
	  $row_rst4 = mysql_fetch_assoc($rst4);
  }
		$url_user_id=$row_rst1['id'];
		
		  
		$fileatttxt=$dokumentart."-".$row_rst3['lfd_nr'];
		$fileatt=$dokumentart."-".$row_rst3['lfd_nr']."-".$row_rst3['id_vg'];
		
		
		require_once('class.phpmailer.php');
		
		$mail             = new PHPMailer(); // defaults to using php "mail()"
		 $body			  = utf8_decode(utf8_decode($row_rst3['anrede'])).",<br><br>";
		$body			  .= "anbei die Unterlagen für Ihre Verwendung.<br><br>";
		$body			  .= "Mit freundlichen Grüßen<br><br>";
		$body			  .= utf8_decode($row_rst1['name']).".<br><br>";
		$body			  .= utf8_decode($row_rst1['firma'])."<br>";
		$body			  .= utf8_decode($row_rst1['anschrift'])."<br>";
		$body			  .= utf8_decode($row_rst1['plz'])." ".utf8_decode($row_rst1['ort'])."<br>";
		$body			  .= "E-Mail: ".utf8_decode($row_rst1['email'])."<br>";
		$body			  .= "<br>";
		$body			  .= "Sitz der Gesellschaften und Erf&uuml;llungsort für Lieferung und Zahlung: ".utf8_decode($row_rst1['ort']).". <br>
Diese E-Mail enthält vertrauliche und/oder rechtlich geschützte Informationen.
Wenn Sie nicht der beabsichtigte Empf&auml;nger sind, informieren Sie bitte den Absender
und l&ouml;schen Sie diese E-Mail. Das unbefugte Kopieren dieser E-Mail oder
die unbefugte Weitergabe der enthaltenen Informationen ist nicht gestattet.<br><br>
 
The information contained in this message is confidential and/or protected by law.
If you are not the intended recipient, please contact the sender and delete this message.
Any unauthorised copying of this message or unauthorised distribution
of the informations in this message are prohibited.<br>";
		
		/*	
		$bodyvorlage         = file_get_contents('contents.html');
		$body             .= eregi_replace("[\]",'',$bodyvorlage);
		*/ 
		$mail->AddReplyTo("info@vekutech.de","FIS-Systeminfo");
		$mail->SetFrom($row_rst1['email'], $row_rst1['name']);
		
		$address = $HTTP_POST_VARS["an"];
		$mail->AddAddress($address, $row_rst3["ansprechpartner"]);
		
		$mail->Subject    = $row_rst1['firma']."- Für Ihre Unterlagen: ".$fileatttxt;
		
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);
		$mail->AddAttachment("picture/logo".$row_rst1['werk'].".jpg","logo.jpg");      // attachment
		$mail->AddAttachment("../nachrichten/$fileatt.pdf");      // attachment
		
		
		if(!$mail->Send()) {
		  $natxt.= "Nachrichtenfehler. Server &uuml;berlasstet. " . $mail->ErrorInfo;
		  $status=1;
		} else {
		  $natxt .= "Nachricht gesendet!". " Die E-Mail wurde an ".$HTTP_POST_VARS["an"]." und ".$HTTP_POST_VARS["cc"]." versendet.";
		  $status=3;
		}
		$mail             = new PHPMailer(); // defaults to using php "mail()"
		$address = $HTTP_POST_VARS["cc"];
		$body= "folgende Information wurden an ".$HTTP_POST_VARS["cc"]." (".$row_rst3["ansprechpartner"].") gesendet.";
		$mail->AddAddress($address, $row_rst1['name']);
		$mail->AddReplyTo("info@vekutech.de","FIS-Systeminfo");
		$mail->SetFrom($row_rst1['email'], $row_rst1['name']);
		
		$address = $HTTP_POST_VARS["cc"];
		$mail->AddAddress($address, $row_rst1["name"]);
		
		$mail->Subject    = "Sendebericht an ".$row_rst3['firma']." - ".$fileatttxt;
		
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);
		$mail->AddAttachment("picture/logo".$row_rst1['werk'].".jpg","logo.jpg");      // attachment
		$mail->AddAttachment("../nachrichten/$fileatt.pdf");      // attachment
		$mail->Send();
		/* Dokument in Vaorgangsnachrichten anlegen */
		 $updateSQL = sprintf("INSERT INTO vorgangsnachrichten (status, id_vg, datum ,datumzeit, nachricht , empfaenger , link_document ) VALUES (%s,%s,%s,%s, %s,%s,%s)",
                      	GetSQLValueString($status,"int"),
					   GetSQLValueString($HTTP_POST_VARS['url_id_vg'], "int"),
                       "now()",
					    "now()",
					   GetSQLValueString($oktxt, "text"),

					   GetSQLValueString($HTTP_POST_VARS['an'], "text"),
                       GetSQLValueString("../nachrichten/$fileatt.pdf", "text"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());


}
 
}





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
  <table width="100%" height="100%">
    <tr bgcolor="#FFFFFF"> 
      <td width="10" rowspan="2"><div align="center"> 
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p><br />
            <br />
            <br />
            <br />
          </p>
        </div></td>
      <td width="800"  rowspan="2"><iframe width="480" height="600" src="pdfeti632.php?url_user_id=<?php echo $row_rst1['id']; ?>&url_id_vg=<?php echo $row_rst3['id_vg']; ?>"></iframe></p> 
      </td>
      <td width="253" height="21"><font color="#009900"><img src="picture/uebersicht.gif" width="15" height="15" align="absmiddle" /> 
        <?php echo $oktxt ?> <?php echo $natxt ?></font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><p>&nbsp;</p>
        <p align="center"><a href="pdfeti632.php?url_user_id=<?php echo $row_rst1['id']; ?>&url_id_vg=<?php echo $row_rst3['id_vg']; ?>"><img src="picture/dokument.png" width="55" height="55" border="0" /></a></p>
        <p align="center"><a href="pdfeti632.php?url_user_id=<?php echo $row_rst1['id']; ?>&url_id_vg=<?php echo $row_rst3['id_vg']; ?>">Nachricht<br />
          ausdrucken</a></p>
        <p>&nbsp; </p>
        <hr />
        <p>AN: 
          <input name="an" type="text" id="an" value="<?php  echo $row_rst3['email']; ?>" size="35" />
        </p>
        <p>BCC: 
          <input name="cc" type="text" id="cc" value="<?php  echo $row_rst1['email']; ?>" size="35" />
        </p>
        <p align="right"> <img src="picture/sort_status2.gif" width="18" height="18" /> 
          <input name="senden" type="submit" id="senden" value="senden" />
        </p>
        <p> 
          <input name="url_id_vg" type="hidden" id="url_id_vg" value="<?php echo $url_id_vg ?>" />
        </p>
        <p> 
        <hr></p>
        <p align="right"><a href="javascript:window.close()"><br />
          <br />
          </a><br />
          <img src="picture/error.gif" width="15" height="15" /> 
          <input name="close" type="submit" id="senden" value="Fenster schliessen" onclick="javascript:window.close()"/>
        </p></td>
    </tr>
  </table>

  
  
</form>

</body>
</html>
