<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 2019-02-14
 * Time: 10:27
 */

if (isset($_POST['file'])) {
    $file = '../tmpupload/' . $_POST['file'];
    if (file_exists($file)) {
        unlink($file);
    }
}