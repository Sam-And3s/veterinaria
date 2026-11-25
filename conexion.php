
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conexion = mysqli_connect("localhost", "root", "", "puppyshop") or die("Error al conectar con la base de datos"); 
if (!$conexion) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}
?>