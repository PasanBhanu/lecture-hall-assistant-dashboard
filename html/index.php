<?php require_once 'codeblocks/session.php' ; ?>
<?php require_once 'codeblocks/database.php' ; ?>
<?php
    $errorData = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        if (isset($_POST['usr_username'])){ $usr_username = mysqli_real_escape_string($conn, textencode($_POST['usr_username'])); }
        if (!empty($_POST['usr_username']) and !empty($_POST['usr_password'])){
            $usr_password = base64_encode($_POST['usr_password']);
            $sql = "SELECT * FROM tblusers WHERE usr_username='$usr_username'";
            $result = mysqli_query($conn, $sql);
            if ($result){
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    if($usr_password == $row['usr_password']){
                        $usr_id = $row['usr_id'];
                        $_SESSION['usr_id'] = $usr_id;
                        $_SESSION['usr_name'] = $row['usr_name'];
                        header('Location: dashboard.php');
                    }else{
                        $errorData = "Username or Password does not match!";
                    }
                }else{
                    $errorData = "Username or Password does not match!";
                }
            }else{
                $errorData = "Username or Password does not match!";
            }
		}else{
			$errorData = "Please fill all required data.";
		}
		mysqli_close($conn);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'codeblocks/header.php'; ?>
    <title>Login | Lecture Hall Assistant</title>
</head>

<body class="fix-header">
    <?php require_once 'codeblocks/preloader.php'; ?>
    <section id="wrapper" class="new-login-register">
        <div class="lg-info-panel">
            <div class="inner-panel">
                <div class="lg-content">
                    <h2>Lecture Hall Assistant <br> University Of Moratuwa</h2>
                    <p class="text-muted">Welcome to Lecture Hall Assistant Dashboard. Please login manage system settings.</p>
                </div>
            </div>
        </div>
        <div class="new-login-box">
            <div class="white-box">
                <h3 class="box-title m-b-0">Sign In</h3>
                <small>Enter your login details below</small>
                <form class="form-horizontal new-lg-form" id="loginform" method="post">
                    <div class="form-group  m-t-20">
                        <div class="col-xs-12">
                        <label>Username</label>
                        <input name="usr_username" class="form-control" type="email" required placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                        <label>Password</label>
                        <input name="usr_password" class="form-control" type="password" required placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">Log In</button>
                        <a href="st_dashboard.php" class="btn btn-success btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light">Student Access</a>
                        </div>
                    </div>
                    <p class="mt-5 mb-3 text-muted" align="center"><font color="red"><?php echo $errorData ?></font></p>
                </form>
            </div>
        </div>            
    </section>
    <?php require_once 'codeblocks/js.php'; ?>
</body>

</html>
