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
                <button type="button" class="btn btn-primary" onclick="createNotion()">Añadir
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
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" readonly style="visibility: hidden">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="checkbox" class="checkbox-invoice" onchange="listInvoice(this)"
                                           unchecked>
                                </div>
                                <input type="text" class="form-control" id="reference" name="reference"
                                       oninput="calculateTotal()" placeholder="Ref." readonly pattern="^\S+$"
                                       autocomplete="off">
                            </div>
                        </div>
                        <tr>
                            <td scope="row"><input type="number" class="form-control" id="quantity1"
                                                   name="quantities[]"
                                                   oninput="calculateAmount(1)" autocomplete="off"></td>
                            <td scope="row"><input type="text" class="form-control" id="description1"
                                                   name="descriptions[]">
                            </td>
                            <td scope="row"><input type="number" class="form-control" id="unitprice1"
                                                   name="unitprices[]"
                                                   oninput="calculateAmount(1)" step=".01" autocomplete="off"></td>
                            <td scope="row"><input type="number" class="form-control" id="amount1" name="amounts[]"
                                                   onkeyup="calculateTotal(1)" step=".01" autocomplete="off"></td>
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
