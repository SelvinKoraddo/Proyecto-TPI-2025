<!DOCTYPE html>
<?php
session_start();
require_once("../Modelos/tecnicoModelo.php");
require_once("../Modelos/resenaModelo.php");

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

    <?php if (isset($_GET['exito'])): ?>
    <div class="alert alert-success text-center mx-3 mt-3">Reseña registrada correctamente.</div>
    <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center mx-3 mt-3">Ocurrió un error al registrar la reseña.</div>
    <?php endif; ?>

    <main class="container my-5">
        <section class="hero text-center">
            <h1>Dejar una Reseña</h1>
            <p class="lead">Comparte tu experiencia con el servicio del técnico</p>
        </section>

        <section class="register-section d-flex justify-content-center align-items-center mt-4">
            <div class="card p-4 shadow-lg" style="max-width: 600px; width: 100%; border-radius: 15px;">
                <form action="../Controladores/resenaControlador.php" method="POST" id="formResena">
                    
                    <div class="mb-4">
                        <label class="form-label">Selecciona el Técnico</label>
                        <select name="tecnico_id" class="form-select" required onchange="location = '?id_tecnico=' + this.value;">
                            <option value="">-- Selecciona un técnico --</option>
                            <?php foreach ($tecnicos as $tec): ?>
                                <option value="<?= $tec['id_tecnico'] ?>" <?= ($id_tecnico == $tec['id_tecnico']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tec['nombre_completo']) ?> - <?= htmlspecialchars($tec['zona_trabajo']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                    </div>

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

    <?php if (empty($resenas)): ?>
        <p class="text-center text-muted">Este técnico aún no tiene reseñas.</p>
    <?php else: ?>
        <div class="row justify-content-center">
            <?php foreach ($resenas as $r): ?>
                <div class="col-md-8 mb-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><?= htmlspecialchars($r['nombre_completo']) ?></strong>
                            <span>
                                <?php for ($i = 0; $i < $r['calificacion']; $i++): ?>
                                    <i class="bi bi-star-fill text-warning"></i>
                                <?php endfor; ?>
                                <?php for ($i = $r['calificacion']; $i < 5; $i++): ?>
                                    <i class="bi bi-star text-muted"></i>
                                <?php endfor; ?>
                            </span>
                        </div>
                        <p class="mt-2 mb-0"><?= htmlspecialchars($r['comentario']) ?></p>
                        <small class="text-muted"><?= $r['fecha_creada'] ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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