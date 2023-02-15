<?php
namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Usuario;

class UsuarioBd
{
    use BaseDatos;

    public static function getUsuario(string $nombre): Usuario|false|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "select * from usuario where nombre=?"
            );
            $sentencia->bind_param(
                "s", 
                $nombre,
            );
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();

            if ($fila == null) {
                return false;
            } else {
                return new Usuario(
                    id: $fila['id'],
                    nombre: $fila['nombre'],
                    clave: $fila['clave'],
                    email: $fila['email'],
                    avatar: $fila['avatar'],
                    registrado: $fila['registrado']
                );
            }
            
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getNombreUsuario(int $id): string|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "select nombre from usuario where id=?"
            );
            $sentencia->bind_param("i", $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();
            if ($fila == null) {
                return null;
            } else {
                return $fila['nombre'];
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function insertar(Usuario $usuario): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare(
                "insert into usuario (nombre, clave, email, avatar) values (?, ?, ?, ?)"
            );
            $nombre = $usuario->getNombre();
            $clave = password_hash($usuario->getClave(), PASSWORD_BCRYPT);
            $email = $usuario->getEmail();
            $avatar = $usuario->getAvatar();
            $sentencia->bind_param(
                "ssss", 
                $nombre, 
                $clave, 
                $email, 
                $avatar
            );
                
            $sentencia->execute();

            return $conexion->insert_id;
        } catch (\Exception $e) {
            return null;
        }
    }
}
