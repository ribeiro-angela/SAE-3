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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="fixed-top navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <!-- LOGO -->
        <a href="/pages/accueil.php" class="navbar-brand d-flex align-items-center">
            <img src="/assets/image/logo.png" alt="logo AS" class="me-2" height="50">
            <span class="logo-text d-none d-md-inline">Armée du Salut</span>
        </a>

        <!-- BOUTON BURGER -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="/pages/accueil.php" class="nav-link active">Accueil</a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Actions Sociales</a>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="dropdown-item">Aide alimentaire</a></li>
                        <li><a href="#" class="dropdown-item">Hébergement d'urgence</a></li>
                        <li><a href="#" class="dropdown-item">Insertion professionnelle</a></li>
                        <li><a href="#" class="dropdown-item">Soutien aux familles</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="/pages/nos-missions.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Nos Missions</a>
                    <ul class="dropdown-menu">
                        <li><a href="/pages/histoire.php" class="dropdown-item">Notre histoire</a></li>
                        <li><a href="#" class="dropdown-item">Nos valeurs</a></li>
                        <li><a href="#" class="dropdown-item">Nos engagements</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">Actualités</a>
                </li>

                <li class="nav-item dropdown">
                    <a href="/pages/don.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Nous soutenir</a>
                    <ul class="dropdown-menu">
                        <li><a href="../pages/don.php" class="dropdown-item">Faire un don</a></li>
                        <li><a href="../pages/rejoindre.php" class="dropdown-item">Devenir bénévole</a></li>
                    </ul>
                </li>
            </ul>

            <!-- BOUTON DON -->
            <div class="ms-lg-3 mt-3 mt-lg-0">
                <a href="/pages/don.php" class="btn-donate">Faire un don</a>
            </div>
        </div>
    </div>
</header>

<main>