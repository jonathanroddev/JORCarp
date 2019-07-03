<?php
$title = "Facturas";
include_once("Layouts/header.php");
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) {
    if (!isset($_POST["fileUploaded"])) {
        ?>
        <form enctype="multipart/form-data" action="" method="POST">
            <input type="hidden" name="uploadFile"/>
            <input name="customersFile" type="file">
            <br>
            <input type="submit" value="Enviar fichero"/>
        </form>

    <?php } else {
        $customers =  $_POST["customersData"];
        foreach ($customers as $k => $v) {
            echo "<pre>";
            var_dump($v);
            echo "</pre>";
        } ?>

    <?php }
} else { ?>
    <div class="alert alert-primary" role="alert">
        Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="login.php">aquí</a>
    </div>
<?php }
include_once("Layouts/footer.php"); ?>