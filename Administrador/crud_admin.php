<?php
require_once('../conexion.php');

$action = $_REQUEST['action'] ?? '';

switch($action) {
    case 'get':
        $id = $_GET['id'];
        $query = "SELECT * FROM usuario WHERE documento_usu = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);
        echo json_encode($admin);
        break;

    case 'update':
        $id = $_POST['documento_usu'];
        $correo = $_POST['correo_usu'];
        $telefono = $_POST['num_usu'];
        $direccion = $_POST['direc_usu'];
        $password = $_POST['contra_usu'];

        $query = "UPDATE usuario SET
                    correo_usu = ?,
                    num_usu = ?,
                    direc_usu = ?";

        // Si se proporciona una nueva contraseña, actualizarla
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", contra_usu = ?";
        }

        $query .= " WHERE documento_usu = ?";
        $stmt = mysqli_prepare($conexion, $query);

        if (!empty($password)) {
            mysqli_stmt_bind_param($stmt, "sssss", $correo, $telefono, $direccion, $password_hash, $id);
        } else {
            mysqli_stmt_bind_param($stmt, "ssss", $correo, $telefono, $direccion, $id);
        }

        $success = mysqli_stmt_execute($stmt);
        echo json_encode(['success' => $success]);
        break;

    case 'delete':
        $id = $_POST['id'];
        $query = "DELETE FROM usuario WHERE documento_usu = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $id);
        $success = mysqli_stmt_execute($stmt);
        echo json_encode(['success' => $success]);
        break;

    case 'create': // Nuevo caso para crear un administrador
        $tipo_doc = $_POST['tipo_doc_usu'];
        $documento = $_POST['documento_usu'];
        $nombre = $_POST['nom_usu'];
        $apellido = $_POST['apell_usu'];
        $telefono = $_POST['num_usu'];
        $direccion = $_POST['direc_usu'];
        $correo = $_POST['correo_usu'];
        $password = $_POST['contra_usu'];
        $id_rol = $_POST['id_rol']; // Recibimos el id_rol del formulario (hidden)
        $id_estado = $_POST['id_estado']; // Recibimos el id_estado del formulario (hidden)

        // Hashear la contraseña antes de guardarla
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO usuario (tipo_doc_usu, documento_usu, nom_usu, apell_usu, num_usu, direc_usu, correo_usu, contra_usu, id_rol, id_estado)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssii", $tipo_doc, $documento, $nombre, $apellido, $telefono, $direccion, $correo, $password_hash, $id_rol, $id_estado);

        $success = mysqli_stmt_execute($stmt);
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conexion)]);
        }
        mysqli_stmt_close($stmt);
        break;
}
?>