<?php
header('Content-Type: application/json');
require_once('../conexion.php');  // Actualizada la ruta

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Primero obtenemos la información de la foto para eliminarla
    $query = "SELECT foto_eqp FROM equipo_vet WHERE num_doc = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $_POST['id']);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $miembro = mysqli_fetch_assoc($resultado);
    
    // Eliminar el registro de la base de datos
    $query = "DELETE FROM equipo_vet WHERE num_doc = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $_POST['id']);
    
    if (mysqli_stmt_execute($stmt)) {
        // Si la eliminación fue exitosa y existe una foto, la eliminamos
        if ($miembro && $miembro['foto_eqp']) {
            $ruta_foto = $miembro['foto_eqp'];
            if (file_exists($ruta_foto)) {
                unlink($ruta_foto);
            }
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . mysqli_error($conexion)]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
}

mysqli_close($conexion);
?>