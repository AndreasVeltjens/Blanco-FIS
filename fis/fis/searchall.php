<?php

include ("./config.php");


switch ( $_GET['datatype']){
    case "material" :
        {
            $fms = new FisMaterial();
            $fms->kontierung=0;
            echo $fms->ShowHtmlAllAsTable(0, 20, $_REQUEST['search'], 1);
            break;
        }
    case "version" :
        {
            $fms = new FisVersion();
            echo $fms->ShowHtmlAllAsTable(0, 20, $_REQUEST['search'], 1);
            break;
        }
    case "returndevices" :
        {
            $fms = new FisReturnDevice();
            echo $fms->ShowHtmlAllReturnDevices(0, 10, $_REQUEST['search'], 1);
            break;
        }

    case "returns" :
        {
            $fms = new FisReturn();
            echo  $fms->ShowHtmlAllReturn(0, 20, $_REQUEST['search'], 1);
            break;
            }

    case "equipment" :
        {
            $fms = new FisEquipment();
            echo  $fms->ShowHtmlAllAsTable(0, 20, $_REQUEST['search'], 1);
            break;
        }
    default:
        {
            echo "unkown data type";
            break;
        }
}

exit;
