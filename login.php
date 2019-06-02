<?php include 'base_templates/form-base.php' ?>

<?php startblock('title') ?>
Login
<?php endblock() ?>

<?php startblock('content') ?>
<div class="container" style="margin-top:90px;">
    <div class="row">
        <div class="col s6 offset-s3">
            <div class="card-panel z-depth-5">
                <h4 class="center">Login</h4>
                <div class="row">
                    <form class="col s12 m12" method="POST" action="login.php">
                        <div class="row">
                            <div class="input-field col s12 m12">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="icon_prefix" type="text" class="validate" name="username" required>
                                <label for="icon_prefix">Username</label>
                            </div>

                            <div class="input-field col s12 m12">
                                <i class="material-icons prefix">lock</i>
                                <input id="icon_password" type="password" class="validate" name="password" required>
                                <label for="icon_password">Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <button class="btn waves-effect waves-light col s12 " type="submit" name="action">Login
                                    <i class="fa fa-sign-in right"></i>
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
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "observer";
    
    if (isset($username, $password)) {
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $select_sql = "
        SELECT * FROM users WHERE username='$username'
        ";
        $result = $conn->query($select_sql);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user["password"])) {
                session_start();

                $_SESSION["user_id"] = $user["id"];
                header("Location:index.php");
            }
            else {
                echo "<script> M.toast({html: 'Bad credentials!'})</script>";
            }
        }
        else {
            echo "<script> M.toast({html: 'Bad credentials!'})</script>";
        }
    
        $conn->close();
        
    }

?>
<?php endblock() ?>