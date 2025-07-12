<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Administrador - FlowFit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h1 class="text-center">Bienvenido Administrador</h1>
  <p class="text-center">Tienes acceso completo al sistema FlowFit.</p>

  <h2 class="mt-5">Solicitudes pendientes</h2>
  <table class="table table-bordered mt-3">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Perfil</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
        include "../../models/usuario.php"; // ← CORREGIDO

        $admin = new administrador();
        $pendientes = $admin->ConsultaPendientes();

        foreach ($pendientes as $usuario) {
          echo "<tr>
                  <td>{$usuario['id']}</td>
                  <td>{$usuario['nombre']}</td>
                  <td>{$usuario['correo']}</td>
                  <td>{$usuario['telefono']}</td>
                  <td>{$usuario['perfil_usuario']}</td>
                  <td>
                    <a href='../../controllers/aprobar_usuario.php?id={$usuario['id']}' class='btn btn-success btn-sm'>Aceptar</a>
                  </td>
                </tr>";
        }
      ?>
    </tbody>
  </table>
</body>
</html>
