<?php
$title = "login";
include_once("Layouts/header.php");
?>

<div class="container">
    <div class="row justify-content-center align-items-center" style="height:50vh">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <form action="">
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" placeholder="Usuario">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña">
                        </div>
                        <button type="button" id="sendlogin" class="btn btn-primary">login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("Layouts/footer.php"); ?>