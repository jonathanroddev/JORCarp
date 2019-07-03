<?php
$title = "Facturas";
include_once("Layouts/header.php");
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) {
    if (!isset($_SESSION["fileUploaded"]) || $_SESSION["fileUploaded"] == false) {
        ?>
        <div class="col-4">
            <div class="card bg-light mb-3 text-center" style="max-width: 18rem;">
                <div class="card-header text-white bg-info"><strong>Cargar Datos de Clientes</strong></div>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="" method="POST" id="uploadForm"
                          onsubmit="return Validate(this);">
                        <input type="hidden" name="uploadFile"/>
                        <input name="customersFile" type="file" id="customersFile" style="display: none;"
                               onchange="fileValidation()">
                        <input type="button" value="Elegir fichero"
                               onclick="document.getElementById('customersFile').click();" class="btn btn-primary"/>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    <?php } else {
        $customers = $_SESSION["customersData"];
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