<?php 

$userright= $row_rst1['recht']; /* Definiton Variable für Funktion GetUserright */

$query_rstgetuserright1 = "SELECT userright.urid, userright.upid, userright.la, userright.lokz FROM qsdb.userright WHERE userright.upid='$userright' AND userright.la='$la' AND userright.lokz=0";
$rstgetuserright1 = mysql_query($query_rstgetuserright1, $qsdatenbank) or die(mysql_error());
$row_rstgetuserright1 = mysql_fetch_assoc($rstgetuserright1);
$totalRows_rstgetuserright1 = mysql_num_rows($rstgetuserright1);

mysql_free_result($rstgetuserright1);

if ($totalRows_rstgetuserright1==0 ){
$updateGoTo = "err.php?url_user=".$row_rst1['id']."&url_la=".$la;
  if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $HTTP_SERVER_VARS['QUERY_STRING'];
  }
  if (($la=="err")or ($la=="log") or ($la=="la5")){
  $errtxt="Fehler Berechtigung";
  }else{
  header(sprintf("Location: %s", $updateGoTo));
  }
}
?>