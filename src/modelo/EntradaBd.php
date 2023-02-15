<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Entrada;
use dwesgram\modelo\BaseDatos;

class EntradaBd
{
    use BaseDatos;

    public static function getEntradas(): array|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $resultado = [];
            $queryResultado = $conexion->query("select * from entrada");
            if ($queryResultado !== false) {
                while (($fila = $queryResultado->fetch_assoc()) != null) {
                    $entrada = new Entrada(
                        id: $fila['id'],
                        texto: $fila['texto'],
                        imagen: $fila['imagen'],
                        autor: $fila['autor'],
                        creado: $fila['creado']
                    );
                    $resultado[] = $entrada;
                }
            }
            return $resultado;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function getEntrada(int $id): Entrada|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select * from entrada where id=?");
            $sentencia->bind_param('i', $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();
            if ($fila == null) {
                return null;
            } else {
                return new Entrada(
                    id: $fila['id'],
                    texto: $fila['texto'],
                    imagen: $fila['imagen'],
                    autor: $fila['autor'],
                    creado: $fila['creado']
                );
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function insertar(Entrada $entrada): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "insert into entrada (texto, imagen, autor) values (?, ?, ?)"
            );
            $autor = $entrada->getAutor();
            $imagen = $entrada->getImagen();
            $texto = $entrada->getTexto();
            $sentencia->bind_param(
                "ssi",
                $texto,
                $imagen,
                $autor
            );
            $sentencia->execute();

            return $conexion->insert_id;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public static function EntradaNoExistente(int $id): bool|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select * from entrada where id=?");
            $sentencia->bind_param("i", $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();

            return $fila == null;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public static function eliminar(int $id): bool|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("delete from entrada where id=?");
            $sentencia->bind_param("i", $id);
            $eliminado = $sentencia->execute();

            return $eliminado;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public static function insertarImagen(string $ruta) : bool
    {
        $fichero = $_FILES['imagen']['tmp_name'];

        return move_uploaded_file($fichero, $ruta);
    }
    
    public static function eliminarImagen(string $ruta) : bool
    {
        return unlink($ruta);
    }

}
