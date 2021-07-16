<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 2018-12-19
 * Time: 22:18
 */


//FID View

if (isset($_REQUEST['send_support_by_email'])) {
    // $u=new FisUser($_SESSION['active_user']);
    $mail = new FisMailer();
    $mail->SetCC("info@uni-plan.de");
    $mail->SetSubject("Supportanfrage UNI-PLAN.de" . FIS_COMPANY);
    $mail->SetContent("Supportanfrage:" . chr(13)
        . "Company: " . FIS_COMPANY . chr(13)
        . "Buchungskreis:" . FIS_ID . chr(13)
        . $_REQUEST['support-text'] . chr(13) . chr(13)
        . $_REQUEST['session-log']);
    //$mail->SetName($u->GetUsername());
    $mail->SendifisMail();
    AddSessionMessage("info", "Mail an info@uni-plan.de gesendet", "Supportanfrage gesendet");

}

if (isset($_REQUEST['fid_reset'])){
    $wp->action=42;
    $_REQUEST['fid_search_txt']="";
   // $_REQUEST['fid_search_material']="";
    $_REQUEST['fid_search_lokz']=0;
    $_REQUEST['fid_search_hits']=100;
    $_SESSION['fid_search_sn']="";
    $_REQUEST['fid_search_sn']="";
    $_SESSION['fid_search_material']="";
        $_REQUEST['fid_search_material']="";
}

if (isset($_REQUEST['fid_search'])){
    $_SESSION['fid_search_material']=$_REQUEST['fid_search_material'];
    $_SESSION['fid_search_limit']=$_REQUEST['fid_search_limit'];
    $_SESSION['fid_search_txt']=$_REQUEST['fid_search_txt'];
    $_SESSION['fid_search_sn']=$_REQUEST['fid_search_sn'];
    $_SESSION['fid_search_lokz']=$_REQUEST['fid_search_lokz'];
    $wp->action=42;
}


//Fauf
if (isset($_REQUEST['new_fauf_add']) or isset($_REQUEST['fauf_save'])) {
    $r = new FisFauf($_SESSION['faufid']);
    $r->Save();
    $wp->action = 72;
}

if (isset($_REQUEST['fauf_reload_defaults'])) {
    $r = new FisFauf($_SESSION['faufid']);
    $r->LoadMaterialProcessData();
    $wp->action = 72;
}


if (isset($_REQUEST['fauf_search'])) {
    $_SESSION['fauf_search_fauftype'] = $_REQUEST['fauf_search_fauftype'];
    $_SESSION['fauf_search_faufstate'] = $_REQUEST['fauf_search_faufstate'];
    $_SESSION['fauf_search_limit'] = $_REQUEST['fauf_search_limit'];
    $_SESSION['fauf_search_txt'] = $_REQUEST['fauf_search_txt'];
    $_SESSION['fauf_search_lokz'] = $_REQUEST['fauf_search_lokz'];
    $wp->action = 71;
}

if (isset($_REQUEST['fauf_reset'])) {

    $_REQUEST['fauf_search_txt'] = "";
    $_REQUEST['fauf_search_fauftype'] = 0;
    $_REQUEST['fauf_search_faufstate'] = 0;
    $_REQUEST['fauf_search_lokz'] = 0;
    $_REQUEST['fauf_search_hits'] = 100;
    $wp->action = 71;
}


//Material list 34
if (isset($_REQUEST['material_search'])){
    $_SESSION['material_search_kontierung']=$_REQUEST['material_search_kontierung'];
    $_SESSION['material_search_group']=$_REQUEST['material_search_group'];
    $_SESSION['material_search_productfamily']=$_REQUEST['material_search_productfamily'];
    $_SESSION['material_search_lokz']=$_REQUEST['material_search_lokz'];
    $wp->action=34;
}
if (isset($_REQUEST['material_reset'])){
    $wp->action=34;
    $_SESSION['material_search_kontierung']="";
    $_SESSION['material_search_productfamily']="";
    $_SESSION['material_search_group']="";
    $_SESSION['material_search_lokz']="";
    $_REQUEST['material_search_lokz']="";
    $_REQUEST['material_search_txt']="";
    $_REQUEST['material_search_hits']=100;
}



