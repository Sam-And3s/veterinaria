<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Conexión a la base de datos
require_once 'conexion.php'; // Asegúrate de que este archivo contiene la configuración de conexión a la base de datos

// Verificar el parámetro de acción
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'getProducts') {
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';

    $sql = "SELECT p.id_product, p.nom_product, p.img_product, p.prec_product, c.nom_cat 
            FROM productos p 
            INNER JOIN categorias_product c ON p.id_cat = c.id_cat";

    if ($category !== 'all') {
        $sql .= " WHERE c.nom_cat = ?";
    }

    $stmt = $conexion->prepare($sql);

    if ($category !== 'all') {
        $stmt->bind_param('s', $category);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
} elseif ($action === 'getCategories') {
    $sql = "SELECT * FROM categorias_product";
    $result = $conexion->query($sql);

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode($categories);
} else {
    echo json_encode(['error' => 'Invalid action']);
}

$conexion->close();
?>