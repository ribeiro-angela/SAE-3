<?php
// admin/partenaires_detail.php
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

$stmt = $db->prepare("
    SELECT *
    FROM PARTENAIRE
    WHERE IDPartenaire = ?
");
$stmt->execute([$id]);
$partenaire = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$partenaire) {
    header('Location: partenaires.php');
    exit;
}

// Récupération des subventions liées au partenaire
$stmt = $db->prepare("
    SELECT *
    FROM SUBVENTION
    WHERE IDPartenaire = ?
    ORDER BY Annee DESC
");
$stmt->execute([$id]);
$subventions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du total des subventions
$totalSubventions = array_sum(array_column($subventions, 'Montant'));
$subventionsAcceptees = array_sum(array_filter(array_map(function($s) {
    return $s['Statut'] === 'Acceptée' ? $s['Montant'] : 0;
}, $subventions)));

include __DIR__ . '/components/admin_header.php';
?>

<div class="admin-container">
    <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

    <div class="admin-content">
        <div class="content-header">
            <h1>Détails du partenaire</h1>
            <div style="display: flex; gap: 10px;">
                <a href="partenaires_add.php?id=<?php echo $id; ?>" class="btn-primary">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="partenaires.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="content-section">
            <div class="form-row">
                <div style="flex: 1;">
                    <h2 style="margin-bottom: 30px;">
                        <?php echo htmlspecialchars($partenaire['NomPartenaire']); ?>
                        <?php if ($partenaire['Actif']): ?>
                            <span class="status-badge status-active">Actif</span>
                        <?php else: ?>
                            <span class="status-badge status-inactive">Inactif</span>
                        <?php endif; ?>
                    </h2>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div>
                            <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Type de partenaire</p>
                            <p style="font-weight: 500;">
                                <span class="badge badge-type"><?php echo htmlspecialchars($partenaire['TypePartenaire']); ?></span>
                            </p>
                        </div>

                        <div>
                            <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Email</p>
                            <p style="font-weight: 500;">
                                <?php if ($partenaire['Mail']): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($partenaire['Mail']); ?>">
                                        <?php echo htmlspecialchars($partenaire['Mail']); ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </p>
                        </div>

                        <div>
                            <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Téléphone</p>
                            <p style="font-weight: 500;">
                                <?php if ($partenaire['Telephone']): ?>
                                    <a href="tel:<?php echo htmlspecialchars($partenaire['Telephone']); ?>">
                                        <?php echo htmlspecialchars($partenaire['Telephone']); ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </p>
                        </div>

                        <div>
                            <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Partenaire depuis</p>
                            <p style="font-weight: 500;"><?php echo date('d/m/Y', strtotime($partenaire['DateCreation'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques des subventions -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(52, 152, 219, 0.1); color: #3498db;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Total subventions</p>
                    <p class="stat-value"><?php echo count($subventions); ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Montant total demandé</p>
                    <p class="stat-value"><?php echo number_format($totalSubventions, 0, ',', ' '); ?> €</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(46, 204, 113, 0.1); color: #27ae60;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Montant accepté</p>
                    <p class="stat-value"><?php echo number_format($subventionsAcceptees, 0, ',', ' '); ?> €</p>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2>Subventions (<?php echo count($subventions); ?>)</h2>
                <a href="subventions_add.php?partenaire=<?php echo $id; ?>" class="btn-primary">
                    <i class="fas fa-plus"></i> Ajouter une subvention
                </a>
            </div>

            <?php if (empty($subventions)): ?>
                <p style="text-align: center; color: #7f8c8d; padding: 40px;">
                    Aucune subvention enregistrée pour ce partenaire.
                </p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Organisme financeur</th>
                            <th>Année</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($subventions as $s): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($s['OrganismeFinanceur']); ?></strong></td>
                                <td><?php echo htmlspecialchars($s['Annee']); ?></td>
                                <td><strong><?php echo number_format($s['Montant'], 0, ',', ' '); ?> €</strong></td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    switch($s['Statut']) {
                                        case 'Acceptée':
                                            $statusClass = 'status-active';
                                            break;
                                        case 'Demandée':
                                            $statusClass = 'status-pending';
                                            break;
                                        case 'Refusée':
                                            $statusClass = 'status-inactive';
                                            break;
                                        default:
                                            $statusClass = 'status-pending';
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($s['Statut']); ?>
                                        </span>
                                </td>
                                <td>
                                    <a href="subventions_detail.php?id=<?php echo $s['IDSubvention']; ?>" class="btn-icon" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="subventions_edit.php?id=<?php echo $s['IDSubvention']; ?>" class="btn-icon" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>