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

// Filtres
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';

$sql = "
    SELECT 
        e.*,
        u.Nom || ' ' || u.Prenom as Organisateur,
        (SELECT COUNT(*) FROM PARTICIPE_EVENEMENT pe WHERE pe.IDEvenement = e.IDEvenement) as NbParticipants
    FROM EVENEMENT e
    LEFT JOIN UTILISATEUR u ON e.IDOrganisateur = u.IDUtilisateur
    WHERE 1=1
";

$params = [];

if ($search) {
    $sql .= " AND (e.NomEvenement LIKE ? OR e.Description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($type_filter) {
    $sql .= " AND e.TypeEvenement = ?";
    $params[] = $type_filter;
}

$sql .= " ORDER BY e.DateEvenement DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);

$types = $db->query("SELECT DISTINCT TypeEvenement FROM EVENEMENT")->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Gestion des événements</h1>
                <a href="/admin/evenements_add.php" class="btn-primary">
                    <i class="fas fa-calendar-plus"></i> Créer un événement
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
                               placeholder="Nom, description...">
                    </div>

                    <div class="filter-group">
                        <label>Type d'événement</label>
                        <select name="type">
                            <option value="">Tous</option>
                            <?php foreach ($types as $t): ?>
                                <option value="<?php echo htmlspecialchars($t); ?>"
                                    <?php echo $type_filter === $t ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">Filtrer</button>
                    <a href="/admin/evenements.php" class="btn-secondary">Réinitialiser</a>
                </form>
            </div>

            <!-- Liste des événements -->
            <div class="content-section">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Événement</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Participants</th>
                            <th>Organisateur</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($evenements)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px;">
                                    Aucun événement trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($evenements as $e): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($e['NomEvenement']); ?></strong>
                                        <?php if ($e['Description']): ?>
                                            <br><small style="color: #7f8c8d;">
                                                <?php echo htmlspecialchars(substr($e['Description'], 0, 50)); ?>...
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge"><?php echo htmlspecialchars($e['TypeEvenement']); ?></span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d/m/Y', strtotime($e['DateEvenement'])); ?>
                                        <?php if ($e['HeureEvenement']): ?>
                                            <br><i class="fas fa-clock"></i>
                                            <?php echo substr($e['HeureEvenement'], 0, 5); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo $e['Lieu'] ? htmlspecialchars($e['Lieu']) : '-'; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-info"><?php echo $e['NbParticipants']; ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($e['Organisateur']); ?></td>
                                    <td>
                                    <span class="status-badge status-<?php echo strtolower($e['Statut']); ?>">
                                        <?php echo htmlspecialchars($e['Statut']); ?>
                                    </span>
                                    </td>
                                    <td>
                                        <a href="/admin/evenements_detail.php?id=<?php echo $e['IDEvenement']; ?>"
                                           class="btn-icon" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/evenements_edit.php?id=<?php echo $e['IDEvenement']; ?>"
                                           class="btn-icon" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
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
        .status-planifié {
            background: rgba(52, 152, 219, 0.1);
            color: var(--admin-info);
        }
    </style>

<?php include __DIR__ . '/components/admin_footer.php'; ?>