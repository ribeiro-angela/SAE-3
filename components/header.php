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
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="icon" type="image/png" href="/assets/image/logo.png">
</head>
<body>
<header class="fixed-top">
    <nav class="navbar">
        <div class="nav-container">

            <!-- LOGO -->
            <a href="/pages/accueil.php" class="nav-logo">
                <img src="/assets/image/logo.png" alt="logo AS" class="logo-image">
                <span class="logo-text">Armée du Salut</span>
            </a>

            <!-- MENU -->
            <div class="nav-menu">
                <div class="nav-item">
                    <a href="/pages/accueil.php" class="nav-link active">Accueil</a>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link">Actions Sociales</a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-link">Aide alimentaire</a>
                        <a href="#" class="dropdown-link">Hébergement d'urgence</a>
                        <a href="#" class="dropdown-link">Insertion professionnelle</a>
                        <a href="#" class="dropdown-link">Soutien aux familles</a>
                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="/pages/nos-missions.php" class="nav-link">Nos Missions</a>
                    <div class="dropdown-menu">
                        <!-- 🔗 Lien mis à jour vers histoire.php -->
                        <a href="/pages/histoire.php" class="dropdown-link">Notre histoire</a>
                        <a href="#" class="dropdown-link">Nos valeurs</a>
                        <a href="#" class="dropdown-link">Nos engagements</a>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link">Actualités</a>
                </div>

                <div class="nav-item dropdown">
                    <a href="/pages/don.php" class="nav-link">Nous soutenir</a>
                    <div class="dropdown-menu">
                        <a href="accueil.php" class="nav-link active">Accueil</a>
                        <a href="nos-missions.php" class="nav-link">Nos Missions</a>
                        <a href="histoire.php" class="dropdown-link">Notre histoire</a>
                        <a href="don.php" class="dropdown-link">Faire un don</a>
                        <a href="rejoindre.php" class="dropdown-link">Devenir bénévole</a>
                    </div>
                </div>
            </div>

            <!-- BOUTONS DROITE -->
            <div class="nav-actions">
                <a href="/pages/don.php" class="btn-donate">Faire un don</a>
                <div class="menu-toggle" id="mobile-menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>

        </div>
    </nav>
</header>

<main>
