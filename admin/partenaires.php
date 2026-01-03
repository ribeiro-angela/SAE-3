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
                $stmt = $db->prepare("UPDATE PARTENAIRE SET Actif = 0 WHERE IDPartenaire = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Partenaire désactivé avec succès";
                break;

            case 'activate':
                $id = $_POST['id'];
                $stmt = $db->prepare("UPDATE PARTENAIRE SET Actif = 1 WHERE IDPartenaire = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Partenaire réactivé avec succès";
                break;
        }
    }
}

// Filtres
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$actif_filter = isset($_GET['actif']) ? $_GET['actif'] : '1';

// Construction de la requête
$sql = "
    SELECT 
        p.IDPartenaire,
        p.NomPartenaire,
        p.TypePartenaire,
        p.Telephone,
        p.Mail,
        p.Actif,
        p.DateCreation,
        (SELECT COUNT(*) FROM SUBVENTION s WHERE s.IDPartenaire = p.IDPartenaire) as NbSubventions,
        (SELECT COALESCE(SUM(s.Montant), 0) FROM SUBVENTION s WHERE s.IDPartenaire = p.IDPartenaire) as TotalSubventions
    FROM PARTENAIRE p
    WHERE 1=1
";

$params = [];

if ($search) {
    $sql .= " AND (p.NomPartenaire LIKE ? OR p.Mail LIKE ? OR p.Telephone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($type_filter) {
    $sql .= " AND p.TypePartenaire = ?";
    $params[] = $type_filter;
}

if ($actif_filter !== '') {
    $sql .= " AND p.Actif = ?";
    $params[] = $actif_filter;
}

$sql .= " ORDER BY p.NomPartenaire";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$partenaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Liste des types pour le filtre
$types = $db->query("SELECT DISTINCT TypePartenaire FROM PARTENAIRE ORDER BY TypePartenaire")->fetchAll(PDO::FETCH_COLUMN);


include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Gestion des partenaires</h1>
                <a href="/admin/partenaires_add.php" class="btn-primary">
                    <i class="fas fa-handshake"></i> Ajouter un partenaire
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
                               placeholder="Nom, email, téléphone...">
                    </div>

                    <div class="filter-group">
                        <label>Type</label>
                        <select name="type">
                            <option value="">Tous les types</option>
                            <?php foreach ($types as $t): ?>
                                <option value="<?php echo htmlspecialchars($t); ?>"
                                        <?php echo $type_filter === $t ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t); ?>
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
                    <a href="/admin/partenaires.php" class="btn-secondary">Réinitialiser</a>
                </form>
            </div>

            <!-- Statistiques rapides -->
            <div class="stats-mini">
                <div class="stat-mini">
                    <span class="stat-mini-label">Total partenaires</span>
                    <span class="stat-mini-value"><?php echo count($partenaires); ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Actifs</span>
                    <span class="stat-mini-value"><?php echo count(array_filter($partenaires, fn($p) => $p['Actif'] == 1)); ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Total subventions</span>
                    <span class="stat-mini-value"><?php echo number_format(array_sum(array_column($partenaires, 'TotalSubventions')), 0, ',', ' '); ?> €</span>
                </div>
            </div>

            <!-- Table des partenaires -->
            <div class="content-section">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Nom du partenaire</th>
                            <th>Type</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Subventions</th>
                            <th>Montant total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($partenaires as $p): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($p['NomPartenaire']); ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-type"><?php echo htmlspecialchars($p['TypePartenaire']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($p['Mail'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($p['Telephone'] ?? '-'); ?></td>
                                <td>
                                    <span class="badge badge-info"><?php echo $p['NbSubventions']; ?></span>
                                </td>
                                <td>
                                    <strong><?php echo number_format($p['TotalSubventions'], 0, ',', ' '); ?> €</strong>
                                </td>
                                <td>
                                    <?php if ($p['Actif']): ?>
                                        <span class="status-badge status-active">Actif</span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/admin/partenaires_detail.php?id=<?php echo $p['IDPartenaire']; ?>"
                                       class="btn-icon" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/partenaires_add.php?id=<?php echo $p['IDPartenaire']; ?>"
                                       class="btn-icon" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display:inline;"
                                          onsubmit="return confirm('Êtes-vous sûr ?');">
                                        <input type="hidden" name="id" value="<?php echo $p['IDPartenaire']; ?>">
                                        <input type="hidden" name="action" value="<?php echo $p['Actif'] ? 'delete' : 'activate'; ?>">
                                        <button type="submit" class="btn-icon"
                                                title="<?php echo $p['Actif'] ? 'Désactiver' : 'Activer'; ?>">
                                            <i class="fas fa-<?php echo $p['Actif'] ? 'trash' : 'check'; ?>"></i>
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
        .status-en {
            background: rgba(52, 152, 219, 0.1);
            color: var(--admin-info);
        }
    </style>

<?php include __DIR__ . '/components/admin_footer.php'; ?>