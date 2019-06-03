<?php 
    $file = "saved_pollution";
    require 'vendor/autoload.php';
    include 'base_templates/base.php';
    include 'helpers.php';
    use GuzzleHttp\Client;
?>

<?php startblock('title') ?>
Saved pollution
<?php endblock() ?>

<?php startblock('content') ?>

<?php
    $select_sql = "
    SELECT * FROM observed_pollutions
    WHERE user_id='$user_id';
    ";

    $result = $conn->query($select_sql);
    if ($result && $result->num_rows > 0) {
        $client = new Client([
            'base_uri' => 'http://api.waqi.info/feed/',
            'timeout'  => 2.0,
        ]);
        
        while($row = $result->fetch_assoc()) {
            $keyword = $row["keyword"];
    
            $response = $client->request('GET', $keyword . '/', [
                'query' => ['token' => $pollution_api_key], 'http_errors' => false
            ]);
    
            if ($response->getStatusCode() == 200) {
                $pollution = json_decode($response->getBody());
                if ($pollution->status == "ok") {
                    $form_name = "deletePollution" . $row["id"];
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
                    <form method="POST" action="actions/delete_pollution.php" id="<?php echo $form_name; ?>"
                        onClick="document.getElementById('<?php echo $form_name; ?>').submit();">
                        <input type="hidden" name="pollution_id" value="<?php echo $row['id'];?>">
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
    }
?>

<?php endblock() ?>