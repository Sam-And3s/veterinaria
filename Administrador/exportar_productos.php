<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);  // Desactivar los avisos de funciones obsoletas
ini_set('display_errors', 0);  // Desactivar la visualización de errores en el navegador

require_once('../conexion.php'); // Incluye la conexión a la base de datos
require_once('../libs/fpdf186/fpdf.php'); // Cambia la ruta a la correcta

class PDF extends FPDF
{
    // Encabezado del PDF
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Lista de Productos', 0, 1, 'C');
        $this->Ln(10); // Salto de línea
    }

    // Pie de página del PDF
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Crear instancia de FPDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Consulta para obtener los productos
$query = "
    SELECT 
        p.nom_product, 
        p.prec_product, 
        p.stock_product, 
        c.nom_cat 
    FROM productos p
    LEFT JOIN categorias_product c ON p.id_cat = c.id_cat
";
$result = mysqli_query($conexion, $query);

// Verifica si hay productos
if ($result && mysqli_num_rows($result) > 0) {
    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(70, 10, 'Nombre del Producto', 1);
    $pdf->Cell(40, 10, 'Categoria', 1);
    $pdf->Cell(30, 10, 'Precio', 1);
    $pdf->Cell(30, 10, 'Stock', 1);
    $pdf->Ln();

    // Datos de los productos
    $pdf->SetFont('Arial', '', 12);
    while ($product = mysqli_fetch_assoc($result)) {
        $pdf->Cell(70, 10, mb_convert_encoding($product['nom_product'], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(40, 10, mb_convert_encoding($product['nom_cat'], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(30, 10, '$' . number_format($product['prec_product'], 2), 1);
        $pdf->Cell(30, 10, $product['stock_product'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No hay productos disponibles.', 1, 1, 'C');
}

// Salida del PDF
$pdf->Output('D', 'productos.pdf'); // Descarga el archivo como "productos.pdf"
?>
