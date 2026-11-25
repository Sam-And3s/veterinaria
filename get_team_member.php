<?php

header('Content-Type: application/json');

// Conexión a la base de datos
require_once 'conexion.php';

// Verificar si se envió el ID del miembro
if (isset($_GET['num_doc'])) {
    $num_doc = intval($_GET['num_doc']);

    // Consulta para obtener los datos del miembro
    $stmt = $conexion->prepare("SELECT * FROM equipo_vet WHERE num_doc = ?");
    $stmt->bind_param('i', $num_doc);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $member = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $member]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Miembro no encontrado']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID del miembro no proporcionado']);
}

$conexion->close();