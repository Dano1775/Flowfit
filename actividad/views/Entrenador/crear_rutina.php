<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Entrenador") {
  header("Location: ../../Inicio/inicio.html");
  exit;
}

include "../../models/conexion.php";
$entrenador_id = $_SESSION["id"];

$global = $conexion->query("SELECT * FROM ejercicio_catalogo WHERE creado_por IS NULL")->fetchAll(PDO::FETCH_ASSOC);
$stmt = $conexion->prepare("SELECT * FROM ejercicio_catalogo WHERE creado_por = ?");
$stmt->execute([$entrenador_id]);
$personales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crear rutina - FlowFit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --bg: #f4f6f8;
      --text: #212529;
    }
    body.dark-mode {
      --bg: #181a1b;
      --text: #f1f1f1;
    }
    body {
      background-color: var(--bg);
      color: var(--text);
      transition: 0.3s;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .main-content {
      flex: 1;
      padding: 90px 20px 30px;
    }
    .modo-toggle {
      position: fixed;
      top: 10px;
      right: 15px;
      z-index: 1001;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 5px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
    }
    .card:hover {
      transform: scale(1.03);
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .card-img-top {
      width: 100%;
      height: 200px;
      object-fit: contain;
      background-color: #f8f9fa;
      padding: 10px;
    }
    .footer {
      background-color: #222;
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: auto;
    }
    .collapsible-header {
      cursor: pointer;
      font-weight: bold;
      margin-top: 30px;
    }
    .collapsible-header:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="entrenador.php">
      <img src="../assets/logo_flowfit.png" alt="FlowFit" height="40" class="me-2">
      <span class="fs-4 text-success fw-bold">FlowFit</span>
    </a>
  </div>
</nav>

<!-- Modo oscuro -->
<div class="modo-toggle">
  <button id="toggleModo" class="btn btn-sm btn-outline-dark">🌓</button>
</div>

<!-- Formulario principal -->
<div class="container main-content">
  <h2 class="mb-4 text-center">Crear nueva rutina</h2>

  <form action="../../controllers/rutinas_controller.php" method="POST">
    <div class="mb-3">
      <label class="form-label">Nombre de la rutina:</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-4">
      <label class="form-label">Descripción:</label>
      <textarea name="descripcion" class="form-control" rows="3"></textarea>
    </div>

    <div class="text-center mb-5">
      <button type="button" class="btn btn-outline-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalEjercicios">
        Seleccionar ejercicios
      </button>
    </div>

    <!-- Modal ejercicios -->
    <div class="modal fade" id="modalEjercicios" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">Seleccionar ejercicios</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="text" id="buscadorModal" class="form-control mb-4" placeholder="Buscar ejercicios...">

            <!-- Plataforma -->
            <h5 class="collapsible-header text-success" onclick="toggleSeccion('modalPlataforma')">Ejercicios de la plataforma</h5>
            <div class="row" id="modalPlataforma">
              <?php foreach ($global as $i => $ej): ?>
                <div class="col-md-4 ejercicio-card-modal mb-4" data-nombre="<?= strtolower($ej["nombre"]) ?>">
                  <div class="card h-100">
                    <img src="/Flowfit/actividad/ejercicio_image_uploads/<?= $ej["imagen"] ?>" class="card-img-top">
                    <div class="card-body">
                      <div class="form-check mb-2">
                        <input class="form-check-input ejercicio-check" type="checkbox" name="ejercicios[]" value="<?= $ej["id"] ?>" id="e<?= $ej["id"] ?>">
                        <label class="form-check-label fw-bold" for="e<?= $ej["id"] ?>"><?= $ej["nombre"] ?></label>
                      </div>
                      <p class="card-text"><?= nl2br($ej["descripcion"]) ?></p>
                      <div class="row">
                        <div class="col">
                          <input type="number" name="sets[<?= $ej["id"] ?>]" class="form-control" placeholder="Sets" min="1" disabled>
                        </div>
                        <div class="col">
                          <input type="number" name="reps[<?= $ej["id"] ?>]" class="form-control" placeholder="Reps" min="1" disabled>
                        </div>
                      </div>
                      <input type="hidden" name="orden[<?= $ej["id"] ?>]" value="<?= $i + 1 ?>">
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Personales -->
            <h5 class="collapsible-header text-primary mt-4" onclick="toggleSeccion('modalPersonales')">Ejercicios creados por ti</h5>
            <div class="row" id="modalPersonales">
              <?php foreach ($personales as $i => $ej): ?>
                <div class="col-md-4 ejercicio-card-modal mb-4" data-nombre="<?= strtolower($ej["nombre"]) ?>">
                  <div class="card h-100">
                    <img src="/Flowfit/actividad/ejercicio_image_uploads/user_uploads/<?= $ej["imagen"] ?>" class="card-img-top">
                    <div class="card-body">
                      <div class="form-check mb-2">
                        <input class="form-check-input ejercicio-check" type="checkbox" name="ejercicios[]" value="<?= $ej["id"] ?>" id="p<?= $ej["id"] ?>">
                        <label class="form-check-label fw-bold" for="p<?= $ej["id"] ?>"><?= $ej["nombre"] ?></label>
                      </div>
                      <p class="card-text"><?= nl2br($ej["descripcion"]) ?></p>
                      <div class="row">
                        <div class="col">
                          <input type="number" name="sets[<?= $ej["id"] ?>]" class="form-control" placeholder="Sets" min="1" disabled>
                        </div>
                        <div class="col">
                          <input type="number" name="reps[<?= $ej["id"] ?>]" class="form-control" placeholder="Reps" min="1" disabled>
                        </div>
                      </div>
                      <input type="hidden" name="orden[<?= $ej["id"] ?>]" value="<?= $i + 100 ?>">
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" data-bs-dismiss="modal">Listo</button>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-success btn-lg">Guardar rutina</button>
      <a href="entrenador.php" class="btn btn-secondary btn-lg">Cancelar</a>
    </div>
  </form>
</div>

<!-- Footer -->
<footer class="footer">
  FlowFit &copy; <?= date("Y") ?> - Todos los derechos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Modo oscuro
  const toggleBtn = document.getElementById("toggleModo");
  if (localStorage.getItem("modoOscuro") === "true") {
    document.body.classList.add("dark-mode");
  }
  toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("modoOscuro", document.body.classList.contains("dark-mode"));
  });

  // Activar/desactivar inputs
  document.querySelectorAll('.ejercicio-check').forEach(check => {
    check.addEventListener('change', () => {
      const id = check.value;
      document.querySelector(`[name="sets[${id}]"]`).disabled = !check.checked;
      document.querySelector(`[name="reps[${id}]"]`).disabled = !check.checked;
    });
  });

  // Colapsar secciones
  function toggleSeccion(id) {
    document.getElementById(id).classList.toggle("d-none");
  }

  // Buscador
  const buscador = document.getElementById("buscadorModal");
  const tarjetas = document.querySelectorAll(".ejercicio-card-modal");
  buscador.addEventListener("input", function () {
    const texto = this.value.toLowerCase();
    tarjetas.forEach(card => {
      card.style.display = card.dataset.nombre.includes(texto) ? "block" : "none";
    });
  });
</script>
</body>
</html>
