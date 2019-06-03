<?php 
    $file = "profile";
    require 'vendor/autoload.php';
    include 'base_templates/base.php';
?>

<?php startblock('title') ?>
My profile
<?php endblock() ?>

<?php startblock('content') ?>
<?php
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
?>

<div class="row">
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s6"><a class="active" href="#information">Information</a></li>
            <li class="tab col s6"><a href="#export">Export</a></li>
        </ul>
    </div>
    <div id="information" class="col s12">
        <h4 class="center">User</h4>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Permission</th>
                    <th>Limit of adding</th>
                    <th>Created at</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?php echo $user["username"] ?></td>
                    <td><?php echo $user["name"] ?></td>
                    <td><?php echo $user["observe_limit"] ?></td>
                    <td><?php echo $user["created_at"] ?></td>
                </tr>
            </tbody>
        </table>
        <h4 class="center">Pollution</h4>
        <table>
            <thead>
                <tr>
                    <th>Observed number</th>
                    <th>Available number</th>
                    <th>Observed cities</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?php echo count($pollution_keywords) ?></td>
                    <td><?php echo $user["observe_limit"] - count($pollution_keywords) ?></td>
                    <td><?php echo implode(", ", $pollution_keywords) ?></td>
                </tr>
            </tbody>
        </table>
        <h4 class="center">Weather</h4>
        <table>
            <thead>
                <tr>
                    <th>Observed number</th>
                    <th>Available number</th>
                    <th>Observed cities</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?php echo count($weather_keywords) ?></td>
                    <td><?php echo $user["observe_limit"] - count($weather_keywords) ?></td>
                    <td><?php echo implode(", ", $weather_keywords) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="export" class="col s12">
        <div style="margin-top: 20px;">
            <a href="actions/export_to_csv.php">
                <button class="btn waves-effect waves-light blue right">Export to CSV
                    <i class="material-icons right">file_download</i>
                </button>
            </a>
            <a href="actions/export_to_pdf.php">
                <button class="btn waves-effect waves-light right" style="margin-right: 10px;">Export to PDF
                    <i class="material-icons right">file_download</i>
                </button>
            </a>
        </div>
    </div>
</div>
</div>

<?php endblock() ?>