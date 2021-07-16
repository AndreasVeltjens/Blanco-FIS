<?php

include("../apps/function.php");

$e= new ifisNotification();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" >
        <meta http-equiv="refresh" content="120; URL=./dashboard.php?werk=<?php echo $_SESSION['werk']; ?>&viewID=<?php echo $_SESSION['viewID']; ?>&sess=<?php echo $_SESSION['sess']; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
		<meta name="author" content="Coderthemes">

		<link rel="shortcut icon" href="../layout-2_blue/assets/images/favicon.ico">

		<title>Infosystem</title>
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
        <link href="../layout-2_blue/assets/plugins/select2/dist/css/select2.css" rel="stylesheet" type="text/css">
        <link href="../layout-2_blue/assets/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">

        <!-- Circlifull chart css -->
        <link href="../layout-2_blue/assets/plugins/jquery-circliful/css/jquery.circliful.css" rel="stylesheet" type="text/css"/>


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
    <div class="navbar">
        <div class="col-lg-12">
        <div class="row">
            <div class="text-right">
                <img src="../media/logo1.png" height="33">
            </div>
        </div>
        </div>
    </div>

    <div class="content">
        <div class="row">

            <div class="col-lg-4">

            <div class="col-lg-12 card-box">
                <div class="text-center">
                    <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-spin fa-refresh"> </i> Arbeitsunfallrisiko</h4>
                </div>
                <p></p>
                <p></p>
                <div class="text-center ">
                    <?php

                    $x= new ifisAllMessageid();
                    $str=$x->GetLastTimeOfMessage(1);
                    ?>
                    <div class="col-sm-6 col-lg-6">
                        <div data-plugin="circliful" class="circliful-chart" data-dimension="200"
                             data-text="<?php echo $str['prozent']?>%" data-info="Quartal <?php echo $str['txt'] ?>" data-width="30" data-fontsize="24"
                             data-percent="<?php echo $str['prozent']?>" data-fgcolor="#64b5f6" data-bgcolor="#eeeeee"
                             data-type="half" data-fill="#f1f1f1"></div>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <h3>
                            <?php
                            echo "Seit ".$str['days']." Tagen<br> ".$str['hours']." Stunden und<br>  ".$str['minuten']." Minuten ".$str['txt']." ";
                            ?>
                        </h3>

                </div>

            </div>
            <!-- end card-box -->
                </div>
                <div class="col-lg-12 card-box">
                    <div class="text-center">
                        <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-spin fa-refresh"> </i> Schadenstatistik</h4>
                    </div>
                    <p></p>
                    <p></p>
                    <div class="text-center ">
                        <?php

                        $x= new ifisAllMessageid();
                        $str=$x->GetLastTimeOfMessage(2);
                        ?>
                        <div class="col-sm-6 col-lg-6">
                            <div data-plugin="circliful" class="circliful-chart" data-dimension="200"
                                 data-text="<?php echo $str['prozent']?>%" data-info="Quartal <?php echo $str['txt'] ?>" data-width="30" data-fontsize="24"
                                 data-percent="<?php echo $str['prozent']?>" data-fgcolor="#64b5f6" data-bgcolor="#eeeeee"
                                 data-type="half" data-fill="#f1f1f1"></div>
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <h3>
                                <?php
                                echo "Seit ".$str['days']." Tagen <br> ".$str['hours']." Stunden und <br> ".$str['minuten']." Minuten ".$str['txt']." ";
                                ?>
                            </h3>

                        </div>

                    </div>
                    <!-- end card-box -->

                </div>
            </div>
            <div class="col-lg-8">

                <div class="col-lg-12 card-box">
                    <div class="text-center">
                        <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-gears"> </i> Fremdarbeitsanmeldungen</h4>
                    </div>
                    <div>Liste der Anmeldungen</div>
                    <table id="table-anmeldungen" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Anmeldung-Nr.</th>
                            <th>Datum</th>
                            <th>Wer</th>
                            <th>Wo</th>
                            <th>RFID-Chip</th>
                            <th>Sonstiges</th>

                        </tr>
                        </thead>
                        <?php echo $x->GetList(3); ?>
                    </table>
                </div>

                <div class="col-lg-12 card-box">
                    <div class="text-center">
                        <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-check-circle"> </i> Erlaubnisscheine</h4>
                    </div>
                    <div>Liste der Erlaubnisscheine</div>
                    <table id="table-anmeldungen" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Anmeldung-Nr.</th>
                            <th>Datum</th>
                            <th>Wer</th>
                            <th>Wo</th>
                            <th>Status</th>
                            <th>Sonstiges</th>

                        </tr>
                        </thead>
                        <?php echo $x->GetList(4); ?>
                    </table>
                </div>


                <div class="col-lg-12 card-box">
                    <div class="text-center">
                        <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-users"> </i> Besucher</h4>
                    </div>
                    <div>Liste der Besucher</div>
                    <table id="table-anmeldungen" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Anmeldung-Nr.</th>
                            <th>Datum</th>
                            <th>Wer</th>
                            <th>Wo</th>
                        </tr>
                        </thead>
                        <?php echo $x->GetList(5); ?>
                    </table>
                </div>
            </div>
        </div>
            <!-- end card-box -->


    </div>

        <?php include('../apps/footer.php'); ?>



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
<?php if (!isset($_REQUEST['view'])) { ?>
                ajax:"message.d.php?sess=<?php echo $_SESSION['sess']; ?>",
<?php } else { ?>
                ajax:"message.d.php?sess=<?php echo $_SESSION['sess']; ?>&view=<?php echo $_REQUEST['view']; ?>",
<?php } ?>
                order: [[ 1, "desc" ]],
                "columnDefs": [
                    {
                        "targets": 0,
                        "data": null,
                        "render": function (data, type, row) {
                            <?php if (!isset($_REQUEST['view'])) { ?>
                            return "<a href='../apps/message.e.php?sess=<?php echo $_SESSION['sess']; ?>&fid=" + row[0] + "'> <i class='zmdi zmdi-edit'> </i> " + row[11] + "  "+ row[0] +"</a>";
                            <?php } else { ?>
                            return "<a href='../apps/message.e.php?sess=<?php echo $_SESSION['sess']; ?>&view=<?php echo $_REQUEST['view']; ?>&fid=" + row[11] + "'> <i class='zmdi zmdi-edit'> </i> " + row[1] + " </a>";
                            <?php } ?>

                        }
                    },
                    {
                        "targets": -1,
                        "data": null,
                        "render": function (data, type, row) {
<?php if (!isset($_REQUEST['view'])) { ?>
                            return "<a href='../apps/message.e.php?sess=<?php echo $_SESSION['sess']; ?>&fid=" + row[0] + "'> <i class='zmdi zmdi-edit'> </i> " + row[10] +" </a>";
<?php } else { ?>
                            return "<a href='../apps/message.e.php?sess=<?php echo $_SESSION['sess']; ?>&view=<?php echo $_REQUEST['view']; ?>&fid=" + row[0] + "'> <i class='zmdi zmdi-edit'> </i> " + row[12] + " </a>";
 <?php } ?>

                        }
                    },
                    {
                        "targets": -2,
                        "data": null,
                        "render": function ( data, type, row ) {
<?php if (!isset($_REQUEST['view'])) { ?>
                        return "<a href='../apps/print.messageid.php?sess=<?php echo $_SESSION['sess']; ?>&fid=" + row[0]+"' target='_blank'> <i class='zmdi zmdi-print'> </i> " + row[9]+"</a>";
<?php } else { ?>
                            return "<a href='../apps/message.e.php?sess=<?php echo $_SESSION['sess']; ?>&view=<?php echo $_REQUEST['view']; ?>&fid=" + row[0] + "'> <i class='zmdi zmdi-edit'> </i> " + row[11] + " </a>";
<?php } ?>

                    }
                    }
                ],
                language: {
                    "lengthMenu": "Zeige _MENU_ Datensätze pro Seite",
                    "zeroRecords": "Nichts gefunden - sorry",
                    "info": "Zeige Seite _PAGE_ von _PAGES_",
                    "infoEmpty": "Keine Treffer gefunden",
                    "infoFiltered": "(gefiltert von _MAX_ Datensätzen)"
                },
                dom: "<lengthMenu> Bfrtip",
                buttons: [{extend: "copy", className: "btn-sm"}, {extend: "csv", className: "btn-sm"}, {
                    extend: "excel",
                    className: "btn-sm"
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
        <!-- Jquery filer js -->
        <script src="../layout-2_blue/assets/plugins/jquery.filer/js/jquery.filer.min.js"></script>
        <script src="../layout-2_blue/assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>

        <!-- Toastr js -->
        <script src="../layout-2_blue/assets/plugins/toastr/toastr.min.js"></script>
        <!-- Circliful -->
        <script src="../layout-2_blue/assets/plugins/jquery-circliful/js/jquery.circliful.min.js"></script>
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
                            url: "../apps/upload.php?userid=<?php echo $u->userid; ?>&path=fid",
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
                    $.post('../apps/remove_file.php?userid=<?php echo $u->userid; ?>&path=fid', {file: file});
                },
                onEmpty: null,
                    options: null,
                    captions: {
                    button: "Datei wählen",
                        feedback: "Dateien zum Hochladen auswählen",
                        feedback2: "Diese Dateien wurden gewählt",
                        drop: "Ziehen Sie die Dateien hier hinein, um Sie hochzuladen",
                        removeConfirmation: "Sie Sie sich sich, dass die diese Datei löschen wollen?",
                        errors: {
                        filesLimit: "Es sind nur {{fi-limit}} Dateien gleichzeitig möglich.",
                            filesType: "Es dürfen nur Bilddateien hochgeladen werden.",
                            filesSize: "{{fi-name}} diese Datei ist zu groß! Erlaubte Dateigröße {{fi-maxSize}} MB.",
                            filesSizeAll: "Die Summe der Dateigrößen ist zu groß! Max. Upload-Größe {{fi-maxSize}} MB."
                    }
                }

            });
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