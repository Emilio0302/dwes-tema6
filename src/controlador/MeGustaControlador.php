<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\MeGusta;
use dwesgram\modelo\MeGustaBd;
use dwesgram\modelo\Sesion;

class MegustaControlador extends Controlador
{

    public function darMeGustaDesdeLista(): array|null
    {
        if (!$this->autenticado()) {
            $this->vista = 'errores/403';
            return null;
        }

        $idEntrada = $_GET && isset($_GET['entrada']) ? htmlspecialchars(trim($_GET['entrada'])) : null;
        $idUsuario = (new Sesion)->getId();

        if (!is_numeric($idEntrada) || !is_numeric($idUsuario)) {
            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }

        $entrada  = EntradaBd::getEntrada($idEntrada);
        if ($entrada === null || $entrada->getAutor() == $idUsuario) {
            $this->vista = "errores/403";
            return null;
        }

        $meGusta = new MeGusta(
            entrada: $idEntrada,
            usuario: $idUsuario
        );

        if (MeGustaBd::hayMeGusta($idUsuario, $idEntrada)) {
            $bien = MegustaBd::quitarMeGusta($meGusta);
            if ($bien !== true) {
                $this->vista = "errores/500";
                return null;
            }

            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }

        $bien = MegustaBd::darMeGusta($meGusta);

        if ($bien !== true) {
            $this->vista = "errores/500";
            return null;
        }

        $this->vista = "entrada/lista";
        return EntradaBd::getEntradas();
    }

    public function darMeGustaDesdeDetalle(): array|Entrada|null
    {
        if (!$this->autenticado()) {
            $this->vista = 'errores/403';
            return null;
        }

        $idEntrada = $_GET && isset($_GET['entrada']) ? htmlspecialchars(trim($_GET['entrada'])) : null;
        $idUsuario = (new Sesion)->getId();

        if (!is_numeric($idEntrada) || !is_numeric($idUsuario)) {
            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }

        $entrada  = EntradaBd::getEntrada($idEntrada);
        if ($entrada === null || $entrada->getAutor() == $idUsuario) {
            $this->vista = "errores/403";
            return null;
        }

        $meGusta = new MeGusta(
            entrada: $idEntrada,
            usuario: $idUsuario
        );

        if (MeGustaBd::hayMeGusta($idUsuario, $idEntrada)) {
            $bien = MegustaBd::quitarMeGusta($meGusta);
            if ($bien !== true) {
                $this->vista = "errores/500";
                return null;
            }

            $this->vista = "entrada/detalle";
            return EntradaBd::getEntrada($idEntrada);
        }

        $bien = MegustaBd::darMeGusta($meGusta);

        if ($bien !== true) {
            $this->vista = "errores/500";
            return null;
        }

        $this->vista = "entrada/detalle";
        return EntradaBd::getEntrada($idEntrada);
    }
}
