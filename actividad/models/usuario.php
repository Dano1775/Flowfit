<?php

class usuario {

    public function Login($correo, $clave) {
        try {
            include "conexion.php";
            $sql = "SELECT * FROM usuario WHERE correo = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && $usuario['clave'] === $clave) {
                return $usuario;
            } else {
                return null; // Credenciales incorrectas
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function Registrar($documento, $nombre, $telefono, $correo, $clave, $perfil_usuario) {
        try {
            include "conexion.php";

            // Solo entrenadores o nutricionistas requieren aprobación
            $estado = ($perfil_usuario == 'Entrenador' || $perfil_usuario == 'Nutricionista') ? 'I' : 'A';

            $sql = "INSERT INTO usuario (numero_documento, nombre, telefono, correo, clave, perfil_usuario, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$documento, $nombre, $telefono, $correo, $clave, $perfil_usuario, $estado]);

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function ConsultaGeneral() {
        try {
            include "conexion.php";
            $sql = "SELECT * FROM usuario WHERE estado = 'A'";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function ConsultaPendientes() {
        try {
            include "conexion.php";
            $sql = "SELECT * FROM usuario WHERE estado = 'I' AND perfil_usuario IN ('Entrenador', 'Nutricionista')";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function Aprobar($id) {
        try {
            include "conexion.php";
            $sql = "UPDATE usuario SET estado = 'A' WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function Eliminar($id) {
        try {
            include "conexion.php";
            $sql = "DELETE FROM usuario WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}

class administrador extends usuario {
    // Hereda todos los métodos del usuario
}
