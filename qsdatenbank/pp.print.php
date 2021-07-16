<?php 
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
if ($location=="611403"){
$updateGoTo = "pp.611403.php".substr($editFormAction,25,1000)."&oktxt=Pdf erzeugt.";

}elseif ($location=="611402"){
$updateGoTo = "pp.611402.php".substr($editFormAction,25,1000)."&oktxt=Pdf erzeugt.";

}elseif ($location=="611404"){
$updateGoTo = "pp.611404.php".substr($editFormAction,25,1000)."&oktxt=Pdf erzeugt.";

}elseif ($location=="199190"){
$updateGoTo = "pp.199190.php".substr($editFormAction,25,1000)."&oktxt=Pdf erzeugt.";


}else{
$updateGoTo = "pp.611401.php".substr($editFormAction,25,1000)."&oktxt=Pdf erzeugt.";
}
/* header(sprintf("Location: %s", $updateGoTo));  */




?><html>
<head>
<title>FIS Druckerwarteschleife</title>
<meta http-equiv="refresh" content="10; URL=<?php echo $updateGoTo ?>; text/html; charset=utf-8" />
</head>

<body>
<p align="center"><img src="picture/signaturen.gif" width="70" height="50"> <font size="+4">Ihre 
  Druckauftr&auml;ge werden angelegt <br>
  und automatisch gestartet</font><font size="+2">. </font></p>
<p align="center"> 
  <?php echo $druckername; ?>
  &nbsp; 
  <?php echo $druckjob; ?>
  &nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center"><img src="picture/druckjob.jpg" width="120" height="90"></p>
<p align="center"> <font color="#FF0000"><img src="picture/icon_info.gif" width="14" height="14" align="absmiddle"> 
  &nbsp; 
  <?php echo $oktxt; ?>
  &nbsp;</font></p>
<p align="center">&nbsp;</p>
</body>
</html>
