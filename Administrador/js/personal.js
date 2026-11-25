// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', function() {
    // Selecciona todos los botones de pestañas con la clase 'tab-btn'
    const tabs = document.querySelectorAll('.tab-btn');
    // Selecciona todos los paneles con la clase 'panel'
    const panels = document.querySelectorAll('.panel');

    // Función para cambiar entre paneles con animación
    function switchPanel(targetId) {
        // Recorre todos los paneles
        panels.forEach(panel => {
            // Si el panel es el objetivo
            if (panel.id === targetId) {
                // Configura el panel para mostrarse
                panel.style.display = 'flex';         // Establece disposición flexible
                panel.style.flexDirection = 'column';   // Organiza elementos en columna
                panel.style.opacity = '0';             // Inicia invisible para la animación

                // Usa requestAnimationFrame para animación suave
                requestAnimationFrame(() => {
                    panel.classList.add('active');     // Marca el panel como activo
                    panel.style.opacity = '1';         // Hace visible el panel gradualmente
                });
            } else {
                // Para los demás paneles
                panel.style.opacity = '0';             // Hace invisible el panel
                panel.classList.remove('active');     // Remueve la clase activa
                setTimeout(() => {
                    panel.style.display = 'none';     // Oculta el panel después de 300ms
                }, 300);
            }
        });
    }

    // Recorre cada pestaña para agregar el evento de clic
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Verifica si la pestaña clickeada no está ya activa
            if (!tab.classList.contains('active')) {
                // Desactiva todas las pestañas
                tabs.forEach(t => t.classList.remove('active'));
                // Activa la pestaña clickeada
                tab.classList.add('active');
                // Obtiene el ID del panel correspondiente usando el atributo data-panel
                const targetPanel = `${tab.dataset.panel}-panel`;
                // Llama a la función para cambiar al panel correspondiente
                switchPanel(targetPanel);
            }
        });
    });

    // Mantener el código existente de búsqueda y filtros
    // Configuración de la funcionalidad de búsqueda
    const searchInputs = document.querySelectorAll('.search-bar input');     // Selecciona todos los campos de búsqueda
    searchInputs.forEach(input => {
        // Agrega un evento que se activa cada vez que se escribe en el campo
        input.addEventListener('input', (e) => {
            // Obtiene el término de búsqueda y lo convierte a minúsculas
            const searchTerm = e.target.value.toLowerCase();
            // Encuentra el panel más cercano al campo de búsqueda
            const panel = e.target.closest('.panel');
            // Selecciona todas las tarjetas (administradores y equipo) dentro del panel
            const cards = panel.querySelectorAll('.admin-card, .team-card');

            // Recorre cada tarjeta para filtrar
            cards.forEach(card => {
                // Obtiene el nombre del título h3 y lo convierte a minúsculas
                const name = card.querySelector('h3').textContent.toLowerCase();
                // Verifica si el nombre incluye el término de búsqueda
                const shouldShow = name.includes(searchTerm);
                // Muestra u oculta la tarjeta según el resultado
                card.style.display = shouldShow ? 'flex' : 'none';
            });
        });
    });

    // Selecciona todos los elementos de filtro desplegable
    const filterSelects = document.querySelectorAll('.filter-select');
    // Recorre cada selector de filtro
    filterSelects.forEach(select => {
        // Agrega un evento que se activa cuando cambia la selección
        select.addEventListener('change', (e) => {
            // Obtiene el valor seleccionado del filtro
            const filterValue = e.target.value;
            // Encuentra el panel más cercano al selector
            const panel = e.target.closest('.panel');
            // Selecciona todas las tarjetas dentro del panel actual
            const cards = panel.querySelectorAll('.admin-card, .team-card');

            // Recorre cada tarjeta para aplicar el filtro
            cards.forEach(card => {
                // Si el filtro está en "todos"
                if (filterValue === 'all') {
                    card.style.display = 'flex';         // Muestra todas las tarjetas
                   } else {
                    // Obtiene el estado y especialidad de la tarjeta desde sus atributos data
                    const cardStatus = card.dataset.status;       // Estado (activo/inactivo)
                    const cardSpecialty = card.dataset.specialty; // Especialidad del miembro
                    // Verifica si la tarjeta coincide con el filtro seleccionado
                    const shouldShow = cardStatus === filterValue || cardSpecialty === filterValue;
                    // Muestra u oculta la tarjeta según el resultado
                    card.style.display = shouldShow ? 'flex' : 'none';
                }
            });
        });
    });
});

// Funcionalidad del Modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('adminModal');
    const addBtn = document.querySelector('.add-btn');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.querySelector('.cancel-btn');

    // Abrir modal
    addBtn.addEventListener('click', () => {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

    // Cerrar modal
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Cerrar al hacer clic fuera del modal
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
});

