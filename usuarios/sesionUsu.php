<?php
require_once '../conexion.php';
$consultarRoles = mysqli_query($conexion, "SELECT * FROM roles") or die("Error al consultar roles");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PuppyShop - Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="css/sesion.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="content">
                <h1>Bienvenido a PuppyShop</h1>
                <p>¡Para una mejor experiencia con tus mascotas!</p>
            </div>
            <div class="image-container">
                <img src="assets/goldenboy.png" alt="Perro Feliz">
            </div>
        </div>
        <div class="right-side">
            <img src="assets/logo.png" alt="PuppyShop" class="logo">
            <div class="form-container">
                <div class="form-selector">
                    <button class="selector-btn active" data-form="login">Iniciar Sesión</button>
                    <button class="selector-btn" data-form="register">Registrarse</button>
                </div>
                <form id="login-form" class="form active" method="POST" action="procesar_login.php">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Correo electrónico" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Contraseña" required>
                        <i class="ri-eye-line toggle-password"></i>
                    </div>
                    <div>
                        <select class="role-select" name="id_rol" required>
                            <option value="" disabled selected>Seleccionar rol</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($consultarRoles)) {
                                echo "<option value='{$row['id_rol']}'>{$row['nom_rol']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Iniciar Sesión</button>
                </form>
                <form id="register-form" class="form" action="procesar_registro.php" method="POST">
                    <div class="input-group">
                        <input type="number" name="documento_usu" id="documento_usu" placeholder="Número de documento" required>
                    </div>  
                    <div class="input-group">
                        <select class="role-select" name="tipo_doc_usu" id="tipo_doc_usu" required>
                            <option value="" disabled selected>Tipo de documento</option>
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="TI">Tarjeta de Identidad</option>
                            <option value="PP">Pasaporte</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" name="nom_usu" id="nom_usu" placeholder="Nombre" required>
                    </div>
                    <div class="input-group">
                        <input type="text" name="apell_usu" id="apell_usu" placeholder="Apellido" required>
                    </div>
                    <div class="input-group">
                        <input type="number" name="num_usu" id="num_usu" placeholder="Número de contacto" required>
                    </div>
                    <div class="input-group">
                        <input type="text" name="direc_usu" id="direc_usu" placeholder="Dirección" required>
                    </div>
                    <div class="input-group">
                        <input type="email" name="correo_usu" id="correo_usu" placeholder="Correo electrónico" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="contra_usu" id="contra_usu" placeholder="Contraseña" required>
                        <i class="ri-eye-line toggle-password"></i>
                    </div>
                    <input type="hidden" name="id_rol" value="2">
                    <input type="hidden" name="id_estado" value="1">
                    <button type="submit" class="submit-btn">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
    <script src="js/sesion.js"></script>
</body>
</html>