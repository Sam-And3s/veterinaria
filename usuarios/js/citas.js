document.addEventListener('DOMContentLoaded', function () {
    // ... (Tu código existente para el video y el carrusel) ...

    // Implementación del Calendario
    const datePicker = document.getElementById('date-picker');
    const daysContainer = document.querySelector('.days');
    const currentMonthElement = document.querySelector('.current-month');
    const scheduleBtn = document.querySelector('.schedule-btn');
    const addAppointmentModal = document.getElementById('addAppointmentModal'); // Obtener el modal
    const closeBtn = addAppointmentModal.querySelector('.close-btn');
    const appointmentDateInput = document.getElementById('appointmentDate');
    const appointmentTimeSelect = document.getElementById('appointmentTime');
    const addAppointmentForm = document.getElementById('addAppointmentForm');

    document.getElementById('addAppointmentModal').style.display = 'none';

    let currentDate = new Date();
    let selectedDate = null;
    // Establecer valor inicial del selector de fecha
    datePicker.valueAsDate = currentDate;

    function updateDatePicker(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        datePicker.value = `${year}-${month}-${day}`;
    }

    async function generateCalendar(date) {
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
            const status = await getDayStatus(currentDay); // Obtener el estado del día
            daysContainer.appendChild(createDayElement(day, date.getMonth() + 1, date.getFullYear(), status));
        }

        // Actualizar el selector de fecha para mostrar el primer día del mes mostrado
        if (!selectedDate) {
            updateDatePicker(firstDay);
        }
    }

    function createDayElement(day, month, year, status) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        dayElement.innerHTML = `<span class="day-number">${day}</span>`;

        // Crear el contenedor para los puntos de estado
        const statusDot = document.createElement('div');
        statusDot.className = 'status-dot';

        // Asignar clases según el estado del día
        if (status == 6) {
            statusDot.classList.add('available'); // Azul: Disponible
            dayElement.addEventListener('click', () => selectDay(dayElement, day)); // Hacer el día interactivo
        } else if (status == 7) {
            statusDot.classList.add('closed'); // Amarillo: Sin Servicio
            dayElement.classList.add('no-service');
        } else if (status == 8) {
            statusDot.classList.add('no_slots'); // Rojo: Sin Cupos
            dayElement.classList.add('unavailable');
        }

        // Agregar el punto de estado al día
        dayElement.appendChild(statusDot);

        return dayElement;
    }

    function selectDay(element, day) {
        // Remover la clase "selected" de otros días
        document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');

        // Actualizar la fecha seleccionada
        selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);

        // Actualizar el campo de fecha nativo
        updateDatePicker(selectedDate);

        // Validar el estado del día seleccionado
        const year = selectedDate.getFullYear();
        const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
        const dayString = String(selectedDate.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${dayString}`;

        fetch(`/veterinaria/citas_api.php?action=checkDayStatus&fecha=${formattedDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const status = data.status;

                    // Actualizar el botón "Agendar Cita" según el estado del día
                    if (status === 6) { // Disponible
                        scheduleBtn.disabled = false;
                        loadAvailableHours(selectedDate); // Cargar horarios disponibles
                    } else if (status === 8) { // Sin Cupos
                        scheduleBtn.disabled = true; // Deshabilitar el botón
                        appointmentTimeSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                        alert('Este día no tiene cupos disponibles.');
                    } else if (status === 7) { // Sin Servicio
                        scheduleBtn.disabled = true; // Deshabilitar el botón
                        appointmentTimeSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                        alert('Este día está marcado como sin servicio.');
                    }
                } else {
                    console.error('Error al validar el estado del día:', data.message);
                    scheduleBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error al validar el estado del día:', error);
                scheduleBtn.disabled = true;
            });

        console.log('Día seleccionado:', selectedDate);
    }

    async function getDayStatus(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;

        try {
            const response = await fetch(`/veterinaria/citas_api.php?action=getDayStatus&fecha=${formattedDate}`);
            const data = await response.json();
            if (data.success) {
                return data.status; // Devuelve el ID del estado del día
            } else {
                return 7; // Estado predeterminado: Sin Servicio
            }
        } catch (error) {
            console.error('Error al verificar el estado del día:', error);
            return 7; // Estado predeterminado: Sin Servicio
        }
    }

    function getAdjustedDay(date) {
        let day = date.getDay() - 1;
        return day < 0 ? 6 : day;
    }

    async function loadAvailableHours(date) {
        appointmentTimeSelect.innerHTML = '<option value="">Cargando horas...</option>';
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;

        try {
            const response = await fetch(`/veterinaria/citas_api.php?action=getAvailableHours&fecha=${formattedDate}`);
            const data = await response.json();
            appointmentTimeSelect.innerHTML = '<option value="">Seleccionar hora</option>'; // Limpiar opciones de carga

            if (data.success && data.horarios) {
                data.horarios.forEach(hora => {
                    const option = document.createElement('option');
                    option.value = hora.id_horario;
                    option.textContent = `${hora.hora_ini} - ${hora.hora_fin}`;
                    appointmentTimeSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay horas disponibles';
                appointmentTimeSelect.appendChild(option);
                appointmentTimeSelect.disabled = true;
            }
        } catch (error) {
            console.error('Error al cargar las horas disponibles:', error);
            appointmentTimeSelect.innerHTML = '<option value="">Error al cargar horas</option>';
            appointmentTimeSelect.disabled = true;
        }
    }

    // Event listener para el botón "Agendar Cita"
    scheduleBtn.addEventListener('click', () => {
        if (selectedDate) {
            // Pre-llenar la fecha en el formulario
            const year = selectedDate.getFullYear();
            const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
            const day = String(selectedDate.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            appointmentDateInput.value = formattedDate;

            // Cargar las horas disponibles para la fecha seleccionada
            loadAvailableHours(selectedDate);

            // Mostrar el modal
            addAppointmentModal.style.display = 'block';
        } else {
            alert('Por favor, selecciona una fecha para agendar.');
        }
    });

    // Event listener para cerrar el modal
    closeBtn.addEventListener('click', () => {
        addAppointmentModal.style.display = 'none';
    });

    // Cerrar el modal si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (event.target == addAppointmentModal) {
            addAppointmentModal.style.display = 'none';
        }
    });

    // Event listener para el envío del formulario
    addAppointmentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const selectedTime = appointmentTimeSelect.value;
        const selectedService = document.getElementById('appointmentService').value;
        const appointmentDescription = document.getElementById('appointmentDescription').value;
        const selectedSize = document.getElementById('appointmentSize').value;
        const userId = document.getElementById('loggedInUserId')?.value || null;
        const userName = document.getElementById('loggedInUserName')?.value || 'Usuario'; // Nombre del cliente

        console.log('selectedDate:', selectedDate);
        console.log('selectedTime:', selectedTime);
        console.log('selectedService:', selectedService);
        console.log('appointmentDescription:', appointmentDescription);
        console.log('selectedSize:', selectedSize);
        console.log('userId:', userId);

        if (selectedDate && selectedTime && selectedService && selectedSize && userId) {
            const year = selectedDate.getFullYear();
            const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
            const day = String(selectedDate.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;

            const appointmentData = {
                date: formattedDate,
                id_horario: selectedTime,
                service: selectedService,
                description: appointmentDescription,
                size: selectedSize,
                user_id: userId
            };

            try {
                const response = await fetch('/veterinaria/citas_api.php?action=addAppointment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(appointmentData)
                });
                const result = await response.json();
                if (result.success) {
                    // Obtener valores textuales de los selects
                    const selectedTimeText = appointmentTimeSelect.options[appointmentTimeSelect.selectedIndex].text;
                    const selectedServiceText = document.getElementById('appointmentService').options[document.getElementById('appointmentService').selectedIndex].text;
                    const selectedSizeText = document.getElementById('appointmentSize').options[document.getElementById('appointmentSize').selectedIndex].text;

                    // Generar el mensaje de WhatsApp
                    let whatsappMessage = "Hola,\n";
                    whatsappMessage += `Mi nombre es ${userName} y acabo de agendar una cita en la página web de PuppyShop.\n\n`;
                    whatsappMessage += "Estos son los datos de mi cita:\n";
                    whatsappMessage += `- Fecha: ${formattedDate}\n`;
                    whatsappMessage += `- Hora: ${selectedTimeText}\n`;
                    whatsappMessage += `- Tamaño del perro: ${selectedSizeText}\n`;
                    whatsappMessage += `- Servicio solicitado: ${selectedServiceText}\n`;
                    whatsappMessage += `- Descripción de la mascota:\n${appointmentDescription}\n\n`;
                    whatsappMessage += "Agradezco su confirmación y quedo atento(a) por este medio.\n";
                    whatsappMessage += "Gracias,\n\n";
                    whatsappMessage += "— Enviado desde PuppyShop Web";

                    // Redirigir al chat de WhatsApp
                    const whatsappUrl = `https://wa.me/3015902278?text=${encodeURIComponent(whatsappMessage)}`;
                    window.open(whatsappUrl, '_blank'); // Abrir en una nueva pestaña

                    alert('Cita agendada exitosamente. Se abrirá WhatsApp para confirmar la cita.');
                    window.location.reload(); // Recargar la página
                } else {
                    alert('Error al agendar la cita: ' + result.message);
                }
            } catch (error) {
                console.error('Error al enviar la cita:', error);
                alert('Ocurrió un error al intentar agendar la cita.');
            }
        } else {
            alert('Por favor, completa todos los campos del formulario.');
        }
    });

    // Inicializar calendario
    generateCalendar(currentDate);

    // Cargar los servicios disponibles al cargar la página
    async function loadAvailableServices() {
        const serviceSelect = document.getElementById('appointmentService');
        serviceSelect.innerHTML = '<option value="">Cargando servicios...</option>';
        try {
            const response = await fetch('/veterinaria/citas_api.php?action=getAvailableServices');
            const data = await response.json();
            serviceSelect.innerHTML = '<option value="">Seleccionar servicio</option>';
            if (data.success && data.servicios) {
                data.servicios.forEach(servicio => {
                    const option = document.createElement('option');
                    option.value = servicio.id_tip_serv;
                    option.textContent = servicio.nom_tip_serv;
                    serviceSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay servicios disponibles';
                serviceSelect.appendChild(option);
                serviceSelect.disabled = true;
            }
        } catch (error) {
            console.error('Error al cargar los servicios:', error);
            serviceSelect.innerHTML = '<option value="">Error al cargar servicios</option>';
            serviceSelect.disabled = true;
        }
    }
    loadAvailableServices();

    async function loadAvailableSizes() {
        const sizeSelect = document.getElementById('appointmentSize');
        sizeSelect.innerHTML = '<option value="">Cargando tamaños...</option>';
        try {
            const response = await fetch('/veterinaria/citas_api.php?action=getAvailableSizes');
            const data = await response.json();
            sizeSelect.innerHTML = '<option value="">Seleccionar tamaño</option>';
            if (data.success && data.sizes) {
                data.sizes.forEach(size => {
                    const option = document.createElement('option');
                    option.value = size.id_tamaño;
                    option.textContent = size.nom_tam;
                    sizeSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay tamaños disponibles';
                sizeSelect.appendChild(option);
                sizeSelect.disabled = true;
            }
        } catch (error) {
            console.error('Error al cargar los tamaños:', error);
            sizeSelect.innerHTML = '<option value="">Error al cargar tamaños</option>';
            sizeSelect.disabled = true;
        }
    }

    
    loadAvailableSizes();

    datePicker.addEventListener('change', async (event) => {
        const selectedDateValue = event.target.value;
        const [year, month, day] = selectedDateValue.split('-');
        const newDate = new Date(year, month - 1, day);

        // Actualizar la fecha seleccionada
        selectedDate = newDate;

        // Validar el estado del día seleccionado
        const response = await fetch(`/veterinaria/citas_api.php?action=checkDayStatus&fecha=${selectedDateValue}`);
        const data = await response.json();

        if (data.success) {
            const status = data.status;

            // Actualizar el botón "Agendar Cita" según el estado del día
            if (status === 6) { // Disponible
                scheduleBtn.disabled = false;
                loadAvailableHours(selectedDate); // Cargar horarios disponibles
            } else if (status === 8) { // Sin Cupos
                scheduleBtn.disabled = true; // Deshabilitar el botón
                appointmentTimeSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                alert('Este día no tiene cupos disponibles.');
            } else if (status === 7) { // Sin Servicio
                scheduleBtn.disabled = true; // Deshabilitar el botón
                appointmentTimeSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                alert('Este día está marcado como sin servicio.');
            }
        } else {
            console.error('Error al validar el estado del día:', data.message);
            scheduleBtn.disabled = true;
        }

        console.log('Fecha seleccionada desde el input:', selectedDate);
    });
});