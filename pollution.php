<?php
    $file = "pollution";
    require 'vendor/autoload.php';
    include 'base_templates/base.php';
    include 'helpers.php';
    use GuzzleHttp\Client;

    if (!isset($user_id)) {
        header("Location: login.php");
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
    $city = @$_GET["city"] ?: NULL;

    if (isset($city)) {
        $client = new Client([
            'base_uri' => 'http://api.waqi.info/feed/',
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', $city . '/', [
            'query' => ['token' => $pollution_api_key], 'http_errors' => false
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
                        <?php
                            $select_sql = "
                            SELECT * FROM observed_pollutions
                            WHERE keyword='$city' AND user_id='$user_id'
                            ";
                            $result = $conn->query($select_sql);

                            if ($result && $result->num_rows == 1){
                        ?>
                        <form method="POST" action="actions/delete_pollution.php" id="deletePollution"
                            onClick="document.getElementById('deletePollution').submit();">
                            <input type="hidden" name="pollution_id" value="<?php echo $result->fetch_assoc()['id'];?>">
                            <a href="#" class="red-text">Don't observe this!</a>
                        </form>
                        <?php
                            }
                            else {
                                $result = $conn->query("
                                SELECT COUNT(*) as total FROM observed_pollutions
                                WHERE user_id='$user_id'
                                ");
                                $count_pollution = $result->fetch_assoc()['total'];
                                
                                $result = $conn->query("
                                SELECT observe_limit FROM permissions
                                WHERE id=" . $user['permission_id']
                                );

                                $observe_limit = $result->fetch_assoc()['observe_limit'];
                                if ($count_pollution == $observe_limit){

                        ?>
                        <a>You raised the limit!</a>
                        <?php
                                }
                                else {
                        ?>
                        <form method="POST" action="actions/add_pollution.php" id="addPollution"
                            onClick="document.getElementById('addPollution').submit();">
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
        else {
            echo "<h3 class='center'>No results found with given city.</h3>";
        }

    }
    ?>
</div>


<?php endblock() ?>