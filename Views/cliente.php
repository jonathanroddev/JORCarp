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
        <div class="text-center">
                <form method="POST" action="">
                    <table class="table table-hover table-primary">
                        <thead>
                        <tr>
                            <th scope="col" style="width:7%">Cantidad</th>
                            <th scope="col" style="width:60%">Descripción</th>
                            <th scope="col" style="width:15%">P. Unidad</th>
                            <th scope="col" style="width:23%">Importe</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row"><input type="text" class="form-control" id="quantity1" name="quantities[]"></th>
                            <td><input type="text" class="form-control" id="description1" name="descriptions[]"></td>
                            <td><input type="text" class="form-control" id="unitprice1" name="unitprices[]"></td>
                            <td><input type="text" class="form-control" id="amount1" name="amounts[]"></td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    <input type="hidden" name="invoice">
                    <button type="button" class="btn btn-primary" onclick="location.href='facturas.php'">Atrás</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">
        Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="login.php">aquí</a>
    </div>
<?php } ?>
<script src="../../Views/Scripts/cliente.js"></script>
<?php include_once("Layouts/footer.php"); ?>
