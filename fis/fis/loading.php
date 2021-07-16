<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 08.10.18
 * Time: 21:54
 */

include("./config.php");

?>
<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
        <meta http-equiv="refresh"
              content="<?php echo FIS_WAITING_TIME_AFTER_NEW_FID ?>; URL=./<?php echo $_SESSION['wp']; ?>.php?sess=<?php echo $_SESSION['sess']; ?>&action=<?php echo $_SESSION['action']; ?>&wp=<?php echo $_SESSION['wp']; ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="../layout-2_blue/assets/images/favicon.ico">

    <title>FIS Load Data</title>

    <!-- DataTables -->
    <link href="../layout-2_blue/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../layout-2_blue/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />

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
                <h4 class="text-uppercase font-bold m-b-0">Update Data</h4>

                <div class="user-thumb m-t-20">
                    <i class="fa fa-lock fa-5x"></i>
                </div>
                <p class="text-muted m-t-10">
                    <?php echo FIS_COMPANY_SLOGAN; ?>
                </p>

            </div>
            <div class="panel-body">
                Load Data....Import

                <div class="progress">
                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow=">100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                        <span class="sr-only"><?php echo $p ?>100% Complete</span>
                    </div>
                </div>

                <a href="./index.php?sess=<?php echo $_SESSION['sess']; ?>" target="_self">Abbrechen</a> 100%
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



        <!-- Datatables-->
        <script src="../layout-2_blue/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/jszip.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/datatables/responsive.bootstrap.min.js"></script>

        <script src="../layout-2_blue/assets/plugins/moment/moment.js"></script>
        <script src="../layout-2_blue/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

        <!-- Datatable init js -->
        <script src="../layout-2_blue/assets/pages/jquery.datatables.init.js"></script>

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
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }



                $('#datatable').dataTable();

            } );




        </script>



    </body>
    </html>