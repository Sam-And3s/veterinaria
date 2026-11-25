<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Conexión a la base de datos
require_once 'conexion.php';

// Verificar el parámetro de acción
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'getSpaPlans') {
    $query = "SELECT s.nom_serv, s.precio_serv, s.descrip_serv
              FROM servicios s
              INNER JOIN tipos_servicios ts ON s.id_tip_serv = ts.id_tip_serv
              WHERE ts.nom_tip_serv = 'spa'";

    $result = $conexion->query($query);

    $spaPlans = [];
    while ($row = $result->fetch_assoc()) {
        $spaPlans[] = $row;
    }

    echo json_encode(['success' => true, 'spaPlans' => $spaPlans]);
    exit;
} elseif ($action === 'getConsultaPlans') {
    $query = "SELECT s.nom_serv, s.precio_serv, s.descrip_serv
              FROM servicios s
              INNER JOIN tipos_servicios ts ON s.id_tip_serv = ts.id_tip_serv
              WHERE ts.id_tip_serv = 3";

    $result = $conexion->query($query);

    $consultaPlans = [];
    while ($row = $result->fetch_assoc()) {
        $consultaPlans[] = $row;
    }

    echo json_encode(['success' => true, 'consultaPlans' => $consultaPlans]);
    exit;
} elseif ($action === 'getPeluqueriaPlans') {
    $query = "SELECT s.nom_serv, s.precio_serv, s.descrip_serv
              FROM servicios s
              INNER JOIN tipos_servicios ts ON s.id_tip_serv = ts.id_tip_serv
              WHERE ts.id_tip_serv = 2";

    $result = $conexion->query($query);

    $peluqueriaPlans = [];
    while ($row = $result->fetch_assoc()) {
        $peluqueriaPlans[] = $row;
    }

    echo json_encode(['success' => true, 'peluqueriaPlans' => $peluqueriaPlans]);
    exit;
}
elseif ($action === 'getProducts') {
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