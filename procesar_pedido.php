<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

require_once 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

// Depuración: Verificar los datos recibidos
file_put_contents('debug.log', print_r($data, true), FILE_APPEND);

// Ajustar para aceptar la estructura actual de los datos enviados
if (isset($data['cliente']) && isset($data['productos']) && is_array($data['productos']) && isset($data['total'])) {
    $userId = $data['cliente']['id']; // Obtener el ID del cliente
    $items = $data['productos']; // Obtener los productos
    $total = $data['total']; // Obtener el total

    file_put_contents('debug.log', "userId recibido: $userId\n", FILE_APPEND); // Depuración

    try {
        // Iniciar una transacción
        $conexion->begin_transaction();

        // Insertar el pedido en la tabla 'pedidos'
        $estadoId = 5; // Estado pendiente
        $queryPedido = "INSERT INTO pedidos (documento_usu, id_estado, total) VALUES (?, ?, ?)";
        $stmtPedido = $conexion->prepare($queryPedido);
        $stmtPedido->bind_param('sid', $userId, $estadoId, $total);
        $stmtPedido->execute();
        $orderId = $stmtPedido->insert_id;

        // Insertar los detalles del pedido en la tabla 'detalle_pedido'
        $queryDetalle = "INSERT INTO detalle_pedido (id_pedido, id_product, cantidad, subtotal) VALUES (?, ?, ?, ?)";
        $stmtDetalle = $conexion->prepare($queryDetalle);

        foreach ($items as $item) {
            $productoId = $item['productId'];
            $cantidad = $item['quantity'];
            $subtotal = $item['subtotal']; // Asegúrate de que este campo exista y tenga un valor válido

            if (is_null($subtotal)) {
                file_put_contents('debug.log', "Subtotal es nulo para el producto ID: $productoId\n", FILE_APPEND);
                echo json_encode(['success' => false, 'message' => 'Error: Subtotal no puede ser nulo.']);
                exit;
            }

            $stmtDetalle->bind_param('iiid', $orderId, $productoId, $cantidad, $subtotal);
            $stmtDetalle->execute();
        }

        // Confirmar la transacción
        $conexion->commit();

        echo json_encode(['success' => true, 'orderId' => $orderId]);
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollback();
        file_put_contents('debug.log', $e->getMessage(), FILE_APPEND); // Depuración: Registrar errores
        echo json_encode(['success' => false, 'message' => 'Error al procesar el pedido: ' . $e->getMessage()]);
    }
} else {
    file_put_contents('debug.log', "Datos incompletos o incorrectos\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Error: Datos del pedido incompletos o incorrectos.']);
}
?>