if (isset($_REQUEST['fmid_save'])
    or isset($_REQUEST['fm_new_varianten'])
    or isset($_REQUEST['fm_add_varianten'])
    or isset($_REQUEST['fm_new_contact'])
    or isset($_REQUEST['fm_new_material'])
){
    $r=new FisReturn($_REQUEST['fmid']);
    $r->Save();
    $wp->action=25;
}

if (isset($_REQUEST['fmid_save_to_list'])){
    $r=new FisReturn($_REQUEST['fmid']);
    $r->Save();
    $wp->action=23;
}
if (isset($_REQUEST['fmid_reset'])){
    $wp->action=23;
}

//Mitarbeiter

if (isset($_REQUEST['user_save']) or isset($_REQUEST['user_new'])){
    $r=new FisUser($_REQUEST['userid']);
    $r->Save();
    $wp->action=36;
}
if (isset($_REQUEST['user_search'])){
    $wp->action=35;
}
if (isset($_REQUEST['user_reset'])){
    $wp->action=35;
    $_REQUEST['user_search_txt']="";
    $_REQUEST['user_search_hits']=100;
}

//fehlermeldung
if (isset($_REQUEST['fm_search'])){
    $wp->action=23;
}
if (isset($_REQUEST['fm_reset'])){
    $wp->action=23;
    $_REQUEST['fm_search_txt']="";
    $_REQUEST['fm_search_hits']=100;
}

if (isset($_REQUEST['fm_last_edit'])){
    if ($_SESSION['fmid'] or $_REQUEST['fmid']>0){
        $wp->action = 24;
    }else {
     AddSessionMessage("info","Es wurde keineFehlermeldung zur Bearbeitung gefunden. Es wird die Übersicht angezeigt.","Fehlermeldungen anzeigen");
        $wp->action = 23;
    }
    $_REQUEST['fm_search_txt']="";
    $_REQUEST['fm_search_hits']=100;
}
if (isset($_REQUEST['fm_save_new'])){
    $fm=new FisReturn(0);
    if ($fm->Add()==0) {
        $wp->action = 24;
    }else{
        $wp->action = 25;
    }
}


//Equipments
if (isset($_REQUEST['fhm_new']) or isset($_REQUEST['fhm_save'])){
    $r=new FisEquipment(0);
    $r->Save();
    $wp->action=31;
}
if (isset($_REQUEST['fhm_search'])){
    $_SESSION['fhm_search_group']=$_REQUEST['fhm_search_group'];
    $_SESSION['fhm_search_limit']=$_REQUEST['fhm_search_limit'];
    $_SESSION['fhm_search_txt']=$_REQUEST['fhm_search_txt'];
    $_SESSION['fhm_search_lokz']=$_REQUEST['fhm_search_lokz'];
    $wp->action=30;
}

if (isset($_REQUEST['fhme_search']) ){
    $_SESSION['fhm_search_group']=$_REQUEST['fhm_search_group'];
    $_SESSION['fhm_search_eventtype']=$_REQUEST['fhm_search_eventtype'];
    $_SESSION['fhm_search_limit']=$_REQUEST['fhm_search_limit'];
    $_SESSION['fhm_search_txt']=$_REQUEST['fhm_search_txt'];
    $_SESSION['fhm_search_lokz']=$_REQUEST['fhm_search_lokz'];

}
if (isset($_REQUEST['fhme_reset'])){
    $wp->action=45;
    $_REQUEST['fhm_search_txt']="";
    $_REQUEST['fhm_search_eventtype']="";
    $_REQUEST['fhm_search_group']="";
    $_REQUEST['fhm_search_lokz']=0;
    $_REQUEST['fhm_search_hits']=100;
}

