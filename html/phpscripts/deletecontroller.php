<?php require_once '../codeblocks/session.php' ; ?>
<?php require_once '../codeblocks/login.php' ; ?>
<?php require_once '../codeblocks/database.php' ; ?>
<?php
    if (isset($_GET['id'])){
        $ctr_id = $_GET['id'];
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        $sql = "DELETE FROM tblcontrollers WHERE ctr_id='$ctr_id'";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
        header ("location: ../control.php");
    }else{
        header ("location: ../control.php");
        die();
    }
?>