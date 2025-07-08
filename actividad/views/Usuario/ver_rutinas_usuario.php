<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Usuario") {
  header("Location: ../../Inicio/inicio.html");
  exit;
}

include "../../models/conexion.php";
$usuario_id = $_SESSION["id"];

// Traer todas las rutinas asignadas al usuario
$sql = "SELECT r.id, r.nombre, r.descripcion 
        FROM rutina r
        INNER JOIN rutina_asignada ra ON r.id = ra.rutina_id
        WHERE ra.usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$usuario_id]);
$rutinas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Rutinas - FlowFit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f9f9f9;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    .card {
      margin-bottom: 30px;
      border-radius: 1rem;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .card-title {
      color: #1F2937;
    }
    .card-text {
      color: #4B5563;
    }
    .ejercicio-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="text-success mb-4 text-center">Rutinas asignadas</h2>

    <?php if (count($rutinas) === 0): ?>
      <div class="alert alert-info text-center">No tienes rutinas asignadas aún.</div>
    <?php endif; ?>

    <?php foreach ($rutinas as $rutina): ?>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($rutina["nombre"]) ?></h5>
          <p class="card-text"><?= htmlspecialchars($rutina["descripcion"]) ?></p>

          <!-- Mostrar ejercicios -->
          <div class="row">
            <?php
              $sqlEj = "SELECT e.* 
                        FROM ejercicio e
                        INNER JOIN rutina_ejercicio re ON e.id = re.ejercicio_id
                        WHERE re.rutina_id = ?";
              $stmtEj = $conexion->prepare($sqlEj);
              $stmtEj->execute([$rutina["id"]]);
              $ejercicios = $stmtEj->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($ejercicios as $ej): ?>
              <div class="col-md-6 col-lg-4 mb-3">
                <div class="border rounded p-3 h-100 bg-light">
                  <h6 class="text-dark"><?= htmlspecialchars($ej["nombre"]) ?></h6>
                  <p class="mb-1"><strong>Sets:</strong> <?= $ej["sets"] ?></p>
                  <p class="mb-2"><strong>Calorías:</strong> <?= $ej["calorias"] ?></p>
                  <?php if (!empty($ej["imagen"])): ?>
                    <img src="../../uploads/<?= htmlspecialchars($ej["imagen"]) ?>" alt="Imagen ejercicio" class="ejercicio-img">
                  <?php else: ?>
                    <p class="text-muted small">Sin imagen</p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
