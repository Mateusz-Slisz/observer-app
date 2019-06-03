<?php 
    $file = "weather";
    require 'vendor/autoload.php';
    include 'base_templates/base.php';
    use GuzzleHttp\Client;
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
    $api_key = "4ed97abf7202e9d6277f6e18fc6d48f6";

    if (isset($city)) {
        $client = new Client([
            'base_uri' => 'https://api.openweathermap.org/data/2.5/weather',
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', '', [
            'query' => ['q' => $city, 'appid' => $api_key, 'units' => 'metric'],
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
                        <a href="#" class="green-text">Observe this!</a>
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
<?php endblock() ?>