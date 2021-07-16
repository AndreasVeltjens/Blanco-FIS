<head>
<title>FIS - <?php echo $la ?></title>
<?php 
$a=0;
if ($vis>=1) {

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_stp = "SELECT * FROM kennzahlen_steps WHERE kennzahlen_steps.id_kzv='$vis' AND kennzahlen_steps.lokz=0 ORDER BY kennzahlen_steps.reihenfolge";
$stp = mysql_query($query_stp, $qsdatenbank) or die(mysql_error());
$row_stp = mysql_fetch_assoc($stp);
$totalRows_stp = mysql_num_rows($stp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_kzv = "SELECT * FROM kennzahlen_visualisierung WHERE kennzahlen_visualisierung.id_kzv='$vis' ";
$kzv = mysql_query($query_kzv, $qsdatenbank) or die(mysql_error());
$row_kzv = mysql_fetch_assoc($kzv);
$totalRows_kzv = mysql_num_rows($kzv);


do{
	$Modul[$a]=array($row_stp['modul'],$row_stp['name'],0,$row_stp['modul'].".php?url_user=$url_user&vis=".$row_kzv['id_kzv']."&".$row_stp['parameter'],$row_stp['parameter']);
	$a=$a+1;

		If ($row_kzv['step']==$row_stp['reihenfolge']){
			$link=$row_stp['modul'].".php?url_user=".$url_user."&vis=".$row_kzv['id_kzv'].$row_stp['parameter'];
			 $aktiv_url_id_kz=$url_id_kz;
			 $aktiv_show_pareto=$show_pareto;
			 $anzeigedauer = $row_stp['anzeigedauer'];
			$anzeigezaehler = $row_stp['reihenfolge']+1;
			
			if ($totalRows_stp < $anzeigezaehler){
				$anzeigezaehler=1;
				}
			$updateSQL = sprintf("UPDATE kennzahlen_visualisierung SET step=%s WHERE id_kzv=%s",
								   $anzeigezaehler,
								   $row_kzv['id_kzv']);
			  mysql_select_db($database_qsdatenbank, $qsdatenbank);
			  $Result1 = mysql_query($updateSQL, $qsdatenbank) or die(mysql_error());
			
			
		}
	
}while ($row_stp= mysql_fetch_assoc($stp));
?>
<meta http-equiv="refresh" content="<?php echo $anzeigedauer ?>; URL=<?php echo $link ?>; text/html; charset=utf-8" />
<?php 

}else{


/* <script type="text/javascript">
function Hinweis () {
  var x = confirm("Sie sind jetzt schon 30 Minuten auf dieser Seite. Fortfahren? Wenn Sie nein drücken wird das FIS beendet.");
  if (x == false)
    top.close();
}

window.setTimeout("Hinweis()", 100000000);
</script> */?>
		<?php 
			if ($la=="log"){?>
				<meta http-equiv="refresh" content="100; URL=http://cde9aw01" />
		<?php }elseif ($la=="start" && $location=="611301") {?>
				<meta http-equiv="refresh" content="300; URL=http://cde9aw01/qsdatenbank/pp.611301.php" />
		<?php }elseif ($la=="start" && $location=="611302") {?>
				<meta http-equiv="refresh" content="300; URL=http://cde9aw01/qsdatenbank/pp.611302.php" />
		<?php }elseif ($la=="start" && $location=="611303") {?>
				<meta http-equiv="refresh" content="300; URL=http://cde9aw01/qsdatenbank/pp.611303.php" />
		<?php }elseif ($la=="start" && $location=="611304") {?>
				<meta http-equiv="refresh" content="300; URL=http://cde9aw01/qsdatenbank/pp.611304.php" />

		<?php }elseif ($la=="start" && $location=="611101") {?>
				<meta http-equiv="refresh" content="500; URL=http://cde9aw01/qsdatenbank/pp.611101.php" />
		<?php }elseif ($la=="start" && $location=="611102") {?>
				<meta http-equiv="refresh" content="500; URL=http://cde9aw01/qsdatenbank/pp.611102.php" />
		<?php }elseif ($la=="start" && $location=="611103") {?>
				<meta http-equiv="refresh" content="400; URL=http://cde9aw01/qsdatenbank/pp.611103.php" />

		
		<?php }elseif ($la=="start" && $location=="611401") {?>
				<meta http-equiv="refresh" content="10000; URL=http://cde9aw01/qsdatenbank/pp.611401.php" />
		
		<?php }elseif ($la=="start" && $location=="611402") {?>
				<meta http-equiv="refresh" content="10000; URL=http://cde9aw01/qsdatenbank/pp.611402.php" />
		<?php }elseif ($la=="start" && $location=="611403") {?>
				<meta http-equiv="refresh" content="10000; URL=http://cde9aw01/qsdatenbank/pp.611403.php" />
		<?php }elseif ($la=="start" && $location=="611404") {?>
				<meta http-equiv="refresh" content="10000; URL=http://cde9aw01/qsdatenbank/pp.611404.php" />
		<?php }elseif ($la=="start" && $location=="611405") {?>
				<meta http-equiv="refresh" content="500; URL=http://cde9aw01/qsdatenbank/pp.611405.php" />
		
		<?php }elseif ($la=="start" && $location=="611406") {?>
				<meta http-equiv="refresh" content="1000; URL=http://cde9aw01/qsdatenbank/pp.611406.php" />
		<?php }elseif ($la=="start" && $location=="611407") {?>
				<meta http-equiv="refresh" content="1000; URL=http://cde9aw01/qsdatenbank/pp.611407.php" />
		<?php }elseif ($la=="start" && $location=="611408") {?>
				<meta http-equiv="refresh" content="200000; URL=http://cde9aw01/qsdatenbank/pp.611408.php" />
		
		
		<?php }elseif ($la=="vl1") {?>
				<meta http-equiv="refresh" content="60; URL=http://cde9aw01/qsdatenbank/vl1.php" />
		<?php }else { ?>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php }?>
<?php } /* ende wenn kein vis */?>


<style type="text/css">

 
  div#Rahmen {

    width: 100em;
    padding: 0.3em;
    border: 1px solid #CCCCCC;
    background-color: #FFFFFF;
  }

  * html div#Rahmen {  /* Korrektur fuer IE 5.x */
    width: 100em;
    w\idth:100em;
  }

  div#Rahmen div {
     clear: left;
  }
  ul#Navigation {
    margin: 0; 
	padding: 0;
	
    text-align: left;
  }
 ul#Navigation li {
    list-style: none;
    float: left;  /* ohne width - nach CSS 2.1 erlaubt */
    position: relative;
	
    margin: 0.0em; padding: 0;
  }
  * html ul#Navigation li {  /* Korrektur fuer den IE 5 und 6 */
    margin-bottom: -0.0em;
  }
  *:first-child+html ul#Navigation li {  /* Korrektur fuer den IE 7 */
    margin-bottom: -0.0em;
  }
  ul#Navigation li ul {
    margin: 0; padding: 0;
    position: absolute;
    top: 1.3em; left: -0.0em;
    display: none;  /* Unternavigation ausblenden */
  }
  * html ul#Navigation li ul {  /* Korrektur fuer IE 5.x */
    left: -0.5em;
    lef\t: -0.1em;
  }
  *:first-child+html ul#Navigation ul {  /* Workaround fuer den IE 7 */
    background-color:silver; padding-bottom:0.0em;
  }
  ul#Navigation li:hover ul {
    display: block;  /* Unternavigation in modernen Browsern einblenden */
  }
  ul#Navigation li ul li {
    float: none;
    display: block;
    margin-bottom: 0.0em;
	background-color: #CCCCCC;
  }
  ul#Navigation a, ul#Navigation span {
    display: block;
    width: 23.4em;  /* Breite den in li enthaltenen Elementen zuweisen */
    padding: 0.0em 0em;
    text-decoration: none; font-weight: bold;
    border: 1px solid #CCCCCC;
    border-left-color: white; border-top-color: white;
    color: maroon; 
	background-color: #CCCCCC;
  }
  * html ul#Navigation a, * html ul#Navigation span {
    width: 23.6em;   /* Breite nach altem MS-Boxmodell fuer IE 5.x */
    w\idth: 23.4em;  /* korrekte Breite fuer den IE 6 im standardkompatiblen Modus */
  }
  ul#Navigation a:hover, ul#Navigation span, li a#aktuell {
  	/* rollover status*/
    border-color: white;
    border-left-color: black; border-top-color: black;
	color: white; 
	background-color: #CCCCFF;
  }
  li a#aktuell {  /* aktuelle Rubrik kennzeichnen */
    color: maroon; 
	background-color: #CCCCCC;
  }
  ul#Navigation li ul span {  /* aktuelle Unterseite kennzeichnen */
	color: black; 
	text-decoration: none; font-weight: normal;
    background-color: #FF6600;
  }
