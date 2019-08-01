<?php
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) {
    ?>
    <div class="row justify-content-center align-items-center" style="padding-top: 75px">
        <div class="col-4">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header text-white bg-info"><strong>Clientes</strong></div>
                <div class="card-body">
                    <?php if (!isset($_SESSION["fileUploaded"]) || $_SESSION["fileUploaded"] == false) { ?>
                        <form enctype="multipart/form-data" action="" method="POST" id="uploadForm" onsubmit="return Validate(this);">
                            <input type="hidden" name="uploadFile" />
                            <input name="customersFile" type="file" id="customersFile" style="display: none;" onchange="fileValidation()">
                            <input type="button" value="Elegir fichero" onclick="document.getElementById('customersFile').click();" class="btn btn-primary" />
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
            <button type="button" class="btn btn-<?php echo $styleButton ?> btn-block" onclick="location.href='?page=cliente&idcliente=<?php echo ($customer["cus_id"]); ?>'">
                <?php echo ($customer["cus_address1"]); ?>
            </button>
        <?php } ?>
        </div>
        </div>
        </div>
        </div>
        <div class="row justify-content-center align-items-center" style="padding-top: 20px" id="displayDivBtn">
            <div class="col-4">
                <div class="text-center">
                    <button type="submit" onclick="displayDiv()" class="btn btn-primary">Exportar facturas a
                        Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="row justify-content-center align-items-center" style="padding-top: 20px;display: none" id="createFile">
            <div class="col-8">
                <div class="text-center">
                    <form action="" method="POST" id="exportForm">
                        <input type="hidden" name="exportToExcel" />
                        <table class="table table-hover table-info">
                            <thead>
                                <th scope="col">Fecha:</th>
                                <th scope="col">Nombre:</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row"><input type="date" name="fileDate" class="form-control" required></td>
                                    <td scope="row"><input type="text" name="fileName" class="form-control" required autocomplete="off"></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <button type="submit" onclick="submiBtnClick()" class="btn btn-primary">Crear archivo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php }
} else { ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">
        <div class="alert alert-primary" role="alert">
            Acceso Denegado. Por favor, ingresa un usuario y contraseña válidos pulsando <a href="?page=login">aquí</a>
        </div>
    </div>
<?php } ?>