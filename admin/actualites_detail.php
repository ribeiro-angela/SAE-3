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

$id = $_GET['id'] ?? 0;

// Récupération de l'actualité
$stmt = $db->prepare("
    SELECT 
        a.*,
        u.Nom,
        u.Prenom,
        u.Mail as AuteurMail
    FROM ACTUALITE a
    LEFT JOIN UTILISATEUR u ON a.IDAuteur = u.IDUtilisateur
    WHERE a.IDActualite = ?
");
$stmt->execute([$id]);
$actualite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$actualite) {
    header('Location: actualites.php');
    exit;
}

// Récupération des médias associés
$stmt = $db->prepare("
    SELECT *
    FROM MEDIA
    WHERE IDActualite = ?
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
                <h1>Détails de l'actualité</h1>
                <div style="display: flex; gap: 10px;">
                    <a href="actualites_add.php?id=<?php echo $id; ?>" class="btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="actualites.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <!-- En-tête de l'actualité -->
            <div class="content-section">
                <?php if ($actualite['ImageURL']): ?>
                    <div class="actualite-header-image">
                        <img src="<?php echo htmlspecialchars($actualite['ImageURL']); ?>"
                             alt="<?php echo htmlspecialchars($actualite['Titre']); ?>">
                        <div class="image-overlay">
                            <?php if ($actualite['Statut'] == 'Publié'): ?>
                                <span class="status-badge status-active">Publié</span>
                            <?php else: ?>
                                <span class="status-badge status-pending">Brouillon</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="actualite-header-content">
                    <h2><?php echo htmlspecialchars($actualite['Titre']); ?></h2>

                    <div class="actualite-meta-info">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>
                            <strong>Auteur :</strong>
                            <?php echo htmlspecialchars($actualite['Prenom'] . ' ' . $actualite['Nom']); ?>
                        </span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>
                            <strong>Date de publication :</strong>
                            <?php echo date('d/m/Y', strtotime($actualite['DatePublication'])); ?>
                        </span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-info-circle"></i>
                            <span>
                            <strong>Statut :</strong>
                            <?php if ($actualite['Statut'] == 'Publié'): ?>
                                <span class="badge badge-success">Publié</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Brouillon</span>
                            <?php endif; ?>
                        </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu de l'actualité -->
            <div class="content-section">
                <h3>Contenu</h3>
                <div class="actualite-contenu">
                    <?php echo nl2br(htmlspecialchars($actualite['Contenu'])); ?>
                </div>
            </div>

            <!-- Médias associés -->
            <div class="content-section">
                <div class="section-header">
                    <h3>Médias (<?php echo count($medias); ?>)</h3>
                    <a href="medias.php?type=actualite&id=<?php echo $id; ?>" class="btn-primary">
                        <i class="fas fa-images"></i> Gérer les médias
                    </a>
                </div>

                <?php if (empty($medias)): ?>
                    <p style="text-align: center; color: #7f8c8d; padding: 40px;">
                        Aucun média associé à cette actualité.
                    </p>
                <?php else: ?>
                    <div class="media-gallery">
                        <?php foreach ($medias as $media): ?>
                            <div class="media-thumbnail">
                                <?php if ($media['TypeMedia'] === 'Image'): ?>
                                    <a href="<?php echo htmlspecialchars($media['CheminFichier']); ?>"
                                       target="_blank" class="media-link">
                                        <img src="<?php echo htmlspecialchars($media['CheminFichier']); ?>"
                                             alt="<?php echo htmlspecialchars($media['NomFichier']); ?>">
                                        <div class="media-type-badge">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    </a>
                                <?php elseif ($media['TypeMedia'] === 'Vidéo'): ?>
                                    <a href="<?php echo htmlspecialchars($media['CheminFichier']); ?>"
                                       target="_blank" class="media-link">
                                        <video src="<?php echo htmlspecialchars($media['CheminFichier']); ?>"></video>
                                        <div class="media-type-badge">
                                            <i class="fas fa-video"></i>
                                        </div>
                                    </a>
                                <?php elseif ($media['TypeMedia'] === 'Audio'): ?>
                                    <a href="<?php echo htmlspecialchars($media['CheminFichier']); ?>"
                                       target="_blank" class="media-link">
                                        <div class="audio-placeholder">
                                            <i class="fas fa-volume-up"></i>
                                        </div>
                                        <div class="media-type-badge">
                                            <i class="fas fa-music"></i>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <p class="media-filename"><?php echo htmlspecialchars($media['NomFichier']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Actions rapides -->
            <div class="content-section">
                <h3>Actions</h3>
                <div class="actions-grid">
                    <?php if ($actualite['Statut'] == 'Brouillon'): ?>
                        <form method="POST" action="actualites.php" class="action-form">
                            <input type="hidden" name="action" value="publish">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <button type="submit" class="action-btn action-btn-success">
                                <i class="fas fa-check-circle"></i>
                                <span>Publier cette actualité</span>
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="actualites.php" class="action-form">
                            <input type="hidden" name="action" value="draft">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <button type="submit" class="action-btn action-btn-warning">
                                <i class="fas fa-file"></i>
                                <span>Remettre en brouillon</span>
                            </button>
                        </form>
                    <?php endif; ?>

                    <a href="actualites_add.php?id=<?php echo $id; ?>" class="action-btn action-btn-primary">
                        <i class="fas fa-edit"></i>
                        <span>Modifier l'actualité</span>
                    </a>

                    <a href="medias.php?type=actualite&id=<?php echo $id; ?>" class="action-btn action-btn-info">
                        <i class="fas fa-images"></i>
                        <span>Gérer les médias</span>
                    </a>

                    <form method="POST" action="actualites.php" class="action-form"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit" class="action-btn action-btn-danger">
                            <i class="fas fa-trash"></i>
                            <span>Supprimer l'actualité</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .actualite-header-image {
            position: relative;
            width: 100%;
            max-height: 400px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .actualite-header-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-overlay {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .actualite-header-content h2 {
            font-size: 32px;
            color: var(--admin-text);
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .actualite-meta-info {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #7f8c8d;
        }

        .meta-item i {
            color: var(--admin-primary);
            font-size: 18px;
        }

        .actualite-contenu {
            font-size: 16px;
            line-height: 1.8;
            color: var(--admin-text);
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h3 {
            margin: 0;
        }

        .media-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .media-thumbnail {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .media-thumbnail:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .media-link {
            display: block;
            position: relative;
            padding-top: 100%;
            background: #ecf0f1;
            overflow: hidden;
        }

        .media-link img,
        .media-link video {
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

        .media-type-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }

        .media-filename {
            padding: 10px;
            font-size: 13px;
            color: #7f8c8d;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .action-form {
            margin: 0;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            width: 100%;
        }

        .action-btn i {
            font-size: 18px;
        }

        .action-btn-success {
            background: var(--admin-success);
            color: white;
        }

        .action-btn-success:hover {
            background: #27ae60;
            transform: translateY(-2px);
        }

        .action-btn-warning {
            background: var(--admin-warning);
            color: white;
        }

        .action-btn-warning:hover {
            background: #e67e22;
            transform: translateY(-2px);
        }

        .action-btn-primary {
            background: var(--admin-primary);
            color: white;
        }

        .action-btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .action-btn-info {
            background: var(--admin-info);
            color: white;
        }

        .action-btn-info:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .action-btn-danger {
            background: var(--admin-danger);
            color: white;
        }

        .action-btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .badge-success {
            background: rgba(46, 204, 113, 0.1);
            color: var(--admin-success);
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }

        .badge-warning {
            background: rgba(243, 156, 18, 0.1);
            color: var(--admin-warning);
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
    </style>

<?php include __DIR__ . '/components/admin_footer.php'; ?>