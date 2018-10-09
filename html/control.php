<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/login.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php
    $ctr_id = $ctr_name = "";
    $ctr_allow = 0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        if (isset($_POST['ctr_id'])){ $ctr_id = mysqli_real_escape_string($conn, textencode($_POST['ctr_id']));}
        if (isset($_POST['ctr_name'])){ $ctr_name = mysqli_real_escape_string($conn, textencode($_POST['ctr_name']));}
        if (isset($_POST['ctr_allow'])){
            $ctr_allow = 1;
        }
        if (!empty($_POST['ctr_id']) and !empty($_POST['ctr_name'])){
            if (countSql(mysqli_connect($database_server, $database_username, $database_password, $database_name), "SELECT * FROM tblcontrollers WHERE ctr_id='$ctr_id'") == 0){
                $sql = "INSERT INTO tblcontrollers (ctr_id, ctr_name, ctr_allow, ctr_status) VALUES ('$ctr_id', '$ctr_name', '$ctr_allow', 0)";
                mysqli_query($conn, $sql);
                $ctr_id = $ctr_name = "";
                $ctr_allow = 0;
                echo '<script type="text/javascript">window.onload = function(){toastr.success("Controller added to the system!", "Success");}; </script>';
            }else{
                echo '<script type="text/javascript">window.onload = function(){toastr.error("Controller ID already in the system!", "Error");}; </script>';
            }
        }else{
            echo '<script type="text/javascript">window.onload = function(){toastr.error("Please fill all required data!", "Error");}; </script>';
        }
        mysqli_close($conn);
    }
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblcontrollers";
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
        <?php require_once 'codeblocks/navbar.php'; ?>

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
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h4 class="box-title">Add Controller</h4>
                            <form class="form-horizontal form-material" method="post">
                                <div class="form-group">
                                    <label class="col-md-12">Controller ID</label>
                                    <div class="col-md-12">
                                        <input type="number" name="ctr_id" class="form-control form-control-line" value="<?php echo $ctr_id; ?>" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Controller Name</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="ctr_name" value="<?php echo $ctr_name; ?>" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 switch">Allow Guest Control</label>
                                    <div class="col-sm-9">
                                        <input <?php if ($ctr_allow) { echo'checked';} ?> data-toggle="toggle" data-size="small" type="checkbox" name="ctr_allow">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary" type="submit" >Add Controller</button>
                                    </div>
                                </div>
                            </form>
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
                                            <th>Access</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($controllers){
                                                while ($row = mysqli_fetch_array($controllers)){
                                                    echo '<tr>';
                                                    echo '<td>' . $row['ctr_id'] . '</td>';
                                                    echo '<td>' . $row['ctr_name'] . '</td>';
                                                    if ($row['ctr_allow'] == 0){
                                                        echo '<td><span class="label label-warning">Private</span></td>';
                                                    }else{
                                                        echo '<td><span class="label label-success">Public</span></td>';
                                                    }
                                                    if ($row['ctr_status'] == 0){
                                                        echo '<td><input data-toggle="toggle" data-size="mini" type="checkbox" id="btn_' . $row['ctr_id'] . '" onchange="toggleStatus(' . $row['ctr_id'] . ')"></td>';
                                                    }else{
                                                        echo '<td><input checked data-toggle="toggle" data-size="mini" type="checkbox" id="btn_' . $row['ctr_id'] . '" onchange="toggleStatus(' . $row['ctr_id'] . ')"></td>';
                                                    }
                                                    echo '<td><a href="editcontroller.php?id=' . $row['ctr_id'] . '" class="btn btn-primary btn-xs">Edit</a></td>';
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
