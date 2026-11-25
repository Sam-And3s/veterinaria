<?php
session_start();
require '../conexion.php'; // Asegúrate de que este archivo exista y defina la variable $conexion

$id = $_SESSION['id'] ?? null; // Validar que la clave 'id' exista en la sesión
$contrasena_actual = $_POST['contrasena_actual'] ?? null;
$contrasena_nueva = $_POST['contrasena_nueva'] ?? null;
$contrasena_confirmacion = $_POST['contrasena_confirmacion'] ?? null;

// Validar los datos antes de procesarlos
if (!$id || empty($contrasena_actual) || empty($contrasena_nueva) || empty($contrasena_confirmacion)) {
    header("Location: inicioUsu.php?error=Faltan datos obligatorios para cambiar la contraseña");
    exit;
}

if ($contrasena_nueva !== $contrasena_confirmacion) {
    header("Location: inicioUsu.php?error=Las contraseñas no coinciden");
    exit;
}

// Verificar la contraseña actual
$query = "SELECT contra_usu FROM usuario WHERE documento_usu=?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!password_verify($contrasena_actual, $usuario['contra_usu'])) {
    header("Location: inicioUsu.php?error=La contraseña actual es incorrecta");
    exit;
}

// Actualizar la contraseña
$nueva_contrasena_hash = password_hash($contrasena_nueva, PASSWORD_DEFAULT);
$query = "UPDATE usuario SET contra_usu=? WHERE documento_usu=?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('si', $nueva_contrasena_hash, $id);
$stmt->execute();

// Redirigir al usuario con un mensaje de éxito
header("Location: inicioUsu.php?success=Contraseña actualizada correctamente");
?>