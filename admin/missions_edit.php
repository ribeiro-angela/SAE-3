<?php
// admin/missions_add.php et admin/missions_edit.php
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
$mission = null;
$errors = [];

if ($isEdit) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM MISSIONS WHERE IDMission = ?");
    $stmt->execute([$id]);
    $mission = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mission) {
        header('Location: missions.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $dateMission = $_POST['date_mission'];
    $heureMission = $_POST['heure_mission'];
    $lieu = trim($_POST['lieu']);
    $nbBenevoles = intval($_POST['nb_benevoles']);
    $idCategorie = intval($_POST['id_categorie']);
    $statut = $_POST['statut'] ?? 'Planifiée';

    if (empty($titre)) $errors[] = "Le titre est requis";
    if (empty($dateMission)) $errors[] = "La date est requise";
    if (empty($heureMission)) $errors[] = "L'heure est requise";
    if (empty($lieu)) $errors[] = "Le lieu est requis";
    if ($nbBenevoles < 1) $errors[] = "Le nombre de bénévoles doit être >= 1";
    if ($idCategorie < 1) $errors[] = "La catégorie est requise";

    if (empty($errors)) {
        try {
            if ($isEdit) {
                $stmt = $db->prepare("
                    UPDATE MISSIONS 
                    SET Titre = ?, Description = ?, DateMission = ?, HeureMission = ?, 
                        Lieu = ?, NbBenevolesAttendu = ?, Statut = ?, IDCategorie = ?
                    WHERE IDMission = ?
                ");
                $stmt->execute([$titre, $description, $dateMission, $heureMission, $lieu, $nbBenevoles, $statut, $idCategorie, $id]);
                $_SESSION['success'] = "Mission modifiée avec succès";
            } else {
                $stmt = $db->prepare("
                    INSERT INTO MISSIONS (Titre, Description, DateMission, HeureMission, Lieu, NbBenevolesAttendu, Statut, IDCategorie, IDResponsable)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$titre, $description, $dateMission, $heureMission, $lieu, $nbBenevoles, $statut, $idCategorie, $_SESSION['user_id']]);
                $_SESSION['success'] = "Mission créée avec succès";
            }

            header('Location: missions.php');
            exit;

        } catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

$categories = $db->query("SELECT * FROM CATEGORIE ORDER BY NomCategorie")->fetchAll(PDO::FETCH_ASSOC);
$statuts = ['Planifiée', 'En cours', 'Terminée', 'Annulée'];

include __DIR__ . '/../components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1><?php echo $isEdit ? 'Modifier' : 'Créer'; ?> une mission</h1>
                <a href="missions.php" class="btn-secondary">
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
                        <h3>Informations générales</h3>

                        <div class="form-group">
                            <label for="titre">Titre de la mission *</label>
                            <input type="text" id="titre" name="titre" required
                                   value="<?php echo htmlspecialchars($mission['Titre'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($mission['Description'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_categorie">Catégorie *</label>
                                <select id="id_categorie" name="id_categorie" required>
                                    <option value="">- Sélectionner -</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['IDCategorie']; ?>"
                                            <?php echo ($mission['IDCategorie'] ?? '') == $cat['IDCategorie'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['NomCategorie']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="statut">Statut *</label>
                                <select id="statut" name="statut" required>
                                    <?php foreach ($statuts as $s): ?>
                                        <option value="<?php echo $s; ?>"
                                            <?php echo ($mission['Statut'] ?? 'Planifiée') == $s ? 'selected' : ''; ?>>
                                            <?php echo $s; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Planification</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_mission">Date *</label>
                                <input type="date" id="date_mission" name="date_mission" required
                                       value="<?php echo $mission['DateMission'] ?? ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="heure_mission">Heure *</label>
                                <input type="time" id="heure_mission" name="heure_mission" required
                                       value="<?php echo $mission['HeureMission'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lieu">Lieu *</label>
                            <input type="text" id="lieu" name="lieu" required
                                   value="<?php echo htmlspecialchars($mission['Lieu'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="nb_benevoles">Nombre de bénévoles attendus *</label>
                            <input type="number" id="nb_benevoles" name="nb_benevoles" min="1" required
                                   value="<?php echo $mission['NbBenevolesAttendu'] ?? 1; ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Mettre à jour' : 'Créer'; ?>
                        </button>
                        <a href="missions.php" class="btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../components/admin_footer.php'; ?>