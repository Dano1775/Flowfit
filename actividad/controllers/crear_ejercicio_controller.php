<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Entrenador") {
  header("Location: ../Inicio/inicio.html");
  exit;
}

include "../models/conexion.php";

$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$creado_por = $_SESSION["id"];

// Imagen
$imagen = $_FILES["imagen"]["name"];
$tmp = $_FILES["imagen"]["tmp_name"];
$ruta = "../ejercicio_image_uploads/user_uploads/" . $imagen;

// Si hay imagen y se sube, guardar en BD
if ($nombre && $descripcion && $imagen) {
  if (move_uploaded_file($tmp, $ruta)) {
    $sql = "INSERT INTO ejercicio_catalogo (nombre, descripcion, imagen, creado_por)
            VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$nombre, $descripcion, $imagen, $creado_por]);

    echo "<script>alert('Ejercicio creado correctamente'); window.location.href = '../views/Entrenador/ejercicios_entrenador.php';</script>";
  } else {
    echo "<script>alert('No se pudo subir la imagen'); history.back();</script>";
  }
} else {
  echo "<script>alert('Faltan datos'); history.back();</script>";
}

?>
