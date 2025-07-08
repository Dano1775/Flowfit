<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Entrenador") {
  header("Location: ../Inicio/inicio.html");
  exit;
}

include "../models/conexion.php";

// Validar que llegaron datos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre"]);
  $descripcion = trim($_POST["descripcion"]);
  $entrenador_id = $_SESSION["id"];
  $ejercicios = $_POST["ejercicios"] ?? [];
  $sets = $_POST["sets"] ?? [];
  $reps = $_POST["reps"] ?? [];

  // Validación básica
  if ($nombre === "" || empty($ejercicios)) {
    echo "<script>alert('Debes ingresar un nombre y al menos un ejercicio'); history.back();</script>";
    exit;
  }

  try {
    $conexion->beginTransaction();

    // Insertar rutina
    $stmt = $conexion->prepare("INSERT INTO rutina (nombre, descripcion, entrenador_id) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $entrenador_id]);
    $rutina_id = $conexion->lastInsertId();

    // Insertar ejercicios asociados (en tabla rutina_ejercicio)
    $stmt_ej = $conexion->prepare("INSERT INTO rutina_ejercicio (rutina_id, ejercicio_id, sets, reps) VALUES (?, ?, ?, ?)");

    foreach ($ejercicios as $ej_id) {
      $num_sets = isset($sets[$ej_id]) ? (int)$sets[$ej_id] : 1;
      $num_reps = isset($reps[$ej_id]) ? (int)$reps[$ej_id] : 1;
      $stmt_ej->execute([$rutina_id, $ej_id, $num_sets, $num_reps]);
    }

    $conexion->commit();

    echo "<script>alert('Rutina creada con éxito'); window.location.href = '../views/Entrenador/rutinas_entrenador.php';</script>";
  } catch (PDOException $e) {
    $conexion->rollBack();
    echo "<script>alert('Error al crear rutina: " . $e->getMessage() . "'); history.back();</script>";
  }
}
?>
