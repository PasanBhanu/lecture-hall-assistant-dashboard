<?php require_once '../codeblocks/session.php' ; ?>
<?php require_once '../codeblocks/login.php' ; ?>
<?php require_once '../codeblocks/database.php' ; ?>
<?php
    if (isset($_GET['id'])){
        $usr_id = $_GET['id'];
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        $sql = "DELETE FROM tblusers WHERE usr_id='$usr_id'";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
        header ("location: ../users.php");
    }else{
        header ("location: ../users.php");
        die();
    }
?>