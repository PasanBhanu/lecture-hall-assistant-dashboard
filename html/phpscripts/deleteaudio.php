<?php require_once '../codeblocks/session.php' ; ?>
<?php require_once '../codeblocks/login.php' ; ?>
<?php require_once '../codeblocks/database.php' ; ?>
<?php
    if (isset($_GET['id'])){
        $inp_id = $_GET['id'];
        $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
        $sql = "DELETE FROM tblinput WHERE inp_id='$inp_id'";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
        header ("location: ../audio.php");
    }else{
        header ("location: ../audio.php");
        die();
    }
?>