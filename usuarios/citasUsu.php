<?php 
require_once ('../Templates/headUsu.php')
?>
    <title>PuppyShop - Citas</title>    <!-- Título en la pestaña del navegador -->
    
    <link rel="stylesheet" href="css/citas.css" />      <!-- Estilos específicos de citas -->
    <?php 
require_once ('../Templates/navVarusu.php')
?>
    <!-- Sección: Hero - Banner principal de la página de citas -->
    <section class="appointment-hero">              <!-- Contenedor principal del banner -->
        <div class="hero-content">                  <!-- Contenedor del contenido textual -->
            <h1>Confía su cuidado, agenda ahora</h1>    <!-- Título principal -->
        </div>
        <div class="hero-video">                    <!-- Contenedor del video de fondo -->
            <video id="tutorial-video" muted loop playsinline>    <!-- Video autoreproducible sin sonido -->
                <source
                    src="assets/CACHORROS TIERNOS Y BONITOS 🧡 ¡Vídeos de Perros Cachorros! - ExpertoAnimal (720p, h264).mp4"
                    type="video/mp4">                <!-- Fuente del video -->
            </video>
            <button class="sound-toggle">           <!-- Botón para activar/desactivar sonido -->
                <i class="ri-volume-mute-line"></i>  <!-- Icono de volumen silenciado -->
            </button>
        </div>
    </section>


    <!-- Sección: Calendario para agendar citas -->
    <section class="calendar-section">                <!-- Contenedor principal del calendario -->
        <div class="section-header">                  <!-- Encabezado de la sección -->
            <h2 class="section-title">Agenda Ya</h2>  <!-- Título de la sección -->
        </div>
        <div class="calendar-wrapper">                <!-- Contenedor del calendario y decoraciones -->
            <div class="calendar-decoration left">    <!-- Decoración izquierda -->

            </div>
            <div class="calendar-container">          <!-- Contenedor del calendario -->
                <input type="date" id="date-picker" class="date-input">  <!-- Selector de fecha nativo -->
                <div class="calendar-view">           <!-- Vista personalizada del calendario -->
                    <div class="calendar-header">      <!-- Encabezado del calendario -->
                        <button class="nav-btn prev-month">  <!-- Botón mes anterior -->
                        </button>
                        <h3 class="current-month">Julio 2024</h3>  <!-- Mes actual -->
                        <button class="nav-btn next-month">  <!-- Botón mes siguiente -->
                        </button>
                    </div>
                    <div class="calendar-grid">        <!-- Cuadrícula del calendario -->
                        <div class="weekdays">         <!-- Días de la semana -->
                            <span>Lun</span><span>Mar</span><span>Mié</span>
                            <span>Jue</span><span>Vie</span><span>Sáb</span>
                            <span>Dom</span>
                        </div>
                        <div class="days"></div>       <!-- Contenedor de días del mes -->
                    </div>
                </div>
                <div class="calendar-legend">          <!-- Leyenda del calendario -->
                    <div class="legend-item">          <!-- Estado: Disponible -->
                        <span class="status-dot available"></span>
                        <span>Disponible</span>
                    </div>
                    <div class="legend-item">          <!-- Estado: No Disponible -->
                        <span class="status-dot unavailable"></span>
                        <span>Sin Cupos</span>
                    </div>
                    <div class="legend-item">          <!-- Estado: Sin Servicio -->
                        <span class="status-dot closed"></span>
                        <span>Sin Servicio</span>
                    </div>
                </div>
            </div>
            <div class="calendar-decoration right">    <!-- Decoración derecha -->
            </div>
        </div>
        <div class="button-wrapper">                  <!-- Contenedor del botón de agenda -->
            <button class="schedule-btn" disabled>Agendar Cita</button>  <!-- Botón para agendar -->
        </div>
    </section>

    <?php 
require_once ('../Templates/footerInicio.php')
?>
    <script src="js/citas.js"></script>  
    <!-- Modal para agregar cita -->
    <div id="addAppointmentModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Agregar Cita</h2>
            <form id="addAppointmentForm">
                <input type="hidden" id="loggedInUserId" value="<?php echo $_SESSION['id'] ?? ''; ?>">

                <label for="appointmentDate">Fecha:</label>
                <input type="text" id="appointmentDate" name="appointmentDate" readonly required>

                <label for="appointmentTime">Hora:</label>
                <select id="appointmentTime" name="appointmentTime" required>
                    <option value="">Seleccionar hora</option>
                </select>

                <label for="appointmentService">Servicio:</label>
                <select name="appointmentService" id="appointmentService" required>
                    <option value="">Seleccionar servicio</option>
                    <?php
                    // ... (Tu código PHP para los servicios) ...
                    ?>
                </select>

                <label for="appointmentSize">Tamaño:</label>
                <select name="appointmentSize" id="appointmentSize" required>
                    <option value="">Seleccionar tamaño</option>
                    <?php
                    // ... (Tu código PHP para los tamaños) ...
                    ?>
                </select>

                <label for="appointmentDescription">Descripción:</label>
                <textarea id="appointmentDescription" name="appointmentDescription"></textarea>

                <input type="hidden" id="loggedInUserName" value="<?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?>">

                <button type="submit">Guardar Cita</button>
            </form>
        </div>
    </div>
    </div>                              <!-- Script personalizado para la página de citas -->
</body>

</html>