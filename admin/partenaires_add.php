<?php
// admin/partenaires_add.php et admin/partenaires_edit.php
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
$partenaire = null;
$errors = [];

if ($isEdit) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM PARTENAIRE WHERE IDPartenaire = ?");
    $stmt->execute([$id]);
    $partenaire = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$partenaire) {
        header('Location: partenaires.php');
        exit;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomPartenaire = trim($_POST['nom_partenaire']);
    $typePartenaire = trim($_POST['type_partenaire']);
    $telephone = trim($_POST['telephone']);
    $mail = trim($_POST['mail']);
    $actif = isset($_POST['actif']) ? 1 : 0;

    if (empty($nomPartenaire)) $errors[] = "Le nom du partenaire est requis";
    if (empty($typePartenaire)) $errors[] = "Le type de partenaire est requis";
    if (!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";

    if (empty($errors)) {
        try {
            if ($isEdit) {
                $stmt = $db->prepare("
                    UPDATE PARTENAIRE 
                    SET NomPartenaire = ?, TypePartenaire = ?, Telephone = ?, Mail = ?, Actif = ?
                    WHERE IDPartenaire = ?
                ");
                $stmt->execute([$nomPartenaire, $typePartenaire, $telephone, $mail, $actif, $id]);

                $_SESSION['success'] = "Partenaire modifié avec succès";
            } else {
                $stmt = $db->prepare("
                    INSERT INTO PARTENAIRE (NomPartenaire, TypePartenaire, Telephone, Mail, Actif)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$nomPartenaire, $typePartenaire, $telephone, $mail, $actif]);

                $_SESSION['success'] = "Partenaire ajouté avec succès";
            }

            header('Location: partenaires.php');
            exit;

        } catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1><?php echo $isEdit ? 'Modifier' : 'Ajouter'; ?> un partenaire</h1>
                <a href="partenaires.php" class="btn-secondary">
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
                        <h3>Informations du partenaire</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom_partenaire">Nom du partenaire *</label>
                                <input type="text" id="nom_partenaire" name="nom_partenaire" required
                                       value="<?php echo htmlspecialchars($partenaire['NomPartenaire'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="type_partenaire">Type de partenaire *</label>
                                <select id="type_partenaire" name="type_partenaire" required>
                                    <option value="">- Sélectionner -</option>
                                    <option value="Entreprise" <?php echo ($partenaire['TypePartenaire'] ?? '') == 'Entreprise' ? 'selected' : ''; ?>>Entreprise</option>
                                    <option value="Association" <?php echo ($partenaire['TypePartenaire'] ?? '') == 'Association' ? 'selected' : ''; ?>>Association</option>
                                    <option value="Collectivité" <?php echo ($partenaire['TypePartenaire'] ?? '') == 'Collectivité' ? 'selected' : ''; ?>>Collectivité</option>
                                    <option value="Organisme public" <?php echo ($partenaire['TypePartenaire'] ?? '') == 'Organisme public' ? 'selected' : ''; ?>>Organisme public</option>
                                    <option value="Fondation" <?php echo ($partenaire['TypePartenaire'] ?? '') == 'Fondation' ? 'selected' : ''; ?>>Fondation</option>
                                    <option value="Autre" <?php echo ($partenaire['TypePartenaire'] ?? '') == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="mail">Email</label>
                                <input type="email" id="mail" name="mail"
                                       value="<?php echo htmlspecialchars($partenaire['Mail'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone"
                                       value="<?php echo htmlspecialchars($partenaire['Telephone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="actif" name="actif" value="1"
                                    <?php echo ($partenaire['Actif'] ?? 1) ? 'checked' : ''; ?>>
                                <label for="actif">Partenaire actif</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Mettre à jour' : 'Enregistrer'; ?>
                        </button>
                        <a href="partenaires.php" class="btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/components/admin_footer.php'; ?>