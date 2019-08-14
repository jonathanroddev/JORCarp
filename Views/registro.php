<?php
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
                $invoice = unserialize($invoiceSerialized[0]["inv_obj"]);
                $indexRow = sizeof($invoice["notions"]) + 1;
                ?>
                <button type="button" class="btn btn-primary" value="<?php echo $indexRow ?>"
                        onclick="createNotionSavedInvoice(this)">Añadir
                    otro
                    concepto
                </button>
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
                        <?php
                        $check = "unchecked";
                        $writable = "readonly";
                        if ($referenceSaved != ""){
                            $check = "checked";
                            $writable = "";
                        }  ?>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" readonly style="visibility: hidden">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="checkbox" class="checkbox-invoice" onchange="listInvoice(this)"
                                        <?php echo $check ?>>
                                </div>
                                <input type="text" class="form-control" id="reference" name="reference" value="<?php echo $referenceSaved ?>"
                                       oninput="calculateTotal()" placeholder="Ref." <?php echo $writable ?> pattern="^\S+$" autocomplete="off">
                            </div>
                        </div>
                        <?php
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
                                                       value="<?php echo $quantity ?>" autocomplete="off"></td>
                                <td scope="row"><input type="text" class="form-control" id="description<?php echo($i + 1) ?>"
                                           name="descriptions[]" value="<?php echo $description ?>"></td>
                                <td scope="row"><input type="number" class="form-control" id="unitprice<?php echo($i + 1) ?>"
                                           name="unitprices[]"
                                           oninput="calculateAmount(<?php echo($i + 1) ?>)" step=".01"
                                           value="<?php echo $unitPrice ?>" autocomplete="off"></td>
                                <td scope="row"><input type="number" class="form-control" id="amount<?php echo($i + 1) ?>"
                                           name="amounts[]"
                                           onkeyup="calculateTotal(<?php echo($i + 1) ?>)" step=".01"
                                           value="<?php echo $amount ?>" autocomplete="off"></td>
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
                        </tbody>
                    </table>
                    <br>
                    <div class="row justify-content-center align-items-center" style="padding-bottom:75px">
                        <div class="col-8">
                            <input type="hidden" name="invoice">
                            <button type="button" class="btn btn-primary" onclick="location.href='?page=ingresos'">
                                Atrás
                            </button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">
        <div class="alert alert-primary" role="alert">
            Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="?page=login">aquí</a>
        </div>
    </div>
<?php } ?>
