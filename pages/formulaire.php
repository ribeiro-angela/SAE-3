<?php
include_once '../components/header.php';
?>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f0;
        }

        .page-wrapper {
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .form-section {
            background: white;
            padding: 50px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            width: 80px;
            height: 80px;
            background-color: #c13a3a;
            margin: 0 auto 30px;
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
            text-align: center;
            font-weight: bold;
            line-height: 1.2;
        }

        h1 {
            color: #c13a3a;
            font-size: 36px;
            margin-bottom: 40px;
            font-weight: 600;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #c13a3a;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            margin: 0;
        }

        .radio-group input[type="radio"] {
            width: auto;
            margin-right: 8px;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            margin-top: 15px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
            margin-top: 3px;
        }

        .checkbox-group label {
            margin: 0;
            font-size: 12px;
            line-height: 1.5;
        }

        .btn {
            width: 100%;
            padding: 16px;
            background-color: white;
            color: #333;
            border: 2px solid #333;
            border-radius: 30px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 15px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn:hover {
            background-color: #c13a3a;
            border-color: #c13a3a;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(193, 58, 58, 0.3);
        }

        .btn-primary {
            background-color: #c13a3a;
            border-color: #c13a3a;
            color: white;
        }

        .btn-primary:hover {
            background-color: #a02f2f;
            border-color: #a02f2f;
        }

        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .hidden {
            display: none !important;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-section {
                padding: 40px 30px;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="container">
            <!-- Page "Tu veux" -->
            <div class="form-section" id="choixPage">
                <div class="logo">ARMÉE<br>DU<br>SALUT</div>
                <h1>Tu veux :</h1>
                <button class="btn" onclick="showConnexion()">Se connecter</button>
                <button class="btn" onclick="showInscription()">S'inscrire</button>
                <a href="accueil.php" class="btn">Visiteur</a>
            </div>

            <!-- Formulaire de connexion -->
            <div class="form-section hidden" id="connexionPage">
                <div class="logo">ARMÉE<br>DU<br>SALUT</div>
                <h1>Se connecter</h1>

                <form method="POST" action="traitement_connexion.php">
                    <div class="form-group">
                        <label for="identifiant">Identifiant :</label>
                        <input type="text" id="identifiant" name="identifiant" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">
                            Ne pas enregistrer mon identifiant.<br>
                            Effacer les consentements accordés<br>
                            préalablement au service.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Se connecter</button>
                    <button type="button" class="btn" onclick="showChoix()">Retour</button>
                </form>
            </div>

            <!-- Formulaire d'inscription -->
            <div class="form-section hidden" id="inscriptionPage">
                <div class="logo">ARMÉE<br>DU<br>SALUT</div>
                <h1>S'inscrire</h1>

                <form method="POST" action="traitement_inscription.php">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom*</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom*</label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="civilite">Civilité*</label>
                            <select id="civilite" name="civilite" required>
                                <option value="">-</option>
                                <option value="mr">M.</option>
                                <option value="mme">Mme</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="naissance">Date de naissance*</label>
                            <input type="date" id="naissance" name="naissance" required>
                            <p class="help-text">Si vous avez moins de 18 ans, vous ne pouvez pas vous inscrire</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telephone">Téléphone*</label>
                            <input type="tel" id="telephone" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label for="postal">Code postal*</label>
                            <input type="text" id="postal" name="postal" pattern="[0-9]{5}" maxlength="5" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="disponibilites">Quelles sont vos disponibilités ?</label>
                        <input type="text" id="disponibilites" name="disponibilites">
                    </div>

                    <div class="form-group">
                        <label>Avez-vous le permis ?*</label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="permis" value="oui" required> Oui
                            </label>
                            <label>
                                <input type="radio" name="permis" value="non"> Non
                            </label>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="mailing" name="mailing">
                        <label for="mailing">Je souhaite recevoir des informations par email</label>
                    </div>

                    <div class="form-group">
                        <label for="competences">Quelles compétences pourriez-vous partager avec l'association ?</label>
                        <textarea id="competences" name="competences"></textarea>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="certify" name="certify" required>
                        <label for="certify">
                            Je certifie que ces informations sont conformes.*<br>
                            Après l'inscription, vérifiez votre boîte mail (onglet Promotions) pour choisir les missions !<br>
                            Données traitées par l'Armée du Salut pour gérer les distributions et le bénévolat. Conservées 3 ans. Droits RGPD disponibles.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 30px;">S'inscrire</button>
                    <button type="button" class="btn" onclick="showChoix()">Retour</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showChoix() {
            document.getElementById('choixPage').classList.remove('hidden');
            document.getElementById('connexionPage').classList.add('hidden');
            document.getElementById('inscriptionPage').classList.add('hidden');
        }

        function showConnexion() {
            document.getElementById('choixPage').classList.add('hidden');
            document.getElementById('connexionPage').classList.remove('hidden');
            document.getElementById('inscriptionPage').classList.add('hidden');
        }

        function showInscription() {
            document.getElementById('choixPage').classList.add('hidden');
            document.getElementById('connexionPage').classList.add('hidden');
            document.getElementById('inscriptionPage').classList.remove('hidden');
        }
    </script>

<?php
include_once '../components/footer.php';
?>