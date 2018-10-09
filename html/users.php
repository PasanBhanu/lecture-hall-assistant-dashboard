<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/login.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php
    $usr_name = $usr_username = $usr_password = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        if (isset($_POST['usr_name'])){ $usr_name = mysqli_real_escape_string($conn, textencode($_POST['usr_name']));}
        if (isset($_POST['usr_username'])){ $usr_username = mysqli_real_escape_string($conn, textencode($_POST['usr_username']));}
        if (!empty($_POST['usr_name']) and !empty($_POST['usr_username']) and !empty($_POST['usr_password']) and !empty($_POST['usr_confirm_password']) ){
            if (countSql(mysqli_connect($database_server, $database_username, $database_password, $database_name), "SELECT * FROM tblusers WHERE usr_username='$usr_username'") == 0){
                $usr_password = base64_encode($_POST['usr_password']);
                $usr_confirm_password = base64_encode($_POST['usr_confirm_password']);
                if ($usr_password == $usr_confirm_password){
                    $sql = "INSERT INTO tblusers (usr_name, usr_username, usr_password) VALUES ('$usr_name', '$usr_username', '$usr_password')";
                    mysqli_query($conn, $sql);
                    $usr_name = $usr_username = $usr_password = "";
                    echo '<script type="text/javascript">window.onload = function(){toastr.success("User added to the system!", "Success");}; </script>';
                }else{
                    echo '<script type="text/javascript">window.onload = function(){toastr.error("Password and confirm password does not match!", "Error");}; </script>';
                }
            }else{
                echo '<script type="text/javascript">window.onload = function(){toastr.error("Username already in the system!", "Error");}; </script>';
            }
        }else{
            echo '<script type="text/javascript">window.onload = function(){toastr.error("Please fill all required data!", "Error");}; </script>';
        }
        mysqli_close($conn);
    }
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblusers";
    $users = mysqli_query($conn, $sql);
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <title>Users | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <div id="wrapper">
        <?php require_once 'codeblocks/navbar.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">User Management</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li class="active">Users</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h4 class="box-title">Add User</h4>
                            <form class="form-horizontal form-material" method="post">
                                <div class="form-group">
                                    <label class="col-md-12">Full Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="usr_name" class="form-control form-control-line" value="<?php echo $usr_name; ?>" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Username</label>
                                    <div class="col-md-12">
                                        <input type="email" class="form-control form-control-line" name="usr_username" value="<?php echo $usr_username; ?>"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Password</label>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control form-control-line" name="usr_password"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Confirm Password</label>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control form-control-line" name="usr_confirm_password"> </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary" type="submit">Add User</button>
                                    </div>
                                </div>
                            </form>
                            <p class="mt-5 mb-3 text-muted"><font color="red"><?php echo $error ?></font></p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">All Users</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($users){
                                                while ($row = mysqli_fetch_array($users)){
                                                    echo '<tr>';
                                                    echo '<td>' . $row['usr_id'] . '</td>';
                                                    echo '<td>' . $row['usr_name'] . '</td>';
                                                    echo '<td>' . $row['usr_username'] . '</td>';
                                                    if ($_SESSION['usr_id'] == $row['usr_id']){
                                                        echo '<td><a href="profile.php" class="btn btn-success btn-xs">My Profile</a></td>';
                                                    }else{
                                                        echo '<td><a onclick=\'resetPassword(' . $row['usr_id'] . ', "' . $row['usr_name'] . '")\' class="btn btn-primary btn-xs">Reset Password</a> <a onclick=\'deleteUser(' . $row['usr_id'] . ', "' . $row['usr_name'] . '")\' class="btn btn-danger btn-xs">Delete</a></td>';
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
        function deleteUser(id, name){
            bootbox.confirm({
                title: 'Confirm Action',
                closeButton: false,
                message: "Are you sure do you want to delete " + name + " ?",
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
                        window.location.href = "phpscripts/deleteuser.php?id=" + id;
                    }
                }
            });
        }
        function resetPassword(id, name){
            bootbox.confirm({
                title: 'Confirm Action',
                closeButton: false,
                message: "Are you sure do you want to reset password of " + name + " to default password? <br> <strong>Default Password : 0000</strong>",
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
                        window.location.href = "phpscripts/resetpassword.php?id=" + id;
                    }
                }
            });
        }
    </script>
</body>

</html>
