<?php 
require_once ('Templates/headInicio.php')
?>
    <title>PuppyShop - Contacto</title>

    <!-- Enlace al archivo de estilos específicos de la página de contacto -->
    <link rel="stylesheet" href="css/contacto.css" />
    <?php 
require_once ('Templates/navVarInicio.php')
?>
    <!-- Sección principal (Hero) de la página de contacto -->
    <section class="contact-hero">
        <!-- Contenedor del texto principal -->
        <div class="hero-content">
            <!-- Título principal de la página -->
            <h1>Tu consulta, nuestra atención</h1>
            <!-- Subtítulo o descripción corta -->
            <p>Estamos aquí para ayudarte</p>
        </div>
        <!-- Contenedor de la imagen principal -->
        <div class="hero-image">
            <!-- Imagen principal del hero -->
            <img src="assets/serv-client.png" alt="Contact Hero">
        </div>
    </section>

   <!-- Sección de redes sociales -->
    <section class="social-section">
         <!-- Encabezado de la sección -->
        <div class="section-header">
            <!-- Título y descripción de la sección -->
            <h2>Síguenos en Redes</h2>
            <p>Conéctate con el mundo PuppyShop: tips, noticias y contenido pensado para ti y tu peludo.</p>
        </div>
        <!-- Cuadrícula de redes sociales -->
        <div class="social-grid">
             <!-- Columna de Facebook -->
            <div class="social-column">
                <!-- Tarjeta de enlace a Facebook -->
                <a href="https://www.facebook.com/puppyshop/" class="social-card facebook">
                    <i class="ri-facebook-fill"></i>
                    <h3>Facebook</h3>
                    <p>@PuppyShopVet</p>
                </a>
                 <!-- Carrusel de publicaciones de Facebook -->
                <div class="social-carousel facebook-frame">
                    <!-- Contenedor del carrusel -->
                    <div class="carousel-container">
                        <!-- Diapositivas individuales con imagen y texto superpuesto -->
                        <div class="carousel-slide">
                            <img src="assets/p-fb1.png" alt="Facebook Post 1">
                            <div class="post-overlay">
                                <p>Tu perro con estilo 🐕☀️</p>
                            </div>
                        </div>
                        <!-- ... más diapositivas similares ... -->
                        <div class="carousel-slide">
                            <img src="assets/p-fb2.png" alt="Facebook Post 2">
                            <div class="post-overlay">
                                <p>Agenda con nosotros ✨</p>
                            </div>
                        </div>
                        <div class="carousel-slide">
                            <img src="assets/p-fb3.png" alt="Facebook Post 3">
                            <div class="post-overlay">
                                <p>Cuidandolo contigo de la mano❤️</p>
                            </div>
                        </div>
                    </div>
                    <!-- Puntos de navegación del carrusel -->
                    <div class="carousel-dots"></div>
                </div>
            </div>
              <!-- Estructura similar para Instagram y TikTok -->
        <!-- Cada red social tiene su propia columna con tarjeta y carrusel -->
            <div class="social-column">
                <a href="https://www.instagram.com/puppyshop_online9/" class="social-card instagram">
                    <i class="ri-instagram-line"></i>
                    <h3>Instagram</h3>
                    <p>@PuppyShopVet</p>
                </a>
                <div class="social-carousel instagram-frame">
                    <div class="carousel-container">
                        <div class="carousel-slide">
                            <img src="assets/p-ig1.png" alt="Instagram Post 1">
                            <div class="post-overlay">
                                <p>Consejos para un cuidado unico 🐾💇‍♀️✨</p>
                            </div>
                        </div>
                        <div class="carousel-slide">
                            <img src="assets/p-ig2.png" alt="Instagram Post 2">
                            <div class="post-overlay">
                                <p>lo nuevo para tu canino 🐾✨</p>
                            </div>
                        </div>
                        <div class="carousel-slide">
                            <img src="assets/p-ig3.png" alt="Instagram Post 3">
                            <div class="post-overlay">
                                <p>el mejor servicio para consentir a tu canino 🐾✨❤️</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-dots"></div>
                </div>
            </div>

            <div class="social-column">
                <a href="#" class="social-card tiktok">
                    <i class="ri-tiktok-fill"></i>
                    <h3>TikTok</h3>
                    <p>@PuppyShopVet</p>
                </a>
                <div class="social-carousel tiktok-frame">
                    <div class="carousel-container">
                        <div class="carousel-slide">
                            <img src="assets/p-tk1.png" alt="TikTok Post 1">
                            <div class="post-overlay">
                                <p>Nuevos accesorios 🎾</p>
                            </div>
                        </div>
                        <div class="carousel-slide">
                            <img src="assets/p-tk2.png" alt="TikTok Post 2">
                            <div class="post-overlay">
                                <p>Celebra el dia de tu consentido 😂</p>
                            </div>
                        </div>
                        <div class="carousel-slide">
                            <img src="assets/p-tk3.png" alt="TikTok Post 3">
                            <div class="post-overlay">
                                <p>Promociones para ti ✨</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-dots"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de información de contacto -->
    <section class="contact-info">
        <!-- Encabezado de la sección con título y subtítulo -->
        <div class="section-header">
            <h2>Canales De Atencion</h2>
            <p>Comunicate con nosotros</p>
        </div>
        <!-- Contenedor principal de la información de contacto -->
        <div class="contact-container">
            <!-- Contenedor de la imagen decorativa -->
            <div class="contact-image">
                <img src="assets/perro-serv.png" alt="Perrito con teléfono">
            </div>
            <!-- Contenedor de las tarjetas de contacto -->
            <div class="contact-cards">
                <!-- Tarjeta de WhatsApp -->
                <div class="contact-card whatsapp">
                    <!-- Ícono de WhatsApp -->
                    <div class="card-icon">
                        <i class="ri-whatsapp-line"></i>
                    </div>
                    <!-- Contenido de la tarjeta de WhatsApp -->
                    <div class="card-content">
                        <h3>WhatsApp</h3>
                        <p>¿Tienes preguntas? Estamos listos para responderte en WhatsApp.</p>
                        <!-- Números de contacto de WhatsApp -->
                        <div class="contact-numbers">
                            <!-- Primer botón de contacto WhatsApp -->
                            <a href="https://wa.me/3163528464" class="contact-btn">
                                <i class="ri-customer-service-2-line"></i>
                                +57 316 3528464
                                <span class="tooltip">Contacto 1</span>
                            </a>
                            <!-- Segundo botón de contacto WhatsApp -->
                            <a href="https://wa.me/3150022863" class="contact-btn">
                                <i class="ri-customer-service-2-line"></i>
                                +57 315 0022863
                                <span class="tooltip">Contacto 2</span>
                            </a>
                        </div>
                    </div>
                    <!-- Decoración de la tarjeta -->
                    <div class="card-decoration">
                        <img src="assets/paw-print.png" alt="Huella" class="floating-paw">
                    </div>
                </div>
                <!-- Tarjeta de Email -->
                <div class="contact-card email">
                    <!-- Ícono de Email -->
                    <div class="card-icon">
                        <i class="ri-mail-line"></i>
                    </div>
                    <!-- Contenido de la tarjeta de Email -->
                    <div class="card-content">
                        <h3>Correo Electrónico</h3>
                        <p>Contáctanos y recibe respuesta rápida y personalizada</p>
                        <!-- Direcciones de correo electrónico -->
                        <div class="email-addresses">
                            <!-- Primer botón de contacto Email -->
                            <a href="mailto:puppyshop@gmail.com" class="contact-btn">
                                <i class="ri-mail-send-line"></i>
                                puppyshop@gmail.com
                                <span class="tooltip">Contacto 1</span>
                            </a>
                        </div>
                    </div>
                    <!-- Decoración de la tarjeta -->
                    <div class="card-decoration">
                        <img src="assets/paw-print.png" alt="Huella" class="floating-paw">
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                        <img src="assets/historia-2.png" alt="Sede 2">
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/instalaciones2.png" alt="Sede 3">
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
    <!-- Custom JavaScript file for contact page functionality -->
    <script src="js/contacto.js"></script>
</body>

</html>