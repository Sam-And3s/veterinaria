<?php
require_once 'conexion.php';

$id_pedido = $_GET['id_pedido'] ?? null;

if (!$id_pedido) {
    echo json_encode(['success' => false, 'message' => 'ID de pedido no proporcionado']);
    exit();
}

$query = "SELECT dp.id_product, dp.cantidad FROM detalle_pedido dp WHERE dp.id_pedido = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $id_pedido);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $updateQuery = "UPDATE productos SET stock = stock - ? WHERE id_product = ?";
    $updateStmt = $conexion->prepare($updateQuery);
    $updateStmt->bind_param('ii', $row['cantidad'], $row['id_product']);
    $updateStmt->execute();
}

echo json_encode(['success' => true, 'message' => 'Stock reducido correctamente']);
?>