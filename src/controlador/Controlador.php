<?php

namespace dwesgram\controlador;
use dwesgram\modelo\Sesion;

abstract class Controlador
{
    protected string|null $vista = null;

    public function getVista(): string|null
    {
        return $this->vista;
    }

    public function autenticado(): bool
    {
        $sesion = new Sesion();
        if (!$sesion->haySesion()) {
            $this->vista = 'errores/403';
            return false;
        }

        return true;
    }

    public function mismoUsuario(int $id): bool
    {
        $sesion = new Sesion();
        if (!$sesion->mismoUsuario($id)) {
            $this->vista = 'errores/403';
            return false;
        }

        return true;
    }
}
