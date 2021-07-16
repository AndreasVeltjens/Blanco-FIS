<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 12.12.18
 * Time: 20:08
 */

?>
<html>
<head>
    <title>gauge.js</title>
    <meta name="description" content="100% native and cool looking animated JavaScript/CoffeeScript gauge">
    <meta name="viewport" content="width=1024, maximum-scale=1">
    <meta property="og:image" content="http://bernii.github.com/gauge.js/assets/preview.jpg?v=1" />
    <link rel="shortcut icon" href="favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=7" />

</head>
<body>

<div id="foo" class="gauge"></div>
<canvas width=380 height=150 id="canvas-preview"></canvas>
<div id="preview-textfield"></div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="../plugins/gauge/gauge.js"></script>
<script>


    document.getElementById("foo").innerHTML = "Gauge";
    demoGauge = new Gauge(document.getElementById("canvas-preview"));
    var bigFont = "14px sans-serif";
    var opts = {
        angle: 0.1,
        radiusScale:0.9,
        lineWidth: 0.2,
        pointer: {
            length: 0.6,
            strokeWidth: 0.05,
            color: '#000000'
        },
        staticLabels: {
            font: "10px sans-serif",
            labels: [{label:200, font: bigFont},
                {label:750},
                {label:1500},
                {label:2250},
                {label:3000},
                {label:3500, font: bigFont}],
            fractionDigits: 0
        },
        staticZones: [
            {strokeStyle: "rgb(255,0,0)", min: 0, max: 500, height: 1.2},
            {strokeStyle: "rgb(200,100,0)", min: 500, max: 1000, height: 1.1},
            {strokeStyle: "rgb(150,150,0)", min: 1000, max: 1500, height: 1},
            {strokeStyle: "rgb(100,200,0)", min: 1500, max: 2000, height: 0.9},
            {strokeStyle: "rgb(0,255,0)", min: 2000, max: 3100, height: 0.8},
            {strokeStyle: "rgb(80,255,80)", min: 3100, max: 3500, height: 0.7},
            {strokeStyle: "rgb(130,130,130)", min: 2470, max: 2530, height: 1}
        ],
        limitMax: false,
        limitMin: false,
        highDpiSupport: true,
        renderTicks: {
            divisions: 5,
            divWidth: 1.1,
            divLength: 0.7,
            divColor: '#333333',
            subDivisions: 3,
            subLength: 0.5,
            subWidth: 0.6,
            subColor: '#666666'
        }
    };
    demoGauge.setOptions(opts);
    document.getElementById("preview-textfield").className = "preview-textfield";
    demoGauge.setTextField(document.getElementById("preview-textfield"));
    demoGauge.minValue = 0;
    demoGauge.maxValue = 3500;
    demoGauge.set(2122);




</script>
</body>
</html>
