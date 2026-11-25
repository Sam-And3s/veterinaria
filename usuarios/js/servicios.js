// servicios.js

// Función para obtener los planes de spa desde la API
async function fetchSpaPlans() {
    const response = await fetch('/veterinaria/servicios_api.php?action=getSpaPlans');
    return await response.json();
}

// Función para mostrar los planes de spa en el contenedor HTML
async function displaySpaPlans() {
    const spaPlansContainer = document.querySelector('.service1 .pricing-container'); // Selector específico
    const welcomeContentSpa = document.querySelector('.service1 .welcome-content'); // Selector para el título

    if (!spaPlansContainer || !welcomeContentSpa) {
        console.error('El contenedor de planes de spa o la sección de bienvenida no se encontraron.');
        return;
    }
    spaPlansContainer.innerHTML = ''; // Limpiar el contenedor

    const spaPlansData = await fetchSpaPlans();

    if (spaPlansData.success && spaPlansData.spaPlans) {
        // Actualizar el título de la sección
        welcomeContentSpa.querySelector('h2').textContent = '';
        welcomeContentSpa.querySelector('p').textContent = 'En nuestro spa canino, tu mascota vive una experiencia de bienestar total. Aplicamos tratamientos de relajación y belleza diseñados para cuidar su piel, pelaje y estado emocional. Nuestro equipo de expertos utiliza productos premium y técnicas especializadas que no solo lo harán lucir increíble, sino sentirse renovado y feliz.';

        spaPlansData.spaPlans.forEach((plan, index) => {
            const planCard = document.createElement('div');
            planCard.classList.add('pricing-card');
            if (index === 1) { // Marcar el segundo plan como "featured"
                planCard.classList.add('featured');
            }

            planCard.innerHTML = `
                <h3>${plan.nom_serv}</h3>
                <div class="price">$${parseFloat(plan.precio_serv).toLocaleString()}</div>
                <div class="price-period">Por sesión</div>
                <ul>
                    <li><i class="ri-checkbox-circle-line"></i> ${plan.descrip_serv}</li>
                </ul>
            `;
            spaPlansContainer.appendChild(planCard);
        });
    } else {
        spaPlansContainer.innerHTML = '<p>No hay planes de spa disponibles.</p>';
        if (spaPlansData.message) {
            console.error('Error al cargar planes de spa:', spaPlansData.message);
        }
    }
}

async function fetchConsultaPlans() {
    const response = await fetch('/veterinaria/servicios_api.php?action=getConsultaPlans');
    return await response.json();
}

async function displayConsultaPlans() {
    const consultaPlansContainer = document.querySelector('.service2 .pricing-container'); // Selector específico
    const welcomeContentConsulta = document.querySelector('.service2 .welcome-content'); // Selector para el título

    if (!consultaPlansContainer || !welcomeContentConsulta) {
        console.error('El contenedor de planes de consulta o la sección de bienvenida no se encontraron.');
        return;
    }
    consultaPlansContainer.innerHTML = ''; // Limpiar el contenedor

    const consultaPlansData = await fetchConsultaPlans();

    if (consultaPlansData.success && consultaPlansData.consultaPlans) {
        // Actualizar el título de la sección
        welcomeContentConsulta.querySelector('h2').textContent = 'Consulta Veterinaria';
        welcomeContentConsulta.querySelector('p').textContent = 'Cuidamos su salud con compromiso y experiencia, en todo momento. Nuestro equipo de veterinarios altamente capacitados brinda atención médica de calidad, desde chequeos preventivos hasta situaciones de urgencia. Estamos disponibles para ofrecer diagnósticos precisos, trato empático y soluciones eficaces que garanticen el bienestar de tu compañero canino.';

        consultaPlansData.consultaPlans.forEach((plan, index) => {
            const planCard = document.createElement('div');
            planCard.classList.add('pricing-card');
            if (index === 1) { // Marcar el segundo plan como "featured"
                planCard.classList.add('featured');
            }

            planCard.innerHTML = `
                <h3>${plan.nom_serv}</h3>
                <div class="price">$${parseFloat(plan.precio_serv).toLocaleString()}</div>
                <div class="price-period">Por consulta</div>
                <ul>
                    <li><i class="ri-checkbox-circle-line"></i> ${plan.descrip_serv}</li>
                </ul>
            `;
            consultaPlansContainer.appendChild(planCard);
        });
    } else {
        consultaPlansContainer.innerHTML = '<p>No hay planes de consulta disponibles.</p>';
        if (consultaPlansData.message) {
            console.error('Error al cargar planes de consulta:', consultaPlansData.message);
        }
    }
}

