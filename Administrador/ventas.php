<!-- link al head  -->
<?php 
require_once ('../Templates/headAdmin.php')
?>
    <title>Puppy Shop - Administrador</title>
    <link rel="stylesheet" href="./css/ventas.css">
<!-- link a la barra de informacion del administrador   -->
<?php 
require_once ('../Templates/infoadmin.php')
?>

<!-- link al menu de navegacion  -->
<?php 
require_once ('../Templates/navbarAdmin.php')
?>

<!-- Contenido principal de ventas -->
<main class="ventas-content">
    <!-- Tarjetas de estadísticas -->
    <div class="resumen-estadisticas">
        <div class="card">
            <h3>Ventas del Día</h3>
            <span id="ventas-dia">0</span>
        </div>
        <div class="card">
            <h3>Pedidos Pendientes</h3>
            <span id="pedidos-pendientes">0</span>
        </div>
        <div class="card">
            <h3>Ventas Completadas</h3>
            <span id="ventas-completadas">0</span>
        </div>
    </div>

    <!-- Panel de pedidos -->
    <div class="pedidos-panel animate__animated animate__fadeInUp">
        <div class="panel-header">
            <h2>Gestión de Pedidos</h2>
            <div class="filtros">
                <select id="filtro-estado">
                    <option value="todos">Todos los pedidos</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="confirmado">Confirmado</option>
                    <option value="cancelado">Cancelados</option>
                </select>
                <input type="date" id="filtro-fecha">
                <button class="btn-refresh">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>

        <div class="tabla-pedidos">
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="lista-pedidos">
                    <!-- Los pedidos se cargarán dinámicamente aquí -->
                </tbody>
            </table>
        </div>
    </div>
</main>


<!-- ... scripts existentes ... -->
    <!-- enlace a la hoja javascript donde se maneja funcionalidad y animaciones -->
    <script src="./js/ventas.js"></script>
</body>
</html>

