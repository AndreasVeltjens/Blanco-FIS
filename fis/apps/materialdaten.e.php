<?php
include_once("../apps/session.php");
include("../apps/function.php");

$e= new ifisNotification();
$us=new ifisUser($_SESSION['userid']);
$w = new ifisMaterial($_REQUEST['materialid']);
$_SESSION['last_materialid']=$_REQUEST['materialid'];


if (isset ($_POST['edit'])){
   $w->SaveData($_POST);
    header ("Location: ../apps/materialdaten.php?sess=".$_SESSION['sess']."");
    exit;
}
if (isset ($_POST['copy'])){
    $w->InsertData($_POST);
    header ("Location: ../apps/materialdaten.php?sess=".$_SESSION['sess']."");
    exit;
}

if (isset ($_POST['sendemail'])) {
    $w->SendEmail("",$_POST['email']);
}

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

		<title>FIS Materialdaten</title>

        <!-- Jquery filer css -->
        <link href="../layout-2_blue/assets/plugins/jquery.filer/css/jquery.filer.css" rel="stylesheet" />
        <link href="../layout-2_blue/assets/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet"

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
                            <i class="zmdi zmdi-toys icon-c-logo"></i><span><span>MM</span></span>
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
                                <h4 class="page-title">Materialdaten bearbeiten</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Bearbeiten</h4>
                                    <?php include('../apps/material.edit.php'); ?>
                                </div>
                            </div><!-- end col -->

                            <div class="col-sm-3">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Funktionen</h4>
                                    <a href="materialdaten.php?sess=<?php echo $_REQUEST['sess']; ?>"><i class="zmdi zmdi-arrow-back"></i> Materialliste</a>
                                    <hr>

                                    <form method="post">
                                        Information ??ber diese Materialdaten per E-Mail senden<br><br>
                                        E-Mail eingeben <input type="text" name="email" >
                                        <button name="sendemail" type="submit" class="btn btn-primary waves-effect waves-light">
                                            Info per E-Mail senden
                                        </button>
                                    </form>


                                </div>

                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Status</h4>
                                    <?php echo $w->GetCurrentState(); ?>
                                </div>
                            </div><!-- end col -->

                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Fertigungsauftr??ge</h4>
                                    <table id="table-documents" class="table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Datum</th>
                                            <th>Gebinde-ID</th>
                                            <th>Material</th>
                                            <th>Beschreibung</th>
                                            <th>Gewicht</th>
                                            <th>SAP</th>

                                        </tr>
                                        </thead>
                                        <?php echo $w->GetFidsAsTableBody(); ?>
                                    </table>

                                </div>
                            </div><!-- end col -->

                        </div><!-- end row -->
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Dokumente</h4>
                                    <table id="table-documents" class="table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Datum</th>
                                            <th>Dokument</th>
                                            <th>Beschreibung</th>
                                            <th>Status</th>
                                            <th>Mitarbeiter</th>

                                        </tr>
                                        </thead>
                                        <?php echo $w->GetDocumentList(); ?>
                                    </table>

                                </div>
                            </div><!-- end col -->

                        </div><!-- end row -->
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="card-box table-responsive">
                                    <h4 class="page-title">Verlauf</h4>
                                    <table id="table-history" class="table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Datum</th>
                                            <th>Beschreibung</th>
                                            <th>Status</th>
                                            <th>Mitarbeiter</th>

                                        </tr>
                                        </thead>
                                        <?php echo $w->GetHistoryAsTableBody()?>
                                    </table>

                                </div>
                            </div><!-- end col -->

                        </div><!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <?php include('../apps/footer.php'); ?>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
              <?php include ("../apps/notification.php"); ?>

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
        <!-- Validation js (Parsleyjs) -->
        <script type="text/javascript" src="../layout-2_blue/assets/plugins/parsleyjs/dist/parsley.min.js"></script>

        <!-- Datatable init js -->
        <script src="../layout-2_blue/assets/pages/jquery.datatables.init.js"></script>
        <!-- Jquery filer js -->
        <script src="../layout-2_blue/assets/plugins/jquery.filer/js/jquery.filer.min.js"></script>

        <!-- Toastr js -->
        <script src="../layout-2_blue/assets/plugins/toastr/toastr.min.js"></script>

        <!-- App js -->
        <script src="../layout-2_blue/assets/js/jquery.core.js"></script>
        <script src="../layout-2_blue/assets/js/jquery.app.js"></script>



        <script type="text/javascript">
          function SetFaufTypeView(){
              if ( $('#ftype').val()==1  || $('#ftype').val()==4 || $('#ftype').val()==5 || $('#ftype').val()==6 || $('#ftype').val()==7) {
                  $('#granulat').show();
                  $('#agglomahl').hide();
              } else{
                  $('#granulat').hide();
                  $('#agglomahl').show();

                  if ($('#ftype').val()==2) {
                      $('#agglomerat').hide();
                      $('#mahlgut').show();}

                  if ($('#ftype').val()==3) {
                      $('#agglomerat').show();
                      $('#mahlgut').hide();
                  }
              }

              if ($('#ftype').val() <1) {
                  $('#granulat').hide();
                  $('#agglomahl').hide();
                  $('#agglomerat').hide();
                  $('#mahlgut').hide();
                  alert('Keine Fertigungsauftragsart gew??hlt. Typ:'+ $('#ftype').val() );
              }
          }

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


                $('#ftype').change(function(){
                   SetFaufTypeView();

                });

                $('#datatable').dataTable();



                    'use-strict';


                    //Example 1
                    $("#filer_input1").filer({
                        limit: null,
                        maxSize: null,
                        extensions: null,
                        changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Drag & Drop files here</h3> <span style="display:inline-block; margin: 15px 0">or</span></div><a class="jFiler-input-choose-btn btn btn-custom waves-effect waves-light">Browse Files</a></div></div>',
                        showThumbs: true,
                        theme: "dragdropbox",
                        templates: {
                            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
                            item: '<li class="jFiler-item">\
                        <div class="jFiler-item-container">\
                            <div class="jFiler-item-inner">\
                                <div class="jFiler-item-thumb">\
                                    <div class="jFiler-item-status"></div>\
                                    <div class="jFiler-item-info">\
                                        <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                        <span class="jFiler-item-others">{{fi-size2}}</span>\
                                    </div>\
                                    {{fi-image}}\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{{fi-progressBar}}</li>\
                                    </ul>\
                                    <ul class="list-inline pull-right">\
                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </li>',
                            itemAppend: '<li class="jFiler-item">\
                            <div class="jFiler-item-container">\
                                <div class="jFiler-item-inner">\
                                    <div class="jFiler-item-thumb">\
                                        <div class="jFiler-item-status"></div>\
                                        <div class="jFiler-item-info">\
                                            <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                            <span class="jFiler-item-others">{{fi-size2}}</span>\
                                        </div>\
                                        {{fi-image}}\
                                    </div>\
                                    <div class="jFiler-item-assets jFiler-row">\
                                        <ul class="list-inline pull-left">\
                                            <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
                                        </ul>\
                                        <ul class="list-inline pull-right">\
                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>',
                            progressBar: '<div class="bar"></div>',
                            itemAppendToEnd: false,
                            removeConfirmation: true,
                            _selectors: {
                                list: '.jFiler-items-list',
                                item: '.jFiler-item',
                                progressBar: '.bar',
                                remove: '.jFiler-item-trash-action'
                            }
                        },
                        dragDrop: {
                            dragEnter: null,
                            dragLeave: null,
                            drop: null,
                        },
                        uploadFile: {
                            url: "../apps/upload.php?userid=<?php echo $u->userid; ?>&path=warenausgang",
                            data: null,
                            type: 'POST',
                            enctype: 'multipart/form-data',
                            beforeSend: function(){},
                            success: function(data, el){
                                var parent = el.find(".jFiler-jProgressBar").parent();
                                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                                    $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
                                });
                            },
                            error: function(el){
                                var parent = el.find(".jFiler-jProgressBar").parent();
                                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                                    $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
                                });
                            },
                            statusCode: null,
                            onProgress: null,
                            onComplete: null
                        },
                        <?php echo $us->LoadUploadData(); ?>
                        ,
                        addMore: false,
                        clipBoardPaste: true,
                        excludeName: null,
                        beforeRender: null,
                        afterRender: null,
                        beforeShow: null,
                        beforeSelect: null,
                        onSelect: null,
                        afterShow: null,
                        onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
                            var file = file.name;
                            $.post('../apps/remove_file.php?userid=<?php echo $u->userid; ?>&path=warenausgang', {file: file});
                        },
                        onEmpty: null,
                        options: null,
                        captions: {
                            button: "Datei w??hlen",
                            feedback: "Dateien zumHochladen ausw??hlen",
                            feedback2: "Diese Dateien wurden gew??hlt",
                            drop: "Ziehen Sie die Dateien hier hinein, um Sie hochzuladen",
                            removeConfirmation: "Sie Sie sich sich, dass die diese Datei l??schen wollen?",
                            errors: {
                                filesLimit: "Es sind nur {{fi-limit}} Dateien gleichzeitig m??glich.",
                                filesType: "Es d??rfen nur Bilddateien hochgeladen werden.",
                                filesSize: "{{fi-name}} diese Datei ist zu gro??! Erlaubte Dateigr????e {{fi-maxSize}} MB.",
                                filesSizeAll: "Die Summe der Dateigr????en ist zu gro??! Max. Upload-Gr????e {{fi-maxSize}} MB."
                            }
                        }
                    });




             } );
            TableManageButtons.init();






        </script>



	</body>
</html>
<?php $e->ClearNotification();
exit;
?>