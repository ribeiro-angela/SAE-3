<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <img src="/assets/image/logo.png" alt="Logo" class="sidebar-logo">
        <h3>Administration</h3>
    </div>

    <nav class="sidebar-nav">
        <a href="/admin/dashboard.php" class="nav-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> <span>Tableau de bord</span>
        </a>
        <a href="/admin/benevoles.php" class="nav-item <?php echo strpos($current_page, 'benevoles') !== false ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> <span>Bénévoles</span>
        </a>
        <a href="/admin/missions.php" class="nav-item <?php echo strpos($current_page, 'missions') !== false ? 'active' : ''; ?>">
            <i class="fas fa-tasks"></i> <span>Missions</span>
        </a>
        <a href="/admin/evenements.php" class="nav-item <?php echo strpos($current_page, 'evenements') !== false ? 'active' : ''; ?>">
            <i class="fas fa-calendar"></i> <span>Événements</span>
        </a>
        <a href="/admin/partenaires.php" class="nav-item <?php echo strpos($current_page, 'partenaires') !== false ? 'active' : ''; ?>">
            <i class="fas fa-handshake"></i> <span>Partenaires</span>
        </a>
        <a href="/admin/donateurs.php" class="nav-item <?php echo strpos($current_page, 'donateurs') !== false ? 'active' : ''; ?>">
            <i class="fas fa-heart"></i> <span>Donateurs</span>
        </a>
        <a href="/admin/statistiques.php" class="nav-item <?php echo $current_page == 'statistiques.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i> <span>Statistiques</span>
        </a>
        <a href="/admin/actualites.php" class="nav-item <?php echo strpos($current_page, 'actualites') !== false || strpos($current_page, 'medias') !== false ? 'active' : ''; ?>">
            <i class="fas fa-newspaper"></i> <span>Actualités</span>
        </a>

        <div class="sidebar-divider"></div>

        <a href="/pages/accueil.php" class="nav-item" target="_blank">
            <i class="fas fa-external-link-alt"></i> <span>Voir le site</span>
        </a>
        <a href="/admin/logout.php" class="nav-item logout">
            <i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <p>Connecté en tant que</p>
        <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur'); ?></strong>
    </div>
</aside>