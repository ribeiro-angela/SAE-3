<?php
include_once '../components/header.php'
?>
    <main>
    <!-- SECTION HERO -->
    <section class="slogan-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="slogan-title">Soupe,<br>Savon, Salut</h1>
                    <h3 class="slogan-subtitle">Ensemble, nous offrons espoir et soutien à ceux qui en ont besoin</h3>
                    <div class="button_accueil">
                        <a href="#contact" class="btn-accueil btn-contact-us">
                            <i class="fas fa-envelope"></i> Nous Contacter
                        </a>
                        <a href="/pages/don.php" class="btn-accueil btn-don-accueil">
                            <i class="fas fa-heart"></i> Je fais un Don
                        </a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="/admin/dashboard.php" class="btn-accueil" style="background-color: #2ecc71; border-color: #2ecc71;">
                                <i class="fas fa-tachometer-alt"></i> Espace Admin
                            </a>
                        <?php else: ?>
                            <a href="/admin/login.php" class="btn-accueil" style="background-color: #3498db; border-color: #3498db;">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="/assets/image/imageSlogan.jpg" alt="Personnes bénéficiant du soutien" class="slogan-image">
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION ASSOCIATION -->
    <section class="section-association">
        <div class="container">
            <h2 class="section-title">L'ASSOCIATION</h2>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <p class="association-text">
                        Présente dans 28 départements et 11 régions, la Fondation de l'Armée du Salut agit chaque jour pour accueillir, accompagner et redonner espoir à ceux qui traversent des moments difficiles.
                        À travers plus de 200 établissements, ses équipes soutiennent enfants, familles, personnes isolées, âgées ou en situation de handicap, avec bienveillance et respect.
                        <br><br>
                        Animée par ses valeurs chrétiennes d'amour, de solidarité et d'espérance, la Fondation croit en la dignité et en la valeur de chaque personne, et œuvre pour bâtir une société plus humaine et fraternelle.
                    </p>
                    <div style="margin-top: 30px;">
                        <a href="/pages/histoire.php" class="btn-accueil btn-contact-us">
                            <i class="fas fa-book"></i> Notre Histoire
                        </a>
                        <a href="/pages/nos-missions.php" class="btn-accueil btn-don-accueil" style="margin-left: 10px;">
                            <i class="fas fa-hands-helping"></i> Nos Missions
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="association-images">
                        <img src="/assets/image/1.png" alt="Établissement" class="association-image">
                        <img src="/assets/image/2.png" alt="Bénévoles" class="association-image">
                        <img src="/assets/image/3.png" alt="Distribution" class="association-image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ACTIONS SOCIALES -->
    <section class="section-actions-sociales">
        <div class="container-fluid px-0">
            <h2 class="text-center mb-5">NOS ACTIONS SOCIALES</h2>

            <div class="actions-carousel-wrapper">
                <div class="actions-carousel" id="actionsCarousel">
                    <!-- Carte 1 - Jeunesse -->
                    <div class="action-card active">
                        <div class="row">
                            <div class="action-image-container">
                                <div class="action-image">
                                    <img src="/assets/image/jeunesse.jpg" alt="jeunesse" class="img-fluid">
                                </div>
                            </div>
                            <div class="action-content-container">
                                <div class="action-content">
                                    <h3>Jeunesse</h3>
                                    <p>Accompagnement des jeunes en difficulté, soutien éducatif et insertion sociale pour construire ensemble un avenir meilleur.</p>
                                    <a href="/pages/nos-missions.php" class="btn-action">En savoir plus</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 2 - Exclusion Sociale -->
                    <div class="action-card">
                        <div class="row">
                            <div class="action-image-container">
                                <div class="action-image">
                                    <img src="/assets/image/exclusionSociale.jpg" alt="Exclusion Sociale" class="img-fluid">
                                </div>
                            </div>
                            <div class="action-content-container">
                                <div class="action-content">
                                    <h3>Exclusion Sociale</h3>
                                    <p>Accueil, hébergement et réinsertion des personnes sans-abri et en situation de grande précarité.</p>
                                    <a href="/pages/nos-missions.php" class="btn-action">En savoir plus</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 3 - Handicap -->
                    <div class="action-card">
                        <div class="row">
                            <div class="action-image-container">
                                <div class="action-image">
                                    <img src="/assets/image/handicape.jpg" alt="Handicap" class="img-fluid">
                                </div>
                            </div>
                            <div class="action-content-container">
                                <div class="action-content">
                                    <h3>Handicap</h3>
                                    <p>Structures adaptées et accompagnement personnalisé pour favoriser l'autonomie et l'épanouissement.</p>
                                    <a href="/pages/nos-missions.php" class="btn-action">En savoir plus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contrôles de navigation -->
                <button class="carousel-control prev" onclick="changeSlide(-1)">
                    <span>‹</span>
                </button>
                <button class="carousel-control next" onclick="changeSlide(1)">
                    <span>›</span>
                </button>

                <!-- Indicateurs -->
                <div class="carousel-indicators">
                    <button class="indicator active" onclick="goToSlide(0)"></button>
                    <button class="indicator" onclick="goToSlide(1)"></button>
                    <button class="indicator" onclick="goToSlide(2)"></button>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION NOUS REJOINDRE -->
    <section class="section-rejoindre">
        <div class="container">
            <h2 class="section-title">NOUS REJOINDRE</h2>
            <div class="rejoindre-cards">
                <div class="rejoindre-card">
                    <img src="/assets/image/benevole.png" alt="Devenir Bénévole">
                    <div class="rejoindre-card-content">
                        <h3>Devenir Bénévole</h3>
                        <p>Chaque personne a des compétences qui peuvent être utiles aux personnes fragiles. Venez découvrir nos différentes missions de bénévolat.</p>
                        <a href="/pages/rejoindre.php" class="btn-accueil btn-don-accueil" style="margin-top: 15px;">
                            <i class="fas fa-user-plus"></i> Rejoindre
                        </a>
                    </div>
                </div>
                <div class="rejoindre-card">
                    <img src="/assets/image/salarie.png" alt="Devenir Salarié">
                    <div class="rejoindre-card-content">
                        <h3>Devenir Salarié</h3>
                        <p>Près de 100 métiers sont exercés chaque jour au service des personnes accueillies. Et si c'était un emploi pour vous dans l'Armée du Salut ?</p>
                        <a href="/pages/rejoindre.php" class="btn-accueil btn-don-accueil" style="margin-top: 15px;">
                            <i class="fas fa-briefcase"></i> Postuler
                        </a>
                    </div>
                </div>
                <div class="rejoindre-card">
                    <img src="/assets/image/soldat.png" alt="Devenir Soldat">
                    <div class="rejoindre-card-content">
                        <h3>Devenir Soldat</h3>
                        <p>Un soldat est une personne qui croit en Dieu et fréquente un poste (paroisse) de la Congrégation de l'Armée du Salut et qui décide de s'engager en faveur de son Église.</p>
                        <a href="/pages/rejoindre.php" class="btn-accueil btn-don-accueil" style="margin-top: 15px;">
                            <i class="fas fa-hands"></i> S'engager
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION TÉMOIGNAGES -->
    <section class="section-temoignages">
        <div class="container">
            <h2 class="section-title text-white">TÉMOIGNAGES</h2>
            <div class="temoignage-container">
                <div class="temoignage-image">
                    <img src="/assets/image/temoignage.png" alt="Personne témoignant">
                </div>
                <div class="temoignage-card">
                    <span class="quote-mark">"</span>
                    <p class="temoignage-text">
                        J'ai perdu ma maman à 14 ans et ai dû m'occuper ensuite de mon père qui est parti en dépression, puis en hôpital psychiatrique[...] Et après? C'est la chute ...
                    </p>
                    <div class="temoignage-author">
                        <strong>Axel, 26 ans</strong>
                        <span>Hébergé dans un établissement de la Fondation de l'Armée du Salut</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION CTA POUR L'ADMIN -->
    <?php if (!isset($_SESSION['user_id'])): ?>
        <section style="background: linear-gradient(135deg, #2c3e50 0%, #212f3c 100%); padding: 80px 20px; text-align: center;">
            <div class="container">
                <h2 style="color: white; font-size: 2.5rem; margin-bottom: 20px;">
                    <i class="fas fa-users"></i> Espace Bénévoles & Équipe
                </h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.2rem; margin-bottom: 40px;">
                    Vous êtes membre de l'équipe ? Accédez à votre espace de gestion
                </p>
                <a href="/admin/login.php" class="btn-accueil btn-don-accueil" style="font-size: 1.1rem; padding: 15px 40px;">
                    <i class="fas fa-sign-in-alt"></i> Se connecter à l'espace admin
                </a>
            </div>
        </section>
    <?php endif; ?>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.action-card');
        const indicators = document.querySelectorAll('.indicator');
        const carousel = document.querySelector('.actions-carousel');
        let autoSlideInterval;

        function changeSlide(direction) {
            indicators[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + direction + slides.length) % slides.length;
            carousel.style.transform = `translateX(${-currentSlide * 33.333}%)`;
            indicators[currentSlide].classList.add('active');
            resetAutoSlide();
        }

        function goToSlide(slideIndex) {
            indicators[currentSlide].classList.remove('active');
            currentSlide = slideIndex;
            carousel.style.transform = `translateX(${-currentSlide * 33.333}%)`;
            indicators[currentSlide].classList.add('active');
            resetAutoSlide();
        }

        function startAutoSlide() {
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
            }
            autoSlideInterval = setInterval(() => {
                changeSlide(1);
            }, 10000);
        }

        function resetAutoSlide() {
            startAutoSlide();
        }

        document.addEventListener('DOMContentLoaded', function() {
            indicators[0].classList.add('active');
            startAutoSlide();

            const wrapper = document.querySelector('.actions-carousel-wrapper');
            wrapper.addEventListener('mouseenter', () => {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                }
            });

            wrapper.addEventListener('mouseleave', () => {
                startAutoSlide();
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') changeSlide(-1);
            if (e.key === 'ArrowRight') changeSlide(1);
        });

        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && !autoSlideInterval) {
                startAutoSlide();
            }
        });
    </script>

<?php
include_once '../components/footer.php'
?>