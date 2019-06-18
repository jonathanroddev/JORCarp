<?php
$title = "Admin Interface";
include_once("Layouts/header.php");
//if(isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"]==1){
?>
    <div class="col-4">
    <div class="card bg-light mb-3 text-center" style="max-width: 18rem;">
        <div class="card-header text-white bg-info mb-3"><strong>Contabilidad</strong></div>
        <div class="card-body">
            <button type="button" class="btn btn-primary btn-block">Importar Clientes</button>
            <hr>
            <button type="button" class="btn btn-primary btn-block">Facturas</button>
            <hr>
            <button type="button" class="btn btn-primary btn-block">Compras</button>
            <hr>
            <button type="button" class="btn btn-primary btn-block">Balance</button>
        </div>
    </div>
<?php /*}
  else{*/ ?>
    <!--<div class="alert alert-primary" role="alert">
        Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos.
    </div>-->
<?php /*}*/ include_once("Layouts/footer.php"); ?>