<?php

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Comentario;

class ComentarioBd
{
    use BaseDatos;

    public static function insertar(Comentario $comentario): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "insert into comentario
                (comentario, usuario, entrada)
                values (?, ?, ?)
                on duplicate key update comentario = ?"
            );
            $usuario = $comentario->getUsuario()->getId();
            $entrada = $comentario->getEntrada();
            $texto = $comentario->getTexto();
            $sentencia->bind_param('siis', $texto, $usuario, $entrada, $texto);
            $sentencia->execute();

            return $conexion->insert_id;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function getComentarios(int $entrada): array|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "select c.comentario, u.nombre
                from comentario c
                join usuario u
                on u.id = c.usuario
                where entrada = ?"
            );
            $sentencia->bind_param("i", $entrada);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $comentarios = [];
            while (($fila = $resultado->fetch_assoc()) != null) {
                $usuario = UsuarioBd::getUsuario($fila['nombre']);
                if ($usuario === null) {
                    return null;
                }

                $comentario = new Comentario(
                    usuario: $usuario,
                    entrada: $entrada,
                    texto: $fila['comentario']
                );
                $comentarios[] = $comentario;
            }
            return $comentarios;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
}