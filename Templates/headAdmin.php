<?php
// Incluir el archivo de verificación de sesión
require_once('../check_session.php');  // Asegúrate de que la ruta sea correcta
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    // Si no ha iniciado sesión, redirigir al index.php
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<!-- Inicio del documento HTML con especificación del idioma español -->
<html lang="es">
<!-- Sección de metadatos y recursos del documento -->
<head>
    <!-- Especificación de codificación de caracteres UTF-8 -->
    <meta charset="UTF-8">
    <!-- Configuración de la vista para dispositivos móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace a la hoja de estilos principal del administrador -->
    <link rel="stylesheet" href="css/indexadmin.css">
    <!-- Importación de la fuente Poppins desde Google Fonts con diferentes pesos -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Importación de la biblioteca de iconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Importación de la biblioteca de animaciones Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="icon" href="../assets/logops.png">