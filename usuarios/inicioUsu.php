<?php
require_once ('../Templates/headUsu.php')
?>

    <title>PuppyShop</title>

    <?php 
require_once ('../Templates/navVarusu.php')
?>
    <!-- ==================== HEADER / BANNER PRINCIPAL ==================== -->
    <header id="home">
        <div class="section__container header__container">
            <!-- Contenido textual del header -->
            <div class="header__content">
                <h4>Bienvenidos</h4>
                <h1>PUPPY<br /><span>SHOP</span></h1>
                <!-- Fix text accents in header -->
                <h2>Tu Estética Canina</h2>
                <p>
                En PuppyShop, tu perro es nuestra prioridad.
                Somos un spa canino que combina estética y atención veterinaria de alta calidad, todo en un solo lugar. Nuestro equipo de profesionales está comprometido con el bienestar, la salud y la imagen de tu mascota, ofreciéndote servicios personalizados y con todo el cariño que merece.
                Porque sabemos que no es solo una mascota… es parte de tu familia.
                </p>
            </div>
            <!-- Imágenes del header -->
            <div class="header__image">
                <img src="assets/header-bg.png" alt="header-bg" class="header__image-bg" />
                <img src="assets/header.png" alt="header" />
            </div>
        </div>
        <div class="header__bottom"></div>
    </header>
    
    <!-- ==================== SECCIÓN DE SERVICIOS ==================== -->
  <section id="servicios" class="section__container intro__container">
    <!-- Encabezados de la sección -->
    <p class="section__subheader">Nuestros</p>
    <h2 class="section__header">SERVICIOS</h2>
    <!-- Grid de tarjetas de servicios -->
    <div class="intro__grid">
    <!-- Tarjeta de servicio: Corte de spa -->
      <div class="intro__card">
        <div class="intro__image">
          <img src="assets/servicio-header1.png" alt="intro" />
        </div>
        <h4>SPA CANINO</h4>
        <p>
        Una experiencia de relajación total para tu peludo. Utilizamos productos de alta calidad y técnicas especializadas para reducir el estrés, hidratar su piel y dejar su pelaje limpio, brillante y saludable. Ideal para consentirlo como se merece.
        </p>
      </div>
      <!-- Tarjeta de servicio: peluqueria canina -->
      <div class="intro__card">
        <div class="intro__image">
          <img src="assets/servicio-header3.png" alt="intro" />
        </div>
        <h4>PELUQUERIA CANINA</h4>
        <p>
        Cortes personalizados según la raza, estilo y necesidades de tu mascota. Nuestro equipo de groomers profesionales cuida cada detalle para que tu perro luzca increíble, cómodo y feliz. Higiene, estilo y amor en cada sesión.
        </p>
      </div>
    <!-- Tarjeta de servicio: consulta veterinaria -->
      <div class="intro__card">
        <div class="intro__image">
          <img src="assets/servicio-header2.png" alt="intro" />
        </div>
        <h4>CONSULTA VETERINARIA</h4>
        <p>
        Cuidamos la salud de tu mejor amigo con atención médica de confianza. Nuestro equipo está listo para brindarte diagnósticos y tratamientos puntuales en un ambiente cálido y seguro. Tu perro en las mejores manos.
        </p>
      </div>
    </div>
  </section>

  
  <!-- ==================== SECCIÓN DE PRODUCTOS ==================== -->
  <section class="product" id="store">
    <div class="section__container product__container">
    <!-- Encabezados de productos -->
    <h2 class="section__header">¡Colitas felices, productos increíbles!</h2>
      <p class="section__subheader">Lo mejor para mimar a tu peludo.</p>
      <!-- Grid de productos -->
      <div class="product__grid">
      <!-- Tarjeta de producto: Cepillo -->
        <div class="product__card">
          <a href="productosUsu.php">
          <img src="assets/accesorios.png"  alt="product" />
          </a>
          <h4>Accesorios y Juguetes</h4>
          <p>
          Descubre una selección divertida y funcional de collares, correas, camas, juguetes interactivos y más.
          </p>
        </div>
        <!-- Más tarjetas de productos -->
        <div class="product__card">
        <a href="productosUsu.php">

          <img src="assets/salud.png" alt="product" />
          </a>
          <h4>Salúd y Limpieza</h4>
          <p>
          Productos de higiene, antiparasitarios, suplementos, medicamentos y artículos de cuidado diario recomendados por profesionales.
          </p>
        </div>
        <div class="product__card">
        <a href="productosUsu.php">
          <img src="assets/alimento.png" alt="product" />
          </a>
          <h4>Alimento</h4>
          <p>
          Ofrecemos croquetas, comida húmeda, dietas especiales y snacks saludables de las mejores marcas.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== SECCIÓN DE BENEFICIOS ==================== -->
  <section class="section__container service__container" id="service">
    <!-- Encabezados de beneficios -->
    <p class="section__subheader">BENEFICIOS</p>
    <h2 class="section__header">Siempre Es Mejor Preferirnos</h2>
    <!-- Contenedor flexible de beneficios -->
    <div class="service__flex">
    <!-- Tarjetas de beneficios -->
      <div class="service__card">
        <div>
          <img src="assets/b1.png" alt="service" />
        </div>
        <p>Productos de calidad</p>
      </div>
      <div class="service__card">
        <div>
          <img src="assets/b2.png" alt="service" />
        </div>
        <p>Seguridad en los servicio</p>
      </div>
      <div class="service__card">
        <div>
          <img src="assets/b3.png" alt="service" />
        </div>
        <p>Profesionales capacitados</p>
      </div>
      <div class="service__card">
        <div>
          <img src="assets/b4.png" alt="service" />
        </div>
        <p>Atención completa e inmediata</p>
      </div>
      <div class="service__card">
        <div>
          <img src="assets/b5.png" alt="service" />
        </div>
        <p>Servicios a domicilio</p>
      </div>
    </div>
  </section>

  

   <!-- ==================== SECCIÓN DE INSTAGRAM ==================== -->
  <section class="section__container instagram__container">
    <!-- Encabezados de redes sociales -->
    <p>SIGUENOS</p>
    <h2 class="section__header">Instagram</h2>
     <!-- Grid de imágenes de Instagram -->
    <div class="instagram__grid">
    <!-- Galería de imágenes -->
     <a class="igmage" href="https://www.instagram.com/puppyshop_online9/" target="_blank">
      <img src="assets/ig1.png" alt="instagram" />
      </a>
      <img src="assets/ig2.png" alt="instagram" />
      <img src="assets/ig4.png" alt="instagram" />
      <img src="assets/ig3.png" alt="instagram" />
    </div>
  </section>

<?php
require_once ('../Templates/footerInicio.php')
?>
</body>
</html>