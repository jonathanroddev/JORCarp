<?php ?>
    <div class="row justify-content-center align-items-center" style="height:50vh">

        <form method="POST" action="" class="needs-validation" novalidate>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="username">Nombre</label>
                    <input type="text" class="form-control" id="username" name="username" pattern="[A-Za-z]{3,32}"
                           autocomplete="off" required>
                    <div class="invalid-feedback">
                        Por favor, añade un nombre válido.
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="usermail">Usuario</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                        </div>
                        <input type="email" class="form-control" id="usermail" name="usermail"
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                               aria-describedby="inputGroupPrepend" placeholder="Correo Electrónico" required>
                        <div class="invalid-feedback">
                            Por favor, añade una dirección de correo válida.
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="userpass">Contraseña</label>
                    <input type="password" class="form-control" id="userpass" name="userpass" pattern=".{5,}"
                           autocomplete="off" required>
                    <div class="invalid-feedback">
                        La contraseña debe tener al menos 5 caracteres.
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirmpass">Confirmar contraseña</label>
                    <input type="password" class="form-control" id="confirmpass" name="confirmpass" pattern=".{5,}"
                           oninput="confirmPassword()"
                           autocomplete="off" required>
                    <div class="invalid-feedback">
                        Las contraseñas no coinciden.
                    </div>
                </div>
            </div>
            <input type="hidden" name="newUser">
            <div class="row justify-content-center align-items-center" style="height:5vh">
                <button class="btn btn-primary" type="submit">¡Regístrate!</button>
            </div>
        </form>

    </div>

<?php if (isset($_POST["errorUser"]) && $_POST["errorUser"] == true) { ?>
    <div class="row justify-content-center align-items-center" style="height:5vh">
            <div class="alert alert-primary" role="alert">
                Usuario ya creado.
            </div>
    </div>
<?php } ?>