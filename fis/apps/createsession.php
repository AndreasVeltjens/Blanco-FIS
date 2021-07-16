<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 26.12.16
 * Time: 10:48
 */

include ("../apps/function.php");
$u=new ifisUser(0);
if ($u->CheckUserLogin($_REQUEST['username'],$_REQUEST['password'])) {

    ini_set("session.use_cookies", 0);
    session_name("sess");
    session_start();
    $sessionID = session_id();
    $classifiedDirectoryID = 1;
    $_SESSION['userid'] = $u->GetUserID();
    $_SESSION['browserType'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['remoteIP'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['classifiedDirectoryID'] = $classifiedDirectoryID;

    $_SESSION['sess'] = $sessionID;
    $_SESSION['sessionID'] = $sessionID;

    mail("andreas.veltjens@gmail.com", "ALBAFIS-login  " . $_SERVER['REMOTE_HOST'] . " " . $_REQUEST['username']. " ", " " . date("Y-m-d - H:i ", time()) .
        "\nE-Mail: " . $_REQUEST['username'] . "\n " . $text .
        "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR'] .
        "\nBKS:" . $_REQUEST['bks'] .
        "\nUSER:" . $_SESSION['userid'] ." ".  $_SESSION['email'] ." " . $_SESSION['name'] .
        "\nSESS:" . $_SESSION['sess']);

    $_SESSION['e'][0] = array("success", "Login erfolgreich", "Info");

    session_commit();
    //echo "success".$u->userid;
    $u->SaveLogin();
    header(sprintf("Location: %s", "../apps/start.php?sess=$sessionID&useridchange=".$u->GetUserID() ));

} else{
    //echo "logout".$u->userid.$_REQUEST['username']."-".$_REQUEST['password'];
    header(sprintf("Location: %s", "../index.php?logout=1"));
}