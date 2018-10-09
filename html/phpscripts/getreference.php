<?php require_once '../codeblocks/session.php' ; ?>
<?php require_once '../codeblocks/database.php' ; ?>
<?php
    header("Content-Type: application/json; charset=UTF-8");
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblreference WHERE ref_id=1";
    $result = mysqli_query($conn, $sql);
    if ($result){
        $row = mysqli_fetch_assoc($result);
        $ref_value = $row['ref_value'];
    }
    if (isset($_SESSION['reference'])){
        array_push($_SESSION['reference'], (int)$ref_value);
        $_SESSION['time'] = $_SESSION['time'] + 1;
    }else{
        $_SESSION['reference'] = array(0,0,0,0,0,0,0,0,0,0);
        $_SESSION['time'] = 0;
    }
    if (count($_SESSION['reference'])>10){
        array_shift($_SESSION['reference']);
    }
    $json = array();
    $time = $_SESSION['time'] - count($_SESSION['reference']);
    foreach ($_SESSION['reference'] as $value) {
        array_push($json, array($time,$value));
        $time ++;
    }
    echo json_encode($json);
    mysqli_close($conn);
?>