<?php
require_once('../conexion.php'); // Incluye la conexión a la base de datos

require_once('../Templates/headAdmin.php');

// Asigna las variables de sesión a variables locales
$admin_id = $_SESSION['id'];
$admin_nombre = $_SESSION['usuario_nombre'];
?>
    <title>Puppy Shop - Administrador</title>
    <link rel="stylesheet" href="./css/productosadmin.css">           <!-- Estilos específicos de productos -->
<!-- link a la barra de informacion del administrador   -->
<?php 
require_once ('../Templates/infoadmin.php')
?>

<!-- link al menu de navegacion  -->
<?php 
require_once ('../Templates/navbarAdmin.php')
?>

    <link rel="stylesheet" href="./css/citasadmin.css">

    <!-- Contenido principal -->
    <!-- Contenedor principal del área administrativa -->
    <div class="admin-container">
        <!-- Sección del Calendario -->
        <div class="calendar-container">
            <!-- Barra de navegación del calendario -->
            <div class="calendar-nav">
                <!-- Navegación entre meses -->
                <div class="month-navigation">
                    <button class="month-btn prev-month">◀</button>          <!-- Botón mes anterior -->
                    <div class="date-selector">
                        <span class="current-date"></span> <!-- Agrega este elemento -->

                        <select class="month-select"></select>               <!-- Selector de mes -->
                        <input type="number" class="year-input" min="1900" max="9999">  <!-- Entrada para el año -->
                    </div>
                    <button class="month-btn next-month">▶</button>          <!-- Botón mes siguiente -->
                </div>
                <!-- Botones de vista del calendario -->
                <div class="calendar-views">
                    <button class="view-btn active">Día</button>             <!-- Vista diaria (activa) -->
                    <button class="view-btn">Semana</button>                 <!-- Vista semanal -->
                    <button class="view-btn">Mes</button>                    <!-- Vista mensual -->
                    <button class="view-btn">Año</button>                    <!-- Vista anual -->
                </div>
            </div>
            <!-- Encabezado con días de la semana -->
            <div class="calendar-header">
                <span>Lun</span>                                             <!-- Lunes -->
                <span>Mar</span>                                             <!-- Martes -->
                <span>Mié</span>                                             <!-- Miércoles -->
                <span>Jue</span>                                             <!-- Jueves -->
                <span>Vie</span>                                             <!-- Viernes -->
                <span>Sáb</span>                                             <!-- Sábado -->
                <span>Dom</span>                                             <!-- Domingo -->
            </div>
    
            <!-- Cuadrícula del calendario -->
            <div class="calendar-grid" id="calendarGrid">
                <!-- Los días se generarán dinámicamente mediante JavaScript -->
            </div>
            
            <!-- Indicadores de estado de las citas -->
            <div class="calendar-indicators">
                <div class="indicator-item">
                <span class="indicator-dot no-service"></span>            <!-- Indicador sin servicio -->
                <span class="indicator-text">NO DISPONIBLE</span>
                </div>
                
            </div>
        </div>
        <!-- Panel lateral de resumen de citas -->
        <div class="appointments-section">
            <div id="appointmentsForToday">
                <h2>Citas del Día</h2>
                <div id="appointmentsList"></div>
            </div>

            <div id="confirmedAppointments">
                <h2>Citas Confirmadas</h2>
                <div id="confirmedList"></div>
            </div>

            <div id="cancelledAppointments">
                <h2>Citas Canceladas</h2>
                <div id="cancelledList"></div>
            </div>
        </div>
    </div>
 

    <div class=""></div>



