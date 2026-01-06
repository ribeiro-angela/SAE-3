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

// Récupération des paramètres
$type = $_GET['type'] ?? 'actualite'; // actualite, mission, evenement
$id = $_GET['id'] ?? 0;

// Validation du type et de l'ID
$validTypes = ['actualite', 'mission', 'evenement'];
if (!in_array($type, $validTypes) || !$id) {
    header('Location: dashboard.php');
    exit;
}

// Récupération de l'entité parente
$entity = null;
$entityName = '';
$backUrl = '';

switch($type) {
    case 'actualite':
        $stmt = $db->prepare("SELECT Titre FROM ACTUALITE WHERE IDActualite = ?");
        $stmt->execute([$id]);
        $entity = $stmt->fetch(PDO::FETCH_ASSOC);
        $entityName = $entity ? $entity['Titre'] : '';
        $backUrl = 'actualites_add.php?id=' . $id;
        break;
    case 'mission':
        $stmt = $db->prepare("SELECT Titre FROM MISSIONS WHERE IDMission = ?");
        $stmt->execute([$id]);
        $entity = $stmt->fetch(PDO::FETCH_ASSOC);
        $entityName = $entity ? $entity['Titre'] : '';
        $backUrl = 'missions_detail.php?id=' . $id;
        break;
    case 'evenement':
        $stmt = $db->prepare("SELECT NomEvenement FROM EVENEMENT WHERE IDEvenement = ?");
        $stmt->execute([$id]);
        $entity = $stmt->fetch(PDO::FETCH_ASSOC);
        $entityName = $entity ? $entity['NomEvenement'] : '';
        $backUrl = 'evenements_detail.php?id=' . $id;
        break;
}

if (!$entity) {
    header('Location: dashboard.php');
    exit;
}

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'upload':
                if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../uploads/' . $type . 's/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $extension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
                    $filename = uniqid($type . '_') . '.' . $extension;
                    $uploadPath = $uploadDir . $filename;
                    $relativePath = '/uploads/' . $type . 's/' . $filename;

                    if (move_uploaded_file($_FILES['media']['tmp_name'], $uploadPath)) {
                        // Déterminer le type de média
                        $typeMedia = 'Image';
                        $mimeType = mime_content_type($uploadPath);
                        if (strpos($mimeType, 'video') !== false) {
                            $typeMedia = 'Vidéo';
                        } elseif (strpos($mimeType, 'audio') !== false) {
                            $typeMedia = 'Audio';
                        }

                        // Insérer dans la base
                        $columnName = 'ID' . ucfirst($type);
                        $stmt = $db->prepare("
                            INSERT INTO MEDIA (NomFichier, CheminFichier, TypeMedia, $columnName)
                            VALUES (?, ?, ?, ?)
                        ");
                        $stmt->execute([
                            $_FILES['media']['name'],
                            $relativePath,
                            $typeMedia,
                            $id
                        ]);

                        $_SESSION['success'] = "Média ajouté avec succès";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'upload";
                    }
                }
                break;

            case 'delete':
                $mediaId = $_POST['media_id'];
                $stmt = $db->prepare("SELECT CheminFichier FROM MEDIA WHERE IDMedia = ?");
                $stmt->execute([$mediaId]);
                $media = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($media) {
                    // Supprimer le fichier
                    $filePath = __DIR__ . '/..' . $media['CheminFichier'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }

                    // Supprimer de la base
                    $stmt = $db->prepare("DELETE FROM MEDIA WHERE IDMedia = ?");
                    $stmt->execute([$mediaId]);

                    $_SESSION['success'] = "Média supprimé avec succès";
                }
                break;
        }
    }
    header('Location: medias.php?type=' . $type . '&id=' . $id);
    exit;
}

