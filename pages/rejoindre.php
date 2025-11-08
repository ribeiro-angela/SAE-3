<?php include_once '../components/header.php'; ?>

<main class="container-fluid px-0">

    <!-- Bandeau principal -->
    <section class="position-relative text-center text-white banner">
        <img src="../assets/image/rejoindre-header.jpg"
             alt="Rejoindre l’Armée du Salut"
             class="img-fluid w-100 banner-img">

        <div class="position-absolute top-50 start-50 translate-middle px-4 py-3 banner-overlay">
            <h1 class="fw-bold display-5 text-shadow">Nous rejoindre</h1>
            <p class="fs-5 mb-0">Rejoindre l’Armée du Salut, c’est s’engager au service de la société.</p>
        </div>
    </section>

    <!-- Introduction -->
    <section class="text-center my-4 px-3">
        <h2 class="fw-bold mb-3 text-danger">Nous rejoindre</h2>
        <p class="mx-auto" style="max-width: 700px;">
            Il existe différentes formes d'engagement pour <strong>aider</strong> et <strong>accompagner</strong> les personnes isolées,
            en détresse, précaires, dépendantes ou fragilisées.
        </p>
    </section>

    <!-- Onglets -->
    <section class="text-center py-2 fade-section">
        <div class="join-tabs d-flex flex-wrap justify-content-center gap-3">
            <a href="#benevole" class="join-tab">Bénévole ?</a>
            <a href="#service-civique" class="join-tab">Service civique ?</a>
            <a href="#salarie" class="join-tab">Salarié ?</a>
            <a href="#officier" class="join-tab">Officier ?</a>
            <a href="#soldat" class="join-tab">Soldat ?</a>
        </div>
    </section>

    <!-- Cartes de présentation -->
    <section class="container my-5">

        <!-- Devenir bénévole -->
        <div id="benevole" class="row align-items-center mb-5">
            <div class="col-md-12">
                <div class="d-md-flex align-items-center bg-light rounded-3 shadow-section overflow-hidden">
                    <div class="flex-shrink-0">
                        <img src="../assets/image/benevole.jpg" alt="Bénévole" class="img-fluid section-img">
                    </div>
                    <div class="p-4">
                        <h3 class="text-danger fw-bold">Devenir bénévole</h3>
                        <p>
                            Aux côtés des professionnels, les bénévoles sont une
                            <strong>force au service des personnes en situation de fragilité sociale</strong>.
                        </p>
                        <button class="btn btn-outline-danger mt-2 toggle-details" data-target="#details-benevole">En savoir plus</button>
                        <div id="details-benevole" class="details collapse mt-3">
                            <p>
                                Vous pourrez participer à des actions de terrain, soutenir les équipes sociales,
                                organiser des collectes et agir auprès des plus fragiles selon vos compétences.
                            </p>
                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Travailler pour l’Armée du Salut -->
        <div id="salarie" class="row align-items-center mb-5">
            <div class="col-md-12">
                <div class="d-md-flex flex-md-row-reverse align-items-center bg-light rounded-3 shadow-section overflow-hidden">
                    <div class="flex-shrink-0">
                        <img src="../assets/image/salarie.jpg" alt="Salarié" class="img-fluid section-img">
                    </div>
                    <div class="p-4">
                        <h3 class="text-danger fw-bold">Travailler pour l’Armée du Salut</h3>
                        <p>
                            Les professionnels exercent les métiers du social et du médico-social pour venir en aide aux plus fragiles.
                        </p>
                        <button class="btn btn-outline-danger mt-2 toggle-details" data-target="#details-salarie">En savoir plus</button>
                        <div id="details-salarie" class="details collapse mt-3">
                            <p>
                                Des postes variés dans l'accompagnement social, la santé, la gestion ou la communication sont proposés selon les profils et les régions.
                            </p>
                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Service civique -->
        <div id="service-civique" class="row align-items-center mb-5">
            <div class="col-md-12">
                <div class="d-md-flex align-items-center bg-light rounded-3 shadow-section overflow-hidden">
                    <div class="flex-shrink-0">
                        <img src="../assets/image/service-civique.jpg" alt="Service civique" class="img-fluid section-img">
                    </div>
                    <div class="p-4">
                        <h3 class="text-danger fw-bold">S’engager en service civique</h3>
                        <p>
                            Une opportunité unique de se sensibiliser aux métiers d’accompagnement dans le secteur social et médico-social.
                        </p>
                        <button class="btn btn-outline-danger mt-2 toggle-details" data-target="#details-service-civique">En savoir plus</button>
                        <div id="details-service-civique" class="details collapse mt-3">
                            <p>
                                Les volontaires découvrent les réalités du terrain tout en bénéficiant d’une expérience humaine enrichissante.
                            </p>
                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Officier -->
        <div id="officier" class="row align-items-center mb-5">
            <div class="col-md-12">
                <div class="d-md-flex flex-md-row-reverse align-items-center bg-light rounded-3 shadow-section overflow-hidden">
                    <div class="flex-shrink-0">
                        <img src="../assets/image/officier.jpg" alt="Officier" class="img-fluid section-img">
                    </div>
                    <div class="p-4">
                        <h3 class="text-danger fw-bold">Devenir officier</h3>
                        <p>
                            Une formation de deux ans pour étudier la théologie et se préparer à un ministère au sein de l’Armée du Salut.
                        </p>
                        <button class="btn btn-outline-danger mt-2 toggle-details" data-target="#details-officier">En savoir plus</button>
                        <div id="details-officier" class="details collapse mt-3">
                            <p>
                                Les officiers accompagnent spirituellement et matériellement ceux dans le besoin, dans un engagement durable.
                            </p>
                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Soldat -->
        <div id="soldat" class="row align-items-center mb-5">
            <div class="col-md-12">
                <div class="d-md-flex align-items-center bg-light rounded-3 shadow-section overflow-hidden">
                    <div class="flex-shrink-0">
                        <img src="../assets/image/soldat.jpg" alt="Soldat" class="img-fluid section-img">
                    </div>
                    <div class="p-4">
                        <h3 class="text-danger fw-bold">Devenir soldat</h3>
                        <p>
                            Toute personne peut aspirer à devenir soldat à partir de 16 ans, après une formation de recrue.
                        </p>
                        <button class="btn btn-outline-danger mt-2 toggle-details" data-target="#details-soldat">En savoir plus</button>
                        <div id="details-soldat" class="details collapse mt-3">
                            <p>
                                Devenir soldat, c’est vivre pleinement les valeurs de l’Armée du Salut et y contribuer activement.
                            </p>
                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Section contact -->
    <section class="text-center py-5 bg-danger text-white fade-section">
        <h2 class="fw-bold mb-3">Prêt à nous rejoindre ?</h2>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="#" class="btn btn-light fw-semibold px-4">Nous contacter</a>
            <a href="#" class="btn btn-outline-light fw-semibold px-4">Faire un don</a>
        </div>
    </section>

</main>

<script>
    // clic des bouttons "En savoir plus"
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.querySelector(button.dataset.target);
            target.classList.toggle('show');
            button.textContent = target.classList.contains('show') ? 'Réduire' : 'En savoir plus';
        });
    });
</script>

<?php include_once '../components/footer.php'; ?>
