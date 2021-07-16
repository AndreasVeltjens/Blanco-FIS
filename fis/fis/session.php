<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 13.10.18
 * Time: 19:41
 */

function ResetSession(){
    unset($_SESSION['material']);
    unset($_SESSION['materialgroup']);
    unset($_SESSION['kontierung']);
    unset($_SESSION['action']);
    unset($_SESSION['version']);
    unset($_SESSION['fid']);
    unset($_SESSION['idk']);
    unset($_SESSION['rueckid']);
    unset($_SESSION['fmid']);
    unset($_SESSION['fmstate']);
    unset($_SESSION['equip']);
    unset($_SESSION['pid']);
    unset($_SESSION['rid']);
    unset($_SESSION['material_search_kontierung']);
    unset($_SESSION['material_search_group']);
    unset($_SESSION['material_search_productfamily']);
    unset($_SESSION['userid']);
    unset($_SESSION['weid']);
    unset($_SESSION['faufid']);
    unset($_SESSION['fid_search_material']);
    unset($_SESSION['div']);


}

function SetSessionVariables(){
    $_SESSION['action'] = $_REQUEST['action'];
    if (isset($_REQUEST['wp'])) $_SESSION['wp'] = $_REQUEST['wp'];
    if (isset($_GET['wp'])) $_SESSION['wp'] = $_GET['wp'];
    if ($_SESSION['wp']=="") {$_SESSION['wp']=0;}

    if (isset($_REQUEST['equip']))  $_SESSION['equip'] = $_REQUEST['equip'];
    if (isset($_REQUEST['pid']))  $_SESSION['pid'] = $_REQUEST['pid'];
    if (isset($_REQUEST['materialgroup'])) $_SESSION['materialgroup'] = $_REQUEST['materialgroup'];
    if (isset($_REQUEST['kontierung'])) $_SESSION['kontierung'] = $_REQUEST['kontierung'];

    if (isset($_REQUEST['material'])){
        $_SESSION['fid_search_material']= $_REQUEST['material'];
        $_SESSION['material'] = $_REQUEST['material'];
    }

    if (isset($_REQUEST['version'])) $_SESSION['version'] = $_REQUEST['version'];
    if (isset($_REQUEST['fid'])) $_SESSION['fid'] = $_REQUEST['fid'];
    if (isset($_REQUEST['faufid'])) $_SESSION['faufid'] = $_REQUEST['faufid'];
    if (isset($_REQUEST['rid'])) $_SESSION['rid'] = $_REQUEST['rid'];
    if (isset($_REQUEST['rueckid'])) $_SESSION['rueckid'] = $_REQUEST['rueckid'];
    if (isset($_REQUEST['idk'])) $_SESSION['idk'] = $_REQUEST['idk'];
    if (isset($_REQUEST['fmid'])) $_SESSION['fmid'] = $_REQUEST['fmid'];
    if (isset($_REQUEST['fmstate'])) $_SESSION['fmstate'] = $_REQUEST['fmstate'];
    if (isset($_REQUEST['fidstate'])) $_SESSION['fidstate'] = $_REQUEST['fidstate'];

    if (isset($_REQUEST['kontierung'])) $_SESSION['material_search_kontierung'] = $_REQUEST['kontierung'];
    
    if (strlen($_REQUEST['material_search_kontierung'])>0) $_SESSION['material_search_kontierung'] = $_REQUEST['material_search_kontierung'];

    if (isset($_REQUEST['materialgroup'])) $_SESSION['material_search_group'] = $_REQUEST['materialgroup'];
    if (isset($_REQUEST['material_search_productfamily'])) $_SESSION['material_search_productfamily'] = $_REQUEST['material_search_productfamily'];

    if (isset($_REQUEST['weid'])) $_SESSION['weid'] = $_REQUEST['weid'];
    if (isset($_REQUEST['userid'])) $_SESSION['userid'] = $_REQUEST['userid'];
    if (isset($_REQUEST['diagrammdataset'])) $_SESSION['diagrammdataset'] = $_REQUEST['diagrammdataset'];
}


