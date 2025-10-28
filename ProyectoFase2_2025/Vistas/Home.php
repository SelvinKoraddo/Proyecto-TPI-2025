<?php
session_start();
require_once '../Modelos/Conexion.php';

// Verificar sesión y rol
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'cliente') {
    header('Location: Login.php');
    exit;
}

$id_usuario = $_SESSION['Id'];
$mensaje = '';

$db = (new Conexion())->getConexion();

//Obtener datos personales y técnicos del usuario
$sql = "SELECT nombre_completo FROM usuarios 
        WHERE id_usuario = :id_usuario";

$stmt = $db->prepare($sql);
$stmt->execute(['id_usuario' => $id_usuario]);
$tecnico = $stmt->fetch(PDO::FETCH_ASSOC);
$primerNombre = explode(" ", $tecnico['nombre_completo'])[0];//obtener primer nombre

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
                <li><a href="#inic">Inicio</a></li>
                <li><a href="#serv">Servicios</a></li>
                <li><a href="#Cf">Cómo Funciona</a></li>
                <li><a href="#historial">Historial</a></li>
                <li><a href="PerfilCliente.php">Perfil</a></li>
                <li>
                    <h5>Bienvenid@: <?= htmlspecialchars($primerNombre) ?></h5>
                </li>

            </ul>

            <div class="auth-buttons">
                <a href="Login.php" class="btn btn-outline">Cerrar Sesión</a>

            </div>
        </nav>
    </header>
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./imagenes/construccion2.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="./imagenes/construccion3.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="./imagenes/construccion1.jpg" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <main class="container">
        <section class="hero">
            <h1 id="inic">Servicios Técnicos Profesionales</h1>
            <p>Conectamos técnicos especializados con clientes que necesitan reparaciones de electrodomésticos y
                servicios de construcción</p>
        </section>

        <section class="search-section">
            <h2 id="tperfect">Encuentra el Técnico Perfecto</h2>

            <form class="search-form" action="BuscarTecnicos.php" method="GET">
                <div class="form-group">
                    <label for="service-type">Tipo de Servicio</label>
                    <select id="service-type" name="especialidad">
                        <option value="">Selecciona un servicio</option>
                        <option value="Refrigeradora">Reparación Refrigeradora</option>
                        <option value="Lavadora">Reparación Lavadora</option>
                        <option value="Horno">Reparación Horno</option>
                        <option value="Televisor">Reparación Televisor</option>
                        <option value="Albañilería">Servicios de Albañilería</option>
                        <option value="Electricidad">Servicios Eléctricos</option>
                    </select>

                </div>
                <button type="submit" class="btn btn-primary">Buscar Técnicos</button>

            </form>
        </section>
        <h1 id="serv">Servicios</h1>
        <section class="service-categories">

            <div class="service-card" onclick="window.location.href='BuscarTecnicos.php?especialidad=Refrigeradora'">
                <div class="service-img">
                    <img src="./imagenes/refri.jpg" alt="Imagen de refrigeradora" />
                </div>
                <h3>Refrigeradoras</h3>
                <p>Reparación y mantenimiento de refrigeradoras de todas las marcas</p>
            </div>
            <div class="service-card" onclick="window.location.href='BuscarTecnicos.php?especialidad=Lavadoras'">
                <div class="service-img">
                    <img src="./imagenes/lavadora.jpg" alt="Imagen de lavadora" />
                </div>
                <h3>Lavadoras</h3>
                <p>Servicio especializado en lavadoras y secadoras</p>
            </div>
            <div class="service-card" onclick="window.location.href='BuscarTecnicos.php?especialidad=Hornos'">
                <div class="service-img">
                    <img src="./imagenes/horno.jpg" alt="Imagen de lavadora" />
                </div>
                <h3>Hornos</h3>
                <p>Reparación de hornos eléctricos y a gas</p>
            </div>
            <div class="service-card" onclick="window.location.href='BuscarTecnicos.php?especialidad=Televisores'">
                <div class="service-img">
                    <img src="./imagenes/tv.jpg" alt="Imagen de lavadora" />
                </div>
                <h3>Televisores</h3>
                <p>Reparación de TV LED, LCD, OLED y Smart TV</p>
            </div>
            <div class="service-card" onclick="window.location.href='BuscarTecnicos.php?especialidad=Albañilería'">
                <div class="service-img">
                    <img src="./imagenes/construccion.jpg" alt="Imagen de lavadora" />
                </div>
                <h3>Albañilería</h3>
                <p>Servicios de construcción y remodelación</p>
            </div>
            <div class="service-card" onclick="window.location.href='BuscarTecnicos.php?especialidad=Electricidad'">
                <div class="service-img">
                    <img src="./imagenes/electricidad.jpg" alt="Imagen de lavadora" />
                </div>
                <h3>Electricidad</h3>
                <p>Instalaciones y reparaciones eléctricas</p>
            </div>
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
                    <h5>Historial de Citas</h5>
                    <p>Aquí va la información de citas...</p>
                </div>

                <div class="tab-pane fade p-3" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
                    <h5>Historial de Pagos</h5>
                    <p>Aquí va la información de pagos...</p>
                </div>
            </div>

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
</body>

</html>