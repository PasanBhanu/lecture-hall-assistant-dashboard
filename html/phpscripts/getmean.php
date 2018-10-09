<?php require_once '../codeblocks/session.php' ; ?>
<?php require_once '../codeblocks/database.php' ; ?>
<?php
    header("Content-Type: application/json; charset=UTF-8");
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblinput WHERE inp_status=1";
    $result = mysqli_query($conn, $sql);
    $total_weight = 0;
    $mean = 0;
    while($row = mysqli_fetch_assoc($result)) {
        $mean += $row['inp_value'] * $row['inp_weight'];
        $total_weight += $row['inp_weight'];
    }
    $mean = $mean / $total_weight;
    $sql = "SELECT * FROM tblreference WHERE ref_id=1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $reference = $row['ref_value'];
    $mean = $mean - $reference;
    if (isset($_SESSION['mean'])){
        array_push($_SESSION['mean'], (int)$mean);
    }else{
        $_SESSION['mean'] = array(0,0,0,0,0,0,0,0,0,0);
    }
    if (count($_SESSION['mean'])>10){
        array_shift($_SESSION['mean']);
    }
    $json = array();
    $time = $_SESSION['time'] - count($_SESSION['mean']);
    foreach ($_SESSION['mean'] as $value) {
        array_push($json, array($time,$value));
        $time ++;
    }
    echo json_encode($json);
    mysqli_close($conn);
?>