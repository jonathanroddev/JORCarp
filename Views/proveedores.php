<?php
if (isset($_SESSION["userPrivileges"]) && $_SESSION["userPrivileges"] == 1) { ?>
    <div class="row justify-content-center align-items-center" style="padding-top: 45px">
        <div class="col-8">
            <div class="text-center">
                <div class="card-header text-white bg-info"><strong>Proveedores</strong></div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center" style="padding-top: 25px">
        <div class="col-8">
            <div class="text-center">
                <?php
                $indexRow = sizeof($suppliers) + 1;
                ?>
                <button type="button" class="btn btn-primary" value="<?php echo $indexRow ?>"
                        onclick="createRowNewSupplier(this)">Añadir otro
                    proveedor
                </button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center" style="padding-top: 25px">
        <div class="col-8">
            <div class="text-center">
                <form method="POST" action="" id="newSup"></form>
                <form method="POST" action="" id="modSup"></form>
                <form method="POST" action="?page=proveedores&deleteSup=true" id="delSup"></form>
                <table class="table table-hover table-primary" id="suppliers">
                    <thead>
                    <tr>
                        <th scope="col" style="width:25%">NIF</th>
                        <th scope="col" style="width:45%">Nombre</th>
                    </tr>
                    </thead>
                    <tbody>
                    <input type="hidden" id="modSupplier" name="" form="modSup">
                    <?php for ($i = 0; $i < sizeof($suppliers); $i++) { ?>
                        <tr>
                            <td scope="row"><input style="text-align:center" type="text" readonly
                                                   class="form-control-plaintext"
                                                   id="cif<?php echo $suppliers[$i]["sup_id"] ?>"
                                                   value="<?php echo $suppliers[$i]["sup_cif"] ?>" form="modSup"></td>
                            <td scope="row"><input style="text-align:center" type="text" readonly
                                                   class="form-control-plaintext"
                                                   id="name<?php echo $suppliers[$i]["sup_id"] ?>"
                                                   value="<?php echo $suppliers[$i]["sup_name"] ?>" form="modSup"></td>
                            <td scope="row">
                                <button type="button" class="btn btn-info"
                                        value="<?php echo $suppliers[$i]["sup_id"] ?>"
                                        onclick="modifySupplier(this)">Modificar
                                </button>
                            </td>
                            <td scope="row">
                                <input type="hidden" id="del<?php echo $suppliers[$i]["sup_id"] ?>" form="delSup">
                                <button type="button" class="btn btn-danger"
                                        value="<?php echo $suppliers[$i]["sup_id"] ?>"
                                        onclick="deleteSupplier(this)">Borrar
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <br>
                <div class="row justify-content-center align-items-center" style="padding-bottom:75px">
                    <div class="col-8">
                        <input type="hidden" name="newSupplier" form="newSup">
                        <button type="button" class="btn btn-primary" onclick="location.href='?page=gastos'">
                            Atrás
                        </button>
                        <button type="submit" class="btn btn-primary" onclick="submitForms()">Guardar</button>
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
