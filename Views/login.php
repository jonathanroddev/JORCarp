<?php
$title = "Login";
include_once("Layouts/header.php");
?>
    <div class="col-4">
        <div class="card text-center">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Usuario">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Contraseña">
                    </div>
                    <input type="hidden" name="login">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </form>
            </div>
        </div>
    </div>
<?php include_once("Layouts/footer.php"); ?>