<?php

include("../apps/function.php");

$e= new ifisNotification();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" >
        <meta http-equiv="refresh" content="60; URL=./qsdashboard.php?werk=<?php echo $_SESSION['werk']; ?>&viewID=<?php echo $_SESSION['viewID']; ?>&sess=<?php echo $_SESSION['sess']; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=0.9">
		<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
		<meta name="author" content="Coderthemes">

		<link rel="shortcut icon" href="../layout-2_blue/assets/images/favicon.ico">

		<title>QS Infosystem</title>
        <!-- Jquery filer css -->
        <link href="../layout-2_blue/assets/plugins/jquery.filer/css/jquery.filer.css" rel="stylesheet" />
        <link href="../layout-2_blue/assets/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet"

        <!--Chartist Chart CSS -->
        <link rel="stylesheet" href="../layout-2_blue/assets/plugins/chartist/dist/chartist.min.css">

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
            <div class="col-lg-6">
                <div class="col-lg-12 card-box">
                    <div class="text-center">
                        <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-gear"> </i> Extruder 5</h4>
                    </div>

                    <table id="table-EX5" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Prüfung-Nr.</th>
                            <th>Datum</th>
                            <th>MFR</th>
                            <th>Bemerkungen</th>
                        </tr>
                        </thead>
                        <?php
                        $x= new ifisAllMid();
                        echo $x->GetListMFR("%Ex%5 %"); ?>
                    </table>


                </div>

                <div class="col-lg-12 card-box">
                    <div class="text-center">
                        <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-gear"> </i> Extruder 2</h4>
                    </div>
                    <table id="table-EX5" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Prüfung-Nr.</th>
                            <th>Datum</th>
                            <th>MFR</th>
                            <th>Bemerkungen</th>
                        </tr>
                        </thead>
                        <?php
                        echo $x->GetListMFR("%Ex%2 %"); ?>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="col-lg-12 card-box">
                    <div class="text-center">
                        <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-gear"> </i> COREMA 1</h4>
                    </div>
                    <table id="table-COREMA1" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Prüfung-Nr.</th>
                            <th>Datum</th>
                            <th>MFR</th>
                            <th>Bemerkungen</th>
                        </tr>
                        </thead>
                        <?php
                        echo $x->GetListMFR("%Corema %"); ?>
                    </table>
                </div>

                <div class="col-lg-12 card-box">
                    <div class="text-center">
                        <h4 class="text-uppercase font-bold m-b-0"><i class="fa fa-gear"> </i> Extruder 1</h4>
                    </div>
                    <table id="table-EX1" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Prüfung-Nr.</th>
                            <th>Datum</th>
                            <th>MFR</th>
                            <th>Bemerkungen</th>
                        </tr>
                        </thead>
                        <?php
                        echo $x->GetListMFR("%Ex%1 %"); ?>
                    </table>
                </div>
            </div>
        </div>

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



    <!-- Flot chart js -->
    <script src="../layout-2_blue/assets/plugins/flot-chart/jquery.flot.js"></script>
    <script src="../layout-2_blue/assets/plugins/flot-chart/jquery.flot.time.js"></script>
    <script src="../layout-2_blue/assets/plugins/flot-chart/jquery.flot.tooltip.min.js"></script>
    <script src="../layout-2_blue/assets/plugins/flot-chart/jquery.flot.resize.js"></script>
    <script src="../layout-2_blue/assets/plugins/flot-chart/jquery.flot.pie.js"></script>
    <script src="../layout-2_blue/assets/plugins/flot-chart/jquery.flot.selection.js"></script>
    <script src="../layout-2_blue/assets/plugins/flot-chart/jquery.flot.stack.js"></script>
    <script src="../layout-2_blue/assets/plugins/flot-chart/jquery.flot.crosshair.js"></script>

    <!-- flot init -->
    <script>

        ! function($) {
            "use strict";

            var FlotChart = function() {
                this.$body = $("body")
                this.$realData = []
            };

            //creates plot graph
            FlotChart.prototype.createPlotGraph = function(selector, data1, data2, labels, colors, borderColor, bgColor) {
                //shows tooltip
                function showTooltip(x, y, contents) {
                    $('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css({
                        position : 'absolute',
                        top : y + 5,
                        left : x + 5
                    }).appendTo("body").fadeIn(200);
                }


                $.plot($(selector), [{
                    data : data1,
                    label : labels[0],
                    color : colors[0]
                }, {
                    data : data2,
                    label : labels[1],
                    color : colors[1]
                }], {
                    series : {
                        lines : {
                            show : true,
                            fill : true,
                            lineWidth : 2,
                            fillColor : {
                                colors : [{
                                    opacity : 0.4
                                }, {
                                    opacity : 0.4
                                }]
                            }
                        },
                        points : {
                            show : false
                        },
                        shadowSize : 0
                    },

                    grid : {
                        hoverable : true,
                        clickable : true,
                        borderColor : borderColor,
                        tickColor : "#f9f9f9",
                        borderWidth : 1,
                        labelMargin : 10,
                        backgroundColor : bgColor
                    },
                    legend : {
                        position : "ne",
                        margin : [0, -24],
                        noColumns : 0,
                        labelBoxBorderColor : null,
                        labelFormatter : function(label, series) {
                            // just add some space to labes
                            return '' + label + '&nbsp;&nbsp;';
                        },
                        width : 30,
                        height : 2
                    },
                    yaxis : {
                        tickColor : '#eee',
                        font : {
                            color : '#bdbdbd'
                        }
                    },
                    xaxis : {
                        tickColor : '#eee',
                        font : {
                            color : '#bdbdbd'
                        }
                    },
                    tooltip : true,
                    tooltipOpts : {
                        content : '%s: Value of %x is %y',
                        shifts : {
                            x : -60,
                            y : 25
                        },
                        defaultTheme : false
                    }
                });
            },
                //end plot graph

                //creates Pie Chart
                FlotChart.prototype.createPieGraph = function(selector, labels, datas, colors) {
                    var data = [{
                        label : labels[0],
                        data : datas[0]
                    }, {
                        label : labels[1],
                        data : datas[1]
                    }, {
                        label : labels[2],
                        data : datas[2]
                    }];
                    var options = {
                        series : {
                            pie : {
                                show : true
                            }
                        },
                        legend : {
                            show : true
                        },
                        grid : {
                            hoverable : true,
                            clickable : true
                        },
                        colors : colors,
                        tooltip : true,
                        tooltipOpts : {
                            content : "%s, %p.0%"
                        }
                    };

                    $.plot($(selector), data, options);
                },

                //returns some random data
                FlotChart.prototype.randomData = function() {
                    var totalPoints = 300;
                    if (this.$realData.length > 0)
                        this.$realData = this.$realData.slice(1);

                    // Do a random walk
                    while (this.$realData.length < totalPoints) {

                        var prev = this.$realData.length > 0 ? this.$realData[this.$realData.length - 1] : 50,
                            y = prev + Math.random() * 10 - 5;

                        if (y < 0) {
                            y = 0;
                        } else if (y > 100) {
                            y = 100;
                        }

                        this.$realData.push(y);
                    }

                    // Zip the generated y values with the x values
                    var res = [];
                    for (var i = 0; i < this.$realData.length; ++i) {
                        res.push([i, this.$realData[i]])
                    }

                    return res;
                }, FlotChart.prototype.createRealTimeGraph = function(selector, data, colors) {
                var plot = $.plot(selector, [data], {
                    colors : colors,
                    series : {
                        grow : {
                            active : false
                        }, //disable auto grow
                        shadowSize : 0, // drawing is faster without shadows
                        lines : {
                            show : true,
                            fill : false,
                            lineWidth : 2,
                            steps : false
                        }
                    },
                    grid : {
                        show : true,
                        aboveData : false,
                        color : '#dcdcdc',
                        labelMargin : 15,
                        axisMargin : 0,
                        borderWidth : 0,
                        borderColor : null,
                        minBorderMargin : 5,
                        clickable : true,
                        hoverable : true,
                        autoHighlight : false,
                        mouseActiveRadius : 20
                    },
                    tooltip : true, //activate tooltip
                    tooltipOpts : {
                        content : "Value is : %y.0" + "%",
                        shifts : {
                            x : -30,
                            y : -50
                        }
                    },
                    yaxis : {
                        min : 0,
                        max : 100,
                        tickColor : '#f5f5f5',
                        color : 'rgba(0,0,0,0.1)'
                    },
                    xaxis : {
                        show : false,
                        tickColor : '#f5f5f5'
                    }
                });

                return plot;
            },
                //creates Donut Chart
                FlotChart.prototype.createDonutGraph = function(selector, labels, datas, colors) {
                    var data = [{
                        label : labels[0],
                        data : datas[0]
                    }, {
                        label : labels[1],
                        data : datas[1]
                    }, {
                        label : labels[2],
                        data : datas[2]
                    }, {
                        label : labels[3],
                        data : datas[3]
                    }];
                    var options = {
                        series : {
                            pie : {
                                show : true,
                                innerRadius : 0.7
                            }
                        },
                        legend : {
                            show : true,
                            labelFormatter : function(label, series) {
                                return '<div style="font-size:14px;">&nbsp;' + label + '</div>'
                            },
                            labelBoxBorderColor : null,
                            margin : 50,
                            width : 20,
                            padding : 1
                        },
                        grid : {
                            hoverable : true,
                            clickable : true
                        },
                        colors : colors,
                        tooltip : true,
                        tooltipOpts : {
                            content : "%s, %p.0%"
                        }
                    };

                    $.plot($(selector), data, options);
                },
                //creates Combine Chart
                FlotChart.prototype.createCombineGraph = function(selector, ticks, labels, datas) {

                    var data = [{
                        label : labels[0],
                        data : datas[0],
                        lines : {
                            show : true,
                            fill : true
                        },
                        points : {
                            show : true
                        }
                    }, {
                        label : labels[1],
                        data : datas[1],
                        lines : {
                            show : true
                        },
                        points : {
                            show : true
                        }
                    }, {
                        label : labels[2],
                        data : datas[2],
                        bars : {
                            show : true
                        }
                    }];
                    var options = {
                        series : {
                            shadowSize : 0
                        },
                        grid : {
                            hoverable : true,
                            clickable : true,
                            tickColor : "#f9f9f9",
                            borderWidth : 1,
                            borderColor : "#eeeeee"
                        },
                        colors : ['#42a5f5', '#ddd', "#4667cc"],
                        tooltip : true,
                        tooltipOpts : {
                            defaultTheme : false
                        },
                        legend : {
                            position : "ne",
                            margin : [0, -24],
                            noColumns : 0,
                            labelBoxBorderColor : null,
                            labelFormatter : function(label, series) {
                                // just add some space to labes
                                return '' + label + '&nbsp;&nbsp;';
                            },
                            width : 30,
                            height : 2
                        },
                        yaxis : {
                            tickColor : '#f5f5f5',
                            font : {
                                color : '#bdbdbd'
                            }
                        },
                        xaxis : {
                            ticks: ticks,
                            tickColor : '#f5f5f5',
                            font : {
                                color : '#bdbdbd'
                            }
                        }
                    };

                    $.plot($(selector), data, options);
                },

                //initializing various charts and components
                FlotChart.prototype.init = function() {
                    //plot graph data
                    var mfr = [[0, 9], [1, 8], [2, 5], [3, 8], [4, 5], [5, 14], [6, 10],[7, 8], [8, 5], [9, 14], [10, 10] ];
                    var restfeuchte = [[0, 5], [1, 12], [2, 4], [3, 3], [4, 12], [5, 11], [6, 14],[7, 12], [8, 8], [9, 4], [10, 8]];
                    var plabels = ["MFR", "Restfeuchte"];
                    var pcolors = ['#ddd', '#86C3F4'];
                    var borderColor = '#f5f5f5';
                    var bgColor = '#fff';
                    this.createPlotGraph("#website-stats", mfr, restfeuchte, plabels, pcolors, borderColor, bgColor);

                    //Pie graph data
                    var pielabels = ["Desktops", "Laptops", "Tablets"];
                    var datas = [20, 30, 15];
                    var colors = ['#42a5f5', '#64b5f6', "#90caf9"];
                    this.createPieGraph("#pie-chart #pie-chart-container", pielabels, datas, colors);

                    //real time data representation
                    var plot = this.createRealTimeGraph('#flotRealTime', this.randomData(), ['#86C3F4']);
                    plot.draw();
                    var $this = this;
                    function updatePlot() {
                        plot.setData([$this.randomData()]);
                        // Since the axes don't change, we don't need to call plot.setupGrid()
                        plot.draw();
                        setTimeout(updatePlot, $('html').hasClass('mobile-device') ? 1000 : 1000);
                    }

                    updatePlot();

                    //Donut pie graph data
                    var donutlabels = ["Desktops", "Laptops", "Tablets", "Mobiles"];
                    var donutdatas = [35, 20, 10, 20];
                    var donutcolors = ['#64b5f6', '#90caf9', "#bbdefb", "#42a5f5"];
                    this.createDonutGraph("#donut-chart #donut-chart-container", donutlabels, donutdatas, donutcolors);

                    //Combine graph data
                    var data24Hours = [[0, 201], [1, 520], [2, 337], [3, 261], [4, 157], [5, 95], [6, 200], [7, 250], [8, 320], [9, 500], [10, 152], [11, 214], [12, 364], [13, 449], [14, 558], [15, 282], [16, 379], [17, 429], [18, 518], [19, 470], [20, 330], [21, 245], [22, 358], [23, 74]];
                    var data48Hours = [[0, 311], [1, 630], [2, 447], [3, 371], [4, 267], [5, 205], [6, 310], [7, 360], [8, 430], [9, 610], [10, 262], [11, 324], [12, 474], [13, 559], [14, 668], [15, 392], [16, 489], [17, 539], [18, 628], [19, 580], [20, 440], [21, 355], [22, 468], [23, 184]];
                    var dataDifference = [[23, 727], [22, 128], [21, 110], [20, 92], [19, 172], [18, 63], [17, 150], [16, 592], [15, 12], [14, 246], [13, 52], [12, 149], [11, 123], [10, 2], [9, 325], [8, 10], [7, 15], [6, 89], [5, 65], [4, 77], [3, 600], [2, 200], [1, 385], [0, 200]];
                    var ticks = [[0, "22h"], [1, ""], [2, "00h"], [3, ""], [4, "02h"], [5, ""], [6, "04h"], [7, ""], [8, "06h"], [9, ""], [10, "08h"], [11, ""], [12, "10h"], [13, ""], [14, "12h"], [15, ""], [16, "14h"], [17, ""], [18, "16h"], [19, ""], [20, "18h"], [21, ""], [22, "20h"], [23, ""]];
                    var combinelabels = ["Last 24 Hours", "Last 48 Hours", "Difference"];
                    var combinedatas = [data24Hours, data48Hours, dataDifference];

                    this.createCombineGraph("#combine-chart #combine-chart-container", ticks, combinelabels, combinedatas);
                },

                //init flotchart
                $.FlotChart = new FlotChart, $.FlotChart.Constructor =
                FlotChart

        }(window.jQuery),

