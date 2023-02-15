<?php
if (empty($datosParaVista['datos']) || $datosParaVista['datos'] === null) {
    echo "<p>No se ha podido eliminar la entrada correctamente</p>";
} else {
    echo "<p>Esta entrada se ha eliminado correctamente</p>";
}
?>