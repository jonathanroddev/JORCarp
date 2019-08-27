<?php
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) {
    if (!isset($_SESSION["invoiceExcelFile"]) || $_SESSION["invoiceExcelFile"] == null) {
        ?>
        <div class="row justify-content-center align-items-center" style="height:50vh">
            <div class="alert alert-primary" role="alert">
                Control de ingresos no creado. Pulsa <a
                        href="?page=ingresos">aquí</a> para comenzar.
            </div>
        </div>
    <?php } else if (!isset($_SESSION["outgoingExcelFile"]) || $_SESSION["outgoingExcelFile"] == null) { ?>
        <div class="row justify-content-center align-items-center" style="height:50vh">
            <div class="alert alert-primary" role="alert">
                Control de gastos no creado. Pulsa <a
                        href="?page=gastos">aquí</a> para comenzar.
            </div>
        </div>
    <?php } else {
        $incomeFileURL = explode("Files/", $_SESSION["invoiceExcelFile"]);
        $incomeFileName = $incomeFileURL[1];
        $outgoingFileURL = explode("Files/", $_SESSION["outgoingExcelFile"]);
        $outgoingFileName = $outgoingFileURL[1]; ?>
        <div class="row justify-content-center align-items-center" style="padding-top: 45px">
            <div class="col-8">
                <div class="text-center">
                    <div class="card-header text-white bg-info"><strong>Balance</strong></div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center align-items-center" style="padding-top: 25px">
            <div class="col-16">
                <div class="text-center">
                    <table class="table table-hover table-primary" id="outgoing">
                        <thead>
                        <tr>
                            <th scope="col" style="width:45%">Archivo de Ingresos</th>
                            <th scope="col" style="width:45%">Archivo de Gastos</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td scope="row"><input style="text-align:center" type="text" readonly
                                                   class="form-control-plaintext"
                                                   value="<?php echo $incomeFileName ?>"></td>
                            <td scope="row"><input style="text-align:center" type="text" readonly
                                                   class="form-control-plaintext"
                                                   value="<?php echo $outgoingFileName ?>"></td>
                            <td scope="row"><input style="text-align:center" type="number" readonly
                                                   class="form-control-plaintext"
                                                   value="<?php echo $outgoingFileName ?>"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <form method="POST" action="" id="createBalanceForm"></form>
        <input type="hidden" name="createBalance" form="createBalanceForm">
        <div class="row justify-content-center align-items-center" style="padding-top: 20px">
            <div class="col-8">
                <div class="text-center">
                    <table class="table table-hover table-info">
                        <thead>
                        <th scope="col" style="width:35%">Nombre del documento:</th>
                        <th scope="col" style="width:35%">Fecha:</th>
                        <th scope="col" style="width:30%">Seguro de autónomo:</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td scope="row"><input type="text" name="balanceFileName" class="form-control" form="createBalanceForm" required autocomplete="off"></td>
                            <td scope="row"><input type="month" name="balanceFileDate" class="form-control" form="createBalanceForm" required autocomplete="off" style="text-align: center"></td>
                            <td scope="row"><input type="number" name="selfEmployedTax" class="form-control" form="createBalanceForm" required autocomplete="off" style="text-align: center" step=".01"></td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    <button type="submit" form="createBalanceForm" class="btn btn-primary">Crear archivo
                    </button>
                </div>
            </div>
        </div>
    <?php }
} else { ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">
        <div class="alert alert-primary" role="alert">
            Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a
                    href="?page=login">aquí</a>
        </div>
    </div>
<?php }