// Funcionalidad CRUD para administradores
document.addEventListener('DOMContentLoaded', function() {
    // Manejadores para los botones CRUD
    document.querySelectorAll('.admin-actions .action-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const adminId = this.closest('.admin-card').dataset.adminId;

            if (this.classList.contains('edit-btn')) {
                editAdmin(adminId);
            } else if (this.classList.contains('info-btn')) {
                viewAdminInfo(adminId);
            } else if (this.classList.contains('delete-btn')) {
                deleteAdmin(adminId);
            }
        });
    });
});

// Función para editar administrador
function editAdmin(adminId) {
    fetch(`crud_admin.php?action=get&id=${adminId}`)
        .then(response => response.json())
        .then(data => {
            // Rellenar el modal con los datos del administrador
            document.getElementById('editAdminForm').elements['nombre'].value = data.nom_usu;
            document.getElementById('editAdminForm').elements['apellido'].value = data.apell_usu;
            document.getElementById('editAdminForm').elements['correo'].value = data.correo_usu;

            document.getElementById('editAdminForm').elements['telefono'].value = data.num_usu;

            // Mostrar el modal de edición
            const modal = document.getElementById('editAdminModal');
            modal.style.display = 'block';
        });
}

// Función para ver información del administrador
function viewAdminInfo(adminId) {
    fetch(`crud_admin.php?action=get&id=${adminId}`)
        .then(response => response.json())
        .then(data => {
            // Mostrar la información en el modal de información
            document.getElementById('adminInfoModal').innerHTML = `
                <div class="admin-info-content">
                    <h3>${data.nom_usu} ${data.apell_usu}</h3>
                    <p><strong>Correo:</strong> ${data.correo_usu}</p>
                    <p><strong>Teléfono:</strong> ${data.num_usu}</p>
                    <p><strong>Estado:</strong> ${data.id_estado === 1 ? 'Activo' : 'Inactivo'}</p>
                </div>
            `;

            // Mostrar el modal de información
            const modal = document.getElementById('adminInfoModal');
            modal.style.display = 'block';
        });
}

// Función para eliminar administrador
function deleteAdmin(adminId) {
    if (confirm('¿Está seguro de que desea eliminar este administrador?')) {
        fetch('crud_admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&id=${adminId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar la tarjeta del DOM
                document.querySelector(`.admin-card[data-admin-id="${adminId}"]`).remove();
                alert('Administrador eliminado exitosamente');
            } else {
                alert('Error al eliminar el administrador');
            }
        });
    }
}
document.addEventListener('DOMContentLoaded', function() {
    // Manejadores de modales
    const modals = document.querySelectorAll('.modal');
    const closeButtons = document.querySelectorAll('.close, .cancel-btn');

    // Cerrar modales
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            modals.forEach(modal => modal.style.display = 'none');
        });
    });

    // Botón editar
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const adminCard = this.closest('.admin-card');
            if (!adminCard) {
                console.error('No se encontró el contenedor .admin-card para este botón.');
                return;
            }

            const adminId = adminCard.dataset.adminId;
            if (!adminId) {
                console.error('No se encontró el atributo data-admin-id en el contenedor .admin-card.');
                return;
            }

            loadAdminForEdit(adminId);
        });
    });

    // Botón info
    document.querySelectorAll('.info-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const adminId = this.closest('.admin-card').dataset.adminId;
            loadAdminInfo(adminId);
        });
    });

    // Botón eliminar
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const adminId = this.closest('.admin-card').dataset.adminId;
            deleteAdmin(adminId);
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-password')) {
            const toggleBtn = e.target.closest('.toggle-password');
            const passwordInput = toggleBtn.closest('.password-field')?.querySelector('input[type="password"], input[type="text"]');

            if (passwordInput) {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;

                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            }
        }
    });


    // Guardar cambios
    document.getElementById('saveEditBtn').addEventListener('click', function() {
        const form = document.getElementById('editAdminForm');
        const formData = new FormData(form);

        fetch('crud_admin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Verificamos si la respuesta es exitosa (código de estado 200)
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.text();   // Usamos .text() para ver lo que está respondiendo
        })
        .then(data => {
            console.log('Respuesta del servidor:', data);   // Muestra la respuesta para debug
            try {
                const jsonData = JSON.parse(data);   // Intenta parsear la respuesta como JSON
                if (jsonData.success) {
                    alert('Cambios guardados exitosamente');
                    location.reload();
                } else {
                    alert('Error al guardar los cambios');
                }
            } catch (error) {
                alert('Respuesta no es JSON válido');
                console.error('Error al parsear JSON:', error);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud o al procesar la respuesta:', error);
        });
    });

    // Evento para el formulario de agregar nuevo administrador (dentro del modal)
    const newAdminForm = document.getElementById('newAdminForm');
    if (newAdminForm) {
        newAdminForm.addEventListener('submit', function(e) {
            e.preventDefault();
            crearNuevoAdmin(new FormData(this));
        });
    }
});
function loadAdminForEdit(adminId) {
    fetch(`crud_admin.php?action=get&id=${adminId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_documento_usu').value = data.documento_usu;
            document.getElementById('edit_nombre').value = data.nom_usu + ' ' + data.apell_usu;
            document.getElementById('edit_tipo_documento').value = data.tipo_doc_usu;
            document.getElementById('edit_num_documento').value = data.documento_usu;
            document.getElementById('edit_correo').value = data.correo_usu;
            document.getElementById('edit_telefono').value = data.num_usu;
            document.getElementById('edit_direccion').value = data.direc_usu;

            document.getElementById('editAdminModal').style.display = 'block';
        });
}

function loadAdminInfo(adminId) {
    fetch(`crud_admin.php?action=get&id=${adminId}`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="admin-info-details">
                    <p><strong>Nombre:</strong> ${data.nom_usu} ${data.apell_usu}</p>
                    <p><strong>Tipo Documento:</strong> ${data.tipo_doc_usu}</p>
                    <p><strong>Número Documento:</strong> ${data.documento_usu}</p>
                    <p><strong>Correo:</strong> ${data.correo_usu}</p>
                    <p><strong>Teléfono:</strong> ${data.num_usu}</p>
                    <p><strong>Dirección:</strong> ${data.direc_usu}</p>
                    <p><strong>Estado:</strong> ${data.id_estado === 1 ? 'Activo' : 'Inactivo'}</p>
                </div>
            `;
            document.getElementById('adminInfoContent').innerHTML = content;
            document.getElementById('infoAdminModal').style.display = 'block';
        });
}

