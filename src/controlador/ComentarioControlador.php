<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\Comentario;
use dwesgram\modelo\ComentarioBd;
use dwesgram\modelo\Sesion;
use dwesgram\modelo\UsuarioBd;

class ComentarioControlador extends Controlador
{

    public function comentar(): array|Entrada|null
    {
        if (!$this->autenticado()) {
            return null;
        }

        $usuarioId = $_POST && isset($_POST['usuario']) ? htmlspecialchars(trim($_POST['usuario'])) : null;
        $entradaId = $_POST && isset($_POST['entrada']) ? htmlspecialchars(trim($_POST['entrada'])) : null;
        $texto = $_POST && isset($_POST['texto']) ? htmlspecialchars(trim($_POST['texto'])) : "";

        if ($entradaId === null || $usuarioId === null) {
            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }

        if (empty($texto)) {
            $this->vista = "entrada/detalle";
            return EntradaBd::getEntrada($entradaId);
        }

        $usuario = UsuarioBd::getUsuario($usuarioId);
        if ($usuario === null) {
            $this->vista = "errores/500";
            return null;
        }

        $comentario = new Comentario(
            usuario: $usuario,
            entrada: $entradaId,
            texto: $texto
        );

        $id = ComentarioBd::insertar($comentario);
        if ($id === null) {
            $this->vista = "errores/500";
            return null;
        }

        $this->vista = "entrada/detalle";
        return EntradaBd::getEntrada($entradaId);
    }
}
