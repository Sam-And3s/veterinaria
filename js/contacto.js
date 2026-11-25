// Add this to your existing JavaScript
// Carrusel social media
// Función para inicializar todos los carruseles de la página
function initializeCarousels() {
    // Selecciona todos los contenedores de carrusel en la página
    const carousels = document.querySelectorAll('.carousel-container');

    // Itera sobre cada carrusel encontrado
    carousels.forEach(carousel => {
        // Obtiene las diapositivas y el contenedor de puntos de navegación
        const slides = carousel.querySelectorAll('.carousel-slide');
        const dotsContainer = carousel.nextElementSibling;
        let currentSlide = 0;  // Control de la diapositiva actual

        // Crea los puntos de navegación para cada diapositiva
        slides.forEach((_, index) => {
            const dot = document.createElement('div');  // Crea elemento punto
            dot.classList.add('dot');                  // Añade clase 'dot'
            if (index === 0) dot.classList.add('active');  // Activa el primer punto
            dot.addEventListener('click', () => goToSlide(index));  // Añade evento click
            dotsContainer.appendChild(dot);  // Añade el punto al contenedor
        });

        // Activa la primera diapositiva por defecto
        slides[0].classList.add('active');

        // Función para cambiar entre diapositivas
        function goToSlide(index) {
            // Desactiva la diapositiva actual y su punto de navegación
            slides[currentSlide].classList.remove('active');
            dotsContainer.children[currentSlide].classList.remove('active');
            
            // Actualiza el índice de la diapositiva actual
            currentSlide = index;
            
            // Activa la nueva diapositiva y su punto de navegación
            slides[currentSlide].classList.add('active');
            dotsContainer.children[currentSlide].classList.add('active');
        }

        // Configura el cambio automático de diapositivas cada 5 segundos
        setInterval(() => {
            const nextSlide = (currentSlide + 1) % slides.length;  // Calcula la siguiente diapositiva
            goToSlide(nextSlide);  // Cambia a la siguiente diapositiva
        }, 5000);
    });
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function () {
    // Configuración inicial de ScrollReveal
    const sr = ScrollReveal({
        origin: 'bottom',          // Los elementos aparecen desde abajo
        distance: '60px',          // Distancia de desplazamiento
        duration: 1000,            // Duración de la animación en milisegundos
        delay: 200                 // Retraso antes de iniciar la animación
    });

    // Configuración de animaciones para diferentes elementos
    sr.reveal('.section-header', {});                    // Animación del encabezado
    sr.reveal('.social-card', { interval: 200 });        // Animación de tarjetas sociales con intervalo
    sr.reveal('.contact-card', { interval: 200 });       // Animación de tarjetas de contacto
    sr.reveal('.map-container', {});                     // Animación del contenedor del mapa
    sr.reveal('.location-info', { delay: 300 });         // Animación de información de ubicación

    // Efectos de hover para tarjetas sociales
    const socialCards = document.querySelectorAll('.social-card');
    socialCards.forEach(card => {
        // Efecto al pasar el mouse por encima
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px)';   // Eleva la tarjeta
        });

        // Efecto al retirar el mouse
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';       // Regresa a posición original
        });
    });

    // Inicializar carruseles básicos
    initializeCarousels();

    // Inicializar Swiper para instalaciones con configuración avanzada
    const facilitiesSwiper = new Swiper('.facilities-carousel', {
        effect: 'fade',              // Efecto de desvanecimiento entre slides
        fadeEffect: {
            crossFade: true          // Habilita el desvanecimiento cruzado
        },
        loop: true,                  // Permite la rotación infinita
        speed: 800,                  // Velocidad de transición en milisegundos
        autoplay: {
            delay: 6000,             // Tiempo entre slides (6 segundos)
            disableOnInteraction: false, // Mantiene autoplay después de interacción
        },
        pagination: {
            el: '.swiper-pagination', // Elemento para los puntos de navegación
            clickable: true,          // Permite clic en los puntos
        },
        navigation: {
            nextEl: '.swiper-button-next', // Botón siguiente
            prevEl: '.swiper-button-prev', // Botón anterior
        }
    });

    // Control de autoplay al pasar el mouse (dentro del DOM ready)
    const swiperContainer = document.querySelector('.facilities-carousel');
    if (swiperContainer) {
        swiperContainer.addEventListener('mouseenter', () => {
            if (facilitiesSwiper.autoplay) facilitiesSwiper.autoplay.stop();  // Pausa al entrar
        });
        swiperContainer.addEventListener('mouseleave', () => {
            if (facilitiesSwiper.autoplay) facilitiesSwiper.autoplay.start(); // Reanuda al salir
        });
    }
});

// Control de autoplay adicional (fuera del DOM ready) - Nota: Este código parece duplicado
const swiperContainer = document.querySelector('.facilities-swiper');
swiperContainer.addEventListener('mouseenter', () => {
    facilitiesSwiper.autoplay.stop();    // Pausa al entrar
});
swiperContainer.addEventListener('mouseleave', () => {
    facilitiesSwiper.autoplay.start();   // Reanuda al salir
});