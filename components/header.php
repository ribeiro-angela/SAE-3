<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Armée du Salut</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/png" href="/assets/image/logo.png">
</head>
<body>
<header class="fixed-top">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">

            <!-- LOGO -->
            <a href="/pages/accueil.php" class="navbar-brand">
                <img src="/assets/image/logo.png" alt="logo AS" class="logo-image">
                <!--<span class="logo-text">Armée du Salut</span> -->
            </a>

            <!-- BOUTON BURGER -->
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navMenu"
                    aria-controls="navMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a href="/pages/accueil.php" class="nav-link <?php echo $current_page == 'accueil.php' ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-info-circle"></i> Qui Sommes-Nous ?
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/pages/histoire.php" class="dropdown-item">
                                    <i class="fas fa-book"></i> Notre Histoire
                                </a></li>
                            <li><a href="/pages/nos-missions.php" class="dropdown-item">
                                    <i class="fas fa-hands-helping"></i> Nos Missions
                                </a></li>
                            <li><a href="#" class="dropdown-item">
                                    <i class="fas fa-heart"></i> Nos Valeurs
                                </a></li>
                            <li><a href="#" class="dropdown-item">
                                    <i class="fas fa-medal"></i> Nos Engagements
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="/pages/nos-missions.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-hands"></i> Actions Sociales
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="dropdown-item">
                                    <i class="fas fa-child"></i> Jeunesse
                                </a></li>
                            <li><a href="#" class="dropdown-item">
                                    <i class="fas fa-user-friends"></i> Exclusion Sociale
                                </a></li>
                            <li><a href="#" class="dropdown-item">
                                    <i class="fas fa-wheelchair"></i> Handicap
                                </a></li>
                            <li><a href="#" class="dropdown-item">
                                    <i class="fas fa-user-nurse"></i> Dépendance
                                </a></li>
                            <li><a href="#" class="dropdown-item">
                                    <i class="fas fa-handshake"></i> Actions Spécifiques
                                </a></li>
                            <li><a href="#" class="dropdown-item">
                                    <i class="fas fa-map-marker-alt"></i> Action de Proximité
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="/pages/don.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-gift"></i> Nous Soutenir
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/pages/don.php" class="dropdown-item">
                                    <i class="fas fa-euro-sign"></i> Faire un Don
                                </a></li>
                            <li><a href="/pages/rejoindre.php" class="dropdown-item">
                                    <i class="fas fa-user-plus"></i> Devenir Bénévole
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-newspaper"></i> Actualités
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li class="nav-item">
                                    <a href="/admin/dashboard.php" class="nav-link">
                                        <i class="fas fa-user-cog"></i> Admin
                                    </a>
                                </li>
                            <?php endif; ?>

                </ul>


                    <?php if ($isLoggedIn): ?>
                        <!-- SI CONNECTÉ -->
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($userName); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/dashboard.php" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                                    </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a href="/admin/logout.php" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                                    </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- SI NON CONNECTÉ -->
                        <li class="nav-item">
                            <a href="/admin/login.php" class="nav-link">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>

                <!-- BOUTON DON - VERSION DESKTOP -->
                <div class="nav-actions ms-lg-3">
                    <a href="/pages/don.php" class="btn-donate">
                        <i class="fas fa-heart"></i> Faire un don
                    </a>
                </div>

                <!-- BOUTON DON - VERSION MOBILE (dans le menu burger) -->
                <div class="menu-donate-container d-lg-none">
                    <a href="/pages/don.php" class="btn-donate">
                        <i class="fas fa-heart"></i> Faire un don
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>