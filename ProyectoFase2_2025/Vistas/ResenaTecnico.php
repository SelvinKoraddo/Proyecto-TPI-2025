<!DOCTYPE html>
<?php
session_start();
require_once("../Modelos/tecnicoModelo.php");
require_once("../Modelos/resenaModelo.php");

if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'cliente') {
    header('Location: Login.php');
    exit;
}

$tecnicoModelo = new TecnicoModelo();
$resenaModelo = new ResenaModelo();

$tecnicos = $tecnicoModelo->obtenerTecnicos();

$id_tecnico = isset($_GET['id_tecnico']) ? $_GET['id_tecnico'] : null;
$resenas = [];

if ($id_tecnico) {
    $resenas = $resenaModelo->obtenerResenasPorTecnico($id_tecnico);
}
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dejar Reseña - TechFix</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Vistas/css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .star-rating {
            font-size: 2.5rem;
            direction: rtl;
            display: inline-flex;
            gap: 5px;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }
    </style>
</head>

<body>

    <?php if (isset($_GET['exito'])): ?>
    <div class="alert alert-success text-center mx-3 mt-3">Reseña registrada correctamente.</div>
    <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center mx-3 mt-3">Ocurrió un error al registrar la reseña o la reseña ya fue creada.</div>
    <?php endif; ?>

    <main class="container my-5">
        <section class="hero text-center">
            <h1>Dejar una Reseña</h1>
            <p class="lead">Comparte tu experiencia con el servicio del técnico</p>
        </section>

        <section class="register-section d-flex justify-content-center align-items-center mt-4">
            <div class="card p-4 shadow-lg" style="max-width: 600px; width: 100%; border-radius: 15px;">
                <form action="../Controladores/resenaControlador.php" method="POST" id="formResena">
                    
                    <?php
                    $nombreTecnico = '';
                    if ($id_tecnico) {
                        foreach ($tecnicos as $t) {
                            if ($t['id_tecnico'] == $id_tecnico) {
                                $nombreTecnico = $t['nombre_completo'];
                                break;
                            }
                        }
                    }
                    ?>
                    <div class="mb-4">
                        <label class="form-label">Técnico</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($nombreTecnico) ?>" disabled>
                        <input type="hidden" name="tecnico_id" value="<?= htmlspecialchars($id_tecnico) ?>">
                    </div>

                    <input type="hidden" name="id_solicitud" value="<?= htmlspecialchars($_GET['id_solicitud'] ?? '') ?>">

                    <div class="mb-4 text-center">
                        <label class="form-label d-block mb-3">Calificación</label>
                        <div class="star-rating">
                            <input type="radio" name="calificacion" value="5" id="star5" required>
                            <label for="star5"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" name="calificacion" value="4" id="star4">
                            <label for="star4"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" name="calificacion" value="3" id="star3">
                            <label for="star3"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" name="calificacion" value="2" id="star2">
                            <label for="star2"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" name="calificacion" value="1" id="star1">
                            <label for="star1"><i class="bi bi-star-fill"></i></label>
                        </div>
                        <small class="text-muted d-block mt-2">Selecciona de 1 a 5 estrellas</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tu Comentario</label>
                        <textarea name="comentario" class="form-control" rows="5" 
                                  placeholder="Cuéntanos sobre tu experiencia con el técnico..." 
                                  required></textarea>
                        <small class="text-muted">Mínimo 10 caracteres</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send-fill me-2"></i>Enviar Reseña
                    </button>
                </form>
                
                <?php if ($id_tecnico): ?>
                    <hr class="my-5">
                    <h2 class="text-center mb-4">Reseñas del Técnico</h2>
                    <section>
                        <div class="card shadow-sm">
                            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #6a11cb;">
                                <h4 class="mb-0"><i class="bi bi-star-fill me-2"></i>Opiniones de Clientes</h4>
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
                    <a href="Home.php" class="btn btn-outline-secondary mt-3">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                <?php endif; ?>

            </div>
        </section>
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
    
    <script>
        document.getElementById('formResena').addEventListener('submit', function(e) {
            const comentario = document.querySelector('textarea[name="comentario"]').value;
            if (comentario.length < 10) {
                e.preventDefault();
                alert('El comentario debe tener al menos 10 caracteres');
            }
        });
    </script>
</body>
</html>