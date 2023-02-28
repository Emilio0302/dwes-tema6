<?php

use dwesgram\modelo\MegustaBd;

if (empty($datosParaVista['datos']) || $datosParaVista['datos'] === null) {
    echo "<h3>No hay entradas</h3>";
} else {
    echo "<h3>Lista de entradas</h3>";
    echo "<hr>";

    foreach ($datosParaVista['datos'] as $entrada) {
        $texto = $entrada->getTexto();
        $id = $entrada->getId();
        $img = $entrada->getImagen();
        $autor = $entrada->getNombreAutor();
        $meGustas = $entrada->getNumMeGustas();
        echo "<p>$autor escribió:</p>";
        echo "<p>$texto</p>";
        if ($img !== null) {
            echo "<img style='width: 100px;' src='$img'></img>";
        }

        //no hay sesion o la entrada es tuya -> solo se puedes ver los meGustas
        if (!$sesion->haySesion() || $sesion->getId() == $entrada->getAutor()) {
            echo "<p><i class='bi bi-hand-thumbs-up'>($meGustas)</i></p>";

        //la entrada no es tuya -> puedes dar o quitar
        } elseif ($sesion->getId() != $entrada->getAutor()) {
            echo "<p><a href='index.php?controlador=megusta&accion=darMeGustaDesdeLista&entrada=$id'>";
            if (MegustaBd::hayMeGusta($sesion->getId(), $id)) { //ya has dado me gusta
                echo "<i class='bi bi-hand-thumbs-up-fill'>($meGustas)</i>";
            } else { //no lo ha dado aún
                echo "<i class='bi bi-hand-thumbs-up'>($meGustas)</i>";
            }
            echo "</a></p>";
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
