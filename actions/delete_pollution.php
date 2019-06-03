<?php
	session_start();
    $user_id = @$_SESSION["user_id"] ?: NULL;
    $pollution_id = @$_POST["pollution_id"] ?: NULL;
    
    if (isset($user_id) && isset($pollution_id)) {
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $dbname = "observer";
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $delete_sql = "
        DELETE FROM observed_pollutions
        WHERE id=$pollution_id
        ";
        $conn->query($delete_sql);
        
        $conn->close();
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
?>