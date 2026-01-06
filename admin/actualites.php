<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                $id = $_POST['id'];
                $stmt = $db->prepare("DELETE FROM ACTUALITE WHERE IDActualite = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Actualité supprimée avec succès";
                break;

            case 'publish':
                $id = $_POST['id'];
                $stmt = $db->prepare("UPDATE ACTUALITE SET Statut = 'Publié', DatePublication = CURRENT_DATE WHERE IDActualite = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Actualité publiée avec succès";
                break;

            case 'draft':
                $id = $_POST['id'];
                $stmt = $db->prepare("UPDATE ACTUALITE SET Statut = 'Brouillon' WHERE IDActualite = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Actualité mise en brouillon";
                break;
        }
    }
}

// Filtres
$search = isset($_GET['search']) ? $_GET['search'] : '';
$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$auteur_filter = isset($_GET['auteur']) ? $_GET['auteur'] : '';

// Construction de la requête
$sql = "
    SELECT 
        a.IDActualite,
        a.Titre,
        a.Contenu,
        a.DatePublication,
        a.Statut,
        a.ImageURL,
        u.Nom,
        u.Prenom,
        (SELECT COUNT(*) FROM MEDIA m WHERE m.IDActualite = a.IDActualite) as NbMedias
    FROM ACTUALITE a
    LEFT JOIN UTILISATEUR u ON a.IDAuteur = u.IDUtilisateur
    WHERE 1=1
";

$params = [];

if ($search) {
    $sql .= " AND (a.Titre LIKE ? OR a.Contenu LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($statut_filter) {
    $sql .= " AND a.Statut = ?";
    $params[] = $statut_filter;
}

if ($auteur_filter) {
    $sql .= " AND a.IDAuteur = ?";
    $params[] = $auteur_filter;
}

