<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Entrenador") {
  header("Location: ../../Inicio/inicio.html");
  exit;
}
include "../../models/conexion.php";
$entrenador_id = $_SESSION["id"];
$modo = isset($_GET["modo"]) && $_GET["modo"] === "eliminar" ? "eliminar" : "asignar";

// Obtener todas las asignaciones actuales
$stmt = $conexion->prepare("
  SELECT ra.rutina_id, ra.usuario_id, ra.fecha_asignacion, u.nombre AS usuario_nombre, r.nombre AS rutina_nombre
  FROM rutina_asignada ra
  JOIN usuario u ON ra.usuario_id = u.id
  JOIN rutina r ON ra.rutina_id = r.id
  WHERE r.entrenador_id = ?
  ORDER BY ra.fecha_asignacion DESC
");
$stmt->execute([$entrenador_id]);
$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignar Rutina - FlowFit</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="entrenador.css">
</head>
<body>
  <nav class="navbar navbar-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="entrenador.php">
        <img src="../assets/logo_flowfit.png" alt="FlowFit" height="40" class="me-2">
        <span class="fs-4 text-success fw-bold">FlowFit</span>
      </a>
    </div>
  </nav>

  <div class="container main-wrapper">
    <h2 class="text-center mb-4 fw-bold text-success">Asignación de Rutinas</h2>

    <?php
      if (isset($_GET["success"]) && $_GET["success"] === "asignada") {
        echo "<script>alert('Rutina asignada correctamente.');</script>";
      }
      if (isset($_GET["success"]) && $_GET["success"] === "desasignada") {
        echo "<script>alert('Asignacion eliminada correctamente.');</script>";
      }
      if (isset($_GET["error"]) && $_GET["error"] === "ya_asignada") {
        echo "<script>alert('Esta rutina ya fue asignada a este usuario en especifico, elimina la rutina si necesitas añadir modificaciones.');</script>";
      }
    ?>

    <div class="text-center mb-4">
      <a href="asignar_rutina.php?modo=asignar" class="btn <?= $modo === 'asignar' ? 'btn-success' : 'btn-outline-success' ?> me-2">Asignar rutina</a>
      <a href="asignar_rutina.php?modo=eliminar" class="btn <?= $modo === 'eliminar' ? 'btn-danger' : 'btn-outline-danger' ?>">Eliminar rutina</a>
    </div>

    <!-- ASIGNAR -->
    <div id="asignarSection" style="display: <?= $modo === 'eliminar' ? 'none' : 'block' ?>;">
      <div class="card shadow mx-auto mb-3" style="max-width: 800px; padding: 25px;">
        <form action="../../controllers/entrenador_controllers/asignar_rutina_controller.php" method="POST">
          <input type="hidden" name="accion" value="asignar">
          <div class="row">
            <div class="col-md-6 mb-2">
              <label class="form-label fw-bold">Seleccionar Rutina</label>
              <select name="rutina_id" class="form-select" required>
                <option value="" disabled selected>-- Selecciona una rutina --</option>
                <?php
                $stmt = $conexion->prepare("SELECT id, nombre FROM rutina WHERE entrenador_id = ?");
                $stmt->execute([$entrenador_id]);
                foreach ($stmt as $row) {
                  echo "<option value='{$row["id"]}'>{$row["nombre"]}</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-md-6 mb-2">
              <label class="form-label fw-bold">Seleccionar Usuario</label>
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
          </div>
          <button type="submit" class="btn btn-success w-100 mt-2">Asignar Rutina</button>
        </form>
      </div>
    </div>

    <!-- ELIMINAR -->
    <div id="eliminarSection" style="display: <?= $modo === 'eliminar' ? 'block' : 'none' ?>;">
      <h4 class="text-center mb-3 text-white">Asignaciones Actuales</h4>
      <?php if (count($asignaciones) > 0): ?>
        <div class="table-responsive">
          <table class="table table-dark table-bordered table-hover align-middle text-center">
            <thead class="table-success text-dark">
              <tr>
                <th>Usuario</th>
                <th>Rutina</th>
                <th>Fecha</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($asignaciones as $asig): ?>
                <tr>
                  <td><?= htmlspecialchars($asig["usuario_nombre"]) ?></td>
                  <td><?= htmlspecialchars($asig["rutina_nombre"]) ?></td>
                  <td><?= $asig["fecha_asignacion"] ?></td>
                  <td>
                    <form action="../../controllers/entrenador_controllers/asignar_rutina_controller.php" method="POST">
                      <input type="hidden" name="accion" value="desasignar">
                      <input type="hidden" name="rutina_id" value="<?= $asig["rutina_id"] ?>">
                      <input type="hidden" name="usuario_id" value="<?= $asig["usuario_id"] ?>">
                      <input type="hidden" name="modo" value="eliminar">
                      <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta asignación?')">Eliminar</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info text-center">No hay asignaciones registradas aún.</div>
      <?php endif; ?>
    </div>

    <div class="text-center mt-4">
      <a href="rutina.php" class="btn btn-outline-light">← Volver al panel</a>
    </div>
  </div>
</body>
</html>
