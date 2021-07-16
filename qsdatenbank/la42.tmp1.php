<?php 

/* Prüfen und anlegen von Positisonsdaten */

$kostenklarung = $HTTP_POST_VARS['fm2variante'];
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_entid = "SELECT entscheidung.entid, entscheidung.entname, entscheidung.entmemo, entscheidungsvarianten.varid, entscheidungsvarianten.varname, entscheidungsvarianten.varbeschreibung, entscheidungsvarianten.Kosten1 FROM entscheidung, entscheidungsvarianten WHERE entscheidung.lokz=0 AND entscheidungsvarianten.lokz=0 AND entscheidungsvarianten.entid=entscheidung.entid and entscheidung.entid=$kostenklarung ORDER BY entscheidung.entname, entscheidungsvarianten.varname";
$entid = mysql_query($query_entid, $qsdatenbank) or die(mysql_error());
$row_entid = mysql_fetch_assoc($entid);
$totalRows_entid = mysql_num_rows($entid);

if ($totalRows_entid >0 ){

do {
  $insertSQL = sprintf("INSERT INTO fehlermeldungsdaten (varname, varbeschreibung, Kosten1, Datum, lokz, entid, fmid) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($row_entid['varname'], "text"),
                       GetSQLValueString($row_entid['varbeschreibung'], "text"),
                       GetSQLValueString($row_entid['Kosten1'], "double"),
                       "now()",
                       "0",
                       "0",
                       GetSQLValueString($HTTP_POST_VARS['hurl_fmid'], "int"));

  mysql_select_db($database_qsdatenbank, $qsdatenbank);
  $Result1 = mysql_query($insertSQL, $qsdatenbank) or die(mysql_error());


}while ($row_entid = mysql_fetch_assoc($entid)); 

} 

?>