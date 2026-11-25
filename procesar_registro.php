<?php
require_once 'conexion.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if connection is successful
if (!$conexion) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['documento_usu']) && isset($_POST['tipo_doc_usu']) && isset($_POST['nom_usu']) && 
    isset($_POST['apell_usu']) && isset($_POST['num_usu']) && isset($_POST['direc_usu']) && 
    isset($_POST['correo_usu']) && isset($_POST['contra_usu']) && isset($_POST['id_estado'])) {

    // Sanitize inputs
    $documento_usu = mysqli_real_escape_string($conexion, $_POST['documento_usu']);
    $tipo_doc_usu = mysqli_real_escape_string($conexion, $_POST['tipo_doc_usu']);
    $nom_usu = mysqli_real_escape_string($conexion, $_POST['nom_usu']);
    $apell_usu = mysqli_real_escape_string($conexion, $_POST['apell_usu']);
    $num_usu = mysqli_real_escape_string($conexion, $_POST['num_usu']);
    $direc_usu = mysqli_real_escape_string($conexion, $_POST['direc_usu']);
    $correo_usu = mysqli_real_escape_string($conexion, $_POST['correo_usu']);
    $contra_usu = mysqli_real_escape_string($conexion, $_POST['contra_usu']);
    $id_estado = mysqli_real_escape_string($conexion, $_POST['id_estado']);

    // Forzar que el id_rol sea 2
    $id_rol = 2;

    try {
        // Verificar si el número de documento ya existe
        $stmt = $conexion->prepare("SELECT documento_usu FROM usuario WHERE documento_usu = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conexion->error);
        }
        
        $stmt->bind_param("s", $documento_usu);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            header("Location: sesion.php?mensaje=Número de documento ya registrado.&tipo=error");
            exit();
        }
        $stmt->close();

        // Verificar si el correo electrónico ya existe
        $stmt = $conexion->prepare("SELECT correo_usu FROM usuario WHERE correo_usu = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conexion->error);
        }

        $stmt->bind_param("s", $correo_usu);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            header("Location: sesion.php?mensaje=Correo electrónico ya registrado.&tipo=error");
            exit();
        }
        $stmt->close();

        // Hashear la contraseña
        $hashed_password = password_hash($contra_usu, PASSWORD_DEFAULT);

        // Prepare and execute insert statement
        $stmt = $conexion->prepare("INSERT INTO usuario (documento_usu, tipo_doc_usu, nom_usu, apell_usu, num_usu, direc_usu, correo_usu, contra_usu, id_rol, id_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conexion->error);
        }

        $stmt->bind_param("ssssssssss", $documento_usu, $tipo_doc_usu, $nom_usu, $apell_usu, $num_usu, $direc_usu, $correo_usu, $hashed_password, $id_rol, $id_estado);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        mysqli_close($conexion);
        header("Location: sesion.php?mensaje=Usuario registrado exitosamente&tipo=success");
        exit();

    } catch (Exception $e) {
        error_log("Error in procesar_registro.php: " . $e->getMessage());
        header("Location: sesion.php?mensaje=Error al registrar usuario: " . urlencode($e->getMessage()) . "&tipo=error");
        exit();
    }
} else {
    header("Location: sesion.php?mensaje=Todos los campos son requeridos&tipo=error");
    exit();
}

mysqli_close($conexion);
?>