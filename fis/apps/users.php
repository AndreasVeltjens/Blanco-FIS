<?php
include("../apps/session.php");
include("../apps/function.php");

$e=new ifisNotification();
$us=new ifisUser($_SESSION['userid']);

if (isset($_POST['refresh'])) {
    AddSessionMessage("info","Datumsbereich eingegrenzt <br><code> ". $_POST['datumvon']. " - ".$_POST['datumbis']." </code>.","Filter aktiv");
    $_SESSION['datumvon'] = $_POST['datumvon'];
    $_SESSION['datumbis'] = $_POST['datumbis'];

}


if (isset($_POST['deldatum'])) {
    $_SESSION['datumvon'] = "";
    $_SESSION['datumbis'] = "";

    AddSessionMessage("info","Datumsbereich aufgehoben.","Filter aktiv");

}

if (isset($_POST['new'])){
    $u=new ifisUser();
    if ($u->InsertData($_POST)){
        header ("Location: ../apps/users.php?sess=".$_SESSION['sess']."");
        exit;
    }
}

$wa = new ifisAllwarenausgang();
$ws = new ifisAllwareneingang();
AddSessionMessage("info","Mitarbeiterliste aktualisiert.","Benachrichtigung");

?>
<!DOCTYPE html>
<html>
	<head>
        <?php include('../apps/header.php'); ?>
        <title>Mitarbeiter</title>
        <?php include('../apps/linker.php'); ?>
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
                        <a href="index.php" class="logo">
                            <i class="zmdi zmdi-toys icon-c-logo"></i><span></span><span>FIS</span>
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

            <div id="custom-width-modal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" style="width:55%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="custom-width-modalLabel">Neuen Mitarbeiter anlegen</h4>
                        </div>

                        <?php include ("users.edit.php");?>

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

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
                                <h4 class="page-title">Mitarbeiterliste</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <form method="post">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label">Zeitraum eingrenzen</label>
                                        <div class="col-lg-8">
                                            <div  id="reportrange" class="pull-right form-control" >
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                <span></span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="datumvon" id="datumvon" value="<?php echo $_SESSION['datumvon']; ?>">
                                        <input type="hidden" name="datumbis" id="datumbis" value="<?php echo $_SESSION['datumbis']; ?>">

                                        <a href="#custom-width-modal3" data-toggle="modal" data-target="#custom-width-modal3" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i> Neuer Mitarbeiter</a>

                                        <button name="refresh" type="submit" class="btn btn-primary waves-effect waves-light">
                                            Eingrenzen
                                        </button>
                                        <button name="deldatum" type="submit" class="btn btn-danger waves-effect waves-light">
                                            Zeitraum entfernen
                                        </button>
                                    </div>

                                    </form>
                                </div>
                            </div><!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <div class="dropdown pull-right">
                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Neuer Mitarbeiter</a></li>
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30">Mitarbeiterliste</h4>

                                    <table id="datatable-buttons" class="table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Abteilung</th>
                                                <th>Straße</th>
                                                <th>Ort</th>
                                                <th>Status</th>
                                                <th>E-Mail</th>
                                                <th>G25</th>
                                                <th>Stapler</th>
                                                <th>Hub</th>
                                                <th>Kran</th>
                                                <th>nächste Vorsorge</th>

                                            </tr>
                                        </thead>

                                    </table>
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
            <?php include ('../apps/notification.php'); ?>

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

        <!-- Validation js (Parsleyjs) -->
        <script type="text/javascript" src="../layout-2_blue/assets/plugins/parsleyjs/dist/parsley.min.js"></script>

        <script src="../layout-2_blue/assets/plugins/moment/moment.js"></script>
        <script src="../layout-2_blue/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

        <!-- Datatable init js -->
        <script >

            var table  = $("#datatable-buttons").DataTable({
                ajax:"users.d.php?sess=<?php echo $_SESSION['sess']; ?>",
                order: [[ 1, "desc" ]],
                "columnDefs": [ {
                    "targets": -1,
                    "data": null,
                    "render": function ( data, type, row ) {
                        return "<a href='../apps/users.e.php?sess=<?php echo $_SESSION['sess']; ?>&useridedit=" + row[0]+"'> <i class='zmdi zmdi-edit'> </i> " + row[11]+"</a>";
                    },
                } ],
                language: {
                    "lengthMenu": "Zeige _MENU_ Datensätze pro Seite",
                    "zeroRecords": "Nichts gefunden - sorry",
                    "info": "Zeige Seite _PAGE_ von _PAGES_",
                    "infoEmpty": "Keine Treffer gefunden",
                    "infoFiltered": "(gefiltert von _MAX_ Datensätzen)"
                },
                dom: "<lengthMenu> Bfrtip ",
                buttons: [{extend: "copy", className: "btn-sm"},
                    {extend: "csv", className: "btn-sm"},
                    {extend: "excel", className: "btn-sm"
                }, {extend: "pdf", className: "btn-sm"}, {extend: "print", className: "btn-sm"}],
                responsive: !0,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });

            var handleDataTableButtons = function () {
                "use strict";
                0 !== $("#datatable-buttons").length && table
            }, TableManageButtons = function () {
                "use strict";
                return {
                    init: function () {
                        handleDataTableButtons()
                    }
                }
            }();


        </script>
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



             } );

            TableManageButtons.init();

            $('#reportrange span').html('<?php echo $_SESSION['datumvon']; ?> - <?php echo $_SESSION['datumbis']; ?>');

            $('#reportrange').daterangepicker({
                format: 'DD/MM/YYYY',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2015',
                maxDate: '31/12/2018',
                dateLimit: {
                    days: 120
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
                    'Letzter Monat': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
        </script>

	</body>
</html>