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
                $stmt = $db->prepare("UPDATE BENEVOLES SET Actif = 0 WHERE IDUtilisateur = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Bénévole désactivé avec succès";
                break;

            case 'activate':
                $id = $_POST['id'];
                $stmt = $db->prepare("UPDATE BENEVOLES SET Actif = 1 WHERE IDUtilisateur = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Bénévole réactivé avec succès";
                break;
        }
    }
}

// Filtres
$search = isset($_GET['search']) ? $_GET['search'] : '';
$ville_filter = isset($_GET['ville']) ? $_GET['ville'] : '';
$actif_filter = isset($_GET['actif']) ? $_GET['actif'] : '1';

// Construction de la requête
$sql = "
    SELECT 
        b.IDUtilisateur,
        u.Nom,
        u.Prenom,
        u.Mail,
        u.Telephone,
        b.DateNaissance,
        b.Profession,
        b.Actif,
        b.Permis,
        v.NomVille,
        r.NomRegime,
        h.NomHandicap,
        c.NomCompetence,
        (SELECT COUNT(*) FROM PARTICIPE_MISSION pm WHERE pm.IDUtilisateur = b.IDUtilisateur) as NbMissions
    FROM BENEVOLES b
    JOIN UTILISATEUR u ON b.IDUtilisateur = u.IDUtilisateur
    LEFT JOIN VILLE v ON b.IDVille = v.IDVille
    LEFT JOIN REGIME_ALIMENTAIRE r ON b.IDRegime = r.IDRegime
    LEFT JOIN HANDICAP h ON b.IDHandicap = h.IDHandicap
    LEFT JOIN COMPETENCE c ON b.IDCompetence = c.IDCompetence
    WHERE 1=1
";

$params = [];

if ($search) {
    $sql .= " AND (u.Nom LIKE ? OR u.Prenom LIKE ? OR u.Mail LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($ville_filter) {
    $sql .= " AND v.NomVille = ?";
    $params[] = $ville_filter;
}

if ($actif_filter !== '') {
    $sql .= " AND b.Actif = ?";
    $params[] = $actif_filter;
}

$sql .= " ORDER BY u.Nom, u.Prenom";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$benevoles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Liste des villes pour le filtre
$villes = $db->query("SELECT DISTINCT NomVille FROM VILLE ORDER BY NomVille")->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/../components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Gestion des bénévoles</h1>
                <a href="/admin/benevoles_add.php" class="btn-primary">
                    <i class="fas fa-user-plus"></i> Ajouter un bénévole
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
                               placeholder="Nom, prénom, email...">
                    </div>

                    <div class="filter-group">
                        <label>Ville</label>
                        <select name="ville">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?php echo htmlspecialchars($v); ?>"
                                    <?php echo $ville_filter === $v ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($v); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Statut</label>
                        <select name="actif">
                            <option value="">Tous</option>
                            <option value="1" <?php echo $actif_filter === '1' ? 'selected' : ''; ?>>Actifs</option>
                            <option value="0" <?php echo $actif_filter === '0' ? 'selected' : ''; ?>>Inactifs</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">Filtrer</button>
                    <a href="/admin/benevoles.php" class="btn-secondary">Réinitialiser</a>
                </form>
            </div>

            <!-- Statistiques rapides -->
            <div class="stats-mini">
                <div class="stat-mini">
                    <span class="stat-mini-label">Total bénévoles</span>
                    <span class="stat-mini-value"><?php echo count($benevoles); ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Actifs</span>
                    <span class="stat-mini-value"><?php echo count(array_filter($benevoles, fn($b) => $b['Actif'] == 1)); ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Avec permis</span>
                    <span class="stat-mini-value"><?php echo count(array_filter($benevoles, fn($b) => $b['Permis'] == 1)); ?></span>
                </div>
            </div>

            <!-- Table des bénévoles -->
            <div class="content-section">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Ville</th>
                            <th>Profession</th>
                            <th>Compétence</th>
                            <th>Missions</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($benevoles as $b): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($b['Nom'] . ' ' . $b['Prenom']); ?></strong>
                                    <?php if ($b['Permis']): ?>
                                        <i class="fas fa-car text-success" title="Permis de conduire"></i>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($b['Mail']); ?></td>
                                <td><?php echo htmlspecialchars($b['Telephone'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($b['NomVille'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($b['Profession'] ?? '-'); ?></td>
                                <td>
                                    <?php if ($b['NomCompetence']): ?>
                                        <span class="badge"><?php echo htmlspecialchars($b['NomCompetence']); ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?php echo $b['NbMissions']; ?></span>
                                </td>
                                <td>
                                    <?php if ($b['Actif']): ?>
                                        <span class="status-badge status-active">Actif</span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/admin/benevoles_detail.php?id=<?php echo $b['IDUtilisateur']; ?>"
                                       class="btn-icon" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/benevoles_edit.php?id=<?php echo $b['IDUtilisateur']; ?>"
                                       class="btn-icon" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;"
                                          onsubmit="return confirm('Êtes-vous sûr ?');">
                                        <input type="hidden" name="id" value="<?php echo $b['IDUtilisateur']; ?>">
                                        <input type="hidden" name="action" value="<?php echo $b['Actif'] ? 'delete' : 'activate'; ?>">
                                        <button type="submit" class="btn-icon"
                                                title="<?php echo $b['Actif'] ? 'Désactiver' : 'Activer'; ?>">
                                            <i class="fas fa-<?php echo $b['Actif'] ? 'trash' : 'check'; ?>"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .filters-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 14px;
            color: var(--admin-text);
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--admin-border);
            border-radius: 6px;
            font-size: 14px;
        }

        .stats-mini {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-mini {
            flex: 1;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-mini-label {
            font-size: 14px;
            color: #7f8c8d;
        }

        .stat-mini-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--admin-primary);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.1);
            color: var(--admin-success);
            border-left: 4px solid var(--admin-success);
        }

        .badge-info {
            background: var(--admin-info);
            color: white;
        }

        .status-active {
            background: rgba(46, 204, 113, 0.1);
            color: var(--admin-success);
        }

        .status-inactive {
            background: rgba(149, 165, 166, 0.1);
            color: #95a5a6;
        }

        .text-success {
            color: var(--admin-success);
            margin-left: 5px;
        }
    </style>

<?php include __DIR__ . '/../components/admin_footer.php'; ?>