<?php
    session_start();
    require '../vendor/autoload.php';
    $config = include("../config/db.php");
    $user_id = @$_SESSION["user_id"] ?: NULL;

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $delimiter = ",";
    $filename = "observer_" . date('Y-m-d') . ".csv";

    $f = fopen('php://memory', 'w');

    $user_information_sql = "
    SELECT users.username, users.created_at,
    permissions.name, permissions.observe_limit
    FROM users
    JOIN permissions ON users.permission_id = permissions.id
    WHERE users.id=$user_id
    ";

    $result = $conn->query($user_information_sql);
    $user = $result->fetch_assoc();
    
    $observed_pollutions = "
    SELECT keyword FROM observed_pollutions WHERE user_id=$user_id
    ";
    $result = $conn->query($observed_pollutions);

    $pollution_keywords = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) $pollution_keywords[] = $row["keyword"];
    }

    $observed_weather = "
    SELECT keyword FROM observed_weather WHERE user_id=$user_id
    ";
    $result = $conn->query($observed_weather);

    $weather_keywords = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) $weather_keywords[] = $row["keyword"];
    }

    $fields = array(
        'Username', 'Created at',
        'Permission name', 'Permision limit',
        'Pollution observed number', 'Pollution available number', 'Pollution observed cities',
        'Weather observed number', 'Weather available number', 'Weather observed cities'
    );
    fputcsv($f, $fields, $delimiter);

    $lineData = array(
        $user['username'], $user['created_at'],
        $user['name'], $user['observe_limit'],
        count($pollution_keywords), ($user["observe_limit"] - count($pollution_keywords)), implode(", ", $pollution_keywords),
        count($weather_keywords), ($user["observe_limit"] - count($weather_keywords)), implode(", " , $weather_keywords)
    );
    fputcsv($f, $lineData, $delimiter);
    
    fseek($f, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    fpassthru($f);
    exit;
?>