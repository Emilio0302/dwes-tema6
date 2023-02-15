<?php
$usuario = $datosParaVista['datos'];

$nombre = $usuario !== null ? $usuario->getNombre() : "";

$email = $usuario !== null ? $usuario->getEmail() : "";

$errores = $usuario !== null ? $usuario->getErrores() : [];
?>
<div class="container">
    <h1>Regístrate</h1>

    <form action="index.php?controlador=usuario&accion=registro" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de usuario</label><br>
            <input type="text" id="nombre" name="nombre" value="<?= $nombre?>">
            <?php
            if (isset($errores['nombre'])) {
                $msg = $errores['nombre'];
                echo "<p>$msg</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label><br>
            <input type="email" id="email" name="email" value="<?= $email?>">
            <?php
            if (isset($errores['email'])) {
                $msg = $errores['email'];
                echo "<p>$msg</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Contraseña</label><br>
            <input type="password" id="clave" name="clave">
            <?php
            if (isset($errores['clave'])) {
                $msg = $errores['clave'];
                echo "<p>$msg</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="repiteclave" class="form-label">Repite la contraseña</label><br>
            <input type="password" id="repiteclave" name="repiteclave">
        </div>
        <div class="mb-3">
            <label for="avatar">Puedes elegir un avatar</label><br>
            <input class="form-control" type="file" name="avatar" id="avatar">
        </div>
        <button type="submit" class="btn btn-primary">Crear cuenta</button>
    </form>
</div>
