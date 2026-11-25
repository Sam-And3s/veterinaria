// Carrusel automático
function initCarousel() {
    const slides = document.querySelectorAll('.carousel-slide');
    let currentSlide = 0;

    function nextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    setInterval(nextSlide, 4000);
}

// Efecto parallax suave
function initParallax() {
    window.addEventListener('scroll', () => {
        const sections = document.querySelectorAll('.section');
        sections.forEach(section => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3;
            section.style.backgroundPositionY = rate + 'px';
        });
    });
}

// Animación de huellas
function initPawPrints() {
    const pawPrints = document.querySelectorAll('.timeline-item');
    pawPrints.forEach(paw => {
        paw.addEventListener('mouseenter', () => {
            paw.querySelector('::before').style.transform = 'rotate(10deg) scale(1.2)';
        });
        paw.addEventListener('mouseleave', () => {
            paw.querySelector('::before').style.transform = 'rotate(0deg) scale(1)';
        });
    });
}

// Inicializar todas las animaciones
document.addEventListener('DOMContentLoaded', () => {
    initCarousel();
    initParallax();
    initPawPrints();
});

// Control de reproducción del video de misión
function handleMissionVideo() {
    const video = document.getElementById('mission-video');
    const section = document.getElementById('mission-section');

    // Crear un nuevo IntersectionObserver
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            // Cuando la sección sea visible
            if (entry.isIntersecting) {
                video.play();
                video.muted = false; // Activar el sonido
                video.volume = 1.0; // Establecer volumen al máximo
            } else {
                video.pause();
                video.muted = true;
            }
        });
    }, {
        threshold: 0.5 // La sección debe estar 50% visible para activar
    });

    // Observar la sección de misión
    if (section) {
        observer.observe(section);
    }
}

// Inicializar todas las funciones
document.addEventListener('DOMContentLoaded', () => {
    initCarousel();
    initParallax();
    initPawPrints();
    handleMissionVideo();
});

// Datos del equipo
const teamMembers = {
    member1: {
        name: "Dr. Juan Pérez",
        role: "Veterinario Principal",
        image: "/path-to-member-image-1.jpg",
        description: "Con más de 15 años de experiencia en medicina veterinaria, el Dr. Pérez lidera nuestro equipo médico con dedicación y experticia. Especializado en cirugía de pequeños animales y medicina preventiva."
    },
    member2: {
        name: "Dra. María González",
        role: "Especialista en Cirugía",
        image: "/path-to-member-image-2.jpg",
        description: "La Dra. González ha realizado más de 1,000 cirugías exitosas y es reconocida por su precisión y cuidado post-operatorio excepcional."
    },
    member3: {
        name: "Dr. Carlos Rodríguez",
        role: "Especialista en Rehabilitación",
        image: "/path-to-member-image-3.jpg",
        description: "Pionero en técnicas de rehabilitación animal, el Dr. Rodríguez ha ayudado a cientos de mascotas a recuperar su movilidad y calidad de vida."
    }
};

// Funcionalidad del modal
function initTeamModal() {
    const modal = document.getElementById('memberModal');
    const closeBtn = document.querySelector('.close-modal');
    const teamCards = document.querySelectorAll('.team-card');

    teamCards.forEach(card => {
        card.addEventListener('click', () => {
            const memberId = card.dataset.member;
            const member = teamMembers[memberId];

            document.getElementById('modalImage').src = member.image;
            document.getElementById('modalName').textContent = member.name;
            document.getElementById('modalRole').textContent = member.role;
            document.getElementById('modalDescription').textContent = member.description;

            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
}

// Inicializar todas las funcionalidades
document.addEventListener('DOMContentLoaded', () => {
    initTeamModal();
});

// Carousel functionality for location section
function initLocationCarousel() {
    const slides = document.querySelectorAll('.location-carousel .carousel-slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Change slide every 5 seconds
    setInterval(nextSlide, 5000);
}

// Initialize carousel when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    initLocationCarousel();
});document.addEventListener('DOMContentLoaded', function () {
    const teamCards = document.querySelectorAll('.team-card');
    const modal = document.getElementById('info-team-modal');
    const modalContent = modal.querySelector('.modal-body');
    const closeModalBtn = modal.querySelector('.close');

    // Agrega un evento de clic a cada tarjeta del equipo
    teamCards.forEach(card => {
        card.addEventListener('click', () => {
            // Obtén los datos del miembro desde los atributos de la tarjeta
            const memberName = card.getAttribute('data-name');
            const memberRole = card.getAttribute('data-role') || 'N/A';
            const memberDescription = card.getAttribute('data-description') || 'N/A';
            const memberEmail = card.getAttribute('data-email');
            const memberPhone = card.getAttribute('data-phone') || 'N/A';
            const memberPhoto = card.getAttribute('data-photo');

            // Actualiza el contenido del modal con los datos del miembro
            modalContent.innerHTML = `
                <div class="modal-member-info">
                    <img src="${memberPhoto}" alt="${memberName}" class="modal-member-image">
                    <h3>${memberName}</h3>
                    <p><strong>Rol:</strong> ${memberRole}</p>
                    <p><strong>Descripción:</strong> ${memberDescription}</p>
                    <p><strong>Teléfono:</strong> ${memberPhone}</p>
                </div>
            `;

            // Muestra el modal
            modal.style.display = 'block';
        });
    });

    // Cierra el modal al hacer clic en el botón de cierre
    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Cierra el modal al hacer clic fuera del contenido del modal
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});