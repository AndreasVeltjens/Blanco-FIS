<?php
include ("./config.php");


?>
! function($) {
    "use strict";

    var FlotChart = function() {
        this.$body = $("body")
        this.$realData = []
    };



<?php

for ($i=0; $i<10; $i++){
    $strcolums.="data".$i.", ";
    $strdata.=",{
                data:data".$i.",
                label:labels[".$i."],
                color:colors[".$i."]
                }" ;
    ?>


//creates plot graph
FlotChart.prototype.createPlotGraph<?php echo ($i+1); ?> = function(selector, <?php echo $strcolums; ?> labels, colors, borderColor, bgColor, ticks) {

    //shows tooltip
    function showTooltip(x, y, contents) {
    $('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css({
    position : 'absolute',
    top : y + 5,
    left : x + 5
    }).appendTo("body").fadeIn(200);
    }




    $.plot($(selector), [
    <?php echo substr($strdata,1); ?>
    ], {
series : {
lines : {
show : true,
fill : true,
lineWidth : 2,
fillColor : {
colors : [{
opacity : 0.2
}, {
opacity : 0.2
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
tickColor : '#ddd',
font : {
color : '#bdbdbd'
}
},
xaxis : {
ticks: ticks,
tickColor : '#ddd',
font : {
color : '#000000'
}
},
    tooltip : true,
    tooltipOpts : {
    content: function (label, x, y) {
            var date = new Date(+x * 1000);
            var tooltip = '<h5>' + label + '</h5>';
                tooltip += '<h5>Datum: ' + date.toLocaleDateString() + ' - ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + '</h5>';
                tooltip += '<h5>Wert:  ' + Math.round(y*10)/10 + '°C </h5>';
            return tooltip;
    },

        shifts : {
                x : -60,
                y : 25
                }

    }



});
},
//end plot graph $i

<?php } ?>


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
<?php

if (count($_SESSION['diagramm'][$_SESSION['diagrammdataset']])>0){
    foreach ($_SESSION['diagramm'][$_SESSION['diagrammdataset']] as $i => $item) {

        $d = new FisDatalog($item['udid'],$_SESSION['diagrammdataset'],$_SESSION['equip']);
        $d->SetDataSet($_SESSION['diagrammdataset']);
        $d->SetInterval($item['interval']);
        $data[$i] = $d->GetFlotDiagrammData();
        $data[$i]['plabels'] = $d->GetFlotDiagrammLabel();
        $data[$i]['pcolors'] = $d->GetFlotDiagrammColor();



        if (count($d->GetFlotDiagrammValueColumns())>0){
            foreach ($d->GetFlotDiagrammValueColumns() as $x=>$datacol){
                echo "//".$datacol." Dataset:".$_SESSION['diagrammdataset'].chr(13);
                echo "var y".$x."_".$i."=".json_encode($data[$i][$datacol]).";
            ";
            }
        }

        echo "var plabels_".$i."=".json_encode($data[$i]['plabels']).";
        ";
        echo "var pcolors_".$i."=".json_encode($data[$i]['pcolors']).";
        ";
        echo "var ticks_".$i."=".json_encode($data[$i]['ticks']).";
        ";
        echo "
        var borderColor = '#f5f5f5';
        var bgColor = '#fff';
        ";
        echo "this.createPlotGraph".$d->GetFlotDiagrammColumnsCount()."(\"#LineDiagamm".$i."\",";
        if (count($d->GetFlotDiagrammValueColumns())>0) {
            foreach ($d->GetFlotDiagrammValueColumns() as $x=>$datacol) {
                echo "y".$x."_".$i.",";
            }
        }
        echo "plabels_$i, pcolors_$i, borderColor, bgColor, ticks_$i);
        ";
    }
}
?>




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
