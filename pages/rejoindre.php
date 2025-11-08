<?php include_once '../components/header.php'; ?>

    <main class="container-fluid px-0">
        <!-- BANDEAU PRINCIPAL -->
        <section class="position-relative text-white">
            <img src="../assets/image/rejoindre-header.jpg" alt="Rejoindre l'Armée du Salut" class="img-fluid w-100" style="height: 400px; object-fit: cover;">
            <div class="position-absolute top-50 start-50 translate-middle text-center bg-dark bg-opacity-50 rounded-3 p-4">
                <h1 class="display-4 fw-bold mb-3">Nous rejoindre</h1>
                <p class="fs-5 mb-0">Rejoindre l'Armée du Salut, c'est s'engager au service de la société.</p>
            </div>
        </section>

        <!-- INTRODUCTION -->
        <section class="py-5 bg-white">
            <div class="container text-center">
                <h2 class="text-danger fw-bold mb-4">Nous rejoindre</h2>
                <p class="lead mx-auto" style="max-width: 700px;">
                    Il existe différentes formes d'engagement pour <strong>aider</strong> et <strong>accompagner</strong> les personnes isolées,
                    en détresse, précaires, dépendantes ou fragilisées.
                </p>
            </div>
        </section>

        <!-- ONGLETS -->
        <section class="py-4 bg-light">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#benevole" class="btn btn-outline-danger px-4">Bénévole ?</a>
                    <a href="#service-civique" class="btn btn-outline-danger px-4">Service civique ?</a>
                    <a href="#salarie" class="btn btn-outline-danger px-4">Salarié ?</a>
                    <a href="#officier" class="btn btn-outline-danger px-4">Officier ?</a>
                    <a href="#soldat" class="btn btn-outline-danger px-4">Soldat ?</a>
                </div>
            </div>
        </section>

        <!-- CARTES DE PRÉSENTATION -->
        <section class="py-5 bg-white">
            <div class="container">
                <!-- BÉNÉVOLE -->
                <div id="benevole" class="row align-items-center mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="../assets/image/benevole.jpg" class="img-fluid h-100 w-100" alt="Bénévole" style="object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body p-4">
                                        <h3 class="card-title text-danger fw-bold mb-3">Devenir bénévole</h3>
                                        <p class="card-text mb-4">
                                            Aux côtés des professionnels, les bénévoles sont une
                                            <strong>force au service des personnes en situation de fragilité sociale</strong>.
                                        </p>
                                        <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#detailsBenevole">
                                            En savoir plus
                                        </button>
                                        <div class="collapse mt-3" id="detailsBenevole">
                                            <p class="card-text mb-3">
                                                Vous pourrez participer à des actions de terrain, soutenir les équipes sociales,
                                                organiser des collectes et agir auprès des plus fragiles selon vos compétences.
                                            </p>
                                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SALARIÉ -->
                <div id="salarie" class="row align-items-center mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="row g-0 flex-md-row-reverse">
                                <div class="col-md-4">
                                    <img src="../assets/image/salarie.jpg" class="img-fluid h-100 w-100" alt="Salarié" style="object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body p-4">
                                        <h3 class="card-title text-danger fw-bold mb-3">Travailler pour l'Armée du Salut</h3>
                                        <p class="card-text mb-4">
                                            Les professionnels exercent les métiers du social et du médico-social pour venir en aide aux plus fragiles.
                                        </p>
                                        <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#detailsSalarie">
                                            En savoir plus
                                        </button>
                                        <div class="collapse mt-3" id="detailsSalarie">
                                            <p class="card-text mb-3">
                                                Des postes variés dans l'accompagnement social, la santé, la gestion ou la communication sont proposés selon les profils et les régions.
                                            </p>
                                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SERVICE CIVIQUE -->
                <div id="service-civique" class="row align-items-center mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="../assets/image/service-civique.jpg" class="img-fluid h-100 w-100" alt="Service civique" style="object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body p-4">
                                        <h3 class="card-title text-danger fw-bold mb-3">S'engager en service civique</h3>
                                        <p class="card-text mb-4">
                                            Une opportunité unique de se sensibiliser aux métiers d'accompagnement dans le secteur social et médico-social.
                                        </p>
                                        <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#detailsServiceCivique">
                                            En savoir plus
                                        </button>
                                        <div class="collapse mt-3" id="detailsServiceCivique">
                                            <p class="card-text mb-3">
                                                Les volontaires découvrent les réalités du terrain tout en bénéficiant d'une expérience humaine enrichissante.
                                            </p>
                                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OFFICIER -->
                <div id="officier" class="row align-items-center mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="row g-0 flex-md-row-reverse">
                                <div class="col-md-4">
                                    <img src="../assets/image/officier.jpg" class="img-fluid h-100 w-100" alt="Officier" style="object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body p-4">
                                        <h3 class="card-title text-danger fw-bold mb-3">Devenir officier</h3>
                                        <p class="card-text mb-4">
                                            Une formation de deux ans pour étudier la théologie et se préparer à un ministère au sein de l'Armée du Salut.
                                        </p>
                                        <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#detailsOfficier">
                                            En savoir plus
                                        </button>
                                        <div class="collapse mt-3" id="detailsOfficier">
                                            <p class="card-text mb-3">
                                                Les officiers accompagnent spirituellement et matériellement ceux dans le besoin, dans un engagement durable.
                                            </p>
                                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SOLDAT -->
                <div id="soldat" class="row align-items-center mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="../assets/image/soldat.jpg" class="img-fluid h-100 w-100" alt="Soldat" style="object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body p-4">
                                        <h3 class="card-title text-danger fw-bold mb-3">Devenir soldat</h3>
                                        <p class="card-text mb-4">
                                            Toute personne peut aspirer à devenir soldat à partir de 16 ans, après une formation de recrue.
                                        </p>
                                        <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#detailsSoldat">
                                            En savoir plus
                                        </button>
                                        <div class="collapse mt-3" id="detailsSoldat">
                                            <p class="card-text mb-3">
                                                Devenir soldat, c'est vivre pleinement les valeurs de l'Armée du Salut et y contribuer activement.
                                            </p>
                                            <a href="#" class="btn btn-danger">Je suis intéressé</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION CONTACT -->
        <section class="py-5 bg-danger text-white text-center">
            <div class="container">
                <h2 class="fw-bold mb-4 display-5">Prêt à nous rejoindre ?</h2>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#" class="btn btn-light fw-semibold px-4 py-2">Nous contacter</a>
                    <a href="don.php" class="btn btn-outline-light fw-semibold px-4 py-2">Faire un don</a>
                </div>
            </div>
        </section>

<?php include_once '../components/footer.php'; ?>