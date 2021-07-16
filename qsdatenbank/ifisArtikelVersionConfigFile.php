<?php
/**
 * Created by PhpStorm.
 * User: Andreas Veltjens lok
 * Date: 06.04.2017
 * Time: 12:57
 */
require_once('Connections/qsdatenbank.php');
include ('IfisArtikelVersionConfig.php');


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM  artikeldaten, artikelversionen WHERE artikelversionen.id = '".$_GET['url_id']."'  AND artikeldaten.artikelid= artikelversionen.artikelid";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);


if (strlen($row_rst2['configprofil'])>0) {
    $configprofil = new IfisArtikelVersionConfig($row_rst2['configprofil']);
    header('Content-Type: application/json');
    echo $configprofil->GetJson();
}else{
    header('Content-Type: application/html');
    echo "Keine Konfigurationsdaten gefunden";
}
exit;