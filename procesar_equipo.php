<?php
header('Content-Type: application/json');
require_once('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Procesar la imagen si se subió una
        $foto_path = null;
        if (isset($_FILES['foto_eqp']) && $_FILES['foto_eqp']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png'];
            $max_size = 2 * 1024 * 1024; // 2MB

            if (!in_array($_FILES['foto_eqp']['type'], $allowed_types)) {
                throw new Exception('Tipo de archivo no permitido. Use JPG o PNG.');
            }

            if ($_FILES['foto_eqp']['size'] > $max_size) {
                throw new Exception('La imagen no debe superar 2MB');
            }

            // Crear directorio si no existe
            $upload_dir = __DIR__ . '/assets/team/';
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                    throw new Exception('Error al crear el directorio de imágenes.');
                }
            }

            // Generar nombre único
            $extension = pathinfo($_FILES['foto_eqp']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('team_') . '.' . $extension;
            $foto_destino = $upload_dir . $filename; // Ruta completa del archivo en el servidor
            $foto_path = 'assets/team/' . $filename; // Ruta relativa para la base de datos

            // Mover archivo
            if (!move_uploaded_file($_FILES['foto_eqp']['tmp_name'], $foto_destino)) {
                throw new Exception('Error al guardar la imagen en el servidor.');
            }
        }

        // Verificar que todos los campos requeridos estén presentes
        $required_fields = ['num_doc', 'id_tipo_doc', 'nom_eqp', 'apell_eqp', 'correo_eqp', 'telefono_eqp', 'prop_eqp'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("El campo $field es requerido");
            }
        }

        // Preparar datos
        $num_doc = mysqli_real_escape_string($conexion, $_POST['num_doc']);
        $id_tipo_doc = mysqli_real_escape_string($conexion, $_POST['id_tipo_doc']);
        $nombre = mysqli_real_escape_string($conexion, $_POST['nom_eqp']);
        $apellido = mysqli_real_escape_string($conexion, $_POST['apell_eqp']);
        $correo = mysqli_real_escape_string($conexion, $_POST['correo_eqp']);
        $telefono = mysqli_real_escape_string($conexion, $_POST['telefono_eqp']);
        $especialidad = mysqli_real_escape_string($conexion, $_POST['prop_eqp']);
        $descripcion = isset($_POST['desc_eqp']) ? mysqli_real_escape_string($conexion, $_POST['desc_eqp']) : '';

        $query = "INSERT INTO equipo_vet (num_doc, id_tipo_doc, nom_eqp, apell_eqp, correo_eqp, telefono_eqp, prop_eqp, desc_eqp, foto_eqp)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "iisssssss",
            $num_doc,
            $id_tipo_doc,
            $nombre,
            $apellido,
            $correo,
            $telefono,
            $especialidad,
            $descripcion,
            $foto_path
        );

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Miembro registrado correctamente']);
        } else {
            throw new Exception('Error al registrar: ' . mysqli_error($conexion));
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    } finally {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        if (isset($conexion)) {
            mysqli_close($conexion);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>