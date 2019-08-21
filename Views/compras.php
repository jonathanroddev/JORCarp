<?php
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) { ?>
    <div class="row justify-content-center align-items-center" style="padding-top: 45px">
        <div class="col-8">
            <div class="text-center">
                <div class="card-header text-white bg-info"><strong>Compras</strong></div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center" style="padding-top: 25px">
        <div class="col-8">
            <div class="text-center">
                <button type="button" class="btn btn-primary" value=""
                        onclick="createRowNewOutgoingInvoice()">Añadir otra
                    compra
                </button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center" style="padding-top: 25px">
        <div class="col-16">
            <div class="text-center">
                <form method="POST" action="" id="saveOutgoingForm"></form>
                <table class="table table-hover table-primary" id="outgoing">
                    <thead>
                    <tr>
                        <th scope="col" style="width:25%">Proveedor</th>
                        <th scope="col" style="width:20%">Nº REF.</th>
                        <th scope="col" style="width:20%">Fecha</th>
                        <th scope="col" style="width:11%">Total Bruto</th>
                        <th scope="col" style="width:11%">IGIC</th>
                        <th scope="col" style="width:11%">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td scope="row"><select class="form-control" id="sup1" name="suppliers[]" form="saveOutgoingForm" required>
                                <option selected value="">Proveedor...</option>
                                <?php for ($i = 0; $i < sizeof($suppliers); $i++) { ?>
                                    <option value="<?php echo $suppliers[$i]["sup_id"] ?>"><?php echo $suppliers[$i]["sup_name"] ?></option>
                                <?php } ?>
                            </select></td>
                        <td scope="row"><input type="text" class="form-control" id="outref1"
                                               name="outgoingreferences[]" form="saveOutgoingForm" autocomplete="off" required></td>
                        <td scope="row"><input type="date" class="form-control" id="outdate1" name="outgoingdates[]" form="saveOutgoingForm" required></td>
                        <td><input type="number" class="form-control" id="outgross1" name="outgoinggross[]" form="saveOutgoingForm"
                                   oninput="calculateTotalSingleOutgoing(1)" step=".01" autocomplete="off" required>
                        </td>
                        <td><input type="number" class="form-control" id="outigic1" name="outgoingigic[]" form="saveOutgoingForm"
                                   oninput="calculateTotalSingleOutgoing(1)" step=".01" autocomplete="off"></td>
                        <td><input type="text" class="form-control" id="outtotal1" name="outgoingtotals[]" form="saveOutgoingForm" step=".01" autocomplete="off" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="3" scope="row" class=" text-right font-weight-bold" style="vertical-align: middle">Suma de Totales</td>
                        <td><input type="text" class="form-control" id="outallgross" name="outallgross" readonly>
                        <td><input type="text" class="form-control" id="outalligic" name="outalligic" readonly>
                        <td><input type="text" class="form-control" id="outalltotal" name="outalltotal" readonly>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <div class="row justify-content-center align-items-center" style="padding-bottom:75px">
                    <div class="col-8">
                        <input type="hidden" name="saveOutgoing" form="saveOutgoingForm">
                        <button type="button" class="btn btn-primary" onclick="location.href='?page=gastos'">
                            Atrás
                        </button>
                        <button type="button" class="btn btn-primary" onclick="displayDivOutgoing()" id="saveoutbtn">Guardar y exportar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center" style="padding-top: 20px;display: none;" id="createOutgoingFile">
        <div class="col-4">
            <div class="text-center">
                    <table class="table table-hover table-info">
                        <thead>
                        <th scope="col">Nombre del documento:</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td scope="row"><input type="text" name="outgoingFileName" class="form-control" form="saveOutgoingForm" required autocomplete="off"></td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    <button type="submit" form="saveOutgoingForm" class="btn btn-primary">Crear archivo
                    </button>
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
