<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechFix - Servicios Técnicos Profesionales</title>

    <!-- Bootstrap CSS v5.3.8 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Vistas/css/estilos.css">
    <script src="../Vistas/script/script.js" defer></script>


</head>

<body>
    <header>
        <nav class="containerH">
            <div class="logo">
                <i class="bi bi-wrench"> TechFix</i>
            </div>
            <ul class="nav-links">
                <li><a href="#inic">Solicitudes Recibidas</a></li>
                <li><a href="#Cf">Citas Programadas</a></li>
                <li><a href="#historial">Historial</a></li>
                <li><a href="#">Perfil</a></li>
            </ul>

            <div class="auth-buttons">
                <a href="Login.php" class="btn btn-outline">Cerrar Sesión</a>

            </div>
        </nav>
    </header>


    <main class="container">
        <section class="hero">
            <h1 id="inic">Servicios Técnicos Profesionales</h1>
            <p>Conectamos técnicos especializados con clientes que necesitan reparaciones de electrodomésticos y
                servicios de construcción</p>
        </section>

        <section class="features">
            <h2 id="Cf">¿Cómo Funciona?</h2>
            <div class="features-grid">
                <div class="feature">
                    <h3><i class="bi bi-journal-check"></i> Registro Fácil</h3>
                    <p>Los técnicos se registran con su especialidad, tarifas y zona de trabajo. Perfil verificado por
                        administrador.</p>
                </div>
                <div class="feature">
                    <h3><i class="bi bi-search"></i> Búsqueda Inteligente</h3>
                    <p>Los clientes buscan técnicos por tipo de reparación usando nuestro formulario.</p>
                </div>
                <div class="feature">
                    <h3><i class="bi bi-credit-card"></i> Pago Seguro</h3>
                    <p>Sistema de pagos integrado con PayPal Sandbox para transacciones seguras y confiables.</p>
                </div>
                <div class="feature">
                    <h3><i class="bi bi-star-fill"></i> Sistema de Calificaciones</h3>
                    <p>Módulo de calificaciones y reseñas para mantener la calidad del servicio.</p>
                </div>
                <div class="feature">
                    <h3><i class="bi bi-calendar4-week"></i> Gestión de Citas</h3>
                    <p>Programación y gestión de citas con historial completo de servicios.</p>
                </div>
                <div class="feature">
                    <h3><i class="bi bi-shield-check"></i> Control de Calidad</h3>
                    <p>Administrador gestiona solicitudes y puede suspender cuentas por incumplimientos.</p>
                </div>
                <a href="Pago.php" class="btn btn-outline">Realizar pago</a>
            </div>
        </section>
        <!-- SECCION PARA MOSTRAR HISTORIAL CITAS -->
        <section class="cta-section">
            <h2 id="historial">Historial Servicios</h2>
            <!-- Navegación con pestañas -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="citas-tab" data-bs-toggle="tab" data-bs-target="#citas"
                        type="button" role="tab" aria-controls="citas" aria-selected="true">Historial de Citas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pagos-tab" data-bs-toggle="tab" data-bs-target="#pagos" type="button"
                        role="tab" aria-controls="pagos" aria-selected="false">Historial de Pagos</button>
                </li>
            </ul>

            <!-- Contenido de cada pestaña -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active p-3" id="citas" role="tabpanel" aria-labelledby="citas-tab">
                    <?php
                    require_once '../Modelos/Conexion.php';
                    require_once '../Controladores/reservarCitaControlador.php';

                    $id_usuario = $_SESSION['Id'];
                    $db = (new Conexion())->getConexion();

                    // Obtener id_tecnico correspondiente al usuario
                    $stmt = $db->prepare("SELECT id_tecnico FROM perfil_tecnico WHERE id_usuario = ?");
                    $stmt->execute([$id_usuario]);
                    $id_tecnico = $stmt->fetchColumn();

                    $citas = [];
                    if ($id_tecnico) {
                        $stmt = $db->prepare("
    SELECT c.*, s.id_usuario, u.nombre_completo AS cliente
    FROM cita c
    INNER JOIN solicitud s ON c.id_solicitud = s.id_solicitud
    INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
    WHERE s.id_tecnico = ?
    ORDER BY c.fecha_inicio DESC
  ");
                        $stmt->execute([$id_tecnico]);
                        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    ?>

                    <?php if (empty($citas)): ?>
                        <div class="alert alert-info text-center">No tienes citas registradas.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>ID Cita</th>
                                        <th>Cliente</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado</th>
                                        <th>Notas</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($citas as $c): ?>
                                        <?php
                                        $stmtMonto = $db->prepare("SELECT monto FROM solicitud WHERE id_solicitud = ?");
                                        $stmtMonto->execute([$c['id_solicitud']]);
                                        $monto = $stmtMonto->fetchColumn();
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($c['id_cita']) ?></td>
                                            <td><?= htmlspecialchars($c['cliente']) ?></td>
                                            <td><?= htmlspecialchars($c['fecha_inicio']) ?></td>
                                            <td><?= htmlspecialchars($c['fecha_fin']) ?></td>
                                            <td><?= ucfirst(htmlspecialchars($c['estado'])) ?></td>
                                            <td><?= htmlspecialchars($c['notas']) ?></td>
                                            <td><?= $monto ? "$" . number_format($monto, 2) : '—' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="tab-pane fade p-3" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
                    <?php
                    $stmt = $db->prepare("
                          SELECT p.id_pago, p.monto, p.estado, p.fecha_pago, u.nombre_completo AS cliente
                          FROM pago p
                          INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                          WHERE p.id_tecnico = ?
                          ORDER BY p.fecha_pago DESC
                        ");
                    $stmt->execute([$id_tecnico]);
                    $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if (empty($pagos)): ?>
                        <div class="alert alert-info text-center">Aún no has recibido pagos.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th>ID Pago</th>
                                        <th>Cliente</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Fecha Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagos as $p): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($p['id_pago']) ?></td>
                                            <td><?= htmlspecialchars($p['cliente']) ?></td>
                                            <td>$<?= number_format($p['monto'], 2) ?></td>
                                            <td><?= ucfirst($p['estado']) ?></td>
                                            <td><?= $p['fecha_pago'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            </div>
        </section>
        <!-- FIN SECCION PARA MOSTRAR HISTORIAL CITAS -->
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>TechFix</h4>
                    <p>Conectando técnicos profesionales con clientes que necesitan servicios de calidad.</p>
                </div>
                <div class="footer-section">
                    <h4>Integrantes</h4>
                    <a href="#">Selvin Obed</a><br>
                    <a href="#">Ulises Bladimir</a><br>
                    <a href="#">Isaak Palacios</a><br>
                    <a href="#">Robert David</a>
                </div>
                <div class="footer-section">
                    <h4>Tecnologías</h4>
                    <p>HTML5 • CSS3 • JavaScript • Bootstrap • PHP • MySQL • API REST</p>
                </div>
            </div>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>