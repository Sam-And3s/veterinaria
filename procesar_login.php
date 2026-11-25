<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar el inicio de sesión
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = $_POST['password'];
    $id_rol = mysqli_real_escape_string($conexion, $_POST['id_rol']);

    $query = "SELECT * FROM usuario WHERE correo_usu = '$email' AND id_rol = '$id_rol'";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        if (password_verify($password, $usuario['contra_usu'])) {
            // Guardar los datos del usuario en la sesión
            $_SESSION['id'] = $usuario['documento_usu'];
            $_SESSION['usuario_nombre'] = $usuario['nom_usu'];
            $_SESSION['usuario_rol'] = $usuario['id_rol'];
            $_SESSION['correo_usu'] = $usuario['correo_usu'];
            $_SESSION['direc_usu'] = $usuario['direc_usu'];
            $_SESSION['num_usu'] = $usuario['num_usu'];

            if ($usuario['id_rol'] == 1) {
                header("Location: Administrador/indexadmin.php");
                exit();
            } else {
                header("Location: usuarios/inicioUsu.php");
                exit();
            }
        }
    }

    // Si las credenciales son incorrectas
    echo "<script>
            alert('Correo o contraseña incorrectos');
            window.location = 'sesion.php';
          </script>";
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Devolver los datos del usuario desde la sesión
    if (isset($_SESSION['id'])) {
        echo json_encode([
            'success' => true,
            'cliente' => [
                'id' => $_SESSION['id'],
                'nombre' => $_SESSION['usuario_nombre'],
                'direccion' => $_SESSION['direc_usu'],
                'telefono' => $_SESSION['num_usu'],
                'correo' => $_SESSION['correo_usu']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No hay sesión activa.'
        ]);
    }
}
?>