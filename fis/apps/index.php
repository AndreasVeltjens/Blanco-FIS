<?php
include_once("../apps/session.php");
include("../apps/function.php");
$e =new ifisNotification();
$us=new ifisUser($_SESSION['userid']);
$wa = new ifisAllwarenausgang();
$ws = new ifisAllwareneingang();

?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
		<meta name="author" content="Coderthemes">

		<link rel="shortcut icon" href="../layout-2_blue/assets/images/favicon.ico">

		<title>FIS Startseite</title>

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

	<body class="fixed-left">

		<!-- Begin page -->
		<!-- Begin page -->
		<div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="index.php?sess=<?php echo $_SESSION['sess'] ?>" class="logo">
                            <img src="../picture/ALRE_WEB.png" alt="logo" style="height: 60px;"> <span> <span> FIS</span></span>
                            <!--<span><img src="../layout-2_blue/assets/images/logo.png" alt="logo" style="height: 20px;"></span>-->
                        </a>
                    </div>
                </div>

                <!-- Button mobile view to collapse sidebar menu -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="container">
                        <div class="">
                            <div class="pull-left">
                                <button class="button-menu-mobile open-left waves-effect waves-light">
                                    <i class="zmdi zmdi-menu"></i>
                                </button>
                                <span class="clearfix"></span>
                            </div>

                            <form role="search" class="navbar-left app-search pull-left hidden-xs">
			                     <input type="text" placeholder="Suche..." class="form-control">
			                     <a href=""><i class="fa fa-search"></i></a>
			                </form>


                            <ul class="nav navbar-nav navbar-right pull-right">
                                <li>
                                    <!-- Notification -->
                                    <div class="notification-box">
                                        <ul class="list-inline m-b-0">
                                            <li>
                                                <a href="javascript:void(0);" class="right-bar-toggle">
                                                    <i class="zmdi zmdi-notifications-none"></i>
                                                </a>
                                                <div class="noti-dot">
                                                    <span class="dot"></span>
                                                    <span class="pulse"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- End Notification bar -->
                                </li>

                                <?php include('../apps/usershortcut.php'); ?>
                            </ul>

                        </div>
                        <!--/.nav-collapse -->
                    </div>
                </div>
            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            <?php include("../apps/navigation.php");?>
			<!-- Left Sidebar End -->

			<!-- ============================================================== -->
			<!-- Start right Content here -->
			<!-- ============================================================== -->
			<div class="content-page">
				<!-- Start content -->
				<div class="content">
					<div class="container">

						<!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                               <div class="btn-group pull-right m-t-5 m-b-20">
                                    <button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Benutzer <span class="m-l-5"><i class="fa fa-user"></i></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a a href="#custom-width-modal2" data-toggle="modal" data-target="#custom-width-modal2">Benutzerwechsel</a></li>
                                        <li><a a href="#custom-width-modal1" data-toggle="modal" data-target="#custom-width-modal1">Abmelden</a></li>
                                    </ul>
                                </div>
                                <h4 class="page-title">Startseite</h4>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Startseite</h4>
                                    Herzlich Willkommen <?php echo $us->data['name']; ?>
                                </div>
                            </div><!-- end col -->
                            <div class="col-sm-6">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Letzte Prüfungen</h4>
                                    <a href="mfrs.php?sess=<?php echo $_REQUEST['sess']; ?>"> <span> alle Messungen anzeigen</span></a>
                                </div>
                            </div><!-- end col -->


                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-box">
                                    <h4 class="text-dark  header-title m-t-0 m-b-30">Anzahl der Wareneingänge</h4>

                                    <div class="widget-chart text-center">
                                        <div id="sparkline4"></div>
                                        <ul class="list-inline m-t-15">
                                            <li>
                                                <h5 class="text-muted m-t-20">Heute</h5>
                                                <h4 class="m-b-0"><?php echo $ws->ReportCountWareneingange(1);?></h4>
                                            </li>
                                            <li>
                                                <h5 class="text-muted m-t-20">letzte Woche</h5>
                                                <h4 class="m-b-0"><?php echo $ws->ReportCountWareneingange(7);?></h4>
                                            </li>
                                            <li>
                                                <h5 class="text-muted m-t-20">letzter Monat</h5>
                                                <h4 class="m-b-0"><?php echo $ws->ReportCountWareneingange(30);?></h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="card-box">
                                    <h4 class="text-dark  header-title m-t-0 m-b-30">Anzahl der Warenausgänge</h4>

                                    <div class="widget-chart text-center">
                                        <div id="sparkline4"></div>
                                        <ul class="list-inline m-t-15">
                                            <li>
                                                <h5 class="text-muted m-t-20">Heute</h5>
                                                <h4 class="m-b-0"><?php echo $wa->ReportCountWareneingange(1);?></h4>
                                            </li>
                                            <li>
                                                <h5 class="text-muted m-t-20">letzte Woche</h5>
                                                <h4 class="m-b-0"><?php echo $wa->ReportCountWareneingange(7);?></h4>
                                            </li>
                                            <li>
                                                <h5 class="text-muted m-t-20">letzter Monat</h5>
                                                <h4 class="m-b-0"><?php echo $wa->ReportCountWareneingange(30);?></h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>



                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Meine Favoriten</h4>
                                    <a href="../apps/wareneingang.php?sess=<?php echo $_SESSION['sess']; ?>"> <i class="zmdi zmdi-inbox"> </i> Neuen Wareneingang anlegen</a>
                                    <a href="../apps/warenausgang.php?sess=<?php echo $_SESSION['sess']; ?>"> <i class="zmdi zmdi-arrow-forward"> </i> Neuen Warenausgang anlegen</a>

                                </div>
                            </div><!-- end col -->
                        </div>
                        <!-- end row -->



                    </div> <!-- container -->

                </div> <!-- content -->

                <?php include('../apps/footer.php'); ?>

            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
            <?php include('../apps/notification.php')?>
            <!-- /Right-bar -->


        </div>
        <!-- END wrapper -->

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

                <?php echo SessionToaster();
                $_SESSION['e']=array(); ?>

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
            TableManageButtons.init();



        </script>



	</body>
</html>
<?php
$e->ClearNotification();
?>