if (isset($_REQUEST['fhm_event_new'])){
    $r=new FisEquipment($_SESSION['equip']);
    $r->AddEquipmentEvent();
    $wp->action=31;
}

if (isset($_REQUEST['new_maintenance_add']) or isset($_REQUEST['maintenance_save']) ){
    $r=new FisMaintenance($_SESSION['pid']);
    $r->Save();
    $wp->action=64;
}

if (isset($_REQUEST['maintenance_save_alloc']) ){
    $r=new FisMaintenance($_SESSION['pid']);
    $r->SaveFhm();
    $wp->action=64;
}


if (isset($_REQUEST['new_maintenance_docu']) ){
    $r=new FisMaintenance($_POST['new_pid']);
    $r->SaveEvent();
    $_SESSION['pid']=$_POST['new_pid'];
    $wp->action=64;
}


if (isset($_REQUEST['report_search'])) {
    $_SESSION['fhm_search_group'] = $_REQUEST['fhm_search_group'];
    $_SESSION['fhm_search_eventtype'] = $_REQUEST['fhm_search_eventtype'];
    $_SESSION['fhm_search_limit'] = $_REQUEST['fhm_search_limit'];
    $_SESSION['fhm_search_txt'] = $_REQUEST['fhm_search_txt'];
    $_SESSION['fhm_search_lokz'] = $_REQUEST['fhm_search_lokz'];

}

if (isset($_REQUEST['report_reset'])) {
    $wp->action = 68;
    $_REQUEST['fhm_search_txt'] = "";
    $_REQUEST['fhm_search_eventtype'] = "";
    $_REQUEST['fhm_search_group'] = "";
    $_REQUEST['fhm_search_lokz'] = 0;
    $_REQUEST['fhm_search_hits'] = 100;
}


if (isset($_REQUEST['new_report_add']) or isset($_REQUEST['report_save'])) {
    $r = new FisReport($_SESSION['rid']);
    $r->Save();
    $wp->action = 69;
}

if (isset($_REQUEST['report_save_alloc'])) {
    $r = new FisReport($_SESSION['rid']);
    $r->SaveFhm();
    $wp->action = 69;
}


if (isset($_REQUEST['new_report_docu'])) {
    $r = new FisReport($_POST['new_rid']);
    $r->SaveEvent();
    $_SESSION['rid'] = $_POST['new_rid'];
    $wp->action = 69;
}


if (isset($_REQUEST['report_report_search'])) {
    $_SESSION['report_datum_von'] = $_REQUEST['report_datum_von'];
    $_SESSION['report_datum_bis'] = $_REQUEST['report_datum_bis'];
    $_SESSION['report_material'] = $_REQUEST['report_material'];
    $_SESSION['report_format'] = $_REQUEST['report_format'];
    $_SESSION['report_limit'] = $_REQUEST['report_limit'];
    $_SESSION['report_compare'] = $_REQUEST['report_compare'];
    $_SESSION['report_aspect'] = $_REQUEST['report_aspect'];
    $_SESSION['report_sizeheight'] = $_REQUEST['report_sizeheight'];

    $_SESSION['report_datum_von2'] = $_REQUEST['report_datum_von2'];
    $_SESSION['report_datum_bis2'] = $_REQUEST['report_datum_bis2'];

    $_SESSION['report_report_group'] = $_REQUEST['report_report_group'];
    $_SESSION['reporttype'] = $_REQUEST['reporttype'];
    $_SESSION['report_search_limit'] = $_REQUEST['report_search_limit'];
    $_SESSION['report_search_txt'] = $_REQUEST['report_search_txt'];
    $_SESSION['report_search_lokz'] = $_REQUEST['report_search_lokz'];

}

