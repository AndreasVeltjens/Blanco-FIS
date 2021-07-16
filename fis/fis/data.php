<?php

include ("./config.php");


switch ( $_GET['datatype']){
    case "fid" : {
        $c=new FisFid(0);
        $c->material=$_GET['fm_artikelid'];
        $c->lokz=$_GET['fid_search_lokz'];
        $c->limit=30;
        $c->gruppe = $_GET['fid_group'];
        echo   $c->ShowHtmlAssemblyAsTable(1,30,$_GET['fid_search_txt'],1,false,true);
        break;
    }
    case "ean13" : {
        if ($_GET['new_material_ean13']){
            $m=new FisMaterial($_SESSION['material']);
            $m->SaveEAN13Code($_GET['new_material_number'],$_GET['new_material_ean13']);
        }
        $v = new FisVersion($m->data['version'], $_SESSION['material']);

        echo $v->GetAllMaterialNumbersAndEANCodes();
        break;
    }

    case "wp":{
        $wp=new FisWorkplace($_GET['wp']);
        echo $wp->ShowHtmlWpFidGroup($_GET['wp']);
        break;
    }

    case "fmid":{
        $fm=new FisReturn(0);
        $fm->SetSearchString($_GET['sendungsid']);
        $fm->SetLimit(10);
        $fm->SetDatum($_GET['datum']);
        $fm->LoadRueckholungData();
        echo $fm->ShowFMList();
        break;
    }

    case "fidhistory":{
        $fid = new FisFid(0);
        $fid->data['fsn']=$_GET['fsn'];
        $fid->data['fartikelid']=$_GET['fartikelid'];
        echo $fid->GetFidHistory();
        break;
    }

    case "maintenancefhm":{
        $m=new FisMaintenance($_GET['pid']);
        echo $m->ShowHtmlAllocFhm(1,100,"");
        break;
    }

    case "maintenancenew":
        {
        $m=new FisMaintenance($_GET['pid']);
        echo $m->NewEventAsTable();
        break;
    }

    case "equipment":
        {
            $e = new FisEquipment($_GET['equipment']);
            echo $e->GetStatus();
            break;
        }
    case "reportmaterial":
        {
            $m = new FisReport($_GET['rid']);
            echo $m->ShowHtmlallocmaterial(1, 100, "");
            break;
        }
    case "reportmaterialnew":
        {
            $m = new FisReport($_GET['rid']);
            echo $m->NewEventAsTable();
            break;
        }

    case "addressbook":
        {
            $fmid = new FisReturn($_GET['fmid']);
            echo $fmid->ShowModalContacts();
            break;
        }

    case "fmidbycustomer":
        {
            $fm = new FisReturn(0);
            $fm->ref1 = $_GET['ref1'];
            $fm->ref2 = $_GET['ref2'];
            echo $fm->ShowHtmlAllReturn(0, 100, "", 0);
            break;
        }

    case "rueckidbycustomer":
        {
            $r = new FisReturnDevice(0);
            $r->idk = $_GET['idk'];
            echo $r->ShowHtmlAllReturnDevices(0, 100, "", 0);
            echo "<script> " . $_SESSION['endscripts'] . " </script>";
            break;
        }

    case "process":
        {
            $v = new FisVersion($_GET['version'], $_GET['material']);
            echo $v->GetProcessasTable();
            break;
        }


    default: {
        echo "unkown data type";
        break;
    }
}

exit;
