<?php require_once 'codeblocks/database.php' ; ?>
<?php
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblcontrollers WHERE ctr_allow='1'";
    $controllers = mysqli_query($conn, $sql);
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <title>Control Center | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <div id="wrapper">
        <?php require_once 'codeblocks/st_navbar.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Controller Management</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li class="active">Controllers</li>
                        </ol>
                    </div>
                </div>
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">All Controllers</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Controller ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($controllers){
                                                while ($row = mysqli_fetch_array($controllers)){
                                                    echo '<tr>';
                                                    echo '<td>' . $row['ctr_id'] . '</td>';
                                                    echo '<td>' . $row['ctr_name'] . '</td>';
                                                    if ($row['ctr_status'] == 0){
                                                        echo '<td><input data-toggle="toggle" data-size="mini" type="checkbox" id="btn_' . $row['ctr_id'] . '" onchange="toggleStatus(' . $row['ctr_id'] . ')"></td>';
                                                    }else{
                                                        echo '<td><input checked data-toggle="toggle" data-size="mini" type="checkbox" id="btn_' . $row['ctr_id'] . '" onchange="toggleStatus(' . $row['ctr_id'] . ')"></td>';
                                                    }
                                                    echo '</tr>';
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once 'codeblocks/footer.php'; ?>
        </div>
    </div>
    <?php require_once 'codeblocks/js.php'; ?>
    <script type="text/javascript">
        function toggleStatus(id){
            $.ajax({
                type        : 'POST',
                url         : 'phpscripts/togglecontroller.php', 
                data        : {ctr_id: id},
                dataType    : 'json', 
                encode      : true
            }).done(function(data) {
                toastr.success("Controller status updated!", "Success");
            }).fail(function(data) {
                console.error(data);
            });
        }
    </script>
</body>

</html>
