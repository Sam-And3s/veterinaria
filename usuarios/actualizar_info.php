<?php
session_start();
require '../conexion.php'; // Asegúrate de que este archivo exista y defina la variable $conexion

// Validar que la sesión esté activa y que el ID del usuario exista
$id = $_SESSION['id'] ?? null;
if (!$id) {
    header("Location: inicioUsu.php?error=No se encontró el usuario en la sesión");
    exit;
}

// Capturar los datos enviados desde el formulario
$correo = $_POST['correo'] ?? null;
$direccion = $_POST['direccion'] ?? null;
$contacto = $_POST['contacto'] ?? null;

// Validar los datos antes de procesarlos
if (empty($correo) || empty($direccion) || empty($contacto)) {
    header("Location: inicioUsu.php?error=Faltan datos obligatorios");
    exit;
}

// Construir la consulta SQL para actualizar los datos
$query = "UPDATE usuario SET correo_usu=?, direc_usu=?, num_usu=? WHERE documento_usu=?";
$stmt = $conexion->prepare($query);
if (!$stmt) {
    header("Location: inicioUsu.php?error=Error al preparar la consulta");
    exit;
}
$stmt->bind_param('sssi', $correo, $direccion, $contacto, $id);

// Ejecutar la consulta y verificar si se actualizó correctamente
if ($stmt->execute()) {
    // Actualizar los datos en la sesión
    $_SESSION['correo_usu'] = $correo;
    $_SESSION['direc_usu'] = $direccion;
    $_SESSION['num_usu'] = $contacto;

    // Redirigir al usuario con un mensaje de éxito
    header("Location: inicioUsu.php?success=Información actualizada correctamente");
} else {
    header("Location: inicioUsu.php?error=Error al actualizar la información");
}
?>