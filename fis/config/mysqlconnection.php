<?php

if ($_SERVER['SERVER_NAME']=="www.albafis.de" or $_SERVER['SERVER_NAME']=="albafis.de") {
    define('FIS_ID', 100001);
    $hostname_datenbank = "mysql5.updatedcc.de";
    $password_datenbank = "4tebshhPs,<u";
    $username_datenbank = "db437050_8";
    $database_datenbank = "db437050_8";
}
elseif ($_SERVER['SERVER_NAME']=="www.blancoprofessional.uni-plan.de" or $_SERVER['SERVER_NAME']=="blancoprofessional.uni-plan.de"){
    define('FIS_ID', 100002);
    $hostname_datenbank = "mysql5.updatedcc.de";
    $password_datenbank = "tRW_5dfu4V?u";
    $username_datenbank = "db437050_9";
    $database_datenbank = "db437050_9";
} elseif ($_SERVER['SERVER_NAME'] == "www.midas24.uni-plan.de" or $_SERVER['SERVER_NAME'] == "midas24.uni-plan.de") {
    define('FIS_ID', 100123);
    $hostname_datenbank = "mysql5.updatedcc.de";
    $password_datenbank = "z,hUa6R8hkVe";
    $username_datenbank = "db437050_10";
    $database_datenbank = "db437050_10";
} elseif ($_SERVER['SERVER_NAME'] == "www.alba-eh.uni-plan.de" or $_SERVER['SERVER_NAME'] == "alba-eh.uni-plan.de") {
    define('FIS_ID', 100112);
    $hostname_datenbank = "mysql5.updatedcc.de";
    $password_datenbank = "zKNcdw4v*yhN";
    $username_datenbank = "db437050_11";
    $database_datenbank = "db437050_11";

} elseif ($_SERVER['SERVER_NAME'] == "www.uniplan.uni-plan.de" or $_SERVER['SERVER_NAME'] == "uniplan.uni-plan.de") {
    define('FIS_ID', 100124);
    $hostname_datenbank = "mysql5.updatedcc.de";
    $password_datenbank = "Gam@wfs9pjeb";
    $username_datenbank = "db437050_12";
    $database_datenbank = "db437050_12";


}else{
    define('FIS_ID', 100002);
    $hostname_datenbank = "127.0.0.1";
    $database_datenbank = "qsdb";
    $username_datenbank = "web4";
    $password_datenbank = "Xp78XpZu";
}