<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/login.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php
    $inp_id = $inp_name = "";
    $inp_weight = $inp_status = 0;
    if (isset($_GET['id'])){
        $inp_id = $_GET['id'];
    }else{
        header ('location: audio.php');
        die();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        if (isset($_POST['inp_name'])){ $inp_name = mysqli_real_escape_string($conn, textencode($_POST['inp_name']));}
        if (isset($_POST['inp_weight'])){ $inp_weight = mysqli_real_escape_string($conn, textencode($_POST['inp_weight']));}
        if (isset($_POST['inp_status'])){
            $inp_status = 1;
        }
        if (!empty($_POST['inp_name']) and isset($_POST['inp_weight'])){
            $sql = "UPDATE tblinput SET inp_name='$inp_name', inp_weight='$inp_weight', inp_status='$inp_status' WHERE inp_id='$inp_id'";
            mysqli_query($conn, $sql);
            echo '<script type="text/javascript">window.onload = function(){toastr.success("Device updated!", "Success");}; </script>';
        }else{
            echo '<script type="text/javascript">window.onload = function(){toastr.error("Please fill all required data!", "Error");}; </script>';
        }
        mysqli_close($conn);
    }
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblinput WHERE inp_id='$inp_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $inp_name = $row['inp_name'];
    $inp_weight = $row['inp_weight'];
    $inp_status = $row['inp_status'];
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <title>Edit Device | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <div id="wrapper">
        <?php require_once 'codeblocks/navbar.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Edit Device : <?php echo $inp_name; ?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="audio.php">Audio</a></li>
                            <li class="active">Edit</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h4 class="box-title">Device Profile</h4>
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
                                    <label class="col-sm-2 switch">Device Status</label>
                                    <div class="col-sm-9">
                                        <input <?php if ($inp_status) { echo'checked';} ?> data-toggle="toggle" data-size="small" type="checkbox" name="inp_status">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary" type="submit" >Save</button>
                                        <a onclick="deleteDevice(<?php echo $inp_id; ?>)" class="btn btn-danger">Delete Device</a>
                                        <a href="audio.php" class="btn btn-default">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once 'codeblocks/footer.php'; ?>
        </div>
    </div>
    <?php require_once 'codeblocks/js.php'; ?>
    <script type="text/javascript">
        function deleteDevice(id){
            bootbox.confirm({
                title: 'Confirm Action',
                closeButton: false,
                message: "Are you sure do you want to delete this input device?",
                buttons: {
                    cancel: {
                        label: 'No',
                        className: 'btn-default'
                    },
                    confirm: {
                        label: 'Yes',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result){
                        window.location.href = "phpscripts/deleteaudio.php?id=" + id;
                    }
                }
            });
        }
    </script>
</body>

</html>
