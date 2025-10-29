<?php
session_start();
require_once("../Modelos/tecnicoModelo.php");
require_once("../Modelos/resenaModelo.php");

$tecnicoModelo = new TecnicoModelo();
$resenaModelo = new ResenaModelo();

$id_tecnico = isset($_GET['id_tecnico']) ? $_GET['id_tecnico'] : null;
$tecnico = null;
$resenas = [];
$promedio = 0;

if ($id_tecnico) {
    $tecnico = $tecnicoModelo->obtenerTecnicoPorId($id_tecnico);
    $resenas = $resenaModelo->obtenerResenasPorTecnico($id_tecnico);
    if (!empty($resenas)) {
        $suma = array_sum(array_column($resenas, 'calificacion'));
        $promedio = round($suma / count($resenas), 1);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Técnico - TechFix</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Vistas/css/estilos.css">
</head>
<body>
<header>
    <nav class="container">
        <div class="logo">
            <i class="bi bi-wrench"> TechFix</i>
        </div>
        <ul class="nav-links">
            <li><a href="../index.php#inic">Inicio</a></li>
            <li><a href="../index.php#serv">Servicios</a></li>
            <li><a href="../index.php#Cf">Cómo Funciona</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="../index.php#log" class="btn btn-outline">Iniciar Sesión</a>
            <a href="../index.php#log" class="btn btn-primary">Registrarse</a>
        </div>
    </nav>
</header>

<main class="container my-5">
<?php if (!$tecnico): ?>
    <div class="alert alert-danger text-center">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Técnico no encontrado.
    </div>
<?php else: ?>
    
    <div class="card shadow-lg mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="imagenes/tv.jpg" alt="Foto del técnico"
                         class="rounded-circle shadow"
                         style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #f8f9fa;">
                </div>

                <div class="col-md-9">
                    <h1 class="mb-3"><?= htmlspecialchars($tecnico['nombre_completo']) ?></h1>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                <strong>Email:</strong> <?= htmlspecialchars($tecnico['correo']) ?>
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-telephone-fill text-success me-2"></i>
                                <strong>Teléfono:</strong> <?= htmlspecialchars($tecnico['telefono']) ?>
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                <strong>Zona:</strong> <?= htmlspecialchars($tecnico['zona_trabajo']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-cash-coin text-warning me-2"></i>
                                <strong>Tarifa por hora:</strong> 
                                <span class="badge bg-success fs-6">$<?= htmlspecialchars($tecnico['tarifa_hora']) ?></span>
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-tools text-info me-2"></i>
                                <strong>Especialidades:</strong> 
                                <span class="badge bg-primary"><?= htmlspecialchars($tecnico['especialidades'] ?? 'No registradas') ?></span>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-3">
                        <div>
                            <strong class="me-2">Calificación:</strong>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi <?= ($i <= $promedio) ? 'bi-star-fill text-warning' : 'bi-star text-muted' ?>" style="font-size: 1.3rem;"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="badge bg-warning text-dark fs-6"><?= $promedio ?>/5</span>
                        <span class="text-muted">(<?= count($resenas) ?> <?= count($resenas) == 1 ? 'reseña' : 'reseñas' ?>)</span>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary me-2">
                            <i class="bi bi-calendar-check me-2"></i>Solicitar Servicio
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-chat-dots me-2"></i>Enviar Mensaje
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header text-white" style="background-color: #6a11cb;">
            <h4 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Sobre el Técnico</h4>
        </div>
        <div class="card-body p-4">
            <p class="mb-0" style="line-height: 1.8;"><?= nl2br(htmlspecialchars($tecnico['descripcion'])) ?></p>
        </div>
    </div>

    <section>
        <div class="card shadow-sm">
            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #6a11cb;">
                <h4 class="mb-0"><i class="bi bi-star-fill me-2"></i>Opiniones de Clientes</h4>
                <a href="DejarResena.php?id_tecnico=<?= $id_tecnico ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-pencil-square me-2"></i>Dejar una reseña
                </a>
            </div>
            <div class="card-body p-3" style="max-height: 600px; overflow-y: auto;">
                <?php if (empty($resenas)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3 mb-0">Este técnico aún no tiene reseñas. ¡Sé el primero en dejar tu opinión!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($resenas as $r): ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="bi bi-person-circle text-primary me-2"></i>
                                            <?= htmlspecialchars($r['nombre_completo']) ?>
                                        </h5>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            <?= date('d/m/Y', strtotime($r['fecha_creada'])) ?>
                                        </small>
                                    </div>
                                    <div>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi <?= ($i <= $r['calificacion']) ? 'bi-star-fill text-warning' : 'bi-star text-muted' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="mb-0 mt-3"><?= htmlspecialchars($r['comentario']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>