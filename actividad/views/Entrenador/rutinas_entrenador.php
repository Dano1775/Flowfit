<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Entrenador") {
  header("Location: ../../Inicio/inicio.html");
  exit;
}

include "../../models/conexion.php";
$entrenador_id = $_SESSION["id"];

// Obtener rutinas del entrenador
$stmt = $conexion->prepare("SELECT * FROM rutina WHERE entrenador_id = ?");
$stmt->execute([$entrenador_id]);
$rutinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener catálogo completo de ejercicios para añadir desde modal
$catalogo = $conexion->query("SELECT * FROM ejercicio_catalogo WHERE creado_por IS NULL OR creado_por = $entrenador_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Mis Rutinas - FlowFit</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body.dark-mode {
      background-color: #181a1b;
      color: #f1f1f1;
    }
    .main-content {
      padding: 90px 20px 30px;
    }
    .modo-toggle {
      position: fixed;
      top: 10px;
      right: 15px;
      z-index: 1001;
    }
    .card:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .card {
      transition: all 0.3s ease;
    }
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="entrenador.php">
      <img src="../assets/logo_flowfit.png" alt="FlowFit" height="40" class="me-2">
      <span class="fs-4 text-success fw-bold">FlowFit</span>
    </a>
  </div>
</nav>

<div class="modo-toggle">
  <button id="toggleModo" class="btn btn-sm btn-outline-dark">🌓</button>
</div>

<div class="container main-content">
  <h2 class="text-center mb-4">Rutinas creadas</h2>

  <div class="row">
    <?php foreach ($rutinas as $rutina): ?>
      <?php
      $stmt = $conexion->prepare("SELECT ec.*, re.sets, re.repeticiones
        FROM rutina_ejercicio re
        JOIN ejercicio_catalogo ec ON re.ejercicio_id = ec.id
        WHERE re.rutina_id = ?");
      $stmt->execute([$rutina["id"]]);
      $ejercicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <div class="col-md-6 mb-4">
        <div class="card p-3">
          <h5 class="fw-bold"><?= htmlspecialchars($rutina["nombre"]) ?></h5>
          <p><?= nl2br(htmlspecialchars($rutina["descripcion"])) ?></p>
          <ul class="list-group mb-3">
            <?php foreach ($ejercicios as $e): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $e["nombre"] ?>
                <span class="badge bg-primary rounded-pill"><?= $e["sets"] ?>x<?= $e["repeticiones"] ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <div class="d-flex justify-content-between">
            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $rutina["id"] ?>">Editar</button>
            <a href="../../controllers/rutinas_controller.php?accion=eliminar&id=<?= $rutina["id"] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta rutina?')">Eliminar</a>
          </div>
        </div>
      </div>

      <!-- Modal edición -->
      <div class="modal fade" id="modalEditar<?= $rutina["id"] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
          <form action="../../controllers/rutinas_controller.php" method="POST" class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title">Editar rutina</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="accion" value="editar">
              <input type="hidden" name="rutina_id" value="<?= $rutina["id"] ?>">

              <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($rutina["nombre"]) ?>" required>
              </div>
              <div class="mb-3">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control"><?= htmlspecialchars($rutina["descripcion"]) ?></textarea>
              </div>

              <h6 class="mt-4">Ejercicios actuales</h6>
              <?php foreach ($ejercicios as $i => $e): ?>
                <div class="border rounded p-3 mb-3">
                  <input type="hidden" name="ejercicios[<?= $i ?>][id]" value="<?= $e["id"] ?>">
                  <div class="d-flex justify-content-between">
                    <strong><?= $e["nombre"] ?></strong>
                  </div>
                  <div class="row mt-2">
                    <div class="col">
                      <label>Sets</label>
                      <input type="number" name="ejercicios[<?= $i ?>][sets]" value="<?= $e["sets"] ?>" class="form-control" required>
                    </div>
                    <div class="col">
                      <label>Reps</label>
                      <input type="number" name="ejercicios[<?= $i ?>][reps]" value="<?= $e["repeticiones"] ?>" class="form-control" required>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>

              <hr>
              <h6>Añadir más ejercicios</h6>
              <div class="row">
                <?php foreach ($catalogo as $e): ?>
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <img src="/Flowfit/actividad/ejercicio_image_uploads/<?= $e["creado_por"] ? "user_uploads/" : "" ?><?= $e["imagen"] ?>" class="card-img-top" style="height: 150px; object-fit: contain;">
                      <div class="card-body">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" name="nuevos_ejercicios[]" value="<?= $e["id"] ?>" id="nuevo<?= $rutina["id"] ?>_<?= $e["id"] ?>">
                          <label class="form-check-label fw-bold" for="nuevo<?= $rutina["id"] ?>_<?= $e["id"] ?>"><?= $e["nombre"] ?></label>
                        </div>
                        <div class="row mt-2">
                          <div class="col">
                            <input type="number" name="nuevos_sets[<?= $e["id"] ?>]" placeholder="Sets" class="form-control" min="1">
                          </div>
                          <div class="col">
                            <input type="number" name="nuevos_reps[<?= $e["id"] ?>]" placeholder="Reps" class="form-control" min="1">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Guardar cambios</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-4">
    <a href="entrenador.php" class="btn btn-outline-secondary">← Volver al panel</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Modo oscuro con localStorage
  const toggleBtn = document.getElementById("toggleModo");
  if (localStorage.getItem("modoOscuro") === "true") {
    document.body.classList.add("dark-mode");
  }
  toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("modoOscuro", document.body.classList.contains("dark-mode"));
  });
</script>
</body>
</html>