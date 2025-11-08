<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Armée du Salut</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <span class="logo-text">Armée du Salut</span>
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
                        <a href="/pages/accueil.php" class="nav-link">Accueil</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                           aria-expanded="false">Qui Sommes-Nous ? </a>
                        <ul class="dropdown-menu">
                            <li><a href="/pages/histoire.php" class="dropdown-item">Notre Histoire</a></li>
                            <li><a href="/pages/nos-missions.php" class="dropdown-item">Nos Missions</a></li>
                            <li><a href="#" class="dropdown-item">Nos Valeurs</a></li>
                            <li><a href="#" class="dropdown-item">Nos Engagements</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="/pages/nos-missions.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                           aria-expanded="false">Actions Sociales</a>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="dropdown-item">Jeunesse</a></li>
                            <li><a href="#" class="dropdown-item">Exclusion Sociale</a></li>
                            <li><a href="#" class="dropdown-item">Handicap</a></li>
                            <li><a href="#" class="dropdown-item">Dépendance</a></li>
                            <li><a href="#" class="dropdown-item">Actions Spécifiques</a></li>
                            <li><a href="#" class="dropdown-item">Action de Proximité</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="/pages/don.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                           aria-expanded="false">Nous Soutenir</a>
                        <ul class="dropdown-menu">
                            <li><a href="../pages/don.php" class="dropdown-item">Faire un Don</a></li>
                            <li><a href="../pages/rejoindre.php" class="dropdown-item">Devenir Bénévole</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">Actualités</a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">Contact</a>
                    </li>

                </ul>

                <!-- BOUTON DON - VERSION DESKTOP -->
                <div class="nav-actions ms-lg-3">
                    <a href="/pages/don.php" class="btn-donate">Faire un don</a>
                </div>

                <!-- BOUTON DON - VERSION MOBILE (dans le menu burger) -->
                <div class="menu-donate-container d-lg-none">
                    <a href="/pages/don.php" class="btn-donate">Faire un don</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>