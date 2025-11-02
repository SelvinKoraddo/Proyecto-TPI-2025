<?php
session_start();
require_once '../Modelos/Conexion.php';

// Solo el admin puede entrar
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

$db = (new Conexion())->getConexion();

// Obtener todos los técnicos
$query = $db->prepare("
    SELECT pt.id_tecnico, 
           u.nombre_completo, 
           u.correo, 
           u.telefono, 
           pt.descripcion, 
           pt.zona_trabajo, 
           pt.tarifa_hora, 
           pt.estado
    FROM perfil_tecnico pt
    INNER JOIN usuarios u ON pt.id_usuario = u.id_usuario
    ORDER BY FIELD(pt.estado, 'pendiente','aprobado','rechazado')
");
$query->execute();
$tecnicos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Suspender Cuentas | TechFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    .estado-badge { padding: 5px 10px; border-radius: 8px; color: white; font-weight: 500; }
    .pendiente { background-color: #ffc107; }
    .aprobado { background-color: #28a745; }
    .rechazado { background-color: #dc3545; }
  </style>
</head>

<body>
  <nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">🚫 Gestionar Cuentas - TechFix</span>
      <a href="adminPanel.php" class="btn btn-outline-light">⬅ Volver al Panel</a>
    </div>
  </nav>

  <main class="container mt-5">
    <h2 class="text-center mb-4">Gestión de Técnicos</h2>

    <!-- 🔍 Buscador -->
    <div class="input-group mb-4">
      <span class="input-group-text bg-dark text-white">🔍 Buscar</span>
      <input type="text" id="buscador" class="form-control" placeholder="Buscar técnico por nombre...">
    </div>

    <!-- 🟡 Pendientes -->
    <div class="card shadow bg-light text-dark p-3 mb-4">
      <h4 class="text-center text-warning mb-3">Solicitudes Pendientes</h4>
      <div id="pendientes"></div>
    </div>

    <!-- 🟢 Aprobados y Rechazados -->
    <div class="card shadow bg-light text-dark p-3 mb-4">
      <h4 class="text-center text-success mb-3">Técnicos Aprobados / Rechazados</h4>
      <div id="activos"></div>
    </div>
  </main>

  <footer>
    © 2025 TechFix | Administrador
  </footer>

  <script>
    const tecnicos = <?php echo json_encode($tecnicos); ?>;

    // --- Renderizado ---
    function renderizar() {
      const pendientes = document.getElementById('pendientes');
      const activos = document.getElementById('activos');
      pendientes.innerHTML = ''; 
      activos.innerHTML = '';

      const filtro = document.getElementById('buscador').value.toLowerCase();

      tecnicos.forEach(t => {
        if (!t.nombre_completo.toLowerCase().includes(filtro)) return;

        const fila = document.createElement('div');
        fila.className = 'd-flex justify-content-between align-items-center border p-2 rounded mb-2';
        fila.innerHTML = `
          <div>
            <strong>${t.nombre_completo}</strong><br>
            <small>${t.correo} | ${t.telefono} | ${t.zona_trabajo}</small>
          </div>
          <div>
            <span class="estado-badge ${t.estado}">${t.estado.toUpperCase()}</span>
          </div>
          <div>${botonesPorEstado(t)}</div>
        `;

        if (t.estado === 'pendiente') pendientes.appendChild(fila);
        else activos.appendChild(fila);
      });
    }

    // --- Botones dinámicos según estado ---
    function botonesPorEstado(t) {
      switch (t.estado) {
        case 'pendiente':
          return `
            <button class="btn btn-success btn-sm me-1" onclick="actualizarEstado(${t.id_tecnico}, 'aprobado')">✅ Aprobar</button>
            <button class="btn btn-danger btn-sm" onclick="actualizarEstado(${t.id_tecnico}, 'rechazado')">❌ Rechazar</button>
          `;
        case 'aprobado':
          return `<button class="btn btn-warning btn-sm" onclick="actualizarEstado(${t.id_tecnico}, 'rechazado')">🚫 Desactivar</button>`;
        case 'rechazado':
          return `<button class="btn btn-primary btn-sm" onclick="actualizarEstado(${t.id_tecnico}, 'aprobado')">🔄 Reactivar</button>`;
      }
    }

    // --- Actualizar estado en tiempo real ---
    function actualizarEstado(id, nuevoEstado) {
      Swal.fire({
        title: '¿Confirmar acción?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: nuevoEstado === 'rechazado' ? '#d33' : '#28a745'
      }).then(result => {
        if (result.isConfirmed) {
          fetch('../Modelos/gestionarTecnicos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'accion=' + nuevoEstado + '&id=' + id
          })
          .then(res => res.text())
          .then(() => {
            const tecnico = tecnicos.find(t => t.id_tecnico == id);
            tecnico.estado = nuevoEstado;
            renderizar(); // Reorganiza en tiempo real
            Swal.fire('Hecho', 'El estado fue actualizado correctamente.', 'success');
          });
        }
      });
    }

    // --- Buscador dinámico ---
    document.getElementById('buscador').addEventListener('input', renderizar);

    // --- Render inicial ---
    renderizar();
  </script>
</body>
</html>



