<?php
header('Content-Type: application/json');
require_once('../conexion.php');

// Verifica si se envió el ID del tipo de servicio
if (isset($_GET['id_tip_serv'])) {
    $id_tip_serv = intval($_GET['id_tip_serv']);

    // Depuración: verifica el valor de id_tip_serv
    if ($id_tip_serv === 0) {
        echo json_encode(['success' => false, 'message' => 'ID del tipo de servicio no válido']);
        exit;
    }

    // Consulta para obtener los servicios del tipo especificado
    $query = "SELECT * FROM servicios WHERE id_tip_serv = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_tip_serv);
    $stmt->execute();
    $result = $stmt->get_result();

    $servicios = [];
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }

    echo json_encode(['success' => true, 'servicios' => $servicios]);
} else {
    echo json_encode(['success' => false, 'message' => 'ID del tipo de servicio no proporcionado']);
}
?>