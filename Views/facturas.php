<?php
$title = "Facturas";
include_once("Layouts/header.php");
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) {
    $dbUtils = new DBUtils();
    $sql = "SELECT * FROM customers";
    $customers = $dbUtils->getDatas($sql);
    $sql2 = "SELECT * FROM invoices";
    $invoices = $dbUtils->getDatas($sql2);
    //SELECT * FROM invoices INNER JOIN customers ON invoices.cus_id = customers.cus_id?>
    <div class="row justify-content-center align-items-center" style="padding-top: 75px">
    <div class="col-4">
    <div class="card bg-light mb-3 text-center" >
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
        </div>
        </div>
        </div>
        </div>
    <?php } else {
        for ($i = 0; $i < sizeof($customers); $i++) {
            $customer = $customers[$i];
            $styleButton = "primary";
            for ($j = 0; $j < sizeof($invoices); $j++) {
                $invoice = $invoices[$j];
                if ($invoice["cus_id"] == $customer["cus_id"]) $styleButton = "success";
            }
            ?>
            <button type="button" class="btn btn-<?php echo $styleButton ?> btn-block"
                    onclick="location.href='cliente.php?idcliente=<?php echo($customer["cus_id"]); ?>'">
                <?php echo($customer["cus_address1"]); ?>
            </button>
        <?php } ?>
        </div>
        </div>
        </div>
        </div>
        <div class="row justify-content-center align-items-center" style="padding-top: 20px">
            <div class="col-4">
                <div class="text-center">
                    <button type="button" class="btn btn-primary" onclick="createNotion()">Exportar facturas a Excel
                    </button>
                </div>
            </div>
        </div>
    <?php } ?>

<?php } else { ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">
    <div class="alert alert-primary" role="alert">
        Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="login.php">aquí</a>
    </div>
    </div>
<?php } ?>
    <script src="../../Views/Scripts/facturas.js"></script>
<?php include_once("Layouts/footer.php"); ?>