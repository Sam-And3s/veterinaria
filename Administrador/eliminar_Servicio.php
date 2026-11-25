<?php
header('Content-Type: application/json');
require_once('../conexion.php');

// Verifica si el ID está presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID no válido.']);
    exit;
}

$id = intval($_GET['id']); // Obtiene el ID del servicio

$query = "DELETE FROM servicios WHERE id_serv = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el servicio.']);
}

$stmt->close();
$conexion->close();
?>