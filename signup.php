<?php include 'base_templates/form-base.php' ?>

<?php startblock('title') ?>
Signup
<?php endblock() ?>

<?php startblock('script') ?>
<script type="text/javascript">
$(document).ready(function() {
    $('select').formSelect();
});
</script>
<?php endblock() ?>

<?php startblock('content') ?>
<div class="container" style="margin-top:90px;">
    <div class="row">
        <div class="col s6 offset-s3">
            <div class="card-panel z-depth-5">
                <h4 class="center">Signup</h4>
                <div class="row">
                    <form class="col s12 m12" method="POST" action="signup.php">
                        <div class="row">
                            <div class="input-field col s12 m12">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="icon_prefix" type="text" name="username" class="validate" required>
                                <label for="icon_prefix">Username</label>
                            </div>

                            <div class="input-field col s12 m12">
                                <i class="material-icons prefix">lock</i>
                                <input id="icon_password" type="password" name="password" class="validate" required>
                                <label for="icon_password">Password</label>
                            </div>

                            <div class="input-field col s12 m12">
                                <i class="material-icons prefix">view_headline</i>
                                <select name="permission" required>
                                    <option value="1">Silver</option>
                                    <option value="2">Gold</option>
                                    <option value="3">Diamond</option>
                                </select>
                                <label>Permission select</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <button class="btn waves-effect waves-light col s12 " type="submit" name="action">Signup
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $username = @$_POST["username"] ?: NULL;
    $password = @$_POST["password"] ?: NULL;
    $permission = @$_POST["permission"] ?: NULL;
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "observer";
    
    if (isset($username, $password, $permission)) {
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $select_sql = "
        SELECT username FROM users WHERE username='$username'
        ";
        $result = $conn->query($select_sql);
        
        if ($result->num_rows > 0) {
            echo "<script> M.toast({html: 'Username with given username exists!'})</script>";
        }
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
        
            $insert_sql = "
            INSERT INTO users (username, password, permission_id) 
            VALUES ('$username', '$hash', '$permission')
            ";
    
            if ($conn->query($insert_sql) === TRUE) {
                echo "<script> M.toast({html: 'You were signed up!'})</script>";
            } else {
                echo "$conn->error";
            }
        }
    
        $conn->close();
        
    }

?>
<?php endblock() ?>