<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<?php if (isset($_GET['exito'])): ?>
<div class="alert alert-success text-center">Técnico registrado correctamente.</div>
<?php elseif (isset($_GET['error'])): ?>
<div class="alert alert-danger text-center">Ocurrió un error al registrar el técnico.</div>
<?php endif; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Técnico - TechFix</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
        <section class="hero text-center">
            <h1>Registro de Técnicos</h1>
            <p class="lead">Completa el formulario para unirte a TechFix y ofrecer tus servicios profesionales</p>
        </section>

        <section class="register-section d-flex justify-content-center align-items-center mt-4">
            <div class="card p-4 shadow-lg" style="max-width: 600px; width: 100%; border-radius: 15px;">
            <form action="../Controladores/tecnicoControlador.php" method="POST" class="card p-4 shadow-lg" style="max-width: 600px;">
                     <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" required>
                    </div>

                    <div class="mb-3">
                    <label class="form-label">Contraseña</label>        
                    <input type="password" name="contrasena" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tarifa por hora ($)</label>
                    <input type="number" name="tarifa" class="form-control" step="0.01" required>    
                </div>
    
                <div class="mb-3">        
                    <label class="form-label">Zona de trabajo</label>        
                    <input type="text" name="zona" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Especialidades</label>
                    <div class="border rounded p-3" style="background-color: #f8f9fa;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="1" id="esp1">
                            <label class="form-check-label" for="esp1">
                                Refrigeradoras
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="2" id="esp2">
                            <label class="form-check-label" for="esp2">
                                Lavadoras
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="3" id="esp3">
                            <label class="form-check-label" for="esp3">
                                Hornos
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="4" id="esp4">
                            <label class="form-check-label" for="esp4">
                                Televisores
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="5" id="esp5">
                            <label class="form-check-label" for="esp5">
                                Albañilería
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="6" id="esp6">
                            <label class="form-check-label" for="esp6">
                                Electricidad
                            </label>
                        </div>
                    </div>
                    <small class="text-muted">Selecciona todas las que apliquen</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Registrar Técnico</button>
            </form>    
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