if (isset($_REQUEST['report_report_reset'])) {
    $wp->action = 70;
    $_SESSION['report_datum_von'] = date("Y-m-d", time() - (2 * 365 * 24 * 60 * 60));
    $_SESSION['report_datum_bis'] = date("Y-m-d", time());
    $_SESSION['report_datum_von2'] = date("Y-m-d", time() - (1 * 365 * 24 * 60 * 60));
    $_SESSION['report_datum_bis2'] = date("Y-m-d", time());

    $_SESSION['report_sizeheight'] = 400;
    $_SESSION['report_aspect'] = "ct-golden-section";
    $_SESSION['report_compare'] = 0;
    $_SESSION['report_material'] = 0;
    $_SESSION['report_search_txt'] = "";
    $_SESSION['reporttype'] = 0;
    $_SESSION['report_format'] = 0;
    $_SESSION['report_report_group'] = "";
    $_SESSION['report_search_lokz'] = 0;
    $_SESSION['report_search_hits'] = 100;
}




if (isset($_REQUEST['maintenance_search'])){
    $_SESSION['fhm_search_group']=$_REQUEST['fhm_search_group'];
    $_SESSION['fhm_search_eventtype']=$_REQUEST['fhm_search_eventtype'];
    $_SESSION['fhm_search_limit']=$_REQUEST['fhm_search_limit'];
    $_SESSION['fhm_search_txt']=$_REQUEST['fhm_search_txt'];
    $_SESSION['fhm_search_lokz']=$_REQUEST['fhm_search_lokz'];

}

if (isset($_REQUEST['maintenancedue_search'])){
    $_SESSION['fhm_search_group']=$_REQUEST['fhm_search_group'];
    $_SESSION['fhm_search_eventtype']=$_REQUEST['fhm_search_eventtype'];
    $_SESSION['fhm_search_limit']=$_REQUEST['fhm_search_limit'];
    $_SESSION['fhm_search_txt']=$_REQUEST['fhm_search_txt'];
    $_SESSION['fhm_search_lokz']=$_REQUEST['fhm_search_lokz'];

}

if (isset($_REQUEST['re_reset'])){
    $wp->action=30;
    $_REQUEST['fhm_search_txt']="";
    $_REQUEST['fhm_search_idk']="";
    $_REQUEST['fhm_search_lokz']=0;
    $_REQUEST['fhm_search_hits']=100;
}




//Rueckholung
if (isset($_REQUEST['re_new']) or isset($_REQUEST['re_save'])){
    $r=new FisReturnDevice(0);
    $r->Save();
    $wp->action=22;
}
if (isset($_REQUEST['re_search'])){
    $wp->action=22;
}

if (isset($_REQUEST['re_reset'])){
    $wp->action=22;
    $_REQUEST['re_search_txt']="";
    $_REQUEST['re_search_idk']="";
    $_REQUEST['re_search_hits']=20;
}

// Warehouse
if (isset($_REQUEST['we_new']) or isset($_REQUEST['we_new_position']) or isset($_REQUEST['we_pos_copy']) ){
    $r=new FisWarehouse($_GET['weid']);
    $r->Save();
    $wp->action=38;
}
if (isset($_REQUEST['we_save']) or isset($_REQUEST['sign_save'])){
    $r=new FisWarehouse($_GET['weid']);
    $r->Save();
    $wp->action=38;
}

if (isset($_REQUEST['we_search'])){
    $_SESSION['fid_search_material']=$_REQUEST['fid_search_material'];
    $wp->action=37;
}
if (isset($_REQUEST['we_reset'])){
    $wp->action=37;
    $_REQUEST['we_search_txt']="";
    $_REQUEST['fid_search_material']="";
    $_SESSION['fid_search_material']="";
    $_REQUEST['we_search_hits']=100;
}


