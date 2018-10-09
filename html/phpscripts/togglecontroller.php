<?php require_once '../codeblocks/database.php' ; ?>
<?php
    $ctr_id = $_POST['ctr_id'];
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblcontrollers WHERE ctr_id='$ctr_id'";
    $results = mysqli_query($conn, $sql);
    if ($results){
        $row = mysqli_fetch_array($results);
        $ctr_status = $row['ctr_status'];
    }
    if ($ctr_status == 0){
        $ctr_status = 1;
    }else{
        $ctr_status = 0;
    }
    $sql = "UPDATE tblcontrollers SET ctr_status='$ctr_status' WHERE ctr_id='$ctr_id'";
    mysqli_query($conn, $sql);
    echo json_encode(array("status" => "SUCCESS"));
    mysqli_close($conn);
?>