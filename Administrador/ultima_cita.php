<?php
// Incluye el archivo de conexión a la base de datos
require_once('../conexion.php');

// Consulta para obtener la última cita registrada (ordenando por id_citas)
$query = "SELECT
    c.id_citas,
    c.fecha AS fecha_cita,
    CASE
        WHEN TIME(c.fecha) IS NOT NULL THEN TIME(c.fecha)
        ELSE (SELECT hd.hora_ini FROM horario_dispo hd WHERE hd.id_horario = c.id_horario)
    END AS hora_cita,
    s.nom_serv AS nom_servicio,
    u.nom_usu AS nom_mascota,
    du.nom_usu AS nom_cliente,
    du.apell_usu AS apell_cliente,
    es.nom_estado AS estado_cita
FROM citas c
INNER JOIN servicios s ON c.id_tip_serv = s.id_tip_serv
INNER JOIN usuario u ON c.id_usu = u.documento_usu
INNER JOIN usuario du ON u.id_rol = du.id_rol -- Asumiendo relación por rol para el dueño (revisar si es correcto)
INNER JOIN estado es ON c.id_estado = es.id_estado
ORDER BY c.id_citas DESC
LIMIT 1";

$resultado = mysqli_query($conexion, $query);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $cita = mysqli_fetch_assoc($resultado);
    $fecha_cita = date('d/m/Y', strtotime($cita['fecha_cita']));
    $hora_cita = date('h:i A', strtotime($cita['hora_cita']));
    $estado_clase = '';
    switch ($cita['estado_cita']) {
        case 'Pendiente':
            $estado_clase = 'pending';
            break;
        case 'Confirmada':
            $estado_clase = 'confirmed';
            break;
        case 'Cancelada':
            $estado_clase = 'cancelled';
            break;
        case 'Completada':
            $estado_clase = 'completed';
            break;
        default:
            $estado_clase = strtolower(str_replace(' ', '-', $cita['estado_cita'])); //Fallback
            break;
    }

    echo '<div class="dashboard-card appointments-section">';
    echo '    <div class="card-header">';
    echo '        <h2>Última Cita Registrada</h2>';
    echo '    </div>';
    echo '    <div class="appointments-list">';
    echo '        <div class="appointment-item">';
    echo '            <div class="time-badge">' . $hora_cita . '</div>';
    echo '            <div class="appointment-details">';
    echo '                <h4>' . $cita['nom_servicio'] . '</h4>';
    echo '                <p>' . $cita['nom_mascota'] . ' - ' . $cita['nom_cliente'] . ' ' . $cita['apell_cliente'] . '</p>';
    echo '                <span class="status ' . $estado_clase . '">' . $cita['estado_cita'] . '</span>';
    echo '            </div>';
    echo '        </div>';
    echo '    </div>';
    echo '</div>';

} else {
    echo '<div class="dashboard-card appointments-section">';
    echo '    <div class="card-header">';
    echo '        <h2>Última Cita Registrada</h2>';
    echo '    </div>';
    echo '    <div class="appointments-list">';
    echo '        <p>No se encontraron citas recientes.</p>';
    echo '    </div>';
    echo '</div>';
}

?>