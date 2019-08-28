<?php
$title = "Login";
if (!isset($_SESSION["userStatus"]) || $_SESSION["userStatus"] == 0) {
    ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">
        <div class="col-4">
            <div class="card text-center">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" name="usermail" placeholder="Usuario" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña"
                                   required>
                        </div>
                        <input type="hidden" name="login">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </form>
                </div>
            </div>
        <?php if (isset($_POST["errorLogin"]) && $_POST["errorLogin"] == true) { ?>
                <div class="alert alert-primary" role="alert">
                    Usuario y/o contraseña no válidos.
                </div>
        <?php } ?>
        <br>
        <div class="row justify-content-center align-items-right">
            <a href="?page=registrousuario">¿No tienes una cuenta de usuario?</a>
        </div>
    </div>
<?php } else { ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">
        <div class="alert alert-primary" role="alert">
            Ya tienes una sesión iniciada. Click <a href="?page=contabilidad">aquí</a> para continuar
        </div>
    </div>
<?php }?>