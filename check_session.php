<?php
// check_session.php
// Este archivo se incluye al principio de CADA página que requiera autenticación

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    // Si no ha iniciado sesión, redirigir al index.php
    header('Location: index.php');
    exit();
}

// Opcional: También podrías definir aquí variables globales para usar en las páginas
$id = $_SESSION['id'];
$usuario_nombre = $_SESSION['usuario_nombre'];
?>