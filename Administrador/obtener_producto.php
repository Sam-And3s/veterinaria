<?php
require_once('../conexion.php');

$id = $_GET['id'];
$query = "SELECT * FROM productos WHERE id_product = $id";
$result = mysqli_query($conexion, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
    echo json_encode($product);
} else {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
}
?>