// Customer
if (isset($_REQUEST['customer_new']) ){
    $r=new FisCustomer($_GET['idk']);
    $r->Save();
    $wp->action=28;
}
if (isset($_REQUEST['customer_save'])){
    $r=new FisCustomer($_GET['idk']);
    $r->Save();
    $wp->action=27;
}

if (isset($_REQUEST['customer_search'])){
    $wp->action=27;
}
if (isset($_REQUEST['customer_reset'])){
    $wp->action=27;
    $_REQUEST['customer_search_txt']="";
    $_REQUEST['suchbegriff_search_txt'] = "";
    $_REQUEST['customer_search_hits']=100;
}

//Verbesserung
if (isset($_REQUEST['new_improvement'])) {
    $wp->AddNewEvent();
    $wp->action=0;
}

if (isset($_REQUEST['new_maintenance'])) {
    $wp->AddNewEvent();
    $wp->action=0;
}

if (isset($_REQUEST['new_fail'])) {
    $wp->AddNewEvent();
    $wp->action=0;
}

if (isset($_REQUEST['new_degreeoforder'])) {
    $wp->AddNewDegreeOfOrder();
    $wp->action=0;
}

if (isset($_REQUEST['new_save_alloweduser'])) {
    $wp->SaveAllowedUser();
    $wp->action=0;
}
if (isset($_REQUEST['new_parameterchange'])) {
    $wp->AddNewEvent();
    $wp->action=0;
}

if (isset($_REQUEST['new_www'])) {
    $wp->AddNewEvent();
    $wp->action=0;
}

if (isset($_REQUEST['new_event'])) {
    $wp->AddNewEvent();
    $wp->action=0;
}

if (isset($_REQUEST['new_prove'])) {
    $wp->AddNewEvent();
    $wp->action=0;
}

if (isset($_REQUEST['new_calibration'])) {
    $wp->AddNewEvent();
    $wp->action=0;
}

if (isset($_REQUEST['save_tool_defaultsettings']) or isset($_REQUEST['new_tool_settings'])){
    $tool=new FisTool(0);
    $tool->SaveToolSettings();
    $wp->action=21;
}

if (isset($_REQUEST['save_plan_defaultsettings']) or isset($_REQUEST['new_plan_settings'])){
    $tool=new FisPlan(0);
    $tool->SavePlanSettings();
    $wp->action=21;
}

if (isset($_REQUEST['new_save_wpsettings'])
    or isset($_REQUEST['save_wp_printersettings'])
    or isset($_REQUEST['save_wp_defaultsettings'])
    or isset($_REQUEST['save_wp_defaultprintersettings'])
) {
    $wp->SaveWpSettings();
    $wp->action=0;
}

if (isset($_REQUEST['save_allwp_defaultsettings'])){
    $wp->LoadAllWpSettings();

}

if (isset($_REQUEST['material_new']) or isset($_REQUEST['save_material'])) {
    $wp->SaveMaterial();
    $wp->action=15;
}

if (isset($_REQUEST['save_ean13_code'])) {
    $m=new FisMaterial($_SESSION['material']);
    $m->SaveEAN13Code($_REQUEST['new_material_number'],$_REQUEST['new_material_ean13']);
    $wp->action=15;
}



if (isset($_REQUEST['import_FIS9_material'])) {
    $m=new FisMaterial($_SESSION['material']);
    $m->ImportFis9data();
    $wp->action=15;
}

if (isset($_REQUEST['new_save_version'])) {
    $wp->SaveVersion();
    $wp->action=15;
}

if (isset($_REQUEST['save_version_as_free'])) {
    $wp->SaveVersionAsFree();
    $wp->action = 15;
}

if (isset($_REQUEST['change_version_name'])) {
    $wp->ChangeVersionName();
    $wp->action = 15;
}
if (isset($_REQUEST['delete_version_name'])) {
    $wp->DeleteVersionName();
    $wp->action = 15;
}
if (isset($_REQUEST['reset_version_name'])) {
    $wp->ResetVersionName();
    $wp->action = 15;
}

