<?php 
require_once ('Templates/headInicio.php')
?>
    <title>PuppyShop - Citas</title>    <!-- Título en la pestaña del navegador -->
    
    <link rel="stylesheet" href="css/citas.css" />      <!-- Estilos específicos de citas -->
    <?php 
require_once ('Templates/navVarInicio.php')
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

    <!-- Sección: Términos y condiciones para agendar citas -->
    <section class="terms-section">                     <!-- Contenedor principal de términos -->
        <div class="terms-content">                     <!-- Contenedor del contenido de términos -->
            <h2>Para Agendar con Nosotros</h2>          <!-- Título de la sección -->
            <div class="terms-text">                    <!-- Contenedor del texto de términos -->
                <p class="intro-text-terms">Antes de agendar tu cita, ten en cuenta:</p>   <!-- Texto introductorio con nueva clase -->
                <br>
                <ul>                                    <!-- Lista de requisitos -->
                    <li><i class="ri-check-line"></i> Debes iniciar sesión para agendar cita</li>   
                    <li><i class="ri-check-line"></i> Llega 10 minutos antes de tu cita</li>      
                    <li><i class="ri-check-line"></i> Trae el carnet de vacunación</li>
                    <li><i class="ri-check-line"></i> Solo un acompañante por mascota</li>
                    <li><i class="ri-check-line"></i> Tolerancia máxima de espera: 10 minutos</li>          
                    <li><i class="ri-check-line"></i> En caso de cancelar la cita. Cancela con 24 horas de anticipación</li>
                    <li><i class="ri-check-line"></i> Los servicios son exclusivamente para caninos</li>      
                              
                </ul>
                <a href="sesion.php"><button class="design-now">Agendar Ahora</button></a>
            </div>
        </div>
        <div class="terms-images swiper">               <!-- Contenedor del carrusel de imágenes -->
            <div class="swiper-wrapper">                <!-- Contenedor de slides del carrusel -->
                <div class="swiper-slide">              <!-- Slide 1 -->
                    <img src="assets/citas1.png" alt="Veterinary Care">    <!-- Imagen 1 -->
                </div>
                <div class="swiper-slide">              <!-- Slide 2 -->
                    <img src="assets/citas2.png" alt="Pet Care">         <!-- Imagen 2 -->
                </div>
                <div class="swiper-slide">              <!-- Slide 3 -->
                    <img src="assets/citas3.png" alt="Professional Service">    <!-- Imagen 3 -->
                </div>
            </div>
            <div class="swiper-pagination"></div>       <!-- Paginación del carrusel -->
        </div>
    </section>



    <?php 
require_once ('Templates/footerInicio.php')
?>
    <script src="js/citas.js"></script>                                    <!-- Script personalizado para la página de citas -->
</body>

</html>