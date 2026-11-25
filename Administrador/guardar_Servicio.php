<?php
header('Content-Type: application/json');
require_once('../conexion.php');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data['nom_serv'] || !$data['precio_serv'] || !$data['id_tip_serv']) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$nom_serv = $data['nom_serv'];
$precio_serv = $data['precio_serv'];
$id_tip_serv = $data['id_tip_serv'];
$descrip_serv = isset($data['descrip_serv']) ? $data['descrip_serv'] : '';

$query = "INSERT INTO servicios (nom_serv, precio_serv, descrip_serv, id_tip_serv) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param('sdsi', $nom_serv, $precio_serv, $descrip_serv, $id_tip_serv);

if ($stmt->execute()) {
    $id_serv = $stmt->insert_id;
    echo json_encode([
        'success' => true,
        'servicio' => [
            'id_serv' => $id_serv,
            'nom_serv' => $nom_serv,
            'precio_serv' => $precio_serv,
            'descrip_serv' => $descrip_serv,
            'id_tip_serv' => $id_tip_serv
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el servicio.']);
}

$stmt->close();
$conexion->close();