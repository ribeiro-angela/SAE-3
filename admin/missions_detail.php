<?php
// admin/missions_detail.php
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
    SELECT m.*, c.NomCategorie, c.Couleur, u.Nom || ' ' || u.Prenom as Responsable
    FROM MISSIONS m
    LEFT JOIN CATEGORIE c ON m.IDCategorie = c.IDCategorie
    LEFT JOIN UTILISATEUR u ON m.IDResponsable = u.IDUtilisateur
    WHERE m.IDMission = ?
");
$stmt->execute([$id]);
$mission = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mission) {
    header('Location: missions.php');
    exit;
}

$stmt = $db->prepare("
    SELECT u.Nom, u.Prenom, u.Mail, u.Telephone, pm.DateInscription, pm.Statut
    FROM PARTICIPE_MISSION pm
    JOIN UTILISATEUR u ON pm.IDUtilisateur = u.IDUtilisateur
    WHERE pm.IDMission = ?
    ORDER BY pm.DateInscription
");
$stmt->execute([$id]);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Détails de la mission</h1>
                <div style="display: flex; gap: 10px;">
                    <a href="missions_edit.php?id=<?php echo $id; ?>" class="btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="missions.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <div class="content-section">
                <div style="margin-bottom: 30px;">
                    <h2 style="margin-bottom: 15px;">
                        <?php echo htmlspecialchars($mission['Titre']); ?>
                        <span class="status-badge status-<?php echo strtolower($mission['Statut']); ?>">
                        <?php echo htmlspecialchars($mission['Statut']); ?>
                    </span>
                    </h2>
                    <span class="badge" style="background: <?php echo $mission['Couleur']; ?>">
                    <?php echo htmlspecialchars($mission['NomCategorie']); ?>
                </span>
                </div>

                <?php if ($mission['Description']): ?>
                    <div style="margin-bottom: 30px;">
                        <h3 style="font-size: 16px; margin-bottom: 10px;">Description</h3>
                        <p style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($mission['Description'])); ?></p>
                    </div>
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 30px;">
                    <div>
                        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">
                            <i class="fas fa-calendar"></i> Date
                        </p>
                        <p style="font-weight: 500;"><?php echo date('d/m/Y', strtotime($mission['DateMission'])); ?></p>
                    </div>

                    <div>
                        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">
                            <i class="fas fa-clock"></i> Heure
                        </p>
                        <p style="font-weight: 500;"><?php echo substr($mission['HeureMission'], 0, 5); ?></p>
                    </div>

                    <div>
                        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">
                            <i class="fas fa-map-marker-alt"></i> Lieu
                        </p>
                        <p style="font-weight: 500;"><?php echo htmlspecialchars($mission['Lieu']); ?></p>
                    </div>

                    <div>
                        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">
                            <i class="fas fa-user-tie"></i> Responsable
                        </p>
                        <p style="font-weight: 500;"><?php echo htmlspecialchars($mission['Responsable']); ?></p>
                    </div>

                    <div>
                        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">
                            <i class="fas fa-users"></i> Participants
                        </p>
                        <p style="font-weight: 500;">
                            <?php echo count($participants); ?> / <?php echo $mission['NbBenevolesAttendu']; ?>
                            <?php if (count($participants) >= $mission['NbBenevolesAttendu']): ?>
                                <i class="fas fa-check-circle" style="color: #2ecc71;"></i>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">
                            <i class="fas fa-calendar-plus"></i> Créée le
                        </p>
                        <p style="font-weight: 500;"><?php echo date('d/m/Y', strtotime($mission['DateCreation'])); ?></p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2>Participants inscrits (<?php echo count($participants); ?>)</h2>
                </div>

                <?php if (empty($participants)): ?>
                    <p style="text-align: center; color: #7f8c8d; padding: 40px;">
                        Aucun participant inscrit pour le moment.
                    </p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date d'inscription</th>
                                <th>Statut</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($participants as $p): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($p['Nom'] . ' ' . $p['Prenom']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($p['Mail']); ?></td>
                                    <td><?php echo htmlspecialchars($p['Telephone'] ?? '-'); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($p['DateInscription'])); ?></td>
                                    <td>
                                        <span class="badge badge-success"><?php echo htmlspecialchars($p['Statut']); ?></span>
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