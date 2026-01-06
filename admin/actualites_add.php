<?php
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
$actualite = null;
$errors = [];

if ($isEdit) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM ACTUALITE WHERE IDActualite = ?");
    $stmt->execute([$id]);
    $actualite = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$actualite) {
        header('Location: actualites.php');
        exit;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $imageURL = trim($_POST['image_url']);
    $statut = $_POST['statut'];
    $idAuteur = $_SESSION['user_id'];

    if (empty($titre)) $errors[] = "Le titre est requis";
    if (empty($contenu)) $errors[] = "Le contenu est requis";

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/actualites/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('actu_') . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $imageURL = '/uploads/actualites/' . $filename;
        } else {
            $errors[] = "Erreur lors de l'upload de l'image";
        }
    }

    if (empty($errors)) {
        try {
            if ($isEdit) {
                $stmt = $db->prepare("
                    UPDATE ACTUALITE 
                    SET Titre = ?, Contenu = ?, ImageURL = ?, Statut = ?
                    WHERE IDActualite = ?
                ");
                $stmt->execute([$titre, $contenu, $imageURL, $statut, $id]);

                $_SESSION['success'] = "Actualité modifiée avec succès";
            } else {
                $stmt = $db->prepare("
                    INSERT INTO ACTUALITE (Titre, Contenu, ImageURL, Statut, IDAuteur, DatePublication)
                    VALUES (?, ?, ?, ?, ?, CURRENT_DATE)
                ");
                $stmt->execute([$titre, $contenu, $imageURL, $statut, $idAuteur]);

                $_SESSION['success'] = "Actualité créée avec succès";
            }

            header('Location: actualites.php');
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
                <h1><?php echo $isEdit ? 'Modifier' : 'Créer'; ?> une actualité</h1>
                <a href="actualites.php" class="btn-secondary">
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
                <form method="POST" enctype="multipart/form-data" class="admin-form">
                    <div class="form-section">
                        <h3>Informations de l'actualité</h3>

                        <div class="form-group">
                            <label for="titre">Titre *</label>
                            <input type="text" id="titre" name="titre" required
                                   value="<?php echo htmlspecialchars($actualite['Titre'] ?? ''); ?>"
                                   placeholder="Titre accrocheur de l'actualité">
                        </div>

                        <div class="form-group">
                            <label for="contenu">Contenu *</label>
                            <textarea id="contenu" name="contenu" required rows="10"
                                      placeholder="Rédigez le contenu de votre actualité..."><?php echo htmlspecialchars($actualite['Contenu'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="statut">Statut</label>
                                <select id="statut" name="statut">
                                    <option value="Brouillon" <?php echo ($actualite['Statut'] ?? 'Brouillon') == 'Brouillon' ? 'selected' : ''; ?>>
                                        Brouillon
                                    </option>
                                    <option value="Publié" <?php echo ($actualite['Statut'] ?? '') == 'Publié' ? 'selected' : ''; ?>>
                                        Publié
                                    </option>
                                </select>
                                <small>Les brouillons ne sont pas visibles sur le site public</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Image de couverture</h3>

                        <?php if (!empty($actualite['ImageURL'])): ?>
                            <div class="current-image">
                                <img src="<?php echo htmlspecialchars($actualite['ImageURL']); ?>" alt="Image actuelle">
                                <p>Image actuelle</p>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="image">Télécharger une nouvelle image</label>
                            <input type="file" id="image" name="image" accept="image/*">
                            <small>Format acceptés: JPG, PNG, GIF. Taille max: 5MB</small>
                        </div>

                        <div class="form-group">
                            <label for="image_url">Ou entrez une URL d'image</label>
                            <input type="url" id="image_url" name="image_url"
                                   value="<?php echo htmlspecialchars($actualite['ImageURL'] ?? ''); ?>"
                                   placeholder="https://example.com/image.jpg">
                        </div>
                    </div>

                    <?php if ($isEdit): ?>
                        <div class="form-section">
                            <h3>Gestion des médias</h3>
                            <a href="medias.php?type=actualite&id=<?php echo $id; ?>" class="btn-secondary">
                                <i class="fas fa-images"></i> Gérer les médias de cette actualité
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Mettre à jour' : 'Créer l\'actualité'; ?>
                        </button>
                        <a href="actualites.php" class="btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        textarea {
            font-family: inherit;
            resize: vertical;
        }

        .current-image {
            margin-bottom: 20px;
            text-align: center;
        }

        .current-image img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .current-image p {
            margin-top: 10px;
            color: #7f8c8d;
            font-size: 14px;
        }

        input[type="file"] {
            padding: 8px;
        }
    </style>

<?php include __DIR__ . '/components/admin_footer.php'; ?>