</style>

<script type="text/javascript">
if(window.navigator.systemLanguage && !window.navigator.language) {
  function hoverIE() {
    var LI = document.getElementById("Navigation").firstChild;
    do {
      if (sucheUL(LI.firstChild)) {
        LI.onmouseover=einblenden; LI.onmouseout=ausblenden;
      }
      LI = LI.nextSibling;
    }
    while(LI);
  }

  function sucheUL(UL) {
    do {
      if(UL) UL = UL.nextSibling;
      if(UL && UL.nodeName == "UL") return UL;
    }
    while(UL);
    return false;
  }

  function einblenden() {
    var UL = sucheUL(this.firstChild);
    UL.style.display = "block"; UL.style.backgroundColor = "silver";
  }
  function ausblenden() {
    sucheUL(this.firstChild).style.display = "none";
  }

  window.onload=hoverIE;
}
</script>


<script language="JavaScript" type="text/javascript">
<!--
    /*
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
    // Ugly fix for Opera and Konqueror 2.2 that are half DOM compliant
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
    document.writeln('<link rel="stylesheet" type="text/css" href="css/phpmyadmin.css.php?lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci&amp;js_frame=right&amp;js_isDOM=' + isDOM + '" />');
//-->
</script>
<script language="JavaScript" type="text/javascript">
function sf(){document.form2.suchtext.focus()}
</script>
<noscript>
    <link rel="stylesheet" type="text/css" href="css/phpmyadmin.css.php?lang=de-utf-8&amp;server=1&amp;collation_connection=utf8_general_ci&amp;js_frame=right" />
</noscript>


    <script src="./libraries/functions.js" type="text/javascript" language="javascript"></script>
	<script src="./libraries/tbl_change.js" type="text/javascript" language="javascript"></script>

	
<meta name="OBGZip" content="true" />
</head>
<?php if ($suchtextfocus==1){?>
<body onLoad="Javascript:sf()">
<?php }else { ?>
<body>
<?php }?>
<table width="99%" border="0" cellpadding="3" cellspacing="0" background="picture/blanco_header.jpg">
  <tr> 
    <td width="41%" rowspan="2"><font color="#FFFFFF" size="5" face="Arial, Helvetica, sans-serif"><font color="#FF0000" size="1"> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i</font><font size="1">FIS-Fertigungsinfosystem</font></font><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"> 
      <br>
      <br>
      <br>
      </font></td>
    <td width="35%" bgcolor="#CCCCCC"><font size="1">Datum: 
      <?php echo date("Y-m-d",time()); ?>
      letzte Aktualisierung: 
      <?php echo date("H:i:s",time()); ?>
      </font></td>
    <td width="24%" rowspan="2" bgcolor="#6699FF"><div align="right"><strong><font size="1">Anwendungsserver: 
        cde9aw01</font></strong> </div></td>
  </tr>
  <tr> 
    <td bgcolor="#CCCCCC"><font size="1" face="Arial, Helvetica, sans-serif">angemeldet: 
      <?php echo $row_rst1['name']; ?> - Werk <?php echo $row_rst1['werk']; ?> </font> <font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"> 
      - 
      <?php echo $aktiv_url_id_kz; ?>
      </font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; </font></td>
  </tr>
</table>
<table align="center"><tr><td>