//initializing flotchart
            function($) {
                "use strict";
                $.FlotChart.init()
            }(window.jQuery);

        $(document).ready(function() {



            //------------- Ordered bars chart -------------//
            $(function () {
                //some data
                var d1 = [];
                for (var i = 0; i <= 10; i += 1)
                    d1.push([i, parseInt(Math.random() * 30)]);

                var d2 = [];
                for (var i = 0; i <= 10; i += 1)
                    d2.push([i, parseInt(Math.random() * 30)]);

                var d3 = [];
                for (var i = 0; i <= 10; i += 1)
                    d3.push([i, parseInt(Math.random() * 30)]);

                var ds = new Array();

                ds.push({
                    label: "Series One",
                    data: d1,
                    bars: {
                        order: 3
                    }
                });
                ds.push({
                    label: "Series Two",
                    data: d2,
                    bars: {
                        order: 2
                    }
                });
                ds.push({
                    label: "Series Three",
                    data: d3,
                    bars: {
                        order: 1
                    }
                });

                var stack = 0,
                    bars = false,
                    lines = false,
                    steps = false;

                var options = {
                    bars: {
                        show: true,
                        barWidth: 0.3,
                        fill: 1
                    },
                    grid: {
                        show: true,
                        aboveData: false,
                        labelMargin: 5,
                        axisMargin: 0,
                        borderWidth: 1,
                        minBorderMargin: 5,
                        clickable: true,
                        hoverable: true,
                        autoHighlight: false,
                        mouseActiveRadius: 20,
                        borderColor: '#f5f5f5'
                    },
                    series: {
                        stack: stack
                    },
                    legend: {
                        position: "ne",
                        margin: [0, -24],
                        noColumns: 0,
                        labelBoxBorderColor: null,
                        labelFormatter: function (label, series) {
                            // just add some space to labes
                            return '' + label + '&nbsp;&nbsp;';
                        },
                        width: 30,
                        height: 2
                    },
                    yaxis: {
                        tickColor: '#f5f5f5',
                        font: {
                            color: '#bdbdbd'
                        }
                    },
                    xaxis: {
                        tickColor: '#f5f5f5',
                        font: {
                            color: '#bdbdbd'
                        }
                    },
                    colors: ['#42a5f5', '#64b5f6', "#90caf9"],
                    tooltip: true, //activate tooltip
                    tooltipOpts: {
                        content: "%s : %y.0",
                        shifts: {
                            x: -30,
                            y: -50
                        }
                    }
                };

                $.plot($("#ordered-bars-chart"), ds, options);
            });
        });



    </script>


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