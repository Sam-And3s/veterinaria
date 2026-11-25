<?php 
require_once ('Templates/headInicio.php')
?>
    <title>PuppyShop - Nosotros</title>
      <!-- Estilos globales del sitio -->
    <link rel="stylesheet" href="css/nosotros.css" />
<?php 
require_once ('Templates/navVarInicio.php')
?>

    <!-- Sección Hero con video -->
    <section class="about-hero">
        <div class="hero-content">
            <!-- Contenedor de video con animación -->
            <div class="video-container" data-aos="fade-right">
                <video autoplay muted loop>
                    <source
                        src="assets/CACHORROS TIERNOS Y BONITOS 🧡 ¡Vídeos de Perros Cachorros! - ExpertoAnimal (720p, h264).mp4"
                        type="video/mp4">
                </video>
            </div>
            <!-- Texto principal con animación -->
            <div class="hero-text" data-aos="fade-left">
                <h1>Cuidamos lo que amas</h1>
            </div>
        </div>
    </section>

     <!-- Sección Quiénes Somos -->
    <section class="section about-section">
        <div class="about-container">
            <div class="about-content" data-aos="fade-right">
                <h2>Quiénes Somos</h2>
                <p class="lead-text">En PuppyShop creemos que cada perro merece atención, respeto y mucho amor. Somos un equipo de profesionales apasionados por el bienestar canino, comprometidos con ofrecer servicios de calidad que cubran tanto su salud como su cuidado estético.

                Contamos con veterinarios y groomers certificados, con amplia experiencia en el trato con mascotas y un enfoque basado en la empatía, la responsabilidad y la excelencia. Nuestro objetivo es que cada visita, cada consulta y cada sesión de spa sea una experiencia positiva tanto para tu perro como para ti.

                Más que un spa canino, somos un espacio de confianza donde tu compañero peludo recibe el cuidado que necesita y el cariño que merece.</p>
            </div>
            <div class="about-image" data-aos="fade-left">
                <img src="assets/quienesomos.png" alt="Equipo veterinario">
            </div>
        </div>
    </section>

    <!-- Sección de Historia con carrusel y línea de tiempo -->
    <section class="section history-section">
        <div class="history-container">
            <!-- Carrusel de imágenes históricas con animación -->
            <div class="history-carousel" data-aos="fade-right">
                <!-- Slides del carrusel -->
                <div class="carousel-slide active">
                    <img src="assets/historia-1.png" alt="Historia 1">
                </div>
                <div class="carousel-slide">
                    <img src="assets/historia-2.png" alt="Historia 2">
                </div>
                <div class="carousel-slide">
                    <img src="assets/historia-3.png" alt="Historia 3">
                </div>
            </div>
            <!-- Contenido de la historia con animación -->
            <div class="history-content" data-aos="fade-left">
                <h2>Nuestra Historia y Trayectoria</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <h3><img src="assets/logro.png" alt="icono timeline" class="timeline-icon-img">2018</h3>
                        <p>Fundación de PuppyShop y apertura del primer centro especializado en estética canina.</p>
                    </div>
                    <div class="timeline-item">
                        <h3><img src="assets/logro.png" alt="icono timeline" class="timeline-icon-img">2021</h3>
                        <p>Integración de servicio veterinario con personal clínico certificado y área médica propia.</p>
                    </div>
                    <div class="timeline-item">
                        <h3><img src="assets/logro.png" alt="icono timeline" class="timeline-icon-img">2023</h3>
                        <p>Implementación del servicio a domicilio, mejorando la cobertura y comodidad para los clientes.</p>
                    </div>
                    <div class="timeline-item">
                        <h3><img src="assets/logro.png" alt="icono timeline" class="timeline-icon-img">2025</h3>
                        <p>Lanzamiento oficial de nuestra plataforma digital con agendamiento en línea, tienda virtual y asesoría remota.</p>
                    </div>
                </div>
                <a href="#" class="know-more-btn">Saber más</a>
            </div>
        </div>
    </section>

    <!-- Sección de Misión con video -->
    <section class="mission-section" data-aos="fade-up" id="mission-section">
        <div class="mission-container">
            <div class="mission-content">
                <div class="mission-text">
                    <h2 class="section-title">Nuestra Misión</h2>
                    <p>EEn PuppyShop, creemos que cada perro merece mucho más que cuidados básicos: merece atención, respeto y amor verdadero. Nuestra misión es ofrecer una experiencia integral que combine salud, bienestar y belleza en un solo lugar, poniendo siempre en primer lugar la seguridad y felicidad de tu mascota.

                    Nos esforzamos por brindar servicios veterinarios de calidad, atención estética especializada y productos seleccionados cuidadosamente, todo a cargo de un equipo humano comprometido, capacitado y apasionado por el mundo canino.

                    Trabajamos con profesionalismo, empatía y dedicación, para que cada familia que confía en nosotros sepa que su compañero de cuatro patas está en las mejores manos.

                    </p>
                </div>
                <!-- Video ilustrativo -->
                <div class="mission-video">
                    <video id="mission-video" loop>
                        <source
                            src="assets/CACHORROS TIERNOS Y BONITOS 🧡 ¡Vídeos de Perros Cachorros! - ExpertoAnimal (720p, h264).mp4"
                            type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Visión con imagen -->
    <section class="vision-section" data-aos="fade-up">
        <div class="vision-container">
            <div class="vision-content">
                <div class="vision-text">
                    <h2 class="section-title">Nuestra Visión</h2>
                    <p>Convertirnos en la empresa líder en el cuidado integral canino a nivel nacional, reconocida por la excelencia en nuestros servicios, la innovación continua y el compromiso profundo con el bienestar animal.

                    Aspiramos a transformar los estándares de atención en la industria veterinaria y estética, integrando tecnología, talento humano especializado y un enfoque centrado en la experiencia del cliente.

                    Nos proyectamos como una marca de referencia, capaz de generar impacto positivo en la calidad de vida de miles de mascotas y sus familias, construyendo una comunidad basada en la confianza, la empatía y el amor por los animales.

                    </p>
                </div>
                <div class="vision-image">
                    <img src="assets/visionvet.png" alt="Vision illustration">
                </div>
            </div>
        </div>
    </section>

    <!-- En la sección de equipo -->
    <section class="team-section" data-aos="fade-up">
        <div class="team-container">
            <h2 class="section-title">Nuestro Equipo</h2>
            <div class="team-grid">
                <?php
                require_once('conexion.php');
                $query = "SELECT * FROM equipo_vet";
                $result = mysqli_query($conexion, $query);
    
                while($miembro = mysqli_fetch_assoc($result)) {
                    $foto = $miembro['foto_eqp'] ? '/veterinaria/'.$miembro['foto_eqp'] :'/veterinaria/assets/img/default-profile.jpg';
                ?>  
<div class="team-card" 
     data-member="<?php echo $miembro['num_doc']; ?>" 
     data-name="<?php echo $miembro['nom_eqp'] . ' ' . $miembro['apell_eqp']; ?>" 
     data-role="<?php echo $miembro['prop_eqp']; ?>" 
     data-description="<?php echo $miembro['desc_eqp']; ?>" 
     data-email="<?php echo $miembro['correo_eqp']; ?>" 
     data-phone="<?php echo $miembro['telefono_eqp']; ?>" 
     data-photo="<?php echo $foto; ?>">
    <div class="team-card-inner">
        <div class="card-image">
            <img src="<?php echo $foto; ?>" alt="<?php echo $miembro['nom_eqp']; ?>">
        </div>
        <div class="card-content">
            <h3>Dr. <?php echo $miembro['nom_eqp'] . ' ' . $miembro['apell_eqp']; ?></h3>
            <p><?php echo $miembro['prop_eqp']; ?></p>
        </div>
    </div>
</div>
                <?php } ?>
            </div>
        </div>
    
        <!-- Modal para mostrar información detallada -->
        <div class="member-modal" id="member-info-modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="member-details">
                    <!-- La información se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para detalles del miembro -->
    <div class="member-modal" id="memberModal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-inner">
                <div class="modal-image">
                    <img src="" alt="Miembro del equipo" id="modalImage">
                </div>
                <div class="modal-info">
                    <h3 id="modalName"></h3>
                    <p class="role" id="modalRole"></p>
                    <div class="description" id="modalDescription"></div>
                    <div class="experience-list">
                        <div class="experience-item">
                            <div class="experience-icon">
                                <i class="ri-award-line"></i>
                            </div>
                            <div class="experience-text">
                                <h4>Experiencia Profesional</h4>
                                <p id="modalExperience"></p>
                            </div>
                        </div>
                        <div class="experience-item">
                            <div class="experience-icon">
                                <i class="ri-user-star-line"></i>
                            </div>
                            <div class="experience-text">
                                <h4>Especialización</h4>
                                <p id="modalSpecialty"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de nuestra sede -->
    <section class="location-section" data-aos="fade-up">
        <div class="location-container">
            <h2 class="section-title">Nuestra Sede</h2>
            <div class="location-content">
                <div class="location-carousel">
                    <div class="carousel-slide active">
                        <img src="assets/instalaciones1.png" alt="Sede 1">
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/instalaciones2.png" alt="Sede 2">
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/historia-3.png" alt="Sede 3">
                    </div>
                </div>
                <div class="location-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3977.8595054229477!2d-75.19530401317087!3d4.437247763882961!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e38c5a334ba6c0b%3A0x478b451e262070bd!2sPuppyShop%20online!5e0!3m2!1sen!2sco!4v1748121116955!5m2!1sen!2sco"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

<?php 
require_once ('Templates/footerInicio.php')
?>
    <script src="js/nosotros.js"></script>
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
        </div>
    </div>
</div>
</body>

</html>