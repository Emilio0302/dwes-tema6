<?php

namespace dwesgram\modelo;

class Megusta
{
    public function __construct(
        private int|null $entrada,
        private int|null $usuario,
    ) {
    }

    public function getEntrada(): int|null
    {
        return $this->entrada;
    }

    public function getUsuario(): int|null
    {
        return $this->usuario;
    }
}
