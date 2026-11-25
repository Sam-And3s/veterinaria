<?php
require_once 'conexion.php';

$id_pedido = $_GET['id'] ?? null;

if (!$id_pedido) {
    echo json_encode(['success' => false, 'message' => 'ID de pedido no proporcionado']);
    exit;
}

$query = "SELECT 
            dp.id_product AS id_producto,
            p.nom_product AS nombre,
            dp.cantidad,
            p.prec_product AS precio,
            dp.subtotal
          FROM detalle_pedido dp
          INNER JOIN productos p ON dp.id_product = p.id_product
          WHERE dp.id_pedido = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $id_pedido);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

if (empty($productos)) {
    echo json_encode(['success' => false, 'message' => 'No se encontraron productos para este pedido']);
} else {
    echo json_encode(['success' => true, 'productos' => $productos]);
}

if (isset($_POST['id_pedido']) && isset($_POST['estado'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['estado'];

    $consulta = "UPDATE pedidos SET id_estado = (SELECT id_estado FROM estado WHERE nom_estado = ?) WHERE id_pedido = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param('si', $nuevo_estado, $id_pedido);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Estado del pedido actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado del pedido']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos del pedido']);
}
?>