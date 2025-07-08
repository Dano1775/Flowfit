<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["perfil"] !== "Entrenador") {
  header("Location: ../Inicio/inicio.html");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FlowFit - Entrenador</title>
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
      padding: 60px 0 30px;
    }


    .stats-section {
      margin-top: -50px;
      padding: 60px 20px;
      background-color: #ffffff;
    }

    .card {
      min-height: 290px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card .btn {
      margin-top: auto;
    }

    .card i {
      font-size: 2rem;
      margin-bottom: 15px;
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
    <a class="navbar-brand d-flex align-items-center" href="entrenador.php">
      <img src="../assets/logo_flowfit.png" alt="FlowFit" height="40" class="me-2" style="border-radius: 10px;">
      <span class="fs-4 fw-bold text-success">FlowFit</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEntrenador" aria-controls="navbarEntrenador" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarEntrenador">
      <ul class="navbar-nav me-3">
        <li class="nav-item"><a class="nav-link" href="rutinas_entrenador.php">Mis Rutinas</a></li>
        <li class="nav-item"><a class="nav-link" href="ejercicios_entrenador.php">Ejercicios</a></li>
        <li class="nav-item"><a class="nav-link" href="asignar_rutina.php">Asignar Rutinas</a></li>
      </ul>
      <a href="../index/index.html" class="btn btn-danger ms-2">Cerrar sesión</a>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero-section text-center d-flex align-items-center justify-content-center">
  <div class="container py-5 mt-4" data-aos="fade-up" data-aos-duration="1000">
    <h1 class="display-5 fw-bold mb-3">Hola, entrenador</h1>
    <p class="lead mb-4">Gestiona tus rutinas, crea ejercicios personalizados y asigna progreso a tus usuarios.</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="#panel" class="btn btn-success px-4">Ir al panel</a>
      <a href="https://flowfit.com/manual-entrenador" class="btn btn-outline-light px-4">Guía de uso</a>
    </div>
  </div>
</section>




<!-- Panel de acciones -->
<section class="stats-section text-center" id="panel">
  <div class="container">
    <div class="row g-4 justify-content-center" data-aos="fade-up" data-aos-duration="1000">

          <!-- Ejercicios -->
      <div class="col-md-4 col-sm-6">
        <div class="card p-4">
          <i class="bi bi-bicycle fs-1 text-primary mb-3"></i>
          <h5>Ejercicios</h5>
          <p>Gestiona y crea tus propios ejercicios o usa los existentes.</p>
          <a href="ejercicios_entrenador.php" class="btn btn-success w-100 mt-2">Ir a ejercicios</a>
        </div>
      </div>

      <!-- Crear rutina -->
      <div class="col-md-4 col-sm-6">
        <div class="card p-4">
          <i class="bi bi-clipboard-check fs-1 text-success mb-3"></i>
          <h5>Crear rutina</h5>
          <p>Diseña rutinas personalizadas para tus usuarios.</p>
          <a href="crear_rutina.php" class="btn btn-success w-100 mt-2">Crear rutina</a>
        </div>
      </div>

      <!-- Ver / Editar rutinas -->
      <div class="col-md-4 col-sm-6">
        <div class="card p-4">
          <i class="bi bi-pencil-square fs-1 text-warning mb-3"></i>
          <h5>Ver / Editar rutinas</h5>
          <p>Consulta, modifica o elimina tus rutinas existentes.</p>
          <a href="rutinas_entrenador.php" class="btn btn-success w-100 mt-2">Ver rutinas</a>
        </div>
      </div>

      <!-- Asignar rutina -->
      <div class="col-md-4 col-sm-6">
        <div class="card p-4">
          <i class="bi bi-person-plus fs-1 text-dark mb-3"></i>
          <h5>Asignar rutina</h5>
          <p>Asigna una rutina a uno o más usuarios desde tu panel.</p>
          <a href="asignar_rutina.php" class="btn btn-success w-100 mt-2">Asignar rutina</a>
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
