
<style>
.profile-avatar {
    font-family: 'Poppins', sans-serif;
    font-size: 1.2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.admin-name {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
}
</style>

</head>
<body>
    <div class="admin-container">
        <button class="hamburger-menu">
            <i class="fas fa-bars"></i>
        </button>

        <div class="admin-top-bar">
            <div class="admin-info">
                <div class="admin-icon">
                    <img src="../assets/logops.png" alt="Icono de Administrador" style="width: 30px; height: 30px;">
                </div>
                <div class="admin-title">ADMINISTRADOR</div>
                <div class="admin-separator">|</div>
                <div class="admin-name"><?php echo strtoupper($_SESSION['usuario_nombre']); ?></div>
            </div>
            <div class="admin-profile">
                <div class="profile-avatar" style="background-color: #8B5CF6; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    <?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?>
                </div>
               
            </div>
        </div>