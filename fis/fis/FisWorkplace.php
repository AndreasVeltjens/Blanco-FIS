<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 06.10.18
 * Time: 13:50

 */
class FisWorkplace
{
    Public $bks = 6101;

    public $FIS_WORKPLACE=array();

    public $degreeoforder = array(
        1 => array(
            "desc" => "Ordnungsgrad",
            "plan" => array(
                1 => array("pruef" => "Ordnung & Sauberkeit", "name" => "SOLL-Zustand (6-S Methode)", "desc" => "Gesamtzustand",
                    "valuemin" => 0, "valuemax" => 100, "layout" => "range", "default" => 10,
                ),


                2 => array("pruef" => "Sichtprüfung", "name" => "IST-Zustand", "desc" => "Gesamtzustand",
                    "valuemin" => 0, "valuemax" => 100, "layout" => "range", "default" => 10,
                ),


            )
        )
    );

    public $function = array();

    public $wp = 0;
    public $wps = array();

    public $data = array();
    public $users = array();
    public $materials = array();
    public $materialconfig = array();
    public $kontierung = "";

    public $materialgroup = "";
    public $material = "";

    public $allowed_users = array();
    public $user = 0;
    public $action = 0;
    public $warning=false;
    public $changed=false;

    function __construct($i)
    {
        if ($i==""){$i=0;}
        $this->wp = $i;
        $wpc=new FisWpConfig();
        $this->FIS_WORKPLACE=$wpc->GetWpDefaults();
        $f=new FisWpFunction();
        $this->function=$f->GetFunctionsAsArray();
        if ($i > 0) {

            if ($_SESSION['active_user'] > 0) {
                $this->materialgroup = $_SESSION['materialgroup'];
                $this->kontierung = $_SESSION['kontierung'];
                $this->material = $_SESSION['material'];
                $this->action = $_GET['action'];
            } else {
                $this->action = 0;

            }
        }else{
            if ($_GET['action']>0){
                $this->action=$_GET['action'];
            }else{
                $this->action=0;
            }
        }
        $this->LoadWp();
        $this->LoadAllowedUsers();
        $this->LoadActiveUser();
        $this->LoadUsers();
    }

    function __toString(){
        if (is_null( $this->wp)) {
            return "0";
        }else{
            return strval($this->wp);
        }
    }


