<!-- link al head  -->
<?php 
require_once ('../Templates/headAdmin.php')
?>
    <title>Puppy Shop - Administrador</title>
    <link rel="stylesheet" href="./css/personal.css">       <!-- Estilos específicos de la página de personal -->
<!-- link a la barra de informacion del administrador   -->
<?php 
require_once ('../Templates/infoadmin.php')
?>

<!-- link al menu de navegacion  -->
<?php 
require_once ('../Templates/navbarAdmin.php')
?>
        
        <!-- contenido principal -->
        <main class="main-content">
            <div class="personal-container">
                <!-- Tabs de navegación -->
                <div class="personal-tabs">
                    <button class="tab-btn active" data-panel="admins">
                        <i class="fas fa-user-shield"></i>
                        Administradores
                    </button>
                    <button class="tab-btn" data-panel="team">
                        <i class="fas fa-user-md"></i>
                        Equipo Veterinario
                    </button>
                </div>

                <!-- Contenedor de paneles -->
                <div class="panels-container">
                    <!-- Panel de Administradores -->
                    <div id="admins-panel" class="panel active">
                        <div class="panel-header">
                            <h2>Gestión de Administradores</h2>
                            <button class="add-btn">
                                <i class="fas fa-plus"></i>
                                Nuevo Administrador
                            </button>
                        </div>

                        <div class="search-filters">
                            <div class="search-bar">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Buscar administrador...">
                            </div>
                            <select class="filter-select">
                                <option value="all">Todos los estados</option>
                                <option value="active">Activos</option>
                                <option value="inactive">Inactivos</option>
                            </select>
                        </div>

                        <div class="admins-grid">
                            <?php
                            require_once('../conexion.php');
                            $query = "SELECT * FROM usuario WHERE id_rol = 1";
                            $result = mysqli_query($conexion, $query);

                            while($admin = mysqli_fetch_assoc($result)) {
                            ?>
                            <div class="admin-card" data-admin-id="<?php echo $admin['documento_usu']; ?>">
                                <div class="admin-header">
                                    <div class="admin-avatar">
                                        <div class="profile-initial">
                                            <?php echo strtoupper(substr($admin['nom_usu'], 0, 1)); ?>
                                        </div>
                                        <span class="status-badge active"></span>
                                    </div>
                                    <div class="admin-main-info">
                                        <h3><?php echo $admin['nom_usu'] . ' ' . $admin['apell_usu']; ?></h3>
                                        <span class="admin-role">Super Admin</span>
                                    </div>
                                </div>
                                <div class="admin-contact">
                                    <p><i class="fas fa-phone"></i> <?php echo $admin['num_usu']; ?></p>
                                    <p><i class="fas fa-envelope"></i> <?php echo $admin['correo_usu']; ?></p>
                                </div>
                                <div class="admin-status">
                                    <div class="status-toggle">
                                        <input type="checkbox" id="status<?php echo $admin['documento_usu']; ?>" <?php echo ($admin['id_estado'] == 1) ? 'checked' : ''; ?>>
                                        <label class="slider" for="status<?php echo $admin['documento_usu']; ?>">Activo</label>
                                    </div>
                                </div>
                                <div class="admin-actions">
                                    <button class="action-btn edit-btn" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn info-btn" title="Ver más">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Panel de Equipo Veterinario -->
                    <div id="team-panel" class="panel">
                        <!-- El contenido del equipo veterinario se mantiene en su propia sección -->
                        <div class="panel-header">
                            <h2>Nuestro Equipo Veterinario</h2>
                            <button class="add-btn" id="nuevo-miembro-btn">
                                <i class="fas fa-plus"></i>
                                Nuevo Miembro
                            </button>
                        </div>
                        
                        <div class="search-filters">
                            <div class="search-bar">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Buscar miembro...">
                            </div>
                            <select class="filter-select">
                                <option value="all">Todas las especialidades</option>
                                <option value="general">Medicina General</option>
                                <option value="surgery">Cirugía</option>
                                <option value="dental">Odontología</option>
                            </select>
                        </div>
                        <div class="team-grid">
    <?php
    // Consulta para obtener todos los miembros del equipo veterinario
    $query_equipo = "SELECT * FROM equipo_vet";
    $result_equipo = mysqli_query($conexion, $query_equipo);

    while($miembro = mysqli_fetch_assoc($result_equipo)) {
        ?>
        <div class="team-card" data-member-id="<?php echo $miembro['num_doc']; ?>">
            <div class="member-header">
                <div class="member-avatar">
                    <?php if(!empty($miembro['foto_eqp'])): ?>
                        <img src="/veterinaria/<?php echo $miembro['foto_eqp']; ?>"
                             alt="Dr(a). <?php echo $miembro['nom_eqp']; ?>">
                    <?php else: ?>
                        <img src="/veterinaria/assets/img/default-profile.jpg"
                             alt="Dr(a). <?php echo $miembro['nom_eqp']; ?>">
                    <?php endif; ?>
                </div>
                <div class="member-info">
                    <h3>Dr(a). <?php echo $miembro['nom_eqp'] . ' ' . $miembro['apell_eqp']; ?></h3>
                    <span class="member-role"><?php echo $miembro['prop_eqp']; ?></span>
                </div>
            </div>
            <div class="member-contact">
                <p><i class="fas fa-phone"></i> <?php echo $miembro['telefono_eqp']; ?></p>
                <p><i class="fas fa-envelope"></i> <?php echo $miembro['correo_eqp']; ?></p>
            </div>
            <div class="member-actions">
                <button class="action-btn edit-btn" data-id="<?php echo $miembro['num_doc']; ?>" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn info-btn" data-id="<?php echo $miembro['num_doc']; ?>" title="Ver más">
                    <i class="fas fa-info-circle"></i>
                </button>
                <button class="action-btn delete-btn" data-id="<?php echo $miembro['num_doc']; ?>" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <?php } ?>
</div>                 <!-- ... resto del código ... -->
                    </div>
                </div>
            </div>
        </main>

        <!-- Modales -->
        <div class="modal" id="admin-modal">
            <!-- Contenido del modal para administradores -->
        </div>
        <!-- En la sección de modales -->
        <!-- Modal para nuevo miembro del equipo -->
        <div id="team-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Registrar Nuevo Miembro del Equipo</h3>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="team-register-form" enctype="multipart/form-data" method="POST" action="../procesar_equipo.php">
                        <div class="input-group">
                            <input type="text" name="num_doc" placeholder="Número de documento" required>
                        </div>
                        <div class="input-group">
                            <select name="id_tipo_doc" required>
                                <option value="">Tipo de documento</option>
                                <?php
                                $query_tipos = "SELECT * FROM tipo_doc_eqp";
                                $result_tipos = mysqli_query($conexion, $query_tipos);
                                while($tipo = mysqli_fetch_assoc($result_tipos)) {
                                    echo "<option value='".$tipo['id_tipo_doc']."'>".$tipo['nom_tipo_doc']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" name="nom_eqp" placeholder="Nombre" required>
                        </div>
                        <div class="input-group">
                            <input type="text" name="apell_eqp" placeholder="Apellido" required>
                        </div>
                        <div class="input-group">
                            <input type="email" name="correo_eqp" placeholder="Correo electrónico" required>
                        </div>
                        <div class="input-group">
                            <input type="tel" name="telefono_eqp" placeholder="Teléfono" required>
                        </div>
                        <div class="input-group">
                            <input type="text" name="prop_eqp" placeholder="Especialidad" required>
                        </div>
                        <div class="input-group">
                            <textarea name="desc_eqp" class="professional-desc" placeholder="Descripción Profesional" required></textarea>
                        </div>
                        <div class="input-group">
                            <div class="input-group">
                                <label for="foto_eqp" class="profile-photo-btn">
                                    <i class="fas fa-camera"></i>
                                    <span>Agregar foto de perfil</span>
                                    <input type="file" id="foto_eqp" name="foto_eqp" accept="image/jpeg,image/png" style="display: none;">
                                </label>
                                <div id="preview-image"></div>
                                <div class="photo-size-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Tamaño recomendado: 400x400px (máx. 2MB) - Formato: JPG, PNG</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancel-btn">Cancelar</button>
                            <button type="submit" class="submit-btn">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <!-- Asegúrate de que estas líneas estén en el head -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/personal.js"></script>
    <script src="js/team.js"></script>
</body>
</html>

<!-- Modal para nuevo administrador -->
<div id="adminModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nuevo Administrador</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="newAdminForm">
                <div class="input-group">
                    <input type="number" name="documento_usu" id="documento_usu" placeholder="Número de documento" required>
                </div>
                <div class="input-group">
                    <select class="role-select" name="tipo_doc_usu" id="tipo_doc_usu" required>
                        <option value="" disabled selected>Tipo de documento</option>
                        <option value="CC">Cédula de Ciudadanía</option>
                        <option value="CE">Cédula de Extranjería</option>
                        <option value="TI">Tarjeta de Identidad</option>
                        <option value="PP">Pasaporte</option>
                    </select>
                </div>
                <div class="input-group">
                    <input type="text" name="nom_usu" id="nom_usu" placeholder="Nombre" required>
                </div>
                <div class="input-group">
                    <input type="text" name="apell_usu" id="apell_usu" placeholder="Apellido" required>
                </div>
                <div class="input-group">
                    <input type="number" name="num_usu" id="num_usu" placeholder="Número de contacto" required>
                </div>
                <div class="input-group">
                    <input type="text" name="direc_usu" id="direc_usu" placeholder="Dirección" required>
                </div>
                <div class="input-group">
                    <input type="email" name="correo_usu" id="correo_usu" placeholder="Correo electrónico" required>
                </div>
                <div class="input-group">
                    <input type="password" name="contra_usu" id="contra_usu" placeholder="Contraseña" required>
                    <i class="ri-eye-line toggle-password"></i>
                </div>
                <input type="hidden" name="id_rol" value="1">
                <input type="hidden" name="id_estado" value="1">
            </div>
            <div class="modal-footer">
                <button type="button" class="cancel-btn">Cancelar</button>
                <button type="submit" form="newAdminForm" class="submit-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Edición -->
<div id="editAdminModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Administrador</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editAdminForm" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="documento_usu" id="edit_documento_usu">
                <div class="input-group">
                    <label>Nombre</label>
                    <input type="text" id="edit_nombre" disabled>
                </div>
                <div class="input-group">
                    <label>Tipo Documento</label>
                    <input type="text" name="tipo_doc_usu" id="edit_tipo_documento" disabled>
                </div>
                <div class="input-group">
                    <label>Número Documento</label>
                    <input type="text" id="edit_num_documento" disabled>
                </div>
                <div class="input-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="correo" id="edit_correo" required>
                </div>
                <div class="input-group">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" id="edit_telefono" required>
                </div>
                <div class="input-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" id="edit_direccion" required>
                </div>
                <div class="input-group">
                    <label>Nueva Contraseña</label>
                    <div class="password-field">
                        <input type="password" name="password" id="edit_password">
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn">Cancelar</button>
                    <button type="button" class="submit-btn" id="saveEditBtn">Guardar Cambios</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Información -->
<div id="infoAdminModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Información del Administrador</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body" id="adminInfoContent">
        <!-- La información se cargará dinámicamente -->
        </div>
        <div class="modal-footer">
            <button class="cancel-btn">Cerrar</button>
        </div>
    </div>
</div>

<!-- Al final del archivo, antes de cerrar el body -->
<<div id="edit-team-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Miembro del Equipo</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
        <form id="edit-team-form" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="hidden" name="num_doc">
                </div>
                <div class="input-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo_eqp" required>
                </div>
                <div class="input-group">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono_eqp" required>
                </div>
                <div class="input-group">
                    <label>Especialidad</label>
                    <input type="text" name="prop_eqp" required>
                </div>
                <div class="input-group">
                    <label>Descripción</label>
                    <textarea name="desc_eqp"></textarea>
                </div>
                <div class="input-group">
                    <label for="foto_eqp2" class="profile-photo-btn">
                        <i class="fas fa-camera"></i>
                        <span>Agregar o cambiar foto de perfil</span>
                        <input type="file" id="foto_eqp2" name="foto_eqp2" accept="image/jpeg,image/png" style="display: none;">
                    </label>
                    <div id="preview-image"></div> <div class="photo-size-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Tamaño recomendado: 400x400px (máx. 2MB) - Formato: JPG, PNG</span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="cancel-btn">Cancelar</button>
            <button type="button" onclick="guardarCambiosMiembro()" class="submit-btn">Guardar</button>
                </div>
    </div>
</div>
<!-- Modal para ver información del miembro -->
<div id="info-team-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Información del Miembro</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <!-- El contenido se cargará dinámicamente -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel">Cerrar</button>
        </div>
    </div>
</div>

