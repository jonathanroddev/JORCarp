<?php
include_once("../Models/DBUtils.php");
$dbUtils = new DBUtils();
if (isset($_GET["idcliente"])) {
    $cusId = $_GET["idcliente"];
    $sql = "SELECT * FROM customers WHERE cus_id=" . $cusId;
    $customer = $dbUtils->getDatas($sql);
    $sql2 = "SELECT inv_obj FROM invoices WHERE cus_id=" . $cusId;
    $invoiceSerialized = $dbUtils->getDatas($sql2);
    $title = "Cliente: " . $customer[0]["cus_address1"];
}
include_once("Layouts/header.php");
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) { ?>
    <div class="row justify-content-center align-items-center" style="padding-top: 75px">
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
    </div>
    <div class="row justify-content-center align-items-center" style="padding-top: 25px">
        <div class="col-8">
            <div class="text-center">
                <?php
                if ($invoiceSerialized != null) {
                    $invoice = unserialize($invoiceSerialized[0]["inv_obj"]);
                    $indexRow = sizeof($invoice["notions"]) + 1;
                    ?>
                    <button type="button" class="btn btn-primary" value="<?php echo $indexRow ?>" onclick="createNotionSavedInvoice(this)">Añadir
                        otro
                        concepto
                    </button>
                <?php } else { ?>
                    <button type="button" class="btn btn-primary" onclick="createNotion()">Añadir
                        otro
                        concepto
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center" style="padding-top: 25px">
        <div class="col-8">
            <div class="text-center">
                <form method="POST" action="">
                    <table class="table table-hover table-primary" id="invoice">
                        <thead>
                        <tr>
                            <th scope="col" style="width:7%">Cantidad</th>
                            <th scope="col" style="width:60%">Descripción</th>
                            <th scope="col" style="width:15%">P. Unidad</th>
                            <th scope="col" style="width:23%">Importe</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($invoiceSerialized != null) {
                            $invoice = unserialize($invoiceSerialized[0]["inv_obj"]);
                            for ($i = 0; $i < sizeof($invoice["notions"]); $i++) {
                                $quantity = $invoice["notions"][$i]["quantity"];
                                $description = $invoice["notions"][$i]["description"];
                                $unitPrice = $invoice["notions"][$i]["unitPrice"];
                                $amount = $invoice["notions"][$i]["amount"];
                                ?>
                                <tr>
                                    <td scope="row"><input type="number" class="form-control"
                                                           id="quantity<?php echo($i + 1) ?>" name="quantities[]"
                                                           oninput="calculateAmount(<?php echo($i + 1) ?>)"
                                                           value="<?php echo $quantity ?>"></td>
                                    <td><input type="text" class="form-control" id="description<?php echo($i + 1) ?>"
                                               name="descriptions[]" value="<?php echo $description ?>"></td>
                                    <td><input type="number" class="form-control" id="unitprice<?php echo($i + 1) ?>"
                                               name="unitprices[]"
                                               oninput="calculateAmount(<?php echo($i + 1) ?>)" step=".01"
                                               value="<?php echo $unitPrice ?>"></td>
                                    <td><input type="number" class="form-control" id="amount<?php echo($i + 1) ?>"
                                               name="amounts[]"
                                               onkeyup="calculateTotal(<?php echo($i + 1) ?>)" step=".01"
                                               value="<?php echo $amount ?>"></td>
                                </tr>
                            <?php }
                            $grossTotal = $invoice["totals"]["grossTotal"];
                            $igic = $invoice["totals"]["igic"];
                            $total = $invoice["totals"]["total"];
                            ?>
                            <tr>
                                <td colspan="3" scope="row" class="text-right">Total Bruto</td>
                                <td><input type="text" class="form-control" id="grosstotal" name="grosstotal"
                                           value="<?php echo $grossTotal ?>" readonly></td>
                            </tr>
                            <tr>
                                <td colspan="3" scope="row" class="text-right">IGIC 6,5%</td>
                                <td><input type="text" class="form-control" id="igic" name="igic"
                                           value="<?php echo $igic ?>" readonly></td>
                            </tr>
                            <tr>
                                <td colspan="3" scope="row" class="text-right">Total</td>
                                <td><input type="text" class="form-control" id="total" name="total"
                                           value="<?php echo $total ?>" readonly></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td scope="row"><input type="number" class="form-control" id="quantity1"
                                                       name="quantities[]"
                                                       oninput="calculateAmount(1)"></td>
                                <td><input type="text" class="form-control" id="description1" name="descriptions[]">
                                </td>
                                <td><input type="number" class="form-control" id="unitprice1" name="unitprices[]"
                                           oninput="calculateAmount(1)" step=".01"></td>
                                <td><input type="number" class="form-control" id="amount1" name="amounts[]"
                                           onkeyup="calculateTotal(1)" step=".01"></td>
                            </tr>
                            <tr>
                                <td colspan="3" scope="row" class="text-right">Total Bruto</td>
                                <td><input type="text" class="form-control" id="grosstotal" name="grosstotal" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" scope="row" class="text-right">IGIC 6,5%</td>
                                <td><input type="text" class="form-control" id="igic" name="igic" readonly></td>
                            </tr>
                            <tr>
                                <td colspan="3" scope="row" class="text-right">Total</td>
                                <td><input type="text" class="form-control" id="total" name="total" readonly></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <br>
                    <input type="hidden" name="invoice">
                    <button type="button" class="btn btn-primary" onclick="location.href='facturas.php'">Atrás</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">
        <div class="alert alert-primary" role="alert">
            Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a
                    href="login.php">aquí</a>
        </div>
    </div>
<?php } ?>
<?php include_once("Layouts/footer.php"); ?>
