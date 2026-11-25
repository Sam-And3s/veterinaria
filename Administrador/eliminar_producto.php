<?php
require_once('../conexion.php'); // Incluye la conexión a la base de datos

// Verifica si se recibió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el ID del producto desde la solicitud
    $id_product = $_POST['id'];

    // Verifica que el ID no esté vacío
    if (!empty($id_product)) {
        // Consulta para eliminar el producto
        $query = "DELETE FROM productos WHERE id_product = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id_product);

        // Ejecuta la consulta y verifica si fue exitosa
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . mysqli_error($conexion)]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

mysqli_close($conexion);
?>