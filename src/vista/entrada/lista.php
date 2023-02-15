<p>Lista de entradas</p>

<?php

use dwesgram\modelo\UsuarioBd;

if (empty($datosParaVista['datos']) || $datosParaVista['datos'] === null) {
    echo "<h3>No hay entradas</h3>";
} else {
    echo "<h3>Lista de entradas</h3>";
    echo "<hr>";
    foreach ($datosParaVista['datos'] as $entrada) {
        $texto = $entrada->getTexto();
        $id = $entrada->getId();
        $img = $entrada->getImagen();
        $autor = UsuarioBd::getNombreUsuario($entrada->getAutor());
        echo "<p>$autor escribió:</p>";
        echo "<p>$texto</p>";
        if ($img !== null) {
            echo "<img src='$img'></img>";
        }
        echo "<p><a href='index.php?controlador=entrada&accion=detalle&id=$id'>Detalles</a>";
        if ($sesion->mismoUsuario($entrada->getAutor())) {
            echo " | <a href='index.php?controlador=entrada&accion=eliminar&id=$id'>Eliminar</a>";
        }
        echo "</p>";
        echo "<hr>";
    }
    echo "</ul>";
}
