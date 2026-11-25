<?php
require_once '../conexion.php';

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;

if (!$fecha) {
    echo '<p>No se ha especificado una fecha válida.</p>';
    exit;
}

$query = "
    SELECT DISTINCT
        c.id_citas,
        CONCAT(u.nom_usu, ' ', u.apell_usu) AS nombre_cliente,
        ts.nom_tip_serv AS tipo_servicio,
        hd.hora_ini AS hora_cita,
        u.num_usu  -- Agregado para seleccionar el número de teléfono
    FROM citas c
    JOIN usuario u ON c.id_usu = u.documento_usu
    JOIN tipos_servicios ts ON c.id_tip_serv = ts.id_tip_serv
    JOIN horario h ON c.id_horario = h.id_horario
    JOIN horario_dispo hd ON h.id_horario = hd.id_horario
    WHERE c.fecha = ? AND c.id_estado = 5 -- Agregamos esta condición
";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, 's', $fecha);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $hora = date("g:i A", strtotime($fila['hora_cita']));
        $numero = isset($fila['num_usu']) ? $fila['num_usu'] : ''; // Verificar si existe el número de teléfono

        echo '
        <div class="appointment-item">
            <div class="appointment-time">' . $hora . '</div>
            <div class="appointment-info">
                <div class="client-name">' . htmlspecialchars($fila['nombre_cliente']) . '</div>
                <div class="service-type">' . htmlspecialchars($fila['tipo_servicio']) . '</div>
            </div>
            <div class="appointment-actions">
                <button class="action-btn confirm" data-id-cita="' . $fila['id_citas'] . '" data-estado="3" title="Confirmar cita">
                    <i class="fas fa-check"></i>
                </button>
                <button class="action-btn cancel" data-id-cita="' . $fila['id_citas'] . '" data-estado="4" title="Cancelar cita">
                    <i class="fas fa-times"></i>
                </button>
                <button class="action-btn whatsapp" data-numero="' . $numero . '" title="Contactar por WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </button>
            </div>
        </div>';

    }
} else {
    echo '<p>No hay citas programadas para esta fecha con estado Pendiente.</p>';
}
?>