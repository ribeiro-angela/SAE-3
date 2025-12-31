<?php
// admin/benevoles_detail.php
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
    SELECT 
        b.*, 
        u.Nom, u.Prenom, u.Mail, u.Telephone, u.DateCreation,
        v.NomVille, v.CodePostal,
        r.NomRegime,
        h.NomHandicap,
        c.NomCompetence
    FROM BENEVOLES b
    JOIN UTILISATEUR u ON b.IDUtilisateur = u.IDUtilisateur
    LEFT JOIN VILLE v ON b.IDVille = v.IDVille
    LEFT JOIN REGIME_ALIMENTAIRE r ON b.IDRegime = r.IDRegime
    LEFT JOIN HANDICAP h ON b.IDHandicap = h.IDHandicap
    LEFT JOIN COMPETENCE c ON b.IDCompetence = c.IDCompetence
    WHERE b.IDUtilisateur = ?
");
$stmt->execute([$id]);
$benevole = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$benevole) {
    header('Location: benevoles.php');
    exit;
}

$stmt = $db->prepare("
    SELECT m.*, c.NomCategorie
    FROM PARTICIPE_MISSION pm
    JOIN MISSIONS m ON pm.IDMission = m.IDMission
    LEFT JOIN CATEGORIE c ON m.IDCategorie = c.IDCategorie
    WHERE pm.IDUtilisateur = ?
    ORDER BY m.DateMission DESC
    LIMIT 10
");
$stmt->execute([$id]);
$missions = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Détails du bénévole</h1>
                <div style="display: flex; gap: 10px;">
                    <a href="benevoles_edit.php?id=<?php echo $id; ?>" class="btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="benevoles.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <div class="content-section">
                <div class="form-row">
                    <div style="flex: 1;">
                        <h2 style="margin-bottom: 30px;">
                            <?php echo htmlspecialchars($benevole['Prenom'] . ' ' . $benevole['Nom']); ?>
                            <?php if ($benevole['Actif']): ?>
                                <span class="status-badge status-active">Actif</span>
                            <?php else: ?>
                                <span class="status-badge status-inactive">Inactif</span>
                            <?php endif; ?>
                        </h2>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Email</p>
                                <p style="font-weight: 500;"><?php echo htmlspecialchars($benevole['Mail']); ?></p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Téléphone</p>
                                <p style="font-weight: 500;"><?php echo htmlspecialchars($benevole['Telephone'] ?? '-'); ?></p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Date de naissance</p>
                                <p style="font-weight: 500;">
                                    <?php
                                    echo date('d/m/Y', strtotime($benevole['DateNaissance']));
                                    $age = date_diff(date_create($benevole['DateNaissance']), date_create('today'))->y;
                                    echo " ($age ans)";
                                    ?>
                                </p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Profession</p>
                                <p style="font-weight: 500;"><?php echo htmlspecialchars($benevole['Profession'] ?? '-'); ?></p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Ville</p>
                                <p style="font-weight: 500;">
                                    <?php echo $benevole['NomVille'] ? htmlspecialchars($benevole['NomVille'] . ' (' . $benevole['CodePostal'] . ')') : '-'; ?>
                                </p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Compétence</p>
                                <p style="font-weight: 500;"><?php echo htmlspecialchars($benevole['NomCompetence'] ?? '-'); ?></p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Régime alimentaire</p>
                                <p style="font-weight: 500;"><?php echo htmlspecialchars($benevole['NomRegime'] ?? '-'); ?></p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Handicap / Limitation</p>
                                <p style="font-weight: 500;"><?php echo htmlspecialchars($benevole['NomHandicap'] ?? '-'); ?></p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Permis de conduire</p>
                                <p style="font-weight: 500;">
                                    <?php if ($benevole['Permis']): ?>
                                        <i class="fas fa-check-circle" style="color: #2ecc71;"></i> Oui
                                    <?php else: ?>
                                        <i class="fas fa-times-circle" style="color: #e74c3c;"></i> Non
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div>
                                <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Inscrit depuis</p>
                                <p style="font-weight: 500;"><?php echo date('d/m/Y', strtotime($benevole['DateCreationBenevole'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2>Historique des missions (<?php echo count($missions); ?>)</h2>
                </div>

                <?php if (empty($missions)): ?>
                    <p style="text-align: center; color: #7f8c8d; padding: 40px;">
                        Ce bénévole n'a participé à aucune mission pour le moment.
                    </p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                            <tr>
                                <th>Mission</th>
                                <th>Catégorie</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($missions as $m): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($m['Titre']); ?></strong></td>
                                    <td><span class="badge"><?php echo htmlspecialchars($m['NomCategorie']); ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($m['DateMission'])); ?></td>
                                    <td>
                                <span class="status-badge status-<?php echo strtolower($m['Statut']); ?>">
                                    <?php echo htmlspecialchars($m['Statut']); ?>
                                </span>
                                    </td>
                                    <td>
                                        <a href="missions_detail.php?id=<?php echo $m['IDMission']; ?>" class="btn-icon" title="Voir">
                                            <i class="fas fa-eye"></i>
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

<?php include __DIR__ . '/../components/admin_footer.php'; ?>