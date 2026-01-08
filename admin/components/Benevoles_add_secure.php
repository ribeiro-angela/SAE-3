<?php
/**
 * AJOUT/MODIFICATION SÉCURISÉE DE BÉNÉVOLES
 * Avec validation stricte et hachage des mots de passe
 */

require_once __DIR__ . '/security.php';

Security::startSecureSession();
Security::requireLogin();

try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

$isEdit = isset($_GET['id']);
$benevole = null;
$errors = [];
$success = '';

if ($isEdit) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("
        SELECT b.*, u.Nom, u.Prenom, u.Mail, u.Telephone
        FROM BENEVOLES b
        JOIN UTILISATEUR u ON b.IDUtilisateur = u.IDUtilisateur
        WHERE b.IDUtilisateur = ?
    ");
    $stmt->execute([$id]);
    $benevole = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$benevole) {
        header('Location: benevoles.php');
        exit;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier le token CSRF
    if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = "Token de sécurité invalide";
    } else {
        // Nettoyer et valider les données
        $nom = Security::cleanInput($_POST['nom']);
        $prenom = Security::cleanInput($_POST['prenom']);
        $mail = Security::cleanInput($_POST['mail']);
        $telephone = Security::cleanInput($_POST['telephone'] ?? '');
        $dateNaissance = $_POST['date_naissance'];
        $profession = Security::cleanInput($_POST['profession'] ?? '');
        $permis = isset($_POST['permis']) ? 1 : 0;
        $idVille = !empty($_POST['id_ville']) ? (int)$_POST['id_ville'] : null;
        $idRegime = !empty($_POST['id_regime']) ? (int)$_POST['id_regime'] : null;
        $idHandicap = !empty($_POST['id_handicap']) ? (int)$_POST['id_handicap'] : null;
        $idCompetence = !empty($_POST['id_competence']) ? (int)$_POST['id_competence'] : null;

        // Validation
        if (empty($nom)) $errors[] = "Le nom est requis";
        if (empty($prenom)) $errors[] = "Le prénom est requis";
        if (empty($mail)) $errors[] = "L'email est requis";
        if (!Security::validateEmail($mail)) $errors[] = "Email invalide";
        if (!empty($telephone) && !Security::validatePhone($telephone)) {
            $errors[] = "Numéro de téléphone invalide";
        }
        if (empty($dateNaissance)) $errors[] = "La date de naissance est requise";

        // Vérifier l'âge (minimum 18 ans)
        $birthDate = new DateTime($dateNaissance);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        if ($age < 18) {
            $errors[] = "L'âge minimum requis est de 18 ans";
        }

        // Pour un nouvel utilisateur, valider le mot de passe
        if (!$isEdit) {
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if (empty($password)) {
                $errors[] = "Le mot de passe est requis";
            } else {
                // Vérifier la force du mot de passe
                $passwordErrors = Security::validatePasswordStrength($password);
                $errors = array_merge($errors, $passwordErrors);

                if ($password !== $passwordConfirm) {
                    $errors[] = "Les mots de passe ne correspondent pas";
                }
            }
        }

        if (empty($errors)) {
            try {
                $db->beginTransaction();

                if ($isEdit) {
                    // Modification
                    $stmt = $db->prepare("
                        UPDATE UTILISATEUR 
                        SET Nom = ?, Prenom = ?, Mail = ?, Telephone = ?
                        WHERE IDUtilisateur = ?
                    ");
                    $stmt->execute([$nom, $prenom, $mail, $telephone, $id]);

                    $stmt = $db->prepare("
                        UPDATE BENEVOLES 
                        SET DateNaissance = ?, Profession = ?, Permis = ?, 
                            IDVille = ?, IDRegime = ?, IDHandicap = ?, IDCompetence = ?
                        WHERE IDUtilisateur = ?
                    ");
                    $stmt->execute([$dateNaissance, $profession, $permis, $idVille, $idRegime, $idHandicap, $idCompetence, $id]);

                    // Si un nouveau mot de passe est fourni
                    if (!empty($_POST['new_password'])) {
                        $newPassword = $_POST['new_password'];
                        $passwordErrors = Security::validatePasswordStrength($newPassword);

                        if (empty($passwordErrors) && $newPassword === $_POST['new_password_confirm']) {
                            $hashedPassword = Security::hashPassword($newPassword);
                            $stmt = $db->prepare("UPDATE UTILISATEUR SET MotDePasse = ? WHERE IDUtilisateur = ?");
                            $stmt->execute([$hashedPassword, $id]);
                        }
                    }

                    $_SESSION['success'] = "Bénévole modifié avec succès";
                } else {
                    // Vérifier si l'email existe déjà
                    $stmt = $db->prepare("SELECT COUNT(*) FROM UTILISATEUR WHERE Mail = ?");
                    $stmt->execute([$mail]);
                    if ($stmt->fetchColumn() > 0) {
                        throw new Exception("Cet email est déjà utilisé");
                    }

                    // Hacher le mot de passe de manière sécurisée
                    $hashedPassword = Security::hashPassword($password);

                    // Insérer l'utilisateur
                    $stmt = $db->prepare("
                        INSERT INTO UTILISATEUR (Nom, Prenom, Mail, Telephone, MotDePasse)
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$nom, $prenom, $mail, $telephone, $hashedPassword]);
                    $idUtilisateur = $db->lastInsertId();

                    // Insérer le bénévole
                    $stmt = $db->prepare("
                        INSERT INTO BENEVOLES (IDUtilisateur, DateNaissance, Profession, Permis, IDVille, IDRegime, IDHandicap, IDCompetence)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$idUtilisateur, $dateNaissance, $profession, $permis, $idVille, $idRegime, $idHandicap, $idCompetence]);

                    $_SESSION['success'] = "Bénévole ajouté avec succès. Un email avec les identifiants a été envoyé.";
                }

                $db->commit();
                header('Location: benevoles.php');
                exit;

            } catch(Exception $e) {
                $db->rollBack();
                $errors[] = $e->getMessage();
            }
        }
    }
}

