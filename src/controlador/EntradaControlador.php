<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;

class EntradaControlador extends Controlador
{

    public function lista(): array
    {
        $this->vista = "entrada/lista";
        return EntradaBd::getEntradas();
    }

    public function detalle(): Entrada|array|null
    {
        if (!$_GET['id'] && !isset($_GET['id'])) {
            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }

        $id = htmlspecialchars(trim($_GET['id']));
        if (!is_numeric($id) || EntradaBd::EntradaNoExistente($id)) {
            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }
        $entrada = EntradaBd::getEntrada($id);
        if ($entrada === null) {
            $this->vista = "errores/500";
            return null;
        }
        $this->vista = "entrada/detalle";
        return $entrada;
    }

    public function nuevo(): Entrada|null
    {
        if (!$this->autenticado()) {
            $this->vista = "errores/403";
            return null;
        }
        //Si no hay post, el formulario se mostrara vacio
        if (!$_POST) {
            $this->vista = "entrada/nuevo";
            return null;
        }

        $entrada = Entrada::crearEntradaDesdePost($_POST);

        if (!$entrada->esValida()) {
            $this->vista = "entrada/nuevo";
            return $entrada;
        }
        $img = $entrada->getImagen();
        if ($img !== null) {
            $insertarImagen = EntradaBd::insertarImagen($img);
            if (!$insertarImagen) {
                $this->vista = 'errores/500';
                return null;
            }
        }
        
        $id = EntradaBd::insertar($entrada);
        if ($id !== null) {
            $this->vista = "entrada/detalle";
            return EntradaBd::getEntrada($id);
        }

        $this->vista = "entrada/nuevo";
        return $entrada;
    }

    public function eliminar(): bool|null
    {
        if (!$this->autenticado()) {
            $this->vista = "errores/403";
            return false;
        }

        if (!$_GET['id'] && !isset($_GET['id'])) {
            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }

        $id = htmlspecialchars(trim($_GET['id']));
        if (!is_numeric($id) || EntradaBd::EntradaNoExistente($id)) {
            $this->vista = "entrada/lista";
            return null;
        }
        
        $entrada = EntradaBd::getEntrada($id);
        if (!$this->mismoUsuario($entrada->getAutor())) {
           $this->vista = "errores/403";
           return null;
        }
        
        $img = $entrada->getImagen(); 
        if ($img !== null) {
            EntradaBd::eliminarImagen($img);
        }
        
        $eliminar = EntradaBd::eliminar($id);
        if ($eliminar) {
            $this->vista = "entrada/eliminar";
            return true;
        }
        $this->vista = "entrada/lista";
        return false;
    }
}
