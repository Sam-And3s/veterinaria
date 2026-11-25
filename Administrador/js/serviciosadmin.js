document.addEventListener('DOMContentLoaded', function () {
    const addPlanButtons = document.querySelectorAll('.add-plan-btn');
    const modal = document.getElementById('edit-product-modal');
    const closeModalBtn = modal.querySelector('.close');
    const cancelModalBtn = modal.querySelector('.btn-cancel');
    const saveButton = document.getElementById('btn-save');

    // Abrir el modal al hacer clic en "Nuevo Plan"
    addPlanButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Limpia los campos del formulario
            document.getElementById('edit-id-product').value = '';
            document.getElementById('edit-nom-product').value = '';
            document.getElementById('edit-id-cat').value = '';
            document.getElementById('edit-prec-product').value = '';
            modal.style.display = 'block';
        });
    });

    // Cerrar el modal al hacer clic en el botón de cierre o cancelar
    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    cancelModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Cerrar el modal al hacer clic fuera del contenido del modal
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Delegación de eventos para abrir el modal en modo "crear"
    document.addEventListener('click', function (event) {
        if (event.target.closest('.add-plan-btn')) {
            const idTipServ = event.target.closest('.add-plan-btn').dataset.idTipServ;

            // Configura el modal para crear un nuevo servicio
            document.getElementById('edit-id-product').value = ''; // Limpia el ID
            document.getElementById('edit-nom-product').value = '';
            document.getElementById('edit-prec-product').value = '';
            document.getElementById('edit-descrip-product').value = '';
            document.getElementById('edit-id-cat').value = idTipServ;

            document.getElementById('modal-title').textContent = 'Nuevo Servicio';
            saveButton.setAttribute('data-mode', 'create'); // Cambia el modo a "create"
            document.getElementById('edit-product-modal').style.display = 'block';
        }
    });

    // Delegación de eventos para abrir el modal en modo "editar"
    document.addEventListener('click', function (event) {
        if (event.target.closest('.edit-btn')) {
            const button = event.target.closest('.edit-btn');
            const planCard = button.closest('.plan-card'); // Encuentra la tarjeta del servicio
            const idProduct = planCard.dataset.id; // ID del servicio (debe estar en el dataset)
            const nomProduct = planCard.querySelector('.plan-name').value;
            const precProduct = planCard.querySelector('.price-value').textContent;
            const descripProduct = planCard.querySelector('.services-list li').textContent;

            // Rellena los campos del modal con los datos del servicio
            document.getElementById('edit-id-product').value = idProduct;
            document.getElementById('edit-nom-product').value = nomProduct;
            document.getElementById('edit-prec-product').value = precProduct;
            document.getElementById('edit-descrip-product').value = descripProduct;

            document.getElementById('modal-title').textContent = 'Editar Servicio';
            saveButton.setAttribute('data-mode', 'edit'); // Cambia el modo a "edit"
            document.getElementById('edit-product-modal').style.display = 'block';
        }
    });

    // Maneja el clic en el botón de guardar
    saveButton.addEventListener('click', function () {
        const mode = saveButton.getAttribute('data-mode'); // Obtiene el modo actual
        if (mode === 'create') {
            crearServicio();
        } else if (mode === 'edit') {
            editarServicio();
        }
    });
});

// Este script maneja la funcionalidad de los servicios en el panel de administración

// Función para guardar cambios en un servicio
function guardarCambiosProducto() {
    const idProduct = document.getElementById('edit-id-product').value;
    const nomProduct = document.getElementById('edit-nom-product').value;
    const idCat = document.getElementById('edit-id-cat').value;
    const precProduct = document.getElementById('edit-prec-product').value;
    const descripProduct = document.getElementById('edit-descrip-product').value;

    if (!nomProduct || !idCat || !precProduct) {
        alert('Por favor, completa todos los campos obligatorios.');
        return;
    }

    const data = {
        id_product: idProduct,
        nom_serv: nomProduct,
        precio_serv: precProduct,
        id_tip_serv: idCat,
        descrip_serv: descripProduct
    };

    fetch('guardar_Servicio.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Servicio guardado correctamente.');
                location.reload(); // Recarga la página para actualizar los datos
            } else {
                alert('Error al guardar el servicio: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el servicio.');
        });
}

// Función para crear un nuevo servicio
function crearServicio() {
    const nomProduct = document.getElementById('edit-nom-product').value;
    const idCat = document.getElementById('edit-id-cat').value;
    const precProduct = document.getElementById('edit-prec-product').value;
    const descripProduct = document.getElementById('edit-descrip-product').value;

    if (!nomProduct || !idCat || !precProduct) {
        alert('Por favor, completa todos los campos obligatorios.');
        return;
    }

    const data = {
        nom_serv: nomProduct,
        precio_serv: precProduct,
        id_tip_serv: idCat,
        descrip_serv: descripProduct
    };

    fetch('guardar_Servicio.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Servicio creado correctamente.');
                location.reload(); // Recarga la página para actualizar los datos
            } else {
                alert('Error al crear el servicio: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al crear el servicio.');
        });
}

