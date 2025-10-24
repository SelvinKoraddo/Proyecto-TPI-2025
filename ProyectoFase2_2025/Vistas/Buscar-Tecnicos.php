<?php
session_start();
require_once "../Modelos/Conexion.php";
$conexion = new Conexion();

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $serviceType = trim($_POST['service_type'] ?? '');
    $location    = trim($_POST['location'] ?? '');

    if (empty($serviceType) || empty($location)) {
        echo "<p style='text-align:center; color:red;'>Por favor selecciona un tipo de servicio y ubicación</p>";
        exit;
    }

    // Convertimos a minúsculas y eliminamos acentos para búsqueda flexible
    function normalizar($str) {
        $str = mb_strtolower($str, 'UTF-8');
        $str = str_replace(
            ['á','é','í','ó','ú','ü','ñ'],
            ['a','e','i','o','u','u','n'],
            $str
        );
        return $str;
    }

    $serviceTypeNorm = normalizar($serviceType);
    $locationNorm    = normalizar($location);

    // Traemos todos los técnicos aprobados
    $stmt = $conexion->getConexion()->prepare("
        SELECT pt.id_tecnico, u.nombre_completo, pt.descripcion, pt.tarifa_hora, pt.zona_trabajo, e.nombre AS especialidad
        FROM perfil_tecnico pt
        INNER JOIN usuarios u ON pt.id_usuario = u.id_usuario
        INNER JOIN tecnico_especialidad te ON pt.id_tecnico = te.id_tecnico
        INNER JOIN especialidad e ON te.id_especialidad = e.id_especialidad
        WHERE pt.estado = 'aprobado'
    ");
    $stmt->execute();
    $tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultados = [];

    // Filtrar en PHP para coincidencia exacta por palabra, ignorando mayúsculas y acentos
    foreach ($tecnicos as $tec) {
        $espNorm = normalizar($tec['especialidad']);
        $zonaNorm = normalizar($tec['zona_trabajo']);

        if ($espNorm === $serviceTypeNorm && $zonaNorm === $locationNorm) {
            $resultados[] = $tec;
        }
    }

    echo "<div class='search-results'>";
    if ($resultados) {
        foreach ($resultados as $tec) {
            echo "<div class='tech-card'>
                    <div class='service-img'>
                        <img src='../Vistas/imagenes/tecnico.png' alt='Imagen Técnico'>
                    </div>
                    <h4>{$tec['nombre_completo']}</h4>
                    <span>Especialidad: {$tec['especialidad']}</span>
                    <span>Zona: {$tec['zona_trabajo']}</span>
                    <span>Tarifa: \${$tec['tarifa_hora']}/hora</span>
                    <div style='margin-top:10px; display:flex; gap:10px; justify-content:center;'>
                        <a href='ReservarCita.php?id={$tec['id_tecnico']}' class='btn btn-primary'>Reservar Cita</a>
                        <a href='contactar.php?id_tecnico={$tec['id_tecnico']}' class='btn btn-secondary'>Contactar</a>
                    </div>
                  </div>";
        }
    } else {
        echo "<p style='text-align:center; color:red;'>No se encontraron técnicos disponibles.</p>";
    }
    echo "</div>";
}
?>
