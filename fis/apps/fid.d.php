<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 10:57
 */
include ("../apps/session.php");
include ("../apps/function.php");
$r=new ifisAllFid();
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
if (!isset($_REQUEST['view'])) {
    echo $r->GetJsonList(0);
} else{
    echo $r->GetJsonQSList(0);
}

exit;