<!-- Modal para agregar cita -->
<div id="addAppointmentModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Agregar Cita</h2>
        <form id="addAppointmentForm">
            <label for="appointmentDate">Fecha:</label>
            <input type="date" id="appointmentDate" name="appointmentDate" readonly required>
            <br>
            <label for="appointmentTime">Hora:</label>
            <select id="appointmentTime" name="appointmentTime" required>
                <option value="">Seleccionar hora</option>
            </select>
            <br>
            <label for="appointmentService">Servicio:</label>
            <select name="appointmentService" id="appointmentService" required>
                <option value="">Seleccionar servicio</option>
                <?php
                // Consulta para obtener los tipos de servicios
                $query_tipos = "SELECT * FROM tipos_servicios";
                $result_tipos_select = mysqli_query($conexion, $query_tipos);

                // Llena el select con los tipos de servicios
                while ($tipo = mysqli_fetch_assoc($result_tipos_select)) {
                    echo "<option value='" . $tipo['id_tip_serv'] . "'>" . $tipo['nom_tip_serv'] . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="appointmentSize">Tamaño:</label>
            <select name="appointmentSize" id="appointmentSize" required>
                <option value="">Seleccionar tamaño</option>
                <?php
                // Consulta para obtener los tamaños
                $query_tamanos = "SELECT * FROM tamaño";
                $result_tamanos_select = mysqli_query($conexion, $query_tamanos);

                // Llena el select con los tamaños
                while ($tamano = mysqli_fetch_assoc($result_tamanos_select)) {
                    echo "<option value='" . $tamano['id_tamaño'] . "'>" . $tamano['nom_tam'] . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="description">Descripción:</label>
            <br>
            <textarea id="description" name="description" placeholder="Ingrese una descripción"></textarea>
            <br>
            <label for="appointmentUser">Registrado por:</label>
            <input type="text" id="appointmentUser" name="appointmentUser" readonly value="<?php echo $admin_nombre; ?>">
            <input type="hidden" id="appointmentUserId" name="appointmentUserId" value="<?php echo $admin_id; ?>">
            <br><br>
            <button type="submit">Guardar Cita</button>
        </form>
    </div>
</div>
<!-- Modal para gestionar la disponibilidad -->
<div id="availabilityModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Gestionar Disponibilidad</h2>
        <form id="availabilityForm">
            <label for="availabilityDate">Fecha:</label>
            <input type="text" id="availabilityDate" name="availabilityDate" readonly>
            <br>
            <label for="totalSlots">Cupos Totales:</label>
            <input type="number" id="totalSlots" name="totalSlots" min="0" readonly>
            <button type="button" id="addSlotButton">Agregar Cupo</button>
            <br>
            <div id="timeSlotsContainer" style="max-height: 150px; overflow-y: auto;">
                <!-- Aquí se generarán dinámicamente los inputs de los cupos -->
            </div>
            <br>
            <button type="submit">Guardar Disponibilidad</button>
        </form>
    </div>
</div>

<div id="manageNoServiceModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Gestionar Días Sin Servicio</h2>
        <p>Selecciona un rango de fechas para marcar como sin servicio o para reactivar el servicio.</p>
        <form id="manageNoServiceForm">
            <label for="startDateNoService">Fecha de Inicio:</label>
            <input type="date" id="startDateNoService" required>

            <label for="endDateNoService">Fecha de Fin:</label>
            <input type="date" id="endDateNoService" required>

            <label for="noServiceAction">Acción:</label>
            <select id="noServiceAction">
                <option value="mark">Marcar como sin servicio</option>
                <option value="unmark">Reactivar servicio</option>
            </select>

            <button type="submit">Aplicar</button>
        </form>
        <div id="noServiceMessage" class="message"></div>
    </div>
</div>

<div id="appointmentDetailsModal" class="modal">
    <div class="modal-content">
        <!-- El contenido se generará dinámicamente -->
    </div>
</div>

    <!-- Enlaces a archivos JavaScript -->
    <!-- Script específicos para citas -->
    <script>
    window.ADMIN_ID = "<?php echo $_SESSION['id']; ?>";
    window.ADMIN_NOMBRE = "<?php echo $_SESSION['usuario_nombre']; ?>";
</script>
    <script src="./js/citasadmin.js"></script>

    </body>
</html>


<script>
    document.getElementById('totalSlots').addEventListener('input', function () {
        const totalSlots = parseInt(this.value);
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        timeSlotsContainer.innerHTML = ''; // Limpia los inputs previos

        if (totalSlots > 0) {
            for (let i = 1; i <= totalSlots; i++) {
                const slotDiv = document.createElement('div');
                slotDiv.classList.add('time-slot');
                slotDiv.innerHTML = `
                    <label for="startTime_${i}">Hora de Inicio (Cupo ${i}):</label>
                    <input type="time" id="startTime_${i}" name="startTime_${i}" required>
                    <label for="endTime_${i}">Hora de Fin (Cupo ${i}):</label>
                    <input type="time" id="endTime_${i}" name="endTime_${i}" required>
                    <button type="button" class="delete-slot-btn" data-slot-id="${i}">Eliminar</button>
                `;
                timeSlotsContainer.appendChild(slotDiv);
            }
        }

        // Agrega eventos a los botones de eliminación
        document.querySelectorAll('.delete-slot-btn').forEach(button => {
            button.addEventListener('click', function () {
                const slotId = this.getAttribute('data-slot-id');
                deleteSlot(slotId);
            });
        });
    });

    // Función para eliminar un cupo
    function deleteSlot(slotId) {
        fetch('/veterinaria/citas_api.php?action=deleteSlot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `slotId=${slotId}`
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Cupo eliminado correctamente');
                    // Actualiza la interfaz
                    document.querySelector(`.time-slot[data-slot-id="${slotId}"]`).remove();
                    reorganizeSlots(); // Reorganiza los índices después de eliminar
                } else {
                    alert('Error al eliminar el cupo: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el cupo');
            });
    }

    document.getElementById('addSlotButton').addEventListener('click', function () {
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const totalSlotsInput = document.getElementById('totalSlots');
        const currentSlots = parseInt(totalSlotsInput.value) || 0;

        // Incrementa el número de cupos
        const newSlotIndex = currentSlots + 1;
        totalSlotsInput.value = newSlotIndex;

        // Crea un nuevo input para el rango de horas
        const slotDiv = document.createElement('div');
        slotDiv.classList.add('time-slot');
        slotDiv.setAttribute('data-slot-index', newSlotIndex); // Agrega un atributo para identificar el índice
        slotDiv.innerHTML = `
            <label for="startTime_${newSlotIndex}">Hora de Inicio (Cupo ${newSlotIndex}):</label>
            <input type="time" id="startTime_${newSlotIndex}" name="startTime_${newSlotIndex}" required>
            <label for="endTime_${newSlotIndex}">Hora de Fin (Cupo ${newSlotIndex}):</label>
            <input type="time" id="endTime_${newSlotIndex}" name="endTime_${newSlotIndex}" required>
            <button type="button" class="delete-slot-btn" data-slot-index="${newSlotIndex}">Eliminar</button>
        `;
        timeSlotsContainer.appendChild(slotDiv);

        // Agrega evento al botón de eliminación
        slotDiv.querySelector('.delete-slot-btn').addEventListener('click', function () {
            const slotIndex = this.getAttribute('data-slot-index');
            slotDiv.remove();

            // Actualiza el valor de "Cupos Totales"
            totalSlotsInput.value = parseInt(totalSlotsInput.value) - 1;

            // Reorganiza los índices de los cupos restantes
            reorganizeSlots();
        });
    });

    // Función para reorganizar los índices de los cupos
    function reorganizeSlots() {
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const totalSlotsInput = document.getElementById('totalSlots');
        const timeSlots = timeSlotsContainer.querySelectorAll('.time-slot');

        timeSlots.forEach((slot, index) => {
            const newIndex = index + 1; // Los índices comienzan desde 1
            slot.setAttribute('data-slot-index', newIndex);

            // Actualiza los IDs y nombres de los inputs
            const startTimeInput = slot.querySelector('input[name^="startTime_"]');
            const endTimeInput = slot.querySelector('input[name^="endTime_"]');
            const deleteButton = slot.querySelector('.delete-slot-btn');

            startTimeInput.id = `startTime_${newIndex}`;
            startTimeInput.name = `startTime_${newIndex}`;
            endTimeInput.id = `endTime_${newIndex}`;
            endTimeInput.name = `endTime_${newIndex}`;
            deleteButton.setAttribute('data-slot-index', newIndex);

            // Actualiza las etiquetas
            const startTimeLabel = slot.querySelector(`label[for^="startTime_"]`);
            const endTimeLabel = slot.querySelector(`label[for^="endTime_"]`);
            startTimeLabel.setAttribute('for', `startTime_${newIndex}`);
            startTimeLabel.textContent = `Hora de Inicio (Cupo ${newIndex}):`;
            endTimeLabel.setAttribute('for', `endTime_${newIndex}`);
            endTimeLabel.textContent = `Hora de Fin (Cupo ${newIndex}):`;
        });

        // Actualiza el valor de "Cupos Totales"
        totalSlotsInput.value = timeSlots.length;
    }
</script>