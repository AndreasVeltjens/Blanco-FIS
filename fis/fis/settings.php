<?php

include("./config.php");


switch ($_GET['datatype']) {


    case "printsettings":
        {
            $v = new FisVersion($_SESSION['version'], $_SESSION['material']);
            if (isset($_REQUEST['typenschild'])) {
                $r = array(
                    "typenschild" => $_REQUEST['typenschild'],
                    "verpackung" => $_REQUEST['verpackung'],
                    "einbau" => $_REQUEST['einbau'],
                    "dokuset" => $_REQUEST['dokuset'],
                    "fehlermeldung" => $_REQUEST['fehlermeldung'],
                    "wareneingang" => $_REQUEST['wareneingang']
                );
                $v->SavePrintSettings($r);
                $v->LoadData();
            }
            echo $v->ShowPrinterDefaults();

            break;
        }

    case "proveversionname":
        {
            $v = new FisVersion($_SESSION['version'], $_SESSION['material']);
            echo $v->ProveNewVersion($_REQUEST['newversion']);
            break;
        }

    default:
        {
            echo "unkown data type";
            break;
        }
}

exit;
