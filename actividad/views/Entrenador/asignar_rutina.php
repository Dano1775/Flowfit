<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Entrenador") {
  header("Location: ../../Inicio/inicio.html");
  exit;
}
include "../../models/conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignar Rutina - FlowFit</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
  <h2 class="text-center mb-4">Asignar Rutina a Usuario</h2>
  <form action="../../controllers/rutinas_controller.php" method="POST" class="w-75 mx-auto">
    <input type="hidden" name="accion" value="asignar">

    <div class="mb-3">
      <label class="form-label">Seleccionar Rutina</label>
      <select name="rutina_id" class="form-select" required>
        <option value="" disabled selected>-- Selecciona una rutina --</option>
        <?php
        $entrenador_id = $_SESSION["id"];
        $stmt = $conexion->prepare("SELECT id, nombre FROM rutina WHERE entrenador_id = ?");
        $stmt->execute([$entrenador_id]);
        foreach ($stmt as $row) {
          echo "<option value='{$row["id"]}'>{$row["nombre"]}</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Seleccionar Usuario</label>
      <select name="usuario_id" class="form-select" required>
        <option value="" disabled selected>-- Selecciona un usuario --</option>
        <?php
        $stmt2 = $conexion->query("SELECT id, nombre FROM usuario WHERE perfil_usuario = 'Usuario' AND estado = 'A'");
        foreach ($stmt2 as $row2) {
          echo "<option value='{$row2["id"]}'>{$row2["nombre"]}</option>";
        }
        ?>
      </select>
    </div>

    <button type="submit" class="btn btn-success w-100">Asignar Rutina</button>
  </form>
</body>
</html>
