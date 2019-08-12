<?php
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) {
    ?>
    <div class="row justify-content-center align-items-center" style="padding-top: 75px">
        <div class="col-4">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header text-white bg-info"><strong>Gastos</strong></div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-block" onclick="location.href='?page=proveedores'">
                        Proveedores
                    </button>
                    <hr>
                    <button type="button" class="btn btn-primary btn-block" onclick="location.href='?page=compras'">Compras</button>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">
        <div class="alert alert-primary" role="alert">
            Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="?page=login">aquí</a>
        </div>
    </div>
<?php }
