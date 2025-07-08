<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Entrenador") {
  header("Location: ../../Inicio/inicio.html");
  exit;
}

include "../../models/conexion.php";
$entrenador_id = $_SESSION["id"];

// Eliminar ejercicio
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar_id"])) {
  $stmt = $conexion->prepare("DELETE FROM ejercicio_catalogo WHERE id = ? AND creado_por = ?");
  $stmt->execute([$_POST["eliminar_id"], $entrenador_id]);
}

// Editar ejercicio
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar_id"])) {
  $stmt = $conexion->prepare("UPDATE ejercicio_catalogo SET nombre = ?, descripcion = ? WHERE id = ? AND creado_por = ?");
  $stmt->execute([$_POST["editar_nombre"], $_POST["editar_descripcion"], $_POST["editar_id"], $entrenador_id]);
}

// Consultar ejercicios
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
  <title>Ejercicios - FlowFit</title>
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

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 5px 12px rgba(0,0,0,0.1);
    }

    .card-img-top {
      width: 100%;
      height: 200px;
      object-fit: contain;
      background-color: #f8f9fa;
      padding: 10px;
    }

    .collapsible-header {
      cursor: pointer;
      margin-top: 30px;
      font-weight: bold;
    }

    .collapsible-header:hover {
      text-decoration: underline;
    }

    .btn-flotante {
      position: fixed;
      bottom: 30px;
      right: 30px;
      z-index: 1000;
    }

    footer {
      background-color: #222;
      color: #fff;
      text-align: center;
      padding: 15px;
      margin-top: auto;
    }

    .modo-toggle {
      position: fixed;
      top: 10px;
      right: 15px;
      z-index: 1001;
    }

    .card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    z-index: 5;
  }

  </style>
</head>
<body>

<!-- Navbar Header -->
<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="entrenador.php">
      <img src="../assets/logo_flowfit.png" alt="FlowFit" height="40" class="me-2">
      <span class="fs-4 text-success fw-bold">FlowFit</span>
    </a>
  </div>
</nav>


<!-- Toggle oscuro -->
<div class="modo-toggle">
  <button id="toggleModo" class="btn btn-sm btn-outline-dark">🌓</button>
</div>

<!-- Contenido -->
<div class="container main-content">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Ejercicios disponibles</h2>
    <a href="entrenador.php" class="btn btn-outline-danger">← Volver</a>
  </div>

  <input type="text" id="buscador" class="form-control mb-4" placeholder="Buscar ejercicios...">

  <!-- Plataforma -->
  <h5 class="collapsible-header text-success" onclick="toggleSeccion('plataforma')">Ejercicios de la plataforma</h5>
  <div class="row" id="plataforma">
    <?php foreach ($global as $ej): ?>
      <div class="col-md-4 ejercicio-card mb-4" data-nombre="<?= strtolower($ej["nombre"]) ?>">
        <div class="card">
          <img src="/Flowfit/actividad/ejercicio_image_uploads/<?= $ej["imagen"] ?>" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title"><?= $ej["nombre"] ?></h5>
            <p class="card-text"><?= nl2br($ej["descripcion"]) ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Personales -->
  <h5 class="collapsible-header text-primary mt-4" onclick="toggleSeccion('personales')">Ejercicios creados por ti</h5>
  <div class="row" id="personales">
    <?php foreach ($personales as $ej): ?>
      <div class="col-md-4 ejercicio-card mb-4" data-nombre="<?= strtolower($ej["nombre"]) ?>">
        <div class="card">
          <img src="/Flowfit/actividad/ejercicio_image_uploads/user_uploads/<?= $ej["imagen"] ?>" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title"><?= $ej["nombre"] ?></h5>
            <p class="card-text"><?= nl2br($ej["descripcion"]) ?></p>
            <div class="d-flex justify-content-between mt-3">
              <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editarModal<?= $ej["id"] ?>">Editar</button>
              <form method="POST" onsubmit="return confirm('¿Eliminar este ejercicio?')">
                <input type="hidden" name="eliminar_id" value="<?= $ej["id"] ?>">
                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal edición -->
      <div class="modal fade" id="editarModal<?= $ej["id"] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title">Editar ejercicio</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="editar_id" value="<?= $ej["id"] ?>">
              <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" name="editar_nombre" class="form-control" value="<?= $ej["nombre"] ?>" required>
              </div>
              <div class="mb-3">
                <label>Descripción:</label>
                <textarea name="editar_descripcion" class="form-control" rows="4" required><?= $ej["descripcion"] ?></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Guardar</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Botón Crear flotante con modal -->
<button class="btn btn-success btn-sm btn-flotante" data-bs-toggle="modal" data-bs-target="#modalCrearEjercicio">+ Crear ejercicio</button>

<!-- Modal crear ejercicio -->
<div class="modal fade" id="modalCrearEjercicio" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form id="formCrearEjercicio" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Crear nuevo ejercicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre del ejercicio</label>
          <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Descripción detallada</label>
          <textarea name="descripcion" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Imagen del ejercicio</label>
          <input type="file" name="imagen" accept="image/*" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Footer -->
<footer>
  FlowFit &copy; <?= date("Y") ?> - Todos los derechos reservados.
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Buscador
  const cards = document.querySelectorAll('.ejercicio-card');
  const buscador = document.getElementById('buscador');
  buscador.addEventListener('input', function () {
    const texto = this.value.toLowerCase();
    cards.forEach(card => {
      const nombre = card.dataset.nombre;
      card.style.display = nombre.includes(texto) ? 'block' : 'none';
    });
  });

  // Colapsar secciones
  function toggleSeccion(id) {
    document.getElementById(id).classList.toggle("d-none");
  }

  // Modo oscuro con localStorage
  const toggleBtn = document.getElementById("toggleModo");
  if (localStorage.getItem("modoOscuro") === "true") {
    document.body.classList.add("dark-mode");
  }
  toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("modoOscuro", document.body.classList.contains("dark-mode"));
  });

  // Crear ejercicio AJAX
  document.getElementById("formCrearEjercicio").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch("../../controllers/crear_ejercicio_controller.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(respuesta => {
      alert("Ejercicio creado correctamente");
      location.reload(); // si prefieres podemos actualizar solo el DOM
    })
    .catch(err => {
      alert("Error al guardar el ejercicio");
      console.error(err);
    });
  });
</script>
</body>
</html>
