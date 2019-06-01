<?php include 'base.php' ?>

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
                    <form class="col s12 m12">
                        <div class="row">
                            <div class="input-field col s12 m12">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="icon_prefix" type="text" class="validate">
                                <label for="icon_prefix">Username</label>
                            </div>

                            <div class="input-field col s12 m12">
                                <i class="material-icons prefix">lock</i>
                                <input id="icon_password" type="password" class="validate">
                                <label for="icon_password">Password</label>
                            </div>

                            <div class="input-field col s12 m12">
                                <i class="material-icons prefix">view_headline</i>
                                <select>
                                    <option value="" disabled selected>Choose your permission</option>
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
<?php endblock() ?>