async function fetchPeluqueriaPlans() {
    const response = await fetch('/veterinaria/servicios_api.php?action=getPeluqueriaPlans');
    return await response.json();
}

async function displayPeluqueriaPlans() {
    const peluqueriaPlansContainer = document.querySelector('.service3 .pricing-container'); // Selector específico
    const welcomeContentPeluqueria = document.querySelector('.service3 .welcome-content'); // Selector para el título

    if (!peluqueriaPlansContainer || !welcomeContentPeluqueria) {
        console.error('El contenedor de planes de peluquería o la sección de bienvenida no se encontraron.');
        return;
    }
    peluqueriaPlansContainer.innerHTML = ''; // Limpiar el contenedor

    const peluqueriaPlansData = await fetchPeluqueriaPlans();

    if (peluqueriaPlansData.success && peluqueriaPlansData.peluqueriaPlans) {
        // Actualizar el título de la sección
        welcomeContentPeluqueria.querySelector('h2').textContent = 'Peluquería Canina Profesional';
        welcomeContentPeluqueria.querySelector('p').textContent = 'Estilo, higiene y cuidado en cada corte. En nuestra peluquería canina profesional, tu mascota recibe una atención personalizada a cargo de estilistas expertos que dominan las características y necesidades de cada raza. Realizamos cortes a medida, con técnicas seguras y productos de calidad, para que luzca impecable y se sienta cómodo en todo momento.';

        peluqueriaPlansData.peluqueriaPlans.forEach((plan, index) => {
            const planCard = document.createElement('div');
            planCard.classList.add('pricing-card');
            if (index === 1) { // Marcar el segundo plan como "featured"
                planCard.classList.add('featured');
            }

            planCard.innerHTML = `
                <h3>${plan.nom_serv}</h3>
                <div class="price">$${parseFloat(plan.precio_serv).toLocaleString()}</div>
                <div class="price-period">Por sesión</div>
                <ul>
                    <li><i class="ri-checkbox-circle-line"></i> ${plan.descrip_serv}</li>
                </ul>
            `;
            peluqueriaPlansContainer.appendChild(planCard);
        });
    } else {
        peluqueriaPlansContainer.innerHTML = '<p>No hay servicios de peluquería disponibles.</p>';
        if (peluqueriaPlansData.message) {
            console.error('Error al cargar servicios de peluquería:', peluqueriaPlansData.message);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    displaySpaPlans();
    displayConsultaPlans();
    displayPeluqueriaPlans();
});

// **EL SIGUIENTE BLOQUE DE CÓDIGO PARA EL CARRUSEL ".slide"
// SE HA COMENTADO PARA EVITAR EL ERROR SI NO ESTÁ EN USO
// EN LA PÁGINA DONDE SE MUESTRAN LOS PLANES DE SPA.**
/*
// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', function () {
    // Obtiene todos los elementos con la clase 'slide' y los almacena en una constante
    const slides = document.querySelectorAll('.slide');
    // Inicializa una variable para mantener el índice de la diapositiva actual
    let currentSlide = 0;

    // Función que muestra una diapositiva específica según el índice proporcionado
    function showSlide(index) {
        if (slides.length > 0) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }
    }

    // Función para avanzar a la siguiente diapositiva
    function nextSlide() {
        if (slides.length > 0) {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
    }

    // Muestra la primera diapositiva al cargar la página (si hay diapositivas)
    if (slides.length > 0) {
        showSlide(0);
        setInterval(nextSlide, 6000);
    }
});
*/

// Segundo evento que se ejecuta cuando el DOM está cargado (para el carrusel de promociones)
document.addEventListener('DOMContentLoaded', function () {
    // Selecciona el contenedor del slider de promociones
    const slider = document.querySelector('.promo-slider');
    // Obtiene todas las tarjetas de promoción existentes
    const cards = document.querySelectorAll('.promo-card');

    if (slider && cards.length > 0) {
        // Duplica cada tarjeta de promoción y las agrega al final del slider
        cards.forEach(card => {
            const clone = card.cloneNode(true);
            slider.appendChild(clone);
        });
    }
});