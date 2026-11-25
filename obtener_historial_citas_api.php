<?php
// obtener_historial_citas_api.php

// Conexión a la base de datos
require_once 'conexion.php';

// Establecer el encabezado para JSON
header('Content-Type: application/json');

// Verificar la acción
if (isset($_GET['action']) && $_GET['action'] === 'obtenerHistorialCitas') {
    // Consulta para obtener el historial de citas
    $query = "SELECT 
                c.id_citas,
                c.fecha,
                u.nom_usu AS nombre_cliente,
                (SELECT nom_usu FROM usuario WHERE documento_usu = c.id_usu) AS nombre_mascota,
                ts.nom_tip_serv AS servicio,
                e.nom_estado AS estado
              FROM citas c
              JOIN usuario u ON c.id_usu = u.documento_usu
              JOIN tipos_servicios ts ON c.id_tip_serv = ts.id_tip_serv  -- Corrección: join con tipos_servicios
              JOIN estado e ON c.id_estado = e.id_estado
              WHERE c.id_estado IN (3, 4)
              ORDER BY c.fecha DESC";

    // echo "Consulta SQL: " . $query . "<br>"; // Comentado para no interferir con la respuesta JSON

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // echo "Número de resultados: " . count($citas) . "<br>"; // Comentado para no interferir con la respuesta JSON

        // Formatear la fecha
        foreach ($citas as &$cita) {
            $cita['fecha'] = date('Y-m-d H:i:s', strtotime($cita['fecha']));
        }

        echo json_encode(['success' => true, 'citas' => $citas]);
    } catch (PDOException $e) {
        // Enviar error JSON
        echo json_encode(['success' => false, 'message' => "Error al obtener el historial de citas: " . $e->getMessage()]);
        exit; // Importante: detener la ejecución después de enviar el error
    }
} else {
    // Enviar error JSON
    echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
    exit; // Importante: detener la ejecución después de enviar el error
}
?>