if (isset($_REQUEST['sess']) && strlen($_REQUEST['sess'])>0 ) {
    $sessionFile = ini_get("session.save_path") . "/sess_" . $_REQUEST['sess'];
    if (!file_exists($sessionFile)) {
        AddSessionMessage("error","Benutzeranmeldung fehlerhaft.","Benachrichtigung");
        header("Location: ./index.php?wp=" . $_REQUEST['wp']);
    }
    session_start();
    $_SESSION['datatable']=0;
    $_SESSION['script_datatable']="";
    $_SESSION['script']="";
    $_SESSION['endscripts']="";
    $_SESSION['loadlayout']="";
    $_SESSION['range']="";
    $_SESSION['focus']="";
    $_SESSION['jsoneditor']="";
    $_SESSION['flot']="";
    $_SESSION['functions']="";
    $_SESSION['morris']="";
    $_SESSION['morrisfunction']="";
    $_SESSION['sql']="";
    $_SESSION['gauge']="";
    $_SESSION['summernote']="";
    $_SESSION['report'] = "";
    $_SESSION['canvas'] = 0;
    $_SESSION['div'] = 0;

    $_SESSION['sess'] = $_REQUEST['sess'];

    if (count($_SESSION['logon_user'])){
        foreach ($_SESSION['logon_user'] as $item=>$s){
            if (isset($s['userid'])){
                if ($s['userid']<>0) {

                    $x = new FisUser($s['userid']);
                    $x->LoadSessionDuration();

                    if (intval($_SESSION['autologout'])==0 or $_SESSION['autologout']=="") {
                        $_SESSION['autologout'] = (60 * 60 * 8);
                    }
                    if (intval($_SESSION['autoinactive'])==0 or $_SESSION['autoinactive']=="") {
                        $_SESSION['autoinactive'] = (60*60);
                    }

                    if ($x->lastactiontime < $_SESSION['autoinactive'] ) {

                        if (($_SESSION['active_user']) == $item) {
                            $_SESSION['active_user'] = "";
                            $_SESSION['active_user_right'] = "";
                            ResetSession();
                        }
                        AddSessionMessage("info", "Benutzer automatisch inaktiv gesetzt, da keine Berechtigung zum Anzeigen dieses Arbeitsplatzes <code> " . $_SESSION['wp'] . " </code>vorliegt.", "Benutzerabmeldung");
                    }

                    /**
                    if ($x->sessiontime > ($_SESSION['autologout'])  ) {
                        $_SESSION['logon_user'][$item]="";
                        if (($_SESSION['active_user']) == $item) {
                            $_SESSION['active_user'] = "";
                            $_SESSION['active_user_right'] = "";
                            ResetSession();
                        }
                        AddSessionMessage("warning", "Benutzer automatisch abgemeldet.", "Logoff");
                    }
                     */
                }else{
                    ResetSession();
                }
            }
        }
    }else{
        $_SESSION['active_user']="";
        $_SESSION['active_user_right']="";

        $_SESSION['action']=0;

        ResetSession();
    }

// login with password
    if (isset($_REQUEST['logon_user_login'])) {
        AddSessionMessage("info", "Password wird geprüft.", "Passwordabfrage");
        $u = new FisUser($_REQUEST['active_userid']);
        if ($u->CheckPassword($_REQUEST['password' . $_REQUEST['active_userid']]) && strlen($_REQUEST['password' . $_REQUEST['active_userid']]) > 0 && $_REQUEST['active_userid'] > 0) {
            $_SESSION['active_user'] = $_REQUEST['active_userid'];
            $x = new FisUser($_REQUEST['active_userid']);
            $x->UpdateLogon();
            $_SESSION['active_user_right'] = $x->GetUserRight();
            $_SESSION['logon_user'][$_REQUEST['active_userid']] = array("userid" => $_REQUEST['active_userid']);
            unset($_REQUEST['logoff_userid']);
            if ($_REQUEST['active_userid_action' . $_REQUEST['active_userid']] > 0) {
                $action = $_REQUEST['active_userid_action' . $_REQUEST['active_userid']];
                $_REQUEST['action'] = $action;
                $_GET['action'] = $action;
                $_REQUEST['userid'] = $_REQUEST['active_userid'];
                AddSessionMessage("info", "Freigabe der Einstellungen <code> $action </code>", "Benachrichtigung");
            }
            AddSessionMessage("success", "Password richtig.", "Benachrichtigung");
        } else {
            $_REQUEST['logoff_userid'] = $_REQUEST['active_userid'];
            AddSessionMessage("error", "Password falsch. Mitarbeiter wird abgemeldet.", "Benachrichtigung");
        }
    } else {

        //login without password
        if (isset($_REQUEST['active_userid']) && $_REQUEST['active_userid'] > 0) {
            $_SESSION['active_user'] = $_REQUEST['active_userid'];
            $x = new FisUser($_REQUEST['active_userid']);
            $x->UpdateLogon();
            $_SESSION['active_user_right'] = $x->GetUserRight();
            $_SESSION['logon_user'][$_REQUEST['active_userid']] = array("userid" => $_REQUEST['active_userid']);

            AddSessionMessage("success", "Benutzeranmeldung erfolgreich.", "Benachrichtigung");
        }
    }
    if (isset($_REQUEST['logoff_userid'])) {
        $_SESSION['active_user'] = "";
        ResetSession();
        session_destroy();
        if (count($_SESSION['logon_user'])){
            foreach ($_SESSION['logon_user'] as $item=>$s){
                if ($_REQUEST['logoff_userid']==$s['userid']){
                    $_SESSION['active_user']="";

                    unset ($_SESSION['logon_user'][$item]);
                    ResetSession();

                    AddSessionMessage("warning","Benutzer abgemeldet.","Benachrichtigung");

                }
            }
        }

    }

    $_SESSION['datatable']=0;
    SetSessionVariables();



}else{
    ini_set("session.use_cookies", 0);
    session_name("sess");
    session_start();
    $sessionID = session_id();
    $_SESSION['browserType'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['remoteIP'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['sess'] = $sessionID;
    $_SESSION['sessionID'] = $sessionID;
    session_commit();
    AddSessionMessage("info","Websession <code> $sessionID </code> angelegt.","Benachrichtigung");
    header("Location: ./index.php?sess=$sessionID&wp=" . $_REQUEST['wp']);
}

