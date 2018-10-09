<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/login.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php
    $inp_id = $inp_name = "";
    $inp_weight = 0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        if (isset($_POST['inp_id'])){ $inp_id = mysqli_real_escape_string($conn, textencode($_POST['inp_id']));}
        if (isset($_POST['inp_name'])){ $inp_name = mysqli_real_escape_string($conn, textencode($_POST['inp_name']));}
        if (isset($_POST['inp_weight'])){ $inp_weight = mysqli_real_escape_string($conn, textencode($_POST['inp_weight']));}
        if (!empty($_POST['inp_id']) and !empty($_POST['inp_name'])){
            if (countSql(mysqli_connect($database_server, $database_username, $database_password, $database_name), "SELECT * FROM tblinput WHERE inp_id='$inp_id'") == 0){
                $sql = "INSERT INTO tblinput (inp_id, inp_name, inp_weight) VALUES ('$inp_id', '$inp_name', '$inp_weight')";
                mysqli_query($conn, $sql);
                $inp_id = $inp_name = "";
                $inp_weight = 0;
                echo '<script type="text/javascript">window.onload = function(){toastr.success("Device added to the system!", "Success");}; </script>';
            }else{
                echo '<script type="text/javascript">window.onload = function(){toastr.error("Device ID already in the system!", "Error");}; </script>';
            }
        }else{
            echo '<script type="text/javascript">window.onload = function(){toastr.error("Please fill all required data!", "Error");}; </script>';
        }
        mysqli_close($conn);
    }
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblinput";
    $audio = mysqli_query($conn, $sql);
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <title>Audio Setup | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <div id="wrapper">
        <?php require_once 'codeblocks/navbar.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Audio Management</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li class="active">Audio</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h4 class="box-title">Add Audio Input</h4>
                            <form class="form-horizontal form-material" method="post">
                                <div class="form-group">
                                    <label class="col-md-12">Device ID</label>
                                    <div class="col-md-12">
                                        <input type="number" name="inp_id" class="form-control form-control-line" value="<?php echo $inp_id; ?>" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Device Name</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="inp_name" value="<?php echo $inp_name; ?>" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Weight</label>
                                    <div class="col-md-12">
                                        <input type="number" name="inp_weight" class="form-control form-control-line" value="<?php echo $inp_weight; ?>" required min=0 max=100> </div>
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
                            <h3 class="box-title">Audio Devices</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Device ID</th>
                                            <th>Name</th>
                                            <th>Weight</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($audio){
                                                while ($row = mysqli_fetch_array($audio)){
                                                    echo '<tr>';
                                                    echo '<td>' . $row['inp_id'] . '</td>';
                                                    echo '<td>' . $row['inp_name'] . '</td>';
                                                    echo '<td>' . $row['inp_weight'] . '</td>';
                                                    if ($row['inp_status'] == 0){
                                                        echo '<td><span class="label label-warning">Offline</span></td>';
                                                    }else{
                                                        echo '<td><span class="label label-success">Live</span></td>';
                                                    }
                                                    echo '<td><a href="editaudio.php?id=' . $row['inp_id'] . '" class="btn btn-primary btn-xs">Edit</a></td>';
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
</body>

</html>
