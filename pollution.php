<?php $file = "pollution"; ?>
<?php include 'base_templates/base.php' ?>

<?php startblock('title') ?>
Pollution
<?php endblock() ?>

<?php startblock('content') ?>
<form method="GET" action="pollution.php">
    <div class="row">
        <div class="input-field col s12 m12">
            <i class="material-icons prefix">search</i>
            <input id="icon_prefix" type="text" class="validate" name="city" required>
            <label for="icon_prefix">Search pollution by city:</label>
        </div>
    </div>
</form>


<?php endblock() ?>