<?php
    $database_server = "localhost";
    $database_name = "ele";
    $database_username = "root";
    $database_password = "";

    function textencode($str){
		$str = 	str_replace("'","",$str);
		$str = 	str_replace('"',"",$str);
		$str = 	str_replace(";","",$str);
		$str = 	str_replace("--","",$str);
		$str = 	str_replace("%","",$str);
		$str = 	str_replace("=","",$str);
		return $str;
	}

	function countSql($conn, $sql){
		$result = mysqli_query($conn, $sql);
		mysqli_close($conn);
		if ($result){
			return mysqli_num_rows($result);
		}else{
			return 0;
		}
	}
?>