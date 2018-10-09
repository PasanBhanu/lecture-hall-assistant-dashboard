<?php require_once '../codeblocks/database.php' ; ?>
<?php
    $conn = mysqli_connect($database_server, $database_username, $database_password, $database_name);
    $sql = "SELECT * FROM tblinput WHERE inp_status=1";
    $audio = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($audio){
        while ($row = mysqli_fetch_array($audio)){
            echo '<tr>';
            echo '<td>' . $row['inp_id'] . '</td>';
            echo '<td>' . $row['inp_name'] . '</td>';
            echo '<td>' . $row['inp_value'] . '</td>';
            echo '</tr>';
        }
    }
?>