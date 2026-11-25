<?php
require_once('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $foto_path = null;

        // Verificar si se ha subido una imagen
        if (isset($_FILES['img_product']) && $_FILES['img_product']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png']; // Tipos de imagen permitidos
            $max_size = 2 * 1024 * 1024; // Tamaño máximo permitido (2MB)

            // Verificar el tipo de archivo
            if (!in_array($_FILES['img_product']['type'], $allowed_types)) {
                throw new Exception('Tipo de archivo no permitido. Use JPG o PNG.');
            }

            // Verificar el tamaño del archivo
            if ($_FILES['img_product']['size'] > $max_size) {
                throw new Exception('La imagen no debe superar los 2MB');
            }

            // Generar un nombre único para la imagen
            $upload_dir = 'assets/products/';
            $file_name = uniqid('product_') . '.' . pathinfo($_FILES['img_product']['name'], PATHINFO_EXTENSION);
            $file_path = $upload_dir . $file_name;

            // Mover el archivo a la carpeta de destino
            if (!move_uploaded_file($_FILES['img_product']['tmp_name'], $file_path)) {
                throw new Exception('Error al mover la imagen al servidor');
            }

            // Guardar la ruta de la imagen en la base de datos
            $foto_path = $file_path;
        }

        // Validar campos requeridos
        $required_fields = ['nom_product', 'id_cat', 'prec_product', 'stock_product'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("El campo $field es requerido");
            }
        }

        // Preparar los valores
        $nombre = mysqli_real_escape_string($conexion, $_POST['nom_product']);
        $categoria = intval($_POST['id_cat']);
        $precio = floatval($_POST['prec_product']);
        $stock = intval($_POST['stock_product']);

        // Insertar los datos en la base de datos
        $query = "INSERT INTO productos (nom_product, img_product, id_cat, prec_product, stock_product) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ssidi", 
            $nombre, $foto_path, $categoria, $precio, $stock
        );

        // Ejecutar la consulta
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Error al registrar producto');
        }

        // ✅ Mostrar alerta y recargar
        echo "<script>
            alert('Producto registrado correctamente ✅');
            window.location.href = document.referrer;
        </script>";
        exit;
    } catch (Exception $e) {
        // Manejo de errores
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Método no permitido'); window.history.back();</script>";
}
?>