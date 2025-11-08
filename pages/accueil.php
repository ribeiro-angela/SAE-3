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
                            <a href="#" class="btn-accueil btn-contact-us">Nous Contacter</a>
                            <a href="#" class="btn-accueil btn-don-accueil">Je fais un Don</a>
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
                            Présente dans 28 départements et 11 régions, la Fondation de l’Armée du Salut agit chaque jour pour accueillir, accompagner et redonner espoir à ceux qui traversent des moments difficiles.
                            À travers plus de 200 établissements, ses équipes soutiennent enfants, familles, personnes isolées, âgées ou en situation de handicap, avec bienveillance et respect.
                            <br>
                            Animée par ses valeurs chrétiennes d’amour, de solidarité et d’espérance, la Fondation croit en la dignité et en la valeur de chaque personne, et œuvre pour bâtir une société plus humaine et fraternelle.
                        </p>
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
                        <!-- Carte 1 - Aide Alimentaire -->
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
                                        <a href="#" class="btn-action">En savoir plus</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte 2 - Hébergement -->
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
                                        <a href="#" class="btn-action">En savoir plus</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte 3 - Insertion -->
                        <div class="action-card">
                            <div class="row">
                                <div class="action-image-container">
                                    <div class="action-image">
                                        <img src="/assets/image/handicape.jpg" alt="Handicap"
                                             class="img-fluid">
                                    </div>
                                </div>
                                <div class="action-content-container">
                                    <div class="action-content">
                                        <h3>Handicap</h3>
                                        <p>Structures adaptées et accompagnement personnalisé pour favoriser l'autonomie et l'épanouissement.</p>
                                        <a href="#" class="btn-action">En savoir plus</a>
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
                        </div>
                    </div>
                    <div class="rejoindre-card">
                        <img src="/assets/image/salarie.png" alt="Devenir Salarié">
                        <div class="rejoindre-card-content">
                            <h3>Devenir Salarié</h3>
                            <p>Près de 100 métiers sont exercés chaque jour au service des personnes accueillies. Et si c'était un emploi pour vous dans l'Armée du Salut ?</p>
                        </div>
                    </div>
                    <div class="rejoindre-card">
                        <img src="/assets/image/soldat.png" alt="Devenir Soldat">
                        <div class="rejoindre-card-content">
                            <h3>Devenir Soldat</h3>
                            <p>Un soldat est une personne qui croit en Dieu et fréquente un poste (paroisse) de la Congrégation de l'Armée du Salut et qui décide de s'engager en faveur de son Église.</p>
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
                // Nettoyer tout intervalle existant
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                }
                // Démarrer le nouvel intervalle
                autoSlideInterval = setInterval(() => {
                    changeSlide(1);
                }, 10000); // 10 secondes
            }

            function resetAutoSlide() {
                // Redémarrer le timer
                startAutoSlide();
            }

            // Initialisation SIMPLIFIÉE qui démarre au load
            document.addEventListener('DOMContentLoaded', function() {
                // S'assurer que la première slide est active
                indicators[0].classList.add('active');

                // Démarrer le timer IMMÉDIATEMENT
                startAutoSlide();

                // Pause au survol
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

            // Navigation clavier
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') changeSlide(-1);
                if (e.key === 'ArrowRight') changeSlide(1);
            });

            // Redémarrer le timer si la page redevient visible
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && !autoSlideInterval) {
                    startAutoSlide();
                }
            });
        </script>

<?php
include_once '../components/footer.php'
?>