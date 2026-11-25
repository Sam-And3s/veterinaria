document.addEventListener('DOMContentLoaded', function() {
    var nav = document.querySelector('.nav');
    var navLinks = nav.querySelectorAll('a');
    var sections = document.querySelectorAll('section');
// Add scroll event listener to change navbar background
window.addEventListener('scroll', function() {
    if (window.scrollY > 50) { // Change background after scrolling 50px
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});
})

let originalUserDropdownContent = null; // Variable para almacenar el contenido original

function mostrarFormularioEdicion(correo, contacto, direccion) {
    const userDropdown = document.getElementById('userDropdown');
    // Guardar el contenido original antes de reemplazarlo
    originalUserDropdownContent = userDropdown.innerHTML;

    userDropdown.innerHTML = `
        <div class="user-info-header">
            <h4>Editar Información</h4>
        </div>
        <form id="editUserForm" action="actualizar_info.php" method="POST">
            <label for="editUserEmail">Correo:</label>
            <input type="email" id="editUserEmail" name="correo" value="${correo}" required>
            
            <label for="editUserPhone">Teléfono:</label>
            <input type="text" id="editUserPhone" name="contacto" value="${contacto}" required>
            
            <label for="editUserAddress">Dirección:</label>
            <input type="text" id="editUserAddress" name="direccion" value="${direccion}" required>
            
            <button type="submit" class="save-btn">Guardar</button>
            <button type="button" class="cancel-btn" onclick="cancelarEdicion()">Cancelar</button>
        </form>
    `;
}

function mostrarFormularioContrasena() {
    const userDropdown = document.getElementById('userDropdown');
    // Guardar el contenido original antes de reemplazarlo
    originalUserDropdownContent = userDropdown.innerHTML;

    userDropdown.innerHTML = `
        <div class="user-info-header">
            <h4>Cambiar Contraseña</h4>
        </div>
        <form id="changePasswordForm" action="cambiar_contraseña.php" method="POST">
            <label for="currentPassword">Contraseña Actual:</label>
            <input type="password" id="currentPassword" name="contrasena_actual" required>
            <br>
            <label for="newPassword">Nueva Contraseña:</label>
            <input type="password" id="newPassword" name="contrasena_nueva" required>
            <br>
            <label for="confirmPassword">Confirmar Contraseña:</label>
            <input type="password" id="confirmPassword" name="contrasena_confirmacion" required>
            <br>
            <button type="submit" class="save-btn">Guardar</button>
            <button type="button" class="cancel-btn" onclick="cancelarEdicion()">Cancelar</button>
        </form>
    `;
}

function cancelarEdicion() {
    const userDropdown = document.getElementById('userDropdown');
    if (originalUserDropdownContent) {
        // Restaurar el contenido original
        userDropdown.innerHTML = originalUserDropdownContent;
    } else {
        console.error("No se pudo restaurar el contenido original del menú de usuario.");
    }
}