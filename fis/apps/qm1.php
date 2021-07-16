<?php
include("../apps/session.php");
include("../apps/function.php");

$e= new ifisNotification();
$us=new ifisUser($_SESSION['userid']);

if (isset($_POST['refresh'])) {
    AddSessionMessage("info","Datumsbereich eingegrenzt <br><code> ". $_POST['datumvon']. " - ".$_POST['datumbis']." </code>.","Filter aktiv");
    $_SESSION['datumvon'] = $_POST['datumvon'];
    $_SESSION['datumbis'] = $_POST['datumbis'];
    $_SESSION['gebindeID'] = $_POST['gebindeID'];
    $_SESSION['Smaterial'] = $_POST['Smaterial'];
    $_SESSION['Smtype'] = $_POST['Smtype'];
}

if (isset($_POST['deldatum'])) {
    $_SESSION['datumvon'] = "";
    $_SESSION['datumbis'] = "";
    $_SESSION['gebindeID'] = $_POST['gebindeID'];
    $_SESSION['Smaterial'] = $_POST['Smaterial'];
    $_SESSION['Smtype'] = $_POST['Smtype'];
    AddSessionMessage("info","Datumsbereich aufgehoben.","Filter aktiv");

}

if (isset($_POST['delall'])) {
    $_SESSION['datumvon'] = "";
    $_SESSION['datumbis'] = "";
    $_SESSION['gebindeID'] = "";
    $_SESSION['Smaterial'] = "";
    $_SESSION['Smtype'] = "";
    AddSessionMessage("info","Datumsbereich aufgehoben.","Filter aktiv");

}


