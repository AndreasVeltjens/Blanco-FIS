<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 12.11.18
 * Time: 13:49
 */
include ("./general.php");
include ("./FisEquipment.php");
include ("./FisDatalog.php");

$udid=new FisDatalog($_REQUEST['udid']);

echo $udid->ImportData($_REQUEST['type'],$_REQUEST['data']);