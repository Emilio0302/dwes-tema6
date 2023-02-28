<?php

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\MeGusta;

class MegustaBd
{

    use BaseDatos;

    public static function darMeGusta(MeGusta $meGusta): bool|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "insert into megusta (entrada, usuario) values (?, ?)"
            );
            $usuario = $meGusta->getUsuario();
            $entrada = $meGusta->getEntrada();
            $sentencia->bind_param('ii', $entrada, $usuario);

            return $sentencia->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function quitarMeGusta(MeGusta $meGusta): bool|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "delete from megusta where usuario = ? and entrada = ?"
            );
            $usuario = $meGusta->getUsuario();
            $entrada = $meGusta->getEntrada();
            $sentencia->bind_param('ii', $usuario, $entrada);

            return $sentencia->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function hayMeGusta(int $usuario, int $entrada): bool|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "select *
                from megusta
                where usuario = ? and entrada = ?"
            );
            $sentencia->bind_param('ii', $usuario, $entrada);
            $sentencia->execute();
            $resultado = $sentencia->get_result();

            if ($resultado->num_rows == 0) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function contarMeGustas(int $entrada): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "select *
                from megusta
                where entrada = ?"
            );
            $sentencia->bind_param('i', $entrada);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            
            $megustas = 0;
            while (($fila = $resultado->fetch_assoc()) != null) {
                $megustas++;
            }
            return $megustas;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
}