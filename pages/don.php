<?php
include_once '../components/header.php'
?>
<main>

    <!-- ==== SECTION HERO DON ==== -->
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

    <!-- ==== SECTION RÉDUCTION D’IMPÔT + DON SÉCURISÉ ==== -->
    <section class="section-reduction">

        <!-- Colonne gauche -->
        <div class="reduction-gauche">
            <h2>75 % de réduction d’impôt</h2>
            <p class="sous-texte">
                Jusqu’à 1 000 € (66 % au-delà). Un reçu fiscal vous est envoyé automatiquement.
            </p>

            <div class="don-calcul">
                <div class="bloc-don">
                    <h3>Votre Dons</h3>
                    <p>200€</p>
                </div>
                <div class="fleche">➜</div>
                <div class="bloc-don">
                    <h3>Coût Réel</h3>
                    <p>50€</p>
                </div>
            </div>

            <p class="description">
                Un repas, un hébergement, une écoute, une chance de repartir…<br>
                Votre don nous permet d'agir chaque jour auprès des personnes en difficultés.
            </p>
        </div>


        <div class="reduction-droite" id="formulaire-don">
            <h3>Votre Don Sécurisé</h3>
            <p>
                Paiements sécurisés avec les derniers protocoles de chiffrement, conçus pour respecter
                les normes les plus élevées de l’industrie.
            </p>

            <div class="type-don">
                <button class="active">Don Mensuel</button>
                <button>Don Unique</button>
            </div>

            <p style="text-align:center; font-style: italic;">Chaque don compte !</p>

            <div class="montants">
                <button class="active">5€</button>
                <button>50€</button>
                <button>100€</button>
                <button>200€</button>
            </div>

            <label for="montant-libre" class="visually-hidden">Montant Libre</label>
            <input type="number" id="montant-libre" class="montant-libre" placeholder="Montant Libre (€)" min="1" step="0.01">

            <button class="btn-faire-don">FAIRE UN DON</button>
            <p class="paiement-securise">Paiement 100% sécurisé</p>
        </div>
    </section>

    <section class="section-precarite">
        <div class="precarite-container">
            <div class="precarite-texte">
                <h2>La précarité <br> prive de <span>tout.</span></h2>
                <h3>
                    Le sommeil, la santé, <br>
                    la sécurité, l’espoir, parfois la vie.
                </h3>
                <p>
                    Prisonnières de l’immense précarité, les personnes sans-abri comptent sur votre solidarité
                    pour retrouver leur dignité et reconstruire leur vie.
                </p>
                <a href="#formulaire-don" class="btn-don-rouge">JE FAIS UN DON</a>
            </div>

            <div class="precarite-image">
                <img src="/assets/image/image-precarite.png" alt="Aide aux sans-abri">
            </div>
        </div>
    </section>

    <!-- ==== SECTION MERCI ==== -->
    <section class="section-merci">
        <div class="merci-container">
            <h2 class="merci-titre">
                Merci chaleureusement pour votre
                <span class="merci-bold">confiance</span>
                et votre
                <span class="merci-bold">engagement</span>
                à nos côtés.
            </h2>
            <p class="merci-sous-texte">
                <strong>CHAQUE</strong> Don <em>transforme</em> la vie des plus fragiles et apporte de
                <em>l’espoir</em> à ceux qui ont en le plus besoin.
            </p>
        </div>
    </section>

    <!-- ==== SECTION IMPACT ==== -->
    <section class="section-impact">
        <div class="impact-container">
            <h2 class="impact-titre">
                Votre Don a un impact <span>immédiat</span>
            </h2>
            <p class="impact-sous-titre">
                Grâce à vos dons, chaque jour nous pouvons :
            </p>

            <div class="impact-cartes">
                <div class="carte">
                    <img src="/assets/image/servir.jpg" alt="Servir des repas">
                    <h3>Servir</h3>
                    <p>Servir des repas aux personnes qui ont faim</p>
                </div>

                <div class="carte">
                    <img src="/assets/image/offrir.jpg" alt="Offrir un hébergement">
                    <h3>Offrir</h3>
                    <p>Offrir un hébergement d’urgence</p>
                </div>

                <div class="carte">
                    <img src="/assets/image/accompagner.jpg" alt="Accompagner">
                    <h3>Accompagner</h3>
                    <p>Accompagner vers la réinsertion sociale et professionnelle</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==== SECTION FAQ ==== -->
    <section class="section-faq">
        <div class="faq-container">
            <h2 class="faq-titre">Foire Aux Questions</h2>

            <details>
                <summary>Pourquoi Donner</summary>
                <p>
                    Vos dons permettent de financer nos actions sociales et humanitaires,
                    de distribuer des repas, d’offrir un abri et d’accompagner chaque personne
                    vers une vie plus digne.
                </p>
            </details>

            <details>
                <summary>Respect de votre Vie Privée</summary>
                <p>
                    Toutes vos informations personnelles sont protégées et utilisées uniquement
                    pour la gestion de vos dons. Aucune donnée n’est transmise à des tiers.
                </p>
            </details>

            <details>
                <summary>Nous Soutenir en toute Confiance</summary>
                <p>
                    Nos comptes sont vérifiés chaque année. La transparence et la confiance
                    sont au cœur de notre engagement envers les donateurs.
                </p>
            </details>
        </div>
    </section>




</main>

<?php
include_once '../components/footer.php'
?>
