<?php
// controllers/login_controller.php

// Incluimos el modelo usando ruta absoluta dinámica
include __DIR__ . "/../models/usuario.php";

$login = new usuario();
$respuesta = $login->Login($_POST["correo"], $_POST["clave"]);

if ($respuesta instanceof Exception) {
    echo "<script>
            alert('Error del servidor, intente más tarde');
            window.location.href = '../views/Inicio/inicio.html';
          </script>";
    exit;
}

// Si no encontró usuario o clave
if ($respuesta === null) {
    echo "<script>
            alert('Credenciales incorrectas o inexistentes');
            window.location.href = '../views/Inicio/inicio.html';
          </script>";
    exit;
}

// Si es Entrenador o Nutricionista y no está aprobado
if (
    ($respuesta['perfil_usuario'] === 'Entrenador' || $respuesta['perfil_usuario'] === 'Nutricionista')
    && $respuesta['estado'] !== 'A'
) {
    echo "<script>
            alert('Tu cuenta aún no ha sido aprobada por el administrador.');
            window.location.href = '../views/Inicio/inicio.html';
          </script>";
    exit;
}

// Login exitoso
session_start();
$_SESSION["id"] = $respuesta["id"];
$_SESSION["perfil"] = $respuesta["perfil_usuario"];

// Redirige según perfil, con rutas corregidas
switch ($_SESSION["perfil"]) {
    case "Administrador":
        header("Location: ../views/administrador/administrador.php");
        break;
    case "Entrenador":
        header("Location: ../views/Entrenador/entrenador.php");
        break;
    case "Nutricionista":
        header("Location: ../views/Nutricionista/Nutricionista.html");
        break;
    case "Usuario":
        header("Location: ../views/Usuario/Usuario.php");
        break;
    default:
        echo "<script>
                alert('Perfil no reconocido');
                window.location.href = '../views/Inicio/inicio.html';
              </script>";
        break;
}
