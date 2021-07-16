<?php

include("./config.php");


switch ($_GET['datatype']) {
    case "report" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->GetReports();
            break;
        }
    case "fids" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammCountFID(99);
            break;
        }
    case "fids0" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammCountFID(0);
            break;
        }
    case "fids1" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammCountFID(1);
            break;
        }
    case "fids2" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammCountFID(2);
            break;
        }
    case "fids3" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammCountFID(3);
            break;
        }
    case "fids4" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammCountFID(4);
            break;
        }
    case "fids5" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammCountFID(5);
            break;
        }
    case "fail" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammFailureQuantity(false);
            break;
        }
    case "failgwl" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammFailureQuantity(true);
            break;
        }
    case "countfms" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQReturnCount(false);
            break;
        }
    case "countgwlfms" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQReturnCount(true);
            break;
        }
    case "q" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQuailty(false);
            break;
        }
    case "qgwl" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQuailty(true);
            break;
        }
    case "t0" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQReturnTimelinie(false, 0);
            break;
        }
    case "t1" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQReturnTimelinie(false, 1);
            break;
        }
    case "t2" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQReturnTimelinie(false, 2);
            break;
        }
    case "t10" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQReturnTimelinie(true, 0);
            break;
        }
    case "t11" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQReturnTimelinie(true, 1);
            break;
        }
    case "t12" :
        {
            $r = new FisReport($_SESSION['report_report_group']);
            $r->id = $_GET['id'];
            $r->datum1 = $_GET['datum1'];
            $r->datum2 = $_GET['datum2'];
            echo $r->DiagrammQReturnTimelinie(true, 2);
            break;
        }
    default:
        {
            echo "unkown data type";
            break;
        }
}

exit;
