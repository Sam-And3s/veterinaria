<?php
header('Content-Type: application/json'); // Asegúrate de que esta línea esté presente
// obtener_pedidos.php

// Conexión a la base de datos
require_once 'conexion.php';

// Consulta para obtener los pedidos con la información del cliente y el estado
$consulta = "SELECT 
                p.id_pedido,
                p.fecha_pedido,
                u.nom_usu AS nombre_cliente,
                u.num_usu AS telefono_cliente,
                (SELECT SUM(dp.subtotal) FROM detalle_pedido dp WHERE dp.id_pedido = p.id_pedido) AS total,
                e.nom_estado AS estado
            FROM pedidos p
            INNER JOIN usuario u ON p.documento_usu = u.documento_usu
            INNER JOIN estado e ON p.id_estado = e.id_estado
            ORDER BY p.id_pedido DESC";

try {
    $stmt = $conexion->prepare($consulta);
    $stmt->execute();
    $result = $stmt->get_result(); // Obtener el resultado de la consulta

    $pedidos = [];
    while ($row = $result->fetch_assoc()) { // Usar fetch_assoc para obtener cada fila como un array asociativo
        $row['fecha_pedido'] = date('Y-m-d H:i:s', strtotime($row['fecha_pedido'])); // Formatear la fecha
        $pedidos[] = $row; // Agregar la fila al array de pedidos
    }

    echo json_encode($pedidos); // Devolver los pedidos como JSON
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al obtener los pedidos: ' . $e->getMessage()]);
    exit();
}
?>