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
        <div class="col-8">
            <div class="text-center">
                <form method="POST" action="" id="saveOutgoings"></form>
                <form method="POST" action="?page=compras&deleteOutgoing=true" id="delOut"></form>
                <table class="table table-hover table-primary" id="outgoings">
                    <thead>
                    <tr>
                        <th scope="col" style="width:30%">Proveedor</th>
                        <th scope="col" style="width:25%">Nº REF.</th>
                        <th scope="col" style="width:15%">Total Bruto</th>
                        <th scope="col" style="width:15%">IGIC</th>
                        <th scope="col" style="width:15%">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td scope="row"><select class="form-control" id="sup1" name="suppliers[]">
                                <option selected value="0">Proveedor...</option>
                                <?php for ($i = 0; $i < sizeof($suppliers); $i++) { ?>
                                    <option value="<?php echo $suppliers[$i]["sup_id"] ?>"><?php echo $suppliers[$i]["sup_name"] ?></option>
                                <?php } ?>
                            </select></td>
                        <td scope="row"><input type="text" class="form-control" id="outref1"
                                               name="outgoingsreferences[]" autocomplete="off"></td>
                        <td><input type="number" class="form-control" id="outgross1" name="outgoingsgross[]"
                                   oninput="calculateAmountOutgoings(1)" autocomplete='off'>
                        </td>
                        <td><input type="number" class="form-control" id="outigic1" name="outgoingsigic[]"
                                   oninput="calculateAmountOutgoings(1)" step=".01" autocomplete="off"></td>
                        <td><input type="number" class="form-control" id="outtotal1" name="outgoingtotals[]"
                                   onkeyup="calculateTotalOutgoings(1)" step=".01" autocomplete="off"></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <div class="row justify-content-center align-items-center" style="padding-bottom:75px">
                    <div class="col-8">
                        <input type="hidden" name="newSupplier" form="saveOutgoings">
                        <button type="button" class="btn btn-primary" onclick="location.href='?page=gastos'">
                            Atrás
                        </button>
                        <button type="submit" class="btn btn-primary" form="saveOutgoings">Guardar</button>
                    </div>
                </div>
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