function deleteAdmin(adminId) {
    if(confirm('¿Está seguro de que desea eliminar este administrador? Esta acción no se puede deshacer.')) {
        fetch('crud_admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&id=${adminId}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Administrador eliminado exitosamente');
                location.reload();
            } else {
                alert('Error al eliminar el administrador');
            }
        });
    }
}
// Mantener todo el código existente de administradores hasta la sección de equipo veterinario

// Funciones para el equipo veterinario
document.addEventListener('DOMContentLoaded', function() {
    // Event delegation para los botones de acción del equipo
    const teamGrid = document.querySelector('.team-grid');
    if (teamGrid) {
        teamGrid.addEventListener('click', function(e) {
            const target = e.target.closest('.action-btn');
            if (!target) return;

            const memberId = target.getAttribute('data-id');

            if (target.classList.contains('edit-btn')) {
                editarMiembroEquipo(memberId);
            } else if (target.classList.contains('info-btn')) {
                verInfoMiembroEquipo(memberId);
            } else if (target.classList.contains('delete-btn')) {
                eliminarMiembroEquipo(memberId);
            }
        });
    }

    // Manejo del modal de nuevo miembro
    const nuevoMiembroBtn = document.getElementById('nuevo-miembro-btn');
    const teamModal = document.getElementById('team-modal');

    if (nuevoMiembroBtn && teamModal) {
        nuevoMiembroBtn.addEventListener('click', () => {
            teamModal.style.display = 'block';
        });
    }

    // Manejo de la foto de perfil para nuevo miembro
    const photoInput = document.getElementById('foto_eqp');
    const previewImage =document.getElementById('preview-image');

    if (photoInput && previewImage) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.style.display = 'block';
                    previewImage.style.backgroundImage = `url(${e.target.result})`;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Formulario de registro de nuevo miembro
    const teamForm = document.getElementById('team-register-form');
    if (teamForm) {
        teamForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('procesar_equipo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Miembro registrado correctamente');
                    location.reload();
                } else {
                    throw new Error(data.message || 'Error en el registro');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error en el proceso de registro');
            });
        });
    }
});

function editarMiembroEquipo(memberId) {
    if (!memberId) return;

    fetch('obtener_miembro.php?id=' + memberId)
        .then(response => response.json())
        .then(data => {
            const editModal = document.getElementById('edit-team-modal');
            if (!editModal) return;

            const form = editModal.querySelector('#edit-team-form');
            if (form) {
                form.querySelector('[name="num_doc"]').value = data.num_doc;
                form.querySelector('[name="correo_eqp"]').value = data.correo_eqp;
                form.querySelector('[name="telefono_eqp"]').value = data.telefono_eqp;
                form.querySelector('[name="prop_eqp"]').value = data.prop_eqp;
                form.querySelector('[name="desc_eqp"]').value = data.desc_eqp || '';
            }

            editModal.style.display = 'block';
        })
        .catch(error => alert('Error al cargar los datos del miembro'));
}

