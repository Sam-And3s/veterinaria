document.addEventListener('DOMContentLoaded', function() {
    // Previsualización de la imagen
    const inputFoto = document.getElementById('foto_eqp');
    const btnFoto = document.querySelector('.profile-photo-btn');

    inputFoto.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                btnFoto.style.backgroundImage = `url(${e.target.result})`;
                btnFoto.style.backgroundSize = 'cover';
                btnFoto.style.backgroundPosition = 'center';
            };
            reader.readAsDataURL(file);
        }
    });

    // Manejo del formulario
    const form = document.getElementById('team-register-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);

        fetch('../procesar_equipo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Miembro registrado exitosamente');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    });

    // Botón cancelar
    document.querySelector('.cancel-btn').addEventListener('click', function() {
        document.getElementById('team-modal').style.display = 'none';
    });
});