if (isset($_REQUEST['new_copy_version'])) {
    $wp->AddVersion();
    $wp->action = 15;
}


if (isset($_REQUEST['new_save_fid'])) {
    $wp->SaveFid();
    $wp->action=14;
}

if (isset($_REQUEST['new_save_at_work_free'])

    or isset($_REQUEST['new_save_fid_at_work'])
    or isset($_REQUEST['new_save_fid_free'])
    or isset($_REQUEST['new_save_fid_at_work_and_print'])
    or isset($_REQUEST['new_save_fid_free_and_print'])
    or isset($_REQUEST['new_save_fid_copy_and_print'])
    or isset($_REQUEST['new_save_fid_repair'])
    or isset($_REQUEST['new_save_fid_repair_at_work'])
    or isset($_REQUEST['new_save_fid_scrap'])
    or isset($_REQUEST['new_save_fid_delete'])
) {
    $wp->SaveFid();
    $wp->action=4;
}


if (isset($_REQUEST['new_newfid'])) {
    $f = new FisFid(0);
    $f->AddCharge();
    if (isset($_REQUEST['count'])) {
        $wp->data['autologoff'] = 2*intval($_REQUEST['count']);
    }else{
        $wp->data['autologoff'] = 3;
    }
    $wp->action=4;
}




//Return

if (isset($_REQUEST['new_fmidfid'])) {
    $f= new FisFid(0);
    $f->AddCharge();

    $_GET['fmid']=$_SESSION['fmid'];
    $wp->action=25;
}

if (isset($_REQUEST['fm4_new_fid'])) {
    $_SESSION['sn']=$_REQUEST['fmsn'];
    $_SESSION['material']=$_REQUEST['fmartikelid'];
    unset($_SESSION['sn_old']);
    unset($_SESSION['material_old']);
    $_SESSION['fidstate']=2;
    $wp->action=50;
}

if (isset($_REQUEST['fm4_fid_material_type'])) {
    $_SESSION['sn']="";
    $_SESSION['material']=$_REQUEST['new_material_type'];
    $_SESSION['sn_old']=$_REQUEST['fmsn'];
    $_SESSION['material_old']=$_REQUEST['fmartikelid'];
    $_SESSION['fidstate']=3;
    $wp->action=51;
}

if (isset($_REQUEST['fm4_fid_vkz'])) {
    $_SESSION['sn']=$_REQUEST['fmsn'];
    $_SESSION['material']=$_REQUEST['fmartikelid'];
    unset($_SESSION['sn_old']);
    unset($_SESSION['material_old']);
    $_SESSION['fidstate']=4;
    $wp->action=53;
}


if (isset($_REQUEST['fm4_fid_repairwithnewsn'])) {
    $_SESSION['sn']="";
    $_SESSION['sn_old']=$_REQUEST['fmsn'];
    $_SESSION['material_old']=$_REQUEST['fmartikelid'];
    $_SESSION['material']=$_REQUEST['fmartikelid'];
    $_SESSION['fidstate']=5;
    $wp->action=54;
}


if (isset($_REQUEST['fm4_fid_exchange'])) {
    $_SESSION['sn']="";
    $_SESSION['sn_old']=$_REQUEST['fmsn'];
    $_SESSION['material_old']=$_REQUEST['fmartikelid'];
    $_SESSION['material']=$_REQUEST['fmartikelid'];
    $_SESSION['fidstate']=6;
    $wp->action=52;
}



if (isset($_REQUEST['print_typenschild'])){
    $f= new FisFid($_SESSION['fid']);
    $f->PrintSerialEtikett();
    $wp->action=39;
}

if (isset($_REQUEST['print_package'])){
    $f= new FisFid($_SESSION['fid']);
    $f->PrintPackageEtikett();
    $wp->action=39;
}

