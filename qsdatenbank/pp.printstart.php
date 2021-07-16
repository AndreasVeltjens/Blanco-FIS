<?php 
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}

$updateGoTo = "pdfetikett4automatisch.php?".substr($editFormAction,25,1000)."";
header(sprintf("Location: %s", $updateGoTo)); 



?><html>
<head>
<title>iFIS Druckerwarteschleife</title>
<meta http-equiv="refresh" content="5; URL=<?php echo $updateGoTo ?>; text/html; charset=utf-8" />
</head>

<body>
<p align="center"><img src="picture/signaturen.gif" width="70" height="50"> <font size="+2">Ihre 
  Druckauftr&auml;ge werden bearbeitet.<br>
Bitte warten....<br>
  </font></p>
<p align="center"> 
  <?php echo $druckername; ?>
  &nbsp; 
  <?php echo $druckjob; ?>
  &nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
</body>
</html>
