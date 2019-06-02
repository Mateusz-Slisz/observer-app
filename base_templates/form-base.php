<?php require_once 'base_templates/ti.php' ?>
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

    <?php startblock('script') ?>
    <?php endblock() ?>

    <?php startblock('content') ?>
    <?php endblock() ?>
</body>

</html>

<?php 
if (isset($user_id)) {
    $conn->close();
}
?>