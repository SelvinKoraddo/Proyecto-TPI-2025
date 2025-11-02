<?php
require_once '../Modelos/Conexion.php';

$db = (new Conexion())->getConexion();

// === Contadores resumen ===
$totalClientes = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn();
$totalSolicitudes = $db->query("SELECT COUNT(*) FROM solicitud")->fetchColumn();
$totalTecnicos = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'tecnico'")->fetchColumn();

// === Técnicos por estado ===
$queryEstados = $db->query("
  SELECT estado, COUNT(*) AS total
  FROM perfil_tecnico
  GROUP BY estado
");
$estados = $queryEstados->fetchAll(PDO::FETCH_ASSOC);

// === Técnicos por zona ===
$queryZonas = $db->query("
  SELECT zona_trabajo AS zona, COUNT(*) AS total
  FROM perfil_tecnico
  WHERE estado = 'aprobado'
  GROUP BY zona_trabajo
");
$zonas = $queryZonas->fetchAll(PDO::FETCH_ASSOC);

// === Técnicos por especialidad ===
$queryEspecialidades = $db->query("
  SELECT e.nombre AS especialidad, COUNT(te.id_tecnico) AS total
  FROM tecnico_especialidad te
  INNER JOIN especialidad e ON te.id_especialidad = e.id_especialidad
  GROUP BY e.nombre
");
$especialidades = $queryEspecialidades->fetchAll(PDO::FETCH_ASSOC);

// === Usuarios por rol ===
$queryRoles = $db->query("
  SELECT rol, COUNT(*) AS total
  FROM usuarios
  GROUP BY rol
");
$roles = $queryRoles->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes y Estadísticas | TechFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    html, body {
      height: 100%;
      display: flex;
      flex-direction: column;
      margin: 0;
    }

    body {
      background: linear-gradient(180deg, #1f56a5, #9340c7);
      color: white;
    }

    main {
      flex: 1;
    }

    footer {
      margin-top: auto;
      background-color: #212529;
      color: white;
      text-align: center;
      padding: 15px 0;
    }

    .card {
      border-radius: 12px;
    }
    .resumen-card {
      background-color: #1a1a1a;
      color: white;
      border: none;
      text-align: center;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
    }
    .resumen-card h4 {
      font-size: 1.1rem;
      margin-bottom: 8px;
    }
    .resumen-card h2 {
      font-weight: bold;
    }
    canvas {
      max-width: 600px;
      max-height: 400px;
      margin: 0 auto;
      display: block;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">📊 Reportes y Estadísticas - TechFix</span>
      <a href="adminPanel.php" class="btn btn-outline-light">⬅ Volver al Panel</a>
    </div>
  </nav>

  <main class="container my-5">

    <!-- === Tarjetas resumen === -->
    <div class="row text-center mb-4">
      <div class="col-md-4">
        <div class="resumen-card">
          <h4>Clientes Registrados</h4>
          <h2 class="text-primary"><?= $totalClientes ?></h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="resumen-card">
          <h4>Solicitudes Totales</h4>
          <h2 class="text-warning"><?= $totalSolicitudes ?></h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="resumen-card">
          <h4>Técnicos Registrados</h4>
          <h2 class="text-success"><?= $totalTecnicos ?></h2>
        </div>
      </div>
    </div>

    <!-- === Gráficas organizadas en 2x2 === -->
    <div class="row mb-5">
      <div class="col-md-6">
        <div class="card p-4 shadow mb-4">
          <h5 class="text-center text-dark">Técnicos por Estado</h5>
          <canvas id="graficoEstados"></canvas>
        </div>

        <div class="card p-4 shadow">
          <h5 class="text-center text-dark">Usuarios por Rol</h5>
          <canvas id="graficoUsuarios"></canvas>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card p-4 shadow mb-4">
          <h5 class="text-center text-dark">Técnicos por Zona</h5>
          <canvas id="graficoZonas"></canvas>
        </div>

        <div class="card p-4 shadow">
          <h5 class="text-center text-dark">Técnicos por Especialidad</h5>
          <canvas id="graficoEspecialidades"></canvas>
        </div>
      </div>
    </div>
  </main>

  <footer>
    © 2025 TechFix | Administrador
  </footer>

  <script>
    // === Gráfico: Técnicos por Estado ===
    const estados = <?= json_encode($estados) ?>;
    new Chart(document.getElementById('graficoEstados'), {
      type: 'doughnut',
      data: {
        labels: estados.map(e => e.estado.charAt(0).toUpperCase() + e.estado.slice(1)),
        datasets: [{
          data: estados.map(e => e.total),
          backgroundColor: ['#ffc107', '#28a745', '#dc3545']
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });

    // === Gráfico: Técnicos por Zona ===
    const zonas = <?= json_encode($zonas) ?>;
    new Chart(document.getElementById('graficoZonas'), {
      type: 'bar',
      data: {
        labels: zonas.map(z => z.zona),
        datasets: [{
          label: 'Técnicos Aprobados',
          data: zonas.map(z => z.total),
          backgroundColor: '#17a2b8'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
      }
    });

    // === Gráfico: Técnicos por Especialidad ===
    const especialidades = <?= json_encode($especialidades) ?>;
    new Chart(document.getElementById('graficoEspecialidades'), {
      type: 'bar',
      data: {
        labels: especialidades.map(e => e.especialidad),
        datasets: [{
          label: 'Cantidad de Técnicos',
          data: especialidades.map(e => e.total),
          backgroundColor: '#6f42c1'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
      }
    });

    // === Gráfico: Usuarios por Rol ===
    const roles = <?= json_encode($roles) ?>;
    new Chart(document.getElementById('graficoUsuarios'), {
      type: 'pie',
      data: {
        labels: roles.map(r => r.rol.charAt(0).toUpperCase() + r.rol.slice(1)),
        datasets: [{
          label: 'Distribución de Usuarios',
          data: roles.map(r => r.total),
          backgroundColor: ['#007bff', '#ffc107', '#6f42c1']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
      }
    });
  </script>
</body>
</html>



