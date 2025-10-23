<?php
session_start();
require_once '../Modelos/Conexion.php';

// para Verificar si viene el parámetro especialidad
$especialidad = $_GET['especialidad'] ?? '';
$db = (new Conexion())->getConexion();

// Consulta para traer los técnicos con su perfil y especialidad
$sql = "SELECT u.nombre_completo, e.nombre AS especialidad, 
               p.tarifa_hora, p.zona_trabajo
        FROM usuarios u
        INNER JOIN perfil_tecnico p ON u.id_usuario = p.id_usuario
        INNER JOIN tecnico_especialidad te ON p.id_tecnico = te.id_tecnico
        INNER JOIN especialidad e ON te.id_especialidad = e.id_especialidad
        WHERE e.nombre LIKE :especialidad
          AND p.estado = 'aprobado'";

$stmt = $db->prepare($sql);
$stmt->execute(['especialidad' => "%$especialidad%"]);
$tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resultados - Técnicos en <?php echo htmlspecialchars($especialidad); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f8fa; font-family: 'Segoe UI'; }
    .card-tech {
      transition: all 0.2s ease;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .card-tech:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body class="container py-5">

  <h2 class="text-center mb-4">Técnicos especializados en "<?php echo htmlspecialchars($especialidad); ?>"</h2>

  <?php if (empty($tecnicos)): ?>
    <p class="text-center">No se encontraron técnicos disponibles en esta especialidad.</p>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($tecnicos as $tec): ?>
        <div class="col-md-4">
          <div class="card card-tech p-3">
            <h5><?php echo htmlspecialchars($tec['nombre_completo']); ?></h5>
            <p><strong>Especialidad:</strong> <?php echo htmlspecialchars($tec['especialidad']); ?></p>
            <p><strong>Tarifa por hora:</strong> $<?php echo htmlspecialchars($tec['tarifa_hora']); ?></p>
            <p><strong>Zona de trabajo:</strong> <?php echo htmlspecialchars($tec['zona_trabajo']); ?></p>
            <div class="d-flex justify-content-between">
              <button class="btn btn-outline-primary btn-sm">Contactar</button>
              <button class="btn btn-outline-success btn-sm">Programar cita</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="Home.php" class="btn btn-secondary">Volver</a>
  </div>

</body>
</html>
