<?php
// indexadmin.php
// Página principal del administrador

include_once('../Templates/headAdmin.php'); // Incluir el encabezado de la plantilla

// Opcional: También podrías definir aquí variables globales para usar en las páginas
$id = $_SESSION['id'];
$usuario_nombre = $_SESSION['usuario_nombre'];

// Impedir el almacenamiento en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado
header("Pragma: no-cache");

?>


<style>
        .profile-avatar {
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .admin-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
    </style>

<body>
    <div class="admin-container">
        <button class="hamburger-menu">
            <i class="fas fa-bars"></i>
        </button>
        <div class="admin-top-bar">
            <div class="admin-info">
                <div class="admin-icon">
                    <img src="../assets/logops.png" alt="Icono de Administrador" style="width: 30px; height: 30px;">
                </div>
                <div class="admin-title">ADMINISTRADOR</div>
                <div class="admin-separator">|</div>
                <div class="admin-name"><?php echo isset($usuario_nombre) ? $usuario_nombre : ''; ?></div>
            </div>
            <div class="admin-profile">
                <div class="profile-avatar" style="background-color: #8B5CF6; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    <?php echo isset($usuario_nombre) ? substr($usuario_nombre, 0, 1) : ''; ?>
                </div>
            </div>
        </div>
        <div class="sidebar-wrapper">
            <aside class="sidebar">
                <nav class="menu">
                    <a href="citasadmin.php" class="menu-item">
                        <span>CITAS</span>
                    </a>
                    <a href="productosadmin.php" class="menu-item">
                        <span>PRODUCTOS</span>
                    </a>
                    <a href="serviciosadmin.php" class="menu-item">
                        <span>SERVICIOS</span>
                    </a>
                    <a href="personal.php" class="menu-item">
                        <span>PERSONAL</span>
                    </a>
                    <a href="ventas.php" class="menu-item">
                        <span>VENTAS</span>
                    </a>
                </nav>
                <div class="bottom-buttons">
                    <a href="indexadmin.php" class="btn-inicio">
                        <span>INICIO</span>
                    </a>
                    <a href="../cerrar_sesion.php" class="btn-salir">
                        <span>SALIR</span>
                    </a>
                </div>
            </aside>
        </div>
        <main class="main-content">
            <div class="dashboard-grid">
                <?php include('ultima_cita.php'); ?>
                <div class="dashboard-card products-section">
                    <div class="card-header">
                        <h2>Producto Más Reciente</h2>
                    </div>
                    <div class="products-grid">
                        <?php
                        require_once('../conexion.php');
                        $query_producto_reciente = "SELECT id_product, nom_product, img_product, prec_product, stock_product
                                                    FROM productos
                                                    ORDER BY id_product DESC
                                                    LIMIT 1";
                        $result_producto_reciente = mysqli_query($conexion, $query_producto_reciente);
                        if ($result_producto_reciente && mysqli_num_rows($result_producto_reciente) > 0) {
                            $producto = mysqli_fetch_assoc($result_producto_reciente);
                            ?>
                            <div class="product-card">
                                <img src="<?php echo !empty($producto['img_product']) ? '../' . $producto['img_product'] : '../assets/product-default.png'; ?>" alt="<?php echo $producto['nom_product']; ?>">
                                <div class="product-info">
                                    <h4><?php echo $producto['nom_product']; ?></h4>
                                    <p class="price">$<?php echo number_format($producto['prec_product'], 0, ',', '.'); ?></p>
                                    <span class="stock">En stock: <?php echo $producto['stock_product']; ?></span>
                                </div>
                            </div>
                            <?php
                        } else {
                            echo '<p>No se encontraron productos.</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="dashboard-card admin-users-section">
                    <div class="card-header">
                        <h2>Administradores</h2>
                        <a href="#" class="view-all">Ver todos</a>
                    </div>
                    <div class="admin-users-list">
                        <?php
                        require_once('../conexion.php');
                        $query = "SELECT nom_usu, apell_usu, correo_usu, id_estado FROM usuario WHERE id_rol = 1 LIMIT 2";
                        $result = mysqli_query($conexion, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($admin = mysqli_fetch_assoc($result)) {
                                $status_class = ($admin['id_estado'] == 1) ? 'active' : 'inactive';
                                $status_text = ($admin['id_estado'] == 1) ? 'Activo' : 'Inactivo';
                                ?>
                                <div class="admin-user-item">
                                    <div class="admin-user-details">
                                        <h4><?php echo $admin['nom_usu'] . ' ' . $admin['apell_usu']; ?></h4>
                                        <p class="admin-role">Administrador</p>
                                        <span class="admin-status <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p>No se encontraron administradores.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
