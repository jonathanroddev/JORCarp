<?php
$title = "Facturas";
include_once("Layouts/header.php");
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) { ?>
    <div class="col-4">
        <div class="card bg-light mb-3 text-center" style="max-width: 18rem;margin-top:2rem">
            <div class="card-header text-white bg-info"><strong>Clientes</strong></div>
            <div class="card-body">
                <?php if (!isset($_SESSION["fileUploaded"]) || $_SESSION["fileUploaded"] == false) { ?>
                    <form enctype="multipart/form-data" action="" method="POST" id="uploadForm"
                          onsubmit="return Validate(this);">
                        <input type="hidden" name="uploadFile"/>
                        <input name="customersFile" type="file" id="customersFile" style="display: none;"
                               onchange="fileValidation()">
                        <input type="button" value="Elegir fichero"
                               onclick="document.getElementById('customersFile').click();" class="btn btn-primary"/>
                        <br>
                    </form>
                <?php } else { ?>
                    <?php $customers = $_SESSION["customersData"];
                    for ($i = 0; $i < sizeof($customers); $i++) {
                        $customer = $customers[$i] ?>
                        <button type="button" class="btn btn-primary btn-block"
                                onclick="location.href='cliente.php?id=<?php echo($i); ?>'">
                            <?php echo($customer["address1"]); ?>
                        </button>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">
        Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="login.php">aquí</a>
    </div>
<?php }
include_once("Layouts/footer.php"); ?>