$sql .= " ORDER BY a.DatePublication DESC, a.IDActualite DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Liste des auteurs pour le filtre
$auteurs = $db->query("
    SELECT DISTINCT u.IDUtilisateur, u.Nom, u.Prenom 
    FROM UTILISATEUR u 
    JOIN ACTUALITE a ON u.IDUtilisateur = a.IDAuteur
    ORDER BY u.Nom, u.Prenom
")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Gestion des actualités</h1>
                <a href="/admin/actualites_add.php" class="btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle actualité
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- Filtres -->
            <div class="filters-section">
                <form method="GET" class="filters-form">
                    <div class="filter-group">
                        <label>Rechercher</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                               placeholder="Titre, contenu...">
                    </div>

                    <div class="filter-group">
                        <label>Statut</label>
                        <select name="statut">
                            <option value="">Tous les statuts</option>
                            <option value="Publié" <?php echo $statut_filter === 'Publié' ? 'selected' : ''; ?>>Publié</option>
                            <option value="Brouillon" <?php echo $statut_filter === 'Brouillon' ? 'selected' : ''; ?>>Brouillon</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Auteur</label>
                        <select name="auteur">
                            <option value="">Tous les auteurs</option>
                            <?php foreach ($auteurs as $a): ?>
                                <option value="<?php echo $a['IDUtilisateur']; ?>"
                                    <?php echo $auteur_filter == $a['IDUtilisateur'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($a['Prenom'] . ' ' . $a['Nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">Filtrer</button>
                    <a href="/admin/actualites.php" class="btn-secondary">Réinitialiser</a>
                </form>
            </div>

            <!-- Statistiques rapides -->
            <div class="stats-mini">
                <div class="stat-mini">
                    <span class="stat-mini-label">Total actualités</span>
                    <span class="stat-mini-value"><?php echo count($actualites); ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Publiées</span>
                    <span class="stat-mini-value"><?php echo count(array_filter($actualites, fn($a) => $a['Statut'] == 'Publié')); ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Brouillons</span>
                    <span class="stat-mini-value"><?php echo count(array_filter($actualites, fn($a) => $a['Statut'] == 'Brouillon')); ?></span>
                </div>
            </div>

            <!-- Grille des actualités -->
            <div class="content-section">
                <?php if (empty($actualites)): ?>
                    <p style="text-align: center; color: #7f8c8d; padding: 40px;">
                        Aucune actualité trouvée.
                    </p>
                <?php else: ?>
                    <div class="actualites-grid">
                        <?php foreach ($actualites as $actu): ?>
                            <div class="actualite-card">
                                <?php if ($actu['ImageURL']): ?>
                                    <div class="actualite-image" style="background-image: url('<?php echo htmlspecialchars($actu['ImageURL']); ?>');">
                                        <div class="actualite-status">
                                            <?php if ($actu['Statut'] == 'Publié'): ?>
                                                <span class="status-badge status-active">Publié</span>
                                            <?php else: ?>
                                                <span class="status-badge status-pending">Brouillon</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="actualite-image actualite-no-image">
                                        <i class="fas fa-image"></i>
                                        <div class="actualite-status">
                                            <?php if ($actu['Statut'] == 'Publié'): ?>
                                                <span class="status-badge status-active">Publié</span>
                                            <?php else: ?>
                                                <span class="status-badge status-pending">Brouillon</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="actualite-content">
                                    <h3><?php echo htmlspecialchars($actu['Titre']); ?></h3>
                                    <p class="actualite-excerpt">
                                        <?php echo htmlspecialchars(substr($actu['Contenu'], 0, 150)); ?>...
                                    </p>

                                    <div class="actualite-meta">
                                        <div>
                                            <i class="fas fa-user"></i>
                                            <?php echo htmlspecialchars($actu['Prenom'] . ' ' . $actu['Nom']); ?>
                                        </div>
                                        <div>
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d/m/Y', strtotime($actu['DatePublication'])); ?>
                                        </div>
                                        <?php if ($actu['NbMedias'] > 0): ?>
                                            <div>
                                                <i class="fas fa-images"></i>
                                                <?php echo $actu['NbMedias']; ?> média(s)
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="actualite-actions">
                                        <a href="/admin/actualites_detail.php?id=<?php echo $actu['IDActualite']; ?>"
                                           class="btn-icon" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/actualites_add.php?id=<?php echo $actu['IDActualite']; ?>"
                                           class="btn-icon" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <?php if ($actu['Statut'] == 'Brouillon'): ?>
                                            <form method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Publier cette actualité ?');">
                                                <input type="hidden" name="id" value="<?php echo $actu['IDActualite']; ?>">
                                                <input type="hidden" name="action" value="publish">
                                                <button type="submit" class="btn-icon btn-success" title="Publier">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Remettre en brouillon ?');">
                                                <input type="hidden" name="id" value="<?php echo $actu['IDActualite']; ?>">
                                                <input type="hidden" name="action" value="draft">
                                                <button type="submit" class="btn-icon btn-warning" title="Brouillon">
                                                    <i class="fas fa-file"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <form method="POST" style="display:inline;"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?');">
                                            <input type="hidden" name="id" value="<?php echo $actu['IDActualite']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn-icon btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .actualites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .actualite-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .actualite-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .actualite-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .actualite-no-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }

        .actualite-status {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .actualite-content {
            padding: 20px;
        }

        .actualite-content h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: var(--admin-text);
            line-height: 1.4;
        }

        .actualite-excerpt {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .actualite-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 13px;
            color: #95a5a6;
            padding-top: 15px;
            border-top: 1px solid #ecf0f1;
            margin-bottom: 15px;
        }

        .actualite-meta i {
            margin-right: 5px;
        }

        .actualite-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-success {
            color: var(--admin-success);
        }

        .btn-success:hover {
            background: rgba(46, 204, 113, 0.1);
        }

        .btn-warning {
            color: var(--admin-warning);
        }

        .btn-warning:hover {
            background: rgba(243, 156, 18, 0.1);
        }

        .btn-danger {
            color: var(--admin-danger);
        }

        .btn-danger:hover {
            background: rgba(231, 76, 60, 0.1);
        }

        .status-pending {
            background: rgba(243, 156, 18, 0.1);
            color: var(--admin-warning);
        }
    </style>

<?php include __DIR__ . '/components/admin_footer.php'; ?>