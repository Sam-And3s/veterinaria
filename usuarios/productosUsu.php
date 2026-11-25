<?php
require_once('../Templates/headUsu.php');
// Asegúrate de que la sesión esté iniciada y el ID del usuario esté en $_SESSION['id']
// session_start();  <-- Elimina esta línea
if (!isset($_SESSION['id'])) {
    // Si el usuario no está logueado, redirige al login o muestra un mensaje de error
    header("Location: sesion.php"); // Verifica que la ruta sea correcta
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PuppyShop - Productos</title>
    <link rel="stylesheet" href="css/productos.css">
    <?php require_once('../Templates/navVarusu.php'); ?>
</head>
<body>
    <section class="products-header">
        <!-- Contenedor del contenido textual del encabezado -->
        <div class="header-content">
            <!-- Título principal con diseño segmentado -->
            <h1>
                ELEGIDOS
                <span>PARA</span>
                <span>CONSENTIRLO</span>
            </h1>
            <!-- Texto promocional de descuentos -->
            <p>Comodidad, salud y estilo en un solo lugar</p>
            <!-- Botón de llamada a la acción -->
        </div>
        <!-- Contenedor de la imagen destacada -->
        <div class="header-image">
            <!-- Imagen principal del encabezado -->
            <img src="assets/fondopd.png" alt="Featured Products" class="header-image">
        </div>
    </section>

    <input type="hidden" id="loggedInUserId" value="<?php echo $_SESSION['id']; ?>">

    <section class="products-section">
        <!-- Barra de filtros para categorías de productos -->
        <div class="filters-buttons">
            <!-- Botón de filtro para mostrar todos los productos (activo por defecto) -->
            <button class="filter-btn active" data-category="all">Todos</button>
            <!-- Botón de filtro para categoría de alimentos -->
            <button class="filter-btn" data-category="food">Alimentos</button>
            <!-- Botón de filtro para categoría de salud -->
            <button class="filter-btn" data-category="health">Salud</button>
            <!-- Botón de filtro para categoría de juguetes -->
            <button class="filter-btn" data-category="toys">Juguetes</button>
            <!-- Botón de filtro para categoría de accesorios -->
            <button class="filter-btn" data-category="accessories">Accesorios</button>
        </div>

        <!-- Contenedor de la cuadrícula de productos (se llena dinámicamente) -->
        <div class="products-grid">
            <!-- Los productos se agregarán aquí mediante JavaScript -->
        </div>

        <!-- Sistema de paginación para navegación entre páginas de productos -->
        <div class="pagination-controls">
            <!-- Botón para página anterior -->
            <button class="page-btn prev-btn"><i class="ri-arrow-left-s-line"></i></button>
            <!-- Contenedor para números de página -->
            <div class="page-numbers"></div>
            <!-- Botón para página siguiente -->
            <button class="page-btn next-btn"><i class="ri-arrow-right-s-line"></i></button>
        </div>
    </section>
    
    <div class="cart-float">
        <i class="ri-shopping-cart-2-line"></i>
        <span class="cart-count-bubble">0</span>
    </div>

    <div class="cart-popup">
        <div class="cart-popup-header">
            <h3>Carrito de Compras</h3>
            <span class="cart-items-count">0 items</span>
        </div>
        <div class="cart-items">
            </div>
        <div class="cart-total">
            <div class="subtotal">
                <span>Subtotal</span>
                <span class="subtotal-amount">$0</span>
            </div>
            <button class="checkout-button">
                <i class="ri-shopping-bag-line"></i>
                Finalizar Compra
            </button>
        </div>
    </div>
    <?php require_once('../Templates/footerInicio.php'); ?>
    <script src="js/productos.js"></script>
</body>
</html>