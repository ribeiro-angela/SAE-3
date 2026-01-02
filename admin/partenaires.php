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

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                $id = $_POST['id'];
                $stmt = $db->prepare("UPDATE MISSIONS SET Statut = 'Annulée' WHERE IDMission = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Mission annulée avec succès";
                break;
        }
    }
}

// Filtres
$search = isset($_GET['search']) ? $_GET['search'] : '';
$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$categorie_filter = isset($_GET['categorie']) ? $_GET['categorie'] : '';

// Construction de la requête
$sql = "
    SELECT 
        m.*,
        c.NomCategorie,
        c.Couleur,
        u.Nom || ' ' || u.Prenom as Responsable,
        (SELECT COUNT(*) FROM PARTICIPE_MISSION pm WHERE pm.IDMission = m.IDMission) as NbInscrits
    FROM MISSIONS m
    LEFT JOIN CATEGORIE c ON m.IDCategorie = c.IDCategorie
    LEFT JOIN UTILISATEUR u ON m.IDResponsable = u.IDUtilisateur
    WHERE 1=1
";

$params = [];

if ($search) {
    $sql .= " AND (m.Titre LIKE ? OR m.Description LIKE ? OR m.Lieu LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($statut_filter) {
    $sql .= " AND m.Statut = ?";
    $params[] = $statut_filter;
}

if ($categorie_filter) {
    $sql .= " AND m.IDCategorie = ?";
    $params[] = $categorie_filter;
}

$sql .= " ORDER BY m.DateMission DESC, m.HeureMission DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$missions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Listes pour filtres
$categories = $db->query("SELECT * FROM CATEGORIE ORDER BY NomCategorie")->fetchAll(PDO::FETCH_ASSOC);
$statuts = ['Planifiée', 'En cours', 'Terminée', 'Annulée'];

// Statistiques
$stats = [];
$stats['total'] = $db->query("SELECT COUNT(*) FROM MISSIONS")->fetchColumn();
$stats['planifiees'] = $db->query("SELECT COUNT(*) FROM MISSIONS WHERE Statut = 'Planifiée'")->fetchColumn();
$stats['en_cours'] = $db->query("SELECT COUNT(*) FROM MISSIONS WHERE Statut = 'En cours'")->fetchColumn();
$stats['a_venir'] = $db->query("SELECT COUNT(*) FROM MISSIONS WHERE DateMission >= date('now') AND Statut = 'Planifiée'")->fetchColumn();

include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Gestion des partenaires</h1>
                <a href="/admin/partenaires_add.php" class="btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un partenaire
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
                               placeholder="Titre, description, lieu...">
                    </div>

                    <div class="filter-group">
                        <label>Catégorie</label>
                        <select name="categorie">
                            <option value="">Toutes</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['IDCategorie']; ?>"
                                    <?php echo $categorie_filter == $cat['IDCategorie'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['NomCategorie']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Statut</label>
                        <select name="statut">
                            <option value="">Tous</option>
                            <?php foreach ($statuts as $s): ?>
                                <option value="<?php echo $s; ?>"
                                    <?php echo $statut_filter === $s ? 'selected' : ''; ?>>
                                    <?php echo $s; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">Filtrer</button>
                    <a href="/admin/missions.php" class="btn-secondary">Réinitialiser</a>
                </form>
            </div>

            <!-- Statistiques -->
            <div class="stats-mini">
                <div class="stat-mini">
                    <span class="stat-mini-label">Total partenaires</span>
                    <span class="stat-mini-value"><?php echo $stats['total']; ?></span>
                </div>

            </div>

            <!-- Table des partenaires -->
            <div class="content-section">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Nom partenaire</th>
                            <th>Type</th>
                            <th>Mail</th>
                            <th>Téléphone</th>
                            <th>Actif</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($partenaires)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px;">
                                    Aucun partenaire trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($partenaires as $p): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($m['Titre']); ?></strong>
                                        <?php if ($m['Description']): ?>
                                            <br><small style="color: #7f8c8d;">
                                                <?php echo htmlspecialchars(substr($m['Description'], 0, 60)); ?>...
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                    <span class="badge" style="background: <?php echo $m['Couleur']; ?>">
                                        <?php echo htmlspecialchars($m['NomCategorie']); ?>
                                    </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d/m/Y', strtotime($m['DateMission'])); ?>
                                        <br>
                                        <i class="fas fa-clock"></i>
                                        <?php echo substr($m['HeureMission'], 0, 5); ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($m['Lieu']); ?>
                                    </td>
                                    <td>
                                    <span class="badge badge-info">
                                        <?php echo $m['NbInscrits']; ?> / <?php echo $m['NbBenevolesAttendu']; ?>
                                    </span>
                                        <?php if ($m['NbInscrits'] >= $m['NbBenevolesAttendu']): ?>
                                            <i class="fas fa-check-circle" style="color: #2ecc71;"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($m['Responsable']); ?></td>
                                    <td>
                                    <span class="status-badge status-<?php echo strtolower($m['Statut']); ?>">
                                        <?php echo htmlspecialchars($m['Statut']); ?>
                                    </span>
                                    </td>
                                    <td>
                                        <a href="/admin/missions_detail.php?id=<?php echo $m['IDMission']; ?>"
                                           class="btn-icon" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/missions_edit.php?id=<?php echo $m['IDMission']; ?>"
                                           class="btn-icon" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($m['Statut'] !== 'Annulée'): ?>
                                            <form method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Annuler cette mission ?');">
                                                <input type="hidden" name="id" value="<?php echo $m['IDMission']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="btn-icon" title="Annuler">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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