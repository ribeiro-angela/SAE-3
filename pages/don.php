<?php
include_once '../components/header.php'
?>
    <main>
        <!-- SECTION HERO DON -->
        <section class="Dons-section">
            <img src="/assets/image/image-Don1.png" alt="Personnes aidées" class="image-full">
            <div class="Dons-details">
                <h1 class="title-Don">VOTRE DON</h1>
                <h1 class="title-Don1">TRANSFORME DES VIES</h1>
                <h3 class="subtitle-Dons">
                    Un repas, un hébergement, une écoute, une chance de repartir…<br>
                    Votre don nous permet d'agir chaque jour auprès des personnes en difficultés.
                </h3>
                <div class="btn-don-container">
                    <a href="#formulaire-don" class="btn-don-banner">JE FAIS UN DON</a>
                </div>
            </div>
        </section>

        <!-- SECTION RÉDUCTION D'IMPÔT -->
        <section class="py-5 bg-white">
            <div class="container">
                <div class="row g-5 align-items-start">
                    <div class="col-lg-7">
                        <h2 class="display-5 fw-bold text-danger mb-3">75% de réduction d'impôt</h2>
                        <p class="text-muted mb-4">Jusqu'à 1 000 € (66% au-delà). Un reçu fiscal vous est envoyé automatiquement.</p>

                        <div class="bg-light rounded-4 p-4 mb-4">
                            <div class="row align-items-center text-center">
                                <div class="col-md-5">
                                    <div class="don-calcul-box">
                                        <h4 class="text-danger mb-2">Votre Don</h4>
                                        <p class="h2 fw-bold text-danger mb-0">200€</p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="h1 text-muted">→</div>
                                </div>
                                <div class="col-md-5">
                                    <div class="don-calcul-box">
                                        <h4 class="text-danger mb-2">Coût Réel</h4>
                                        <p class="h2 fw-bold text-danger mb-0">50€</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-dark">
                            Un repas, un hébergement, une écoute, une chance de repartir…<br>
                            Votre don nous permet d'agir chaque jour auprès des personnes en difficultés.
                        </p>
                    </div>

                    <div class="col-lg-5">
                        <div class="bg-white border rounded-4 p-4 shadow" id="formulaire-don">
                            <h3 class="text-center text-primary fw-bold mb-3">Votre Don Sécurisé</h3>
                            <p class="text-center text-muted mb-4">
                                Paiements sécurisés avec les derniers protocoles de chiffrement, conçus pour respecter les normes les plus élevées de l'industrie.
                            </p>

                            <div class="btn-group w-100 mb-3" role="group">
                                <input type="radio" class="btn-check" name="don-type" id="mensuel" checked>
                                <label class="btn btn-outline-primary" for="mensuel">Don Mensuel</label>

                                <input type="radio" class="btn-check" name="don-type" id="unique">
                                <label class="btn btn-outline-primary" for="unique">Don Unique</label>
                            </div>

                            <p class="text-center fst-italic text-muted mb-4">Chaque don compte !</p>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="montant" id="montant5" checked>
                                    <label class="btn btn-outline-secondary w-100" for="montant5">5€</label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="montant" id="montant50">
                                    <label class="btn btn-outline-secondary w-100" for="montant50">50€</label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="montant" id="montant100">
                                    <label class="btn btn-outline-secondary w-100" for="montant100">100€</label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="montant" id="montant200">
                                    <label class="btn btn-outline-secondary w-100" for="montant200">200€</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="number" class="form-control form-control-lg" placeholder="Montant Libre (€)" min="1" step="0.01">
                            </div>

                            <button class="btn btn-danger btn-lg w-100 py-3 fw-bold mb-3">FAIRE UN DON</button>
                            <p class="text-center text-muted small">🔒 Paiement 100% sécurisé</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION PRÉCARITÉ -->
        <section class="section-precarite">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <h2 class="precarite-titre">La précarité <br>prive de <span class="precarite-accent">tout.</span></h2>
                        <h3 class="precarite-sous-titre">
                            Le sommeil, la santé, <br>
                            la sécurité, l'espoir, parfois la vie.
                        </h3>
                        <p class="precarite-texte">
                            Prisonnières de l'immense précarité, les personnes sans-abri comptent sur votre solidarité
                            pour retrouver leur dignité et reconstruire leur vie.
                        </p>
                        <a href="#formulaire-don" class="btn btn-danger btn-lg px-4">JE FAIS UN DON</a>
                    </div>
                    <div class="col-lg-6 text-center">
                        <img src="/assets/image/image-precarite.png" alt="Aide aux sans-abri" class="precarite-image">
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION MERCI -->
        <section class="section-merci">
            <div class="container text-center">
                <h2 class="merci-titre">
                    Merci chaleureusement pour votre
                    <span class="merci-bold">confiance</span>
                    et votre
                    <span class="merci-bold">engagement</span>
                    à nos côtés.
                </h2>
                <p class="merci-sous-texte">
                    <strong>CHAQUE</strong> Don <em>transforme</em> la vie des plus fragiles et apporte de
                    <em>l'espoir</em> à ceux qui ont en le plus besoin.
                </p>
            </div>
        </section>

        <!-- SECTION IMPACT -->
        <section class="section-impact">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="impact-titre">Votre Don a un impact <span class="impact-accent">immédiat</span></h2>
                    <p class="impact-sous-titre">Grâce à vos dons, chaque jour nous pouvons :</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <img src="/assets/image/servir.jpg" alt="Servir des repas" class="impact-image">
                            <h3 class="impact-carte-titre">Servir</h3>
                            <p class="impact-carte-texte">Servir des repas aux personnes qui ont faim</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <img src="/assets/image/offrir.jpg" alt="Offrir un hébergement" class="impact-image">
                            <h3 class="impact-carte-titre">Offrir</h3>
                            <p class="impact-carte-texte">Offrir un hébergement d'urgence</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <img src="/assets/image/accompagner.jpg" alt="Accompagner" class="impact-image">
                            <h3 class="impact-carte-titre">Accompagner</h3>
                            <p class="impact-carte-texte">Accompagner vers la réinsertion sociale et professionnelle</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION FAQ -->
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center display-4 fw-bold text-primary mb-5">Foire Aux Questions</h2>

                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Pourquoi Donner
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Vos dons permettent de financer nos actions sociales et humanitaires, de distribuer des repas, d'offrir un abri et d'accompagner chaque personne vers une vie plus digne.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Respect de votre Vie Privée
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Toutes vos informations personnelles sont protégées et utilisées uniquement pour la gestion de vos dons. Aucune donnée n'est transmise à des tiers.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Nous Soutenir en toute Confiance
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Nos comptes sont vérifiés chaque année. La transparence et la confiance sont au cœur de notre engagement envers les donateurs.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Animation des images au survol
        document.querySelectorAll('img[style*="grayscale"]').forEach(img => {
            img.addEventListener('mouseenter', () => {
                img.style.filter = 'grayscale(0%)';
                img.style.transform = 'scale(1.05)';
            });
            img.addEventListener('mouseleave', () => {
                img.style.filter = 'grayscale(100%)';
                img.style.transform = 'scale(1)';
            });
        });
    </script>

<?php
include_once '../components/footer.php'
?>