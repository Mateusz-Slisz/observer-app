<?php
    $file = "pollution";
    require 'vendor/autoload.php';
    include 'base_templates/base.php';
    include 'helpers.php';
    use GuzzleHttp\Client;
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
                    <img src="https://img.icons8.com/color/420/windy-weather.png">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <span class="card-title center" style="font-size: 20px !important;">
                            <?php
                                $info = explode(",", $pollution->data->city->name);
                                echo $info[0];
                            ?>
                        </span>
                        <p>Address: <?php foreach (array_slice($info, 1) as &$value) echo $value;?></p>
                        <p>Air condition: <?php echo $pollution->data->aqi; ?></p>
                        <p>Status: <?php air_condition_status($pollution->data->aqi); ?></p>
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
        else {
            echo "<h3 class='center'>No results found with given city.</h3>";
        }

    }
    ?>
</div>


<?php endblock() ?>