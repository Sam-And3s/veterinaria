<?php
date_default_timezone_set('America/Bogota'); // O tu zona real
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');

// Conexión a la base de datos
require_once 'conexion.php';

// Verificar el parámetro de acción
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'getAppointments') {
    // Obtener citas de un día específico
    $date = $_GET['date'] ?? null;

    if (!$date) {
        echo json_encode(['success' => false, 'message' => 'Fecha no proporcionada']);
        exit;
    }

    // Consulta para obtener las citas del día
    $query = "SELECT 
                c.id_citas, 
                c.descripcion, 
                c.fecha, 
                c.id_estado, 
                e.nom_estado, 
                t.nom_tam, 
                ts.nom_tip_serv, 
                u.nom_usu, 
                u.apell_usu,
                u.num_usu, 
                h.hora_ini, 
                h.hora_fin
              FROM citas c
              INNER JOIN estado e ON c.id_estado = e.id_estado
              INNER JOIN tamaño t ON c.id_tamaño = t.id_tamaño
              INNER JOIN tipos_servicios ts ON c.id_tip_serv = ts.id_tip_serv
              INNER JOIN usuario u ON c.id_usu = u.documento_usu
              INNER JOIN horario_dispo h ON c.id_horario = h.id_horario
              WHERE DATE(c.fecha) = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $row['show_buttons'] = $row['id_estado'] != 3; // Solo mostrar botones si el estado no es confirmado
        $appointments[] = $row;
    }

    if (empty($appointments)) {
        echo json_encode(['success' => false, 'message' => 'No hay citas para esta fecha']);
    } else {
        echo json_encode(['success' => true, 'appointments' => $appointments]);
    }
    exit;
} 

if ($action === 'cancelAppointment') {
   $input = json_decode(file_get_contents('php://input'), true);
    $id_cita = $input['id_cita'] ?? $_GET['id_cita'] ?? null;

    if (!$id_cita) {
        echo json_encode(['success' => false, 'message' => 'ID de cita no proporcionado']);
        exit;
    }

    // Actualizar el estado de la cita a 4 (cancelado)
    $query = "UPDATE citas SET id_estado = 4 WHERE id_citas = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_cita);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cita cancelada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al cancelar la cita']);
    }
    exit;
}

if ($action === 'addAppointment') {
    // Decodificar los datos enviados en formato JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Capturar los valores enviados desde el frontend
    $date = $input['date'] ?? null;
    $id_horario = $input['id_horario'] ?? null;
    $service = $input['service'] ?? null;
    $size = $input['size'] ?? null;
    $description = $input['description'] ?? null;
    $user_id = $input['user_id'] ?? null;

    if (!$date || !$id_horario || !$service || !$size || !$user_id) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos para crear la cita']);
        exit;
    }

    // Establece el estado de la cita en 5 (pendiente)
    $id_estado = 5;

    $query = "INSERT INTO citas (fecha, id_horario, id_tip_serv, id_tamaño, descripcion, id_usu, id_estado) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('siissii', $date, $id_horario, $service, $size, $description, $user_id, $id_estado);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cita creada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear la cita']);
    }
    exit;
}

