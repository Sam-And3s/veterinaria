<?php
require_once('../conexion.php'); // Incluye la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_product = $_POST['id_product'];
    $nom_product = $_POST['nom_product'];
    $id_cat = $_POST['id_cat'];
    $prec_product = $_POST['prec_product'];
    $stock_product = $_POST['stock_product'];

    if (!empty($_FILES['img_product']['tmp_name'])) {
        $img_name = 'product_' . uniqid() . '.png'; // Nombre único
        $img_path = '../assets/products/' . $img_name; // Ruta de almacenamiento

        if (move_uploaded_file($_FILES['img_product']['tmp_name'], $img_path)) {
            // Guardar solo la ruta relativa (sin ../)
            $relative_path = 'assets/products/' . $img_name;

            // Consulta con imagen
            $query = "UPDATE productos SET 
                        nom_product = ?, 
                        id_cat = ?, 
                        prec_product = ?, 
                        stock_product = ?, 
                        img_product = ? 
                      WHERE id_product = ?";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, 'sidssi', $nom_product, $id_cat, $prec_product, $stock_product, $relative_path, $id_product);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
            exit;
        }
    } else {
        // Consulta sin imagen
        $query = "UPDATE productos SET 
                    nom_product = ?, 
                    id_cat = ?, 
                    prec_product = ?, 
                    stock_product = ? 
                  WHERE id_product = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, 'sidii', $nom_product, $id_cat, $prec_product, $stock_product, $id_product);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . mysqli_error($conexion)]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

mysqli_close($conexion);
?>