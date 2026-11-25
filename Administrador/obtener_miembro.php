<?php
require_once '../conexion.php';

// Habilita la visualización de errores temporalmente
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON

if (!$conexion) {
    die(json_encode(['error' => 'Error al conectar con la base de datos: ' . mysqli_connect_error()]));
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    error_log("ID recibido: " . $id); // Depuración

    $query = "SELECT e.*, t.nom_tipo_doc 
              FROM equipo_vet e 
              JOIN tipo_doc_eqp t ON e.id_tipo_doc = t.id_tipo_doc 
              WHERE e.num_doc = ?";

    $stmt = mysqli_prepare($conexion, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $miembro = mysqli_fetch_assoc($result);
            echo json_encode($miembro);
        } else {
            echo json_encode(['error' => 'Miembro no encontrado']);
        }

        mysqli_stmt_close($stmt);
    } else {
        die(json_encode(['error' => 'Error en la preparación de la consulta: ' . mysqli_error($conexion)]));
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}

mysqli_close($conexion);