<?php
$usuario = $datosParaVista['datos'];

$nombre = $usuario != null ? $datosParaVista['datos']['nombre'] : "";

$error = $usuario != null ? $datosParaVista['datos']['error']: [];
?>
<div class="container">
    <h1>Inicia sesión</h1>

    <form action="index.php?controlador=usuario&accion=login" method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de usuario</label><br>
            <input type="text" id="nombre" name="nombre" value="<?= $nombre?>">
            <?php
            if ($error) {;
                echo "<p>$error</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Contraseña</label><br>
            <input type="password" id="clave" name="clave">
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>