function verInfoMiembroEquipo(memberId) {
    if (!memberId) return;

    fetch('obtener_miembro.php?id=' + memberId)
        .then(response => response.json())
        .then(data => {
            const infoModal = document.getElementById('info-team-modal');
            if (!infoModal) return;

            const modalBody = infoModal.querySelector('.modal-body');
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="member-info-detail">
                        <img src="${data.foto_eqp ? `/veterinaria/${data.foto_eqp}` : '/veterinaria/assets/img/default-profile.jpg'}" alt="Foto" class="member-photo">
                        <div class="member-details">
                            <h3>Dr(a). ${data.nom_eqp} ${data.apell_eqp}</h3>
                            <p><strong>Documento:</strong> ${data.nom_tipo_doc} ${data.num_doc}</p>
                            <p><strong>Especialidad:</strong> ${data.prop_eqp}</p>
                            <p><strong>Teléfono:</strong> ${data.telefono_eqp}</p>
                            <p><strong>Correo:</strong> ${data.correo_eqp}</p>
                            <p><strong>Descripción:</strong> ${data.desc_eqp || 'No disponible'}</p>
                        </div>
                    </div>
                `;
            }

            infoModal.style.display = 'block';
        })
        .catch(error => alert('Error al cargar la información del miembro'));
}
function crearNuevoAdmin(formData) {
    fetch('crud_admin.php?action=create', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error en la respuesta del servidor: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Administrador creado exitosamente');
            location.reload(); // Recargar la página para mostrar el nuevo administrador
        } else {
            alert(`Error al crear el administrador: ${data.message || 'Error desconocido'}`);
        }
    })
    .catch(error => {
        console.error('Error al crear el administrador:', error);
        alert('Error de red o al procesar la respuesta');
    });
}
function eliminarMiembroEquipo(memberId) {
    if (!confirm('¿Está seguro de eliminar este miembro?')) return;

    fetch('eliminar_miembro.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${memberId}`
    })
    .then(response => {
        console.log('Estado de la respuesta:', response.status); // Depuración
        return response.json();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data); // Depuración
        if (data.success) {
            alert('Miembro eliminado correctamente');
            location.reload();
        } else {
            throw new Error(data.message || 'Error al eliminar');
        }
    })
    .catch(error => {
        console.error('Error al eliminar el miembro:', error);
        alert('Error al eliminar el miembro: ' + error.message);
    });
}

// Función para guardar cambios del miembro
window.guardarCambiosMiembro = function() {
    const form = document.getElementById('edit-team-form');
    if (!form) return;

    fetch('actualizar_miembro.php', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cambios guardados exitosamente');
            location.reload();
        } else {
            alert('Error al guardar los cambios: ' + (data.message || 'Error desconocido')); // Mostrar el mensaje del backend
        }
    })
    .catch(error => alert('Error de red al guardar los cambios')); // Error de conexión
};

// Manejo global de modales del equipo
document.addEventListener('click', function(e) {
    if (e.target.matches('.modal .close') ||
        e.target.matches('.modal .btn-cancel') ||
        e.target.matches('.modal')) {
        const modal = e.target.closest('.modal');
        if (modal) modal.style.display = 'none';
    }
});

document.querySelectorAll('.team-card .edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const memberId = this.getAttribute('data-id');
        if (!memberId) {
            console.error('No se encontró el ID del miembro.');
            return;
        }

        // Llama a la función para cargar los datos del miembro
        cargarDatosMiembro(memberId);
    });
});

function cargarDatosMiembro(memberId) {
    fetch(`obtener_miembro.php?id=${memberId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            console.log('Datos del miembro:', data); // Depuración

            // Rellena los campos del formulario en el modal
            const form = document.getElementById('edit-team-form');
            form.querySelector('[name="num_doc"]').value = data.num_doc || '';
            form.querySelector('[name="correo_eqp"]').value = data.correo_eqp || '';
            form.querySelector('[name="telefono_eqp"]').value = data.telefono_eqp || '';
            form.querySelector('[name="prop_eqp"]').value = data.prop_eqp || '';
            form.querySelector('[name="desc_eqp"]').value = data.desc_eqp || '';

            // Muestra el modal
            const modal = document.getElementById('edit-team-modal');
            modal.style.display = 'block';
        })
        .catch(error => {
            console.error('Error al cargar los datos del miembro:', error);
            alert('Error al cargar los datos del miembro: ' + error.message);
        });
}