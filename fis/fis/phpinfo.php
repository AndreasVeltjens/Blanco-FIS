<?php

include("./config.php");


switch ($_GET['datatype']) {


    case "phpinfo":
        {

            echo phpinfo();

            break;
        }


    default:
        {
            echo "unkown data type";
            break;
        }
}

exit;
