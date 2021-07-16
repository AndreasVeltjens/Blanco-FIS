<?php 
if(isset($_POST['file'])){
    $file = '../tmpupload/' . $_POST['file'];
    if(file_exists($file)){
        unlink($file);
    }
}
?>