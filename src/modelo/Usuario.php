<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Usuario extends Modelo
{
    private array $errores = [];

    public function __construct(
        private string|null $nombre,
        private string|null $email,
        private string|null $clave,
        private int|null $id = null,
        private string|null $avatar = "assets/img/avatar_predefinido.jpg",
        private int|null $registrado = null
    ) {
        $this->errores = [
            'nombre' => $nombre === null || empty($nombre) ? 'El nombre no puede estar vacío' : null,
            'clave' => $clave === null || empty($clave) ? 'La clave no puede estar vacía' : null,
            'repiteclave' => null,
            'email' => $email === null || empty($email) ? 'El email no puede estar vacío' : null,
            'avatar' => null
        ];
    }
    
    public static function crearUsuarioDesdePost(array $post): Usuario|null
    {
        $usuario = new Usuario(
            nombre: $post && isset($post['nombre']) ? htmlspecialchars(trim($post['nombre'])) : "",
            clave: $post && isset($post['clave']) ? htmlspecialchars(trim($post['clave'])) : "",
            email: $post && isset($post['email']) ? htmlspecialchars(trim($post['email'])) : ""
        );

        if (strlen($usuario->getNombre()) > 10) {
            $usuario->errores['nombre'] = "El nombre de usuario no puede contener mas de 10 caracteres";
        }

        $repiteClave = $post && isset($post['repiteclave']) && !empty($post['repiteclave']) ? htmlspecialchars(trim($post['repiteclave'])) : "";
        if ($usuario->clave !== $repiteClave) {
            $usuario->errores['clave'] = "Las contraseñas no coiciden";
        }
        if (mb_strlen($usuario->clave) < 8) {
            $usuario->errores['clave'] = "La contraseña debe tener al menos 8 carácteres";
        }


        if (
            $_FILES && isset($_FILES['avatar']) &&
            $_FILES['avatar']['error'] === UPLOAD_ERR_OK &&
            $_FILES['avatar']['size'] > 0
        ) {
            $fichero = $_FILES['avatar']['tmp_name'];

            $permitido = array('image/png', 'image/jpeg');

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fichero);

            if (in_array($mime, $permitido)) {
                $usuario->avatar = "assets/img/" . time() . basename($_FILES['avatar']['name']);
            } else {
                $usuario->errores['avatar'] = "extension no disponible";
                return $usuario;
            }
        }

        return $usuario;
    }

    public function getNombre(): string|null
    {
        return $this->nombre;
    }

    public function getClave(): string|null
    {
        return $this->clave;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getAvatar(): string|null
    {
        return $this->avatar;
    }

    public function getRegistrado(): int|null
    {
        return $this->registrado;
    }
    public function getErrores(): array
    {
        return $this->errores;
    }
    public function esValido(): bool
    {
        return count(array_filter($this->errores, fn($error) => $error !== null)) == 0;
    }
}
