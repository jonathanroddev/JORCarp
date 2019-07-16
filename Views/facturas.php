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
                <?php } else {
                    $dbUtils = new DBUtils();
                    $sql = "SELECT * FROM customers";
                    $customers = $dbUtils->getDatas($sql);
                    $sql2 = "SELECT cus_id FROM invoices";
                    $invoices = $dbUtils->getDatas($sql2);
                    for ($i = 0; $i < sizeof($customers); $i++) {
                        $customer = $customers[$i];
                        $styleButton = "primary";
                        for ($j = 0; $j < sizeof($invoices); $j++) {
                            if ($invoices[$j]["cus_id"] == $customer["cus_id"]) $styleButton = "success";
                        }
                        ?>
                        <button type="button" class="btn btn-<?php echo $styleButton ?> btn-block"
                                onclick="location.href='cliente.php?idcliente=<?php echo($customer["cus_id"]); ?>'">
                            <?php echo($customer["cus_address1"]); ?>
                        </button>
                    <?php }
                } ?>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">
        Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="login.php">aquí</a>
    </div>
<?php } ?>
    <script src="../../Views/Scripts/facturas.js"></script>
<?php include_once("Layouts/footer.php"); ?>