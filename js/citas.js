document.addEventListener('DOMContentLoaded', function () {
    // Control del Video
    const video = document.getElementById('tutorial-video');
    const soundToggle = document.querySelector('.sound-toggle');
    let isInView = false;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                video.play();
                isInView = true;
            } else {
                video.pause();
                isInView = false;
            }
        });
    });

    observer.observe(video);

    soundToggle.addEventListener('click', () => {
        video.muted = !video.muted;
        soundToggle.innerHTML = video.muted ?
            '<i class="ri-volume-mute-line"></i>' :
            '<i class="ri-volume-up-line"></i>';
    });

    // Sección del Carrusel de Términos
    const swiper = new Swiper('.terms-images', {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
    });
    // Implementación del Calendario
    const datePicker = document.getElementById('date-picker');
    const daysContainer = document.querySelector('.days');
    const currentMonthElement = document.querySelector('.current-month');
    const scheduleBtn = document.querySelector('.schedule-btn');
    let currentDate = new Date();
    let selectedDate = null;
    // Establecer valor inicial del selector de fecha
    datePicker.valueAsDate = currentDate;

    function updateDatePicker(date) {
        datePicker.valueAsDate = date;
    }

    function generateCalendar(date) {
        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        currentMonthElement.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
        daysContainer.innerHTML = '';

        // Agregar celdas vacías para los días antes del primer día del mes
        for (let i = 0; i < getAdjustedDay(firstDay); i++) {
            daysContainer.appendChild(createDayElement('', 'empty'));
        }

        // Agregar días del mes
        for (let day = 1; day <= lastDay.getDate(); day++) {
            const currentDay = new Date(date.getFullYear(), date.getMonth(), day);
            const status = getDayStatus(currentDay);
            daysContainer.appendChild(createDayElement(day, status));
        }

        // Actualizar el selector de fecha para mostrar el primer día del mes mostrado
        if (!selectedDate) {
            updateDatePicker(firstDay);
        }
    }

    function createDayElement(day, status) {
        const dayElement = document.createElement('div');
        dayElement.className = `day ${status}`;
        dayElement.textContent = day;

        if (day && status !== 'empty') {
            const statusDot = document.createElement('div');
            statusDot.className = `day-status ${status}`;
            dayElement.appendChild(statusDot);

            if (status === 'available') {
                dayElement.addEventListener('click', () => selectDay(dayElement, day));
            }
        }

        return dayElement;
    }

    function selectDay(element, day) {
        document.querySelectorAll('.day.selected').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
        scheduleBtn.disabled = false;
        updateDatePicker(selectedDate);
    }

    function getDayStatus(date) {
        if (date.getDay() === 0) return 'closed';
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (date < today) return 'unavailable';
        return Math.random() > 0.5 ? 'available' : 'unavailable';
    }

    function getAdjustedDay(date) {
        let day = date.getDay() - 1;
        return day < 0 ? 6 : day;
    }
    // Escuchadores de Eventos
    // Actualizar el escuchador de eventos para la entrada de fecha
    datePicker.addEventListener('change', (e) => {
        const newDate = new Date(e.target.value);
        currentDate = new Date(newDate.getFullYear(), newDate.getMonth(), 1);
        generateCalendar(currentDate);
    
        // Encontrar y seleccionar el día específico en el calendario
        setTimeout(() => {
            const dayElements = document.querySelectorAll('.day');
            dayElements.forEach(dayElement => {
                if (dayElement.textContent == newDate.getDate()) {
                    const status = getDayStatus(newDate);
                    if (status === 'available') {
                        selectDay(dayElement, newDate.getDate());
                    }
                }
            });
        }, 100);
    });
    // Actualizar los manejadores de botones de navegación para sincronizar con la entrada de fecha
    document.querySelector('.prev-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        selectedDate = null;
        scheduleBtn.disabled = true;
        generateCalendar(currentDate);
    // Actualizar entrada de fecha al primer día del mes
        datePicker.valueAsDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    });
    document.querySelector('.next-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        selectedDate = null;
        scheduleBtn.disabled = true;
        generateCalendar(currentDate);
    // Actualizar entrada de fecha al primer día del mes
        datePicker.valueAsDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    });
    // Inicializar calendario
    generateCalendar(currentDate);
});