if ($action === 'markUnavailable') {
    $data = json_decode(file_get_contents('php://input'), true);
    $date = $data['date'] ?? null;

    if (!$date) {
        echo json_encode(['error' => 'Fecha no proporcionada']);
        exit;
    }

    $stmt = $conexion->prepare("UPDATE dispo_dia SET sin_servicio = 1 WHERE fech_dia = ?");
    $stmt->bind_param('s', $date);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Día marcado como sin servicio']);
    } else {
        echo json_encode(['error' => 'No se pudo marcar el día como sin servicio']);
    }
}
if ($action === 'updateAvailability') {
    $data = json_decode(file_get_contents('php://input'), true);

    $date = $data['date'] ?? null;
    $totalSlots = $data['totalSlots'] ?? null;
    $noService = $data['noService'] ?? 0;
    $timeSlots = $data['timeSlots'] ?? [];

    // Validar datos
    if (!$date || !$totalSlots || empty($timeSlots)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }

    // Determinar el estado del día
    $dayStatus = $noService ? 7 : ($totalSlots > 0 ? 6 : 8);

    // Verificar si el día ya existe en dispo_dia
    $query = "SELECT id_dia FROM dispo_dia WHERE fech_dia = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El día ya existe, actualiza los cupos totales y el estado de servicio
        $row = $result->fetch_assoc();
        $id_dia = $row['id_dia'];

        $updateQuery = "UPDATE dispo_dia SET cup_tot = ?, id_estado = ? WHERE id_dia = ?";
        $updateStmt = $conexion->prepare($updateQuery);
        $updateStmt->bind_param('iii', $totalSlots, $dayStatus, $id_dia);
        $updateStmt->execute();
    } else {
        // El día no existe, insértalo
        $insertQuery = "INSERT INTO dispo_dia (fech_dia, cup_tot, id_estado) VALUES (?, ?, ?)";
        $insertStmt = $conexion->prepare($insertQuery);
        $insertStmt->bind_param('sii', $date, $totalSlots, $dayStatus);
        $insertStmt->execute();
        $id_dia = $conexion->insert_id;
    }

    // Agregar nuevos horarios
    foreach ($timeSlots as $slot) {
        $startTime = $slot['startTime'];
        $endTime = $slot['endTime'];

        // Verificar si el horario ya existe
        $checkQuery = "SELECT id_horario FROM horario_dispo WHERE hora_ini = ? AND hora_fin = ? AND cup_dispo = ?";
        $checkStmt = $conexion->prepare($checkQuery);
        $checkStmt->bind_param('ssi', $startTime, $endTime, $id_dia);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows === 0) {
            // Insertar nuevo horario
            $insertSlotQuery = "INSERT INTO horario_dispo (hora_ini, hora_fin, cup_dispo) VALUES (?, ?, ?)";
            $insertSlotStmt = $conexion->prepare($insertSlotQuery);
            $insertSlotStmt->bind_param('ssi', $startTime, $endTime, $id_dia);
            $insertSlotStmt->execute();
        }
    }

    echo json_encode(['success' => true, 'message' => 'Disponibilidad actualizada correctamente']);
    exit;
}

if ($action === 'deleteSlot') {
    $slotId = $_POST['slotId'] ?? null;

    if (!$slotId) {
        echo json_encode(['success' => false, 'message' => 'ID del cupo no proporcionado']);
        exit;
    }

    // Obtén el ID del día asociado al cupo
    $stmt = $conexion->prepare("SELECT cup_dispo FROM horario_dispo WHERE id_horario = ?");
    $stmt->bind_param('i', $slotId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Cupo no encontrado']);
        exit;
    }

    $dayId = $row['cup_dispo'];

    // Elimina el cupo de la tabla horario_dispo
    $stmt = $conexion->prepare("DELETE FROM horario_dispo WHERE id_horario = ?");
    $stmt->bind_param('i', $slotId);

    if ($stmt->execute()) {
        // Actualiza el valor de cup_tot en la tabla dispo_dia
        $stmt = $conexion->prepare("UPDATE dispo_dia SET cup_tot = cup_tot - 1 WHERE id_dia = ?");
        $stmt->bind_param('i', $dayId);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Cupo eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el cupo']);
    }

    $stmt->close();
    exit;
}

