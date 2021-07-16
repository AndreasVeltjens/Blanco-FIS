<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 08.10.18
 * Time: 21:54
 */

include("./config.php");
include("./configfunction.php");

$_SESSION['autologoff']=2880000;
$_SESSION['autologout']=2880000;
$_SESSION['autoinactive']=1;


switch ($_SESSION['importfunction']){
    case "save_fis9_import_FMID_data":
        {
            $_SESSION['importtime']=25;
            $f= new FisUpdate();
            $message=$f->ImportFMIDfromFis9();
            break;
        }
    case "SetFinalFMIDfromFis9":
        {
            $_SESSION['importtime']=25;
            $f= new FisUpdate();
            $message=$f->SetFinalFMIDfromFis9();
            break;
        }
    case "setWpFolder":
        {
            $_SESSION['importtime']=10;
            $_SESSION['importcount']=100;
            $f= new FisUpdate();
            $message=$f->CreateWorkplaceFolder();
            $_SESSION['importitem']=101;
            break;
        }
    case "CreateWorkplacePrinter":
        {
            $_SESSION['importtime']=10;
            $f= new FisUpdate();
            $message=$f->CreateWorkplacePrinter();
            break;
        }
    case "save_import_sapproperties":
        {
            $_SESSION['importtime']=10;
            $_SESSION['importcount']=100;
            $f= new FisUpdate();
            $message=$f->ResultSAPEAN13();
            $_SESSION['importitem']=101;
            break;
        }

    case "fis_update":
        {
            $_SESSION['importtime']=10;
            $_SESSION['importcount']=100;
            $f= new FisUpdate();
            $message=$f->FisUpdate();
            $_SESSION['importitem']=101;
            break;
        }
    case "save_fis9_import_material_versionen":
        {
            $_SESSION['importtime']=1;
            $f= new FisUpdate();
            $message=$f->ImportArtikeldatafromFis9();

            break;
        }
    case "ImportArtikelPicturesfromFis9":
        {
            $_SESSION['importtime']=5;
            $f= new FisUpdate();
            $message=$f->ImportArtikelPicturesfromFis9();

            break;
        }
    case "SetEventfromFis9":
        {
            $_SESSION['importtime']=25;
            $f= new FisUpdate();
            $message=$f->SetEventfromFis9();

            break;
        }

    case "ChangePCNANumber":
        {
            $_SESSION['importtime']=25;
            $f= new FisUpdate();
            $message=$f->ChangePCNANumber($_SESSION['order_old'],$_SESSION['order_new']);

            break;
        }

    case "ChangePCNAGWLNumber":
        {
            $_SESSION['importtime']=25;
            $f= new FisUpdate();
            $message=$f->ChangePCNAGWLNumber($_SESSION['order_old'],$_SESSION['order_new']);

            break;
        }
    case "UpdateVersionPrintSettings":
        {
            $_SESSION['importtime']=5;
            $f= new FisUpdate();
            $message=$f->UpdateVersionPrintSettings();

            break;
        }




}
if ($_SESSION['importtime']=="") $_SESSION['importtime']=1;

if ($_SESSION['importcount']==0){ $_SESSION['importcount']=1;}
if ($_SESSION['importitem']==0){ $_SESSION['importitem']=0;}


$rate=round($_SESSION['importitem']/$_SESSION['importcount']*100,0);

if ($rate>100) {
    $_SESSION['importcount']=0;
    $_SESSION['importitem']=0;
    $_SESSION['importfunction']="";
    $_SESSION['importtime']=5;
    $updatetogo = $_SESSION['importtime']."; URL=./" . $_SESSION['wp'] . ".php?sess=" . $_SESSION['sess'] . "&action=100&wp=" . $_SESSION['wp'];
    AddSessionMessage("success", $_SESSION['importfunction'], "Update erfolgreich");
}else{
    $updatetogo = $_SESSION['importtime']."; URL=./importing.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'];
}

?>
<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">

    <meta http-equiv="refresh" content="<?php echo $updatetogo; ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="../layout-2_blue/assets/images/favicon.ico">

    <title>Import FIS Data</title>

    <!-- Notification css (Toastr) -->
    <link href="../layout-2_blue/assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    <link href="../layout-2_blue/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../layout-2_blue/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <link href="../layout-2_blue/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="../layout-2_blue/assets/js/modernizr.min.js"></script>

    </head>
    <body>
    <div class="text-center logo-alt-box">
        <a href="../index.php" class="logo"><span><span>FIS</span></span></a>
        <h5 class="text-muted m-t-0"><?php echo FIS_COMPANY; ?></h5>
    </div>

    <div class="wrapper-page">

        <div class="m-t-30 card-box">
            <div class="text-center">
                <h4 class="text-uppercase font-bold m-b-0">Import Data</h4>

                <div class="user-thumb m-t-20">
                    <i class="fa fa-lock fa-5x"></i>
                </div>
                <p class="text-muted m-t-10">
                    <?php echo FIS_COMPANY_SLOGAN; ?>
                </p>

            </div>
            <div class="panel-body">
                <?php echo $_SESSION['importfunction'];

                ?>

                <div class="progress">
                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $rate; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $rate; ?>%;">
                        <span class="sr-only"><?php echo $rate; ?>% Complete</span>
                    </div>
                </div>
                <div>
                <a href="./index.php?sess=<?php echo $_SESSION['sess']; ?>&action=100" target="_self">Abbrechen</a>
                </div>
                <div><hr><?php echo $message; ?></div>
            </div>
        </div>
        <!-- end card-box -->

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="../layout-2_blue/assets/js/jquery.min.js"></script>
        <script src="../layout-2_blue/assets/js/bootstrap.min.js"></script>
        <script src="../layout-2_blue/assets/js/detect.js"></script>
        <script src="../layout-2_blue/assets/js/fastclick.js"></script>
        <script src="../layout-2_blue/assets/js/jquery.slimscroll.js"></script>
        <script src="../layout-2_blue/assets/js/jquery.blockUI.js"></script>
        <script src="../layout-2_blue/assets/js/waves.js"></script>
        <script src="../layout-2_blue/assets/js/wow.min.js"></script>
        <script src="../layout-2_blue/assets/js/jquery.nicescroll.js"></script>
        <script src="../layout-2_blue/assets/js/jquery.scrollTo.min.js"></script>




        <!-- Toastr js -->
        <script src="../layout-2_blue/assets/plugins/toastr/toastr.min.js"></script>

        <!-- App js -->
        <script src="../layout-2_blue/assets/js/jquery.core.js"></script>
        <script src="../layout-2_blue/assets/js/jquery.app.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {

                 <?php echo $toaster->SessionToaster(); ?>

                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": true,

                    "positionClass": "toast-top-center",
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",

                }





            } );




        </script>



    </body>
    </html>
<?php
//clear session variable
if ($rate<=100) $toaster->ClearNotification();
?>