$w=new ifisFid();
$wa = new ifisAllwarenausgang();
$ws = new ifisAllwareneingang();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
		<meta name="author" content="Coderthemes">

		<link rel="shortcut icon" href="../layout-2_blue/assets/images/favicon.ico">

		<title>Auswertung</title>
        <!-- Jquery filer css -->
        <link href="../layout-2_blue/assets/plugins/jquery.filer/css/jquery.filer.css" rel="stylesheet" />
        <link href="../layout-2_blue/assets/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet"
        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="../layout-2_blue/assets/plugins/morris/morris.css">
        <!-- Notification css (Toastr) -->
        <link href="../layout-2_blue/assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
        <link href="../layout-2_blue/assets/plugins/select2/dist/css/select2.css" rel="stylesheet" type="text/css">
        <link href="../layout-2_blue/assets/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">


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
                            <i class="zmdi zmdi-toys icon-c-logo"></i><span></span><span>QS</span>
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

                                    </ul>
                                </div>
                                <h4 class="page-title">Auswertung</h4>
                            </div>
                        </div>
                        <form method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-box table-responsive">

                                    <div class="form-group">
                                        <label class="col-sm-8 control-label">Zeitraum eingrenzen</label>
                                        <div class="col-sm-8">
                                            <div  id="reportrange" class="pull-right form-control" >
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                <span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <br><br>
                                    <hr>
                                    <div class="form-group">
                                        <label for="Smaterial" class="col-sm-4 control-label">Material</label>
                                        <div class="col-sm-8">
                                            <select  class="form-control select2" name="Smaterial" id="Smaterial">
                                                <?php echo GetMaterialName("select2",$_SESSION['Smaterial']);

                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="Smtype" class="col-sm-4 control-label">Prüfungart</label>
                                        <div class="col-sm-8">
                                            <select  class="form-control select2" name="Smtype" id="Smtype">
                                                <?php echo GetListMidType("select",$_SESSION['Smtype']);

                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                    <hr>
                                    <button name="refresh" type="submit" class="btn btn-primary waves-effect waves-light">
                                        Eingrenzen
                                    </button>
                                    <button name="deldatum" type="submit" class="btn btn-danger waves-effect waves-light">
                                        Zeitraum entfernen
                                    </button>
                                    <button name="delall" type="submit" class="btn btn-danger waves-effect waves-light">
                                        Suche zurücksetzen
                                    </button>

                                </div>

                            </div>
                            <div class="col-sm-6">
                                    <div class="card-box table-responsive">
                                        <div class="form-group">
                                            <label for="gebindeID" class="col-sm-3 control-label">ID Messung</label>
                                            <div class="col-sm-9">
                                                <input  type="text" class="form-control" name="gebindeID" id="gebindeID" placeholder="Messung-ID eingeben" value="<?php echo $_SESSION['gebindeID']; ?>">
                                            </div>

                                        </div>
                                        <hr>


                                        <input type="hidden" name="datumvon" id="datumvon" value="<?php echo $_SESSION['datumvon']; ?>">
                                        <input type="hidden" name="datumbis" id="datumbis" value="<?php echo $_SESSION['datumbis']; ?>">
                                        <hr>

                                    </div>
                                </div>
                            </div><!-- end col -->
                        </div>
                        <!-- end row -->
                        </form>

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="card-box">
                                    <div class="dropdown pull-right">
                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a></li>
                                            <li><a href="#">Another action</a></li>
                                            <li><a href="#">Something else here</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a></li>
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30">Area Chart with Point</h4>

                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #2196f3;"></i>Series A</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #64b5f6;"></i>Series B</h5>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="morris-area-with-dotted" style="height: 300px;"></div>

                                </div>
                            </div><!-- end col-->

                            <div class="col-lg-6">
                                <div class="card-box">
                                    <div class="dropdown pull-right">
                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a></li>
                                            <li><a href="#">Another action</a></li>
                                            <li><a href="#">Something else here</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a></li>
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30"> Donut Chart</h4>

                                    <div id="morris-donut-example" style="height: 300px;"></div>

                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #2196f3;"></i>Download Sales</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #64b5f6;"></i>In-Store Sales</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #bbdefb;"></i>Mail-Order Sales</h5>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div><!-- end col-->

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
            <?php include('../apps/notification.php'); ?>

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

        <!--Morris Chart-->
        <script src="../layout-2_blue/assets/plugins/morris/morris.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/raphael/raphael-min.js"></script>
        <script src="jquery.morris.init.js"></script>


        <!-- Validation js (Parsleyjs) -->
        <script type="text/javascript" src="../layout-2_blue/assets/plugins/parsleyjs/dist/parsley.min.js"></script>

        <script src="../layout-2_blue/assets/plugins/moment/moment.js"></script>
        <script src="../layout-2_blue/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>


        <!-- Jquery filer js -->
        <script src="../layout-2_blue/assets/plugins/jquery.filer/js/jquery.filer.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>

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




                'use-strict';


            } );

            $('#reportrange span').html('<?php echo $_SESSION['datumvon']; ?> - <?php echo $_SESSION['datumbis']; ?>');

            $('#reportrange').daterangepicker({
                format: 'DD/MM/YYYY',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2015',
                maxDate: '31/12/2018',
                dateLimit: {
                    days: 365
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Heute': [moment(), moment()],
                    'Gestern': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'letzte 7 Tagen': [moment().subtract(6, 'days'), moment()],
                    'letzte 30 Tagen': [moment().subtract(29, 'days'), moment()],

                    'Dieser Monat': [moment().startOf('month'), moment().endOf('month')],
                    'Letzter Monat': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Letztes Quartal': [moment().subtract(92, 'days'), moment()],
                    'Letztes Jahr': [moment().subtract(365, 'days'), moment()]
                },
                opens: 'left',
                drops: 'down',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-success',
                cancelClass: 'btn-default',
                separator: ' to ',
                locale: {
                    applyLabel: 'Übernehmen',
                    cancelLabel: 'Abbrechen',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Benutzerdefiniert',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('D.M.YYYY') + ' - ' + end.format('D.M.YYYY'));
                $('#datumvon').val(start.format('YYYY-M-D'));
                $('#datumbis').val(end.format('YYYY-M-D'));

            });

            // Select2
            $(".select2").select2();

            $(".select2-limiting").select2({
                placeholder: "Bitte auswählen"
            });



        </script>

	</body>
</html>
<?php $e->ClearNotification();
exit;
?>