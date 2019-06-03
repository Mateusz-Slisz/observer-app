<?php
	session_start();
    $user_id = @$_SESSION["user_id"] ?: NULL;
    $keyword = @$_POST["keyword"] ?: NULL;
    
    if (isset($user_id) && isset($keyword)) {
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $dbname = "observer";
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $select_sql = "
        SELECT * FROM observed_pollutions
        WHERE keyword='$keyword' AND user_id='$user_id'
        ";
        $result = $conn->query($select_sql);

        if ($result->num_rows == 0){
            $insert_sql = "
            INSERT INTO observed_pollutions (keyword, user_id)
            VALUES ('$keyword', '$user_id')
            ";
            $conn->query($insert_sql);

            $conn->close();
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
?>