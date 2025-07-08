<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Usuario") {
  header("Location: ../Inicio/inicio.html");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FlowFit - Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 0;
    }

    .navbar-brand {
      font-weight: bold;
      color: #4ADE80 !important;
    }

    .hero-section {
      min-height: 50vh;
      background: linear-gradient(to right, #1f2937, #4ade80);
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding-top: 100px;
    }

    .stats-section {
      margin-top: -50px;
      padding: 60px 20px;
      background-color: #ffffff;
    }

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .footer {
      background-color: #1f2937;
      color: white;
      padding: 20px;
      margin-top: 60px;
    }

    .btn-success {
      background-color: #4ade80;
      border: none;
    }

    .btn-success:hover {
      background-color: #3ac47d;
      transform: scale(1.05);
    }

    .btn-outline-light:hover {
      background-color: white;
      color: #1F2937;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="../assets/logo_flowfit.png" alt="FlowFit" height="40" class="me-2" style="border-radius: 10px;">
      <span class="fs-4 fw-bold text-success">FlowFit</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUsuario" aria-controls="navbarUsuario" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarUsuario">
      <ul class="navbar-nav me-3">
        <li class="nav-item"><a class="nav-link" href="#">Mis Rutinas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Mi Progreso</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Mi Perfil</a></li>
      </ul>
      <a href="../index/index.html" class="btn btn-danger ms-2">Cerrar sesión</a>
    </div>
  </div>
</nav>



<!-- Hero -->
<section class="hero-section">
  <div data-aos="fade-up" data-aos-duration="1000">
    <h1 class="display-5 fw-bold">Este es tu espacio.</h1>
    <p class="lead mt-3">Consulta tus rutinas, revisa tu progreso y actualiza tu perfil personal.</p>
    <div class="mt-4">
      <a href="#panel" class="btn btn-success me-2">Comenzar</a>
      <a href="#" class="btn btn-outline-light">Saber más</a>
    </div>
  </div>
</section>

<!-- Dashboard -->
<section class="stats-section text-center" id="panel">
  <div class="container">
    <div class="row g-4 justify-content-center" data-aos="fade-up" data-aos-duration="1000">
      <div class="col-md-4 col-sm-6">
        <div class="card p-4">
          <i class="bi bi-list-check fs-1 text-success mb-3"></i>
          <h5>Mis Rutinas</h5>
          <p>Consulta las rutinas asignadas por tu entrenador.</p>
          <a href="ver_rutinas_usuario.php" class="btn btn-success w-100 mt-2">Ver rutinas</a>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card p-4">
          <i class="bi bi-graph-up-arrow fs-1 text-primary mb-3"></i>
          <h5>Progreso</h5>
          <p>Visualiza tus avances y logros semanales.</p>
          <a href="progreso.php" class="btn btn-success w-100 mt-2">Ver progreso</a>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card p-4">
          <i class="bi bi-person-circle fs-1 text-dark mb-3"></i>
          <h5>Mi Perfil</h5>
          <p>Modifica tus datos y objetivos personales.</p>
          <a href="perfil.php" class="btn btn-success w-100 mt-2">Editar perfil</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer text-center">
  <p>© 2025 FlowFit. Todos los derechos reservados.</p>
  <p>
    Síguenos en 
    <a href="#" class="text-success">Instagram</a> · 
    <a href="#" class="text-success">Facebook</a> · 
    <a href="#" class="text-success">X</a>
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
