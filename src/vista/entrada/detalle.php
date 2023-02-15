<p>Detalle</p>
<?php

use dwesgram\modelo\Entrada;
use dwesgram\modelo\UsuarioBd;

$entrada = $datosParaVista['datos'];

$texto = $entrada->getTexto();
$autor = UsuarioBd::getNombreUsuario($entrada->getAutor());
$id = $entrada->getId();
$img = $entrada->getImagen();
$dt = new \DateTime('@' . $entrada->getCreado());
$dtstr = $dt->format('r');

echo "<h1> $autor escribió:</h1>";
echo "<p> $texto </p>";
if ($img !== null) {
    echo "<img src='$img' alt='imagen'>";
}

echo "<p> $dtstr </p>";

if ($sesion->mismoUsuario($entrada->getAutor())) {
    echo "<p><a href='index.php?controlador=entrada&accion=eliminar&id=$id'>Eliminar</a></p>";
}

?>