$villes = $db->query("SELECT * FROM VILLE ORDER BY NomVille")->fetchAll(PDO::FETCH_ASSOC);
$regimes = $db->query("SELECT * FROM REGIME_ALIMENTAIRE ORDER BY NomRegime")->fetchAll(PDO::FETCH_ASSOC);
$handicaps = $db->query("SELECT * FROM HANDICAP ORDER BY NomHandicap")->fetchAll(PDO::FETCH_ASSOC);
$competences = $db->query("SELECT * FROM COMPETENCE ORDER BY NomCompetence")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1><?php echo $isEdit ? 'Modifier' : 'Ajouter'; ?> un bénévole</h1>
                <a href="benevoles.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo Security::escape($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" class="admin-form">
                    <?php echo Security::getCSRFField(); ?>

                    <div class="form-section">
                        <h3>Informations personnelles</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom">Nom *</label>
                                <input type="text" id="nom" name="nom" required
                                       value="<?php echo Security::escape($benevole['Nom'] ?? ''); ?>"
                                       maxlength="100">
                            </div>

                            <div class="form-group">
                                <label for="prenom">Prénom *</label>
                                <input type="text" id="prenom" name="prenom" required
                                       value="<?php echo Security::escape($benevole['Prenom'] ?? ''); ?>"
                                       maxlength="100">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="mail">Email *</label>
                                <input type="email" id="mail" name="mail" required
                                       value="<?php echo Security::escape($benevole['Mail'] ?? ''); ?>"
                                       maxlength="255">
                                <small class="help-text">Sera utilisé comme identifiant de connexion</small>
                            </div>

                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone"
                                       value="<?php echo Security::escape($benevole['Telephone'] ?? ''); ?>"
                                       placeholder="0123456789"
                                       pattern="[0-9]{10}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_naissance">Date de naissance *</label>
                                <input type="date" id="date_naissance" name="date_naissance" required
                                       value="<?php echo $benevole['DateNaissance'] ?? ''; ?>"
                                       max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                                <small class="help-text">Âge minimum : 18 ans</small>
                            </div>

                            <div class="form-group">
                                <label for="profession">Profession</label>
                                <input type="text" id="profession" name="profession"
                                       value="<?php echo Security::escape($benevole['Profession'] ?? ''); ?>"
                                       maxlength="100">
                            </div>
                        </div>
                    </div>

                    <?php if (!$isEdit): ?>
                        <div class="form-section">
                            <h3>Mot de passe</h3>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule et un chiffre.
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="password">Mot de passe *</label>
                                    <input type="password" id="password" name="password" required
                                           minlength="8">
                                </div>

                                <div class="form-group">
                                    <label for="password_confirm">Confirmer le mot de passe *</label>
                                    <input type="password" id="password_confirm" name="password_confirm" required
                                           minlength="8">
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="form-section">
                            <h3>Changer le mot de passe (optionnel)</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">Nouveau mot de passe</label>
                                    <input type="password" id="new_password" name="new_password" minlength="8">
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirm">Confirmer le nouveau mot de passe</label>
                                    <input type="password" id="new_password_confirm" name="new_password_confirm" minlength="8">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-section">
                        <h3>Informations complémentaires</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_ville">Ville</label>
                                <select id="id_ville" name="id_ville">
                                    <option value="">- Sélectionner -</option>
                                    <?php foreach ($villes as $v): ?>
                                        <option value="<?php echo $v['IDVille']; ?>"
                                            <?php echo ($benevole['IDVille'] ?? '') == $v['IDVille'] ? 'selected' : ''; ?>>
                                            <?php echo Security::escape($v['NomVille'] . ' (' . $v['CodePostal'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_competence">Compétence principale</label>
                                <select id="id_competence" name="id_competence">
                                    <option value="">- Sélectionner -</option>
                                    <?php foreach ($competences as $c): ?>
                                        <option value="<?php echo $c['IDCompetence']; ?>"
                                            <?php echo ($benevole['IDCompetence'] ?? '') == $c['IDCompetence'] ? 'selected' : ''; ?>>
                                            <?php echo Security::escape($c['NomCompetence']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_regime">Régime alimentaire</label>
                                <select id="id_regime" name="id_regime">
                                    <option value="">- Sélectionner -</option>
                                    <?php foreach ($regimes as $r): ?>
                                        <option value="<?php echo $r['IDRegime']; ?>"
                                            <?php echo ($benevole['IDRegime'] ?? '') == $r['IDRegime'] ? 'selected' : ''; ?>>
                                            <?php echo Security::escape($r['NomRegime']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_handicap">Handicap / Limitation</label>
                                <select id="id_handicap" name="id_handicap">
                                    <option value="">- Sélectionner -</option>
                                    <?php foreach ($handicaps as $h): ?>
                                        <option value="<?php echo $h['IDHandicap']; ?>"
                                            <?php echo ($benevole['IDHandicap'] ?? '') == $h['IDHandicap'] ? 'selected' : ''; ?>>
                                            <?php echo Security::escape($h['NomHandicap']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="permis" name="permis" value="1"
                                    <?php echo ($benevole['Permis'] ?? 0) ? 'checked' : ''; ?>>
                                <label for="permis">Possède le permis de conduire</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Mettre à jour' : 'Enregistrer'; ?>
                        </button>
                        <a href="benevoles.php" class="btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validation côté client pour les mots de passe
        <?php if (!$isEdit): ?>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas');
                return false;
            }
        });
        <?php endif; ?>
    </script>

<?php include __DIR__ . '/components/admin_footer.php'; ?>