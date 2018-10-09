<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/login.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php
    unset($_SESSION['reference']);
    unset($_SESSION['mean']);
    unset($_SESSION['time']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <script src="js/canvasjs.min.js"></script>
    <title>Dashboard | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <div id="wrapper">
        <?php require_once 'codeblocks/navbar.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Dashboard</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="white-box">
                            <h3 class="box-title">Welcome</h3> 
                            <p>Welcome to Lecture Hall Assistant! Please use navigation bar to navigate through application. </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="white-box">
                            <h3 class="box-title">Audio Inputs</h3> 
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Device Name</th>
                                        <th>Audio Level</th>
                                    </tr>
                                </thead>
                                <tbody id="devices">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="white-box">
                            <h3 class="box-title">Reference Audio Level</h3> 
                            <div id="referenceChart" style="height: 370px; width:100%;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="white-box">
                            <h3 class="box-title">Relative Audio Level</h3> 
                            <div id="meanChart" style="height: 370px; width:100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once 'codeblocks/footer.php'; ?>
        </div>
    </div>
    <?php require_once 'codeblocks/js.php'; ?>
    <script type="text/javascript">
        window.onload = function() {
            var dataPoints = [];
            var chart;
            $.getJSON("phpscripts/getreference.php", function(data) {  
                $.each(data, function(key, value){
                    dataPoints.push({x: value[0], y: parseInt(value[1])});
                });
                chart = new CanvasJS.Chart("referenceChart",{
                    axisX:{      
                        title: "Time"
                    },
                    axisY: {
                        title: "Audio Level"
                    },
                    data: [{
                        type: "line",
                        dataPoints : dataPoints,
                    }]
                });
                chart.render();
                updateChart();
            });
            function updateChart() {
                $.getJSON("phpscripts/getreference.php", function(data) {
                    $.each(data, function(key, value) {
                        dataPoints.shift() 
                        dataPoints.push({
                        x: parseInt(value[0]),
                        y: parseInt(value[1])
                        });
                    });
                    chart.render();
                    setTimeout(function(){updateChart()}, 1000);
                });
            }
            var dataPoints2 = [];
            var chart2;
            $.getJSON("phpscripts/getmean.php", function(data) {  
                $.each(data, function(key, value){
                    dataPoints2.push({x: value[0], y: parseInt(value[1])});
                });
                chart2 = new CanvasJS.Chart("meanChart",{
                    axisX:{      
                        title: "Time"
                    },
                    axisY: {
                        title: "Audio Level"
                    },
                    data: [{
                        type: "line",
                        dataPoints : dataPoints2,
                    }]
                });
                chart2.render();
                updateChart2();
            });
            function updateChart2() {
                $.getJSON("phpscripts/getmean.php", function(data) {
                    $.each(data, function(key, value) {
                        dataPoints2.shift() 
                        dataPoints2.push({
                        x: parseInt(value[0]),
                        y: parseInt(value[1])
                        });
                    });
                    chart2.render();
                    setTimeout(function(){updateChart2()}, 1000);
                });
            }
        }
    </script>
    <script type="text/javascript">
        function updateInputs(){
            $.ajax({
                type        : 'POST',
                url         : 'phpscripts/getinputs.php',
                encode      : true
            }).done(function(data) {
                $("#devices").html(data);
            }).fail(function(data) {
                console.error(data);
            });
        }
        setInterval(updateInputs, 1000);
    </script>
</body>

</html>
