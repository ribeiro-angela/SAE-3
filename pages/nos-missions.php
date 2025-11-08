<?php
include_once '../components/header.php'
?>

    <main>
        <!-- HERO SECTION -->
        <section class="mission-hero text-white text-center">
            <div class="container">
                <h1 class="display-2 fw-bold mb-4">NOTRE MISSION</h1>
                <p class="lead fs-2">Prendre soin des personnes dans le besoin</p>
            </div>
        </section>

        <!-- HIGHLIGHT PARAGRAPHE -->
        <section class="py-5 bg-cream">
            <div class="container text-center">
                <p class="lead fs-5">
                    Prendre soin des personnes est le centre de la démarche de <span class="text-primary fw-bold">la Congrégation</span> et de <span class="text-primary fw-bold">la Fondation de l'Armée du Salut</span>.
                    <br> Les deux entités accueillent, soutiennent, et accompagnent les femmes et les hommes de toute origine et condition sans distinction aucune.
                </p>
            </div>
        </section>

        <!-- FONDATION -->
        <section class="py-5 bg-white">
            <div class="container">
                <h2 class="text-center display-4 fw-bold mb-5">Les missions de la <span class="text-danger">Fondation de l'Armée du Salut</span></h2>

                <div class="text-center mb-5">
                    <h3 class="h1 fw-bold text-dark mb-3">Secourir, Accompagner, Reconstruire</h3>
                    <p class="lead text-muted">Les trois missions prioritaires de la Fondation de l'Armée du Salut pour redonner espoir et dignité aux personnes les plus fragiles</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card card-hover h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <h4 class="card-title text-danger fw-bold mb-3">Secourir</h4>
                                <p class="card-text">
                                    Nous apportons une aide vitale aux femmes et aux hommes et aux familles qui se trouvent
                                    dans la plus grande détresse : des repas, un hébergement d'urgence...
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-hover h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <h4 class="card-title text-success fw-bold mb-3">Accompagner</h4>
                                <p class="card-text">
                                    Les personnes que nous accueillons dans nos centres sont suivies par nos équipes qui
                                    les aident à retrouver leurs repères et à élaborer un nouveau projet de vie.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-hover h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <h4 class="card-title text-primary fw-bold mb-3">Reconstruire</h4>
                                <p class="card-text">
                                    Une fois la situation des personnes stabilisée, nous les soutenons dans leurs recherches
                                    d'emploi et de logement jusqu'à ce qu'elles puissent mener une vie autonome.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- NOTRE MISSION -->
        <section class="py-5 text-white" style="background-color: var(--secondary-color);">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <h2 class="display-4 fw-bold mb-3">Notre Mission.</h2>
                        <h3 class="h4 mb-4">
                            Personnes isolées ou familles <br>
                            en situation de grande <br>
                            précarité,
                        </h3>
                        <p class="lead mb-4">
                            personnes handicapées et/ou âgées dépendantes, enfants et adolescents
                            en situation de fragilité : <span class="text-warning">ils sont des milliers à être, chaque
                        année, accueillis et accompagnés dans une grande variété
                        d'établissements,</span> en lien avec les politiques publiques élaborées
                            et mises en œuvre aux niveaux national et local.
                        </p>
                        <button class="btn btn-danger btn-lg" data-bs-toggle="collapse" data-bs-target="#detailsMission">
                            EN SAVOIR PLUS
                        </button>

                        <div class="collapse mt-4" id="detailsMission">
                            <div class="bg-dark bg-opacity-25 p-4 rounded">
                                <h4>Détails sur notre mission</h4>
                                <p>Qualité de l'accueil et accompagnement global (tenant compte de toutes les dimensions de la vie et du parcours
                                    de chaque personne accueillie) sont au fondement de l'action de la Fondation de l'Armée du Salut, réalisée dans
                                    la plupart des régions et de nombreux départements et grandes villes (outre Paris/région parisienne, Lyon, Marseille,
                                    Lille, Strasbourg, Rouen, Le Havre, Montpellier, Saint-Étienne, Reims, Nîmes, etc.), sans compter l'appui apporté
                                    à un nombre croissant de structures locales.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <img src="/assets/image/mission1.jpg" alt="Mission" class="img-fluid rounded-circle shadow" style="max-width: 400px;">
                    </div>
                </div>
            </div>
        </section>

        <!-- STATISTIQUES -->
        <section class="py-5 bg-dark text-white">
            <div class="container">
                <h2 class="text-center display-4 fw-bold mb-5">La Fondation en Chiffres</h2>
                <div class="row g-4 text-center">
                    <div class="col-md-4">
                        <div class="stat-card-custom p-4 rounded">
                            <span class="display-3 fw-bold d-block" data-target="225">0</span>
                            <span class="fs-5">structures et services</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card-custom p-4 rounded">
                            <span class="display-3 fw-bold d-block" data-target="23000">0</span>
                            <span class="fs-5">personnes accueillies</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card-custom p-4 rounded">
                            <span class="display-3 fw-bold d-block" data-target="2600000">0</span>
                            <span class="fs-5">journées d'hébergement</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card-custom p-4 rounded">
                            <span class="display-3 fw-bold d-block" data-target="205">0</span>
                            <span class="fs-5">millions d'€ pour les missions sociales</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card-custom p-4 rounded">
                            <span class="display-3 fw-bold d-block" data-target="32">0</span>
                            <span class="fs-5">départements couverts</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card-custom p-4 rounded">
                            <span class="display-3 fw-bold d-block" data-target="134">0</span>
                            <span class="fs-5">pays dans le monde</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CONGRÉGATION -->
        <section class="py-5 bg-white">
            <div class="container">
                <h2 class="text-center display-4 fw-bold mb-5">Les missions de la <span class="text-danger">Congrégation</span></h2>

                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <h2 class="display-4 fw-bold mb-3">Une Double Mission.</h2>
                        <h3 class="h4 mb-4">Depuis ses origines,</h3>
                        <p class="lead">
                            l'Armée du Salut s'adresse à la personne dans sa globalité avec une mission
                            à la fois spirituelle et sociale. Ces deux objectifs sont complémentaires et indissociables.
                            <br><br>
                            En 1994, l'Armée du Salut s'est constituée en Congrégation pour la partie cultuelle de son action,
                            avec aujourd'hui 25 postes d'évangélisation en France.
                        </p>
                    </div>
                    <div class="col-lg-6 text-center">
                        <img src="/assets/image/congregation.jpg" alt="Congrégation" class="img-fluid rounded shadow" style="max-width: 500px;">
                    </div>
                </div>
            </div>
        </section>

        <!-- ACTION SOCIALE -->
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center display-4 fw-bold mb-4">Action Sociale des Postes</h2>
                <p class="text-center lead text-muted mb-5">
                    L'action sociale représente près de <span class="text-danger fw-bold">60% du temps de travail de l'officier</span>.
                    <br>Elle est subventionnée par la Fondation de l'Armée du Salut, en fonction des projets montés.
                </p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title text-danger fw-bold text-center mb-4">Actions Principales</h3>
                                <ul class="list-unstyled">
                                    <li class="mb-3">• Aide alimentaire <small class="text-muted">(distribution de denrées et bons)</small></li>
                                    <li class="mb-3">• Vente et distribution de vêtements</li>
                                    <li class="mb-3">• Accompagnement administratif</li>
                                    <li class="mb-0">• Distribution de soupes <small class="text-muted">de nuit l'hiver</small></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title text-primary fw-bold text-center mb-4">Programmes Spécifiques</h3>
                                <ul class="list-unstyled">
                                    <li class="mb-3">• Alphabétisation <small class="text-muted">pour adultes</small></li>
                                    <li class="mb-3">• Organisation de vacances <small class="text-muted">pour enfants</small></li>
                                    <li class="mb-3">• Cours de musique</li>
                                    <li class="mb-0">• Clubs vidéo et bricolage</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- HÉRITAGE -->
        <section class="py-5 text-white position-relative" style="background: linear-gradient(rgba(67, 8, 20, 0.22), rgba(24, 30, 56, 0.22)), url('/assets/image/eglise1.jpg') center/cover;">
            <div class="container position-relative">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-5">
                        <div class="bg-white bg-opacity-90 rounded-3 p-4 text-dark">
                            <h3 class="fw-bold mb-3">Héritage Protestant</h3>
                            <p>Le Salut fait partie des églises issues de la Réformation du seizième siècle et est membre de la Fédération Protestante en France.</p>
                            <p>Fondée par un ancien pasteur méthodiste, elle continue comme seule autorité l'Écriture sainte (la Bible).</p>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-white bg-opacity-90 rounded-3 p-4 text-dark">
                            <h3 class="fw-bold mb-3">Le Concept du Salut</h3>
                            <p>Le salut signifie apporter un soin à chaque personne dans 4 dimensions :</p>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary">PHYSIQUE</span>
                                <span class="badge bg-success">SOCIALE</span>
                                <span class="badge bg-warning">MENTALE</span>
                                <span class="badge bg-info">SPIRITUELLE</span>
                            </div>
                            <p>C'est la conception théologique du mot « salut » de l'Armée du Salut.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATISTIQUES FINALES -->
        <section class="py-5 bg-white">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-md-4 text-center">
                        <div class="bg-light rounded-circle p-5 mx-auto" style="width: 200px; height: 200px;">
                            <div class="h2 fw-bold text-primary">52</div>
                            <div class="fw-bold">Postes évangéliques</div>
                            <small class="text-muted">Répartis dans toute la France</small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h4 class="text-success fw-bold mb-3">Témoignage de Foi</h4>
                                <p>Les bénévoles témoignent de l'amour de Dieu, espérant que leurs auditeurs, quelle que soit leur région, apportent une présence spirituelle paisible auprès des personnes en détresse.</p>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-primary fw-bold mb-3">Solidarité-Charité</h4>
                                <p>La Congrégation développe une culture de partage et de solidarité visant à la recherche de la paix, où chacun trouve sa véritable liberté au service des autres.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Animation des chiffres
        document.addEventListener('DOMContentLoaded', function() {
            const statNumbers = document.querySelectorAll('[data-target]');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const target = parseInt(element.getAttribute('data-target'));
                        const duration = 2000;
                        const increment = target / (duration / 16);
                        let current = 0;

                        const updateNumber = () => {
                            current += increment;
                            if (current < target) {
                                element.textContent = Math.floor(current).toLocaleString('fr-FR');
                                requestAnimationFrame(updateNumber);
                            } else {
                                element.textContent = target.toLocaleString('fr-FR');
                            }
                        };
                        updateNumber();
                        observer.unobserve(element);
                    }
                });
            }, { threshold: 0.5 });

            statNumbers.forEach(stat => observer.observe(stat));
        });
    </script>

<?php
include_once '../components/footer.php'
?>