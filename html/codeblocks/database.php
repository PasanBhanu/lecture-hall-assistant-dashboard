<?php
    $database_server = "YOUR_SERVER_URL";
    $database_name = "DATABASE_NAME";
    $database_username = "DATABASE_USERNAME";
    $database_password = "DATABASE_PASSWORD";

// Please check the root folder for the database

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