    //Layouts
    function ShowHtmlWorkplace()
    {

        switch ($this->action) {
            case 0: {
                if ($this->wp==0){
                    $h = $this->ShowHtmlWorkplaceLayout0();
                }else{
                    $h = $this->ShowHtmlWorkplaceLayout1();
                }
                break;
            }
            case 1: {
                $h = $this->ShowHtmlWorkplaceLayout2();
                break;
            }
            case 2: {
                $h = $this->ShowHtmlWorkplaceLayout2();
                break;
            }
            case 3: {
                $h = $this->ShowHtmlWorkplaceLayout3();
                break;
            }
            case 4: {
                $h = $this->ShowHtmlWorkplaceLayout4();
                break;
            }
            case 5: {
                $h = $this->ShowHtmlWorkplaceTimeline();
                break;
            }
            case 6: {
                $h = $this->ShowHtmlWorkplaceSettings();
                break;
            }
            case 7: {
                $h = $this->ShowHtmlEventSaveDegreeOfOrder();
                break;
            }
            case 8: {
                $h = $this->ShowHtmlWorkplaceParameterChange();
                break;
            }
            case 9: {
                $h = $this->ShowHtmlWorkplaceSaveWWW();
                break;
            }
            case 10: {
                if ($this->wp==0){
                    $equip=new FisEquipment($_SESSION['fid']);
                    $h = $equip->ShowHtmlEventSaveEvent();
                }else {
                    $h = $this->ShowHtmlWorkplaceLayout10();
                }
                break;
            }
            case 11: {
                $h = $this->ShowHtmlWorkplaceLayout11();
                break;
            }
            case 12: {
                $h = $this->ShowHtmlWorkplaceLayout12();
                break;
            }
            case 13: {
                $h = $this->ShowHtmlWorkplaceLayout13();
                break;
            }
            case 14: {
                $h = $this->ShowHtmlViewFidSettings();
                break;
            }
            case 15: {
                $h = $this->ShowHtmlViewMaterialSettings();
                break;
            }

            case 16: {
                $h = $this->ShowHtmlChangeMaterialSettings();
                break;
            }
            case 17: {
                $h = $this->ShowHtmlChangeVersionSettings();
                break;
            }
            case 18: {
                $h = $this->ShowHtmlChangeFidSettings();
                break;
            }
            case 19: {
                $h = $this->ShowHtmlChangeWpPrinterSettings();
                break;
            }
            case 20: {
                $h = $this->ShowHtmlSearchAll();
                break;
            }
            case 21: {
                $s=new FisSettings();
                $h = $s->ShowHtmlFisSetup();
                break;
            }

            case 22: {
                $h = $this->ShowHtmlReturnDevice();
                break;
            }
            case 23: {
                $h = $this->ShowHtmlReturnList($_GET['fmstate']);
                break;
            }
            case 24: {
                $h = $this->ShowHtmlReturnNew();
                break;
            }
            case 25: {
                $h = $this->ShowHtmlReturnEdit($_GET['fmstate']);
                break;
            }
            case 26: {
                $h = $this->ShowHtmlDatalogView();
                break;
            }
            case 27: {
                $h = $this->ShowHtmlCustomers();
                break;
            }
            case 28: {
                $h = $this->ShowHtmlEditCustomer();
                break;
            }
            case 29: {
                $h = $this->ShowHtmlEditReturnDevice();
                break;
            }
            case 30: {
                $h = $this->ShowHtmlEquipments();
                break;
            }
            case 31: {
                $h = $this->ShowHtmlEditEquipment();
                break;
            }

            case 34: {
                $h = $this->ShowHtmlMaterials();
                break;
            }
            case 35: {
                if ($_SESSION['active_user_right'] >= 5){
                    $h = $this->ShowHtmlUsers();
                }else{
                    $h=$this->ShowHtmlNoPermission();
                }
                break;
            }
            case 36: {
                if ($_SESSION['active_user_right'] >= 5){
                    $h = $this->ShowHtmlChangeUserSettings();
                }else{
                    $h=$this->ShowHtmlNoPermission();
                }
                break;
            }

            case 37: {
                if ($_SESSION['active_user_right'] >= 2){
                    $h = $this->ShowHtmlViewWarehouseInput();
                }else{
                    $h=$this->ShowHtmlNoPermission();
                }
                break;
            }
            case 38: {
                if ($_SESSION['active_user_right'] >= 2){
                    $h = $this->ShowHtmlChangeWarehouseInput();
                }else{
                    $h=$this->ShowHtmlNoPermission();
                }
                break;
            }
            case 39:{$h=$this->ShowHtmlPrintAgain(); break; }
            case 40:{
                $h=$this->ShowHtmlChangeWpPrinterSettings();
                break;
            }

            case 41:{
                $h=$this->ShowHtmlHardware();
                break;
            }

            case 42:{
                $h=$this->ShowHtmlFIDView();break;
            }

            case 43:{
                $h=$this->ShowHtmlFIDSetFree();break;
            }


            case 44:{
                $h=$this->ShowHtmlBarCodeGenerator();break;
            }


            case 45:{
                $_SESSION['fhm_search_eventstate']=0;
                $h=$this->ShowHtmlEventList();break;
            }

            case 46:{
                $h=$this->ShowHtmlReturnConfirm();break;
            }

            case 47:{
                $_SESSION['maintenancetype']=0;
                $h=$this->ShowHtmlMaintenanceDue();break;
            }
            case 48:{
                $_SESSION['maintenancetype']=0;
                $h=$this->ShowHtmlMaintenanceManager();break;
            }
            case 49:{
                $h=$this->ShowHtmlWpFunctions();break;
            }

            case 50:{
                $h=$this->ShowHtmlMaterialConfig();
                break;
            }
            case 51:{
                $h=$this->ShowHtmlMaterialConfig();
                break;
            }
            case 52:{$h=$this->ShowHtmlMaterialConfig();
                break;
            }
            case 53:{ $h=$this->ShowHtmlMaterialConfig(); break; }
            case 54:{ $h=$this->ShowHtmlMaterialConfig(); break; }
            case 55:{ $h=$this->ShowHtmlMaterialConfig(); break; }
            case 56:{ $h=$this->ShowHtmlMaterialConfig(); break; }

            case 57:{
                $s=new FisSettings();
                $h= $s->ShowHtmlUpdateOrderNumbers();
                break; }


            case 60:{   $h = $this->ShowHtmlWorkplaceLayout1(); break;}
            case 61:{   $h = $this->ShowHtmlWorkplaceLayout1(); break;}
            case 62:{   $h = $this->ShowHtmlWorkplaceLayout1(); break;}

            case 63:{   $h = $this->ShowHtmlEtikettGenerator(); break;}
            case 64:{   $h = $this->ShowHtmlMaintenanceManagerEdit(); break;}

            case 65:{
                $_SESSION['fhm_search_eventtype']=6;
                $_SESSION['fhm_search_eventstate']=1;

                $h=$this->ShowHtmlEventList();break;}

            case 66:{
                $_SESSION['maintenancetype']=1;
                $h=$this->ShowHtmlMaintenanceManager(); break;}
            case 67:{
                $_SESSION['maintenancetype']=1;
                $h = $this->ShowHtmlMaintenanceDue(); break;}

            case 68:
                {
                    $_SESSION['reporttype'] = 0;
                    $h = $this->ShowHtmlReportManager();
                    break;
                }
            case 70:
                {
                    $_SESSION['report'] = 1;
                    $h = $this->ShowHtmlReports();
                    break;
                }

            case 69:
                {
                    $_SESSION['reporttype'] = 0;
                    $h = $this->ShowHtmlReportManagerEdit();
                    break;
                }

            case 71:
                {
                    $h = $this->ShowHtmlFaufView();
                    break;
                }
            case 72:
                {
                    $h = $this->ShowHtmlFaufEdit();
                    break;
                }
            case 74:
                {
                    $h = $this->ShowHtmlGLSGenerator();
                    break;
                }
            case 99:{
                if (strlen($_SESSION['wpgroup'])==0){ $_SESSION['wpgroup']="Montage";}
                $h=$this->ShowHtmlWorkplaceGroup($_SESSION['wpgroup']); break;}
            case 100:{ $h=$this->ShowHtmlFISUpdate();       break;}

            default:{
                $h="unbekannte Funktion (".$this->action.")";
            }
        }


        return $h;
    }
    //Database
    function LoadMaterials()
    {

        include("./connection.php");
        $this->data = array();
        $s = "";
        if (strlen($this->kontierung) > 0) {
            $s = " AND artikeldaten.Kontierung='" . $this->kontierung . "'";
        }
        $sql = "SELECT * FROM artikeldaten WHERE artikeldaten.Gruppe like '" . $this->materialgroup . "' $s AND artikeldaten.aktiviert=1 
    ORDER BY artikeldaten.Bezeichnung ASC LIMIT 0,100";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->materials[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());
        } else {
            $this->data = array();
            AddSessionMessage("error", "Materialgruppe <code>  $this->materialgroup </code> hat keine Datensätze.", "Datenbankfehler");
        }

    }

    function LoadWps()
    {

        include("./connection.php");
        $this->wps = array();
        $sql = "SELECT * FROM fissettings WHERE fissettings.bks like '" . $this->bks . "' LIMIT 0,100";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->wps[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());
        } else {
            $this->wps = array();
            AddSessionMessage("error", "Arbeitsplatzgruppen <code> BKS $this->bks </code> hat keine Datensätze.", "Datenbankfehler");
        }

    }

    function LoadWp()
    {

        include("./connection.php");
        $this->data = array();
        $sql = "SELECT * FROM fissettings WHERE fissettings.wp = '" . $this->wp . "' AND fissettings.type='wp' LIMIT 0,1";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->data = json_decode(utf8_decode($row_rst['settings']), true);
            $_SESSION['autologout']=$this->data['autologout'];
            $_SESSION['autologoff']=$this->data['autologoff'];
            $_SESSION['autoinactive']=$this->data['autoinactive'];
            $_SESSION['wpgroup']=$this->data['group'];
            if ($this->data <> $this->FIS_WORKPLACE[strval($this->wp)]){
                $this->changed=true;
            }

        } else {
            $this->data = $this->FIS_WORKPLACE[$this->wp];
            $_SESSION['autologout']=$this->FIS_WORKPLACE[$this->wp]['autologout'];
            $_SESSION['autologoff']=$this->FIS_WORKPLACE[$this->wp]['autologoff'];
            $_SESSION['autoinactive']=$this->FIS_WORKPLACE[$this->wp]['autoinactive'];
            $_SESSION['wpgroup']=$this->FIS_WORKPLACE[$this->wp]['group'];
            // AddSessionMessage("warning", "Arbeitsplatz <code> WP $this->wp </code> hat keine Datensätze -> Default geladen.", "Datenbankfehler");
            $this->warning=true;
        }

    }

    function AddWp($s)
    {
        include("./connection.php");
        $sql = "SELECT * FROM fissettings WHERE fissettings.wp = '" . $this->wp . "' AND fissettings.type='wp' LIMIT 0,1";
        $rst = $mysqli->query($sql);

        if (mysqli_affected_rows($mysqli) == 0) {

            $updateSQL = sprintf("INSERT INTO  fissettings (wp, bks, settings,`type`) VALUES (%s, %s, %s, %s)",
                GetSQLValueString($this->wp, "int"),
                GetSQLValueString($this->bks, "int"),
                GetSQLValueString($s, "text"),
                GetSQLValueString("wp", "text")
                );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Automatisches Anlegen von Arbeitsplatzänderungen  <code> WP" . $this->wp . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");
            } else {
                AddSessionMessage("success", "Arbeitsplatz  <code> WP" . $this->wp . "</code> wurde neu angelegt.", "Create Settings");

            }
        }
    }

    function SaveWpSettings()
    {
        include("./connection.php");
        $txt = "";
        if (strlen($_REQUEST['settings']) > 1) {
            $s = ($_REQUEST['settings']);
           // $r = str_replace("," . chr(13), ",", $settings);
           // $s = str_replace("\\/", "/", $r);
           // $s = str_replace("//", "/", $s);
            $txt = "Workplace Settings";
        }
        if (isset($_REQUEST['save_wp_printersettings'])) {
            $r = $this->data;

            if (strlen($_REQUEST['ts']) > 0) {
                $r['printer']['typenschild'] = array("link" => $_REQUEST['ts'], "top" => $_REQUEST['tstop'], "left" => $_REQUEST['tsleft']);
            }
            if (strlen($_REQUEST['vp']) > 0) {
                $r['printer']['verpackung'] = array("link" => $_REQUEST['vp'], "top" => $_REQUEST['vptop'], "left" => $_REQUEST['vpleft']);
            }
            if (strlen($_REQUEST['m']) > 0) {
                $r['printer']['einbau'] = array("link" => $_REQUEST['m'], "top" => $_REQUEST['mtop'], "left" => $_REQUEST['mleft']);
            }
            if (strlen($_REQUEST['doku']) > 0) {
                $r['printer']['dokuset'] = array("link" => $_REQUEST['doku'], "top" => $_REQUEST['dokutop'], "left" => $_REQUEST['dokuleft']);
            }
            if (strlen($_REQUEST['fm']) > 0) {
                $r['printer']['fehermeldung'] = array("link" => $_REQUEST['fm'], "top" => $_REQUEST['fmtop'], "left" => $_REQUEST['fmleft']);
            }
            if (strlen($_REQUEST['we']) > 0) {
                $r['printer']['wareneingang'] = array("link" => $_REQUEST['we'], "top" => $_REQUEST['wetop'], "left" => $_REQUEST['weleft']);
            }
            if (strlen($_REQUEST['gls']) > 0) {
                $r['printer']['gls'] = array("link" => $_REQUEST['gls'], "top" => $_REQUEST['glstop'], "left" => $_REQUEST['glsleft']);
            }
            $s = json_encode($r);
            $txt = "Printer Settings";
        }

        //Load Default Settings
        if (isset($_REQUEST['save_wp_defaultsettings']) or $_REQUEST['save_allwp_defaultsettings']) {
            $s = json_encode($this->FIS_WORKPLACE[$this->wp]);
            $txt = "Default Workplace Settings";
        }
        //Load Default Printer Settings
        if (isset($_REQUEST['save_wp_defaultprintersettings'])) {
            $r = $this->data;
            $r['printer'] = $this->FIS_WORKPLACE[$this->wp]['printer'];
            $s = json_encode($r);
            $txt = "Default Printer Settings";
        }



        $this->AddWp($s); //check Wp in DB ?


        $this->SaveSettings($s);
    }



    function SaveSettings($s,$wp=0){
        if ($wp==0){$wp=$this->wp;}
        include("./connection.php");
        if (count(json_decode($s, true)) > 0) {

            $updateSQL = sprintf("
                UPDATE fissettings SET fissettings.settings=%s 
                WHERE fissettings.wp='$wp' AND fissettings.type= 'wp'",
                GetSQLValueString($s, "text"));
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("error", "Änderung von Einstellungen von  <code> WP" .$wp . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");
            } else {
                AddSessionMessage("success", "$txt von <code> WP" . $wp . "</code> wurde gespeichert.", "Update Settings");
            }
        } else {
            AddSessionMessage("error", "JSON-Datenformat der Einstellung <code> WP" . $wp . "</code> sind fehlerhaft.", "Abbruch");
        }
    }

    function LoadAllWpSettings(){

        if (count($this->FIS_WORKPLACE)>0){

            foreach ($this->FIS_WORKPLACE as $i =>$s){
                $this->wp=$i;
                $r=json_encode($s);
                $this->AddWp($r); //check Wp in DB ?
                $this->SaveSettings($r,$i);
            }
        }

    }

    function GetTools()
    {
        $tools = new FisTool();
        return $tools->GetTools();
    }

    function GetPlans()
    {
        $tools = new FisPlan();
        return $tools->GetPlans();
    }

    //Header
    function WpName()
    {
        $h = "alle Arbeitsplätze";
        if ($this->wp > 0) {
            $h = $this->data['name'];
        }
        return $h;
    }

    function GetLink()
    {
        $a = "";
        if ($_SESSION['action'] == 4) $a = "&action=4";
        $a .= "&materialgroup=" . $_SESSION['materialgroup'];

        if (strlen($_SESSION['material'] > 0)) $a .= "&material=" . $_SESSION['material'];

        if ($this->wp >10){
            $l = "./".$this->wp.".php?sess=" . $_SESSION['sess'] . "&wp=" . strval($_SESSION['wp']);
        }else {
            $l = "./index.php?sess=" . $_SESSION['sess'] . "&wp=" . strval($_SESSION['wp']);
        }

        return $l . $a;
    }

    function AutoLogoff()
    {

        if (!isset($this->data['autologoff'])) $this->data['autologoff'] = 60;
        return $this->data['autologoff'] . " ; URL=" . $this->GetLink();
    }

    function GetWpFunctions($layout="btn"){
        $h="";
        if (count($this->data['functions'])){
            if ($layout=="btn" && count($this->data['mainfunctions'])==0) {
                $h.="<div class='col-md-10 '><b>Funktionen</b><br>";
            } elseif ($layout=="btn" && count($this->data['mainfunctions'])>0) {
               // $h.="<div class='col-md-5 '><b>Funktionen</b><br>";



                $h.="
                <ul class=\"nav nav-tabs\">
                    <li role=\"presentation\" class=\"active\">
                        <a href=\"#func1_".$this->wp."\" role=\"tab\" data-toggle=\"tab\">Funktionen</a>
                    </li>
                    <li role=\"presentation\">
                        <a href=\"#func2_".$this->wp."\" role=\"tab\" data-toggle=\"tab\">erweitert</a>
                    </li>
                  
                </ul>
                <div class=\"tab-content\">
                    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"func1_".$this->wp."\">
                        <p>";



            }elseif ($layout == "list"){
                $h.="";
            }elseif ($layout == "listfunction"){
                $h .= "<br>";

            }elseif ($layout == "listerweitert"){
                $h.="";
            }
            foreach ($this->data['functions'] as $i){
                if ($_SESSION['active_user']>0 && $_SESSION['active_user_right']>=intval($this->function[$i]['userright'])){

                    if ($i>0) {
                        if ($layout == "btn") {
                            $h .= "<a href=\"" . $this->GetLink() . "&active_userid=" . $_SESSION['active_user'] . "&action=$i\" target=\"_self\" 
                        class=\"btn btn-custom btn-sm waves-effect waves-light\"> <i class=\"" . $this->function[$i]["icon"] . "\" > </i> " . $this->function[$i]["name"] . "</a><br> ";

                        }
                        if ($layout == "listfunction") {
                            $h .= "<a href=\"" . $this->GetLink() . "&active_userid=" . $_SESSION['active_user'] . "&action=$i\" target=\"_self\" >
                        <i class=\"" . $this->function[$i]["icon"] . "\" > </i> " . $this->function[$i]["name"] . "</a> ";

                        }
                        if ($layout == "list") {
                            $h .= "<a href=\"" . $this->GetLink() . "&active_userid=" . $_SESSION['active_user'] . "&action=$i\" target=\"_self\" >
                        <i class=\"" . $this->function[$i]["icon"] . "\" > </i> " . $this->function[$i]["name"] . "</a> ";

                        }
                    }else{
                       $h.="<hr>";
                    }


                }else{
                    if ($layout=="btn") {
                        $h .= "<a href=\"#\" target=\"_self\" 
                class=\"btn btn-inverse btn-sm waves-effect waves-light\" disabled> <i class=\"" . $this->function[$i]["icon"] . "\" > </i> " . $this->function[$i]["name"] . "</a><br> ";
                    }
                }
            }
            if ($layout=="btn")  {

            //  $h.="</div>";
                $h.="</p></div>";



            }
        }

        if (count($this->data['mainfunctions'])){
            if ($layout=="btn") {
                //$h.="<div class='col-md-5'><b>erweitert</b><br>";

                $h.="<div role=\"tabpanel\" class=\"tab-pane fade\" id=\"func2_".$this->wp."\"><p>";




            }
            elseif ($layout == "list"){
                $h.="<hr>";
            }
            elseif ($layout == "listerweitert"){
                $h.="<hr>";
            }
            foreach ($this->data['mainfunctions'] as $i){
                if ($_SESSION['active_user']>0 && $_SESSION['active_user_right']>=intval($this->function[$i]['userright']) ){
                    if ($i>0) {
                        if ($layout == "btn") {
                            $h .= "<a href=\"" . $this->GetLink() . "&active_userid=" . $_SESSION['active_user'] . "&action=$i\" target=\"_self\" 
                        class=\"btn btn-custom btn-sm waves-effect waves-light\"> <i class=\"" . $this->function[$i]["icon"] . "\" > </i> " . $this->function[$i]["name"] . "</a><br> ";

                        }
                        if ($layout == "list") {
                            $h .= "<a href=\"" . $this->GetLink() . "&active_userid=" . $_SESSION['active_user'] . "&action=$i\" target=\"_self\" >
                        <i class=\"" . $this->function[$i]["icon"] . "\" > </i> " . $this->function[$i]["name"] . "</a> ";

                        }
                        if ($layout == "listerweitert") {
                            $h .= "<a href=\"" . $this->GetLink() . "&active_userid=" . $_SESSION['active_user'] . "&action=$i\" target=\"_self\" >
                        <i class=\"" . $this->function[$i]["icon"] . "\" > </i> " . $this->function[$i]["name"] . "</a> ";

                        }
                    }else{
                        $h.="<hr>";
                    }

                }else{
                    if ($layout=="btn") {
                        $h .= "<a href=\"#\" target=\"_self\" 
                class=\"btn btn-inverse btn-sm waves-effect waves-light\" disabled> <i class=\"" . $this->function[$i]["icon"] . "\" > </i> " . $this->function[$i]["name"] . "</a><br> ";
                    }
                }
            }
            if ($layout=="btn") {
               // $h.="</div>";
                $h.="</p></div></div>";

            }
        }
        return $h;
    }


    function GetWpLinks(){
        $h="";
        if (count($this->data['links'])){
            $h.="<div class='col-lg-12'><hr><b>externe Links</b><br>";
            foreach ($this->data['links'] as $i=>$link){
                if ($_SESSION['active_user']>0){
                    $h .= "<a href=\"".$link["href"]."\" target=\"".$link["target"]."\" class=\"btn ".$link["btn"]." waves-effect waves-light\"> <i class=\"fa fa-link\"> </i> ".$link["alias"]."</a> ";
                }else{
                    $h .= "<a href=\"#\" target=\"".$link["target"]."\" class=\"btn ".$link["btn"]." waves-effect waves-light\" disabled> <i class=\"fa fa-link\"> </i> ".$link["alias"]."</a> ";

                }
            }
            $h.="</div>";
        }
        return $h;
    }
    //Descripten
    function EquipName()
    {
        $e = new FisEquipment($_GET['equip']);
        return $e->GetEqipmentName();
    }

    function GetFunctionName($i)
    {
        $h = "<i class=\"" . $this->function[$i]['icon'] . "\"> </i> " . $this->function[$i]['name'] . " ";
        return $h;
    }

    function GetTitle()
    {
        if ($this->action==0) {
            $h = "FIS " . $this->WpName() . " " . $this->EquipName();
        }else{
            $h= $this->function[$this->action]['name']. " - FIS12";
        }
        return $h;
    }

    function GetWPGroupName(){
        return $this->data['group'];
    }
    //Ordnungsgrad function=7
    function AddNewDegreeOfOrder()
    {
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $i => $item) {
                $equip = new FisEquipment($item['fid']);
                $t = array();
                $z = 0;
                if (count($this->degreeoforder[$item['degreeoforder']]['plan']) > 0) {
                    foreach ($this->degreeoforder[$item['degreeoforder']]['plan'] as $d => $x) {
                        $z = $_POST['range_' . $d]; //last value is current value
                        $t[$d] = $_POST['range_' . $d];
                    }
                }
                $t['result'] = $z;
                $equip->AddEvent(101, json_encode($t));
                AddSessionMessage("info", "Ordnungsgradbewertung " . $equip->data['bezeichnung'] . "<code>" . $t['result'] . "</code> gespeichert.", "Benachrichtigung");
            }
        }


    }

    function ShowHtmlEventSaveDegreeOfOrder()
    {
        $h = "<div class=\"row\">
                <div class=\"col-sm-12\" >
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Ordnungsrad anlegen (" . $this->degreeoforder[1]['desc'] . ")</b></h4>
                        <form id='form_edit' method='post' enctype='multipart/form-data' enctype='multipart/form-data'>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Merkmal</th>
                                <th>Was</th>
                                <th>Bewertung</th>
                            </tr>
                            </thead>
                            <tbody>";
        $_SESSION['range'] = "";
        if (count($this->degreeoforder[1]['plan']) > 0) {
            foreach ($this->degreeoforder[1]['plan'] as $d => $i) {
                $h .= "    <tr>
                                <td>" . $i['name'] . "</td>
                                <td>" . $i['desc'] . "</td>
                                <td width='80%'>
                                 <div class=\"form-group\">
                                            <label for=\"range_$d\" class=\"col-sm-2 control-label\"><b>Bewertung</b><span class=\"font-normal text-muted clearfix\">Test</span></label>
                                            <div class=\"col-sm-10\">
                                                <input type=\"text\" name=\"range_$d\" id=\"range_$d\" value='\"" . $i['default'] . "\"'>
                                            </div>
                                        </div>
                                
                                </td>
                            </tr>";
                if (count($i['values'])) {
                    $values = "values : " . json_encode($i['values']) . ",
                    ";
                } else {
                    $values = "";
                }

                $_SESSION['range'] .= " 
                $(\"#range_$d\").ionRangeSlider({
                         min: " . $i['valuemin'] . ",
                            max: " . $i['valuemax'] . ",
                            from: " . $i['valuemin'] . ",
                            $values
                            step: 1,
                                          });
                ";
            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>";

        $h .= "<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=0&materialgroup=&material=\" onclick='refresh_site()'> Abbrechen </a>";

        $h .= "<div class='pull-right'><button name=\"new_degreeoforder\" type=\"submit\" onclick='refresh_site()' class=\"btn btn-primary waves-effect waves-light\">
                                Neu anlegen
                            </button></div> 			
						 </form>
					</div>
                </div>
             </div>";

        return $h;
    }

    //Websession
    function ShowHtmlWebSession()
    {
        $h = "<div class=\"col-sm-6\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Websession</b></h4>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Wert</th>
                                <th>Info</th>
                            </tr>
                            </thead>
                            <tbody>";

        if (count($_SESSION) > 0) {
            foreach ($_SESSION as $d => $i) {
                if (count($i)) {
                    $t = print_r($i, true);
                } else {
                    $t = $i;
                }
                $h .= "    <tr>
                                <td>" . $d . "</td>
                                <td>" . $t . "</td>
                                <td></td>
                            </tr>";
            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>
                 </div>
              </div>";
        return $h;
    }

    //User
    function LoadAllowedUsers()
    {
        $u = new FisUser(0);
        $this->allowed_users = $u->LoadAllowedUser();

    }

    function LoadUsers()
    {
        $this->users = $_SESSION['logon_user'];
    }

    function LoadActiveUser()
    {
        if ($_SESSION['active_user']==0 or $_SESSION['active_user']==""){
            unset($_SESSION['active_user']);
            unset($_SESSION['active_user_right']);
            $this->action=0;
        }
        $this->user = $_SESSION['active_user'];
    }

    function SaveAllowedUser()
    {
        $u = new FisUser(0);
        if (count($_POST['my_multi_select1'])) {
            $u->DeleteWpAllUser($this->wp);
            foreach ($_POST['my_multi_select1'] as $item) {
                $u->AddWpUser($this->wp, $item);
                AddSessionMessage("success", "Benutzer " . ($item) . " für Arbeitsplatz $this->wp gespeichert", "Berechtigung");
            }
        }
    }


    function ShowUserIcon($u)
    {
        $user = new FisUser($u);
        $h = $user->GetUserIcon();

        if ($u == $this->user) {
            $h .= "<span class=\"label label-success\">" . $user->GetUsername() . "</span>";
            //$h .= "<div class=\"user-status away\"><i class=\"zmdi zmdi-dot-circle\"></i></div>";
        } else {
            $h .= "<span>" . $user->GetUsername() . "</span>";
        }

        if ($user->data['passwordactive']) {
            $user->LoadSessionDuration();
            $h .= "";
            $h .= "</a>
            <ul class=\"dropdown-menu\">
                <li><a href=\"#custom-width-modal-user" . $user->userid . "\" data-toggle=\"modal\" data-target=\"#custom-width-modal-user" . $user->userid . "\"
                    onclick=\"
                    $('#custom-width-modal-user" . $user->userid . "').on('shown.bs.modal', function() {
                      $('#password" . $user->userid . "').focus();
                    });
                    \" class=\"alert alert-success\"> <i class=\"ti-user m-r-5\"></i> Aktivieren </a></li>
                <li class='divider'></li>
                <li><a href=\"#custom-width-modal-user" . $user->userid . "\" data-toggle=\"modal\" data-target=\"#custom-width-modal-user" . $user->userid . "\"
                    onclick=\"
                    $('#custom-width-modal-user" . $user->userid . "').on('shown.bs.modal', function() {
                     $('#active_userid_action" . $user->userid . "').val(36);
                      $('#password" . $user->userid . "').focus();
                    });
                    \" >
                <i class=\"fa fa-gear\"></i> eigene Einstellungen </a></li>
                <li class='divider'></li>
                 <li><a><i class=\"fa fa-times-circle\"> </i> Session-Time: " . round(($user->lastactiontime / 60), 0) . " min </a></li>
                <li class='divider'></li>
                 <li><a href=\"./index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_GET['wp'] . "&logoff_userid=" . $u . "\" class='alert alert-danger'><i class=\"ti-power-off m-r-5\"></i> Logout </a></li>
               </ul>
        </li>";


        } else {
            $h .= "</a>
            <ul class=\"dropdown-menu\">
                <li><a href=\"./index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_GET['wp'] . "&active_userid=" . $u . "\" class='alert alert-success'><i class=\"ti-user m-r-5\"></i> Aktivieren </a></li>
                <li class='divider'></li>
                <li><a href=\"./index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_GET['wp'] . "&userid=" . $u . "&action=36\"><i class=\"ti-user m-r-5\"></i> eigene Einstellungen </a></li>
                <li class='divider'></li>
                 <li><i class=\"fa fa-times-circle\"> </i> Session-Time: " . $user->lastactiontime . " s </li>
                <li class='divider'></li>
                 <li><a href=\"./index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_GET['wp'] . "&logoff_userid=" . $u . "\" class='alert alert-danger'><i class=\"ti-power-off m-r-5\"></i> Logout </a></li>
               </ul>
        </li>";
        }


        return $h;
    }

    function ShowUsers()
    {
        $h = "";
        $i = 0;
        if (count($this->users) > 0) {
            foreach ($this->users as $i => $u) {
                $i++;
                if (isset($u['userid'])) {
                    $h .= $this->ShowUserIcon($u['userid']);
                }
            }
        }
        return $h;
    }

    function ShowAllowedUsers()
    {
        $h = "";
        $i = 0;
        if (count($this->allowed_users) > 0) {
            foreach ($this->allowed_users as $i => $u) {
                $i++;
                if ($i < 5) $h .= $this->ShowUserIcon($u['userid']);
            }
        }
        return $h;
    }

    function ShowHtmlAllowedUser()
    {
        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Benutzerliste</h4>
                <ul class=\"list-group m-b-0 user-list\">";
        if (count($this->allowed_users) > 0) {
            foreach ($this->allowed_users as $i) {
                $u = new FisUser($i['userid']);
                $h .= $u->ShowUserProperties();
            }
        }
        $h .= "</ul>";
        return $h;
    }

    function ShowHtmlLogonDialogs()
    {
        $h = "";
        if (count($this->allowed_users) > 0) {
            foreach ($this->allowed_users as $i) {
                $u = new FisUser($i['userid']);
                $h .= $u->ShowUserLogonDialog();
            }
        }
        return $h;
    }

    function ShowHtmlChangeAllowedUser()
    {
        $u = new FisUser(0);
        $h = "<div class=\"col-sm-6\">
                <div class=\"card-box\">
                <form id='form_edit' method='post' enctype='multipart/form-data'>
                    <h4 class=\"m-b-30 m-t-0 header-title\"><b>Erlaubte Mitarbeiter (" . $u->wp . ")</b></h4>";
        $h .= "<select multiple=\"multiple\" class=\"multi-select\" id=\"my_multi_select1\" name=\"my_multi_select1[]\" >";
        $h .= $u->GetHtmlSelectUsers();
        $h .= "</select><hr>
        <div>
        <button name=\"new_save_alloweduser\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site()'>Änderungen speichern</button>
        </div>
        
        </form></div></div>";

        $_SESSION['endscripts'].=" //advance multiselect start
        $('#my_multi_select1').multiSelect({
            selectableHeader: \"<input type='text' class='form-control search-input' autocomplete='off' placeholder='suchen...'>\",
            selectionHeader: \"<input type='text' class='form-control search-input' autocomplete='off' placeholder='suchen...'>\",
            afterInit: function (ms) {
                var that = this,
                    \$selectableSearch = that.\$selectableUl.prev(),
                    \$selectionSearch = that.\$selectionUl.prev(),
                    selectableSearchString = '#' + that.\$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.\$container.attr('id') + ' .ms-elem-selection.ms-selected';

                that.qs1 = \$selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which === 40) {
                            that.\$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = \$selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.\$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function () {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function () {
                this.qs1.cache();
                this.qs2.cache();
            }
        });";

        return $h;
    }

//Printer
    function LoadAllAvailablePrinters($documenttype = "typenschild")
    {
        $r = array();
        $this->LoadWps();
        if (Count($this->wps) > 0) {
            foreach ($this->wps as $wp => $item) {
                $i = json_decode(utf8_decode($item['settings']), true);
                $r[$wp] = array(
                    "link" => $i['printer'][$documenttype]['link'],
                    "name" => $item['wp'],
                    "alias" => $i['name'],
                    "layout" => $i['printer'][$documenttype]['layout'],
                );
            }
        }
        return $r;
    }

    function GetAvailablePrinterByType($documenttype = "typenschild", $type = "select", $value)
    {
        $r = $this->LoadAllAvailablePrinters($documenttype);
        $h = "";
        switch ($type) {
            case "select": {
                if (count($r) > 0) {
                    $found = 0;
                    foreach ($r as $i => $item) {
                        if (strlen($item['link']) > 10) {
                            if ($value == $item['link'] && $this->wp == $item['name'] && $found < 100) {
                                $s = "selected";
                                $found++;
                            } else {
                                $s = "";
                            }
                            $h .= "<option value=\"" . $item['link'] . "\" $s>". $item['link'] . " (". $item['name'] . "-".$item['alias'] . "-". $item['layout'] . ")</option>";
                        }
                    }
                }
                if ($value == "") {
                    $s = "selected";
                } else {
                    $s = "";
                }
                $h .= "<option value=\"\" $s>Nicht drucken</option>";

                break;
            }
            case "text": {
                $h = $value . " " . $r[$value]['link'];
                break;
            }
            case "top": {
                $h = $value . " " . $r[$value]['top'];
                break;
            }
            case "left": {
                $h = $value . " " . $r[$value]['left'];
                break;
            }
        }
        return $h;
    }

    function GetPrinterCorrection($type = "typenschild", $direction = "top")
    {
        $p = array();
        switch ($type) {
            case "verpackung": {
                $p = $this->data['printer']['verpackung'];
                break;
            }
            case "typenschild": {
                $p = $this->data['printer']['typenschild'];
                break;
            }
            case "dokuset": {
                $p = $this->data['printer']['dokuset'];

                break;
            }
            case "bg": {
                $p = $this->data['printer']['dokuset'];

                break;
            }
            case "einbau": {
                $p = $this->data['printer']['einbau'];

                break;
            }
            case "fehlermeldung": {
                $p = $this->data['printer']['fehlermeldung'];

                break;
            }
            case "wareneingang": {
                $p = $this->data['printer']['wareneingang'];

                break;
            }
            case "neutraletikett": {
                $p = $this->data['printer']['neutraletikett'];

                break;
            }
            case "gls": {
                $p = $this->data['printer']['gls'];

                break;
            }

        }
        return $p[$direction];
    }

    function GetPrinterDirectory($type = "typenschild")
    {
        $p = array();
        switch ($type) {
            case "verpackung": {
                $p = $this->data['printer']['verpackung'];
                break;
            }
            case "typenschild": {
                $p = $this->data['printer']['typenschild'];
                break;
            }
            case "dokuset": {
                $p = $this->data['printer']['dokuset'];
                break;
            }
            case "bg": {
                $p = $this->data['printer']['dokuset'];
                break;
            }
            case "einbau": {
                $p = $this->data['printer']['einbau'];

                break;
            }
            case "fehlermeldung": {
                $p = $this->data['printer']['fehlermeldung'];

                break;
            }
            case "wareneingang": {
                $p = $this->data['printer']['wareneingang'];

                break;
            }
            case "neutraletikett": {
                $p = $this->data['printer']['neutraletikett'];

                break;
            }
            case "gls": {
                $p = $this->data['printer']['gls'];
                break;
            }
        }
        return $p['link'];
    }

    function GetPrinterAddressFields()
    {
        return $this->data['address'];
    }


    //Statussite
    function GetAddFidStatus()
    {
        // Freigabe =2  nur Anlegen ohne Freigabe=1, deleted=0
        $s = 2;
        if (isset($this->data['defaultfidstatus'])) {
            $s = $this->data['defaultfidstatus'];
        }
        return $s;
    }

    function GetOrderInfo($item){
        if (isset($this->data['fehlermeldungen'][$item])){
            return $this->data['fehlermeldungen'][$item]['gwl'];
        }else{
            return false;
        }

    }

    function ShowHtmlPageTitel()
    {

        $h = "<div class=\"row\"><div class=\"col-sm-5\">
            <h4 class=\"page-title\">" . FisTranslate($this->GetFunctionName($this->action) . " " . $this->WpName() . " " . $this->EquipName()) . "</h4>
             </div> 
            <div class=\"col-sm-7 \">
            <ul class=\"nav nav-pills nav-pills-custom display-xs-none navbar-right\">";
        $h .= $this->GetPresentationLinks();
        $h .= "</ul></div></div>";
        return $h;
    }

    function GetPresentationLinks()
    {
        $h = "";
        if (count($this->function[$this->action]['links']) > 0) {
            foreach ($this->function[$this->action]['links'] as $i) {
                if ($this->action == $i) {
                    $active = "class=\"active\"";
                } else {
                    $active = "";
                }
                $h .= "<li role=\"presentation\" $active ><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $this->wp . "&action=" . $i . "\">" . $this->GetFunctionName($i) . "</a></li>";
            }
        }
        return $h;
    }

    function ShowHtmlFunctions($id, $equip, $favorits=true,$size="")
    {
        $h = "";
        if ($this->user > 0) {

            $x = "";
            $h = "<div class=\"btn-group\">";
            if ($_SESSION['active_user_right'] > 3) {


                $h .= "<div class=\"btn-group\">
            <button type=\"button\" class=\"btn btn-default $size dropdown-toggle waves-effect\" data-toggle=\"dropdown\" aria-expanded=\"false\"> <i class='zmdi zmdi-more' > </i> <span class=\"caret\"></span> </button>
            <ul class=\"dropdown-menu\">";
                if (count($this->data['fids'][$id]['functions']) > 0) {
                    foreach ($this->data['fids'][$id]['functions'] as $d => $i) {

                        if ($i == 1) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=1\">" . $this->GetFunctionName(1) . "</a></li>";
                        if ($i == 2) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=2\">" . $this->GetFunctionName(2) . "</a></li>";

                        if ($i == 3) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=3\">" . $this->GetFunctionName(3) . "</a></li>";
                        if ($i == 4) $h .= "   <li class='divider'></li>";
                        if ($i == 4) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=4\">" . $this->GetFunctionName(4) . "</a></li>";
                        if ($i == 5) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=5\">" . $this->GetFunctionName(5) . "</a></li>";
                        if ($i == 6) $h .= "   <li class='divider'></li>";
                        if ($i == 6) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=6\">" . $this->GetFunctionName(6) . "</a></li>";
                        if ($i == 7) $h .= "   <li class='divider'></li>";
                        if ($i == 7) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=7\">" . $this->GetFunctionName(7) . "</a></li>";
                        if ($i == 7) $h .= "   <li class='divider'></li>";

                        if ($i == 8) $h .= "  <li class='divider'></li>";
                        if ($i == 8) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=8\">" . $this->GetFunctionName(8) . "</a></li>";
                        if ($i == 9) $h .= "   <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=9\">" . $this->GetFunctionName(9) . "</a></li>";
                        if ($i == 12) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=12\">" . $this->GetFunctionName(12) . "</a></li>";
                        if ($i == 13) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=13\">" . $this->GetFunctionName(13) . "</a></li>";

                        if ($i == 31) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=31\">" . $this->GetFunctionName(31) . "</a></li>";

                        if ($i == 37) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=37\">" . $this->GetFunctionName(37) . "</a></li>";
                        if ($i == 22) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=22\">" . $this->GetFunctionName(22) . "</a></li>";
                        if ($i == 23) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=23\">" . $this->GetFunctionName(23) . "</a></li>";
                        if ($i == 40) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=40\">" . $this->GetFunctionName(40) . "</a></li>";

                        if ($i == 41) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=41\">" . $this->GetFunctionName(41) . "</a></li>";
                        if ($i == 63) $h .= "  <li><a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=63\">" . $this->GetFunctionName(63) . "</a></li>";

                    }
                }

                $h .= "</ul></div>";

            }
            if (count($this->data['fids'][$id]['favoriten']) > 0 && $favorits==true) {
                foreach ($this->data['fids'][$id]['favoriten'] as $d => $i) {
                    if ($i == 1) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=1\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Wartung</a> ";
                    if ($i == 2) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=2\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Störung</a> ";
                    if ($i == 4) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=4\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Aufträge</a> ";
                    if ($i == 39) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=39\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Nachdruck</a> ";

                    if ($i == 7) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=7\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Bewerten</a> ";
                    if ($i == 9) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=9\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Werkzeugwechsel</a> ";

                    if ($i == 12) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=12\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Prüfung</a> ";
                    if ($i == 13) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=13\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Kalibrierung</a> ";
                    if ($i == 31) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=31\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Equipment</a> ";
                    if ($i == 44) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=44\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Barcodes</a> ";
                    if ($i == 63) $h .= " <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=$this->wp&equip=$equip&action=63\" class=\"btn btn-custom btn-lg $size waves-effect waves-light\">
                                Etiketten</a> ";
                }
            }
            $h .= "</div>";
        } else {
            $h .= "<br>";
        }
        return $h;
    }

    function ShowHtmlTableHistory($id, $equip)
    {
        // show content on main screen
        $h = "";
        if (count($this->data['fids'][$id]['functions']) > 0) {
            $e = new FisEquipment($equip);

            foreach ($this->data['fids'][$id]['functions'] as $d => $i) {
                $e->pduration = $this->data['fids'][$id]['pduration'];
                if ($i == 7) $h .= $e->ShowHtmlEventList(7);

                if ($i == 1) {

                    $h .= $e->ShowHtmlEventList(1);
                }
                if ($i == 2) $h .= $e->ShowHtmlEventList(2);
                if ($i == 3) $h .= $e->ShowHtmlEventList(3);
                if ($i == 8) $h .= $e->ShowHtmlEventList(8);
                if ($i == 9) $h .= $e->ShowHtmlEventList(9);
                if ($i == 12) $h .= $e->ShowHtmlEventList(12);
                if ($i == 13) $h .= $e->ShowHtmlEventList(13);
                if ($i == 41) $h .= $e->ShowHtmlEventList(41); //Datalog Morris
            }
        }
        return $h;
    }

    //Save functions
    function AddNewEvent()
    {
        $e = new FisEquipment($_GET['equip']);
        if (count($this->data['fids'])>0){
            foreach ($this->data['fids'] as $i=>$item)
                if ($item['fid']==$_GET['equip']) {
                    $e->pplan = $item['pplan'];
                }
        }
        $e->AddNewEvent();
    }

    function SaveMaterial()
    {
        $e = new FisMaterial($_SESSION['material']);
        $e->SaveMaterial();
    }

    function SaveVersion()
    {
        $e = new FisVersion($_SESSION['version'], $_SESSION['material']);
        $e->SaveVersion();
    }

    function SaveVersionAsFree()
    {
        $m = new FisMaterial($_SESSION['material']);
        $m->SaveVersionAsActive($_SESSION['version']);
    }


    function ChangeVersionName()
    {
        $m = new FisMaterial($_SESSION['material']);
        $m->ChangeVersionName($_REQUEST['newversion']);
    }

    function DeleteVersionName()
    {
        $m = new FisMaterial($_SESSION['material']);
        $m->DeleteVersionName($_SESSION['version'], 1);
    }

    function ResetVersionName()
    {
        $m = new FisMaterial($_SESSION['material']);
        $m->DeleteVersionName($_SESSION['version'], 0);
    }

    function AddVersion()
    {
        $e = new FisVersion($_SESSION['version'], $_SESSION['material']);
        $e->Add();
    }

    function SaveFid()
    {
        $e = new FisFid($_SESSION['fid']);
        $e->SaveFid();
    }




    function ShowHtmlWorkplaceLayout0()
    {


        $h = "<div class='row'>";
        $h .= "<div class=\"col-lg-12 col-md-12\">
               <div >
               <div class='row'>";
        $wpgroup = "x";
        $x=0;
        foreach ($this->FIS_WORKPLACE as $i => $wp) {
            if ($i == 0 or $i > 10) {
                $wpx = new FisWorkplace($i);

                if ($wpgroup <> $wp['group'] && $wpgroup <> "x") {
                    $h .= "</tbody></table>";
                    $h .= "</div></div></div>";
                    $h .= "</div>";
                }

                if ($wpgroup <> $wp['group']) {
                    $x++;

                    if ($x == 5 or $x == 9) {
                        $h .= "</tbody></table>";
                        $h .= "</div>";
                        $h .= "<div class='row'\">";
                    }

                    $h .= "<div class=\"col-lg-3 table-responsive\">";
                    $h .= "<div class=\"portlet\">
                                    <div class=\"portlet-heading bg-inverse\">
                                        <h3 class=\"portlet-title\">
                                        <a href='".$this->GetLink()."&wp=".$i."&action=99&wpgroup=".$this->FIS_WORKPLACE[$i]['group']."'>
                                             " . FisTranslate($this->FIS_WORKPLACE[$i]['group']) . "</a>
                                        </h3>
                                        <div class=\"portlet-widgets\">
                                            <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                            <a data-toggle=\"collapse\" data-parent=\"#accordion$i\" href=\"#bg-primary$i\"><i
                                                    class=\"zmdi zmdi-minus\"></i></a>
                                           
                                        </div>
                                        <div class=\"clearfix\"></div>
                                    </div>
                                    <div id=\"bg-primary$i\" class=\"panel-collapse collapse in\">
                                        <div class=\"portlet-body table-responsive\">";

                    $h .= $wpx->GetWpFunctions();
                    $h .= $wpx->GetWpLinks();
                }
                $old_wp = "x";

                if (count($this->FIS_WORKPLACE[$i]['fids']) > 0) {
                    $h.="<div id='wp_layout_$i'></div>";
                    $h.="<script>
           
                    function load_wp_layout_$i(){   
                        $(\"#wp_layout_$i\").html('Es wird aktualisiert... <i class=\"fa fa-refresh fa-spin\"><i> ');            
                      
                        \$(\"#wp_layout_$i\").load(\"data.php?sess=".$_SESSION['sess']."&datatype=wp&wp=$i\" , function(responseTxt, statusTxt, xhr){
                        
                       });
                    
                     }
                </script>
                
                ";
                    $_SESSION['endscripts'].="
                    load_wp_layout_$i();
                    ";

                    $wpgroup = $wp['group'];
                } else {
                    if ($wpx->warning or $wpx->changed) {
                        $h .= "<hr><table class=\"table table-striped\"><tbody><tr><td>";
                        if ($wpx->warning) {
                            $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" ><span class='label label-danger'> <i class=\"fa fa-warning\"> </i></span> </a>";

                        }
                        if ($wpx->changed) {
                            $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" ><span class='label label-warning'> <i class=\"fa fa-warning\"> </i></span> </a>";
                        }
                        $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" alt='Arbeitsplatzinstellungen'> <i class=\"fa fa-gear\"> </i> Verwaltung anpassen </a>";
                        $h .= "</td><td>";
                        $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=21&&active_userid=" . $_SESSION['active_user'] . "\" alt='FIS-Grundeinstellungen'> <i class=\"fa fa-gears\"> </i> Grundeinstellungen </a>";
                        $h .= "</td></tr>";
                        $h .= "</tbody></table>";
                    }
                }
            }
        }

        $h .= "</div>";
        $h.="</div></div></div>";
        $h .= "</div>";
        $h .= "</div>";
        $h .= "<div class='row'>";



        $h .= "</div>";

    return $h;
    }

    function ShowHtmlWorkplaceGroup($group="groupname")
    {


        $h = "<div class='row'>";
        $h .= "<div class=\"col-lg-12 col-md-12\">
               <div >
               <div class='row'>";
        $wpgroup = "x";
        $x=0;
        foreach ($this->FIS_WORKPLACE as $i => $wp) {
            if ($i == 0 or $i > 10) {

                if ($group == $wp['group']) {
                    $wpx = new FisWorkplace($i);


                    $x++;

                    if ($x == 5 or $x == 9) {
                        //$h .= "</tbody></table>";
                       // $h .= "</div>";
                       // $h .= "<div class='row'\">";
                    }

                    $h .= "<div class=\"col-lg-3 table-responsive\">";
                    $h .= "<div class=\"portlet\">
                                    <div class=\"portlet-heading bg-inverse\">
                                        <h3 class=\"portlet-title\">
                                             " . FisTranslate($this->FIS_WORKPLACE[$i]['name']) . "
                                        </h3>
                                        <div class=\"portlet-widgets\">
                                            <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                            <a data-toggle=\"collapse\" data-parent=\"#accordion$i\" href=\"#bg-primary$i\"><i
                                                    class=\"zmdi zmdi-minus\"></i></a>
                                           
                                        </div>
                                        <div class=\"clearfix\"></div>
                                    </div>
                                    <div id=\"bg-primary$i\" class=\"panel-collapse collapse in\">
                                        <div class=\"portlet-body table-responsive\">";

                    $h .= $wpx->GetWpFunctions();
                    $h .= $wpx->GetWpLinks();



                if (count($this->FIS_WORKPLACE[$i]['fids']) > 0) {
                    $h .= "<div id='wp_layout_$i'></div>";
                    $h .= "<script>
           
                    function load_wp_layout_$i(){   
                        $(\"#wp_layout_$i\").html('Es wird aktualisiert... <i class=\"fa fa-refresh fa-spin\"><i> ');            
                      
                        \$(\"#wp_layout_$i\").load(\"data.php?sess=" . $_SESSION['sess'] . "&datatype=wp&wp=$i\" , function(responseTxt, statusTxt, xhr){
                        
                       });
                    
                     }
                </script>
                
                ";
                    $_SESSION['endscripts'] .= "
                    load_wp_layout_$i();
                    ";


                }
                }
                $h .= "</div></div></div></div>";
            }
        }


        $h .= "</div>";
        $h.="</div></div></div>";
        $h .= "</div>";
        $h .= "</div>";
        $h .= "<div class='row'>";

        $h .= "</div>";

        return $h;
    }

    function ShowHtmlWpFidGroup($i){
        if (count($this->FIS_WORKPLACE[$i]['fids']) > 0) {
            foreach ($this->FIS_WORKPLACE[$i]['fids'] as $f => $fid) {
                $fhm = new FisEquipment($fid['fid']);
                $wpx = new FisWorkplace($i);

                if ($this->FIS_WORKPLACE[$i]['name'] <> $old_wp) {

                    $h .= "<table class=\"table table-striped\"><thead><tr><th width='60%'>";
                    if ($wpx->warning) {
                        $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" ><span class='label label-danger'> <i class=\"fa fa-warning\"> </i></span> </a>";
                    }
                    if ($wpx->changed) {
                        $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" ><span class='label label-warning'> <i class=\"fa fa-warning\"> </i></span> </a>";
                    }
                    $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=19&&active_userid=" . $_SESSION['active_user'] . "\" > <i class=\"zmdi zmdi-print\"> </i> </a>";
                    $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" > <i class=\"fa fa-gear\"> </i> </a>";
                    $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=&&active_userid=" . $_SESSION['active_user'] . "\" > <i class=\"zmdi zmdi-tv\"> </i> " . FisTranslate($this->FIS_WORKPLACE[$i]['name']) . " </a>";


                    $h .= "</th>
                       
                        <th width='40%'><i class='fa fa-check'> </i> <i class='fa fa-warning'> </i> <i class='fa fa-list'>  </i> </th>
                       
                        </tr></thead><tbody>";
                }
                $fhm->pduration = $fid['pduration'];
                $h .= "<tr><td>";
                $txt = "";
                $txt .= "Funtionen " . $wpx->ShowHtmlFunctions($f, $fid['fid'], false, "btn-sm");
                $txt .= $fhm->ShowHtmlEventListMaintenance();
                if ($fid['calibration']) {
                    $txt .= $fhm->ShowHtmlEventListCalibration(false);

                }


                $txt .= $fhm->ShowHtmlEventListFail();
                $txt .= $fhm->ShowHtmlEventListParameter();
                $txt .= $fhm->ShowHtmlEventListProductProvement();


                $h .= asModal("fhm" . $fid['fid'] . "_" . $i, $txt, "", 60, "Status: " . $fhm->GetEqipmentName());

                $h .= "<a href=\"#custom-width-modalfhm" . $fid['fid'] . "_" . $i . "\" data-toggle=\"modal\" 
                        data-target=\"#custom-width-modalfhm" . $fid['fid'] . "_" . $i . "\">" . substr($fhm->GetEqipmentName(), 0, 40) . "</a>";
                if ($fid['calibration']) {
                    $h .= $fhm->ShowHtmlEventLatestCalibration();
                }
                $h .= "</td><td>";


                if ($fid['calibration']) {
                    $h .= " " . $fhm->ShowHtmlEventListCalibrationSmall($i);
                } else {
                    $h .= " " . $fhm->ShowHtmlEventListMaintenanceSmall($i);
                }
                $h .= " " . $fhm->ShowHtmlEventListFailSmall($i);
                $h .= " " . $fhm->ShowHtmlLatestDatalog();
                $h .= "</td></tr>";

                $old_wp = $this->FIS_WORKPLACE[$i]['name'];
            }


            $h .= "</tbody></table>";
            $h .= $_SESSION['gauge'];

        }
        return $h;
    }

    function ShowHtmlHardware()
    {


        $h = "<div class='row'>";
        $h .= "<div class=\"col-lg-12 col-md-12\">
               <div class=\"card-box table-responsive\">
               <div class='row'>";
        $wpgroup = "x";
        $x=0;
        foreach ($this->FIS_WORKPLACE as $i => $wp) {
            if ($i > 0 && $i <= 10) {
                $wpx = new FisWorkplace($i);

                if ($wpgroup <> $wp['group'] && $wpgroup <> "x") {
                    $h .= "</tbody></table>";
                    $h .= "</div></div></div>";
                    $h .= "</div>";
                }

                if ($wpgroup <> $wp['group']) {
                    $x++;

                    if ($x == 5 or $x == 9) {
                        $h .= "</tbody></table>";
                        $h .= "</div>";
                        $h .= "<div class='row'\">";
                    }

                    $h .= "<div class=\"col-lg-3 table-responsive\">";
                    $h .= "<div class=\"portlet\">
                                    <div class=\"portlet-heading bg-inverse\">
                                        <h3 class=\"portlet-title\">
                                             " . FisTranslate($this->FIS_WORKPLACE[$i]['group']) . "
                                        </h3>
                                        <div class=\"portlet-widgets\">
                                            <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                            <a data-toggle=\"collapse\" data-parent=\"#accordion$i\" href=\"#bg-primary$i\"><i
                                                    class=\"zmdi zmdi-minus\"></i></a>
                                           
                                        </div>
                                        <div class=\"clearfix\"></div>
                                    </div>
                                    <div id=\"bg-primary$i\" class=\"panel-collapse collapse in\">
                                        <div class=\"portlet-body\">";

                    $h .= $wpx->GetWpFunctions();
                    $h .= $wpx->GetWpLinks();
                }
                $old_wp = "x";

                if (count($this->FIS_WORKPLACE[$i]['fids']) > 0) {
                    foreach ($this->FIS_WORKPLACE[$i]['fids'] as $f => $fid) {
                        $fhm = new FisEquipment($fid['fid']);


                        if ($this->FIS_WORKPLACE[$i]['name'] <> $old_wp) {


                            $h.="<br>";
                            $h .= "<table class=\"table table-striped\"><thead>
                            <tr><th colspan='2' width='90%'>";
                            if ($wpx->warning) {
                                $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" ><span class='label label-danger'> <i class=\"fa fa-warning\"> </i></span> </a>";
                            }
                            if ($wpx->changed) {
                                $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" ><span class='label label-warning'> <i class=\"fa fa-warning\"> </i></span> </a>";
                            }
                            $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=19&&active_userid=" . $_SESSION['active_user'] . "\" > <i class=\"zmdi zmdi-print\"> </i> </a>";
                            $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" > <i class=\"fa fa-gear\"> </i> </a>";
                            $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=&&active_userid=" . $_SESSION['active_user'] . "\" > <i class=\"zmdi zmdi-tv\"> </i> " . FisTranslate($this->FIS_WORKPLACE[$i]['name']) . " </a>";


                            $h .= "</th>
                       
                        <th><i class='fa fa-check'> </i> </th>
                        <th><i class='fa fa-warning'> </i> </th>
                        <th><i class='fa fa-list'> </i> </th>
                       
                        </tr></thead><tbody>";
                        }
                        $fhm->pduration = $fid['pduration'];
                        $h .= "<tr><td>" . $wpx->ShowHtmlFunctions($f, $fid['fid'], false, "btn-sm");
                        $h .= "</td><td>" .substr($fhm->GetEqipmentName() ,0,50);
                        $h .= "</td><td>" . $fhm->ShowHtmlEventListMaintenanceSmall($i);
                        $h .= "</td><td>" . $fhm->ShowHtmlEventListFailSmall($i);
                        $h .= "</td><td>" . $fhm->ShowHtmlLatestDatalog();
                        $h .= "</td></tr>";

                        $old_wp = $this->FIS_WORKPLACE[$i]['name'];
                    }

                    $wpgroup = $wp['group'];

                } else {
                    $h .= "<hr><table class=\"table table-striped\"><tbody><tr><td>";
                    if ($wpx->warning) {
                        $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" ><span class='label label-danger'> <i class=\"fa fa-warning\"> </i></span> </a>";

                    }
                    if ($wpx->changed) {
                        $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" ><span class='label label-warning'> <i class=\"fa fa-warning\"> </i></span> </a>";
                    }
                    $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=6&&active_userid=" . $_SESSION['active_user'] . "\" alt='Arbeitsplatzinstellungen'> <i class=\"fa fa-gear\"> </i> Verwaltung anpassen </a>";
                    $h .= "</td><td>";
                    $h .= "<a href=\"./index.php?wp=" . $i . "&sess=" . $_SESSION['sess'] . "&action=21&&active_userid=" . $_SESSION['active_user'] . "\" alt='FIS-Grundeinstellungen'> <i class=\"fa fa-gears\"> </i> Grundeinstellungen </a>";
                    $h .= "</td></tr>";
                }
            }
        }
        $h .= "</tbody></table>";
        $h .= "</div>";
        $h.="</div></div></div>";
        $h .= "</div>";
        $h .= "</div>";
        $h .= "<div class='row'>";



        $h .= "</div>";

        return $h;
    }

    function ShowHtmlWorkplaceLayout1(){
        $h="";
        $z=0;
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                $e= new FisEquipment($i['fid']);
                $z++;
                $col=$i['col'];
                if ($col==""){$col=3;}

                if ($this->data['layout']==1) {

                    $h .= "<div class=\"col-lg-$col col-md-$col \">
                        
                       ";
                    $h .="<div class=\"portlet\">
                            <div class=\"portlet-heading bg-inverse\">
                                <h3 class=\"portlet-title\">
                                     <i class='" .$i['icon']."'> </i> ". substr($e->GetEqipmentName() ,0,20) ."
                                </h3>
                                <div class=\"portlet-widgets\">
                                    <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                    <a data-toggle=\"collapse\" data-parent=\"#accordion$d\" href=\"#bg-primary$d\"><i
                                            class=\"zmdi zmdi-minus\"></i></a>
                                   
                                </div>
                                <div class=\"clearfix\"></div>
                            </div>
                            <div id=\"bg-primary$d\" class=\"panel-collapse collapse in\">
                                <div class=\"portlet-body\">   
                                ";
                                $h.=$this->ShowHtmlFunctions($d,$i['fid']). $this->ShowHtmlTableHistory($d,$i['fid']);
                                $h.="</div>
                                    
                    </div> </div> </div><!-- end col -->";
                }   //max 4 columns

                if ($this->data['layout']==2) {
                    if ($z==1 or $z>=3 ){

                    $h .= "<div class=\"col-lg-$col col-md-$col\">";
                    $h .="<div class=\"portlet\">
                            <div class=\"portlet-heading bg-inverse\">
                                <h3 class=\"portlet-title\">
                                     <i class='" .$i['icon']."'> </i> ". substr($e->GetEqipmentName() ,0,20) ."
                                </h3>
                                <div class=\"portlet-widgets\">
                                    <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                    <a data-toggle=\"collapse\" data-parent=\"#accordion$d\" href=\"#bg-primary$d\"><i
                                            class=\"zmdi zmdi-minus\"></i></a>
                                   
                                </div>
                                <div class=\"clearfix\"></div>
                            </div>
                            <div id=\"bg-primary$d\" class=\"panel-collapse collapse in\">
                                <div class=\"portlet-body\">   
                                ";
                        $h.=$this->ShowHtmlFunctions($d,$i['fid']). $this->ShowHtmlTableHistory($d,$i['fid']);
                        $h.="</div></div> </div> </div><!-- end col -->";
                    }
                    if ($z==2){
                        $_SESSION['equip']=$i['fid'];
                        $_SESSION['diagramm']=array();
                        $_SESSION['equip']=$i['fid'];
                        $_SESSION['diagramm'][$i['dataset']][1]['udid']=$e->GetUDID();
                        $_SESSION['diagramm'][$i['dataset']][1]['interval']=$i['interval'];
                        $dlog=new FisDatalog($e->GetUDID(),1,$i['fid']);
                        $dlog->SetDataSet($i['dataset']);
                        $link="<a href=\"./index.php?sess=".$_SESSION['sess']."&wp=".$this->wp."&action=26&equip=".$i['fid']."\" 
                        target=\"_self\"  class=\"btn btn-inverse btn-sm waves-effect waves-light pull-right\" >weitere Details anzeigen</a>";

                        $h .= "<div class=\"col-lg-9 col-md-9 \">";
                        $h .="<div class=\"portlet\">
                            <div class=\"portlet-heading bg-inverse\">
                                <h3 class=\"portlet-title\">
                                     <i class='" .$i['icon']."'> </i> ". substr($e->GetEqipmentName() ,0,20) ."
                                </h3>
                                <div class=\"portlet-widgets\">
                                    <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                    <a data-toggle=\"collapse\" data-parent=\"#accordion$d\" href=\"#bg-primary$d\"><i
                                            class=\"zmdi zmdi-minus\"></i></a>
                                   
                                </div>
                                <div class=\"clearfix\"></div>
                            </div>
                            <div id=\"bg-primary$d\" class=\"panel-collapse table-responsive collapse\" aria-expanded='false' style='height: 600px;'>
                                <div class=\"portlet-body\">   
                                ";

                        $h.=$dlog->GetWidgetFlotChartwithoutCardbox(" <i class=\"" . $i['icon'] . "\"> </i> Temperaturverlauf ".$link,450,12);



                        $h.="<div class='col-lg-12 table-responsive'><p>T</p> </div> </div> 

                            </div> </div> </div><!-- end col -->";


                    }


                }   //1x columns 1x ( 1x flot diagramm + 3 columns )

                if ($this->data['layout']==3) {

                    $h .= "<div class=\"col-lg-3 col-md-6 \">";
                    $h .="<div class=\"portlet\">
                            <div class=\"portlet-heading bg-inverse\">
                                <h3 class=\"portlet-title\">
                                     <i class='" .$i['icon']."'> </i> ".  substr($e->GetEqipmentName() ,0,25)   ."
                                </h3>
                                <div class=\"portlet-widgets\">
                                    <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                    <a data-toggle=\"collapse\" data-parent=\"#accordion$d\" href=\"#bg-primary$d\"><i
                                            class=\"zmdi zmdi-minus\"></i></a>
                                   
                                </div>
                                <div class=\"clearfix\"></div>
                            </div>
                            <div id=\"bg-primary$d\" class=\"panel-collapse collapse in\">
                                <div class=\"portlet-body\">   
                                ";
                    $h.=$this->ShowHtmlFunctions($d,$i['fid']). $this->ShowHtmlTableHistory($d,$i['fid']);
                    $h.="</div>
                                    
                    </div> </div> </div><!-- end col -->";

                    if ($_SESSION['active_user']>0) {
                        $h .= " 
                        <div class=\"col-sm-9\">
                            <div class=\"card-box table-responsive\">
                                ";
                        if (isset($_REQUEST['new_newfid'])) {

                            $h .= "<div class=\"m-t-30 card-box\">
                            <div class=\"text-center\">
                                <h4 class=\"text-uppercase font-bold m-b-0\">Erfolgreich !!</h4>
                            </div>
                            <div class=\"panel-body text-center\">
                                <i class=\"zmdi zmdi-print text-success\" style=\"font-size: 48px;\"></i>
                                <p class=\"text-muted font-13 m-t-20\"> Druckvorlagen erstellt.</p>
                            </div>
                            
                             <div class=\"panel-body text-center\">";
                            $h.=SessionToasterMessage();
                            $h.="</div>
                        </div>";

                        } else {
                            if ($this->materialgroup == "") {
                                $h .= $this->ShowHtmlMaterialgroup();
                            } else {
                                if ($this->material == "") {
                                    $h .= $this->ShowHtmlMaterial();

                                } else {
                                    $h .= $this->ShowHtmlMaterialConfig();
                                }
                            }
                        }

                        $h .= "</div>";
                        $h .= "</div>";
                    }
                }  // with confirm

                if ($this->data['layout']==4) {

                    $h .= "<div class=\"col-lg-9 col-md-9\">
                            <div class=\"card-box table-responsive\">";
                    $wpgroup=$this->data['group'];

                    foreach ($this->FIS_WORKPLACE as $i=>$wp){
                        if ($wpgroup== $wp['group']){
                            $h.="<div class='col-lg-3 '>
                                 <div class=\"card-box \">
                                <h4>".$wp['name']."</h4>";
                                foreach ($this->FIS_WORKPLACE[$i]['fids'] as $f=>$fid){
                                    $fhm=new FisEquipment($fid['fid']);
                                    $h.=$fhm->GetEqipmentName()."<br>";
                                    $h.=$fhm->ShowHtmlEventListMaintenance()."<br>";
                                    $h.="<hr>";


                                }

                            $h.="</div></div>";

                        }

                    }
                    $h.="</div></div>";


                     $h.="<div class='col-lg-3'>
                          <div class=\"card-box table-responsive\">
                          <h4>Funktionen</h4>";

                                if ($_SESSION['active_user_right']>3){

                                $h.="<a href=\"index.php?sess=<".$_SESSION['sess']."&wp=<".$_SESSION['wp']."&action=21\" class='btn btn-custom' alt=\"FIS-Grundeinstellungen\" onclick=\"refresh_site()\">
                                    <i class=\"fa fa-gears\"> </i> Grundeinstellungen </a>
                                    <hr>
                                    <a href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=6\" class='btn btn-custom' alt=\"Arbeitsplatzeinstellungen\" onclick=\"refresh_site()\">
                                    <i class=\"fa fa-gear\"> </i> Arbeitsplatzeinstellungen </a>
                                     <hr>";

                              }

                    if ($_SESSION['active_user_right']>1) {

                        $h .= "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=23\"  class='btn btn-custom' alt=\"Fehlermeldungen\" onclick=\"refresh_site()\">
                                        <i class=\"fa fa-list\"> </i> alle Retouren </a>
                                        <hr>
                                <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=27\"  class='btn btn-custom' alt=\"Kundendaten\" onclick=\"refresh_site()\">
                                        <i class=\"fa fa-users\"> </i> Kundendaten anzeigen </a>
                                        <hr>
                                 <a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=22\"  class='btn btn-custom' alt=\"Rückholung\" onclick=\"refresh_site()\">
                                <i class=\"fa fa-refresh\"> </i> Rückholung anzeigen </a>";
                    }
                    $h.="</div></div>";
                    $h.="</div></div><!-- end col -->";
                }   //Dashboard

                if ($this->data['layout']==5) {

                    $h .= "<div class=\"col-lg-4 col-md-4 \">
                        
                       ";
                    $h .="<div class=\"portlet\">
                            <div class=\"portlet-heading bg-inverse\">
                                <h3 class=\"portlet-title\">
                                     <i class='" .$i['icon']."'> </i> ".  substr($e->GetEqipmentName() ,0,25)   ."
                                </h3>
                                <div class=\"portlet-widgets\">
                                    <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                    <a data-toggle=\"collapse\" data-parent=\"#accordion$d\" href=\"#bg-primary$d\"><i
                                            class=\"zmdi zmdi-minus\"></i></a>
                                   
                                </div>
                                <div class=\"clearfix\"></div>
                            </div>
                            <div id=\"bg-primary$d\" class=\"panel-collapse collapse in\">
                                <div class=\"portlet-body\">   
                                ";
                    $h.=$this->ShowHtmlFunctions($d,$i['fid']). $this->ShowHtmlTableHistory($d,$i['fid']);
                    $h.="</div>
                                    
                    </div> </div> </div><!-- end col -->";
                }   //max 3 rows

                if ($this->data['layout']==6) {


                    if ($_SESSION['active_user_right'] >= 2){
                        switch ($this->data['functions'][0]){
                            case 23 :{
                                $h.= $this->ShowHtmlReturnList(); break;
                            }
                            case 60 :{

                                $h.= $this->ShowHtmlReturnList(0); break;
                            }
                            case 61 :{

                                $h.= $this->ShowHtmlReturnList(4); break;
                            }
                            case 62 :{

                                $h.= $this->ShowHtmlReturnList(5); break;
                            }

                            case 37 :{
                                $h.= $this->ShowHtmlViewWarehouseInput(); break;
                            }
                        }

                    }else{
                        $h.=$this->ShowHtmlNoPermission();
                    }

                    $h.=$this->ShowHtmlFunctions($d,$i['fid']);

                }   //app

                if ($this->data['layout']==7) {

                    $h .= "<div class=\"col-lg-6 col-md-6 \">
                        
                       ";
                    $h .="<div class=\"portlet\">
                            <div class=\"portlet-heading bg-inverse\">
                                <h3 class=\"portlet-title\">
                                     <i class='" .$i['icon']."'> </i> ".  substr($e->GetEqipmentName() ,0,35)   ."
                                </h3>
                                <div class=\"portlet-widgets\">
                                    <a href=\"javascript:;\" data-toggle=\"reload\"><i class=\"zmdi zmdi-refresh\"></i></a>
                                    <a data-toggle=\"collapse\" data-parent=\"#accordion$d\" href=\"#bg-primary$d\"><i
                                            class=\"zmdi zmdi-minus\"></i></a>
                                   
                                </div>
                                <div class=\"clearfix\"></div>
                            </div>
                            <div id=\"bg-primary$d\" class=\"panel-collapse collapse in\">
                                <div class=\"portlet-body\">   
                                ";
                    $h.=$this->ShowHtmlFunctions($d,$i['fid']). $this->ShowHtmlTableHistory($d,$i['fid']);
                    $h.="</div>
                                    
                    </div> </div> </div><!-- end col -->";
                }   //max 2 rows
            }
            //$h.="<div class='pull-right'>Layout ".$this->data['layout']."</div>";
        }

        return $h;
    } //max 4 rows

    function ShowHtmlWorkplaceLayout2(){ //Maintenance + Fail dokumentation
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);
                    $equip->pplan=$this->data['fids'][$d]['pplan'];

                    if ($this->action==1) {

                        $h=$equip->ShowHtmlEventSaveMaintenance();
                    }else{
                        $h=$equip->ShowHtmlEventSaveFail();
                    }
                }
            }
        }
        return $h;
    } //Maintenance + Fail

    function ShowHtmlWorkplaceLayout3(){
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);
                    $h=$equip->ShowHtmlEventSaveImprovement();
                }
            }
        }
        return $h;
    } //Improvement

    function ShowHtmlWorkplaceLayout4(){
        $h="<div class=\"row\">
            <div class=\"col-sm-12\">
                <div class=\"card-box table-responsive\">
                                   ";
        if (isset($_REQUEST['new_newfid'])){
            $h.="<div class=\"m-t-30 card-box\">
                 <div class=\"text-center\">
                    <h4 class=\"text-uppercase font-bold m-b-0\">Erfolgreich !!</h4>
                </div>
                <div class=\"panel-body text-center\">
                    <i class=\"zmdi zmdi-print text-success\" style=\"font-size: 48px;\"></i>
					<p class=\"text-muted font-13 m-t-20\"> Druckvorlagen erstellt.</p>
                </div>

            </div>";
        }else {
            if ($this->materialgroup == "") {
                $h .= $this->ShowHtmlMaterialgroup();
            } else {
                if ($this->material == "") {
                    $h .= $this->ShowHtmlMaterial();
                } else {
                    $h .= $this->ShowHtmlMaterialConfig();
                }
            }
        }

        $h.="</div>";
       $h.="</div></div>";
        return $h;
    } //Confirm



    function ShowHtmlMaterialgroup(){
        $this->material="";
        $this->materialconfig=array();
        $this->materialgroup="";
        $h="<a class='btn btn-danger pull-right' onclick='refresh_site()' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=\"> Zur Startseite </a>";

        $h.= "<h4 class=\"header-title m-t-0 m-b-30\">Materialgruppe auswählen</h4>";
        if (count($this->data['materialgroups']) > 0) {
            $h.=" ";

            foreach ($this->data['materialgroups'] as $d => $i) {
                $h.="<div class=\"col-sm-4 \"> 
                        <div class='card-box table-responsive panel panel-custom panel-border'>
                            <a href=\"./" . $this->GetLink() . "&action=4&materialgroup=" . $i['name'] . "&kontierung=" . $i['kontierung'] . "&material=&version=&fmtype=\">
                            <div class=\"col-lg-4\">
                                <i class=\"fa fa-list fa-5x\"> </i>
                            </div>
                            <div class=\"col-lg-8\">
                                <div class=\"wid-u-info\">
                                    <h4 class=\"m-t-0 m-b-5\">".FisTranslate($i['alias'])."</h4>
                                    <p class=\"text-muted m-b-5 font-13\">".FisTranslate($i['name'])."</p>
                                   
                                </div>
                            </div>
                            </a>
                            </div>
                        </div>";
            }



        }else {
            $h .= "keine Materialgruppe für diesen Arbeitsplatz vorhanden.";
        }

        $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">";

        $h.="</div></div>";

        $h.="<hr></div>";
        if ($this->action<>20){
        $h.="<div class=\"card-box table-responsive\">";
            $h .= $this->ShowHtmlLatestFids(1, 5, "", $this->wp);
            $h .= "</div>";

            $h .= "<div class=\"card-box table-responsive\">";
            $h .= $this->ShowHtmlLatestFids(2, 5, "", $this->wp);
            $h .= "</div>";
        }
        return $h;
    }


    function ShowHtmlPrintAgain(){
        $this->material="";
        $this->materialconfig=array();
        $this->materialgroup="";

        $h = "<div class=\"card-box table-responsive\">";
        $h.="<a class='btn btn-danger pull-right' onclick='refresh_site()' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=\"> Zur Startseite </a>";

        $h .= $this->ShowHtmlLatestFids(1, 10, "", $this->wp);

        $h .= "</div><div class=\"card-box table-responsive\">";
        $h .= $this->ShowHtmlLatestFids(2, 10, "", $this->wp);
        $h .= "</div>";
        return $h;
    }

    function ShowHtmlLatestFids($status,$limit,$search="",$arbeitsplatz=""){
        if ($status==1) $s="in Arbeit";
        if ($status==2) $s="fertiggestellt";

        $fids=new FisFid(0);
        $fids->LoadLatestFids($status,$limit,$search,$arbeitsplatz);
        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Letzte Bauteile ($s) </h4>";
        $_SESSION['datatable']=$_SESSION['datatable']+1;
        if (count($fids->latest) > 0) {


            $_SESSION['script'].="
            $('#datatable".$_SESSION['datatable']."').dataTable();
            ";

            $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                            <th>Material</th>
                             <th>Material</th>
                            <th>Version</th>
                            <th>Status</th>
                            <th>Freigabe</th>
                            <th>SN</th>
                            <th>Montage</th>
                            <th>Typenschild</th>
                            <th>Verpackung</th>
                            <th>Dokuset</th>
                            <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>";
            $form="";
            foreach ($fids->latest as $d => $i) {
                $f=new FisFid($i['fid']);
                $form.="<form method=\"POST\" action=\"".$this->GetLink()."&fid=".$i['fid']."\" >";
                $h.="<tr>
                        <td>".$f->GetPicture("thumb-sm")."</td>";

                if (($f->data['status']==2) ) {
                    $h .= "<td><a href=\"" . $this->GetLink() . "&action=14&fid=" . $i['fid'] . "\" target='_self'>" . $f->GetMaterialName() . "</a></td>";
                }else {
                    $h .= "<td><a href=\"" . $this->GetLink() . "&action=43&fid=" . $i['fid'] . "\" target='_self'>" . $f->GetMaterialName() . "</a></td>";
                }
                $h.="<td>".$f->GetVersion()."</td>
                          <td>" . $f->GetStatus("text") . " ".$f->GetFidType()."</td>
                          <td>".$f->GetFree("text")." ".$f->GetSpecial("text")."</td>
                          
                         <td><h4>".$f->GetSerialnumber()."</h4></td>
                        <td>".$form.$f->ShowHtmLButtonMounting("","submit")."</form></td>                     
                        <td>".$form.$f->ShowHtmLButtonTS("","submit")."</form></td>
                        <td>".$form.$f->ShowHtmLButtonVP("","submit")."</form></td>
                        <td>".$form.$f->ShowHtmLButtonDoku("","submit")."</form></td>
                        <td>".$f->ShowHtmLButtonEdit($this->GetLink()).$f->ShowHtmLButtonView($this->GetLink())."</td>
                    </tr>";

            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Fertigungsmeldungen für diesen Arbeitsplatz vorhanden.";
        }
        return $h;
    }

    function ShowHtmlMaterial(){
        $this->LoadMaterials();
        $h = "<a class='btn btn-danger pull-right' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=4&materialgroup=&material=&fmid=&sn=&version=&fmstate=&fidstate=\"> Zur Auswahlliste </a>";

        $h.="<h4 class=\"header-title m-t-0 m-b-30\">".$this->materialgroup.": Material-Nr auswählen</h4>";

        if (count($this->materials) > 0) {

            $_SESSION['datatable']= $_SESSION['datatable']+1;
            $_SESSION['script_datatable'].="
            
            var table  = $(\"#datatable".$_SESSION['datatable']."\").DataTable({
                            order: [[ 1, \"desc\" ]],
                    
                            language: {
                                \"lengthMenu\": \"Zeige _MENU_ Datensätze pro Seite\",
                                \"zeroRecords\": \"Nichts gefunden - sorry\",
                                \"info\": \"Zeige Seite _PAGE_ von _PAGES_\",
                                \"infoEmpty\": \"Keine Treffer gefunden\",
                                \"infoFiltered\": \"(gefiltert von _MAX_ Datensätzen)\"
                            },
                            dom: \"<lengthMenu> Bfrtip\",
                            buttons: [{extend: \"copy\", className: \"btn-sm\"}, {extend: \"csv\", className: \"btn-sm\"}, {
                                extend: \"excel\",
                                className: \"btn-sm\"
                            }, {extend: \"pdf\", className: \"btn-sm\"}, {extend: \"print\", className: \"btn-sm\"}],
                            responsive: !0,
                            lengthMenu: [[20,10, 25, 50, -1], [20,10, 25, 50, \"All\"]]
                        });
                    
                        var handleDataTableButtons = function () {
                            \"use strict\";
                            0 !== $(\"#datatable".$_SESSION['datatable']."\").length && table
                        }, TableManageButtons = function () {
                            \"use strict\";
                            return {
                                init: function () {
                                    handleDataTableButtons()
                                }
                            }
                        }();

     
            ";
            $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                            <th>Material</th>
                             <th>Typenschild</th>
                              <th>Verpackung</th>
                               <th>Materialnummer</th>
                            <th>Bezeichnung</th>
                            <th>Version</th>
                            <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                                           ";
            foreach ($this->materials as $d => $i) {
                $m=new FisMaterial($i['artikelid']);
                $h.="<tr>
                        <td> <a href=\"" . $this->GetLink() . "&material=" . $i['artikelid'] . "&version=" . $i['version'] . "&fmid=&fidstate=&fmstate=\">" . $m->GetPicture() . "</a></td>
                         <td>".$m->ShowHtmlIcons2()."<br>".$m->GetPowerDesc()."</td>
                          <td>".$m->ShowHtmlIcons2("verpackung")."</td>
                          <td><a href=\"" . $this->GetLink() . "&material=" . $i['artikelid'] . "&version=" . $i['version'] . "&fmid=&fidstate=&fmstate=\"><h3>" . $m->GetMaterial() . "</h3></a></td>
                         <td><a href=\"" . $this->GetLink() . "&material=" . $i['artikelid'] . "&version=" . $i['version'] . "&fmid=&fidstate=&fmstate=\"><h3>" . $m->GetMaterialName() . "</h3></a></td>
                       
                        <td><code>".$m->GetVersion()."</code></td>
                        <td>".$m->ShowHtmlLinkAllVersion($this->GetLink() )."</td>
                    </tr>";
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Material-Nr. für diesen Arbeitsplatz vorhanden.";
            $h.="<div class=\"row\"> 
            <div class=\"col-sm-12\">
            <a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=0&materialgroup=&material=&fmid=&fmstate=&fidstate=\"> Zur Startseite </a>";
            $h.="</div></div>";
        }

        $h.="</div>";

        return $h;
    }

    function ShowHtmlSaveFid(){

        $material= new FisMaterial($_SESSION['material']);


        $h="<div class=\"col-sm-4\"> 
               <div class='card-box table-responsive panel panel-custom panel-border'>";

        if ($_SESSION['fmid']>0){
        $h.="<h4 class=\"header-title m-t-0 m-b-30\">Neue Meldung für FMID ".$_SESSION['fmid']." anlegen</h4>";

            if ($this->action == 50 or $_REQUEST['fmstate'] == 2) {
                $h .= "<b>neue Bauteilprüfung (Reparatur) für SN " . $_SESSION['sn'] . "</b><br>";
            }
            if ($this->action == 51 or $_REQUEST['fmstate'] == 3) {
                $h.="<b>Upgrade mit anderem Material und neuer SN</b><br>";
            }

            if ($this->action == 53 or $_REQUEST['fmstate'] == 4) {
                $h.="<b>Verschrottung für SN ".$_SESSION['sn']."</b><br>";
            }
            if ($this->action == 52 or $_REQUEST['fmstate'] == 5) {
                $h .= "<b>Austausch mit neuer SN</b><br>";
            }
        }else{
            $h.="<h4 class=\"header-title m-t-0 m-b-30\">Neues Gerät anlegen</h4>";
            $_SESSION['sn']="";
        }


        $h.="<div class='row'><div class='col-lg-3'>";
        $h.=$material->ShowHtmlPicture()."</div><div class='col-lg-9'>";
        $h.=$material->GetMaterialName()."<br>";
        $h.=$material->GetEAN13()."<br>";
        $h.=$material->GetMaterial()."<br>";
        $h.=$material->GetSelectedVersion()."<br>
        ".$material->GetPowerDesc()."<br>
         ".$material->GetMaxLoad()."<br>
        ".$material->ShowHtmlIcons2()."</div>
        </div>
        <hr>
        <div class='row'>
        <div >";

        $h.=$material->ShowHtmlInputFields()."

        </div></div>";
        if ($_SESSION['fmid']>0){
            $h .= "<table class='table table-striped'><tbody><tr><td>";
            $h .= "<h3>FMID: " . $_SESSION['fmid'] . "</h3>";
            $h.="</td></tr></tbody></table>";
        }else {
            $h .= "<table class='table table-striped'><tbody><tr><td>";
            $h.="<h3>nächste SN: " . $material->GetNextSN() . "</h3>";
            $h.="</td></tr></tbody></table>";
        }
        if ($_SESSION['fmid']>0){
            $h.="<div class='col-lg-12'></div>
        <div class=\"form-group pull-right\">
                <button name=\"new_fmidfid\" type=\"submit\" class=\"btn btn-lg btn-primary waves-effect waves-light\" onclick='refresh_site()' > Neue Meldung anlegen und<br>zurück zur Fehlermeldung </button>";
           $h.="</div></div>
          <a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=25&fmid=" . $_SESSION['fmid'] . "\" onclick='refresh_site()'> Abbrechene und zurück zur Fehlermeldung </a>
          
        </div></div>";

        }else{

       $h.="<div class='col-lg-12'></div>
        <div class=\"form-group pull-right\">";
       if ($material->IsCharge()) {
           $h .= "<button name=\"new_newfid\" type=\"submit\" class=\"btn btn-lg btn-primary waves-effect waves-light\" onclick='refresh_site()' > Neue Charge anlegen </button>";
       }else {
           $h .= "<button name=\"new_newfid\" type=\"submit\" class=\"btn btn-lg btn-primary waves-effect waves-light\" onclick='refresh_site()' > Neues Bauteil anlegen </button>";
       }

        $h.="</div></div>
</div>
           <div class='card-box table-responsive'>
          <a class='btn btn-lg btn-danger pull-right' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=4&material=\" onclick='refresh_site()'> Anderes Material </a>
          <a class='btn btn-lg btn-danger pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=4&materialgroup=&material=\" onclick='refresh_site()'> Zur Auswahlliste </a>
          </div>
          
          <div class='card-box  table-responsive'>
             <a class='btn btn-lg btn-inverse pull-left' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=39&materialgroup=&material=\" onclick='refresh_site()'> Nachdruck </a >
            <a href=\"#custom-width-modal4000\" data-toggle=\"modal\" data-target=\"#custom-width-modal4000\" class=\"btn btn-lg btn-custom waves-effect waves-light\" >Druckeinstellungen</a>
            <a class='btn btn-lg btn-inverse pull-left' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=44\" onclick='refresh_site()'> Barcodegenerator </a >
            <a class='btn btn-lg btn-inverse pull-left' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=63\" onclick='refresh_site()'> Etikett Generator </a >
            <a class='btn btn-lg btn-inverse pull-left' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=15\" onclick='refresh_site()'> Material anzeigen </a >
        
            <a class='btn btn-lg btn-danger pull-right' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=0&materialgroup=&material=\" onclick='refresh_site()'> Zur Startseite </a >
           </div>
          
          <div>
         
         </div>
        </div>";

        }

        return $h;
    }

    function ShowHtmlFIDAutoFill(){
        $material= new FisMaterial($_SESSION['material']);
        $h="<div class='card-box table-responsive'> 
               <h4>Datenübernahme aus vorheriger SN</h4>";
        if (count($material->GetLastFSNxValues()>0)){
            $lastValues=$material->GetLastFSNxValues();
            $r=explode(";",$material->data['merkmale']);
            $count=count($r);

            if ($count>1){
                $h .= "<table class='table table-striped'><tbody>";
                for ($i = 0; $i < ($count - 1); $i++) {

                    $index = $i + 1;
                    $h.="<tr><td><h4>".$r[$i]."</h4></td><td>";
                    $h.="<td><h4>".$lastValues['fsn'.$index]."</h4></td><td>";


                    $s=" $('#fsn$index').val('".$lastValues['fsn'.$index]."');";
                    $h.="
                    <script>
                    function Autofill_$i(){
                   
                        $s
                    }
                    
                    
                    </script>
                    
                    ";

                    $h .= "<a href='#' id='btn$i' class='btn btn-custom btn-lg' onclick='Javascript: Autofill_$i();'> übernehmen</a>
                            </td></tr>";


                }

                $h.="</tbody></table>";

            }


    }else{
            $h.="keine Daten verfügbar";
        }
        $h.="</div>";
        return $h;
    }

    function ShowHtmlMaterialConfig()
    {
        $material= new FisMaterial($_SESSION['material']);
        $material->LoadDefaults();
        $this->materialconfig=$material->conf;

        $_SESSION['focus'].=" 
        $(\"#fsn1\").focus();       
        ";
        $h="<form action=\"".$this->GetLink()."&action=4\" method='post'>";
        
        $h.="<div class=\"col-sm-12 \"> 
            <div class=\"col-sm-8 \"> 
               <div class='card-box table-responsive'> ";
        if ($_SESSION['fidstate'] == 4 or $_REQUEST['fmstate'] == 4) {
            //Verschrottung
            $h .= "Keine Auswahl der Konfigurationsmerkmale notwendig, da Bauteil verschrottet werden soll.";
        } else {
            if (count($this->materialconfig)) {
                $h .= $material->ShowControlFields();
            }else{
                $h.="Keine Konfiguration angelegt.";
            }
        }
        $h.="</div>";

        $h.=$this->ShowHtmlFIDAutoFill();
        $h.="</div>";
        $h.=$this->ShowHtmlSaveFid();

        $h.="</div></form>";
        $modal = asJSLoader("EditPrintSettings", "Druckeinstellungen werden geladen", "settings.php", "datatype=printsettings");
        $h .= asModal(4000, $modal, "", 95, "Druckeinstellungen für " . $material->GetMaterialDesc() . " bearbeiten");
        return $h;
    }



    function ShowHtmlViewFidSettings(){
        $fid=new FisFid($_SESSION['fid']);
        $d=new FisDocument("","fid",$_SESSION['fid']);
        $w=new FisWorkflow("","fid",$_SESSION['fid']);
        $h = "";
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data'>";
        $modal = $fid->EditFIDChange();
        $h .= asModal(4000, $modal, "", 80, "Fertigungsdaten ändern");
        $h .= "</form>";

        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data'>
               <div class='row' >
                <div class=\"col-lg-12\">
               <div class=\"col-lg-5\"> 
               <div class='card-box table-responsive'>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Fertigungsmeldung (".$_SESSION['fid'].")</b></h4>";


        $h.=$fid->ShowFidPropertyAsTable();
        $h.="</div></div>";

        $h.=" <div class=\"col-lg-5\"> <div>
               <div class='card-box table-responsive'>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Baugruppe aus der Stückliste</b></h4>";

        if ($fid->materialdata['stuli']>0) {
            if (count($fid->stuli) > 0) {
                foreach ($fid->stuli as $i => $item) {
                    $stuli = new FisFid($item['fid']);

                    $h .= $stuli->ShowFidPropertyAsTable();

                }
            } else {
                $m=new FisMaterial($fid->materialdata['stuli']);
                $h .= "Keine Stücklistenposition (" . $m->GetMaterial(). " - ".$m->GetMaterialName().") gefunden, obwohl redrograde Verwendung vorgesehen ist. Eventuell fehlt eine Dokumentation der Fertigungsmeldung";
            }
        }else{
            $h.="Für diese Fertigungsmeldung ist keine Stücklistenposition angelegt.";
        }
        $h.="</div></div>";

        $h.="<div class='card-box table-responsive'>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Verwendungsnachweis</b></h4>";

        $fid->LoadUsageData();
        if (count($fid->usage) > 0) {
            foreach ($fid->usage as $i => $item) {
                $usage = new FisFid($item['fid']);

                $h .= $usage->ShowFidPropertyAsTable();

            }
        } else {
            $h.="Für diese Fertigungsmeldung ist keine Verwendung dokumentiert.";
        }

        $h.="</div></div>";


        $h.=" <div class=\"col-lg-2\"> 
               <div class='card-box table-responsive'>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen</b></h4>";
        $h .= "<a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=4&materialgroup=&material=" . $fid->data['fartikelid'] . "&version=&fid=\"> Abbrechen </a>";

        if ($_SESSION['wp'] == 0) {
            $h .= "<a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=42&materialgroup=&material=" . $fid->data['fartikelid'] . "&version=&fid=\"> zur Liste </a>";
        }
        $h .= "<hr><br><a href=\"#custom-width-modal4000\" data-toggle=\"modal\" data-target=\"#custom-width-modal4000\" class=\"btn btn-custom waves-effect waves-light\" >Daten bearbeiten</a>";

        // todo fertigungsmeldung ändern

        $h.="<hr><a class='btn btn-custom ' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=15&materialgroup=&material=".$fid->data['fartikelid']."&version=&fid=\" onclick='refresh_site()'> Material anzeigen </a>";

        if ($fid->materialdata['stuli']>0) {
            if (count($fid->stuli) > 0) {
                foreach ($fid->stuli as $i => $item) {
                    $stuli = new FisFid($item['fid']);
                    $h .= "<hr><a class='btn btn-custom btn-sm' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=18&materialgroup=&material=&version=&fid=" . $stuli->data['fid'] . "\" onclick='refresh_site()'>Baugruppe ändern </a>";
                    $h.="<hr><a class='btn btn-custom btn-sm' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=15&materialgroup=&material=" . $stuli->data['fartikelid'] . "&version=&fid=\" onclick='refresh_site()'> Baugruppenmaterial <br> anzeigen </a>";

                }
            }
        }

        $h.="</div>";
        $h.="<div class=\"card-box\">
                 <h4 class=\"m-b-30 m-t-0 header-title\"><b>Drucken </b></h4>";
        $h.=$fid->ShowHtmLButtonMounting("","submit");
        $h.=$fid->ShowHtmLButtonTS("","submit");
        $h.=$fid->ShowHtmLButtonVP("","submit");
        $h.=$fid->ShowHtmLButtonDoku("","submit");
        $h.="<hr>Es werden nur die Druckvorlagen angezeigt, die für diese Version freigegeben wurde";
        $h.="</div>";
        $h.="<div class=\"card-box\">
                 <h4 class=\"m-b-30 m-t-0 header-title\"><b>Vorschau </b></h4>";
        $h.=$fid->ShowHtmLButtonMounting("print.mounting.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&fid=".$fid->fid."&asview=1","link","_blank");
        $h.=$fid->ShowHtmLButtonTS("print.sn.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&fid=".$fid->fid."&asview=1","link","_blank");
        $h.=$fid->ShowHtmLButtonVP("print.package.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&fid=".$fid->fid."&asview=1","link","_blank");
        $h.=$fid->ShowHtmLButtonDoku("print.doku.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&fid=".$fid->fid."&asview=1","link","_blank");
        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>History</b></h4>";
        $h.=$fid->GetFidHistory();
        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Workflow</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";

        $h.="</div>
          </div>
            </div></div></form>";
        return $h;
    } //Fid view

    function ShowHtmlChangeFidSettings(){
        $fid=new FisFid($_SESSION['fid']);
        $d=new FisDocument("","fid",$_SESSION['fid']);
        $w=new FisWorkflow("","fid",$_SESSION['fid']);

        $h="";
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data'>";
        $modal = $fid->EditFIDChange();
        $h .= asModal(4000, $modal, "", 80, "Fertigungsdaten ändern");
        $h.="</form>";

        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data'>
               <div class='row'>
                <div class=\"col-lg-12\" >
             
            <div class=\"col-lg-10\"> 
               <div class='card-box table-responsive'>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>gespeicherte Fertigungsmeldung (".$_SESSION['fid'].")</b></h4>";


        $h.=$fid->ShowFidPropertyAsTable();
        $h.="</div></div>";

        $h.="<div class=\"col-lg-2\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen</b></h4>";

        $h.="<a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=14&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";


        $h .= "<hr><br><a href=\"#custom-width-modal4000\" data-toggle=\"modal\" data-target=\"#custom-width-modal4000\" class=\"btn btn-custom waves-effect waves-light\" >Daten bearbeiten</a>";

        $h .= "<hr><br><a class='btn btn-custom' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=14&materialgroup=&material=" . $fid->material . "\" onclick='refresh_site()'> Material anzeigen </a>";

        $h .= "</div></div>";
        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Historie</b></h4>";
        $h .= $fid->GetFidHistory();
        $h .= "</div></div>";

        $h .= "</div></div>";
        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Verwendung</b></h4>";
        $h .= $fid->GetUsageFidHistory();
        $h .= "</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";


        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";

        $h.="</div></div>
            </div></div></form>";

        return $h;
    } //Fid edit

    function ShowHtmlFIDSetFree(){
        $fid=new FisFid($_SESSION['fid']);
        $d=new FisDocument("","fid",$_SESSION['fid']);
        $w=new FisWorkflow("","fid",$_SESSION['fid']);

        $h="";
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data'>";
        $modal = $fid->EditFIDChange();
        $h .= asModal(4000, $modal, "", 80, "Fertigungsdaten ändern");
        $h.="</form>";

        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data'>
               <div class='row'>
                <div class=\"col-lg-12\" >";

        $h.=" <div class=\"col-lg-9\"> 
               <div class='card-box table-responsive'>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Ändern (".$_SESSION['fid'].")</b></h4>";

        $h .= $fid->ShowFidPropertyAsTable();


        $h.="</div></div>";
        $h.="<div class=\"col-lg-3\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen</b></h4>";

        $h .= "<a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=0&materialgroup=&material=" . $fid->material . "&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";

        $h .= "<hr><br><a href=\"#custom-width-modal4000\" data-toggle=\"modal\" data-target=\"#custom-width-modal4000\" class=\"btn btn-custom waves-effect waves-light\" >Daten ändern</a>";
        $h .= "<hr><br><a class='btn btn-custom' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=14&materialgroup=&material=" . $fid->material . "\" onclick='refresh_site()'> Material anzeigen </a>";

        $h .= "</div></div>";
        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Historie</b></h4>";
        $h .= $fid->GetFidHistory();
        $h .= "</div></div>";

        $h .= "</div></div>";
        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Verwendung</b></h4>";
        $h .= $fid->GetUsageFidHistory();
        $h.="</div></div>";


        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";


        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";

        $h.="</div></div>
            </div></div></form>";

        return $h;
    } //Fid edit


    function ShowHtmlWorkplaceTimeline(){
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);

                    $h = "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=5&equip=" . $i['fid'] . "'>";
                    $h.=$equip->ShowHtmlTimeline();
                    $h.="<form>";
                }
            }
        }
        return $h;
    } //History and Timeline

    function ShowHtmlWorkplaceSettings(){
        $h="";
        if (($_GET['equip'])>0){
            if (count($this->data['fids']) > 0) {
                foreach ($this->data['fids'] as $d => $i) {
                    if ($i['fid']==$_GET['equip']) {
                        $equip=new FisEquipment($i['fid']);
                        $h=$equip->ShowHtmlSettings();
                    }
                }
            }
        }else{
            $h=$this->ShowHtmlWpProperties();
        }
        return $h;
    } //Settings

    function ShowHtmlWorkplaceParameterChange(){
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);
                    $h = $equip->ShowHtmlEventSaveParameterChange();
                }
            }
        }
        return $h;
    } //Parameter Change

    function ShowHtmlWorkplaceSaveWWW(){
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);
                    $equip->www=$this->data['fids'][$d]['www'];
                    $h = $equip->ShowHtmlEventSaveWWW();
                }
            }
        }
        return $h;
    } //WWW


    function ShowHtmlWorkplaceLayout10(){
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);
                    $h = $equip->ShowHtmlEventSaveEvent();
                }
            }
        }
        return $h;
    } //History edit

    function ShowHtmlWorkplaceLayout11(){
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);
                    $h = $equip->ShowHtmlEvent();
                }
            }
        }
        return $h;
    } //History view

    function ShowHtmlWorkplaceLayout12(){
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);
                    $equip->www=$this->data['fids'][$d]['www'];
                    $h = $equip->ShowHtmlEventSaveProve();
                }
            }
        }
        return $h;
    } //Prove


    function ShowHtmlWorkplaceLayout13(){
        $h="";
        if (count($this->data['fids']) > 0) {
            foreach ($this->data['fids'] as $d => $i) {
                if ($i['fid']==$_GET['equip']) {
                    $equip=new FisEquipment($i['fid']);
                    $equip->wwplan=$this->data['fids'][$d]['calplan'];
                    $h = $equip->ShowHtmlEventSaveCalibration();
                }
            }
        }
        return $h;
    } //calibration



    function ShowHtmlSearchAll(){


        $h="<div class=\"col-lg-12\">
                <div class=\"card-box\">
               
                    <h4 class=\"m-b-30 m-t-0 header-title\"><b>Trefferlisten für Suchbegriff \"".$_REQUEST['search']."\"</b></h4>";
        $h.="<div class=\"card-box table-responsive\">";
        $h.="<div>";
        $h.="<a class='btn btn-danger pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        $h.="</div>";

        $h.=$this->ShowHtmlLatestFids(1,20,$_REQUEST['search'],"alle");

        $h.="</div>
             <div class=\"card-box table-responsive\">";
        $h.="<div>";
        $h.="<a class='btn btn-danger pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        $h.="</div>";
        $h.=$this->ShowHtmlLatestFids(2,20,$_REQUEST['search'],"alle");

        $h.="</div>
             <div class=\"card-box table-responsive\">";
        $h.="<div>";
        $h.="<a class='btn btn-danger pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";


        $h.="<a class='btn btn-custom pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=34&materialgroup=&material=&version=&fid=&kontierung=0&material_search_txt=".$_REQUEST['search']."\" onclick='refresh_site()'> Mehr.... </a>";

        $h.="</div>";
        $h.=asJSLoader("searchall_material","Es werden Materialdaten geladen...","searchall.php","datatype=material&search=".urlencode($_REQUEST['search']));


        $h.="</div>
             <div class=\"card-box table-responsive\">";
        $h.="<div>";
        $h.="<a class='btn btn-danger pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        $h.="</div>";
        $h.=asJSLoader("searchall_version","Es werden Versionen geladen...","searchall.php","datatype=version&search=".urlencode($_REQUEST['search']));

        $h.="</div>
             <div class=\"card-box table-responsive\">";
        $h.="<div>";
        $h.="<a class='btn btn-danger pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        $h.="<a class='btn btn-custom pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=23&materialgroup=&material=&version=&fid=&kontierung=0&fmstate=0&fm_search_txt=".$_REQUEST['search']."\" onclick='refresh_site()'> Mehr.... </a>";

        $h.="</div>";
        $h.=asJSLoader("searchall_returns","Es werden Fehlermeldungen geladen...","searchall.php","datatype=returns&search=".urlencode($_REQUEST['search']));


        $h.="</div>
             <div class=\"card-box table-responsive\">";
        $h.="<div>";
        $h.="<a class='btn btn-danger pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        $h.="<a class='btn btn-custom pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=22&materialgroup=&material=&version=&fid=&kontierung=0&re_search_txt=".$_REQUEST['search']."\" onclick='refresh_site()'> Mehr.... </a>";

        $h.="</div>";
        $h.=asJSLoader("searchall_returndevices","Es werden Rückholungen geladen...","searchall.php","datatype=returndevices&search=".urlencode($_REQUEST['search']));


        $h.="</div>
             <div class=\"card-box table-responsive\">";
        $h.="<div>";
        $h.="<a class='btn btn-danger pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        $h.="<a class='btn btn-custom pull-right' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=30&materialgroup=&material=&version=&fid=&kontierung=0&fhm_search_txt=".$_REQUEST['search']."\" onclick='refresh_site()'> Mehr.... </a>";

        $h.="</div>";
        $h.=asJSLoader("searchall_equipment","Es werden Equipments geladen...","searchall.php","datatype=equipment&search=".urlencode($_REQUEST['search']));
        $h.="</div>";


        $h.="</div>
        </div></div>";




        return $h;
    }   //Search


    function ShowHtmlReturnDevice(){
        $rueck=new FisReturnDevice(0);
        $rueck->SetCustomer($_REQUEST['re_search_idk']);
        $rueck->SetLimit($_REQUEST['re_search_hits']);

        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=22'>";
        $link=" <button name=\"re_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neu anlegen</button>";
        $h.=asModal(3000,$rueck->EditPropertyAsTable(),$link,60);
        $h.="</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=22'>";


        $h.="<div class=\"col-lg-9\">
                <div class=\"card-box\">";
        $h.=$rueck->ShowHtmlAllReturnDevices(1,10,$_REQUEST['re_search_txt'],0);
        $h.="<hr><br><br></div></div>";


        $h.="<div class=\"col-lg-3\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
           


        $h.=asTextField("Volltextsuche","re_search_txt",$_REQUEST['re_search_txt'],12,"");
        $_SESSION['focus'].="$(\"#re_search_txt\").focus();";

        $h .= asSelect2Box("Kunde", "re_search_idk", GetCustomerName("select2", $_REQUEST['re_search_idk']),12,"");

        $h.=asTextField("Max. Treffer","re_search_hits",$rueck->GetLimit(),5,"");

        $h.="<br><button name=\"re_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr>
            <button name=\"re_reset\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br>
            <hr>
            <br><a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Neu</a>
            <hr>
            <a href=\"" . $this->GetLink() . "&action=23\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Fehlermeldungen</a>
            <a href=\"" . $this->GetLink() . "&action=27\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Kundendaten</a>
          
            
      
            
            </div></div>";


        $h.="</form>";
        return $h;
    }   //22

    function ShowHtmlEditReturnDevice(){
        $c=new FisReturnDevice($_GET['rueckid']);
        $w=new FisWorkflow("","rueckid",$_GET['rueckid']);
        $h="";


        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=22'>";
        $h.="<div class='row'>";
        $h.=" <div class=\"col-lg-10\">
                    <div class=\"card-box\">
                    ";


        $h.=$c->EditPropertyAsTable();
        $h.=" <hr><br><br> </div></div>";


        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div class='text-center'> ";

        $h.="<br><button name=\"re_save\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Speichern</button>
            <hr>
             <a href=\"".$this->GetLink()."&action=22\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Abbrechen</a>
           <br>
            <hr>
            <br>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Kopieren</a>
           <hr>
            <br>
            
            <a class='btn btn-custom btn-sm' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=28&rueckid=&idk=".$c->data['idk']."&materialgroup=&material=&version=&fid=\" onclick = 'refresh_site()' > Kunde anzeigen </a >
  
           
 </div></div></div></div>
            ";

        $h.="<div class='row'>
                <div class=\"col-lg-12\">
                <div class=\"card-box\">";

                $fm=new FisReturn();
                $fm->SetLimit(10);
                $fm->SetSearchString($c->data['sendungsid']);
                $h.= $fm->ShowHtmlAllReturn(0,100,$c->data['sendungsid'],1);
                $h.="</div></div>";


        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";

        $h.="</div></form>";

        $c->rueckid=0;
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=22'>";
        $link=" <button name=\"re_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neu anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(),$link,60);
        $h.="</form>";


        return $h;
    }   //26



    function ShowHtmlEquipments(){
        $c=new FisEquipment();
        $c->id_fhm_gruppe=$_SESSION['fhm_search_group'];
        $c->lokz=$_SESSION['fhm_search_lokz'];

        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=30'>";
        $link=" <button name=\"fhm_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neu anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(),$link,60);
        $h.="</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=30'>
                <div class=\"col-lg-10\">
                    <div class=\"card-box\">";


        $h.=$c->ShowHtmlAllAsTable(1,$_SESSION['fhm_search_hits'],$_SESSION['fhm_search_txt'],0);
        $h.=" <hr><br><br> </div></div>";


        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
           
            <div class='text-center'> ";

        $h.=asTextField("Volltext","fhm_search_txt",$_SESSION['fhm_search_txt'],12,"");
        $_SESSION['focus'].="$(\"#fhm_search_txt\").focus();";
        $h .= asSelect2Box("Equipmentgruppe", "fhm_search_group", GetEquipmentGroupName("select2", $_SESSION['fhm_search_group']),12,"");
        $h .= asSelectBox("auch gelöschte anzeigen", "fhm_search_lokz", GetYesNoList("select", $_SESSION['fhm_search_lokz']),12,"");

        $h.=asTextField("Max. Treffer","fhm_search_hits",$c->GetLimit(),12);

        $h.="<br><button name=\"fhm_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr>
            <button name=\"fhm_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br>
            <hr>
            <br>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Neu</a>
            
            </div>
            
            </div></div></form>";
        return $h;
    }   //27
    function ShowHtmlEditEquipment(){
        $c=new FisEquipment($_GET['equip']);
        $w= new FisWorkflow("","equip",$_GET['equip']);
        $d= new FisDocument("","equip",$_GET['equip']);


        $h = "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=31&equip=" . $c->equip . "'>";

        $h.="<div class='row'>";
        $h.=" <div class=\"col-lg-5\">";
        $h.=" <div class=\"col-lg-12\">";
        $h.="<div class=\"card-box\">";

        $h.=$c->EditPropertyAsTable();
        $h.=" <hr><br><br>";
        $h.=$d->ShowHtmlInputField();
        $h.="</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Temperaturen Datenloggereinträge</b></h4>";
        $h.=$c->ShowHtmlEventListLogs("temp");
        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Netzwerk Datenloggereinträge</b></h4>";
        $h.=$c->ShowHtmlEventListLogs("net");
        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>System Datenloggereinträge</b></h4>";
        $h.=$c->ShowHtmlEventListLogs("system");
        $h.="</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";


        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div></div>";

        $h.=" <div class=\"col-lg-5\">
                    <div class=\"card-box\">";

        $h.=$c->ShowHtmlTimeline();

        $h.="</div></div>";

        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
           
            <div class='text-center'> ";

        $h.="<br><button name=\"fhm_save\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Speichern</button>
            <hr>
            <a href=\"".$this->GetLink()."&action=30\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Abbrechen</a>
            <br>
            <hr>
            <br>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Kopieren</a>
            <hr>
            <br>
            <a href=\"#custom-width-modal3001\" data-toggle=\"modal\" data-target=\"#custom-width-modal3001\" class=\"btn btn-custom waves-effect waves-light\" >Neues Ereignis<br>anlegen</a>
            
            </div>
            
            </div></div></div></div>";

        $h.="<div class='row'>";

        $link="<button name=\"fhm_event_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Ereignis anlegen</button>";
        $h.=asModal(3001,$c->EditEventAsTable(),$link,60);

        $h.="</div></form>";
        $c->equip=0;
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=30'>";
        $link=" <button name=\"fhm_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neues Eqipment anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(),$link,60);
        $h.="</form>";
        return $h;
    }   //28


    function ShowHtmlEventList(){

        $c=new FisEvent(0);
        $c->group=$_SESSION['fhm_search_group'];
        $c->lokz=$_SESSION['fhm_search_lokz'];
        $c->eventtype=$_SESSION['fhm_search_eventtype'];
        $c->eventstate=$_SESSION['fhm_search_eventstate'];

        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=45'>";

        $h.="</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=" . $this->action . "'>
                <div class=\"col-lg-10\">
                    <div class=\"card-box\">";


        $h.=$c->ShowHtmlAllAsTable(1,$_SESSION['fhm_search_hits'],$_SESSION['fhm_search_txt'],0);
        $h.=" <hr><br><br> </div></div>";


        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
           
            <div class='text-center'> ";

        $h.=asTextField("Volltext","fhm_search_txt",$_SESSION['fhm_search_txt'],12,"");
        $_SESSION['focus'].="$(\"#fhm_search_txt\").focus();";
        $h .= asSelect2Box("Equipmentgruppe", "fhm_search_group", GetEquipmentGroupName("select2", $_SESSION['fhm_search_group']),12,"");
        $h .= asSelect2Box("Ereignisart", "fhm_search_eventtype", GetEventTypeName("select2", $_SESSION['fhm_search_eventtype']),12,"");
        $h .= asSelectBox("Status", "fhm_search_eventstate", GetEventStates("select", $_SESSION['fhm_search_eventstate']),12,"");

        $h .= asSelectBox("auch gelöschte anzeigen", "fhm_search_lokz", GetYesNoList("select", $_SESSION['fhm_search_lokz']),12,"");

        $h.=asTextField("Max. Treffer","fhm_search_hits",$c->limit,12);

        $h.="<br><button name=\"fhme_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr>
            <button name=\"fhme_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br>
            <hr>
            <br>
           
            </div>
            
            </div></div></form>";
        return $h;
    }   //45
    function ShowHtmlReturnList($fmstate=0){
        $fms=new FisReturn();
        $fms->fmstate=$fmstate;
        $fms->customergroup = $_REQUEST['suchbegriff_search_txt'];


        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=23&fmstate=$fmstate'>
        <div class=\"col-lg-10\">
               <div class=\"card-box\">
                    <h4 class=\"m-b-30 m-t-0 header-title\"><b><i class='".GetFMState("icon",$fmstate)."' > </i> Fehlermeldungen anzeigen - ".GetFMState("text",$fmstate)."</b></h4>";

        $h.=$fms->ShowHtmlAllReturn($fmstate,$_REQUEST['fm_search_hits'],$_REQUEST['fm_search_txt'],0);
        $h.=" <hr><br><br>            
            </div></div>";


        $h.="<div class=\"col-lg-2\">
                <div class=\"card-box\">
                    <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=23&fmstate=$fmstate'>
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
       $h.="<div>
            
            <div class='text-center'>";
        $h.="<a href=\"".$this->GetLink()."&action=24&fmstate=$fmstate\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Neue Fehlermeldung anlegen</a>";

        $h.="<hr>";
        $h.=asTextField("Volltextsuche","fm_search_txt",$_REQUEST['fm_search_txt'],12,"");
        $_SESSION['focus'].="$(\"#fm_search_txt\").focus();";
        $h .= asTextField("Kundengruppe", "suchbegriff_search_txt", $_REQUEST['suchbegriff_search_txt'], 12, "");

        $h.=asTextField("Max. Treffer","fm_search_hits",$fms->GetLimit(),12);

        $h.="<br><button name=\"fm_search\" type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'><i class='fa fa-search'></i> Suchen</button>
           <hr><a href=\"".$this->GetLink()."&action=23&fmstate=$fmstate\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'><i class='fa fa-reddit'></i> Suche zurücksetzen</a>
           <hr></div></div></div></div>";

        $h.="<div class=\"col-lg-2\">";
        $h.="<div class=\"card-box\">";
        $h.="<h4 class=\"m-b-30 m-t-0 header-title\"><b>Sonderfunktionen </b></h4>";
        $h.="<hr><a href=\"".$this->GetLink()."&action=39&fmstate=$fmstate\" class=\"btn btn-inverse waves-effect waves-light\" onclick='refresh_site()'>Nachdruck</a>";
        $h.="<hr><a href=\"".$this->GetLink()."&action=46&fmstate=$fmstate\" class=\"btn btn-inverse waves-effect waves-light\" onclick='refresh_site()'>SAP Rückmeldung</a>";
        $h.="</div></div>";


        $h.="<div class=\"col-lg-2\">";
        $h.="<div class=\"card-box\">";

        $h.="<table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
        <thead><tr><td>Im neuen Fenster öffnen</td></tr></thead>
        </body>";
        if(count(GetFMState("data",""))){
            foreach (GetFMState("data","") as $i=>$item){
                If ($_REQUEST['fmstate']==$i){
                    $color="success";
                }else{
                    $color="custom";
                }
              $h.="<tr><td><a class='btn btn-$color btn-sm' target='_blank' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=23&fmstate=$i&materialgroup=&material=&version=&fid=\" 
              onclick='refresh_site()'>
              <i class='".$item['icon']."'> </i>  ".$item['desc']." </a></td></tr>";

            }
        }
        $h.="</tbody></table></div></div>";
        $h.="<div class=\"col-lg-2\">";
        $h.="<div class=\"card-box\">";

        $h.="<table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
         <thead><tr><td>im gleichen Fenster öffnen</td></tr></thead>
        </body>";
        if(count(GetFMState("data",""))){
            foreach (GetFMState("data","") as $i=>$item){
                If ($_REQUEST['fmstate']==$i){
                    $color="success";
                }else{
                    $color="custom";
                }
                $h.="<tr><td><a class='btn btn-$color btn-sm' target='_self' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=23&fmstate=$i&materialgroup=&material=&version=&fid=\" 
                onclick='refresh_site()'>
                <i class='".$item['icon']."'> </i>  ".$item['desc']." </a></td></tr>";

            }
        }
        $h.="</tbody></table>";
        $h.="</div></div></form>";
        return $h;
    }   //23
    function ShowHtmlReturnNew(){
        $fm=new FisReturn(0);
        $link="<a href=\"#\" id=\"load_home\" class=\"btn btn-custom waves-effect waves-light\" onclick=\"search_asm()\">Bauteil finden</a>";
        $js=" onChange=\"search_asm()\" ";

        $modal = asSelect2Box("Warengruppe einschränken", "fid_group", GetArtikelGroup("select2", $_REQUEST['fid_group']), 7, "");

        $modal .= asTextField("Bauteil eingeben", "fid_search_txt", $_REQUEST['fid_search_txt'], 7, "", $js);
        $modal.=$link."<hr>";
        $modal.="<div id='content_asm'></div>";
        $h="<script>
            function set_sn(sn,datum,artikel,name){
                 $(\"#fm_sn\").val(sn);
                 $(\"#fm_fd\").val(datum);
                 $(\"#fm_artikelid\").val(artikel);
                 alert('Folgende Werte wurden übernommen: \\nMaterial: ' + name + ' \\nDatum: ' + datum + ' \\nSeriennummer: '+sn);
                  $(\"#custom-width-modal3002\").modal('hide');
                
                 $(\"#sendform\").submit();
            }

            function search_asm(){   
                var str=\"Es wird nach \" + $(\"#fid_search_txt\").val()  + \" gesucht... <i class='fa fa-refresh fa-spin'><i>\";
                $(\"#content_asm\").html( str );            
               
                \$(\"#content_asm\").load(\"data.php?sess=" . $_SESSION['sess'] . "&datatype=fid&fm_artikelid=\" + encodeURI(\$(\"#fm_artikelid\").val() )+ \"&fid_search_txt=\" + encodeURI( \$(\"#fid_search_txt\").val()) + \"&fid_group=\" + encodeURI(\$(\"#fid_group\").val()) , function(responseTxt, statusTxt, xhr){
                
               });
            
             }
        </script>
        
        ";
        $h.=asModal(3002,$modal,$link,98,"Bauteilsuche");

        $h.="<form  id='sendform' method='post' action='".$this->GetLink()."&action=24&fmstate=$fmstate'>
            <div class=\"col-sm-10 \">
               <div class=\"card-box table-responsive\">
                  <h4 class=\"m-b-30 m-t-0 header-title\"><b>Fehlermeldungen anlegen</b></h4>";
        $h.=$fm->GetStarted();
        $h.=" <hr><br><br>            
            </div></div>";


        $h.="<div class=\"col-sm-2\">
                    <div class=\"card-box\">
                    <form id='form_edit' method='post' enctype='multipart/form-data'>
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
            <hr><br><br>
            <div class='text-center'>";


        $h.="<button name=\"fm_new_search\" onclick='refresh_site()' type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" ><i class='fa fa-search'> </i> Seriennummer<br>suchen</button>";

       $h.=" <hr>
            <br>
            <a href=\"#custom-width-modal3002\" data-toggle=\"modal\" data-target=\"#custom-width-modal3002\" class=\"btn btn-custom waves-effect waves-light\" >Bauteil suche</a>
           ";
        $h.="<hr><br><button name=\"fm_save_new\" onclick='refresh_site()' type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" >Fehlermeldung anlegen</button> 
            <hr><br><a href='".$this->GetLink()."&action=24' onclick='refresh_site()' class=\"btn btn-danger waves-effect waves-light\" >Formular<br>zurücksetzen</a>        
            <hr><br><a href='" . $this->GetLink() . "&action=23'  onclick='refresh_site()' type=\"submit\" class=\"btn btn-danger waves-effect waves-light\">zurück<br>zur Liste</a>
           
            </div>
            
            </div></div></form>";
        return $h;
    }   //24

    function ShowHtmlReturnEdit($fmstate=""){

        $c=new FisReturn($_GET['fmid']);

        $d=new FisDocument("Fehlermeldung","fmid/".$c->fmid,$c->fmid);
        $w=new FisWorkflow("Fehlermeldung","fmid",$c->fmid);

        $h = "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=25&fmid=" . $c->fmid . "&fmstate=" . $c->fmstate . "'>";

        $h.="<div class='row'>";
        $h.="<div class=\"col-lg-8\">";

        $h.="<div><div class=\"card-box\">";


        $h.=$c->EditPropertyAsTable();
        $h.=" <hr><br><br>    </div></div>";

        $h.=" <div>
                    <div class=\"card-box\">";

        $h.="<div id='fid_layout_1'></div>";
        $h.="<script>
           
                    function load_fid_layout_1(){   
                        $(\"#fid_layout_1\").html('Es wird aktualisiert... <i class=\"fa fa-refresh fa-spin\"><i> ');            
                       
                        \$(\"#fid_layout_1\").load(\"data.php?sess=".$_SESSION['sess']."&datatype=fidhistory&fsn=".$c->data['fmsn']."&fartikelid=".$c->data['fmartikelid']."\" , function(responseTxt, statusTxt, xhr){
                      
                       });
                    
                     }
                    </script>
                    
                    ";
        $_SESSION['loadlayout'].="
                    load_fid_layout_1();
                    ";
        $h.=" <hr><br><br>    </div></div>";

        if ($c->data['fmsn2']<>$c->data['fmsn'] && $c->data['fmsn2']>0) {


            if ($c->data['fmartikelid2'] == 0) {
                $fartikelid = $c->data['fmartikelid'];
            }else{
                $fartikelid = $c->data['fmartikelid2'];
            }


            $h.=" <div>
                    <div class=\"card-box\">";
            $h.="Austausch Bauteil ";
            $h.="<div id='fid_layout_2'></div>";
            $h.="<script>
           
                    function load_fid_layout_2(){   
                        $(\"#fid_layout_2\").html('Es wird aktualisiert... <i class=\"fa fa-refresh fa-spin\"><i> ');            
                      
                        \$(\"#fid_layout_2\").load(\"data.php?sess=".$_SESSION['sess']."&datatype=fidhistory&fsn=".$c->data['fmsn2']."&fartikelid=".$fartikelid."\" , function(responseTxt, statusTxt, xhr){
                       
                       });
                    
                     }
                    </script>
                    
                    ";
            $_SESSION['loadlayout'].="
                    load_fid_layout_2();
                    ";


            $h.=" <hr><br><br>    </div></div>";

        }

        $h.="</div>";
        $h.="<div class=\"col-lg-4\">";

        $h.="<div>
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";

                       


        $h.="<br>
            <button name=\"fmid_save\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Speichern</button>
            <button name=\"fmid_save_to_list\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>zur Liste</button>
           
            <button name=\"fmid_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Abbrechen</button>
            
                  
           <a href=\"./print.errormessage.php?sess=" . $_SESSION['sess'] . "&asview=1&fmid=" . $_GET['fmid'] . "\" target=\"_blank\"  class=\"btn btn-custom waves-effect waves-light\" >Ettikett drucken</a>
           <a href=\"./print.kv.php?sess=" . $_SESSION['sess'] . "&asview=1&fmid=" . $_GET['fmid'] . "\" target=\"_blank\"  class=\"btn btn-custom waves-effect waves-light\" >Kostenvoranschlag drucken</a>
                     
           <a href=\"./print.gls.php?sess=" . $_SESSION['sess'] . "&asview=1&fmid=" . $_GET['fmid'] . "\" target=\"_blank\"  class=\"btn btn-custom waves-effect waves-light\" >Versandlabel drucken</a>
         
                       
            
            </div></div>";

        $h.="<div >
              <div class=\"card-box\">";
        $h.=$c->GetInternMessage();
        $h .= $c->GetRepairDuration();
        $h .= " " . $c->GetDeviceAge();
        $h.="</div></div>";

        $h.="<div >
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Fehlerauswahl </b></h4>";
        $h.=$c->ShowHtmlErrorList();
        $h.="</div></div>";

        $h.="<div >
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Upload </b></h4>";
        $h.=$d->ShowHtmlInputField();
        $h.="</div></div>";


        $h.=" <div >
                <div class=\"card-box\">";
        $h.=$d->GetDocumentList();

        $h.="</div></div>";

        $h.="</div>";

        $h.="<div class='row'>";
        $h.="<div class=\"col-lg-12\">

                <div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>
        </div></div>

        </form>";
        return $h;
    }   //25


    function ShowHtmlReturnConfirm(){
        $fmid=new FisReturn(0);
        $h= "<div class=\"col-lg-6\">
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">SAP Rückmeldung bearbeiten </h4>";
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=46&fmid=&fmstate='>";
        $h.=$fmid->ShowHtmlSAP();
        $h.="</form></div></div>";
        $h.= "<div class=\"col-lg-6\">
                <div class=\"card-box table-responsive\">
        <h4 class=\"header-title m-t-0 m-b-30\">erledigte Rückmeldungen </h4>";
        $h.= $fmid->ShowHtmlFmidConfirms();
        $h.="</div></div>";
        $h.= "<div class=\"col-lg-6\">
                <div class=\"card-box table-responsive\">
        <h4 class=\"header-title m-t-0 m-b-30\">Bearbeitungsprotokoll </h4>";
        $h.=SessionToasterMessage();
        $h.="</div></div>";



        $h.= "<div class=\"col-lg-6\">
                <div class=\"card-box table-responsive\">
        <h4 class=\"header-title m-t-0 m-b-30\">Zusatzfunktionen </h4>";
        $h .= "<a href=\"" . $this->GetLink() . "&action=57\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Auftragsnummern ändern</a>";

        $h.="</div></div>";
        return $h;
    }


    function ShowHtmlWpFunctions(){
        $h= "<div class=\"col-lg-6\">
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">Funktionsübersicht </h4>";
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=49&fmid=&fmstate='>";
       if (count($this->function)>0){
           $h.="<div class=\"table-responsive\">mögliche IFIS- Funktionen";
           $h.="<table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Function-ID</th>
                        <th>Description</th>
                         <th>Icon</th>
                         <th>Userright</th>
                         
                    </tr>
                    </thead>
                    <tbody>";
           foreach ($this->function as $f=>$func){
             $h.="<tr><td>".$f."</td>";
               $h.="<td>".$func['name']."</td>";
               $h.="<td><i class='".$func['icon']."'></i></td>";
               $h.="<td>".$func['userright']."</td></tr>";
           }
           $h.="</tbody>";
           $h.="</table>";
       }else{
           $h.="<div class='alert alert-info'>keine Funktionen vorhanden</div>";

       }
        $h .= "</div></div></div>";

        $h .= "<div class=\"col-lg-6\">
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">Changelog IFIS12</h4>";
        include("./version.php");

        if (count($IFIS_VERSION) > 0) {
            $h .= "<div class=\"table-responsive\">Changelog";
            $h .= "<table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Version</th>
                        <th>Name</th>
                         <th>Log</th>
                       
                         
                    </tr>
                    </thead>
                    <tbody>";
            foreach ($IFIS_VERSION as $l => $log) {
                $h .= "<tr><td>" . $l . "</td>";
                $h .= "<td>" . $log['desc'] . "</td>";
                $h .= "<td>" . $log['changelog'] . "</td></tr>";
            }
            $h .= "</tbody>";
            $h .= "</table>";
        } else {
            $h .= "<div class='alert alert-info'>keine Logs vorhanden</div>";

        }
        $h .= "</div></div></div>";
        return $h;
    }

    function ShowHtmlMaintenanceDue(){
        $txt="";
        if ($_SESSION['maintenancetype']==1){$txt="Messmittel-";}
        $h="";
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=48&pid='>";
        $link="<button name=\"new_maintenance_docu\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Wartung dokumentieren</button>";
        $h.=asModal(5002,"<div id='layout_new_maintenance'></div>",$link,60,"Neue Wartung dokumentieren");
        $h.="</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=" . $this->action . "&fmid=&fmstate='>";
        $h.="<div class='col-lg-10'>";

        $h.= "<div >
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">überfällige $txt Wartungen und Rückstandsbearbeitung (-2 Jahre bis heute)</h4>";

        $h.=$this->ShowHtmlMaintenanceDueList(-730,0);
        $h.="</div></div>";

        $h.= "<div >
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">anstehende $txt Wartungen (heute bis 14 Tage in die Zukunft)</h4>";

        $h.=$this->ShowHtmlMaintenanceDueList(1,14);
        $h.="</div></div>";

        $h.= "<div >
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">geplante $txt Wartungen (15 Tage bis 3 Monate in die Zukunft)</h4>";

        $h.=$this->ShowHtmlMaintenanceDueList(15,90);
        $h.="</div></div>";

        if ($_SESSION['maintenancetype']==0) {
            $h .= "<div >
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">Routinewartungen an den Arbeitsplätzen </h4>";

            $h .= $this->ShowHtmlWpMaintenanceList();
            $h .= "</div></div>";
        }else{

            $h.= "<div >
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">geplante $txt Wartungen (3 Monate bis 1 Jahr in die Zukunft)</h4>";

            $h.=$this->ShowHtmlMaintenanceDueList(91,370);
            $h.="</div></div>";


        }
        $h.="</div>";
        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";

           
       $h.="<div class='text-center'> ";

        $h.=asTextField("Volltext","fhm_search_txt",$_SESSION['fhm_search_txt'],12,"");


        // $_SESSION['focus'].="$(\"#fhm_search_txt\").focus();";
        $h .= asSelect2Box("Equipmentgruppe", "fhm_search_group", GetEquipmentGroupName("select2", $_SESSION['fhm_search_group']),12,"");
       // $h .= asSelect2Box("Ereignisart", "fhm_search_eventtype", GetEventTypeName("select2", $_SESSION['fhm_search_eventtype']),12,"");
        $h .= asSelectBox("Ansicht", "maintenancetype", GetMaintenanceTypes("select", $_SESSION['maintenancetype']),12,"");

        $h .= asSelectBox("auch gelöschte anzeigen", "fhm_search_lokz", GetYesNoList("select", $_SESSION['fhm_search_lokz']),12,"");

        // $h.=asTextField("Max. Treffer","fhm_search_hits",$_SESSION['fhm_search_hits'],12);

        $h.="<br><button name=\"maintenancedue_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr>
            <button name=\"maintenancedue_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br>
            <hr>
            <br>
           
            </div>
            
            </div></div></form>";

        return $h;
    }

    function ShowHtmlMaintenanceManager(){
        $w=new FisMaintenance(0);
        $h="";
        $w->pid=0;
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=48&maintenancetype=" . $_SESSION['maintenancetype'] . "&pid='>";
        $link="<button name=\"new_maintenance_add\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Neu anlegen</button>";
        $h.=asModal(5001,$w->EditPropertyAsTable(),$link,60,"Neue Wartung anlegen");
        $h.="</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=48&maintenancetype=" . $_SESSION['maintenancetype'] . "&pid='>";
        $link="<button name=\"new_maintenance_docu\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Wartung dokumentieren</button>";
        $h.=asModal(5002,"<div id='layout_new_maintenance'></div>",$link,60,"Neue Wartung dokumentieren");
        $h.="</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=" . $this->action . "&maintenancetype=" . $_SESSION['maintenancetype'] . "&pid='>";
        $h.= "<div class=\"col-lg-10\">
                <div class=\"card-box table-responsive\">";
        $w->lokz=$_SESSION['fhm_search_lokz'];
        $h.=$w->ShowHtmlAllAsTable(0,$_SESSION['fhm_search_hits'],$_SESSION['fhm_search_txt'],1);

        $h.="</div></div>";
        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";


        $h .= "<div class='text-center'> ";

        $h.=asTextField("Volltext","fhm_search_txt",$_SESSION['fhm_search_txt'],12,"");

        $_SESSION['focus'].="$(\"#fhm_search_txt\").focus();";
        // $h .= asSelect2Box("Equipmentgruppe", "fhm_search_group", GetEquipmentGroupName("select2", $_SESSION['fhm_search_group']),12,"");
       // $h .= asSelect2Box("Ereignisart", "fhm_search_eventtype", GetEventTypeName("select2", $_SESSION['fhm_search_eventtype']),12,"");

        $h .= asSelectBox("Ansicht", "maintenancetype", GetMaintenanceTypes("select", $_SESSION['maintenancetype']),12,"");
        $h .= asSelectBox("auch gelöschte anzeigen", "fhm_search_lokz", GetYesNoList("select", $_SESSION['fhm_search_lokz']),12,"");
        $h.=asTextField("Max. Treffer","fhm_search_hits",$w->limit,12);

        $h.="<br><button name=\"maintenance_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr><button name=\"maintenance_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br><hr><a href=\"#custom-width-modal5001\" data-toggle=\"modal\" data-target=\"#custom-width-modal5001\" class=\"btn btn-custom waves-effect waves-light\" >Neuen Wartungsplan<br>anlegen</a>
            </div>";

        $h.="</div></div></form>";
        return $h;
    } //48


    function ShowHtmlMaintenanceManagerEdit(){
        $p=new FisMaintenance($_SESSION['pid']);
        $w= new FisWorkflow("","pid",$_SESSION['pid']);
        $d= new FisDocument("","pid",$_SESSION['pid']);
        $h = "";

        $fhm = new FisEquipment(0);
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=64'>";
        $link = " <button name=\"fhm_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neues Equipment anlegen</button>";
        $h .= asModal(3000, $fhm->EditPropertyAsTable(), $link, 60);
        $h .= "</form>";

        $c = new FisCustomer(0);
        $c->data['service'] = 1;
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=64'>";
        $link = " <button name=\"fhm_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neuen Servicepartner anlegen</button>";
        $h .= asModal(3001, $c->EditPropertyAsTable(), $link, 60);
        $h .= "</form>";


        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=64&pid=" . $p->pid . "'>";

        $h.="<div class='row'>";
        $h.=" <div class=\"col-lg-5\">
 
                    <div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Wartungsstammdaten </b></h4>";

        $h.=$p->EditPropertyAsTable();

        $h.="</div></div>";


        $h.=" <div class=\"col-lg-5\">
 
                    <div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Equipments </b></h4>";

        $h.=$p->EditEquipments();

        $h.=$p->ShowHtmlAllocFhm();

        $h.="</div></div>";
        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
           
            <div class='text-center'> ";

        $h.="<br><button name=\"maintenance_save\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Speichern</button>
            <hr>
            <a href=\"".$this->GetLink()."&action=48\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Abbrechen</a>
            <br>
            
            <hr>
            <br>
            <a href=\"#custom-width-modal5002\" data-toggle=\"modal\" data-target=\"#custom-width-modal5002\" class=\"btn btn-custom waves-effect waves-light\" >Neue Wartung<br>dokumentieren</a>
           
            <hr>
            <br>
            <a href=\"#custom-width-modal5001\" data-toggle=\"modal\" data-target=\"#custom-width-modal5001\" class=\"btn btn-custom waves-effect waves-light\" >Plan kopieren</a>
           
            <hr>
            <br>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >neues Equipment anlegen</a>
            <hr>
            <br>
            <a href=\"#custom-width-modal3001\" data-toggle=\"modal\" data-target=\"#custom-width-modal3001\" class=\"btn btn-custom waves-effect waves-light\" >neuen Servicepartner anlegen</a>
           
            </div>
            
            </div></div></div></div>";

        $h.="<div class='row'>
                <div class=\"col-lg-12\">
                <div class=\"card-box\"><b>Verknüpfte Equipments</b></h4>";

        $h.=$p->ShowTableAllocFhm();

        $h.="</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";


        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";
        $h.="</div></form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=48&pid=" . $p->pid . "'>";
        $link="<button name=\"new_maintenance_docu\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Wartung dokumentieren</button>";
        $h.=asModal(5002,$p->NewEventAsTable(),$link,60,"Neue Wartung dokumentieren");
        $h.="</form>";

        $p->pid=0;
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=48&pid='>";
        $link="<button name=\"new_maintenance_add\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Neu anlegen</button>";
        $h.=asModal(5001,$p->EditPropertyAsTable(),$link,60,"Neuen Wartungsplan anlegen");
        $h.="</form>";


        return $h;
    }   //64


    function ShowHtmlMaintenanceDueList($start=0,$end=14,$limit=100){
        $p=new FisMaintenance(0);
        $p->datum1=date("Y-m-d",time()+ ($start*60*60*24) );
        $p->datum2=date("Y-m-d",time()+ ($end*60*60*24) );
        $p->lokz=$_SESSION['fhm_search_lokz'];
        $h=$p->ShowHtmlAllAsTable(0,$_SESSION['fhm_search_hits'],$_SESSION['fhm_search_txt'],1);


        return $h;
    }

    function ShowHtmlWpMaintenanceList(){
        $h="";
        if (count($this->FIS_WORKPLACE)>0){
            $h.="<table class='table table-responsive table-striped'>";
            $h.="<tbody>";
            foreach ($this->FIS_WORKPLACE as $i=>$wp){
                if ($i>100){
                    if (count($wp['fids'])>0){
                        foreach ($wp['fids'] as $f=>$item) {
                            if (in_array(1,$item['functions'])){
                            $e = new FisEquipment($item['fid']);

                            $h .= "<tr><td>" . $e->GetIntervall($item['pduration']) . "</td>";
                            $h .= "<td>" . $e->ShowHtmlEventListMaintenanceSmall($i) . "</td>";
                            $h .= "<td>" . $e->GetEqipmentName() . "</td>";
                            $h .= "</tr>";
                            }
                        }

                    }
                }
            }
            $h.="</tbody>";
            $h.="</table>";
        }

        return $h;
    }

    function ShowHtmlDatalogView(){
        $_SESSION['diagramm']=array();
       // if ($_SESSION['diagrammdataset']=""){$_SESSION['diagrammdataset']=1;}
        $h="";
        $e= new FisEquipment($_SESSION['equip']);
        if ($_SESSION['diagrammdataset']=="") $_SESSION['diagrammdataset']=1;

        if (count($e->layout['diagramms']['flot'])>0) {
            foreach ($e->layout['diagramms']['flot'] as $d => $item) {

                $_SESSION['diagramm'][$_SESSION['diagrammdataset']][$d]['udid'] = $e->GetUDID();
                $_SESSION['diagramm'][$_SESSION['diagrammdataset']][$d]['interval'] = $item['interval'];

                $diaheight=$item['height'];
                if ($diaheight<10) $diaheight=450;
                $diacol=$item['col'];
                if ($diacol<2) $diacol=12;


                $dlog1 = new FisDatalog($e->GetUDID(), $d, $_SESSION['equip']);
                $dlog1->SetDataSet($_SESSION['diagrammdataset']);

                $str = "<a href='./print.diagramm.php?sess=" . $_SESSION['sess'] . "&udid=" . $e->GetUDID() . "&diagrammdataset=" . $_SESSION['diagrammdataset'] . "&asview=1' 
            target='_blank' class='btn btn-sm btn-inverse pull-right' >Prüfbericht anzeigen</a>";
                $str .= "<i class=\"fa fa-area-chart\"> </i> Temperaturverlauf (" . $dlog1->GetDiagrammDesc() . ")";

                $h .= $dlog1->GetWidgetFlotChart($str, $diaheight, $diacol);
            }


            $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>weitere Diagramme</b></h4>";
            $h .= "<a href=\"" . $this->GetLink() . "&action=0\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Abbrechen</a>";

            $h .= $dlog1->GetDiagrammFunctions();
            $h .= "</div></div>";


        }


    return $h;
    } //26

    function ShowHtmlFIDView(){
        $c=new FisFid(0);
        $c->material=$_SESSION['fid_search_material'];
        $c->lokz=$_SESSION['fid_search_lokz'];
        $c->sn=$_SESSION['fid_search_sn'];

        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=42'>";
        $link=" <button name=\"fid_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neu anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(),$link,60);
        $h.="</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=42'>
                <div class=\"col-lg-10\">
                    <div class=\"card-box\">";


        $h .= $c->ShowHtmlAllAsTable(1, $_SESSION['fid_search_hits'], $_SESSION['fid_search_txt'], 0);
        $h.=" <hr><br><br> </div></div>";


        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
           
            <div class='text-center'> ";

        $h.=asTextField("Volltext","fid_search_txt",$_SESSION['fid_search_txt'],12,"");
        $_SESSION['focus'].="$(\"#fid_search_txt\").focus();";

        $h.=asTextField("Seriennummer","fid_search_sn",$_SESSION['fid_search_sn'],12,"");

        $h .= asSelect2Box("Materialnummer", "fid_search_material", GetArtikelName("select2", $_SESSION['fid_search_material']),12,"");
        $h .= asSelectBox("auch gelöschte anzeigen", "fid_search_lokz", GetYesNoList("select", $_SESSION['fid_search_lokz']),12,"");

        $h.=asTextField("Max. Treffer","fid_search_hits",$c->GetLimit(),12);

        $h.="<br><button name=\"fid_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr>
            <button name=\"fid_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br>
            ";

        if ($_SESSION['fid_search_material']>0) {
            $h .= "<hr>
            <a class='btn btn-custom' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=15&materialgroup=&material=" . $_SESSION['fid_search_material'] . "&version=&fid=\" onclick='refresh_site()'>Materialdaten anzeigen </a>";
        }

        //$h.="<hr> <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Neu</a>";

        $h .= "</div></div></div></form>";
        return $h;
    }   //42



    function ShowHtmlNoPermission(){
        $h="<div class='col-lg-12 card-box'>";
        $h.="<div class='table-responsive text-center'><span class='text text-warning'> 
            <i class='fa fa-warning fa-5x'> </i> <br> Bitte melden Sie sich an <br><br>
            <a href=\"#custom-width-modal2\" data-toggle=\"modal\" data-target=\"#custom-width-modal2\" class='btn btn-custom '>Anmelden</a>
            <br><code> WP ".$this->wp." & Function ".$this->action." </code></span></div>";
        $h.="</div>";
        return $h;
    } //No Permission


    function ShowHtmlCustomers(){
        $c=new FisCustomer();
        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=27'>";
        $link=" <button name=\"customer_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neu anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(),$link,60);
        $h.="</form>";

        $c->suchbegriff = $_REQUEST['suchbegriff_search_txt'];

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=27'>
                <div class=\"col-lg-10\">
                    <div class=\"card-box\">";


        $h.=$c->ShowHtmlAllCustomers(1,$_REQUEST['customer_search_hits'],$_REQUEST['customer_search_txt'],0);
        $h.=" <hr><br><br> </div></div>";


        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
           
            <div class='text-center'> ";

        $h.=asTextField("Volltext","customer_search_txt",$_REQUEST['customer_search_txt'],12,"");
        $h .= asTextField("Kundengruppe", "suchbegriff_search_txt", $_REQUEST['suchbegriff_search_txt'], 12, "");

        $_SESSION['focus'].="$(\"#customer_search_txt\").focus();";
        $h.=asTextField("Max. Treffer","customer_search_hits",$c->GetLimit(),12);

        $h.="<br><button name=\"customer_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr>
            <button name=\"customer_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br>
            <hr>
            <br>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Neu</a>
            
            </div>
            
            </div></div></form>";
        return $h;
    }   //27
    function ShowHtmlEditCustomer(){
        $c=new FisCustomer($_GET['idk']);
        $w= new FisWorkflow("","idk",$_GET['idk']);
        $d= new FisDocument("","idk",$_GET['idk']);


        $h = "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=28'>";

        $h.="<div class='row'>";
        $h.=" <div class=\"col-lg-10\">
                    <div class=\"card-box\">";


        $h.=$c->EditPropertyAsTable();
        $h.=" <hr><br><br>";
        $h.=$d->ShowHtmlInputField();
        $h.="</div></div>";


        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
           
            <div class='text-center'> ";

        $h.="<br><button name=\"customer_save\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Speichern</button>
            <hr>
            <a href='" . $this->GetLink() . "&action=27' class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>zur Liste</a>
            <br>
            <hr>
            <a href='" . $this->GetLink() . "&action=23' class=\"btn btn-custom  waves-effect waves-light\" onclick='refresh_site()'>alle Fehlermeldungen</a>
            <hr>
            <a href='" . $this->GetLink() . "&action=24&fm_idk=" . $_SESSION["idk"] . "' class=\"btn btn-custom  waves-effect waves-light\" onclick='refresh_site()'>neue Fehlermeldung anlegen</a>
           <hr>
            <a href=\"#custom-width-modal3002\" data-toggle=\"modal\" data-target=\"#custom-width-modal3002\" class=\"btn btn-custom waves-effect waves-light\" >neue Rückholung anlegen</a>
           
            <hr>
            <br>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Ansprechpartner kopieren</a>
            <br>
            <a href=\"#custom-width-modal3001\" data-toggle=\"modal\" data-target=\"#custom-width-modal3001\" class=\"btn btn-custom waves-effect waves-light\" >Ansprechpartner neu anlegen</a>
           
           
            </div>
            
            </div></div></div></div>";

        $h.="<div class='row'>
                <div class=\"col-lg-12\">
                <div class=\"card-box\">";

        $h .= asJSLoader("returns", "Fehlermeldungen werden geladen...", "data.php", "datatype=fmidbycustomer&ref1=" . $c->data['kundenummer'] . "&ref2" . $c->data['kdnummer2']);
        $h.="</div></div>";

        $h .= "<div class=\"col-lg-12\">
                <div class=\"card-box\">";

        $h .= asJSLoader("returndevices", "Rückholungen werden geladen...", "data.php", "datatype=rueckidbycustomer&idk=" . $c->data['idk']);
        $h .= "</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";


        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";
        $h.="</div></form>";



        $c->idk=0;
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=27'>";
        $link = " <button name=\"customer_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Kopie anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(),$link,60);
        $h.="</form>";
        $c->LoadData();
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=27'>";
        $link = " <button name=\"customer_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neu anlegen</button>";
        $h .= asModal(3001, $c->EditPropertyAsTable(), $link, 60);
        $h .= "</form>";

        $re = new FisReturnDevice(0);
        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=22'>";
        $link = " <button name=\"re_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Rückholung anlegen</button>";
        $h .= asModal(3002, $re->EditPropertyAsTable(), $link, 60);
        $h .= "</form>";
        return $h;
    }   //28


    function ShowHtmlMaterials(){
        $c=new FisMaterial();
        $c->group=$_SESSION['material_search_group'];
        $c->kontierung=$_SESSION['material_search_kontierung'];
        $c->productfamily=$_SESSION['material_search_productfamily'];

        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=34'>";
        $link=" <button name=\"material_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neues Material anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(),$link,60);
        $h.="</form>";

        if (strlen($_SESSION['material_search_kontierung'])>0) {

            $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=34'>
                <div class=\"col-lg-10\">
                    <div class=\"card-box\">";


            $h .= $c->ShowHtmlAllAsTable(0, $_REQUEST['material_search_hits'], $_REQUEST['material_search_txt'],0);
            $h .= " <hr><br><br> </div></div>";


            $h .= "<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
            $h .= "<div>
           
            <div class='text-center'> ";

            $h .= asTextField("Volltext", "material_search_txt", $_REQUEST['material_search_txt'], 12, "");
            $_SESSION['focus'] .= "$(\"#material_search_txt\").focus();";
            $h .= asSelect2Box("Kontierung", "material_search_kontierung", GetArtikelKontierung("select2", $_SESSION['material_search_kontierung']), 12, "");

            $h .= asSelect2Box("Warengruppe", "material_search_group", GetArtikelGroup("select2", $_SESSION['material_search_group']), 12, "");

            $h .= asSelect2Box("Produktfamilie", "material_search_productfamily", GetArtikelProductfamily("select2", $_SESSION['material_search_productfamily']), 12, "");

            $h .= asSelectBox("auch gelöschte anzeigen", "material_search_lokz", GetYesNoList("select", $_SESSION['material_search_lokz']),12,"");

            $h .= asTextField("Max. Treffer", "material_search_hits", $c->GetLimit(), 12);

            $h .= "<br><button name=\"material_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr>
            <button name=\"material_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br>
            <hr>
            <br>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Neues Material anlegen</a>
            
            </div>
            
            </div></div></form>";

        }else{
            $h.=$this->ShowMaterialKontierung();
        }
        return $h;
    }   //34 Material list
    function ShowHtmlViewMaterialSettings(){
        $m=new FisMaterial($_SESSION['material']);
        $d=new FisDocument("","artikelid",$_SESSION['material']);
        $w=new FisWorkflow("","artikelid",$_SESSION['material']);

        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=16'>";
        $link=" <button name=\"material_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Als neues Material kopieren</button>";
        $h.=asModal(3000,$m->EditPropertyAsTable(),$link,60);
        $h.="</form>";

        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->Getlink() . "&material=" . $m->material . "&version=" . $m->data['version'] . "'>
               <div class='row'>
                <div class=\"col-lg-12\" >
                 
            <div class=\"col-lg-5\"> 
               <div class='card-box '>
               
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Material (".$_SESSION['material'].")</b></h4>";

        $h.=$m->ShowPropertyAsTable();

        $h.="</div></div>";

        $h.=" <div class=\"col-lg-5\"> 
               <div class='card-box '>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>aktive Artikelversion (".$m->data['version'].")</b></h4>";

        if (strlen($m->data['version'])>0) {

            $version = new FisVersion($m->data['version'],$m->material);
            $h .= $version->ShowPropertyAsTable();


        }else{
            $h.="Für diese Material ist keine Artikelversion angelegt.";
        }

        $h.="</div></div>";
        $h.="<div class=\"col-lg-2\">
                <div class=\"card-box\">
                 <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        if ($_SESSION['wp']==0) {
            $h .= "<a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=34&materialgroup=&material=" . $m->material . "&version=&fid=\" onclick='refresh_site()'> zur Liste </a>";

        }else{
            $h .= "<a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=4&materialgroup=&material=" . $m->material . "&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
            $h .= "<a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=34&materialgroup=&material=" . $m->material . "&version=&fid=\" onclick='refresh_site()'> zur Liste </a>";
        }

        $h .= "<hr><a class='btn btn-custom' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=42&materialgroup=&material=" . $m->material . "&version=&fid=\" onclick='refresh_site()'>Fertigungsdaten anzeigen </a>";
        $h .= "<hr><a class='btn btn-custom' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=37&materialgroup=&material=" . $m->material . "&version=&fid=\" onclick='refresh_site()'>Wareneingänge anzeigen </a>";

        $h .= "<hr><a class='btn btn-custom' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=16&materialgroup=&material=" . $m->material . "&version=&fid=\" onclick='refresh_site()'>Material ändern </a>";
        $h .= "<hr><a class='btn btn-custom' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=17&materialgroup=&material=" . $m->material . "&version=" . $m->data['version'] . "&fid=\" onclick='refresh_site()'>Version ändern </a>";
        $h .= "<hr><a href=\"#custom-width-modal4000\" data-toggle=\"modal\" data-target=\"#custom-width-modal4000\" class=\"btn btn-custom waves-effect waves-light\" >Druckeinstellungen bearbeiten</a>";

        $h.="<hr><a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Neues Material anlegen</a>";

        $h.="</div></div>";


        $h.="<div class=\"col-lg-2\">
                <div class=\"card-box\">
                 <h4 class=\"m-b-30 m-t-0 header-title\"><b>Drucken </b></h4>";
        $h .= "<hr><button class='btn btn-custom' type='submit' name='print_material_desc' onclick='refresh_site()'> <i class='fa fa-print'> </i> Regalbeschriftung<br>drucken</button>";

        $h .= "<hr><a class='btn btn-custom' href=\"print.material.desc.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&material=" . $_SESSION['material'] . "&asview=1\" target='_blank'> <i class='fa fa-eye'> </i> Voransicht<br>Regalbeschriftung </a>";
        $h.="</div></div>";

        $h.="<div class=\"col-sm-12\">
                    <div class=\"card-box\">
                        <hr><h4 class=\"m-b-30 m-t-0 header-title\"><b>Versionsübericht</b></h4>";
        $h.=$m->ShowVersionenAsTable();
        $h.="</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Workflow</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";

        $h.="</div>
          </div>
            </div></div></form>";
        $_SESSION['version'] = $m->data['version'];
        $modal = asJSLoader("EditPrintSettings", "Druckeinstellungen werden geladen", "settings.php", "datatype=printsettings");
        $h .= asModal(4000, $modal, "", 95, "Druckeinstellungen für " . $m->GetMaterialDesc() . " bearbeiten");

        return $h;
    }  //Material view
    function ShowHtmlChangeMaterialSettings(){
        $m=new FisMaterial($_SESSION['material']);
        $v = new FisVersion($m->data['version'], $_SESSION['material']);

        $d=new FisDocument("","artikelid",$_SESSION['material']);
        $w=new FisWorkflow("","artikelid",$_SESSION['material']);

        $h="";
        // $h.="<form id='form_edit' method='post' enctype='multipart/form-data' action='".$this->GetLink()."&action=15'>";
        $btn="<button name=\"save_ean13_code\" class=\"btn btn-primary waves-effect waves-light\" onclick='save_ean13()'> Aktualisieren </button>";
        $modal=$m->ShowHtmlUpdateEAN13();
        $modal.="<div id='content_asm'></div>";
        $h="<script>
           

            function save_ean13(){   
                $(\"#content_asm\").html('Es wird aktualisiert... <i class=\"fa fa-refresh fa-spin\"><i> ');            
               
                \$(\"#content_asm\").load(\"data.php?sess=".$_SESSION['sess']."&datatype=ean13&new_material_ean13=\" + \$(\"#new_material_ean13\").val() + \"&new_material_number=\" + \$(\"#new_material_number\").val() , function(responseTxt, statusTxt, xhr){
               
               });
            
             }
        </script>
        
        ";


        $h.=asModal(3003, $modal,$btn,55,"EAN-Code bearbeiten");
        //$h.="</form>";


        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=15'>
               <div class='row'>
                <div class=\"col-lg-12\" >
                
                  
         <div class=\"col-lg-4\"> 
               <div class='card-box '>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>gespeicherte Materialdaten (".$_SESSION['material'].")</b></h4>";


        $h.=$m->ShowPropertyAsTable();
        $h.="</div></div>";

        $h.=" <div class=\"col-lg-6\"> 
               <div class='card-box '>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderung (".$_SESSION['material'].")</b></h4>";


        $h.=$m->EditPropertyAsTable();

        $h.="</div></div>";

        $h.="<div class=\"col-lg-2\">
                <div class=\"card-box\">
                 <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        if ($_SESSION['wp']==0) {
            $h .= "<a class='btn btn-danger ' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=34&materialgroup=&material=" . $_SESSION['material'] . "&version=&fid=\" onclick='refresh_site()'> zur Liste </a>";
        }else{
            $h.="<a class='btn btn-danger ' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=15&materialgroup=&material=".$_SESSION['material']."&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
            $h .= "<a class='btn btn-danger' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=34&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> zur Liste </a>";
        }
        $h.="<hr><div class=\"form-group \"><button name=\"save_material\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site()'> Speichern </button></div>";

        $h.="<hr><a href=\"#custom-width-modal3003\" data-toggle=\"modal\" data-target=\"#custom-width-modal3003\" class=\"btn btn-custom waves-effect waves-light\" >EAN Codes<br>bearbeiten</a>";

        //$h.="<hr><div class=\"form-group \"><button name=\"import_FIS9_material\" type=\"submit\" class=\"btn btn-warning waves-effect waves-light\" onclick='refresh_site()'> Import aus FIS9 </button></div>";

        $h.="<hr>Die Änderung an Materialdaten werden sofort aktiv.";
        $h.="</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Versionsübericht</b></h4>";
        $h.=$m->ShowVersionenAsTable();

        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";

        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";

        $h.="</div>
          </div>
            </div></div></form>";
        return $h;
    }  //Material edit
    function ShowHtmlChangeVersionSettings(){

        if (strlen($_SESSION['version'])>0) {
            $version = new FisVersion($_SESSION['version'], $_SESSION['material']);
            $d = new FisDocument("", "versionid", $version->versionid);
            $w = new FisWorkflow("", "versionid", $version->versionid);
        }


        $modal = "";
        $modal .= " <div class=\"col-lg-12\"> 
              
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>aktive Artikelversion (" . $_SESSION['version'] . ")</b></h4>";

        if (strlen($_SESSION['version']) > 0) {
            $modal .= $version->ShowPropertyAsTable();
        } else {
            $modal .= "Für dieses Material ist keine Artikelversion angelegt.";
        }
        $modal .= "</div>";
        $h = asModal(2000, $modal, "", 95, "gespeicherte Versionsdaten");

        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data'>";
        $modal = $version->EditVersionName();
        $h .= asModal(2002, $modal, "", 95, "Version umbenennen");
        $h .= "<div class='row'>
                <div class=\"col-lg-12\" >";


        $h.=" <div class=\"col-lg-4\"> 
               <div class='card-box '>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderung (" . $_SESSION['version'] . ")</b></h4>";
        if (strlen($_SESSION['version'])>0) {
            $h .= $version->EditPropertyAsTable();
        }
        $h.="</div></div>";
        $h.=" <div class=\"col-lg-6\"> 
               <div class='card-box '>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderung (".$_SESSION['version'].")</b></h4>";
        if (strlen($_SESSION['version'])>0) {
            $h .= $version->EditProperty2AsTable();
        }
        $h.="</div></div>";
        $h.=" <div class=\"col-lg-2\"> 
               <div class='card-box '>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen</b></h4>";

        $h.="<a class='btn btn-danger' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=15&materialgroup=&material=".$_SESSION['material']."&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        if (strlen($_SESSION['version'])>0) {
            $h.="<hr><div class=\"form-group \">
                <button name=\"new_save_version\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='ProveJsonSettings(); refresh_site()'> Speichern </button>";
            $h.="</div>";
            $h .= "<hr><div class=\"form-group \">
                <button name=\"save_version_as_free\" type=\"submit\" class=\"btn btn-success waves-effect waves-light\" onclick='ProveJsonSettings(); refresh_site()'>Version als<br>aktive Version freigeben </button>";
            $h .= "</div>";
        }


        $h .= "<hr><a href=\"#custom-width-modal2000\" data-toggle=\"modal\" data-target=\"#custom-width-modal2000\" class=\"btn btn-custom waves-effect waves-light\" >Aktive Daten anzeigen</a>";
        $h .= "<hr><a href=\"#custom-width-modal2001\" data-toggle=\"modal\" data-target=\"#custom-width-modal2001\" class=\"btn btn-custom waves-effect waves-light\" >Neue Version anlegen</a>";
        $h .= "<hr><a href=\"#custom-width-modal2002\" data-toggle=\"modal\" data-target=\"#custom-width-modal2002\" class=\"btn btn-custom waves-effect waves-light\" >Version umbenennen</a>";

        $h .= "<hr><a href=\"#\" class=\"btn btn-inverse waves-effect waves-light\" onclick=\"EditWithoutEditor();\">Einstellungen ohne<br>Editor bearbeiten</a>";
        $h .= "<hr><a href=\"#\" class=\"btn btn-inverse waves-effect waves-light\" onclick=\"EditWithEditor();\">Einstellungen mit<br>Editor bearbeiten</a>";

        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";

        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";

        $h.="</div>
          </div>
            </div></div></form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data'><div class=\"col-lg-12\">";


        $version->data['Datum'] = date('Y-m-d', time());
        $modal = $version->EditPropertyAsTable(true);
        $modal .= " Vorlage ist " . $_SESSION['version'] . ". Es wird eine neue Kopie angelegt und alle Druckeinstellungen, Konfigurationseinstellungen sowie Arbeitspläne und deren Stücklisten kopiert.";

        $btn = "<button name=\"new_copy_version\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site();'> Neue Version erstellen </button>";
        $h .= asModal(2001, $modal, $btn, 95, "Neue Version anlegen");
        $h .= "</div></form>";
        return $h;
    }  //Version edit

    function ShowMaterialKontierung(){
        $h= "<div class=\"col-lg-12\">
                <div class=\"card-box table-responsive\">
                <h4 class=\"header-title m-t-0 m-b-30\">Materialkontierung auswählen ".$_SESSION['material_search_kontierung']."</h4>";
        if (count(GetArtikelKontierung("data","")) > 0) {

            foreach (GetArtikelKontierung("data","") as $d=>$i) {
                $h.="<div class=\"col-sm-3 \"> 
                        <div class='card-box table-responsive panel panel-custom panel-border'>
                            <a href=\"./".$this->GetLink()."&action=34&kontierung=".$i['Kontierung']."&material=&version=&fid=\">
                            <div class=\"col-lg-4\">
                                <i class=\"fa fa-list fa-4x\"> </i>
                            </div>
                            <div class=\"col-lg-8\">
                                <div class=\"wid-u-info\">
                                    <h4 class=\"m-t-0 m-b-5\">".$i['Kontierung']."</h4>
                                    <p class=\"text-muted m-b-5 font-13\"></p>
                                   
                                </div>
                            </div>
                            </a>
                            </div>
                        </div>";
            }
            $h.="<div class=\"col-sm-3 \"> 
                        <div class='card-box table-responsive panel panel-success panel-border'>
                            <a href=\"./".$this->GetLink()."&action=34&kontierung=0&material=&version=&fid=\">
                            <div class=\"col-lg-4\">
                                <i class=\"fa fa-list fa-4x text-success\"> </i>
                            </div>
                            <div class=\"col-lg-8\">
                                <div class=\"wid-u-success\">
                                    <h4 class=\"m-t-0 m-b-5\">Alles anzeigen</h4>
                                    <p class=\"text-muted m-b-5 font-13\"></p>
                                   
                                </div>
                            </div>
                            </a>
                            </div>
                        </div>";
            $h.=" </div></div>";
        }
        return $h;
    }




    function ShowHtmlUsers(){
        $c=new FisUser();
        $c->lokz=$_REQUEST['user_search_lokz'];

        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=35'>";
        $link=" <button name=\"user_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neuen Mitarbeiter anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(),$link,60);
        $h.="</form>";


        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=35'>
            <div class=\"col-lg-10\">
                <div class=\"card-box\">";


        $h .= $c->ShowHtmlAllAsTable(1, $_REQUEST['user_search_hits'], $_REQUEST['user_search_txt'],0);
        $h .= " <hr><br><br> </div></div>";


        $h .= "<div class=\"col-lg-2\">
          <div class=\"card-box\">
            <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h .= "<div>
       
        <div class='text-center'> ";

        $h .= asTextField("Volltext", "user_search_txt", $_REQUEST['user_search_txt'], 12, "");
        $_SESSION['focus'] .= "$(\"user_search_txt\").focus();";
        $h .= asSelectBox("auch gelöschte anzeigen", "user_search_lokz", GetYesNoList("select", $_REQUEST['user_search_lokz']),12,"");

        $h .= asTextField("Max. Treffer", "user_search_hits", $c->GetLimit(), 12);

        $h .= "<br><button name=\"user_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
        <hr>
        <button name=\"user_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
        <br>
        <hr>
        <br>
        <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Neues Material anlegen</a>
        
        </div>
        
        </div></div></form>";


        return $h;
    }   //35 Userlist
    function ShowHtmlChangeUserSettings(){
        $c=new FisUser($_SESSION['userid']);
        $d=new FisDocument("","userid",$_SESSION['userid']);
        $w=new FisWorkflow("","userid",$_SESSION['userid']);

        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=35' mult> ";
        $link=" <button name=\"user_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neuen Mitarbeiter anlegen</button>";
        $h.=asModal(3000,$c->EditPropertyAsTable(false),$link,60);
        $h.="</form>";

        $h.=" <form method=\"post\" action=\"".$this->GetLink()."&action=35&userid=".$_SESSION['userid']."\" enctype=\"multipart/form-data\">
               <div class='row'>
                <div class=\"col-lg-12\" >
                
                  
         <div class=\"col-lg-4\"> 
               <div class='card-box '>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>gespeicherte Mitarbeiterdaten (".$_SESSION['userid'].")</b></h4>";


        $h.=$c->ShowPropertyAsTable();
        $h.="</div></div>";

        $h.=" <div class=\"col-lg-6\"> 
               <div class='card-box '>
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderung (".$_SESSION['userid'].")</b></h4>";


        $h.=$c->EditPropertyAsTable();

        $h.="</div></div>";

        $h.="<div class=\"col-lg-2\">
                <div class=\"card-box\">
                 <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        if ($_SESSION['wp']=="0") {
            $h .= "<a class='btn btn-danger ' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=35&materialgroup=&material=&userid=&version=&fid=\" onclick='refresh_site()'> zur Liste </a>";
        }else{
            $h.="<a class='btn btn-danger ' href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=0&materialgroup=&material=&userid=&version=&fid=\" onclick='refresh_site()'> Abbrechen </a>";
        }
        $h.="<hr><div class=\"form-group \"><button name=\"user_save\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light\" onclick='refresh_site()'> Speichern </button></div>";

        $h.="<hr>Die Änderung an Mitarbeiterdaten werden sofort aktiv.

        <hr>
        <br>
         <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Kopie anlegen</a>
       ";

        $h.="</div></div>";


        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";

        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";

        $h.="</div>
          </div>
            </div></div></form>";
        return $h;
    }  //36 User edit



    function ShowHtmlViewWarehouseInput(){
        $c=new FisWarehouse();
        $h = " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=37'>";
        $link=" <button name=\"we_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neu anlegen</button>";
        $h .= asModal(3000, $c->EditPropertyAsTable(), $link, 90);
        $h.="</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=37'>
                <div class=\"col-lg-10\">
                    <div class=\"card-box\">";


        $h.=$c->ShowHtmlAllWarehouse(1,$_REQUEST['we_search_hits'],$_REQUEST['we_search_txt'],0);
        $h.=" <hr><br><br> </div></div>";


        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div>
           
            <div class='text-center'> ";

        $h.=asTextField("Volltext","we_search_txt",$_REQUEST['we_search_txt'],12,"");
        $_SESSION['focus'].="$(\"#we_search_txt\").focus();";

        $h .= asSelect2Box("Materialnummer", "fid_search_material", GetArtikelName("select2", $_SESSION['fid_search_material']),12,"");

        $h.=asTextField("Max. Treffer","we_search_hits",$c->GetLimit(),12);

        $h.="<br><button name=\"we_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr>
            <button name=\"we_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br>
            <hr>
            <br>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-custom waves-effect waves-light\" >Neu</a>
            
            </div>
            
            </div></div></form>";
        return $h;
    } //37

    function ShowHtmlChangeWarehouseInput(){
        $c=new FisWarehouse($_GET['weid']);
        $w= new FisWorkflow("","weid",$_GET['weid']);
        $d= new FisDocument("","weid",$_GET['weid']);
        $mat=GetArtikelName("data",$c->data['we_nummer']);
        $m=new FisMaterial($mat);

        $s=new FisSignature("","weid",$_GET['weid']);


        $h="<form id='sendform' method='post' action='".$this->GetLink()."&action=37&weid=".$_GET['weid']."'>";

        $h.="<div class='row'>";


        $h.=" <div class=\"col-lg-10\">
                    <div class=\"card-box\">";


        $h.=$c->EditPropertyAsTable();
        $h.=" <hr><b>Liste der Prüfmerkmal(e)</b><br><br>";

        $h.=$c->GetPosList();

        $h.="</div></div>";


        $h.="<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h.="<div class='text-center'> ";

        $h.="<br>
            <button name=\"we_save\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'><i class='fa fa-save'> </i> Speichern</button>
            <hr>
            <a href=\"".$this->GetLink()."&action=37\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Abbrechen und<br>zur Übersicht</a>
            <br>
            <hr>
             <a href=\"./print.warehouse.php?sess=".$_SESSION['sess']."&asview=1\" target=\"_blank\"  class=\"btn btn-custom waves-effect waves-light\" ><i class='fa fa-file'> </i> Etikett<br>anzeigen</a>
            <br>
             <a href=\"".$this->GetLink()."&action=37&print_warehouse=1\" target=\"_self\"  class=\"btn btn-custom waves-effect waves-light\" ><i class='fa fa-print'> </i> Etikett<br>drucken</a>
           
            <hr>
            <a href=\"#custom-width-modal3000\" data-toggle=\"modal\" data-target=\"#custom-width-modal3000\" class=\"btn btn-inverse waves-effect waves-light\" >Daten in neuen<br>Wareneingang<br>kopieren</a>
           <br>
           
             <hr>
            <button name=\"we_pos_copy\" type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>alle Positionen<br>kopieren</button>
            <hr>
            <br>
            <a href=\"#custom-width-modal3002\" data-toggle=\"modal\" data-target=\"#custom-width-modal3002\" class=\"btn btn-custom waves-effect waves-light\" >Neue Prüfpostion anfügen</a>
           <br>
           <hr>";
        $h .= "<b>Status: </b>" . $c->GetStatus(). " <br><br>Maßnahmen im Fehlerfall: Umgehend Einkauf informieren! <br><hr>";
        $h.=$c->GetPicture();
        $h.="</div></div></div>";


        $h.="</div><div class='row'>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Versionsübersicht zum Material ".$c->data['we_nummer']."</b></h4>";
        $h.=$m->ShowVersionenAsTable();
        $h.="</div></div> </div>";


        $h.="<div class='row'>";
/**
        $h.="<div class=\"col-lg-6\">";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Unterschreiben</b></h4>";
        $h.=$s->ShowHtmlSign();
        $h.="</div></div>";

        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Unterschriften</b></h4>";
        $h.=$s->GetDocumentList();
        $h.="</div></div>";
        $h.="</div>";
 *
*/
        // $h.="<div class=\"col-lg-6\">";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Lieferschein und/ oder Foto hochladen</b></h4>";
        $h.=$d->ShowHtmlInputField();
        $h.="</div></div>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h.=$d->GetDocumentList();
        $h.="</div></div>";
        $h.="</div>";
   //$h.="</div>";

        $h.="<div class='row'>";
        $h.="<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h.=$w->GetWorkflowList();
        $h.="</div></div>";
        $h.="</div></form>";

        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=37&weid=" . $_GET['weid'] . "'>";
        $link=" <button name=\"we_new\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Neu anlegen</button>";
        $h .= asModal(3000, $c->EditPropertyAsTable(true), $link, 90);
        $h.="</form>";


        $h .= " <form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=37&weid=" . $_GET['weid'] . "'>";
        $link=" <button name=\"we_new_position\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Meue Prüfposition anlegen</button>";

        $h .= asModal(3002, $c->EditPosPropertyAsTable(), $link, 90);
        $h.="</form>";
        return $h;
    } //38


    function ShowHtmlWpProperties(){
        $h=$this->ShowHtmlChangeWpSettings();
        $h.=$this->ShowHtmlChangeAllowedUser();

        // $h.=$this->ShowHtmlWpGroup();
        // $h.=$this->ShowHtmlWebSession();
        return $h;
    }       //WP
    function ShowHtmlChangeWpSettings(){

        $s=new FisSettings($this->wp);
        $h=$s->ShowHtmlWpSettings();
        return $h;
    }   //WP


    function PrintEikettgenerator(){
        $s=new FisSettings($this->wp);
        $h=$s->PrintEtikettGenerator();
        return $h;
    }   //Etikett

    function ShowHtmlEtikettGenerator(){

        $s=new FisSettings($this->wp);
        $h=$s->ShowHtmlEtikettGenerator();
        return $h;
    }   //Etikett

    function PrintBarcodegenerator(){
        $s=new FisSettings($this->wp);
        $h=$s->PrintBarcodeGenerator();
        return $h;
    }   //Barcodeprint

    function ShowHtmlBarCodeGenerator(){

        $s=new FisSettings($this->wp);
        $h=$s->ShowHtmlBarcodeGenerator();
        return $h;
    }   //


    function PrintGLSgenerator()
    {
        $s = new FisSettings($this->wp);
        $h = $s->PrintGLSGenerator();
        return $h;
    }   //74

    function ShowHtmlGLSGenerator()
    {

        $s = new FisSettings($this->wp);
        $h = $s->ShowHtmlGLSGenerator();
        return $h;
    }   //74

    function ShowHtmlFISUpdate(){

        $s=new FisSettings($this->wp);
        $h=$s->ShowHtmlFISUpdate();
        return $h;
    }   //WP

    function ShowHtmlChangeWpPrinterSettings(){
       $s=new FisSettings($this->wp);
       $h=$s->ShowHtmlWpPrintSettings();
       return $h;
    }  //WPPrintSettings


    function ShowHtmlWpGroup(){
        $h="<div class=\"col-sm-6\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Arbeitsplatzgruppe</b></h4>
                        <div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Wert</th>
                                <th>Info</th>
                            </tr>
                            </thead>
                            <tbody>";

        if (count($this->data) > 0) {
            foreach ($this->data as $d => $i) {
                if (count($i)){$t=print_r($i,true);} else{$t=$i;}
                $h .= "    <tr>
                                <td>".$d."</td>
                                <td>".$t."</td>
                                <td></td>
                            </tr>";
            }
        }
        $h .= "          </tbody>
                        </table>
                     </div>
                 </div>
              </div>";
        return $h;
    }            //WP


    function ShowHtmlReportManager()
    {
        $w = new FisReport(0);
        $h = "";
        $w->rid = 0;
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=68&reporttype=" . $_SESSION['reporttype'] . "&rid='>";
        $link = "<button name=\"new_report_add\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Neu anlegen</button>";
        $h .= asModal(5001, $w->EditPropertyAsTable(), $link, 60, "Neue Auswertegruppe anlegen");
        $h .= "</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=68&reporttype=" . $_SESSION['reporttype'] . "&rid='>";
        $link = "<button name=\"new_report_docu\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Auswertung dokumentieren</button>";
        $h .= asModal(5002, "<div id='layout_new_maintenance'></div>", $link, 60, "Neue Auswertung dokumentieren");
        $h .= "</form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=" . $this->action . "&reporttype=" . $_SESSION['reporttype'] . "&rid='>";
        $h .= "<div class=\"col-lg-10\">
                <div class=\"card-box table-responsive\">";
        $w->lokz = $_SESSION['fhm_search_lokz'];
        $h .= $w->ShowHtmlAllAsTable(0, $_SESSION['fhm_search_hits'], $_SESSION['fhm_search_txt'], 1);

        $h .= "</div></div>";
        $h .= "<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";


        $h .= "<div class='text-center'> ";

        $h .= asTextField("Volltext", "fhm_search_txt", $_SESSION['fhm_search_txt'], 12, "");

        $_SESSION['focus'] .= "$(\"#fhm_search_txt\").focus();";
        // $h .= asSelect2Box("Equipmentgruppe", "fhm_search_group", GetEquipmentGroupName("select2", $_SESSION['fhm_search_group']),12,"");
        // $h .= asSelect2Box("Ereignisart", "fhm_search_eventtype", GetEventTypeName("select2", $_SESSION['fhm_search_eventtype']),12,"");

        $h .= asSelectBox("Ansicht", "reporttype", GetMaintenanceTypes("select", $_SESSION['reporttype']), 12, "");
        $h .= asSelectBox("auch gelöschte anzeigen", "fhm_search_lokz", GetYesNoList("select", $_SESSION['fhm_search_lokz']), 12, "");
        $h .= asTextField("Max. Treffer", "fhm_search_hits", $w->limit, 12);

        $h .= "<br><button name=\"report_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr><button name=\"report_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br><hr><a href=\"#custom-width-modal5001\" data-toggle=\"modal\" data-target=\"#custom-width-modal5001\" class=\"btn btn-custom waves-effect waves-light\" >Neue Auswertegruppe<br>anlegen</a>
            </div>";

        $h .= "</div></div></form>";
        return $h;
    } //68


    function ShowHtmlReportManagerEdit()
    {
        $p = new FisReport($_SESSION['rid']);
        $w = new FisWorkflow("", "rid", $_SESSION['rid']);
        $d = new FisDocument("", "rid", $_SESSION['rid']);
        $h = "";


        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=69&rid=" . $p->rid . "'>";

        $h .= "<div class='row'>";
        $h .= " <div class=\"col-lg-5\">
 
                    <div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Reportstammdaten </b></h4>";

        $h .= $p->EditPropertyAsTable();

        $h .= "</div></div>";


        $h .= " <div class=\"col-lg-5\">
 
                    <div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Artikeldaten </b></h4>";

        $h .= $p->EditArtikel();

        $h .= $p->ShowHtmlallocmaterial();

        $h .= "</div></div>";
        $h .= "<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h .= "<div>
           
            <div class='text-center'> ";

        $h .= "<br><button name=\"report_save\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Speichern</button>
            <hr>
            <a href=\"" . $this->GetLink() . "&action=68\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Abbrechen</a>
            <br>
            
            <hr>
            <br>
            <a href=\"#custom-width-modal5002\" data-toggle=\"modal\" data-target=\"#custom-width-modal5002\" class=\"btn btn-custom waves-effect waves-light\" >Neue Wartung<br>dokumentieren</a>
           
            <hr>
            <br>
            <a href=\"#custom-width-modal5001\" data-toggle=\"modal\" data-target=\"#custom-width-modal5001\" class=\"btn btn-custom waves-effect waves-light\" >Plan kopieren</a>
           
           
            </div>
            
            </div></div></div></div>";

        $h .= "<div class='row'>
                <div class=\"col-lg-12\">
                <div class=\"card-box\"><b>Verknüpfte Artikel</b></h4>";

        $h .= $p->ShowTableallocmaterial();

        $h .= "</div></div>";

        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h .= $d->GetDocumentList();
        $h .= "</div></div>";


        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h .= $w->GetWorkflowList();
        $h .= "</div></div>";
        $h .= "</div></form>";

        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=68&rid=" . $p->rid . "'>";
        $link = "<button name=\"new_report_docu\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Report dokumentieren</button>";
        $h .= asModal(5002, $p->NewEventAsTable(), $link, 60, "Neuen Report dokumentieren");
        $h .= "</form>";

        $p->rid = 0;
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=69&rid='>";
        $link = "<button name=\"new_report_add\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Neu anlegen</button>";
        $h .= asModal(5001, $p->EditPropertyAsTable(), $link, 60, "Neue Auswertegruppe anlegen");
        $h .= "</form>";


        return $h;
    }   //69


    function ShowHtmlReports()
    {

        $h = "";
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=70&rid='>";

        $h .= "<div class='row'>";
        $h .= " <div class=\"col-lg-10\">";

        $h .= "<div class=\"card-box table-responsive\">
             <h4 class=\"m-b-30 m-t-0 header-title\"><b>Auswertungszeiträume und Eigenschaften </b></h4>";
        $h .= "<div class='col-lg-4 card-box table-responsive'>";
        $h .= asTextField("Datum von", "report_datum_von", $_SESSION['report_datum_von'], 5, "");
        $h .= asTextField("Datum bis", "report_datum_bis", $_SESSION['report_datum_bis'], 5, "");
        $h .= asSelect2Box("Auswertegruppe", "report_report_group", GetReportName("select2", $_SESSION['report_report_group']), 7, "");
        $h .= asSelect2Box("Material", "report_material", GetArtikelName("select2", $_SESSION['report_material']), 7, "");
        $h .= asSelectBox("Ergebnisse einschränken", "report_limit", GetReportLimit("select", $_SESSION['report_limit']), 7, "");
        $h .= "</div>";
        $h .= "<div class='col-lg-4 card-box table-responsive'>";
        $h .= asSelectBox("Zeiträume vergleichen", "report_compare", GetYesNoList("select", $_SESSION['report_compare']), 5, "");
        $h .= asTextField("Vergleichszeitraum Datum von", "report_datum_von2", $_SESSION['report_datum_von2'], 5, "");
        $h .= asTextField("Vergleichszeitraum Datum bis", "report_datum_bis2", $_SESSION['report_datum_bis2'], 5, "");

        $h .= "</div>";
        $h .= "<div class='col-lg-4 card-box table-responsive'>";
        $h .= asSelectBox("Diagramm-Format", "report_sizeheight", GetReportSizeHeight("select", $_SESSION['report_sizeheight']), 7, "");
        $h .= asSelectBox("Diagramm-Format", "report_aspect", GetReportAspectRatio("select", $_SESSION['report_aspect']), 7, "");
        $h .= asSelectBox("Format der Zeitachsen", "report_format", GetReportFormat("select", $_SESSION['report_format']), 7, "");
        $h .= "</div>";
        $h .= "</div>";

        $h .= "<div class=\"card-box table-responsive\">
             <h4 class=\"m-b-30 m-t-0 header-title\"><b>Auswertungen auswählen </b></h4>";
        $h .= "<div class='col-lg-4 card-box table-responsive'>";
        $h .= "<h5 >Zeitlicher Verlauf </h5><br>";
        $h .= asSwitchery("Gesamt produzierte Menge", "report_1", $_REQUEST['report_1'], "00b19d", 2, "");
        $h .= asSwitchery("Menge Fertigware", "report_2", $_REQUEST['report_2'], "00b19d", 2, "");
        $h .= asSwitchery("Menge Nacharbeit", "report_3", $_REQUEST['report_3'], "00b19d", 2, "");
        $h .= asSwitchery("Menge Reparatur", "report_4", $_REQUEST['report_4'], "00b19d", 2, "");
        $h .= asSwitchery("Menge Upgrade", "report_5", $_REQUEST['report_5'], "00b19d", 2, "");
        $h .= asSwitchery("Menge Verschrottung", "report_6", $_REQUEST['report_6'], "00b19d", 2, "");
        $h .= asSwitchery("Menge Austausch", "report_7", $_REQUEST['report_7'], "00b19d", 2, "");

        $h .= "</div>";
        $h .= "<div class='col-lg-4 card-box table-responsive'>";
        $h .= "<h5><b>Zeitlicher Verlauf </b></h5><br>";
        $h .= asSwitchery("Q-Lage Gewährleistungfehlermeldungen / produzierte Menge", "report_10", $_REQUEST['report_10'], "00b19d", 2, "");
        $h .= asSwitchery("Q-Lage alle Fehlermeldungen/ produzierte Menge", "report_11", $_REQUEST['report_11'], "00b19d", 2, "");
        $h .= asSwitchery("Anzahl Gewährleistungsfehlermeldungen", "report_12", $_REQUEST['report_12'], "00b19d", 2, "");
        $h .= asSwitchery("Anzahl Fehlermeldungen", "report_13", $_REQUEST['report_13'], "00b19d", 2, "");

        $h .= "<br><h5><b>Kreisdiagramme </b></h5><br>";
        $h .= asSwitchery("Fehlerhäufigkeit Gewährleistungfehlermeldungen", "report_20", $_REQUEST['report_20'], "00b19d", 2, "");
        $h .= asSwitchery("Fehlerhäufigkeit alle Fehlermeldungen", "report_21", $_REQUEST['report_21'], "00b19d", 2, "");
        $h .= "</div>";
        $h .= "<div class='col-lg-4 card-box table-responsive'>";
        $h .= "<h5><b>Reparaturzeiten </b></h5><br>";
        $h .= asSwitchery("Reparaturzeit Kostenklärung", "report_30", $_REQUEST['report_30'], "00b19d", 2, "");
        $h .= asSwitchery("Reparaturzeiten Reparatur bis Versand", "report_31", $_REQUEST['report_31'], "00b19d", 2, "");
        $h .= asSwitchery("Gesamt Reparaturzeit", "report_32", $_REQUEST['report_32'], "00b19d", 2, "");
        $h .= asSwitchery("GWL Reparaturzeit Kostenklärung", "report_33", $_REQUEST['report_33'], "00b19d", 2, "");
        $h .= asSwitchery("GWL Reparaturzeiten Reparatur bis Versand", "report_34", $_REQUEST['report_34'], "00b19d", 2, "");
        $h .= asSwitchery("GWL Gesamt Reparaturzeit", "report_35", $_REQUEST['report_35'], "00b19d", 2, "");

        $h .= "</div>";
        $h .= "</div>";


        $v = "<div class='card-box table-responsive'><h5 class=\"m-b-30 m-t-0 header-title\">Vergleich Zeitraum " . $_SESSION['report_datum_von'] . " bis " . $_SESSION['report_datum_bis'] . " und Zeitraum " . $_SESSION['report_datum_von2'] . " bis " . $_SESSION['report_datum_bis2'] . "</h5><div class='col-lg-6'>";

        if ($_REQUEST['report_1'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm1", "Es werden Fertigungsdaten geladen...", "report.php", "datatype=fids&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=1");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm1_2", "Es werden Fertigungsdaten geladen...", "report.php", "datatype=fids&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=2");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_2'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm2", "Es werden Fertigungsdaten für Neugeräte geladen...", "report.php", "datatype=fids0&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=3");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm2_2", "Es werden Fertigungsdaten für Neugeräte geladen...", "report.php", "datatype=fids0&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=4");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_3'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm3", "Es werden Nacharbeitsarbeiten geladen...", "report.php", "datatype=fids1&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=5");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm3_2", "Es werden Nacharbeitsarbeiten geladen...", "report.php", "datatype=fids1&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=6");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_4'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm4", "Es werden Reparaturfertigungsmeldungen geladen...", "report.php", "datatype=fids2&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=7");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm4_2", "Es werden Reparaturfertigungsmeldungen geladen...", "report.php", "datatype=fids2&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=8");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_5'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm5", "Es werden Updrade geladen...", "report.php", "datatype=fids3&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=9");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm5_2", "Es werden Updrade geladen...", "report.php", "datatype=fids3&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=10");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_6'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm6", "Es werden Verschrottungen geladen...", "report.php", "datatype=fids4&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=11");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm6_2", "Es werden Verschrottungen geladen...", "report.php", "datatype=fids4&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=12");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_7'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm7", "Es werden Gerätetausche geladen...", "report.php", "datatype=fids5&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=13");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm7_2", "Es werden Gerätetausche geladen...", "report.php", "datatype=fids5&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=14");
                $h .= "</div></div>";
            }
        }

        if ($_REQUEST['report_10'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm10", "Q-Lage innerhalb der Gewährleistung wird berechnet", "report.php", "datatype=qgwl&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=15");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm10_2", "Q-Lage innerhalb der Gewährleistung wird berechnet", "report.php", "datatype=qgwl&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=16");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_11'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm11", "Q-Lage wird berechnet", "report.php", "datatype=q&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=17");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm11_2", "Q-Lage wird berechnet", "report.php", "datatype=q&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=18");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_12'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm12", "Gewährleistungsfehlermeldungen werden gezählt", "report.php", "datatype=countgwlfms&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=19");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm12_2", "Gewährleistungsfehlermeldungen werden gezählt", "report.php", "datatype=countgwlfms&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=20");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_13'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm13", "Fehlermeldungen werden gezählt", "report.php", "datatype=countfms&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=21");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm13_2", "Fehlermeldungen werden gezählt", "report.php", "datatype=countfms&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=22");
                $h .= "</div></div>";
            }
        }


        if ($_REQUEST['report_20'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm20", "Es werden Gewährleistungen untersucht...", "report.php", "datatype=failgwl&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=23");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm20_2", "Es werden Gewährleistungen untersucht...", "report.php", "datatype=failgwl&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=24");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_21'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm21", "Es werden Fehlermeldungen durchsucht...", "report.php", "datatype=fail&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=25");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm21_2", "Es werden Fehlermeldungen durchsucht...", "report.php", "datatype=fail&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=26");
                $h .= "</div></div>";
            }
        }


        if ($_REQUEST['report_30'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm30", "Es werden Zeiten der Kostenklärung berechnet...", "report.php", "datatype=t0&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=30");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm30_2", "Es werden Zeiten der Kostenklärung berechnet...", "report.php", "datatype=t0&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=31");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_31'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm31", "Es werden Reparaturzeiten berechnet...", "report.php", "datatype=t1&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=32");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm31_2", "Es werden Reparaturzeiten berechnet...", "report.php", "datatype=t1&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=33");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_32'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm32", "Es werden Gesamtzeiten berechnet...", "report.php", "datatype=t2&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=34");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm32_2", "Es werden Gesamtzeiten berechnet...", "report.php", "datatype=t2&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=35");
                $h .= "</div></div>";
            }
        }

        if ($_REQUEST['report_33'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm33", "Es werden Zeiten der Kostenklärung berechnet...", "report.php", "datatype=t10&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=36");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm33_2", "Es werden Zeiten der Kostenklärung berechnet...", "report.php", "datatype=t10&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=37");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_34'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm34", "Es werden Reparaturzeiten berechnet...", "report.php", "datatype=t11&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=38");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm34_2", "Es werden Reparaturzeiten berechnet...", "report.php", "datatype=t11&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=39");
                $h .= "</div></div>";
            }
        }
        if ($_REQUEST['report_35'] == "on") {
            if ($_REQUEST['report_compare']) {
                $h .= $v;
            }
            $h .= asJSLoader("report_diagramm35", "Es werden Gesamtzeiten berechnet...", "report.php", "datatype=t12&datum1=" . $_SESSION['report_datum_von'] . "&datum2=" . $_SESSION['report_datum_bis'] . "&id=40");
            if ($_REQUEST['report_compare']) {
                $h .= "</div><div class='col-lg-6'>";
                $h .= asJSLoader("report_diagramm35_2", "Es werden Gesamtzeiten berechnet...", "report.php", "datatype=t12&datum1=" . $_SESSION['report_datum_von2'] . "&datum2=" . $_SESSION['report_datum_bis2'] . "&id=41");
                $h .= "</div></div>";
            }
        }


        $h .= "<div class=\"card-box table-responsive\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Auswertungsergebnisse </b></h4>";
        $h .= "</div></div>";
        $h .= "<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";


        $h .= "<div class='text-center'> ";

        $h .= asTextField("Volltext", "report_search_txt", $_SESSION['report_search_txt'], 12, "");

        $_SESSION['focus'] .= "$(\"#report_search_txt\").focus();";


        $h .= asSelectBox("Ansicht", "reporttype", GetReportTypes("select", $_SESSION['reporttype']), 12, "");


        $h .= asSelectBox("auch gelöschte anzeigen", "report_search_lokz", GetYesNoList("select", $_SESSION['report_search_lokz']), 12, "");
        // $h.=asTextField("Max. Treffer","fhm_search_hits",$r->limit,12);

        $h .= "<br><button name=\"report_report_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Auswerten</button>
            <hr><button name=\"report_report_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            </div>";

        $h .= "</div></div></form>";

        $_SESSION['endscripts'] .= "
         \$('#report_datum_von').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
         \$('#report_datum_bis').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: \"yyyy-mm-dd\",
          
            });   
         \$('#report_datum_von2').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
         \$('#report_datum_bis2').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: \"yyyy-mm-dd\",
           
            });       
        ";
        return $h;
    }


    function ShowHtmlFaufView()
    {
        $w = new FisFauf(0);
        $h = "";
        $w->SetFaufDefaults();
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=71&faufid='>";
        $link = "<button name=\"new_fauf_add\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Neu anlegen</button>";
        $h .= asModal(5001, $w->EditPropertyAsTable(), $link, 60, "Neuen Fertigungsauftrag anlegen");
        $h .= "</form>";


        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=" . $this->action . "&faufid='>";
        $h .= "<div class=\"col-lg-10\">
                <div class=\"card-box table-responsive\">";
        $w->lokz = $_SESSION['fauf_search_lokz'];
        $w->faufstate = $_SESSION['fauf_search_faufstate'];
        $w->fauftype = $_SESSION['fauf_search_fauftype'];
        $h .= $w->ShowHtmlAllAsTable(0, $_SESSION['fauf_search_hits'], $_SESSION['fauf_search_txt'], 1);

        $h .= "</div></div>";
        $h .= "<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";


        $h .= "<div class='text-center'> ";

        $h .= asTextField("Volltext", "fauf_search_txt", $_SESSION['fauf_search_txt'], 12, "");

        $_SESSION['focus'] .= "$(\"#fauf_search_txt\").focus();";

        $h .= asSelectBox("Status", "fauf_search_faufstate", GetFaufStates("select", $_SESSION['fauf_search_faufstate']), 12, "");
        $h .= asSelectBox("Typ", "fauf_search_fauftype", GetFaufType("select", $_SESSION['fauf_search_fauftype']), 12, "");
        $h .= asSelectBox("auch gelöschte anzeigen", "fauf_search_lokz", GetYesNoList("select", $_SESSION['fauf_search_lokz']), 12, "");
        $h .= asTextField("Max. Treffer", "fauf_search_hits", $w->limit, 12);

        $h .= "<br><button name=\"fauf_search\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Suchen</button>
            <hr><button name=\"fauf_reset\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Anzeigen<br>zurücksetzen</button>
            <br><hr><a href=\"#custom-width-modal5001\" data-toggle=\"modal\" data-target=\"#custom-width-modal5001\" class=\"btn btn-custom waves-effect waves-light\" >Neuen Fertigungs-<br>uftrag anlegen</a>
            </div>";

        $h .= "</div></div></form>";
        return $h;
    } //71

    function ShowHtmlFaufEdit()
    {
        $p = new FisFauf($_SESSION['faufid']);
        $w = new FisWorkflow("", "faufid", $_SESSION['faufid']);
        $d = new FisDocument("", "faufid", $_SESSION['faufid']);
        $h = "";


        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=72&faufid=" . $p->faufid . "'>";


        $link = "<a href=\"#\"  class=\"btn btn-custom waves-effect waves-light\" data-toggle=\"modal\" onclick='ProveConfigSettings()'>übernehmen</a>";
        $h .= asModal(5002, $p->EditConfig(), $link, 60, "Konfiguration anpassen");

        $link = "<a href=\"#\"  class=\"btn btn-custom waves-effect waves-light\" data-toggle=\"modal\" onclick='ProveProcessSettings()'>übernehmen</a>";
        $h .= asModal(5003, $p->EditProcess(), $link, 60, "Neuen Arbeitsplan und Stückliste anpassen");

        $link = "<a href=\"#\"  class=\"btn btn-custom waves-effect waves-light\" data-toggle=\"modal\" onclick='ProveProfilSettings()'>Übernehmen</a>";
        $h .= asModal(5004, $p->EditProfil(), $link, 60, "Neuen Profil bearbeiten");

        $p->SetSessionScript();
        $p->JsonEditor();
        $h .= $p->JsonScripts();


        $h .= "<div class='row'>";
        $h .= " <div class=\"col-lg-5\">
                    <div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Fertigungsauftragsdaten für Auftrag-Nr. " . $_SESSION['faufid'] . "</b></h4>";

        $h .= $p->EditPropertyAsTable();

        $h .= "</div></div>";

        $h .= " <div class=\"col-lg-5\">
                <div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Auftragskonfiguration </b></h4>";
        $h .= $p->ConfigAsTable();
        $h .= "</div>";
        $h .= "<div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Profil</b></h4>";
        $h .= $p->ProfilAsTable();
        $h .= "</div>";

        $h .= "<div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Plankosten </b></h4>";
        $h .= $p->PlanCostsAsTable();
        $h .= "</div>";

        $h .= "<div class=\"card-box\">
                       <h4 class=\"m-b-30 m-t-0 header-title\"><b>Istkosten </b></h4>";
        $h .= $p->ActualCostsAsTable();
        $h .= "</div>";


        $h .= "</div>";



        $h .= "<div class=\"col-lg-2\">
              <div class=\"card-box\">
                <h4 class=\"m-b-30 m-t-0 header-title\"><b>Funktionen </b></h4>";
        $h .= "<div>
           
            <div class='text-center'> ";

        $h .= "<br><button name=\"fauf_save\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Speichern</button>
            <hr>
            <a href=\"" . $this->GetLink() . "&action=71\" class=\"btn btn-danger waves-effect waves-light\" onclick='refresh_site()'>Abbrechen</a>
            <br>
            <hr>
            <a href=\"#custom-width-modal5002\" data-toggle=\"modal\" data-target=\"#custom-width-modal5002\" class=\"btn btn-custom waves-effect waves-light\" >Konfiguration anpassen</a>
            <hr>
            <a href=\"#custom-width-modal5003\" data-toggle=\"modal\" data-target=\"#custom-width-modal5003\" class=\"btn btn-custom waves-effect waves-light\" >Arbeitsplan & Stückliste</a>
            <hr>
            <a href=\"#custom-width-modal5004\" data-toggle=\"modal\" data-target=\"#custom-width-modal5004\" class=\"btn btn-custom waves-effect waves-light\" >Druckeinstellungen</a>
           
           <br><button name=\"fauf_reload_defaults\"  type=\"submit\" class=\"btn btn-inverse waves-effect waves-light\" onclick='refresh_site()'>Default-Einstellungen laden</button>
            <hr>
           
            ";
        $h .= "<hr><a href=\"#\" class=\"btn btn-inverse waves-effect waves-light\" onclick=\"EditWithoutEditor();\">Einstellungen ohne<br>Editor bearbeiten</a>";
        $h .= "<hr><a href=\"#\" class=\"btn btn-inverse waves-effect waves-light\" onclick=\"EditWithEditor();\">Einstellungen mit<br>Editor bearbeiten</a>";

        $h .= "<hr>
            <br>
            <a href=\"#custom-width-modal5001\" data-toggle=\"modal\" data-target=\"#custom-width-modal5001\" class=\"btn btn-custom waves-effect waves-light\" >Fertigungsauftrag<br>kopieren</a>
           
           
            </div>
            
            </div></div></div></div>";

        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Auftragsarbeitsplan und Stückliste</b></h4>";
        $h .= $p->GetProcessList();
        $h .= "</div></div>";

        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Dokumente</b></h4>";
        $h .= $d->GetDocumentList();
        $h .= "</div></div>";


        $h .= "<div class=\"col-lg-12\">
                    <div class=\"card-box\">
                        <h4 class=\"m-b-30 m-t-0 header-title\"><b>Änderungen</b></h4>";
        $h .= $w->GetWorkflowList();
        $h .= "</div></div>";
        $h .= "</div></form>";


        $p->SetFaufDefaults();
        $h .= "<form id='form_edit' method='post' enctype='multipart/form-data' action='" . $this->GetLink() . "&action=72&faufid='>";
        $link = "<button name=\"new_report_add\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light\" onclick='refresh_site()'>Neu anlegen</button>";
        $h .= asModal(5001, $p->EditPropertyAsTable(), $link, 60, "Neuen Fertigungsauftrag anlegen");
        $h .= "</form>";


        return $h;
    }   //69
}