// Función para editar un servicio existente
function editarServicio() {
    const idProduct = document.getElementById('edit-id-product').value;
    const nomProduct = document.getElementById('edit-nom-product').value;
    const idCat = document.getElementById('edit-id-cat').value;
    const precProduct = document.getElementById('edit-prec-product').value;
    const descripProduct = document.getElementById('edit-descrip-product').value;

    if (!idProduct || !nomProduct || !idCat || !precProduct) {
        alert('Por favor, completa todos los campos obligatorios.');
        return;
    }

    const data = {
        id_product: idProduct,
        nom_serv: nomProduct,
        precio_serv: precProduct,
        id_tip_serv: idCat,
        descrip_serv: descripProduct
    };

    fetch('editar_Servicio.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Servicio actualizado correctamente.');
                location.reload(); // Recarga la página para actualizar los datos
            } else {
                alert('Error al actualizar el servicio: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el servicio.');
        });
}

// Función para agregar un servicio al grid
function agregarPlanAlGrid(servicio) {
    const panelId = servicio.id_tip_serv; // ID del tipo de servicio
    const panel = document.getElementById(`${panelId}-panel`);
    const plansGrid = panel.querySelector('.plans-grid');

    const planCard = document.createElement('div');
    planCard.className = 'plan-card';
    planCard.dataset.id = servicio.id_serv; // Agrega el ID del servicio
    planCard.innerHTML = `
        <div class="plan-header">
            <input type="text" class="plan-name" value="${servicio.nom_serv}" readonly>
            <div class="plan-actions">
                <button class="edit-btn" title="Editar"><i class="fas fa-edit"></i></button>
                <button class="delete-btn" title="Eliminar"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <div class="plan-price">
            <span class="currency">$</span>
            <span class="price-value">${parseFloat(servicio.precio_serv).toFixed(2)}</span>
        </div>
        <div class="plan-services">
            <ul class="services-list">
                <li>${servicio.descrip_serv || 'Sin descripción'}</li>
            </ul>
        </div>
        <div class="plan-footer">
            <label class="status-toggle">
                <input type="checkbox" checked>
                <span class="slider">Activo</span>
            </label>
        </div>
    `;
    plansGrid.appendChild(planCard);
}

// Función para obtener servicios por tipo
function obtenerServicios(idTipServ) {
    fetch(`obtener_servicios.php?id_tip_serv=${idTipServ}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const panel = document.getElementById(`${idTipServ}-panel`);
                if (!panel) {
                    console.error(`No se encontró el panel con ID: ${idTipServ}-panel`);
                    return;
                }

                const plansGrid = panel.querySelector('.plans-grid');
                if (!plansGrid) {
                    console.error(`No se encontró el contenedor .plans-grid en el panel con ID: ${idTipServ}-panel`);
                    return;
                }

                plansGrid.innerHTML = ''; // Limpia los planes existentes

                if (data.servicios.length === 0) {
                    plansGrid.innerHTML = '<p>No hay servicios disponibles para este tipo.</p>';
                    return;
                }

                data.servicios.forEach(servicio => {
                    agregarPlanAlGrid(servicio);
                });
            } else {
                console.error('Error al obtener los servicios:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al obtener los servicios:', error);
        });
}

// Delegación de eventos para editar y eliminar servicios
document.addEventListener('DOMContentLoaded', function () {
    // Delegación de eventos para el botón de editar
    document.addEventListener('click', function (event) {
        if (event.target.closest('.edit-btn')) {
            const button = event.target.closest('.edit-btn');
            const planCard = button.closest('.plan-card'); // Encuentra la tarjeta del servicio
            const idProduct = planCard.dataset.id; // ID del servicio (debe estar en el dataset)
            const nomProduct = planCard.querySelector('.plan-name').value;
            const precProduct = planCard.querySelector('.price-value').textContent;
            const descripProduct = planCard.querySelector('.services-list li').textContent;

            // Rellena los campos del modal con los datos del servicio
            document.getElementById('edit-id-product').value = idProduct;
            document.getElementById('edit-nom-product').value = nomProduct;
            document.getElementById('edit-prec-product').value = precProduct;
            document.getElementById('edit-descrip-product').value = descripProduct;

            // Abre el modal
            document.getElementById('edit-product-modal').style.display = 'block';
        }
    });

    // Delegación de eventos para el botón de eliminar
    document.addEventListener('click', function (event) {
        if (event.target.closest('.delete-btn')) {
            const button = event.target.closest('.delete-btn');
            const planCard = button.closest('.plan-card'); // Encuentra la tarjeta del servicio
            const idProduct = planCard.dataset.id; // ID del servicio (debe estar en el dataset)

            if (confirm('¿Estás seguro de que deseas eliminar este servicio?')) {
                fetch(`eliminar_Servicio.php?id=${idProduct}`, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Servicio eliminado correctamente.');
                            planCard.remove(); // Elimina la tarjeta del DOM
                        } else {
                            alert('Error al eliminar el servicio: ' + result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al eliminar el servicio.');
                    });
            }
        }
    });
});

// Inicialización de pestañas y carga de servicios
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.service-panel');

    // Cargar servicios al cambiar de pestaña
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const panelId = tab.getAttribute('data-panel').replace('-panel', '');
            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));

            tab.classList.add('active');
            const activePanel = document.getElementById(`${panelId}-panel`);
            if (activePanel) {
                activePanel.classList.add('active');
                obtenerServicios(panelId);
            }
        });
    });

    // Cargar servicios al cargar la página
    panels.forEach(panel => {
        const panelId = panel.id.replace('-panel', '');
        obtenerServicios(panelId);
    });
});