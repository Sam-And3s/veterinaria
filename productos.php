<?php 
require_once ('Templates/headInicio.php')
?>
    <title>PuppyShop - Productos</title>

    <link rel="stylesheet" href="css/productos.css">
    <?php 
require_once ('Templates/navVarInicio.php')
?>
    <!-- Sección del encabezado de productos con imagen destacada -->
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
    <!-- Sección principal de productos con filtros y paginación -->
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
<?php 
require_once ('Templates/footerInicio.php')
?>
<!-- Archivo JavaScript específico para la funcionalidad de la página de productos -->
<script src="js/productos.js"></script>
</body>
</html>