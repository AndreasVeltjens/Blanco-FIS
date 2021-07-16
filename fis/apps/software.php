<?php
include_once("../apps/session.php");
include("../apps/function.php");

if (isset ($_POST['update'])){
    include('../apps/softwareupdate.php');
    DoSoftwareUpdate();
}


if (isset ($_POST['MaterialNames'])){
    include('../apps/softwareupdate.php');
    DoCleanMaterialNames();
}

if (isset ($_POST['RepairData'])){
    include('../apps/softwareupdate.php');
    DoRepairImportData();
}

if (isset ($_POST['import'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoImportData&limit=0");
}

if (isset ($_POST['RepairUsernames'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoRepairUsernamesData&limit=0");
}

if (isset ($_POST['RepairUsernames1'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoRepairUsernamesData1&limit=0");
}

if (isset ($_POST['RepairUsernames2'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoRepairUsernamesData2&limit=0");
}

if (isset ($_POST['RepairUsernames3'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoRepairUsernamesData3&limit=0");
}
if (isset ($_POST['RepairUsernames4'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoRepairUsernamesData4&limit=0");
}
if (isset ($_POST['RepairUsernames5'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoRepairUsernamesData5&limit=0");
}

if (isset ($_POST['DoInventur'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoInventur&limit=0");
}

if (isset ($_POST['DoMitarbeiterUpdate'])){
    header("Location: loading.php?sess=".$_SESSION['sess']."&action=DoMitarbeiterUpdate&limit=0");
}

if (isset ($_POST['clear'])){
    $_SESSION['x']="";
}
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

		<title>FIS Softwareupdate</title>

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
                            <i class="zmdi zmdi-toys icon-c-logo"></i><span>ALBA<span>FIS</span></span>
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
                                    <button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div>
                                <h4 class="page-title">Software</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Update</h4>
                                    <form method="post">
                                    <button name="update" type="submit" class="btn btn-primary waves-effect waves-light">
                                        Softwareupdate ausführen
                                    </button>


                                        <button name="DoMitarbeiterUpdate" type="submit" class="btn btn-warning waves-effect waves-light">
                                            Check Mitarbeiter ausführen
                                        </button>


                                        <button name="clear" type="submit" class="btn btn-primary waves-effect waves-light">
                                            Sessiondaten leeren
                                        </button>

                                        <button name="import" type="submit" class="btn btn-primary waves-effect waves-light">
                                            Datenimport aus Accessdaten ausführen
                                        </button>
                                        <button name="MaterialNames" type="submit" class="btn btn-primary waves-effect waves-light">
                                            Materialnamen prüfen
                                        </button>
                                        <button name="RepairData" type="submit" class="btn btn-primary waves-effect waves-light">
                                            Fertigungszeiten prüfen
                                        </button>
                                        <hr>
                                        <button name="RepairUsernames" type="submit" class="btn btn-primary waves-effect waves-light">
                                            MFI Werkstoffprüfer ergänzen
                                        </button>

                                        <button name="RepairUsernames1" type="submit" class="btn btn-primary waves-effect waves-light">
                                            RF Werkstoffprüfer ergänzen
                                        </button>

                                        <button name="RepairUsernames2" type="submit" class="btn btn-primary waves-effect waves-light">
                                            MA Werkstoffprüfer ergänzen
                                        </button>
                                        <button name="RepairUsernames3" type="submit" class="btn btn-primary waves-effect waves-light">
                                            MA2 Werkstoffprüfer ergänzen
                                        </button>
                                        <button name="RepairUsernames4" type="submit" class="btn btn-primary waves-effect waves-light">
                                            F Werkstoffprüfer ergänzen
                                        </button>
                                        <button name="RepairUsernames5" type="submit" class="btn btn-primary waves-effect waves-light">
                                            Q Werkstoffprüfer ergänzen
                                        </button>
                                        <hr>
                                        <button name="DoInventur" type="submit" class="btn btn-primary waves-effect waves-light">
                                            Inventur alles Warenausgang buchen
                                        </button>


                                    </form>
                                </div>
                            </div><!-- end col -->

                            <div class="col-sm-6">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Info</h4>
                                    Software arbeitet selbstständig. Die Applikation endet selbstständig und führt u.a. auch Datenbankupdates aus.
                                </div>
                            </div><!-- end col -->

                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Debug Informationen</h4>
                                <?php echo print_r($_REQUEST);  ?> <br><br>
                                <?php echo $_SESSION['sess']; ?> <br><br>
                                <?php echo print_r($_SESSION); ?><br><br>
                                <?php echo print_r($_SERVER); ?>
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
            <div class="side-bar right-bar">
                <a href="javascript:void(0);" class="right-bar-toggle">
                    <i class="zmdi zmdi-close-circle-o"></i>
                </a>
                <h4 class="">Notifications</h4>
                <div class="notification-list nicescroll">
                    <ul class="list-group list-no-border user-list">
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="../layout-2_blue/assets/images/users/avatar-2.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">Michael Zenaty</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 hours ago</span>
                                </div>
                            </a>
                        </li>


                    </ul>
                </div>
            </div>

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

                <?php echo SessionToaster() ?>

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