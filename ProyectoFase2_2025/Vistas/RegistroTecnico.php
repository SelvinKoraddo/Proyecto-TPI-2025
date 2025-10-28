<?php
session_start();
require_once '../Modelos/Conexion.php';
$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $especialidades = $_POST['especialidades'] ?? [];
    $tarifa = $_POST['tarifa'] ?? 0.00;
    $zona_trabajo = $_POST['zona_trabajo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    // Validar que se haya seleccionado al menos una especialidad
    if (empty($especialidades)) {
        $mensaje = "Debes seleccionar al menos una especialidad";
        $tipoMensaje = "danger";
    } else {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);

        try {
            $db = (new Conexion())->getConexion();
            $db->beginTransaction();

            // Insertar en tabla usuarios
            $stmt = $db->prepare("INSERT INTO usuarios (nombre_completo, contrasena_hash, rol, telefono, correo, fecha_creado)
                                  VALUES (:nombre, :hash, 'tecnico', :telefono, :correo, NOW())");
            $stmt->execute([
                'nombre' => $nombre,
                'hash' => $hash,
                'telefono' => $telefono,
                'correo' => $correo
            ]);
            $id_usuario = $db->lastInsertId();   

            // Insertar en perfil_tecnico
            $stmt2 = $db->prepare("INSERT INTO perfil_tecnico (id_usuario, descripcion, tarifa_hora, zona_trabajo, estado, fecha_aprobado)
                                   VALUES (:id_usuario, :descripcion, :tarifa, :zona, 'pendiente', NULL)");
            $stmt2->execute([
                'id_usuario' => $id_usuario,
                'descripcion' => $descripcion,
                'tarifa' => $tarifa,
                'zona' => $zona_trabajo
            ]);
            $id_tecnico = $db->lastInsertId();

            $stmt3 = $db->prepare("INSERT INTO tecnico_especialidad (id_tecnico, id_especialidad)
                                   VALUES (:id_tecnico, :id_especialidad)");
            
            foreach ($especialidades as $id_especialidad) {
                $stmt3->execute([
                    'id_tecnico' => $id_tecnico,
                    'id_especialidad' => $id_especialidad
                ]);
            }

            $db->commit();
            $mensaje = "Registro exitoso. Tu perfil será revisado por un administrador.";
            $tipoMensaje = "success";
            
            $_POST = [];
            
        } catch (Exception $e) {
            $db->rollBack();
            $mensaje = "Error al registrar: " . $e->getMessage();
            $tipoMensaje = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
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
    
    <main class="container my-5">
        <section class="hero text-center">
            <h1>Registro de Técnicos</h1>
            <p class="lead">Completa el formulario para unirte a TechFix y ofrecer tus servicios profesionales</p>
        </section>

        <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipoMensaje ?> text-center mx-auto" style="max-width: 600px;">
            <?= htmlspecialchars($mensaje) ?>
        </div>
        <?php endif; ?>

        <section class="register-section d-flex justify-content-center align-items-center mt-4">
            <div class="card p-4 shadow-lg" style="max-width: 600px; width: 100%; border-radius: 15px;">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" 
                               value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" 
                               value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" 
                               value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>        
                        <input type="password" name="contrasena" class="form-control" required>
                        <small class="text-muted">Mínimo 6 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                        <small class="text-muted">Cuéntanos sobre tu experiencia y habilidades</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Especialidades</label>
                        <div class="border rounded p-3" style="background-color: #f8f9fa;">
                            <?php
                            try {
                                $db = (new Conexion())->getConexion();
                                $especialidades = $db->query("SELECT id_especialidad, nombre FROM especialidad ORDER BY nombre");
                                $contador = 0;
                                foreach ($especialidades as $esp) {
                                    $contador++;
                                    $checked = isset($_POST['especialidades']) && in_array($esp['id_especialidad'], $_POST['especialidades']) ? 'checked' : '';
                                    echo "
                                    <div class='form-check'>
                                        <input class='form-check-input' type='checkbox' name='especialidades[]' 
                                               value='{$esp['id_especialidad']}' id='esp{$contador}' {$checked}>
                                        <label class='form-check-label' for='esp{$contador}'>
                                            {$esp['nombre']}
                                        </label>
                                    </div>";
                                }
                            } catch (Exception $e) {
                                echo "<div class='alert alert-warning'>Error al cargar especialidades</div>";
                            }
                            ?>
                        </div>
                        <small class="text-muted">Selecciona todas las que apliquen</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tarifa por hora ($)</label>
                        <input type="number" name="tarifa" class="form-control" step="0.01" min="0" 
                               value="<?= htmlspecialchars($_POST['tarifa'] ?? '') ?>" required>    
                    </div>
        
                    <div class="mb-3">        
                        <label class="form-label">Zona de trabajo</label>        
                        <select name="zona_trabajo" class="form-select" required>
                            <option value="">Seleccione un departamento</option>
                            <?php
                            $departamentos = [
                                'Ahuachapán', 'Sonsonate', 'Santa Ana', 'La Libertad', 
                                'Chalatenango', 'San Salvador', 'Cuscatlán', 'La Paz',
                                'San Vicente', 'Cabañas', 'Usulután', 'San Miguel', 
                                'Morazán', 'La Unión'
                            ];
                            $zonaSeleccionada = $_POST['zona_trabajo'] ?? '';
                            foreach ($departamentos as $depto) {
                                $selected = ($zonaSeleccionada === $depto) ? 'selected' : '';
                                echo "<option value='$depto' $selected>$depto</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-plus-fill me-2"></i>Registrar Técnico
                    </button>
                </form>

                <hr class="my-4">
                <p class="text-center mb-0">
                    ¿Ya tienes cuenta? <a href="Login.php">Inicia sesión aquí</a>
                </p>
            </div>
        </section>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>