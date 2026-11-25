</head>

<!-- ==================== ESTILOS PAAR EL PERFIL ==================== -->
<style>
/* ==================== ESTILOS DE PERFIL DE USUARIO ==================== */
.user-menu-container {
  position: relative;
  margin-left: 20px;
}

.user-avatar {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: var(--primary-color);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  font-weight: 600;
  color: var(--text-dark);
  text-transform: uppercase; /* Para asegurar que la inicial esté en mayúscula */
  cursor: pointer;
  transition: transform 0.3s ease;
}

.user-avatar:hover {
  transform: scale(1.05);
}

.user-dropdown {
  position: absolute;
  top: 120%;
  right: 0;
  min-width: 300px;
  background-color: var(--white);
  border-radius: 15px;
  padding: 20px;
  word-wrap: break-word; /* Para manejar emails largos */
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  z-index: 1000;
  display: none;
  animation: slideDown 0.3s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Ajuste para el avatar en el menú desplegable */
.user-info-header .user-avatar {
  width: 45px;  /* Mismo tamaño que el botón de perfil */
  height: 45px;
  aspect-ratio: 1/1; /* Asegura que sea perfectamente circular */
  font-size: 18px;
}

.user-info-header {
  display: flex;
  align-items: center;
  gap: 12px; /* Ajustado para mejor espaciado */
  padding-bottom: 15px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
  margin-bottom: 15px;
}

.user-info-header .user-avatar {
  width: 50px;
  height: 50px;
}

.user-info-text {
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 220px; /* Ajustamos el ancho máximo del texto */
}

.user-info-text h4 {
  font-size: 16px;
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-info-text p {
  font-size: 14px;
  color: var(--text-light);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-dropdown button {
  width: 100%;
  padding: 10px;
  margin-top: 10px;
  border: none;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.edit-btn {
  background-color: var(--primary-color);
  color: var(--text-dark);
}

.logout-btn {
  background-color: #fee4e2;
  color: #d92d20;
}

.edit-btn:hover {
  background-color: #c5bef0;
}

.logout-btn:hover {
  background-color: #fecdca;
}

#editUserForm {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

#editUserForm input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.save-btn {
    background-color: var(--primary-color);
    color: var(--text-dark);
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}

.cancel-btn {
    background-color: #fee4e2;
    color: #d92d20;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}

.save-btn:hover {
    background-color: #c5bef0;
}

.cancel-btn:hover {
    background-color: #fecdca;
}
</style>

<body>
    <!-- ==================== BARRA DE NAVEGACIÓN PRINCIPAL ==================== -->
    <nav>
        <section class="nav">
            <!-- Encabezado de navegación con logo y botón de menú móvil -->
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="#">Puppy Shop</a>
                </div>
                <!-- Botón de menú hamburguesa para móviles -->
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            
            <!-- Lista de enlaces de navegación -->
            <!-- Fix the incomplete button in navigation -->
            <ul class="nav__links" id="nav-links">
                <li><a href="inicioUsu.php">Inicio</a></li>
                <li><a href="serviciosUsu.php">Servicios</a></li>
                <li><a href="productosUsu.php">Productos</a></li>
                <li><a href="citasUsu.php">Citas</a></li>
                <!-- Reemplazar los dos contenedores de usuario por uno solo -->
                <!-- Reemplazar el contenedor de usuario -->
                <li class="user-menu-container">
                    <div class="user-avatar" onclick="toggleUserMenu()">
                        <?php echo strtoupper(substr($_SESSION['usuario_nombre'] ?? 'Usuario', 0, 1)); ?>
                    </div>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-info-header">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($_SESSION['usuario_nombre'] ?? 'Usuario', 0, 1)); ?>
                            </div>
                            <div class="user-info-text">
                                <h4><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></h4>
                                <p><?php echo htmlspecialchars($_SESSION['correo_usu'] ?? 'Correo no disponible'); ?></p>
                            </div>
                        </div>
                        <button class="edit-btn" onclick="mostrarFormularioEdicion('<?php echo htmlspecialchars($_SESSION['correo_usu'] ?? ''); ?>', '<?php echo htmlspecialchars($_SESSION['num_usu'] ?? ''); ?>', '<?php echo htmlspecialchars($_SESSION['direc_usu'] ?? ''); ?>')">
                            <i class="ri-edit-line"></i>
                            Editar Información
                        </button>
                        <button class="edit-btn" onclick="mostrarFormularioContrasena()">
                            <i class="ri-lock-line"></i>
                            Cambiar Contraseña
                        </button>
                        <form action="../logout.php" method="POST">
                            <button type="submit" class="logout-btn">
                                <i class="ri-logout-box-r-line"></i>
                                Salir
                            </button>
                        </form>
                    </div>
                </li>
            </ul>

        </section>
    </nav>

    <!-- ==================== SCRIPTS DEL PERFIL ==================== -->
    <script>
        // Cerrar el menú cuando se hace clic fuera de él
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userDropdown');
            const iconContainer = document.querySelector('.user-icon-container');

            // Verificar si los elementos existen antes de acceder a ellos
            if (menu && iconContainer) {
                if (!iconContainer.contains(event.target) && !menu.contains(event.target)) {
                    menu.style.display = 'none';
                }
            }
        });

        function toggleUserMenu() {
            const menu = document.getElementById("userDropdown");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
            event.stopPropagation();  // Evita que el clic se propague
        }
    </script>
        <script src="js/nav.js"></script>