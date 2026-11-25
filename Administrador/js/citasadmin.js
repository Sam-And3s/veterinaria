document.addEventListener('DOMContentLoaded', function () {

    // Inicializa la fecha actual del calendario
    const currentDate = new Date();

    // Obtiene referencias a elementos del DOM (iniciales)
    const calendarGrid = document.getElementById('calendarGrid');
    const currentDateElement = document.querySelector('.current-date');
    const prevMonthBtn = document.querySelector('.prev-month');
    const nextMonthBtn = document.querySelector('.next-month');
    const yearInput = document.querySelector('.year-input');
    const monthSelect = document.querySelector('.month-select');
    const yearSelect = document.querySelector('.year-select');
    const appointmentsList = document.querySelector('.appointments-list');
    const addAppointmentForm = document.getElementById('addAppointmentForm');
    const manageNoServiceModal = document.getElementById('manageNoServiceModal');
    // MANTÉN ESTA DECLARACIÓN FUERA DE generateCalendar()
    const manageNoServiceForm = document.getElementById('manageNoServiceForm');
    const noServiceMessageDiv = document.getElementById('noServiceMessage');
    const startDateNoServiceInput = document.getElementById('startDateNoService');
    const endDateNoServiceInput = document.getElementById('endDateNoService');
    const noServiceActionSelect = document.getElementById('noServiceAction');

    // Asegúrate de que todos los modales estén ocultos al cargar la página
    document.getElementById('addAppointmentModal').style.display = 'none';
    document.getElementById('availabilityModal').style.display = 'none';
    
    document.getElementById('manageNoServiceModal').style.display = 'none';
    const appointmentDetailsModal = document.getElementById('appointmentDetailsModal');
    appointmentDetailsModal.style.display = 'none';
    // ===== FUNCIONES DEL CALENDARIO =====

    // Función para generar la estructura del calendario
    function generateCalendar(daySummary = []) {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // Actualiza el título del mes y año
        const monthName = new Intl.DateTimeFormat('es-ES', { month: 'long' }).format(currentDate);
        currentDateElement.textContent = `${monthName.charAt(0).toUpperCase() + monthName.slice(1)} ${year}`;
        yearInput.value = year;
        monthSelect.value = month;

        // Limpia el contenido previo del calendario
        calendarGrid.innerHTML = '';

        // Calcula los días del mes
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const totalDays = lastDay.getDate();
        let startingDay = firstDay.getDay();

        // Ajustar para que el domingo sea el primer día de la semana
        startingDay = (startingDay === 0) ? 7 : startingDay;

        // Agrega celdas vacías para los días anteriores al primer día del mes
        for (let i = 1; i < startingDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day empty';
            calendarGrid.appendChild(emptyDay);
        }

        // Genera las celdas para cada día del mes
        for (let day = 1; day <= totalDays; day++) {
            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const summary = daySummary.find(s => s.date === formattedDate);
            const dayElement = createDayElement(day, month, year, summary);
            calendarGrid.appendChild(dayElement);
        }

        function createDayElement(day, month, year, summary) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.innerHTML = `<span class="day-number">${day}</span>`;

            // Muestra los textos si hay cupos asignados
            if (summary && summary.assigned_slots > 0) {
                const assignedSlots = document.createElement('div');
                assignedSlots.textContent = `C. A: ${summary.assigned_slots - summary.daily_appointments}`;
                assignedSlots.title = 'Cupos totales';
                dayElement.appendChild(assignedSlots);

                const dailyAppointments = document.createElement('div');
                dailyAppointments.textContent = `C. D: ${summary.daily_appointments}`;
                dailyAppointments.title = 'Citas del Día';
                dayElement.appendChild(dailyAppointments);
            }

            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            // Verifica si el día está marcado como sin servicio
            fetch(`/veterinaria/citas_api.php?action=checkNoService&fecha=${formattedDate}`)
                .then(response => response.json())
                .then(data => {
                    if (data.noService) {
                        dayElement.classList.add('no-service');
                        dayElement.style.backgroundColor = 'yellow';
                        dayElement.style.cursor = 'not-allowed'; // Opcional: cambia el cursor
                        dayElement.removeEventListener('click', dayElement.clickListener); // Elimina el listener original
                    } else {
                        // Adjunta el evento de clic solo si no está sin servicio
                        dayElement.clickListener = () => {
                            document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                            dayElement.classList.add('selected');
                            fetchAppointments(formattedDate);
                            loadAvailableHours(formattedDate);
                        };
                        dayElement.addEventListener('click', dayElement.clickListener);
                    }
                })
                .catch(error => console.error('Error al verificar el servicio:', error));

            return dayElement;
        }

        // Configurar botones de acción del calendario
        const calendarViews = document.querySelector('.calendar-views');
        calendarViews.innerHTML = `
            <button class="action-btn add-appointment" title="Agregar cita">
                <i class="fas fa-plus"></i>
            </button>
            <button class="action-btn set-availability" title="Configurar disponibilidad">
                <i class="fas fa-calendar-alt"></i>
            </button>
            <button class="action-btn manage-no-service" title="Gestionar días sin servicio">
                <i class="fas fa-ban"></i>
            </button>
        `;

        // ===== MUEVE LOS EVENT LISTENERS DE LOS BOTONES AQUÍ DENTRO DE generateCalendar() =====
        const addAppointmentBtn = document.querySelector('.add-appointment');
        if (addAppointmentBtn) {
            addAppointmentBtn.addEventListener('click', () => {
                const selectedDay = document.querySelector('.calendar-day.selected');
                if (selectedDay) {
                    const day = selectedDay.querySelector('.day-number').textContent;
                    const month = currentDate.getMonth() + 1;
                    const year = currentDate.getFullYear();
                    const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    console.log('Día seleccionado:', day, 'Mes:', month, 'Año:', year, 'Fecha armada:', formattedDate);
                    openAddAppointmentModal(formattedDate);
                    document.getElementById('addAppointmentModal').style.display = 'block';
                } else {
                    alert('Por favor, selecciona un día en el calendario.');
                }
            });
        }

        const setAvailabilityBtn = calendarViews.querySelector('.set-availability');
        if (setAvailabilityBtn) {
            setAvailabilityBtn.addEventListener('click', openAvailabilityModal);
        } else {
            console.error("El botón .set-availability no se encontró en calendarViews.");
        }


        const manageNoServiceBtn = calendarViews.querySelector('.manage-no-service');
        if (manageNoServiceBtn) {
            manageNoServiceBtn.addEventListener('click', () => {
                manageNoServiceModal.style.display = 'block';
                noServiceMessageDiv.textContent = ''; // Limpia cualquier mensaje previo
                // Puedes opcionalmente prellenar las fechas con el mes actual si lo deseas
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const firstDayOfMonth = `${year}-${month}-01`;
                const lastDayOfMonth = `${year}-${month}-${new Date(year, today.getMonth() + 1, 0).getDate()}`;
                startDateNoServiceInput.value = firstDayOfMonth;
                endDateNoServiceInput.value = lastDayOfMonth;
            });
        } else {
            console.error("El botón .manage-no-service no se encontró en calendarViews.");
        }
        // ===== FIN DE LOS EVENT LISTENERS MOVIDOS =====
    }

    function loadCalendar(selectedDayNumber = null, selectedMonth = null, selectedYear = null) {
        fetch('/veterinaria/citas_api.php?action=getDaySummary')
            .then(response => response.json())
            .then (data => {
                if (data.success) {
                    generateCalendar(data.summary);

                    // Vuelve a seleccionar el día y recarga las citas
                    if (selectedDayNumber && selectedMonth !== null && selectedYear !== null) {
                        // Solo selecciona si el mes y año coinciden
                        if (
                            currentDate.getMonth() === selectedMonth &&
                            currentDate.getFullYear() === selectedYear
                        ) {
                            const days = document.querySelectorAll('.calendar-day:not(.empty)');
                            days.forEach(dayEl => {
                                if (dayEl.querySelector('.day-number').textContent === selectedDayNumber) {
                                    dayEl.classList.add('selected');
                                    const formattedDate = `${selectedYear}-${String(selectedMonth + 1).padStart(2, '0')}-${String(selectedDayNumber).padStart(2, '0')}`;
                                    fetchAppointments(formattedDate);
                                }
                            });
                        }
                    }
                } else {
                    generateCalendar([]);
                }
            })
            .catch(error => {
                console.error('Error al cargar el calendario:', error);
                generateCalendar([]);
            });
    }

    // Función para inicializar selectores de fecha
    function initializeDateSelectors() {
        const months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        // Limpia opciones existentes
        monthSelect.innerHTML = '';

        months.forEach((month, index) => {
            const option = document.createElement('option');
            option.value = index;
            option.textContent = month;
            monthSelect.appendChild(option);
        });

        yearInput.value = currentDate.getFullYear();
        monthSelect.value = currentDate.getMonth();
    }
    // ===== FUNCIONES DE CITAS =====

    // Función para obtener citas de un día específico
    function fetchAppointments(dateStr) {
        console.log('Intentando cargar citas para la fecha:', dateStr);

        const appointmentsList = document.getElementById('appointmentsList');
        const confirmedList = document.getElementById('confirmedList');
        const cancelledList = document.getElementById('cancelledList'); // Nueva sección para citas canceladas

        if (!appointmentsList || !confirmedList || !cancelledList) {
            console.error('Uno de los contenedores no existe en el DOM.');
            return;
        }

        fetch(`/veterinaria/citas_api.php?action=getAppointments&date=${dateStr}`)
            .then(response => response.json())
            .then(data => {
                appointmentsList.innerHTML = ''; // Limpia las citas previas
                confirmedList.innerHTML = ''; // Limpia las citas confirmadas previas
                cancelledList.innerHTML = ''; // Limpia las citas canceladas previas

                if (data.success) {
                    data.appointments.forEach(appointment => {
                        const appointmentDiv = document.createElement('div');
                        appointmentDiv.id = `appointment_${appointment.id_citas}`;
                        appointmentDiv.classList.add('appointment-item');
                        appointmentDiv.innerHTML = `
                            <div>
                                <div class="appointment-header">
                                    ${appointment.hora_ini} ${appointment.nom_usu} ${appointment.apell_usu}
                                </div>
                            </div>
                            <div class="appointment-actions">
                                ${
                                    appointment.id_estado === 3
                                        ? `<button class="action-btn info" data-id-cita="${appointment.id_citas}" title="Información">
                                            <i class="fas fa-info-circle"></i>
                                        </button>`
                                        : appointment.id_estado === 4
                                        ? `<button class="action-btn info" data-id-cita="${appointment.id_citas}" title="Información">
                                            <i class="fas fa-info-circle"></i>
                                        </button>`
                                        : `
                                            <button class="action-btn confirm" data-id-cita="${appointment.id_citas}" title="Confirmar">
                                                ✔ 
                                            </button>
                                            <button class="action-btn cancel" data-id-cita="${appointment.id_citas}" title="Cancelar">
                                                <b>X</b>
                                            </button>
                                            ${
                                                appointment.num_usu
                                                    ? `<a href='https://wa.me/${appointment.num_usu}' style="text-decoration: none;"><button class="action-btn whatsapp" data-numero="${appointment.num_usu}" title="WhatsApp">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </button></a>`
                                                    : ''
                                            }
                                            <button class="action-btn info" data-id-cita="${appointment.id_citas}" title="Información">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        `
                                }
                            </div>
                        `;

                        if (appointment.id_estado === 3) {
                            // Si la cita está confirmada, muévela a la sección de confirmadas
                            confirmedList.appendChild(appointmentDiv);
                        } else if (appointment.id_estado === 4) {
                            // Si la cita está cancelada, muévela a la sección de canceladas
                            cancelledList.appendChild(appointmentDiv);
                        } else {
                            // Si no está confirmada ni cancelada, muévela a la lista del día
                            appointmentsList.appendChild(appointmentDiv);
                        }
                    });

                    // Adjuntar eventos a los botones
                    attachAppointmentActionListeners();
                } else {
                    appointmentsList.innerHTML = '<div class="no-appointments">No hay citas para este día.</div>';
                }
            })
            .catch(error => {
                console.error('Error al cargar las citas:', error);
                alert('Error al cargar las citas.');
            });
    }

    function loadAppointmentsForDay(date) {
        fetch(`/veterinaria/citas_api.php?action=getAppointmentsForDay&date=${date}`)
            .then(response => response.json())
            .then(data => {
                const appointmentsList = document.querySelector('.appointments-list');
                appointmentsList.innerHTML = '';
                if (data.success && data.appointments.length > 0) {
                    data.appointments.forEach(app => {
                        const div = document.createElement('div');
                        div.className = 'appointment-item';
                        div.innerHTML = `
                            <strong>${app.hora_ini} - ${app.hora_fin}</strong><br>
                            Servicio: ${app.nom_tip_serv}<br>
                            Tamaño: ${app.nom_tam}<br>
                            Descripción: ${app.descripcion}<br>
                            Registrado por: ${app.usuario_nombre}
                        `;
                        appointmentsList.appendChild(div);
                    });
                } else {
                    appointmentsList.innerHTML = '<div class="no-appointments">No hay citas para este día.</div>';
                }
            });
    }

    // ===== FUNCIONES DE MODALES =====

    function openAvailabilityModal() {
        const selectedDay = document.querySelector('.calendar-day.selected');
        if (!selectedDay) {
            alert('Por favor, selecciona un día en el calendario.');
            return;
        }

        const day = selectedDay.querySelector('.day-number').textContent;
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        const modal = document.getElementById('availabilityModal');
        const availabilityDate = document.getElementById('availabilityDate');

        // Rellena la fecha en el modal
        availabilityDate.value = dateStr;

        // Carga los cupos existentes para la fecha
        loadCurrentSlots(dateStr);

        // Muestra el modal
        modal.style.display = 'block';
    }

    function openAddAppointmentModal(date) {
        console.log('Fecha seleccionada para agregar cita:', date);
        document.getElementById('appointmentDate').value = date;
        const timeSelect = document.getElementById('appointmentTime');
        const saveBtn = document.querySelector('#addAppointmentForm button[type="submit"]');

        // Limpia el select
        timeSelect.innerHTML = '<option value="">Seleccionar hora</option>';

        fetch(`/veterinaria/citas_api.php?action=getSlotsForDate&date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.id_horario;
                        option.textContent = `${slot.hora_ini} - ${slot.hora_fin}`;
                        timeSelect.appendChild(option);
                    });
                    timeSelect.disabled = false;
                    saveBtn.disabled = false;
                } else {
                    // No hay horarios disponibles
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No hay horarios disponibles';
                    timeSelect.appendChild(option);
                    timeSelect.disabled = true;
                    saveBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error al cargar los horarios:', error);
                alert('Error al cargar los horarios.');
            });
    }

    function attachAppointmentActionListeners() {
        // Botón de confirmar
        document.querySelectorAll('.action-btn.confirm').forEach(button => {
            button.addEventListener('click', function () {
                const idCita = this.dataset.idCita;
                console.log('Botón de confirmar clicado, ID de cita:', idCita);

                // Mostrar alerta de confirmación
                const confirmAction = confirm('¿Estás seguro de que deseas confirmar esta cita como realizada?');
                if (confirmAction) {
                    confirmAppointment(idCita);
                }
            });
        });

        // Evento para el botón de cancelar
        document.querySelectorAll('.action-btn.cancel').forEach(button => {
            button.addEventListener('click', function () {
                const idCita = this.dataset.idCita;
                console.log('Botón de cancelar clicado, ID de cita:', idCita);

                const cancelAction = confirm('¿Estás seguro de que deseas cancelar esta cita?');
                if (cancelAction) {
                    cancelAppointment(idCita);
                }
            });
        });

        // Botón de WhatsApp
        document.querySelectorAll('.action-btn.whatsapp').forEach(button => {
            button.addEventListener('click', function () {
                const numero = this.getAttribute('data-numero');
                if (numero) {
                    window.open(`https://wa.me/${numero}`, '_blank');
                } else {
                    alert('Número de WhatsApp no disponible.');
                }
            });
        });

        document.querySelectorAll('.action-btn.info').forEach(button => {
            button.addEventListener('click', function () {
                const citaId = this.dataset.idCita;
                fetch(`/veterinaria/citas_api.php?action=getAppointmentDetails&id=${citaId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAppointmentDetailsModal(data.appointment);
                        } else {
                            alert('Error al obtener la información de la cita: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener la información de la cita:', error);
                        alert('Error al obtener la información de la cita.');
                    });
            });
        });
    }

    document.getElementById('appointmentsList').addEventListener('click', function (event) {
        if (event.target.classList.contains('action-btn') && event.target.classList.contains('confirm')) {
            const idCita = event.target.dataset.idCita;
            console.log('Botón de confirmar clicado, ID de cita:', idCita);
            confirmAppointment(idCita);
        }
    });

    function confirmAppointment(idCita) {
        console.log('Confirmando cita con ID:', idCita);

        fetch('/veterinaria/citas_api.php?action=confirmAppointment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_cita: idCita }),
        })
            .then(response => {
                console.log('Estado de la respuesta:', response.status);
                if (!response.ok) {
                    throw new Error(`Error en la respuesta del servidor: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.success) {
                    alert('Cita confirmada correctamente.');

                    // Mover la cita a la sección de "Citas Confirmadas"
                    const appointmentDiv = document.getElementById(`appointment_${idCita}`);
                    if (appointmentDiv) {
                        console.log('Eliminando botones de confirmar y cancelar...');

                        // Eliminar los botones de confirmar y cancelar
                        const confirmButton = appointmentDiv.querySelector('.action-btn.confirm');
                        const cancelButton = appointmentDiv.querySelector('.action-btn.cancel');
                        console.log('Botón de confirmar encontrado:', confirmButton);
                        console.log('Botón de cancelar encontrado:', cancelButton);

                        if (confirmButton) {
                            confirmButton.remove();
                            console.log('Botón de confirmar eliminado.');
                        } else {
                            console.warn('Botón de confirmar no encontrado.');
                        }

                        if (cancelButton) {
                            cancelButton.remove();
                            console.log('Botón de cancelar eliminado.');
                        } else {
                            console.warn('Botón de cancelar no encontrado.');
                        }

                        // Mover la cita al contenedor de confirmadas
                        const confirmedSection = document.getElementById('confirmedAppointments');
                        confirmedSection.appendChild(appointmentDiv);
                    } else {
                        console.error('No se encontró el contenedor de la cita.');
                    }
                } else {
                    alert('Error al confirmar la cita: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al confirmar la cita:', error);
                alert('Error al confirmar la cita.');
            });
    }

    // ===== EVENT LISTENERS =====

    // Manejadores de eventos para los botones de navegación
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        loadCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        loadCalendar();
    });

    monthSelect.addEventListener('change', (e) => {
        currentDate.setMonth(parseInt(e.target.value));
        loadCalendar();
    });

    yearInput.addEventListener('change', (e) => {
        const year = parseInt(e.target.value);
        if (year >= 1900 && year <= 9999) {
            currentDate.setFullYear(year);
            loadCalendar();
        }
    });

    // Event listener para el formulario de gestión de días sin servicio
    if (manageNoServiceForm) {
        manageNoServiceForm.addEventListener('submit', function(event) {
            event.preventDefault();
    
            const startDate = document.getElementById('startDateNoService').value;
            const endDate = document.getElementById('endDateNoService').value;
            const action = document.getElementById('noServiceAction').value;
    
            if (!startDate || !endDate) {
                const noServiceMessageDiv = document.getElementById('noServiceMessage');
                noServiceMessageDiv.textContent = 'Por favor, selecciona las fechas de inicio y fin.';
                noServiceMessageDiv.className = 'message error';
                return;
            }
    
            const data = {
                startDate: startDate,
                endDate: endDate,
                action: action
            };
    
            fetch('/veterinaria/citas_api.php?action=manageNoServiceDays', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                const noServiceMessageDiv = document.getElementById('noServiceMessage');
                if (result.success) {
                    noServiceMessageDiv.textContent = `Rango de fechas marcado como ${action === 'mark' ? 'sin servicio' : 'con servicio'} correctamente.`;
                    noServiceMessageDiv.className = 'message success';
                    loadCalendar(); // Recarga el calendario para reflejar los cambios
                } else {
                    noServiceMessageDiv.textContent = 'Error al actualizar los días sin servicio: ' + result.message;
                    noServiceMessageDiv.className = 'message error';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const noServiceMessageDiv = document.getElementById('noServiceMessage');
                noServiceMessageDiv.textContent = 'Error al comunicarse con el servidor.';
                noServiceMessageDiv.className = 'message error';
            });
        });
    }

    // Event listeners para cerrar modales
    document.querySelectorAll('.close-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Cierra todos los modales
            document.getElementById('addAppointmentModal').style.display = 'none';
            document.getElementById('availabilityModal').style.display = 'none';
            document.getElementById('manageNoServiceModal').style.display = 'none';
        });
    });
    // Event listener para el formulario de citas
    document.getElementById('addAppointmentForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Capturar los valores de los campos
        const appointmentData = {
            date: document.getElementById('appointmentDate')?.value || '',
            id_horario: document.getElementById('appointmentTime')?.value || '',
            service: document.getElementById('appointmentService')?.value || '',
            size: document.getElementById('appointmentSize')?.value || '',
            description: document.getElementById('description')?.value.trim() || '', // Asegúrate de enviar una cadena vacía si no hay descripción
            user_id: document.getElementById('appointmentUserId')?.value || ''
        };

        // Log para depuración
        console.log('Datos enviados al backend:', appointmentData);

        // Verificar si algún campo está vacío
        if (!appointmentData.date || !appointmentData.id_horario || !appointmentData.service || !appointmentData.size || !appointmentData.user_id) {
            alert('Por favor, completa todos los campos requeridos.');
            return;
        }

        fetch('/veterinaria/citas_api.php?action=addAppointment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(appointmentData)
        })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data); // Log para depuración
                if (data.success) {
                    alert('Cita guardada correctamente');
                    window.location.reload(); // Recarga la página automáticamente
                } else {
                    alert('Error al guardar la cita: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al guardar la cita:', error);
                alert('Error al guardar la cita.');
            });
    });

    // Event listener para el formulario de disponibilidad
    document.getElementById('availabilityForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const date = document.getElementById('availabilityDate').value;
        const totalSlots = document.getElementById('totalSlots').value;
        //const noService = document.getElementById('noService').checked ? 1 : 0;

        const timeSlots = [];
        const timeSlotElements = document.querySelectorAll('.time-slot');
        timeSlotElements.forEach((slot, index) => {
            const startTime = slot.querySelector(`#startTime_${index + 1}`).value;
            const endTime = slot.querySelector(`#endTime_${index + 1}`).value;

            if (startTime && endTime) {
                timeSlots.push({ startTime, endTime });
            }
        });

        const data = {
            date, // Incluye la fecha seleccionada
            totalSlots,
            timeSlots
        };

        fetch('/veterinaria/citas_api.php?action=updateAvailability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Disponibilidad actualizada correctamente.');
                    document.getElementById('availabilityModal').style.display = 'none';
                    loadCalendar(); // Recarga el calendario automáticamente
                } else {
                    alert('Error al actualizar la disponibilidad: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error al actualizar la disponibilidad:', error);
                alert('Error al actualizar la disponibilidad.');
            });
    });

    // Función para cargar los cupos actuales al abrir el formulario de disponibilidad
    function loadCurrentSlots(date) {
        fetch(`/veterinaria/citas_api.php?action=getSlots&date=${date}`)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos del backend:', data); // Log para depuración
                if (data.success) {
                    const totalSlotsInput = document.getElementById('totalSlots');
                    const timeSlotsContainer = document.getElementById('timeSlotsContainer');

                    // Limpia los inputs previos
                    timeSlotsContainer.innerHTML = '';

                    // Genera los inputs para los cupos existentes
                    data.slots.forEach((slot, index) => {
                        const slotDiv = document.createElement('div');
                        slotDiv.classList.add('time-slot');
                        slotDiv.setAttribute('data-slot-id', slot.id_horario);
                        slotDiv.innerHTML = `
                            <label for="startTime_${index + 1}">Hora de Inicio (Cupo ${index + 1}):</label>
                            <input type="time" id="startTime_${index + 1}" name="startTime_${index + 1}" value="${slot.hora_ini}" readonly>
                            <label for="endTime_${index + 1}">Hora de Fin (Cupo ${index + 1}):</label>
                            <input type="time" id="endTime_${index + 1}" name="endTime_${index + 1}" value="${slot.hora_fin}" readonly>
                            <button type="button" class="delete-slot-btn" data-slot-id="${slot.id_horario}">Eliminar</button>
                        `;
                        timeSlotsContainer.appendChild(slotDiv);
                    });

                    // Actualiza el valor del input "Cupos Totales"
                    totalSlotsInput.value = data.totalSlots;
                    totalSlotsInput.min = data.totalSlots; // No permite disminuir el número
                    totalSlotsInput.setAttribute('readonly', true); // Bloquea el input para que no sea editable

                    // Agrega eventos a los botones de eliminación
                    document.querySelectorAll('.delete-slot-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const slotId = this.getAttribute('data-slot-id');
                            deleteSlot(slotId);
                        });
                    });
                } else {
                    alert('Error al cargar los cupos: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al cargar los cupos:', error); // Log para depuración
                alert('Error al cargar los cupos.');
            });
    }

    // Llama a esta función cuando abras el formulario de disponibilidad
    document.getElementById('availabilityDate').addEventListener('change', function () {
        const selectedDate = this.value;
        loadCurrentSlots(selectedDate);
    });

    function loadAvailableHours(date) {

        fetch(`/veterinaria/citas_api.php?action=getAvailableHours&fecha=${date}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data); // Depuración

                const appointmentTime = document.getElementById('appointmentTime');
                if (!appointmentTime) {
                    console.error('El elemento appointmentTime no existe en el DOM.');
                    return;
                }

                appointmentTime.innerHTML = '<option value="">Seleccionar hora</option>'; // Limpia las opciones previas

                if (data.success) {
                    data.horarios.forEach(horario => {
                        const option = document.createElement('option');
                        option.value = horario.id_horario;
                        option.textContent = `${horario.hora_ini} - ${horario.hora_fin}`;
                        appointmentTime.appendChild(option);
                    });
                } else {
                    alert(data.message || 'No hay horarios disponibles');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los horarios');
            });
    }


    function updateAppointmentStatus(idCita, estado) {
        fetch('/veterinaria/citas_api.php?action=updateAppointmentStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_cita: idCita, estado: estado })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Estado de la cita actualizado correctamente.');
                    const selectedDay = document.querySelector('.calendar-day.selected');
                    if (selectedDay) {
                        const day = selectedDay.querySelector('.day-number').textContent;
                        const month = currentDate.getMonth() + 1;
                        const year = currentDate.getFullYear();
                        const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        fetchAppointments(formattedDate); // Recargar citas del día
                    }
                } else {
                    alert('Error al actualizar el estado de la cita: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al actualizar el estado de la cita:', error);
                alert('Error al actualizar el estado de la cita.');
            });
    }

    // ===== INICIALIZACIÓN =====
    initializeDateSelectors();
    loadCalendar();

    // Llamar a fetchAppointments con la fecha actual
    const today = new Date();
    const formattedDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    fetchAppointments(formattedDate);

    // Oculta el modal al cargar la página
    appointmentDetailsModal.style.display = 'none';

    // Función para mostrar el modal con los detalles de la cita
    function showAppointmentDetailsModal(appointment) {
        const appointmentDetailsModal = document.getElementById('appointmentDetailsModal');
        const modalContent = appointmentDetailsModal.querySelector('.modal-content');
        modalContent.innerHTML = `
            <span class="close-btn">&times;</span>
            <h2>Detalles de la Cita</h2>
            <p><strong>Hora:</strong> ${appointment.hora_ini} - ${appointment.hora_fin}</p>
            <p><strong>Nombre:</strong> ${appointment.nom_usu} ${appointment.apell_usu}</p>
            <p><strong>Teléfono:</strong> ${appointment.num_usu}</p>
            <p><strong>Descripción:</strong> ${appointment.descripcion}</p>
            <p><strong>Servicio:</strong> ${appointment.nom_tip_serv}</p>
            <p><strong>Tamaño:</strong> ${appointment.nom_tam}</p>
        `;
        appointmentDetailsModal.style.display = 'flex'; // Muestra el modal

        // Agrega el evento para cerrar el modal
        const closeBtn = modalContent.querySelector('.close-btn');
        closeBtn.addEventListener('click', function () {
            appointmentDetailsModal.style.display = 'none'; // Oculta el modal
        });
    }

    // Adjunta el evento al botón de información
    function attachAppointmentActionListeners() {
        document.querySelectorAll('.action-btn.info').forEach(button => {
            button.addEventListener('click', function () {
                const citaId = this.dataset.idCita;
                fetch(`/veterinaria/citas_api.php?action=getAppointmentDetails&id=${citaId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAppointmentDetailsModal(data.appointment);
                        } else {
                            alert('Error al obtener la información de la cita.');
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener la información de la cita:', error);
                        alert('Error al obtener la información de la cita.');
                    });
            });
        });
    }

    function confirmAppointment(idCita) {
        console.log('Confirmando cita con ID:', idCita);

        fetch('/veterinaria/citas_api.php?action=confirmAppointment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_cita: idCita }),
        })
            .then(response => {
                console.log('Estado de la respuesta:', response.status);
                if (!response.ok) {
                    throw new Error(`Error en la respuesta del servidor: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.success) {
                    alert('Cita confirmada correctamente.');

                    // Mover la cita a la sección de "Citas Confirmadas"
                    const appointmentDiv = document.getElementById(`appointment_${idCita}`);
                    if (appointmentDiv) {
                        console.log('Eliminando botones de confirmar y cancelar...');

                        // Eliminar los botones de confirmar y cancelar
                        const confirmButton = appointmentDiv.querySelector('.action-btn.confirm');
                        const cancelButton = appointmentDiv.querySelector('.action-btn.cancel');
                        console.log('Botón de confirmar encontrado:', confirmButton);
                        console.log('Botón de cancelar encontrado:', cancelButton);

                        if (confirmButton) {
                            confirmButton.remove();
                            console.log('Botón de confirmar eliminado.');
                        } else {
                            console.warn('Botón de confirmar no encontrado.');
                        }

                        if (cancelButton) {
                            cancelButton.remove();
                            console.log('Botón de cancelar eliminado.');
                        } else {
                            console.warn('Botón de cancelar no encontrado.');
                        }

                        // Mover la cita al contenedor de confirmadas
                        const confirmedSection = document.getElementById('confirmedAppointments');
                        confirmedSection.appendChild(appointmentDiv);
                    } else {
                        console.error('No se encontró el contenedor de la cita.');
                    }
                } else {
                    alert('Error al confirmar la cita: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al confirmar la cita:', error);
                alert('Error al confirmar la cita.');
            });
    }

    document.getElementById('appointmentsList').addEventListener('click', function (event) {
        if (event.target.classList.contains('action-btn') && event.target.classList.contains('cancel')) {
            const idCita = event.target.dataset.idCita;
            console.log('Botón de cancelar clicado, ID de cita:', idCita);
            cancelAppointment(idCita);
        }
    });

    function cancelAppointment(idCita) {
        console.log('Cancelando cita con ID:', idCita);

        fetch('/veterinaria/citas_api.php?action=cancelAppointment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_cita: idCita }),
        })
            .then(response => {
                console.log('Estado de la respuesta:', response.status);
                if (!response.ok) {
                    throw new Error(`Error en la respuesta del servidor: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.success) {
                    alert('Cita cancelada correctamente.');

                    // Mover la cita a la sección de "Citas Canceladas"
                    const appointmentDiv = document.getElementById(`appointment_${idCita}`);
                    if (appointmentDiv) {
                        // Eliminar botones innecesarios
                        const confirmButton = appointmentDiv.querySelector('.action-btn.confirm');
                        const cancelButton = appointmentDiv.querySelector('.action-btn.cancel');
                        if (confirmButton) confirmButton.remove();
                        if (cancelButton) cancelButton.remove();

                        console.log('Cita movida a la sección de canceladas.');
                    }

                    const cancelledSection = document.getElementById('cancelledList');
                    cancelledSection.appendChild(appointmentDiv);
                } else {
                    alert('Error al cancelar la cita: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al cancelar la cita:', error);
                alert('Error al cancelar la cita.');
            });
    }

    async function updateDayStatus(date, newStatus) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;

        try {
            const response = await fetch('/veterinaria/citas_api.php?action=updateDayStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ fecha: formattedDate, estado: newStatus })
            });
            const result = await response.json();
            if (result.success) {
                alert('Estado del día actualizado correctamente.');
                generateCalendar(new Date(year, month - 1, 1)); // Recargar el calendario
            } else {
                alert('Error al actualizar el estado del día: ' + result.message);
            }
        } catch (error) {
            console.error('Error al actualizar el estado del día:', error);
            alert('Ocurrió un error al intentar actualizar el estado del día.');
        }
    }
});