<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/login.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php
    $ctr_id = $ctr_name = "";
    $ctr_allow = $ctr_status = 0;
    if (isset($_GET['id'])){
        $ctr_id = $_GET['id'];
    }else{
        header ('location: control.php');
        die();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        if (isset($_POST['ctr_name'])){ $ctr_name = mysqli_real_escape_string($conn, textencode($_POST['ctr_name']));}
        if (isset($_POST['ctr_allow'])){
            $ctr_allow = 1;
        }
        if (isset($_POST['ctr_status'])){
            $ctr_status = 1;
        }
        if (!empty($_POST['ctr_id']) and !empty($_POST['ctr_name'])){
            $sql = "UPDATE tblcontrollers SET ctr_name='$ctr_name', ctr_allow='$ctr_allow', ctr_status='$ctr_status' WHERE ctr_id='$ctr_id'";
            mysqli_query($conn, $sql);
            echo '<script type="text/javascript">window.onload = function(){toastr.success("Controller updated!", "Success");}; </script>';
        }else{
            echo '<script type="text/javascript">window.onload = function(){toastr.error("Please fill all required data!", "Error");}; </script>';
        }
        mysqli_close($conn);
    }
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblcontrollers WHERE ctr_id='$ctr_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $ctr_name = $row['ctr_name'];
    $ctr_allow = $row['ctr_allow'];
    $ctr_status = $row['ctr_status'];
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <title>Edit Controller | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <div id="wrapper">
        <?php require_once 'codeblocks/navbar.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Edit Controller : <?php echo $ctr_name; ?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="control.php">Controllers</a></li>
                            <li class="active">Edit</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h4 class="box-title">Controller Profile</h4>
                            <form class="form-horizontal form-material" method="post">
                                <div class="form-group">
                                    <label class="col-md-12">Controller ID</label>
                                    <div class="col-md-12">
                                        <input type="number" name="ctr_id" class="form-control form-control-line" value="<?php echo $ctr_id; ?>" readonly> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Controller Name</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control form-control-line" name="ctr_name" value="<?php echo $ctr_name; ?>"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 switch">Allow Guest Control</label>
                                    <div class="col-sm-9">
                                        <input <?php if ($ctr_allow) { echo'checked';} ?> data-toggle="toggle" data-size="small" type="checkbox" name="ctr_allow">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 switch">Controller Status</label>
                                    <div class="col-sm-9">
                                        <input <?php if ($ctr_status) { echo'checked';} ?> data-toggle="toggle" data-size="small" type="checkbox" name="ctr_status">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary" type="submit" >Save</button>
                                        <a onclick="deleteController(<?php echo $ctr_id; ?>)" class="btn btn-danger">Delete Controller</a>
                                        <a href="control.php" class="btn btn-default">Back</a>
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
        function deleteController(id){
            bootbox.confirm({
                title: 'Confirm Action',
                closeButton: false,
                message: "Are you sure do you want to delete this controller?",
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
                        window.location.href = "phpscripts/deletecontroller.php?id=" + id;
                    }
                }
            });
        }
    </script>
</body>

</html>
