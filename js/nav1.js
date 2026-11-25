document.addEventListener('DOMContentLoaded', function() {
    var nav = document.querySelector('.nav');
    var navLinks = nav.querySelectorAll('a');
    var sections = document.querySelectorAll('section');

    // Add scroll event listener to change navbar background
    window.addEventListener('scroll', function() {
        if (window.scrollY >50) { // Cambia el fondo después de hacer scroll 50px
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
});
