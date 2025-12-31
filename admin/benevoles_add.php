<?php
// admin/benevoles_add.php et admin/benevoles_edit.php
// Ce fichier gère à la fois l'ajout ET la modification

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

$isEdit = isset($_GET['id']);
$benevole = null;
$errors = [];

if ($isEdit) {
    $id = $_GET['id'];
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
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $mail = trim($_POST['mail']);
    $telephone = trim($_POST['telephone']);
    $dateNaissance = $_POST['date_naissance'];
    $profession = trim($_POST['profession']);
    $permis = isset($_POST['permis']) ? 1 : 0;
    $idVille = $_POST['id_ville'] ?: null;
    $idRegime = $_POST['id_regime'] ?: null;
    $idHandicap = $_POST['id_handicap'] ?: null;
    $idCompetence = $_POST['id_competence'] ?: null;

    if (empty($nom)) $errors[] = "Le nom est requis";
    if (empty($prenom)) $errors[] = "Le prénom est requis";
    if (empty($mail)) $errors[] = "L'email est requis";
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
    if (empty($dateNaissance)) $errors[] = "La date de naissance est requise";

    if (empty($errors)) {
        try {
            $db->beginTransaction();

            if ($isEdit) {
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

                $_SESSION['success'] = "Bénévole modifié avec succès";
            } else {
                $stmt = $db->prepare("SELECT COUNT(*) FROM UTILISATEUR WHERE Mail = ?");
                $stmt->execute([$mail]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Cet email est déjà utilisé");
                }

                $password = password_hash('benévole123', PASSWORD_DEFAULT);

                $stmt = $db->prepare("
                    INSERT INTO UTILISATEUR (Nom, Prenom, Mail, Telephone, MotDePasse)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$nom, $prenom, $mail, $telephone, $password]);
                $idUtilisateur = $db->lastInsertId();

                $stmt = $db->prepare("
                    INSERT INTO BENEVOLES (IDUtilisateur, DateNaissance, Profession, Permis, IDVille, IDRegime, IDHandicap, IDCompetence)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$idUtilisateur, $dateNaissance, $profession, $permis, $idVille, $idRegime, $idHandicap, $idCompetence]);

                $_SESSION['success'] = "Bénévole ajouté avec succès. Mot de passe par défaut : benévole123";
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

$villes = $db->query("SELECT * FROM VILLE ORDER BY NomVille")->fetchAll(PDO::FETCH_ASSOC);
$regimes = $db->query("SELECT * FROM REGIME_ALIMENTAIRE ORDER BY NomRegime")->fetchAll(PDO::FETCH_ASSOC);
$handicaps = $db->query("SELECT * FROM HANDICAP ORDER BY NomHandicap")->fetchAll(PDO::FETCH_ASSOC);
$competences = $db->query("SELECT * FROM COMPETENCE ORDER BY NomCompetence")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>

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
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" class="admin-form">
                    <div class="form-section">
                        <h3>Informations personnelles</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom">Nom *</label>
                                <input type="text" id="nom" name="nom" required
                                       value="<?php echo htmlspecialchars($benevole['Nom'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="prenom">Prénom *</label>
                                <input type="text" id="prenom" name="prenom" required
                                       value="<?php echo htmlspecialchars($benevole['Prenom'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="mail">Email *</label>
                                <input type="email" id="mail" name="mail" required
                                       value="<?php echo htmlspecialchars($benevole['Mail'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone"
                                       value="<?php echo htmlspecialchars($benevole['Telephone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_naissance">Date de naissance *</label>
                                <input type="date" id="date_naissance" name="date_naissance" required
                                       value="<?php echo $benevole['DateNaissance'] ?? ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="profession">Profession</label>
                                <input type="text" id="profession" name="profession"
                                       value="<?php echo htmlspecialchars($benevole['Profession'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

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
                                            <?php echo htmlspecialchars($v['NomVille'] . ' (' . $v['CodePostal'] . ')'); ?>
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
                                            <?php echo htmlspecialchars($c['NomCompetence']); ?>
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
                                            <?php echo htmlspecialchars($r['NomRegime']); ?>
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
                                            <?php echo htmlspecialchars($h['NomHandicap']); ?>
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

<?php include __DIR__ . '/../components/admin_footer.php'; ?>