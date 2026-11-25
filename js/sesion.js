// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', function () {
    // Elementos para cambiar entre formularios
    // Selecciona todos los botones que permiten cambiar entre login y registro
    const selectorBtns = document.querySelectorAll('.selector-btn');
    // Obtiene el formulario de inicio de sesión por su ID
    const loginForm = document.getElementById('login-form');
    // Obtiene el formulario de registro por su ID
    const registerForm = document.getElementById('register-form');

    // Funcionalidad para cambiar entre formularios
    function switchForm(formType) {
        // Objeto que define qué formulario mostrar y cuál ocultar
        const forms = {
            // Para login: muestra el form de login y oculta el de registro
            'login': { show: loginForm, hide: registerForm },
            // Para registro: muestra el form de registro y oculta el de login
            'register': { show: registerForm, hide: loginForm }
        };

        // Actualiza el estado de los botones selectores
        selectorBtns.forEach(btn => {
            // Activa el botón seleccionado y desactiva el otro
            btn.classList.toggle('active', btn.dataset.form === formType);
        });

        // Oculta el formulario actual con animación
        forms[formType].hide.style.opacity = '0'; // Hace transparente el formulario
        forms[formType].hide.style.transform = 'translateY(10px)'; // Mueve el formulario hacia abajo
        setTimeout(() => {
            // Después de 300ms, remueve la clase active
            forms[formType].hide.classList.remove('active');
        }, 300);

        // Muestra el formulario seleccionado
        setTimeout(() => {
            // Añade la clase active para hacer visible el formulario
            forms[formType].show.classList.add('active');
            // Solicita el siguiente frame de animación para garantizar suavidad
            requestAnimationFrame(() => {
                // Hace el formulario completamente visible
                forms[formType].show.style.opacity = '1';
                // Mueve el formulario a su posición original con animación
                forms[formType].show.style.transform = 'translateY(0)';
            });
        }, 350); // Espera 350ms para iniciar la animación de entrada
    }
    
    // Manejo del cambio en el selector de roles
    document.querySelector('.role-select')?.addEventListener('change', function() {
        // Obtiene el contenedor de credenciales de administrador
        const adminCredentials = document.querySelector('.admin-credentials');
        // Obtiene el campo de entrada dentro del contenedor
        const credentialsInput = adminCredentials.querySelector('input');
        
        // Verifica si se seleccionó el rol de administrador
        if (this.value === 'admin') {
            // Muestra el campo de credenciales
            adminCredentials.style.display = 'block';
            // Hace obligatorio el campo
            credentialsInput.required = true;
        } else {
            // Oculta el campo de credenciales
            adminCredentials.style.display = 'none';
            // Hace opcional el campo
            credentialsInput.required = false;
        }
    });
    
    // Manejadores de clic para los botones selectores
    selectorBtns.forEach(btn => {
        // Añade evento de clic a cada botón para cambiar entre formularios
        btn.addEventListener('click', () => {
            switchForm(btn.dataset.form);
        });
    });

    // Control de visibilidad de contraseña
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Obtiene el campo de contraseña anterior al botón
            const input = button.previousElementSibling;
            // Obtiene el tipo actual del campo
            const type = input.getAttribute('type');
            // Cambia entre tipo 'password' y 'text'
            input.setAttribute('type', type === 'password' ? 'text' : 'password');
            // Alterna los iconos de ojo abierto/cerrado
            button.classList.toggle('ri-eye-line');
            button.classList.toggle('ri-eye-off-line');
        });
    });

    // Animaciones de campos de entrada
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        // Cuando el campo recibe el foco
        input.addEventListener('focus', () => {
            // Añade sombra y cambia el fondo
            input.style.boxShadow = '0 0 0 2px var(--primary)';
            input.style.background = 'var(--white)';
        });

        // Cuando el campo pierde el foco
        input.addEventListener('blur', () => {
            // Si el campo está vacío, restaura el estilo original
            if (!input.value) {
                input.style.boxShadow = 'none';
                input.style.background = 'var(--bg-light)';
            }
        });
    });

    // Animaciones de botones
    const allButtons = document.querySelectorAll('.submit-btn, .google-btn');
    allButtons.forEach(button => {
        // Cuando el mouse entra en el botón
        button.addEventListener('mouseenter', () => {
            // Eleva ligeramente el botón
            button.style.transform = 'translateY(-2px)';
            // Si es el botón de envío, añade sombra especial
            if (button.classList.contains('submit-btn')) {
                button.style.boxShadow = '0 8px 15px rgba(192, 132, 252, 0.2)';
            }
        });

        // Cuando el mouse sale del botón
        button.addEventListener('mouseleave', () => {
            // Restaura los estilos originales
            button.style.transform = '';
            button.style.boxShadow = '';
        });
    });

    // Manejo del envío de formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        // Añade evento de envío a cada formulario
        form.addEventListener('submit', async (e) => {
            // Previene el envío tradicional del formulario
if (form.id === 'login-form') return; // Solo evita envío si es login
            // Obtiene el botón de envío
            const button = form.querySelector('.submit-btn');
            // Guarda el texto original del botón
            const originalText = button.innerHTML;

            // Estado de carga
            button.innerHTML = `
                <div class="loader">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            `;
            // Deshabilita el botón durante el proceso
            button.disabled = true;

            try {
                // Simula una petición al servidor de 1.5 segundos
                await new Promise(resolve => setTimeout(resolve, 1500));

                // Estado de éxito
                button.innerHTML = `
                    <i class="ri-check-line"></i>
                    <span>¡Éxito!</span>
                `;
                // Añade clase de éxito para estilos
                button.classList.add('success');

                // Restaura el estado original después de 2 segundos
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.classList.remove('success');
                    form.reset();
                }, 2000);

            } catch (error) {
                // Estado de error
                button.innerHTML = `
                    <i class="ri-close-line"></i>
                    <span>Error</span>
                `;
                // Añade clase de error para estilos
                button.classList.add('error');

                // Restaura el estado original después de 2 segundos
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.classList.remove('error');
                }, 2000);
            }
        });
    });
});
