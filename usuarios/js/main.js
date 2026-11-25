/* ==================== NAVEGACIÓN MÓVIL ==================== */
// Obtener elementos del DOM para el menú móvil
const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

// Manejador de eventos para el botón de menú
menuBtn.addEventListener("click", (e) => {
  navLinks.classList.toggle("open");
  // Cambiar el ícono según el estado del menú
  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

// Cerrar menú al hacer clic en un enlace
navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

/* ==================== CONFIGURACIÓN DE SCROLL REVEAL ==================== */
// Opciones base para las animaciones
const scrollRevealOption = {
  distance: "50px",
  origin: "bottom",
  duration: 1000,
};

/* ==================== ANIMACIONES DEL HEADER ==================== */
// Animación secuencial de elementos del header
ScrollReveal().reveal(".header__content h4", {
  ...scrollRevealOption,
});
ScrollReveal().reveal(".header__content h1", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".header__content h2", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".header__content p", {
  ...scrollRevealOption,
  delay: 1500,
});
ScrollReveal().reveal(".header__btn", {
  ...scrollRevealOption,
  delay: 2000,
});

/* ==================== ANIMACIONES DE SECCIONES ==================== */
// Animación de tarjetas de introducción
ScrollReveal().reveal(".intro__card", {
  ...scrollRevealOption,
  interval: 500,
});

// Animaciones de la sección About
ScrollReveal().reveal(
  ".about__row:nth-child(3) .about__image img, .about__row:nth-child(5) .about__image img",
  {
    ...scrollRevealOption,
    origin: "left",
  }
);
ScrollReveal().reveal(".about__row:nth-child(4) .about__image img", {
  ...scrollRevealOption,
  origin: "right",
});
ScrollReveal().reveal(".about__content span", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".about__content h4", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".about__content p", {
  ...scrollRevealOption,
  delay: 1500,
});

// Animación de productos
ScrollReveal().reveal(".product__card", {
  ...scrollRevealOption,
  interval: 500,
});

// Animación de servicios
ScrollReveal().reveal(".service__card", {
  duration: 1000,
  interval: 500,
});

/* ==================== CONFIGURACIÓN DEL SLIDER ==================== */
const swiper = new Swiper(".swiper", {
  slidesPerView: 1,
  spaceBetween: 20,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    800: {
      slidesPerView: 3,
      spaceBetween: 30,
    },
    600: {
      slidesPerView: 2,
      spaceBetween: 20,
    },
  },
  loop: true,
});