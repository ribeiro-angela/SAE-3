<?php
// admin/donateurs.php
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

$search = $_GET['search'] ?? '';
$actif_filter = $_GET['actif'] ?? '1';

$sql = "
    SELECT 
        d.*,
        COUNT(don.IDDon) as NbDons,
        SUM(don.Montant) as TotalDons,
        MAX(don.DateDon) as DernierDon
    FROM DONATEUR d
    LEFT JOIN DON don ON d.IDDonateur = don.IDDonateur
    WHERE 1=1
";

$params = [];

if ($search) {
    $sql .= " AND (d.Nom LIKE ? OR d.Prenom LIKE ? OR d.Mail LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($actif_filter !== '') {
    $sql .= " AND d.Actif = ?";
    $params[] = $actif_filter;
}

$sql .= " GROUP BY d.IDDonateur ORDER BY TotalDons DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$donateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->query("SELECT SUM(Montant) as total FROM DON");
$totalDons = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$stmt = $db->query("SELECT COUNT(*) as total FROM DONATEUR WHERE Actif = 1");
$totalDonateurs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Gestion des donateurs</h1>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="filters-section">
                <form method="GET" class="filters-form">
                    <div class="filter-group">
                        <label>Rechercher</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                               placeholder="Nom, prénom, email...">
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
                    <a href="donateurs.php" class="btn-secondary">Réinitialiser</a>
                </form>
            </div>

            <div class="stats-mini">
                <div class="stat-mini">
                    <span class="stat-mini-label">Total donateurs</span>
                    <span class="stat-mini-value"><?php echo $totalDonateurs; ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Donateurs affichés</span>
                    <span class="stat-mini-value"><?php echo count($donateurs); ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Total des dons</span>
                    <span class="stat-mini-value"><?php echo number_format($totalDons, 0, ',', ' '); ?> €</span>
                </div>
            </div>

            <div class="content-section">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Nombre de dons</th>
                            <th>Total donné</th>
                            <th>Dernier don</th>
                            <th>Statut</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($donateurs as $d): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($d['Nom'] . ' ' . ($d['Prenom'] ?? '')); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($d['Mail'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($d['Telephone'] ?? '-'); ?></td>
                                <td><span class="badge badge-info"><?php echo $d['NbDons'] ?? 0; ?></span></td>
                                <td><strong><?php echo number_format($d['TotalDons'] ?? 0, 2, ',', ' '); ?> €</strong></td>
                                <td>
                                    <?php echo $d['DernierDon'] ? date('d/m/Y', strtotime($d['DernierDon'])) : '-'; ?>
                                </td>
                                <td>
                                    <?php if ($d['Actif']): ?>
                                        <span class="status-badge status-active">Actif</span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">Inactif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/components/admin_footer.php'; ?>