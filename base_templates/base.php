<?php
    require_once 'base_templates/ti.php';
    include('config/db.php');
    session_start();
    $user = NULL;
    $user_id = @$_SESSION["user_id"] ?: NULL;
    $weather_api_key = @$_SESSION["weather_api_key"] ?: NULL;
    $pollution_api_key = @$_SESSION["pollution_api_key"] ?: NULL;
    
    if (isset($user_id)) {
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $select_sql = "
        SELECT * FROM users WHERE id='$user_id'
        ";
        $result = $conn->query($select_sql);
        $user = $result->fetch_assoc();
    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>
        <?php startblock('title') ?>
        Base template
        <?php endblock() ?>
    </title>
    <meta name="author" content="Mateusz Slisz">
    <meta name="description" content="Observer app - observe the surrounding world.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <header>
        <nav class="red lighten-2">
            <div class="nav-wrapper">
                <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <a href="#" class="brand-logo center"><i class='material-icons'>brightness_1</i>Observer</a>
            </div>
        </nav>
    </header>

    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li id="sidenav-logo">
            <a href="#" class="subheader brand-logo center">
                <img src="res/earth.svg" alt="Earth planet" height="100px">
            </a>
        </li>
        <?php
		if (isset($user_id)) {
        ?>
        <li>
            <a class="subheader">Logged as <?php echo $user["username"] ?></a>
        </li>
        <li <?php if ($file == "profile") echo "class='active'";?>>
            <a href='profile.php'>My profile</a>
        </li>
        <li <?php if ($file == "observed_weather") echo "class='active'";?>>
            <a href='observed_weather.php'>Observed weather</a>
        </li>
        <li <?php if ($file == "observed_pollution") echo "class='active'";?>>
            <a href='observed_pollution.php'>Observed pollution</a>
        </li>
        <li>
            <a href='actions/logout.php'>Logout<i class='material-icons right'>chevron_right</i></a>
        </li>
        <?php
        }
        else {
        ?>
        <li>
            <a href='signup.php'>Signup</a>
        </li>
        <li>
            <a href='login.php'>Login</a>
        </li>
        <?php
        }
        ?>
        <li>
            <div class="divider"></div>
        </li>
        <li <?php if ($file == "index") echo "class='active'";?>>
            <a href="index.php">Main</a>
        </li>
        <li <?php if ($file == "weather") echo "class='active'";?>>
            <a href="weather.php">Weather</a>
        </li>
        <li <?php if ($file == "pollution") echo "class='active'";?>>
            <a href="pollution.php">Pollution</a>
        </li>
    </ul>
    <main>
        <div class="container">
            <?php startblock('content') ?>
            <?php endblock() ?>
        </div>
    </main>

    <script type="text/javascript">
    $('.dropdown-trigger').dropdown();

    $(document).ready(function() {
        $('.sidenav').sidenav();
    });

    $(document).ready(function() {
        $('.collapsible').collapsible();
    });

    $(document).ready(function() {
        $('.tabs').tabs();
    });
    </script>

    <?php startblock('script') ?>
    <?php endblock() ?>
</body>

</html>