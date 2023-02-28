<?php

use dwesgram\modelo\MegustaBd;

$entrada = $datosParaVista['datos'];

$texto = $entrada->getTexto();
$autor = $entrada->getNombreAutor();
$id = $entrada->getId();
$img = $entrada->getImagen();
$dt = new \DateTime('@' . $entrada->getCreado());
$dtstr = $dt->format('r');
$meGustas = $entrada->getNumMeGustas();
$comentarios = $entrada->getComentarios();

echo "<h1> $autor escribió:</h1>";
echo "<p> $texto </p>";
if ($img !== null) {
    echo "<img style='width: 100px;' src='$img' alt='imagen'>";
}

//no hay sesion o la entrada es tuya -> solo se puedes ver los meGustas
if (!$sesion->haySesion() || $sesion->getId() == $entrada->getAutor()) {
    echo "<p><i class='bi bi-hand-thumbs-up'>($meGustas)</i></p>";

    //la entrada no es tuya -> puedes dar o quitar
} elseif ($sesion->getId() != $entrada->getAutor()) {
    echo "<p><a href='index.php?controlador=megusta&accion=darMeGustaDesdeDetalle&entrada=$id'>";
    if (MegustaBd::hayMeGusta($sesion->getId(), $id)) { //ya has dado me gusta
        echo "<i class='bi bi-hand-thumbs-up-fill'>($meGustas)</i>";
    } else { //no lo ha dado aún
        echo "<i class='bi bi-hand-thumbs-up'>($meGustas)</i>";
    }
    echo "</a></p>";
}

echo "<p> $dtstr </p>";

if ($sesion->mismoUsuario($entrada->getAutor())) {
    echo "<p><a href='index.php?controlador=entrada&accion=eliminar&id=$id'>Eliminar</a></p>";
}

if ($sesion->haySesion()) {
    
    echo "<h2>Comentarios</h2>";
    //comentar
    echo <<<END
    <form action="index.php?controlador=comentario&accion=comentar" method="post">
        <input type="number" name="entrada" value="{$id}" hidden>
        <input type="text" name="usuario" value="{$sesion->getNombre()}" hidden>
        <textarea name="texto" cols="80" rows="5"></textarea>
        <input type="submit" value="Comentar">
    </form>
    END;
}

//caja de comentarios
if (empty($comentarios)) {
    echo "<p>No hay comentarios</p>";
} else {
    echo "<hr>";
    foreach ($comentarios as $comentario) {
        $nombre = $comentario->getUsuario()->getNombre();
        $texto = $comentario->getTexto();

        echo "<p>$nombre comentó:</p>";
        echo "<p>$texto</p>";
        echo "<hr>";
    }
}
