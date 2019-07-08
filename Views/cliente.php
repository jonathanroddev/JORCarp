<?php
include_once("../Models/DBUtils.php");
$dbUtils = new DBUtils();
if (isset($_GET["idcliente"])) $cusId = $_GET["idcliente"];
$sql = "SELECT * FROM customers WHERE cus_id=" . $cusId;
$customer = $dbUtils->getDatas($sql);
$title = "Cliente: " . $customer[0]["cus_address1"];

include_once("Layouts/header.php");
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) { ?>
    <div class="col-8">
        <div class="card bg-light mb-3 text-center">
            <div class="card-header text-white bg-info"><strong><?php echo $customer[0]["cus_address1"] ?></strong>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">D/Dª</dt>
                    <dd class="col-sm-9"><?php echo $customer[0]["cus_name"] ?></dd>
                    <dt class="col-sm-3">NIF</dt>
                    <dd class="col-sm-9"><?php echo $customer[0]["cus_nif"] ?></dd>
                    <dt class="col-sm-3">Dirección</dt>
                    <dd class="col-sm-9"><?php echo $customer[0]["cus_address2"] ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card text-center">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" name="descriptions[]" placeholder="Descripción"
                               required>
                    </div>
                    <input type="hidden" name="invoice">
                    <button type="button" class="btn btn-primary" onclick="location.href='facturas.php'">Atrás</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>


<?php } else { ?>
    <div class="alert alert-primary" role="alert">
        Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="login.php">aquí</a>
    </div>
<?php }
include_once("Layouts/footer.php"); ?>