if (isset($_REQUEST['print_doku'])){
    $f= new FisFid($_SESSION['fid']);
    $f->PrintDoku();
    $wp->action=39;
}

if (isset($_REQUEST['print_mounting'])){
    $f= new FisFid($_SESSION['fid']);
    $f->PrintMountingEtikett();
    $wp->action=39;
}

if (isset($_REQUEST['print_material_desc'])) {
    $m = new FisMaterial($_SESSION['material']);
    $m->PrintMaterialDesc();
    $wp->action = 15;
}



if (isset($_REQUEST['print_warehouse']) && $_SESSION['weid']>0){
    $we= new FisWarehouse($_SESSION['weid']);
    $we->PrintWarehouseEitkett();
    $wp->action=37;
}

if (isset($_REQUEST['print_barcodegenerator'])){

    $wp->PrintBarcodegenerator();
    $wp->action=44;
}

if (isset($_REQUEST['print_glsgenerator'])) {

    $wp->PrintGLSgenerator();
    $wp->action = 74;
}


if (isset($_REQUEST['print_neutraletikett'])){

    $wp->PrintEikettgenerator();
    $wp->action=63;
}

if (isset($_REQUEST['save_fis9_import_FMID_data'])){
    $_SESSION['importfunction']="save_fis9_import_FMID_data";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}

if (isset($_REQUEST['SetFinalFMIDfromFis9'])){
    $_SESSION['importfunction']="SetFinalFMIDfromFis9";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}
if (isset($_REQUEST['setWpFolder'])){
    $_SESSION['importfunction']="setWpFolder";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}
if (isset($_REQUEST['save_import_sapproperties'])){
    $_SESSION['importfunction']="save_import_sapproperties";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}

if (isset($_REQUEST['fis_update'])){
    $_SESSION['importfunction']="fis_update";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}

if (isset($_REQUEST['save_fis9_import_material_versionen'])){
    $_SESSION['importfunction']="save_fis9_import_material_versionen";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}

if (isset($_REQUEST['CreateWorkplacePrinter'])){
    $_SESSION['importfunction']="CreateWorkplacePrinter";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}

if (isset($_REQUEST['ImportArtikelPicturesfromFis9'])){
    $_SESSION['importfunction']="ImportArtikelPicturesfromFis9";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}

if (isset($_REQUEST['SetEventfromFis9'])){
    $_SESSION['importfunction']="SetEventfromFis9";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}

if (isset($_REQUEST['UpdateVersionPrintSettings'])){
    $_SESSION['importfunction']="UpdateVersionPrintSettings";
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}

if (isset($_REQUEST['UpdatePCNAOrder'])){
    $_SESSION['importfunction']="ChangePCNANumber";
    $_SESSION['order_old']=$_REQUEST['order_old'];
    $_SESSION['order_new']=$_REQUEST['order_new'];
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}
if (isset($_REQUEST['UpdatePCNAGWLOrder'])){
    $_SESSION['importfunction']="ChangePCNAGWLNumber";
    $_SESSION['order_old']=$_REQUEST['order_old'];
    $_SESSION['order_new']=$_REQUEST['order_new'];
    $_SESSION['importitem']=0;
    $_SESSION['importcount']=0;
    header("Location: ./importing.php?sess=".$_SESSION['sess']);
}


$confirmorderlist = $wp->data['fehlermeldungen'];

if (isset($_REQUEST['Reset_Order_Confirm'])){
    $fm=new FisReturn(0);
    $fm->ResetOrderConfirm($_REQUEST['reset_order']);
}


if (count($confirmorderlist) > 0) {
    foreach ($confirmorderlist as $item => $order) {
        if (isset($_POST['fm7_confirm_' . $item]) && strlen($_POST['confirm_order_' . $item])>6) {
            $fmid=new FisReturn(0);
            $fmid->SetConfirmState($item,$order['gwl']);
        }
    }
}


