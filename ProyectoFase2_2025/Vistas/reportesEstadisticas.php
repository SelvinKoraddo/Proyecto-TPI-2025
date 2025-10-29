<?php
require_once '../Modelos/Conexion.php';
$db = (new Conexion())->getConexion();

// Total de usuarios activos y suspendidos
$queryEstados = $db->query("SELECT estado, COUNT(*) AS total FROM usuarios GROUP BY estado");
$estados = $queryEstados->fetchAll(PDO::FETCH_ASSOC);

// Total de usuarios por rol
$queryRoles = $db->query("SELECT rol, COUNT(*) AS total FROM usuarios GROUP BY rol");
$roles = $queryRoles->fetchAll(PDO::FETCH_ASSOC);

// Total de solicitudes por mes (si existe tabla solicitud)
$querySolicitudes = $db->query("
    SELECT MONTH(fecha_creado) AS mes, COUNT(*) AS total 
    FROM solicitud 
    GROUP BY MONTH(fecha_creado)
");
$solicitudes = $querySolicitudes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes y Estadísticas - TechFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="background: linear-gradient(180deg, #1f56a5, #9340c7); color: white;">
  <nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">📊 Reportes y Estadísticas - TechFix</span>
      <a href="adminPanel.php" class="btn btn-outline-light">Volver al Panel</a>
    </div>
  </nav>

  <div class="container my-5">
    <h2 class="text-center mb-4">Resumen General del Sistema</h2>

    <div class="row text-center mb-5">
      <div class="col-md-4">
        <div class="card shadow bg-dark text-white p-3">
          <h4>Usuarios Activos</h4>
          <h2 class="text-success">
            <?php
              $activos = 0;
              foreach ($estados as $e) if ($e['estado'] == 'activo') $activos = $e['total'];
              echo $activos;
            ?>
          </h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow bg-dark text-white p-3">
          <h4>Usuarios Suspendidos</h4>
          <h2 class="text-danger">
            <?php
              $suspendidos = 0;
              foreach ($estados as $e) if ($e['estado'] == 'suspendido') $suspendidos = $e['total'];
              echo $suspendidos;
            ?>
          </h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow bg-dark text-white p-3">
          <h4>Solicitudes Totales</h4>
          <h2 class="text-warning">
            <?php echo count($solicitudes); ?>
          </h2>
        </div>
      </div>
    </div>

    <div class="row mb-5">
      <div class="col-md-6">
        <div class="card p-4 shadow">
          <h5 class="text-center text-dark">Usuarios por Estado</h5>
          <canvas id="graficoEstados"></canvas>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card p-4 shadow">
          <h5 class="text-center text-dark">Usuarios por Rol</h5>
          <canvas id="graficoRoles"></canvas>
        </div>
      </div>
    </div>

    <div class="card p-4 shadow mb-5">
      <h5 class="text-center text-dark">Solicitudes por Mes</h5>
      <canvas id="graficoSolicitudes"></canvas>
    </div>
  </div>

  <footer class="text-center mt-5 p-3 bg-dark">
    <p class="mb-0">© 2025 TechFix | Administrador</p>
  </footer>

  <script>
    // Datos desde PHP
    const estados = <?php echo json_encode($estados); ?>;
    const roles = <?php echo json_encode($roles); ?>;
    const solicitudes = <?php echo json_encode($solicitudes); ?>;

    // --- Grafico de Estados ---
    const ctxEstados = document.getElementById('graficoEstados');
    new Chart(ctxEstados, {
      type: 'doughnut',
      data: {
        labels: estados.map(e => e.estado),
        datasets: [{
          data: estados.map(e => e.total),
          backgroundColor: ['#28a745', '#dc3545']
        }]
      }
    });

    // --- Grafico de Roles ---
    const ctxRoles = document.getElementById('graficoRoles');
    new Chart(ctxRoles, {
      type: 'pie',
      data: {
        labels: roles.map(r => r.rol),
        datasets: [{
          data: roles.map(r => r.total),
          backgroundColor: ['#007bff', '#ffc107', '#6610f2']
        }]
      }
    });

    // --- Grafico de Solicitudes ---
    const ctxSolicitudes = document.getElementById('graficoSolicitudes');
    new Chart(ctxSolicitudes, {
      type: 'bar',
      data: {
        labels: solicitudes.map(s => 'Mes ' + s.mes),
        datasets: [{
          label: 'Solicitudes',
          data: solicitudes.map(s => s.total),
          backgroundColor: '#17a2b8'
        }]
      }
    });
  </script>
</body>
</html>
