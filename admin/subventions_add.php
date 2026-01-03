<?php
// admin/subventions_add.php et admin/subventions_edit.php
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
$subvention = null;
$errors = [];
$partenairePreselect = $_GET['partenaire'] ?? null;

if ($isEdit) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM SUBVENTION WHERE IDSubvention = ?");
    $stmt->execute([$id]);
    $subvention = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$subvention) {
        header('Location: subventions.php');
        exit;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $organismeFinanceur = trim($_POST['organisme_financeur']);
    $montant = floatval($_POST['montant']);
    $annee = intval($_POST['annee']);
    $statut = $_POST['statut'];
    $idPartenaire = $_POST['id_partenaire'] ?: null;

    if (empty($organismeFinanceur)) $errors[] = "L'organisme financeur est requis";
    if ($montant <= 0) $errors[] = "Le montant doit être supérieur à 0";
    if ($annee < 2000 || $annee > 2100) $errors[] = "L'année est invalide";
    if (empty($statut)) $errors[] = "Le statut est requis";

    if (empty($errors)) {
        try {
            if ($isEdit) {
                $stmt = $db->prepare("
                    UPDATE SUBVENTION 
                    SET OrganismeFinanceur = ?, Montant = ?, Annee = ?, Statut = ?, IDPartenaire = ?
                    WHERE IDSubvention = ?
                ");
                $stmt->execute([$organismeFinanceur, $montant, $annee, $statut, $idPartenaire, $id]);

                $_SESSION['success'] = "Subvention modifiée avec succès";
            } else {
                $stmt = $db->prepare("
                    INSERT INTO SUBVENTION (OrganismeFinanceur, Montant, Annee, Statut, IDPartenaire)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$organismeFinanceur, $montant, $annee, $statut, $idPartenaire]);

                $_SESSION['success'] = "Subvention ajoutée avec succès";
            }

            // Redirection vers la page du partenaire si présélectionné
            if ($idPartenaire) {
                header('Location: partenaires_detail.php?id=' . $idPartenaire);
            } else {
                header('Location: subventions.php');
            }
            exit;

        } catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

// Récupération de la liste des partenaires
$partenaires = $db->query("SELECT IDPartenaire, NomPartenaire FROM PARTENAIRE WHERE Actif = 1 ORDER BY NomPartenaire")->fetchAll(PDO::FETCH_ASSOC);

// Année actuelle par défaut
$anneeActuelle = date('Y');

include __DIR__ . '/components/admin_header.php';
?>

<div class="admin-container">
    <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

    <div class="admin-content">
        <div class="content-header">
            <h1><?php echo $isEdit ? 'Modifier' : 'Ajouter'; ?> une subvention</h1>
            <a href="<?php echo $partenairePreselect ? 'partenaires_detail.php?id=' . $partenairePreselect : 'subventions.php'; ?>" class="btn-secondary">
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
                    <h3>Informations de la subvention</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="organisme_financeur">Organisme financeur *</label>
                            <input type="text" id="organisme_financeur" name="organisme_financeur" required
                                   value="<?php echo htmlspecialchars($subvention['OrganismeFinanceur'] ?? ''); ?>"
                                   placeholder="Ex: Région Île-de-France, Fondation de France...">
                        </div>

                        <div class="form-group">
                            <label for="id_partenaire">Partenaire associé</label>
                            <select id="id_partenaire" name="id_partenaire">
                                <option value="">- Aucun partenaire -</option>
                                <?php foreach ($partenaires as $p): ?>
                                    <option value="<?php echo $p['IDPartenaire']; ?>"
                                        <?php
                                        $selected = ($subvention['IDPartenaire'] ?? $partenairePreselect) == $p['IDPartenaire'];
                                        echo $selected ? 'selected' : '';
                                        ?>>
                                        <?php echo htmlspecialchars($p['NomPartenaire']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="montant">Montant (€) *</label>
                            <input type="number" id="montant" name="montant" required step="0.01" min="0"
                                   value="<?php echo $subvention['Montant'] ?? ''; ?>"
                                   placeholder="Ex: 15000">
                        </div>

                        <div class="form-group">
                            <label for="annee">Année *</label>
                            <input type="number" id="annee" name="annee" required min="2000" max="2100"
                                   value="<?php echo $subvention['Annee'] ?? $anneeActuelle; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="statut">Statut *</label>
                        <select id="statut" name="statut" required>
                            <option value="">- Sélectionner -</option>
                            <option value="Demandée" <?php echo ($subvention['Statut'] ?? 'Demandée') == 'Demandée' ? 'selected' : ''; ?>>Demandée</option>
                            <option value="En cours d'instruction" <?php echo ($subvention['Statut'] ?? '') == 'En cours d\'instruction' ? 'selected' : ''; ?>>En cours d'instruction</option>
                            <option value="Acceptée" <?php echo ($subvention['Statut'] ?? '') == 'Acceptée' ? 'selected' : ''; ?>>Acceptée</option>
                            <option value="Refusée" <?php echo ($subvention['Statut'] ?? '') == 'Refusée' ? 'selected' : ''; ?>>Refusée</option>
                            <option value="Versée" <?php echo ($subvention['Statut'] ?? '') == 'Versée' ? 'selected' : ''; ?>>Versée</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> <?php echo $isEdit ? 'Mettre à jour' : 'Enregistrer'; ?>
                    </button>
                    <a href="<?php echo $partenairePreselect ? 'partenaires_detail.php?id=' . $partenairePreselect : 'subventions.php'; ?>" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>

        <?php if (!$isEdit): ?>
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <div>
                    <h4>À propos des subventions</h4>
                    <p>Les subventions peuvent être associées à un partenaire pour faciliter le suivi. Si l'organisme financeur n'est pas encore enregistré comme partenaire, vous pouvez d'abord <a href="partenaires_add.php">créer un nouveau partenaire</a>.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>