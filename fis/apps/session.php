<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 26.12.16
 * Time: 09:49
 */


function AddSessionMessage($type,$text,$header="Benachrichtigung"){

    $_SESSION['e'][]=array('type'=>$type,'text'=>$text,'header'=>$header);
}

function SessionToaster(){
    $e= $_SESSION['e'];
    if (count($e)>0) {
        foreach ($e as $item) {
            if (strlen($item['type'])>0) {
                $n[] = "toastr[\"" . $item['type'] . "\"](\"" . ($item['text']) . "\", \"" . ($item['header']) . "\");";
            }
        }
        return implode("\n",$n);
    }
    $_SESSION['e']=array();
}

function SessionToasterMessage(){
    $e= $_SESSION['e'];
    $h="";
    if (count($e)>0) {
        foreach ($e as $item) {
            if (strlen($item['type'])>0) {
                $h.="<div class='card-box'>".$item['header']."<br>";
                $h.="<span class='text-".$item['type']."'>".$item['text']."</span></div>";
            }
        }
        return $h;
    }
}



if (isset($_REQUEST['sess'])){
    $sessionFile = ini_get("session.save_path") . "/sess_" .$_REQUEST['sess'];
    if (!file_exists($sessionFile))
    {
        //AddSessionMessages($_SESSION,"danger","No valid Session-File found. Please authentificate again.");
        header ("Location: ../index.php?bks=".$_SESSION['classifiedDirectoryID']."&logout=1");
    }

    session_start();

    $_SESSION['history'][1]=$_SESSION['history'][0];
    $_SESSION['history'][0]=$_SERVER['PHP_SELF'];


    $_SESSION['sess']=$_REQUEST['sess'];

    if (strlen($_SESSION['browserType'])==0) {
        $_SESSION['browserType']=$_SERVER['HTTP_USER_AGENT'];
        AddSessionMessage("info","Internet-Browser erkannt.","");
    }
    if (strlen($_SESSION['remoteIP'])==0) {
        $_SESSION['remoteIP']=$_SERVER['REMOTE_ADDR'];
        AddSessionMessage("success","Anmeldung erfolgreich.","");
    }

    if (strlen($_REQUEST['useridchange'])>0) {
        $_SESSION['userid']=$_REQUEST['useridchange'];
        AddSessionMessage("success","Anmeldung erfolgreich.","");
    }

    if (strlen($_SESSION['classifiedDirectoryID'])==0) {
        $_SESSION['classifiedDirectoryID']=1501;


    }

    if (intval($_SESSION['userid']) <= 0) {
        //AddSessionMessages($_SESSION, "danger", "Your account-ID is not guilty. Please authentificate again.");
        header("Location: ../index.php?bks=" . $_SESSION['classifiedDirectoryID'] . "&logout=4");
    }
  /*
        if ($_SERVER['REMOTE_ADDR'] <> $_SESSION['remoteIP']) {
            //AddSessionMessages($_SESSION, "danger", "Remote-IP was changed.<code>" . $_SERVER['REMOTE_ADDR'] . " <> " . $_SESSION['remoteIP'] . "</code> Please authentificate again.");
            header("Location: ../index.php?bks=" . $_SESSION['classifiedDirectoryID'] . "&logout=2&ip=".$_SESSION['remoteIP']);
        }
        if ($_SERVER['HTTP_USER_AGENT'] <> $_SESSION['browserType']) {
            //AddSessionMessages($_SESSION, "danger", "Browser type was changed.<code>" . $_SERVER['HTTP_USER_AGENT'] . " <> " . $_SESSION['browserType'] . "</code> Please authentificate again.");
            header("Location: ../index.php?bks=" . $_SESSION['classifiedDirectoryID'] . "&logout=3");
        }

        if (intval($_SESSION['userid']) <= 0) {

            //AddSessionMessages($_SESSION, "danger", "Your account-ID is not guilty. Please authentificate again.");
            header("Location: ../index.php?bks=" . $_SESSION['classifiedDirectoryID'] . "&logout=4");
        }
        if (strlen($_SESSION['sessionID']) == 0) {
            //AddSessionMessages($_SESSION, "danger", "Your session-ID is not guilty. Please authentificate again.");
            header("Location: ../index.php?bks=" . $_SESSION['classifiedDirectoryID'] . "&logout=5");
        }
   */
} else {
    //AddSessionMessages($_SESSION,"danger","No valid Session-Key found. Please authentificate again.");
    header ("Location: ../index.php?bks=".$_SESSION['classifiedDirectoryID']."&logout=6&action=".$_SESSION['action']."&item=".$_SESSION['item']);
}