<?php
namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Entrada extends Modelo
{
    private array $errores = [];

    public function __construct(
        private string|null $texto,
        private int|null $id = null,
        private string|null $imagen = null,
        private int|null $autor = null,
        private int|null $creado = null
    ) {
        $this->errores = [
            'texto' => $texto === null || empty($texto) ? 'El texto no puede estar vacío' : null,
            'imagen' => null
        ];
    }

    public static function crearEntradaDesdePost(array $post): Entrada|null
    {
        if (isset($post['texto'])) {
            $texto = mb_substr(htmlspecialchars(trim($post['texto'])), 0, 128);
        } 
        
        $entrada = new Entrada(
            texto: isset($texto) ? $texto : null,
            autor: (new Sesion)->getId()
        );
        if ($_FILES && isset($_FILES['imagen']) &&
            $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
            $_FILES['imagen']['size'] > 0) {

            $fichero = $_FILES['imagen']['tmp_name'];
            $permitido = ['image/png', 'image/jpeg'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_fichero = finfo_file($finfo, $fichero);
            
            if (in_array($mime_fichero, $permitido)) {
                $entrada->imagen = "assets/img/" . time() . basename($_FILES['imagen']['name']);
            } else {
                $entrada->errores['imagen'] = "extension no disponible";
                return $entrada;
            }
        }
        return $entrada;
    }
    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTexto(): string
    {
        return $this->texto ? $this->texto : '';
    }

    public function getImagen(): string|null
    {
        return $this->imagen;
    }

    public function esValida(): bool
    {
        return count(array_filter($this->errores, fn($error) => $error !== null)) == 0;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }

    public function getAutor(): int|null
    {
        return $this->autor;
    }

    public function getCreado(): int|null
    {
        return $this->creado;
    }
}
