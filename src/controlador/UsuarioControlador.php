<?php

namespace dwesgram\controlador;

use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\Usuario;
use dwesgram\modelo\UsuarioBD;

class UsuarioControlador extends Controlador
{
    public function login(): array|null
    {
        if ($this->autenticado()) {
            $this->vista = 'errores/403';
            return null;
        }

        if (!$_POST) {
            $this->vista = 'usuario/login';
            return null;
        }

        $nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : "";
        $clave = isset($_POST['clave']) ? htmlspecialchars(trim($_POST['clave'])) : "";

        $usuario = UsuarioBD::getUsuario($nombre);
        if ($usuario === null) {
            $this->vista = 'errores/500';
            return null;
        }

        if (empty($nombre) || empty($clave) || $usuario === false || !password_verify($clave, $usuario->getClave())) {
            $this->vista = 'usuario/login';
            return [
                'nombre' => $nombre,
                'error' => 'Usuario y/o contraseña incorrectos'
            ];
        }

        $_SESSION['usuario'] = [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre()
        ];
        header('Location: index.php');
        $this->vista = "entrada/lista";
        return null;
    }

    public function registro(): Usuario|array|null
    {
        if ($this->autenticado()) {
            $this->vista = 'errores/403';
            return null;
        }

        if (!$_POST) {
            $this->vista = 'usuario/registro';
            return null;
        }

        $usuario = Usuario::crearUsuarioDesdePost($_POST);
        if (!$usuario->esValido()) {
            $this->vista = 'usuario/registro';
            return $usuario;
        }

        if ($usuario->getAvatar() != "assets/img/avatar_predefinido.jpg") {
            $movido = move_uploaded_file($_FILES['avatar']['tmp_name'], $usuario->getAvatar());
            if (!$movido) {
                $this->vista = "errores/500";
                return null;
            }
        }

        $id = UsuarioBd::insertar($usuario);

        if ($id === null) {
            $this->vista = "errores/500";
            return null;
        }

        $_SESSION['usuario'] = [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre()
        ];
        header('Location: index.php');
        return null;
    }

    public function logout()
    {
        if (!$this->autenticado()) {
            $this->vista = 'errores/403';
            return null;
        }

        session_destroy();

        header('Location: index.php');
        $this->vista = "entrada/lista";
        return null;
    }
}