// Récupération des médias
$columnName = 'ID' . ucfirst($type);
$stmt = $db->prepare("
    SELECT *
    FROM MEDIA
    WHERE $columnName = ?
    ORDER BY DateAjout DESC
");
$stmt->execute([$id]);
$medias = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <div>
                    <h1>Médias</h1>
                    <p style="color: #7f8c8d; margin-top: 5px;">
                        <?php echo htmlspecialchars($entityName); ?>
                    </p>
                </div>
                <a href="<?php echo $backUrl; ?>" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Upload de nouveaux médias -->
            <div class="content-section">
                <h2>Ajouter un média</h2>
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <input type="hidden" name="action" value="upload">
                    <div class="upload-area">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Glissez-déposez vos fichiers ici ou cliquez pour sélectionner</p>
                        <input type="file" name="media" id="media" required accept="image/*,video/*,audio/*">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-upload"></i> Télécharger
                        </button>
                    </div>
                    <small>Formats acceptés: Images (JPG, PNG, GIF), Vidéos (MP4, WebM), Audio (MP3, WAV). Taille max: 10MB</small>
                </form>
            </div>

            <!-- Galerie des médias -->
            <div class="content-section">
                <h2>Galerie (<?php echo count($medias); ?>)</h2>

                <?php if (empty($medias)): ?>
                    <p style="text-align: center; color: #7f8c8d; padding: 40px;">
                        Aucun média pour le moment.
                    </p>
                <?php else: ?>
                    <div class="media-grid">
                        <?php foreach ($medias as $media): ?>
                            <div class="media-item">
                                <div class="media-preview">
                                    <?php if ($media['TypeMedia'] === 'Image'): ?>
                                        <img src="<?php echo htmlspecialchars($media['CheminFichier']); ?>"
                                             alt="<?php echo htmlspecialchars($media['NomFichier']); ?>">
                                    <?php elseif ($media['TypeMedia'] === 'Vidéo'): ?>
                                        <video controls>
                                            <source src="<?php echo htmlspecialchars($media['CheminFichier']); ?>">
                                        </video>
                                    <?php elseif ($media['TypeMedia'] === 'Audio'): ?>
                                        <div class="audio-placeholder">
                                            <i class="fas fa-volume-up"></i>
                                        </div>
                                        <audio controls>
                                            <source src="<?php echo htmlspecialchars($media['CheminFichier']); ?>">
                                        </audio>
                                    <?php endif; ?>

                                    <div class="media-overlay">
                                        <a href="<?php echo htmlspecialchars($media['CheminFichier']); ?>"
                                           target="_blank" class="btn-icon" title="Voir en plein écran">
                                            <i class="fas fa-expand"></i>
                                        </a>
                                        <form method="POST" style="display:inline;"
                                              onsubmit="return confirm('Supprimer ce média ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="media_id" value="<?php echo $media['IDMedia']; ?>">
                                            <button type="submit" class="btn-icon btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="media-info">
                                    <p class="media-name"><?php echo htmlspecialchars($media['NomFichier']); ?></p>
                                    <p class="media-meta">
                                        <span class="badge"><?php echo htmlspecialchars($media['TypeMedia']); ?></span>
                                        <span><?php echo date('d/m/Y', strtotime($media['DateAjout'])); ?></span>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .upload-form {
            max-width: 600px;
        }

        .upload-area {
            border: 2px dashed var(--admin-border);
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            margin-bottom: 10px;
        }

        .upload-area i {
            font-size: 48px;
            color: var(--admin-primary);
            margin-bottom: 15px;
        }

        .upload-area p {
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .upload-area input[type="file"] {
            display: block;
            margin: 20px auto;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .media-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .media-preview {
            position: relative;
            padding-top: 75%; /* Ratio 4:3 */
            background: #ecf0f1;
            overflow: hidden;
        }

        .media-preview img,
        .media-preview video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .audio-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 48px;
        }

        .media-preview audio {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            width: calc(100% - 20px);
        }

        .media-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .media-item:hover .media-overlay {
            opacity: 1;
        }

        .media-overlay .btn-icon {
            background: white;
            color: var(--admin-text);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .media-overlay .btn-danger {
            background: var(--admin-danger);
            color: white;
        }

        .media-info {
            padding: 15px;
        }

        .media-name {
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .media-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #95a5a6;
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--admin-danger);
            border-left: 4px solid var(--admin-danger);
        }
    </style>

<?php include __DIR__ . '/components/admin_footer.php'; ?>