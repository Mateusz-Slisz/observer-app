<?php 
    require 'vendor/autoload.php';
    $file = "pollution";
    include 'base_templates/base.php';

    function air_condition_status($value) {
        if ($value < 51) {
            echo "<p class='green-text'>Good</p>";
        }
        elseif ($value < 101) {
            echo "<p class='yellow-text'>Moderate</p>";
        }
        elseif ($value < 151) {
            echo "<p class='orange-text'>Unhealthy</p>";
        }
        elseif ($value < 201) {
            echo "<p class='red-text'>Unhealthy+</p>";
        }
        elseif ($value < 301) {
            echo "<p class='purple-text'>Very unhealthy</p>";
        }
        else {
            echo "<p class='red accent-4-text'>Hazzardous</p>";
        }
    }
?>


<?php startblock('title') ?>
Pollution
<?php endblock() ?>

<?php startblock('content') ?>
<div>
    <form method="GET" action="pollution.php">
        <div class="row">
            <div class="input-field col s12 m12">
                <i class="material-icons prefix">search</i>
                <input id="icon_prefix" type="text" class="validate" name="city" required>
                <label for="icon_prefix">Search pollution by city:</label>
            </div>
        </div>
    </form>

    <?php
    use GuzzleHttp\Client;
    $city = @$_GET["city"] ?: NULL;
    $api_key = "c68b28f7053af4bce8a47c9c0443e9c268d4aabf";

    if (isset($city)) {
        $client = new Client([
            'base_uri' => 'http://api.waqi.info/feed/',
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', $city . '/', [
            'query' => ['token' => $api_key], 'http_errors' => false
        ]);

        if ($response->getStatusCode() == 200) {
            $pollution = json_decode($response->getBody());
            if ($pollution->status == "ok") {

    ?>
    <div class="row">
        <div class="col s8 offset-s2">
            <div class="card horizontal">
                <div class="card-image red lighten-4">
                    <img src="https://image.flaticon.com/sprites/new_packs/1793199-pollution.png">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <span class="card-title center" style="font-size: 20px !important;">
                            <?php
                                $info = explode(",", $pollution->data->city->name);
                                foreach ($info as &$value) {
                                    echo $value . "<br>";
                                }
                            ?>
                        </span>
                        <p>Air condition: <?php echo $pollution->data->aqi; ?></p>
                        <?php air_condition_status($pollution->data->aqi); ?>
                    </div>
                    <div class="card-action center">
                        <a href="#">Save this pollution!</a>
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
        else {
            echo "<h3 class='center'>No results found with given city.</h3>";
        }

    }
    ?>
</div>


<?php endblock() ?>