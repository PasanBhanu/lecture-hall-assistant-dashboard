<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/login.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php
    $usr_id = $_SESSION['usr_id'];
    $usr_name = $usr_username = $usr_password = "";
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblusers WHERE usr_id='$usr_id'";
    $result = mysqli_query($conn, $sql);
    if ($result){
        $row = mysqli_fetch_assoc($result);
        $usr_name = $row['usr_name'];
        $usr_password = $row['usr_password'];
        $usr_username = $row['usr_username'];
    }
    mysqli_close($conn);
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['profile'])){
            $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
            if (isset($_POST['usr_name'])){ $usr_name = mysqli_real_escape_string($conn, textencode($_POST['usr_name'])); }
            if (!empty($_POST['usr_name'])){
                $sql = "UPDATE tblusers SET usr_name='$usr_name' WHERE usr_id='$usr_id'";
                mysqli_query($conn, $sql);
                $_SESSION['usr_name'] = $usr_name;
                echo '<script type="text/javascript">window.onload = function(){toastr.success("Profile Updated!", "Success");}; </script>';
            }else{
                echo '<script type="text/javascript">window.onload = function(){toastr.error("Please enter your name!", "Error");}; </script>';
            }
            mysqli_close($conn);
        }
        if (isset($_POST['password'])){
            $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
            $usr_old_password = base64_encode($_POST['usr_old_password']);
            $usr_new_password = base64_encode($_POST['usr_new_password']);
            $usr_confirm_password = base64_encode($_POST['usr_confirm_password']);
            if (!empty($_POST['usr_old_password']) and !empty($_POST['usr_new_password']) and !empty($_POST['usr_confirm_password'])){
                if ($usr_new_password == $usr_confirm_password){
                    if(countSql(mysqli_connect($database_server, $database_username, $database_password, $database_name), "SELECT * FROM tblusers WHERE usr_id='$usr_id' AND usr_password='$usr_old_password'") > 0){
                        $sql = "UPDATE tblusers SET usr_password='$usr_new_password' WHERE usr_id='$usr_id'";
                        mysqli_query($conn, $sql);
                        echo '<script type="text/javascript">window.onload = function(){toastr.success("Password Updated!", "Success");}; </script>';
                    }else{
                        echo '<script type="text/javascript">window.onload = function(){toastr.error("Current password does not match!", "Error");}; </script>';
                    }
                }else{
                    echo '<script type="text/javascript">window.onload = function(){toastr.error("Password and confirm password does not match!", "Error");}; </script>';
                }
            }else{
                echo '<script type="text/javascript">window.onload = function(){toastr.error("Please fill all required data!", "Error");}; </script>';
            }
            mysqli_close($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <title>Profile | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <div id="wrapper">
        <?php require_once 'codeblocks/navbar.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">My Profile</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li class="active">Profile</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h4 class="box-title">My Profile</h4>
                            <form class="form-horizontal form-material" method="post">
                                <div class="form-group">
                                    <label class="col-md-12">Full Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="usr_name" class="form-control form-control-line" value="<?php echo $usr_name; ?>" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Username</label>
                                    <div class="col-md-12">
                                        <input type="email" class="form-control form-control-line" name="usr_username" value="<?php echo $usr_username; ?>" readonly> </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success" type="submit" name="profile">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="white-box">
                            <h4 class="box-title">Change Password</h4>
                            <form class="form-horizontal form-material" method="post">
                                <div class="form-group">
                                    <label class="col-md-12">Old Password</label>
                                    <div class="col-md-12">
                                        <input type="password" name="usr_old_password" class="form-control form-control-line" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">New Password</label>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control form-control-line" name="usr_new_password" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Confirm Password</label>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control form-control-line" name="usr_confirm_password" required> </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-danger" type="submit" name="password">Change Password</button>
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
</body>

</html>
