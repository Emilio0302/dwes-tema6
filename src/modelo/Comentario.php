<?php

namespace dwesgram\modelo;

class Comentario
{
    public function __construct(
        private Usuario|null $usuario,
        private int|null $entrada,
        private string|null $texto
    ) {
    }

    public function getUsuario(): Usuario|null
    {
        return $this->usuario;
    }
    public function getEntrada(): int|null
    {
        return $this->entrada;
    }
    public function getTexto(): string|null
    {
        return $this->texto;
    }
}