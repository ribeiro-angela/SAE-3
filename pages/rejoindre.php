<?php
include_once '../components/header.php'
?>

<main class="container-fluid px-0">

    <!-- Bandeau principal -->
    <section class="position-relative text-center text-white">
        <img src="../assets/image/rejoindre-header.jpg" alt="Rejoindre l’Armée du Salut" class="img-fluid w-100"
             style="object-fit: cover; height: 350px;">
        <div class="position-absolute top-50 start-50 translate-middle">
            <h1 class="fw-bold display-5 text-shadow">Nous rejoindre</h1>
            <p class="fs-5">Rejoindre l’Armée du Salut, c’est s’engager au service de la société.</p>
        </div>
    </section>

    <!-- Introduction -->
    <section class="text-center my-5 px-3">
        <h2 class="fw-bold mb-3 text-danger">Nous rejoindre</h2>
        <p class="mx-auto" style="max-width: 700px;">
            Il existe différentes formes d'engagement pour <strong>aider</strong> et <strong>accompagner</strong> les personnes isolées, en détresse, précaires, dépendantes ou fragilisées.
        </p>
        <div class="my-3">
            <img src="../assets/image/interrogation.png" alt="?" width="50">
        </div>
    </section>

    <!-- Onglets -->
    <section class="text-center bg-light py-3 border-top border-bottom">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="#benevole" class="text-decoration-none text-dark fw-semibold">Bénévole ?</a>
            <a href="#salarie" class="text-decoration-none text-dark fw-semibold">Salarié ?</a>
            <a href="#service-civique" class="text-decoration-none text-dark fw-semibold">En service civique ?</a>
            <a href="#officier" class="text-decoration-none text-dark fw-semibold">Officier ?</a>
            <a href="#soldat" class="text-decoration-none text-dark fw-semibold">Soldat ?</a>
        </div>
    </section>

    <!-- Cartes de présentation -->
    <section class="container my-5">

        <!-- Devenir bénévole -->
        <div id="benevole" class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="../assets/image/benevole.jpg" class="img-fluid rounded shadow-sm" alt="Bénévole">
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-light rounded-3 shadow-sm">
                    <h3 class="text-danger fw-bold">Devenir bénévole</h3>
                    <p>Aux côtés des professionnels, les bénévoles sont une <strong>force au service des personnes en situation de fragilité sociale</strong>, reconnue parmi les valeurs de l’Armée du Salut et dans le projet de la Fondation.</p>
                    <a href="#" class="btn btn-outline-danger mt-2">En savoir plus</a>
                </div>
            </div>
        </div>

        <!-- Travailler pour l’Armée du Salut -->
        <div id="salarie" class="row align-items-center flex-md-row-reverse mb-5">
            <div class="col-md-6">
                <img src="../assets/image/salarie.jpg" class="img-fluid rounded shadow-sm" alt="Salarié">
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-light rounded-3 shadow-sm">
                    <h3 class="text-danger fw-bold">Travailler pour l’Armée du Salut</h3>
                    <p>Les professionnels travaillant à la Fondation exercent l’ensemble des métiers du social et du médico-social pour venir en aide aux plus fragiles.</p>
                    <a href="#" class="btn btn-outline-danger mt-2">En savoir plus</a>
                </div>
            </div>
        </div>

        <!-- Service civique -->
        <div id="service-civique" class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="../assets/image/service-civique.jpg" class="img-fluid rounded shadow-sm" alt="Service civique">
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-light rounded-3 shadow-sm">
                    <h3 class="text-danger fw-bold">S’engager en service civique</h3>
                    <p>Une opportunité unique de se sensibiliser aux métiers d’accompagnement dans le secteur social et médico-social.</p>
                    <a href="#" class="btn btn-outline-danger mt-2">En savoir plus</a>
                </div>
            </div>
        </div>

        <!-- Officier -->
        <div id="officier" class="row align-items-center flex-md-row-reverse mb-5">
            <div class="col-md-6">
                <img src="../assets/image/officier.jpg" class="img-fluid rounded shadow-sm" alt="Officier">
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-light rounded-3 shadow-sm">
                    <h3 class="text-danger fw-bold">Devenir officier</h3>
                    <p>La formation de deux ans permet d’étudier la théologie, de renforcer sa foi et de se préparer à un ministère au sein de l’Armée du Salut.</p>
                    <a href="#" class="btn btn-outline-danger mt-2">En savoir plus</a>
                </div>
            </div>
        </div>

        <!-- Soldat -->
        <div id="soldat" class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="../assets/image/soldat.jpg" class="img-fluid rounded shadow-sm" alt="Soldat">
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-light rounded-3 shadow-sm">
                    <h3 class="text-danger fw-bold">Devenir soldat</h3>
                    <p>Toute personne peut aspirer à devenir soldat à partir de 16 ans, après une formation de recrue.</p>
                    <a href="#" class="btn btn-outline-danger mt-2">En savoir plus</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section contact -->
    <section class="text-center py-5 bg-danger text-white">
        <h2 class="fw-bold mb-3">Prêt à nous rejoindre ?</h2>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="#" class="btn btn-light fw-semibold px-4">Nous contacter</a>
            <a href="don.php" class="btn btn-outline-light fw-semibold px-4">Faire un don</a>
        </div>
    </section>

</main>

<?php
include_once '../components/footer.php'
?>
