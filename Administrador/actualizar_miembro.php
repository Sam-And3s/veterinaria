<?php
header('Content-Type: application/json');
require_once('../conexion.php');

// Función para validar strings
function validarString($data, $min = 1, $max = 255) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if (empty($data)) {
        return false;
    }
    if (strlen($data) < $min || strlen($data) > $max) {
        return false;
    }
    return $data;
}

// Función para validar correo electrónico
function validarEmail($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return $email;
}

// Función para validar número de teléfono
function validarTelefono($telefono) {
    $telefono = trim($telefono);
    $telefono = stripslashes($telefono);
    $telefono = htmlspecialchars($telefono);
    // Permite números, espacios, guiones y paréntesis
    if (!preg_match('/^[0-9\s()-]+$/', $telefono)) {
        return false;
    }
    return $telefono;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $num_doc = validarString($_POST['num_doc'], 5, 20);
    $correo = validarEmail($_POST['correo_eqp']);
    $telefono = validarTelefono($_POST['telefono_eqp']);
    $especialidad = validarString($_POST['prop_eqp'], 2, 100);
    $descripcion = validarString($_POST['desc_eqp'], 0, 500); // La descripción puede estar vacía

    if (!$num_doc) {
        echo json_encode(['success' => false, 'message' => 'Número de documento no válido.']);
        exit;
    }
    if (!$correo) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido.']);
        exit;
    }
    if (!$telefono) {
        echo json_encode(['success' => false, 'message' => 'Número de teléfono no válido.']);
        exit;
    }
    if (!$especialidad) {
        echo json_encode(['success' => false, 'message' => 'Especialidad no válida.']);
        exit;
    }

    // Manejo de la foto
    $foto_path = null;
    if (isset($_FILES['foto_eqp2']) && $_FILES['foto_eqp2']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($_FILES['foto_eqp2']['type'], $allowed_types)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido (solo JPG o PNG).']);
            exit;
        }

        if ($_FILES['foto_eqp2']['size'] > $max_size) {
            echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande (máximo 2MB).']);
            exit;
        }

        $extension = pathinfo($_FILES['foto_eqp2']['name'], PATHINFO_EXTENSION);
        $nuevo_nombre = 'team_' . uniqid() . '.' . $extension;
        $foto_path = 'assets/team/' . $nuevo_nombre; // **ASEGÚRATE DE QUE ESTA LÍNEA ESTÉ ASÍ**

        if (!move_uploaded_file($_FILES['foto_eqp2']['tmp_name'], '../' . $foto_path)) {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen en el servidor.']);
            exit;
        }
    }

    // 3. Actualizar la base de datos
    if ($foto_path) {
        $query = "UPDATE equipo_vet SET
                    correo_eqp = ?,
                    telefono_eqp = ?,
                    prop_eqp = ?,
                    desc_eqp = ?,
                    foto_eqp = ?
                    WHERE num_doc = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ssssss", $correo, $telefono, $especialidad, $descripcion, $foto_path, $num_doc);
    } else {
        $query = "UPDATE equipo_vet SET
                    correo_eqp = ?,
                    telefono_eqp = ?,
                    prop_eqp = ?,
                    desc_eqp = ?
                    WHERE num_doc = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $correo, $telefono, $especialidad, $descripcion, $num_doc);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la base de datos: ' . mysqli_error($conexion)]);
    }

    mysqli_stmt_close($stmt);

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

mysqli_close($conexion);
?>