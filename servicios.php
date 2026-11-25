<?php 
require_once ('Templates/headInicio.php')
?>
    <title>PuppyShop - Servicios</title>

    <link rel="stylesheet" href="css/servicios.css">

<?php 
require_once ('Templates/navVarInicio.php')
?>
    <!-- Sección principal con presentación de servicios -->
    <section class="services-hero">
        <div class="hero-container">
            <div class="hero-text">
                <h2>Nuestros</h2>
                <h2>Servicios</h2>
                <h2></h2>
                <p>Cada perro, una historia. Cada visita, un cuidado único</p>
                <a href="sesion.php"><button class="go-btn">Agenda ya</button></a>
            </div>
            <!-- Paneles de servicios con imágenes de fondo -->
            <div class="panels-container">
                <div class="panel" style="background-image: url('assets/serv1.jpg')">
                    <div class="overlay-text">
                        <h3>Spa Canino</h3>
                        <span>Renueva su energía, cuida su piel</span>
                    </div>
                </div>
                <div class="panel" style="background-image: url('assets/serv3.jpg')">
                    <div class="overlay-text">
                        <h3>Consulta Veterinaria</h3>
                        <span>Porque prevenir también es amar</span>
                    </div>
                </div>
                <div class="panel" style="background-image: url('assets/serv2.jpg')">
                    <div class="overlay-text">
                        <h3>Peluqueria Canina</h3>
                        <span>Higiene, estilo y actitud</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Sección de servicios de spa y bienestar -->
    <section class="service1">
        <div class="titu"><h2>Spa Canino</h2>
    </div>
        <div class="service-welcome">
            <div class="welcome-image">
                <img src="assets/spacanino.png" alt="Spa Canino">
            </div>
            <div class="welcome-content">
                <h2>caninos</h2>
                <p>En nuestro spa canino, tu mascota vive una experiencia de bienestar total. Aplicamos tratamientos de relajación y belleza diseñados para cuidar su piel, pelaje y estado emocional. Nuestro equipo de expertos utiliza productos premium y técnicas especializadas que no solo lo harán lucir increíble, sino sentirse renovado y feliz.</p>
            </div>
        </div>
        <div class="pricing-section">
            <h2>Nuestros Planes de Spa</h2>
            <p>Explora nuestros servicios y elige el plan perfecto para tu mascota</p>
            <div class="pricing-container">
            </div>
        </div>
    </section>
    

<section class="service2">
    <div class="service-welcome">
        <div class="welcome-content">
            <h2>Consulta Veterinaria</h2>
            <p>Cuidamos su salud con compromiso y experiencia, en todo momento. Nuestro equipo de veterinarios altamente capacitados brinda atención médica de calidad, desde chequeos preventivos hasta situaciones de urgencia. Estamos disponibles para ofrecer diagnósticos precisos, trato empático y soluciones eficaces que garanticen el bienestar de tu compañero canino.</p>
        </div>
        <div class="welcome-image">
            <img src="assets/consulta.png" alt="Clínica Veterinaria">
        </div>
    </div>

    <div class="pricing-section">
        <h2>Planes de Consulta Veterinaria</h2>
        <p>Selecciona el plan que mejor se adapte a las necesidades de tu mascota</p>
        <div class="pricing-container">
            </div>
    </div>
</section>

<section class="service3">
    <div class="service-welcome">
        <div class="welcome-image">
            <img src="assets/peluqueria.jpg" alt="Peluquería Canina">
        </div>
        <div class="welcome-content">
            <h2>Peluquería Canina Profesional</h2>
            <p>Estilo, higiene y cuidado en cada corte. En nuestra peluquería canina profesional, tu mascota recibe una atención personalizada a cargo de estilistas expertos que dominan las características y necesidades de cada raza. Realizamos cortes a medida, con técnicas seguras y productos de calidad, para que luzca impecable y se sienta cómodo en todo momento.</p>
        </div>
    </div>

    <div class="pricing-section">
        <h2>Servicios de Peluquería</h2>
        <p>Elige el servicio que mejor se adapte al estilo de tu mascota</p>

        <div class="pricing-container">
            </div>
    </div>
</section>

   

<?php 
require_once ('Templates/footerInicio.php')
?>
<!-- Script principal -->
<script src="js/servicios.js"></script>
</body>
</html>