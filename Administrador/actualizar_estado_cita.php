<?php
require_once '../conexion.php';

// Asegurarse de que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (isset($data['id_cita']) && isset($data['estado'])) {
        $idCita = $data['id_cita'];
        $estado = $data['estado'];

        // La columna correcta es 'id_estado'
        $query = "UPDATE citas SET id_estado = ? WHERE id_citas = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $estado, $idCita); // Asumo que 'id_estado' e 'id_citas' son enteros

        if (mysqli_stmt_execute($stmt)) {
            $response = array('success' => true);
        } else {
            $response = array('success' => false, 'message' => 'Error al actualizar el estado de la cita: ' . mysqli_error($conexion));
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);

        header('Content-Type: application/json');
        echo json_encode($response);

    } else {
        $response = array('success' => false, 'message' => 'Datos de cita o estado faltantes.');
        header('Content-Type: application/json');
        echo json_encode($response);
    }

} else {
    // Si la solicitud no es POST
    $response = array('success' => false, 'message' => 'Solicitud inválida.');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>