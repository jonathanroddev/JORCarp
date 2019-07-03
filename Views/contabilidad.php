<?php
$title = "Admin Interface";
include_once("Layouts/header.php");
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) {
    ?>
    <div class="col-4">
        <div class="card bg-light mb-3 text-center" style="max-width: 18rem;">
            <div class="card-header text-white bg-info"><strong>Contabilidad</strong></div>
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-block" onclick="location.href='facturas.php'">
                    Facturas
                </button>
                <hr>
                <button type="button" class="btn btn-primary btn-block">Compras</button>
                <hr>
                <button type="button" class="btn btn-primary btn-block">Balance</button>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">
        Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="login.php">aquí</a>
    </div>
<?php }
include_once("Layouts/footer.php"); ?>