if ($action === 'getAvailableHours') {
    $fecha = $_GET['fecha'] ?? null;

    if (!$fecha) {
        echo json_encode(['success' => false, 'message' => 'Fecha no proporcionada']);
        exit;
    }

    $query = "
        SELECT hd.id_horario, hd.hora_ini, hd.hora_fin
        FROM horario_dispo hd
        INNER JOIN dispo_dia dd ON hd.cup_dispo = dd.id_dia
        LEFT JOIN citas c ON hd.id_horario = c.id_horario AND DATE(c.fecha) = ?
        WHERE dd.fech_dia = ? AND c.id_horario IS NULL
    ";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ss', $fecha, $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    $horarios = [];
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row;
    }

    if (empty($horarios)) {
        echo json_encode(['success' => false, 'message' => 'No hay horarios disponibles']);
    } else {
        echo json_encode(['success' => true, 'horarios' => $horarios]);
    }
    exit;
}elseif ($action === 'checkNoService') { // Nueva acción para verificar "sin servicio"
    $fecha = $_GET['fecha'] ?? null;

    if (!$fecha) {
        echo json_encode(['noService' => false]);
        exit;
    }

    $query = "SELECT id_estado FROM dispo_dia WHERE fech_dia = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('s', $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['noService' => $row['id_estado'] == 7]); // Verifica si el estado es "Sin Servicio"
    } else {
        echo json_encode(['noService' => false]); // Si no se encuentra el día, se asume que hay servicio
    }
    exit;

} elseif ($action === 'getAvailableServices') {
    $query = "SELECT id_tip_serv, nom_tip_serv FROM tipos_servicios";
    $result = $conexion->query($query);

    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    if (!empty($services)) {
        echo json_encode(['success' => true, 'servicios' => $services]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No hay servicios disponibles']);
    }
    exit;

}elseif ($action === 'manageNoServiceDays') {
    $data = json_decode(file_get_contents('php://input'), true);
    $startDate = $data['startDate'] ?? null;
    $endDate = $data['endDate'] ?? null;
    $actionType = $data['action'] ?? null;

    if ($actionType && $startDate && $endDate) {
        if ($actionType === 'mark') {
            // Lógica para marcar el rango de fechas como sin servicio
            $query = "INSERT INTO dispo_dia (fech_dia, id_estado) VALUES (?, 7)
                      ON DUPLICATE KEY UPDATE id_estado = 7";
            $stmt = $conexion->prepare($query);
            $currentDate = new DateTime($startDate);
            $endDateObj = new DateTime($endDate);
            $endDateObj->modify('+1 day'); // Incluir la fecha de fin

            $success = true;
            while ($currentDate < $endDateObj) {
                $fecha = $currentDate->format('Y-m-d');
                $stmt->bind_param("s", $fecha);
                if (!$stmt->execute()) {
                    $success = false;
                    break;
                }
                $currentDate->modify('+1 day');
            }
            $stmt->close();

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al marcar los días sin servicio.']);
            }

        } elseif ($actionType === 'unmark') {
            // Lógica para eliminar el rango de fechas de los días sin servicio
            $query = "UPDATE dispo_dia SET id_estado = 6 WHERE fech_dia >= ? AND fech_dia <= ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al reactivar los días.']);
            }
            $stmt->close();

        } else {
            echo json_encode(['error' => 'Acción no válida']);
        }
    } else {
        echo json_encode(['error' => 'Parámetros incompletos']);
    }
    exit;
}
elseif ($action === 'getAvailableSizes') { // Implementación para obtener tamaños
    $query = "SELECT id_tamaño, nom_tam FROM tamaño";
    $result = $conexion->query($query);

    $sizes = [];
    while ($row = $result->fetch_assoc()) {
        $sizes[] = $row;
    }

    echo json_encode(['success' => true, 'sizes' => $sizes]);
    exit;
} elseif ($action === 'getSlots') {
    $date = $_GET['date'] ?? null;

    if (!$date) {
        echo json_encode(['success' => false, 'message' => 'Fecha no proporcionada']);
        exit;
    }

    // Obtiene los horarios existentes para la fecha
    $stmt = $conexion->prepare("SELECT id_horario, hora_ini, hora_fin FROM horario_dispo hd
                            INNER JOIN dispo_dia dd ON hd.cup_dispo = dd.id_dia
                            WHERE dd.fech_dia = ?");
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $slots = [];
    while ($row = $result->fetch_assoc()) {
        $slots[] = $row;
    }

    // Obtiene la cantidad total de cupos para la fecha
    $stmt = $conexion->prepare("SELECT cup_tot FROM dispo_dia WHERE fech_dia = ?");
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $day = $result->fetch_assoc();

    if (!$day) {
        echo json_encode(['success' => true, 'slots' => [], 'totalSlots' => 0]);
        exit;
    }

    $totalSlots = $day['cup_tot'] ?? 0; // Si no hay registros, devuelve 0

    echo json_encode(['success' => true, 'slots' => $slots, 'totalSlots' => $totalSlots]);
    exit;
} elseif ($action === 'getSlotsForDate') {
    $fecha = $_GET['date'] ?? null;

    if (!$fecha) {
        echo json_encode(['success' => false, 'message' => 'Fecha no proporcionada']);
        exit;
    }

    // Consulta para obtener los horarios disponibles que no están ocupados
    $query = "SELECT hd.id_horario, hd.hora_ini, hd.hora_fin
              FROM horario_dispo hd
              INNER JOIN dispo_dia dd ON hd.cup_dispo = dd.id_dia
              LEFT JOIN citas c ON hd.id_horario = c.id_horario AND DATE(c.fecha) = ?
              WHERE dd.fech_dia = ? AND c.id_horario IS NULL";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ss', $fecha, $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    $slots = [];
    while ($row = $result->fetch_assoc()) {
        $slots[] = $row;
    }

    if (empty($slots)) {
        echo json_encode(['success' => false, 'message' => 'No hay horarios disponibles para esta fecha']);
    } else {
        echo json_encode(['success' => true, 'slots' => $slots]);
    }
    exit;
} elseif ($action === 'getDaySummary') {
    $stmt = $conexion->prepare("
        SELECT 
            dd.fech_dia AS date,
            dd.cup_tot AS assigned_slots,
            COUNT(c.id_citas) AS daily_appointments
        FROM dispo_dia dd
        LEFT JOIN horario_dispo hd ON dd.id_dia = hd.cup_dispo
        LEFT JOIN citas c ON hd.id_horario = c.id_horario
        GROUP BY dd.fech_dia
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $summary = [];
    while ($row = $result->fetch_assoc()) {
        $summary[] = [
            'date' => $row['date'],
            'assigned_slots' => $row['assigned_slots'],
            'daily_appointments' => $row['daily_appointments']
        ];
    }

    echo json_encode(['success' => true, 'summary' => $summary]);
    exit;
} else if ($action === 'getAppointmentDetails') {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID de cita no proporcionado']);
        exit;
    }

    $query = "SELECT 
                hd.hora_ini, 
                hd.hora_fin, 
                u.nom_usu, 
                u.apell_usu, 
                u.num_usu, 
                c.descripcion, 
                ts.nom_tip_serv, 
                t.nom_tam
              FROM citas c
              JOIN horario_dispo hd ON c.id_horario = hd.id_horario
              JOIN usuario u ON c.id_usu = u.documento_usu
              JOIN tipos_servicios ts ON c.id_tip_serv = ts.id_tip_serv
              JOIN tamaño t ON c.id_tamaño = t.id_tamaño
              WHERE c.id_citas = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $appointment = $result->fetch_assoc();
            echo json_encode(['success' => true, 'appointment' => $appointment]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cita no encontrada']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta']);
    }
    exit;
} else if ($action === 'confirmAppointment') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id_cita = $input['id_cita'] ?? $_GET['id_cita'] ?? null;

    if (!$id_cita) {
        echo json_encode(['success' => false, 'message' => 'ID de cita no proporcionado']);
        exit;
    }

    // Actualizar el estado de la cita a 3 (confirmado)
    $query = "UPDATE citas SET id_estado = 3 WHERE id_citas = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_cita);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cita confirmada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al confirmar la cita']);
    }
    exit;
} else if ($action === 'getDayStatus') {
    $fecha = $_GET['fecha'] ?? null;

    if (!$fecha) {
        echo json_encode(['success' => false, 'message' => 'Fecha no proporcionada']);
        exit;
    }

    // Consulta para obtener el estado del día
    $query = "
        SELECT 
            dd.id_dia,
            dd.id_estado AS day_status,
            dd.cup_tot AS total_slots,
            COUNT(c.id_citas) AS occupied_slots,
            (dd.cup_tot - COUNT(c.id_citas)) AS available_slots
        FROM dispo_dia dd
        LEFT JOIN horario_dispo hd ON dd.id_dia = hd.cup_dispo
        LEFT JOIN citas c ON hd.id_horario = c.id_horario AND DATE(c.fecha) = ?
        WHERE dd.fech_dia = ?
        GROUP BY dd.id_dia;
    ";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ss', $fecha, $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $id_dia = $row['id_dia'];
        $status = $row['day_status']; // Estado actual del día
        $availableSlots = $row['available_slots'];

        // Actualizar el estado del día según los cupos disponibles
        if ($status == 6 && $availableSlots <= 0) {
            $status = 8; // Cambiar a "Sin Cupos" si no hay cupos disponibles
        } elseif ($status == 8 && $availableSlots > 0) {
            $status = 6; // Cambiar a "Disponible" si hay cupos disponibles nuevamente
        }

        // Actualizar el estado en la base de datos si ha cambiado
        $updateQuery = "UPDATE dispo_dia SET id_estado = ? WHERE id_dia = ?";
        $updateStmt = $conexion->prepare($updateQuery);
        $updateStmt->bind_param('ii', $status, $id_dia);
        $updateStmt->execute();

        echo json_encode([
            'success' => true,
            'status' => $status,
            'total_slots' => $row['total_slots'],
            'occupied_slots' => $row['occupied_slots'],
            'available_slots' => $availableSlots
        ]);
    } else {
        echo json_encode(['success' => true, 'status' => 7]); // Día sin servicio por defecto
    }
    exit;
} else if ($action === 'checkDayStatus') {
    $fecha = $_GET['fecha'] ?? null;

    if (!$fecha) {
        echo json_encode(['success' => false, 'message' => 'Fecha no proporcionada']);
        exit;
    }

    $query = "SELECT id_estado FROM dispo_dia WHERE fech_dia = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('s', $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'status' => $row['id_estado']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Día no encontrado']);
    }
    exit;
} else if ($action === 'confirmOrder') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id_pedido = $input['id_pedido'] ?? null;

    if (!$id_pedido) {
        echo json_encode(['success' => false, 'message' => 'ID de pedido no proporcionado']);
        exit;
    }

    $query = "UPDATE pedidos SET id_estado = (SELECT id_estado FROM estado WHERE nom_estado = 'confirmado') WHERE id_pedido = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_pedido);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pedido confirmado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al confirmar el pedido']);
    }
    exit;
}

$conexion->close();

?>