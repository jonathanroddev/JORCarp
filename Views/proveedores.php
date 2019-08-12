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
                <button type="button" class="btn btn-primary" value="<?php echo $indexRow?>" onclick="createRowNewSupplier(this)">Añadir otro
                    proveedor
                </button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center" style="padding-top: 25px">
        <div class="col-8">
            <div class="text-center">
                <form method="POST" action="">
                    <table class="table table-hover table-primary" id="suppliers">
                        <thead>
                        <tr>
                            <th scope="col" style="width:30%">NIF</th>
                            <th scope="col" style="width:50%">Nombre</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < sizeof($suppliers); $i++) {?>
                        <tr>
                            <td scope="row"><?php echo $suppliers[$i]["sup_cif"]?></td>
                            <td scope="row"><?php echo $suppliers[$i]["sup_name"]?></td>
                            <td scope="row"><a href="proveedor.php&idpr=<?php echo $suppliers[$i]["sup_id"]?>">Modificar</a>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <br>
                    <div class="row justify-content-center align-items-center" style="padding-bottom:75px">
                        <div class="col-8">
                            <input type="hidden" name="invoice">
                            <button type="button" class="btn btn-primary" onclick="location.href='?page=gastos'">
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
