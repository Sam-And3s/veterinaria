<?php
require_once('../conexion.php'); // Incluye la conexión a la base de datos
// Definir el número de productos por página
$productos_por_pagina = 5;

// Obtener la página actual (si no está definida, por defecto será la página 1)
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $productos_por_pagina;

// Contar el total de productos
$query_total = "SELECT COUNT(*) AS total FROM productos";
$result_total = mysqli_query($conexion, $query_total);
$total_productos = mysqli_fetch_assoc($result_total)['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_productos / $productos_por_pagina);

// Consulta para obtener los productos paginados y sus categorías
$query = "
    SELECT 
        p.id_product, 
        p.nom_product, 
        p.img_product, 
        p.prec_product, 
        p.stock_product, 
        p.id_cat, 
        c.nom_cat 
    FROM productos p
    LEFT JOIN categorias_product c ON p.id_cat = c.id_cat
    LIMIT $productos_por_pagina OFFSET $offset
";
$result = mysqli_query($conexion, $query);
?>
<?php 
require_once ('../Templates/headAdmin.php');
?>
<title>Puppy Shop - Administrador</title>
<link rel="stylesheet" href="./css/productosadmin.css"> <!-- Estilos específicos de productos -->
<?php 
require_once ('../Templates/infoadmin.php');
?>
<?php 
require_once ('../Templates/navbarAdmin.php');
?>

<main class="main-content">
    <div class="products-container">
        <!-- Encabezado de la sección de productos -->
        <div class="products-header">
            <div class="title-section">
                <h1>Productos</h1>
                <div class="title-separator"></div>
            </div>
            <div class="controls-section">
                <div class="search-bar">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Buscar producto..." class="search-input">
                    </div>
                </div>
                <div class="action-buttons">
                <button class="export-btn" onclick="window.location.href='exportar_productos.php'">
    <i class="fas fa-file-export"></i>
    Exportar
</button>
                    <button class="new-product-btn">
                        <i class="fas fa-plus"></i>
                        Nuevo producto
                    </button>
                </div>
            </div>
        </div>

   <!-- Tabla de productos -->
<div class="products-table">
    <div class="table-header">
        <div class="checkbox-cell">
            <input type="checkbox" class="select-all">
        </div>
        <div class="image-cell">IMAGEN</div>
        <div class="name-cell">NOMBRE</div>
        <div class="category-cell">CATEGORÍA</div>
        <div class="stock-cell">STOCK</div>
        <div class="price-cell">PRECIO</div>
        <div class="status-cell">ESTADO</div>
        <div class="actions-cell">ACCIONES</div>
    </div>

    <div class="table-body">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($product = mysqli_fetch_assoc($result)) {
                $imgProduct = $product['img_product'];

                // Generar la ruta completa de la imagen
                if (empty($imgProduct)) {
                    $imgSrc = '/veterinaria/assets/products/default-product.jpg';
                } elseif (strpos($imgProduct, 'assets/products/') === false) {
                    $imgSrc = '/veterinaria/assets/products/' . $imgProduct;
                } else {
                    $imgSrc = '/veterinaria/' . $imgProduct;
                }
        ?>
                <div class="product-row" data-id="<?php echo $product['id_product']; ?>">
                    <div class="checkbox-cell">
                        <input type="checkbox">
                    </div>
                    <div class="image-cell">
                        <img 
                            src="<?php echo $imgSrc; ?>" 
                            alt="Imagen de <?php echo htmlspecialchars($product['nom_product']); ?>" 
                            class="product-image"
                        >
                    </div>
                    <div class="name-cell"><?php echo htmlspecialchars($product['nom_product']); ?></div>
                    <div class="category-cell"><?php echo htmlspecialchars($product['nom_cat']); ?></div>
                    <div class="stock-cell"><?php echo $product['stock_product']; ?></div>
                    <div class="price-cell">$<?php echo number_format($product['prec_product'], 2); ?></div>
                    <div class="status-cell">
                        <span class="status-badge <?php echo $product['stock_product'] > 0 ? 'active' : 'inactive'; ?>">
                            <?php echo $product['stock_product'] > 0 ? 'Activo' : 'Inactivo'; ?>
                        </span>
                    </div>
                    <div class="actions-cell">
                        <button class="action-btn edit" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        ?>
    </div>
</div>

<!-- Control de paginación -->
<div class="pagination-container">
    <?php if ($pagina_actual > 1): ?>
        <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="pagination-button prev" title="Anterior">◀</a>
    <?php endif; ?>

    <?php
    // Mostrar números de páginas
    for ($i = 1; $i <= $total_paginas; $i++) {
        if ($i == $pagina_actual) {
            echo '<button class="pagination-button active">' . $i . '</button>';
        } else {
            echo '<a href="?pagina=' . $i . '" class="pagination-button">' . $i . '</a>';
        }
    }
    ?>

    <?php if ($pagina_actual < $total_paginas): ?>
        <a href="?pagina=<?php echo $pagina_actual + 1; ?>" class="pagination-button next" title="Siguiente">▶</a>
    <?php endif; ?>
</div>
</main>

<!-- Modal para registrar un nuevo producto -->
<div id="products-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Registrar Nuevo Producto</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="product-register-form" enctype="multipart/form-data" method="POST" action="../procesar_producto.php">
                <div class="input-group">
                    <input type="text" name="nom_product" placeholder="Nombre del producto" required>
                </div>
                <div class="input-group">
                    <select name="id_cat" required>
                        <option value="">Seleccionar categoría</option>
                        <?php
                        $query_tipos = "SELECT * FROM categorias_product";
                        $result_tipos = mysqli_query($conexion, $query_tipos);
                        while ($tipo = mysqli_fetch_assoc($result_tipos)) {
                            echo "<option value='" . $tipo['id_cat'] . "'>" . $tipo['nom_cat'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                    <input type="number" name="prec_product" placeholder="Precio" required>
                </div>
                <div class="input-group">
                    <input type="number" name="stock_product" placeholder="Stock" required>
                </div>
                <div class="input-group">
                    <label for="img_product" class="profile-photo-btn">
                        <i class="fas fa-camera"></i>
                        <span>Agregar imagen del producto</span>
                        <input type="file" id="img_product" name="img_product" accept="image/jpeg,image/png" style="display: none;">
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn">Cancelar</button>
                    <button type="submit" class="submit-btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-product-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Producto</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="edit-product-form" enctype="multipart/form-data" method="POST">
                <!-- Campo oculto para el ID del producto -->
                <input type="hidden" name="id_product" id="edit-id-product">
                
                <div class="input-group">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nom_product" id="edit-nom-product" placeholder="Nombre del producto" required>
                </div>
                
                <div class="input-group">
                    <label>Categoría</label>
                    <select name="id_cat" id="edit-id-cat" required>
                        <option value="">Seleccionar categoría</option>
                        <?php
                        $query_tipos = "SELECT * FROM categorias_product";
                        $result_tipos = mysqli_query($conexion, $query_tipos);
                        while ($tipo = mysqli_fetch_assoc($result_tipos)) {
                            echo "<option value='" . $tipo['id_cat'] . "'>" . $tipo['nom_cat'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="input-group">
                    <label>Precio</label>
                    <input type="number" name="prec_product" id="edit-prec-product" placeholder="Precio" required>
                </div>
                
                <div class="input-group">
                    <label>Stock</label>
                    <input type="number" name="stock_product" id="edit-stock-product" placeholder="Stock" required>
                </div>
                
                <div class="input-group">
                    <label>Imagen del Producto</label>
                    <label for="edit-img-product" class="profile-photo-btn">
                        <i class="fas fa-camera"></i>
                        <span>Actualizar imagen del producto</span>
                        <input type="file" id="edit-img-product" name="img_product" accept="image/jpeg,image/png" style="display: none;">
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel">Cancelar</button>
            <button type="button" onclick="guardarCambiosProducto()" class="btn-save">Guardar</button>
        </div>
    </div>
</div>

<script src="./js/productosadmin.js"></script>