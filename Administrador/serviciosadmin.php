

<!-- filepath: c:\wamp64\www\veterinaria\Administrador\serviciosadmin.php -->
<?php 
require_once('../conexion.php'); // Incluye la conexión a la base de datos
require_once('../Templates/headAdmin.php');
?>
<title>Puppy Shop - Administrador</title>
<link rel="stylesheet" href="./css/serviciosadmin.css"> <!-- Estilos específicos de servicios -->

<?php 
require_once('../Templates/infoadmin.php'); // Barra de información del administrador
require_once('../Templates/navbarAdmin.php'); // Menú de navegación
?>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<main class="main-content">
    <div class="services-admin-container">
        <div class="services-header">
            <h1>Gestión de Servicios</h1>
            <p>Administra los planes y precios de cada servicio</p>
        </div>

      
        <div class="panel-header">
                    <h2>Planes de Veterinaria</h2>
                    <button class="add-plan-btn">
                        <i class="fas fa-plus"></i>
                        Nuevo Plan
                    </button>
                </div>
                <div class="services-tabs">
    <?php
    // Consulta para obtener los tipos de servicios
    $query_tipos = "SELECT * FROM tipos_servicios";
    $result_tipos = mysqli_query($conexion, $query_tipos);

    // Generar los botones de las pestañas
    while ($tipo = mysqli_fetch_assoc($result_tipos)) {
        echo '<button class="tab-btn" data-panel="' . $tipo['id_tip_serv'] . '-panel">';
        echo '<i class="fas fa-' . strtolower($tipo['nom_tip_serv']) . '"></i>';
        echo $tipo['nom_tip_serv'];
        echo '</button>';
    }
    ?>
</div>

<!-- Contenedor de Paneles -->
<?php
// Reinicia el puntero de la consulta para volver a iterar
mysqli_data_seek($result_tipos, 0);

while ($tipo = mysqli_fetch_assoc($result_tipos)) {
    $id_tip_serv = $tipo['id_tip_serv'];
    $nom_tip_serv = $tipo['nom_tip_serv'];

    echo '<div id="' . $id_tip_serv . '-panel" class="service-panel">';
    echo '<div class="panel-header">';
    echo '<h2>Planes de ' . $nom_tip_serv . '</h2>';
    echo '</div>';

    // Carrusel de Bootstrap
    echo '<div id="carousel-' . $id_tip_serv . '" class="carousel slide" data-bs-ride="carousel">';
    echo '<div class="carousel-inner plans-grid">'; // Agrega la clase .plans-grid aquí

    echo '</div>'; // Cierra carousel-inner
    echo '</div>'; // Cierra carousel
    echo '</div>'; // Cierra service-panel
}
?>
    </div>
</main>

<div id="edit-product-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Nuevo Servicio</h3>
            <span class="close" onclick="document.getElementById('edit-product-modal').style.display='none'">&times;</span>
        </div>
        <div class="modal-body">
            <form id="edit-product-form" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="id_product" id="edit-id-product">
                
                <div class="input-group">
                    <label>Nombre del servicio</label>
                    <input type="text" name="nom_product" id="edit-nom-product" placeholder="Nombre del producto" required>
                </div>
                
                <div class="input-group">
                    <label>Tipo de servicio</label>
                    <select name="id_cat" id="edit-id-cat" required>
                        <option value="">Seleccionar categoría</option>
                        <?php
                        // Consulta para llenar el select con los tipos de servicios
                        mysqli_data_seek($result_tipos, 0);
                        while ($tipo = mysqli_fetch_assoc($result_tipos)) {
                            echo "<option value='" . $tipo['id_tip_serv'] . "'>" . $tipo['nom_tip_serv'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="input-group">
                    <label>Precio</label>
                    <input type="number" name="prec_product" id="edit-prec-product" placeholder="Precio" required>
                </div>

                <div class="input-group">
                    <label>Descripción</label>
                    <textarea name="descrip_product" id="edit-descrip-product" placeholder="Descripción del servicio" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="document.getElementById('edit-product-modal').style.display='none'">Cancelar</button>
            <button type="button" id="btn-save" data-mode="create" class="btn-save">Guardar</button>
        </div>
    </div>
</div>

<script>
    function abrirModalNuevoPlan(idTipServ) {
        document.getElementById('edit-id-cat').value = idTipServ; // Selecciona automáticamente el tipo de servicio
        document.getElementById('edit-product-modal').style.display = 'block';
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="./js/serviciosadmin.js"></script>
</body>
</html>