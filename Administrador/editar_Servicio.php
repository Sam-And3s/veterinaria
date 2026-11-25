<?php
header('Content-Type: application/json');
require_once('../conexion.php');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data['id_product'] || !$data['nom_serv'] || !$data['precio_serv'] || !$data['id_tip_serv']) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$idProduct = $data['id_product'];
$nom_serv = $data['nom_serv'];
$precio_serv = $data['precio_serv'];
$id_tip_serv = $data['id_tip_serv'];
$descrip_serv = isset($data['descrip_serv']) ? $data['descrip_serv'] : '';

$query = "UPDATE servicios SET nom_serv = ?, precio_serv = ?, descrip_serv = ?, id_tip_serv = ? WHERE id_serv = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('sdsii', $nom_serv, $precio_serv, $descrip_serv, $id_tip_serv, $idProduct);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el servicio.']);
}

$stmt->close();
$conexion->close();
?>