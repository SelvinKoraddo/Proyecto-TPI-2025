<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechFix - Servicios Técnicos Profesionales</title>

    <script src="../Vistas/script/script.js" defer></script>
    <!-- Bootstrap CSS v5.3.8 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Vistas/css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="container">
            <div class="logo">
                <i class="bi bi-wrench"> TechFix</i>
            </div>
            <ul class="nav-links">
                <li><a href="#inic">Inicio</a></li>
                <li><a href="#serv">Servicios</a></li>
                <li><a href="#Cf">Cómo Funciona</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="#log" class="btn btn-outline">Iniciar Sesión</a>
                <a href="#log" class="btn btn-primary">Registrarse</a>
            </div>
        </nav>
    </header>


    <main class="container">
        <section class="hero">
            <h1 id="inic">Servicios Técnicos Profesionales</h1>
            <p>Conectamos técnicos especializados con clientes que necesitan reparaciones de electrodomésticos y servicios de construcción</p>
        </section>

        <section class="search-section">
            <h2 id="tperfect">Encuentra el Técnico Perfecto</h2>
            <form class="search-form">
                <div class="form-group">
                    <label for="service-type">Tipo de Servicio</label>
                    <select id="service-type">
                        <option value="">Selecciona un servicio</option>
                        <option value="refrigeradora">Reparación Refrigeradora</option>
                        <option value="lavadora">Reparación Lavadora</option>
                        <option value="horno">Reparación Horno</option>
                        <option value="television">Reparación Televisor</option>
                        <option value="albañil">Servicios de Albañilería</option>
                        <option value="electricista">Servicios Eléctricos</option>
                        <option value="plomero">Servicios de Plomería</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="location">Ubicación</label>
                    <input type="text" id="location" placeholder="Ciudad o código postal">
                </div>
                <button type="submit" class="btn btn-primary">Buscar Técnicos</button>
            </form>
        </section>
        <h1 id="serv">Servicios</h1>
        <section class="service-categories">

            <div class="service-card">
                <div class="service-img">
                    <img src="./imagenes/refri.jpg" alt="Imagen de refrigeradora" />
                </div>
                <h3>Refrigeradoras</h3>
                <p>Reparación y mantenimiento de refrigeradoras de todas las marcas</p>
            </div>
            <div class="service-card">
                <div class="service-img">
                <img src="./imagenes/lavadora.jpg" alt="Imagen de lavadora" />               
                </div>
                <h3>Lavadoras</h3>
                <p>Servicio especializado en lavadoras y secadoras</p>
            </div>
            <div class="service-card">
                <div class="service-img">
                <img src="./imagenes/horno.jpg" alt="Imagen de lavadora" />               
                </div>                
                <h3>Hornos</h3>
                <p>Reparación de hornos eléctricos y a gas</p>
            </div>
            <div class="service-card">
                <div class="service-img">
                <img src="./imagenes/tv.jpg" alt="Imagen de lavadora" />               
                </div>
                <h3>Televisores</h3>
                <p>Reparación de TV LED, LCD, OLED y Smart TV</p>
            </div>
            <div class="service-card">
                <div class="service-img">
                <img src="./imagenes/construccion.jpg" alt="Imagen de lavadora" />               
                </div>
                <h3>Albañilería</h3>
                <p>Servicios de construcción y remodelación</p>
            </div>
            <div class="service-card">
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
                    <p>Los técnicos se registran con su especialidad, tarifas y zona de trabajo. Perfil verificado por administrador.</p>
                </div>
                <div class="feature">
                    <h3><i class="bi bi-search"></i> Búsqueda Inteligente</h3>
                    <p>Los clientes buscan técnicos por tipo de reparación y ubicación usando nuestro formulario.</p>
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
            </div>
        </section>

        <section class="cta-section">
            <h2>¿Listo para comenzar?</h2>
            <p>Únete a nuestra plataforma y conecta con profesionales de confianza</p>
            <div class="cta-buttons" id="log">
                <a href="#" class="btn btn-primary">Soy Cliente</a>
                <a href="#" class="btn btn-outline">Soy Técnico</a>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>