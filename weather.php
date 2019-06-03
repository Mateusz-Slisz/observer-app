<?php 
    $file = "weather";
    require 'vendor/autoload.php';
    include 'base_templates/base.php';
    use GuzzleHttp\Client;

    if (!isset($user_id)) {
        header("Location: login.php");
    }
?>

<?php startblock('title') ?>
Weather
<?php endblock() ?>

<?php startblock('content') ?>
<div>
    <form method="GET" action="weather.php">
        <div class="row">
            <div class="input-field col s12 m12">
                <i class="material-icons prefix">search</i>
                <input id="icon_prefix" type="text" class="validate" name="city" required>
                <label for="icon_prefix">Search weather by city:</label>
            </div>
        </div>
    </form>

    <?php
    $city = @$_GET["city"] ?: NULL;
    
    if (isset($city)) {
        $client = new Client([
            'base_uri' => 'https://api.openweathermap.org/data/2.5/weather',
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', '', [
            'query' => ['q' => $city, 'appid' => $weather_api_key, 'units' => 'metric'],
            'http_errors' => false
        ]);

        if ($response->getStatusCode() == 200) {
            $weather = json_decode($response->getBody());
    ?>
    <div class="row">
        <div class="col s8 offset-s2">
            <div class="card horizontal">
                <div class="card-image red lighten-4">
                    <img src="https://image.flaticon.com/sprites/new_packs/178324-weather.png">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <span class="card-title center">
                            <?php echo $weather->name . " " . $weather->sys->country; ?>
                        </span>
                        <p>Sky:
                            <?php echo $weather->weather[0]->description; ?>, <?php echo $weather->clouds->all; ?>%
                        </p>
                        <p>Temperature: <?php echo $weather->main->temp; ?>&#8451;</p>
                        <p>Pressure: <?php echo $weather->main->pressure; ?>hPa</p>
                        <p>Wind speed: <?php echo $weather->wind->speed; ?>mps</p>
                    </div>
                    <div class="card-action center">
                        <?php
                            $select_sql = "
                            SELECT * FROM observed_weather
                            WHERE keyword='$city' AND user_id='$user_id'
                            ";
                            $result = $conn->query($select_sql);

                            if ($result && $result->num_rows == 1){
                        ?>
                        <form method="POST" action="actions/delete_weather.php" id="deleteWeather"
                            onClick="document.getElementById('deleteWeather').submit();">
                            <input type="hidden" name="weather_id" value="<?php echo $result->fetch_assoc()['id'];?>">
                            <a href="#" class="red-text">Don't observe this!</a>
                        </form>
                        <?php
                            }
                            else {
                                $result = $conn->query("
                                SELECT COUNT(*) as total FROM observed_weather
                                WHERE user_id='$user_id'
                                ");
                                $count_weather = $result->fetch_assoc()['total'];
                                
                                $result = $conn->query("
                                SELECT observe_limit FROM permissions
                                WHERE id=" . $user['permission_id']
                                );

                                $observe_limit = $result->fetch_assoc()['observe_limit'];
                                if ($count_weather == $observe_limit){

                        ?>
                        <a>You raised the limit!</a>
                        <?php
                                }
                                else {
                        ?>
                        <form method="POST" action="actions/add_weather.php" id="addWeather"
                            onClick="document.getElementById('addWeather').submit();">
                            <input type="hidden" name="keyword" value="<?php echo $city ?>">
                            <a href="#" class="green-text">Observe this!</a>
                        </form>
                        <?php 
                               }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        }
        else {
            echo "<h3 class='center'>No results found with given city.</h3>";
        }

    }
    ?>
</div>
<?php 
if (isset($user_id)) {
    $conn->close();
}
?>
<?php endblock() ?>