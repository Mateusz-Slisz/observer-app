<?php 
    $file = "observed_weather";
    require '../vendor/autoload.php';
    include '../base_templates/base.php';
    use GuzzleHttp\Client;
?>

<?php startblock('title') ?>
Saved weather
<?php endblock() ?>

<?php startblock('content') ?>

<?php
    $select_sql = "
    SELECT * FROM observed_weather
    WHERE user_id='$user_id';
    ";

    $result = $conn->query($select_sql);
    if ($result && $result->num_rows > 0) {
        $client = new Client([
            'base_uri' => 'https://api.openweathermap.org/data/2.5/weather',
            'timeout'  => 2.0,
        ]);
        
        while($row = $result->fetch_assoc()) {
            $keyword = $row["keyword"];
    
            $response = $client->request('GET', '', [
                'query' => ['q' => $keyword, 'appid' => $weather_api_key, 'units' => 'metric'],
                'http_errors' => false
            ]);
    
            if ($response->getStatusCode() == 200) {
                $weather = json_decode($response->getBody());
                $form_name = "deleteWeather" . $row["id"];
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
                    <form method="POST" action="../actions/delete_weather.php" id="<?php echo $form_name;?>"
                        onClick="document.getElementById('<?php echo $form_name;?>').submit();">
                        <input type="hidden" name="weather_id" value="<?php echo $row["id"];?>">
                        <a href="#" class="red-text">Don't observe this!</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
            }
        }   
    }
?>

<?php endblock() ?>