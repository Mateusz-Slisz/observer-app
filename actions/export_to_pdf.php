<?php
    session_start();
    require '../vendor/autoload.php';
    $config = include("../config/db.php");
    $user_id = @$_SESSION["user_id"] ?: NULL;

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ustawienia dla gereowanego pliku pdf
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setFontSubsetting(true);

    $pdf->SetFont('freeserif', '', 12);


    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage();

    $user_information_sql = "
    SELECT users.username, users.created_at,
    permissions.name, permissions.observe_limit
    FROM users
    JOIN permissions ON users.permission_id = permissions.id
    WHERE users.id=$user_id
    ";

    $result = $conn->query($user_information_sql);
    $user = $result->fetch_assoc();
    $pdf->writeHTML("<h1>Document has been written at: " . date('Y-m-d H:i:s') . "</h1><br>");
    $pdf->writeHTML(
        "<h2>User information</h2>
        <p>Username: " . $user["username"] . "</p>
        <p>Created at: " . $user["created_at"] . "</p><br>", 
        true, 
        false,
        true, 
        false,
        ''
    );
    $pdf->writeHTML(
        "<h2>Permission information</h2>
        <p>Permission name: " . $user["name"] . "</p>
        <p>Permission limit: " . $user["observe_limit"] . "</p><br>", 
        true,
        false,
        true,
        false,
        ''
    ); 
    
    $observed_pollutions = "
    SELECT keyword FROM observed_pollutions WHERE user_id=$user_id
    ";
    $result = $conn->query($observed_pollutions);

    $pollution_keywords = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) $pollution_keywords[] = $row["keyword"];
    }

    $pdf->writeHTML(
        "<h2>Pollution information</h2>
        <p>Observed number: " . count($pollution_keywords) . "</p>
        <p>Available number: " . ($user["observe_limit"] - count($pollution_keywords)) . "</p>
        <p>Observed cities: " . implode(", ", $pollution_keywords) . "</p><br>", 
        true,
        false,
        true,
        false,
        ''
    ); 

    $observed_weather = "
    SELECT keyword FROM observed_weather WHERE user_id=$user_id
    ";
    $result = $conn->query($observed_weather);

    $weather_keywords = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) $weather_keywords[] = $row["keyword"];
    }

    $pdf->writeHTML(
        "<h2>Weather information</h2>
        <p>Observed number: " . count($weather_keywords) . "</p>
        <p>Available number: " . ($user["observe_limit"] - count($weather_keywords)) . "</p>
        <p>Observed cities: " . implode(", ", $weather_keywords) . "</p><br>", 
        true,
        false,
        true,
        false,
        ''
    ); 
    ob_end_clean();
    $pdf->Output();
?>