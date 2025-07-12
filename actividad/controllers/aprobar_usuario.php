<?php
include "../models/usuario.php";

if (isset($_GET["id"])) {
    $admin = new administrador();
    $admin->Aprobar($_GET["id"]);
    
    // Alerta en JavaScript y redirección
    echo "<script>
            alert('Usuario aprobado exitosamente.');
            window.location.href = '../views/administrador/administrador.php';
          </script>";
} else {
    echo "<script>
            alert('ID de usuario no válido.');
            window.location.href = '../views/administrador/administrador.php';
          </script>";
}
?>
