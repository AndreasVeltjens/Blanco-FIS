<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 2019-03-18
 * Time: 08:26
 */

include_once("../config/setup.php");

if (!isset($_GET['printer'])) {
    echo "Printer can not be null !";
} else {

    $folder = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . "print/" . $_GET['printer'];

    if (!is_dir($folder)) {
        echo " printer directory " . $folder . " does not exist";
    } else {

        $files = glob($folder . "/*.*");

        if (is_array($files)) {
            if (isset($_GET['info'])) {
                foreach ($files as $value) {
                    echo $value . " ";
                }
            } else {
                if (is_file($files[0])) {
                    $content = file_get_contents($files[0]);
                    time_nanosleep(0, 50);
                    unlink($files[0]);
                    echo $content;
                } else {
                    echo 0;
                }
            }
        } else {
            echo 0;
        }
    }
}
exit;