<?php
// actualizar_estado_pedido.php

// Conexión a la base de datos
require_once 'conexion.php'; // Asegúrate de que este archivo contiene la configuración de conexión a la base de datos

// Verificar si se reciben los datos del pedido
if (isset($_POST['id_pedido']) && isset($_POST['estado'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['estado'];

    // Validar el estado (opcional, pero recomendado)
    $estados_validos = ['pendiente', 'completado', 'cancelado', 'confirmado']; // Asegúrate de que estos coincidan con tu base de datos
    if (!in_array($nuevo_estado, $estados_validos)) {
        echo json_encode(['success' => false, 'message' => 'Estado no válido']);
        exit();
    }

    // Actualizar el estado del pedido en la base de datos
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