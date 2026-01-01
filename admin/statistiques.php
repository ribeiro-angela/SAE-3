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

// STATISTIQUES BÉNÉVOLES
$stats_benevoles = [];
$stats_benevoles['total'] = $db->query("SELECT COUNT(*) FROM BENEVOLES WHERE Actif = 1")->fetchColumn();
$stats_benevoles['nouveaux_mois'] = $db->query("
    SELECT COUNT(*) FROM BENEVOLES 
    WHERE DATE(DateCreationBenevole) >= DATE('now', '-1 month') AND Actif = 1
")->fetchColumn();

// Répartition par âge
$ages = $db->query("
    SELECT 
        CASE 
            WHEN (julianday('now') - julianday(DateNaissance))/365.25 < 25 THEN '< 25 ans'
            WHEN (julianday('now') - julianday(DateNaissance))/365.25 < 35 THEN '25-34 ans'
            WHEN (julianday('now') - julianday(DateNaissance))/365.25 < 50 THEN '35-49 ans'
            WHEN (julianday('now') - julianday(DateNaissance))/365.25 < 65 THEN '50-64 ans'
            ELSE '65+ ans'
        END as Tranche,
        COUNT(*) as Nombre
    FROM BENEVOLES
    WHERE Actif = 1
    GROUP BY Tranche
")->fetchAll(PDO::FETCH_ASSOC);

// Répartition par ville
$villes = $db->query("
    SELECT v.NomVille, COUNT(b.IDUtilisateur) as Nombre
    FROM VILLE v
    LEFT JOIN BENEVOLES b ON v.IDVille = b.IDVille AND b.Actif = 1
    GROUP BY v.IDVille, v.NomVille
    ORDER BY Nombre DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Répartition par compétence
$competences = $db->query("
    SELECT c.NomCompetence, COUNT(b.IDUtilisateur) as Nombre
    FROM COMPETENCE c
    LEFT JOIN BENEVOLES b ON c.IDCompetence = b.IDCompetence AND b.Actif = 1
    GROUP BY c.IDCompetence, c.NomCompetence
    ORDER BY Nombre DESC
")->fetchAll(PDO::FETCH_ASSOC);

// STATISTIQUES MISSIONS
$stats_missions = [];
$stats_missions['total'] = $db->query("SELECT COUNT(*) FROM MISSIONS")->fetchColumn();
$stats_missions['planifiees'] = $db->query("SELECT COUNT(*) FROM MISSIONS WHERE Statut = 'Planifiée'")->fetchColumn();
$stats_missions['terminees'] = $db->query("SELECT COUNT(*) FROM MISSIONS WHERE Statut = 'Terminée'")->fetchColumn();

// Participation aux missions
$participations = $db->query("
    SELECT 
        u.Nom || ' ' || u.Prenom as Benevole,
        COUNT(pm.IDMission) as NbMissions
    FROM PARTICIPE_MISSION pm
    JOIN UTILISATEUR u ON pm.IDUtilisateur = u.IDUtilisateur
    GROUP BY pm.IDUtilisateur
    ORDER BY NbMissions DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Missions par catégorie
$missions_categorie = $db->query("
    SELECT c.NomCategorie, c.Couleur, COUNT(m.IDMission) as Nombre
    FROM CATEGORIE c
    LEFT JOIN MISSIONS m ON c.IDCategorie = m.IDCategorie
    GROUP BY c.IDCategorie, c.NomCategorie, c.Couleur
    ORDER BY Nombre DESC
")->fetchAll(PDO::FETCH_ASSOC);

// STATISTIQUES FINANCIÈRES
$dons_total = $db->query("SELECT COALESCE(SUM(Montant), 0) FROM DON")->fetchColumn();
$dons_annee = $db->query("
    SELECT COALESCE(SUM(Montant), 0) FROM DON 
    WHERE strftime('%Y', DateDon) = strftime('%Y', 'now')
")->fetchColumn();
$nb_donateurs = $db->query("SELECT COUNT(*) FROM DONATEUR WHERE Actif = 1")->fetchColumn();

include __DIR__ . '/components/admin_header.php';
?>

    <div class="admin-container">
        <?php include __DIR__ . '/components/admin_sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h1>Statistiques et Tableaux de Bord</h1>
                <div style="display: flex; gap: 10px;">
                    <button class="btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                    <button class="btn-secondary" onclick="exportPDF()">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                </div>
            </div>

            <!-- OVERVIEW CARDS -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $stats_benevoles['total']; ?></h3>
                        <p>Bénévoles actifs</p>
                        <small style="color: #2ecc71;">
                            <i class="fas fa-arrow-up"></i>
                            +<?php echo $stats_benevoles['nouveaux_mois']; ?> ce mois
                        </small>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $stats_missions['total']; ?></h3>
                        <p>Missions total</p>
                        <small><?php echo $stats_missions['planifiees']; ?> planifiées</small>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo number_format($dons_annee, 0, ',', ' '); ?> €</h3>
                        <p>Dons cette année</p>
                        <small><?php echo $nb_donateurs; ?> donateurs</small>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $stats_missions['terminees']; ?></h3>
                        <p>Missions terminées</p>
                        <small>Taux de réussite élevé</small>
                    </div>
                </div>
            </div>

            <!-- BÉNÉVOLES -->
            <div class="content-section">
                <h2 style="margin-bottom: 30px;">
                    <i class="fas fa-users"></i> Statistiques Bénévoles
                </h2>

                <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
                    <!-- Répartition par âge -->
                    <div>
                        <h3 style="font-size: 18px; margin-bottom: 15px;">Répartition par âge</h3>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                            <?php foreach ($ages as $age): ?>
                                <?php $percentage = ($stats_benevoles['total'] > 0) ? ($age['Nombre'] / $stats_benevoles['total'] * 100) : 0; ?>
                                <div style="margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span><?php echo $age['Tranche']; ?></span>
                                        <strong><?php echo $age['Nombre']; ?></strong>
                                    </div>
                                    <div style="background: #e9ecef; border-radius: 10px; overflow: hidden;">
                                        <div style="background: var(--admin-primary); height: 8px; width: <?php echo $percentage; ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Top villes -->
                    <div>
                        <h3 style="font-size: 18px; margin-bottom: 15px;">Top 10 villes</h3>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                <tr>
                                    <th>Ville</th>
                                    <th>Nombre</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($villes as $v): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($v['NomVille']); ?></td>
                                        <td><span class="badge badge-info"><?php echo $v['Nombre']; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COMPÉTENCES -->
            <div class="content-section">
                <h2 style="margin-bottom: 20px;">
                    <i class="fas fa-star"></i> Compétences disponibles
                </h2>
                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <?php foreach ($competences as $comp): ?>
                        <div style="background: #f8f9fa; padding: 15px 25px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: 700; color: var(--admin-primary);">
                                <?php echo $comp['Nombre']; ?>
                            </div>
                            <div style="font-size: 14px; color: #7f8c8d;">
                                <?php echo htmlspecialchars($comp['NomCompetence']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MISSIONS -->
            <div class="content-section">
                <h2 style="margin-bottom: 30px;">
                    <i class="fas fa-chart-bar"></i> Statistiques Missions
                </h2>

                <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
                    <!-- Missions par catégorie -->
                    <div>
                        <h3 style="font-size: 18px; margin-bottom: 15px;">Missions par catégorie</h3>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                            <?php foreach ($missions_categorie as $mc): ?>
                                <?php $percentage = ($stats_missions['total'] > 0) ? ($mc['Nombre'] / $stats_missions['total'] * 100) : 0; ?>
                                <div style="margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span><?php echo htmlspecialchars($mc['NomCategorie']); ?></span>
                                        <strong><?php echo $mc['Nombre']; ?></strong>
                                    </div>
                                    <div style="background: #e9ecef; border-radius: 10px; overflow: hidden;">
                                        <div style="background: <?php echo $mc['Couleur']; ?>; height: 8px; width: <?php echo $percentage; ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Top participants -->
                    <div>
                        <h3 style="font-size: 18px; margin-bottom: 15px;">Top 10 participants</h3>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                <tr>
                                    <th>Bénévole</th>
                                    <th>Missions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($participations as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['Benevole']); ?></td>
                                        <td><span class="badge badge-success"><?php echo $p['NbMissions']; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FINANCIER -->
            <div class="content-section">
                <h2 style="margin-bottom: 20px;">
                    <i class="fas fa-euro-sign"></i> Vue financière
                </h2>
                <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                    <div style="background: #f8f9fa; padding: 25px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 32px; font-weight: 700; color: var(--admin-success);">
                            <?php echo number_format($dons_total, 0, ',', ' '); ?> €
                        </div>
                        <div style="font-size: 14px; color: #7f8c8d; margin-top: 8px;">
                            Total des dons
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 25px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 32px; font-weight: 700; color: var(--admin-info);">
                            <?php echo number_format($dons_annee, 0, ',', ' '); ?> €
                        </div>
                        <div style="font-size: 14px; color: #7f8c8d; margin-top: 8px;">
                            Dons cette année
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 25px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 32px; font-weight: 700; color: var(--admin-warning);">
                            <?php echo $nb_donateurs; ?>
                        </div>
                        <div style="font-size: 14px; color: #7f8c8d; margin-top: 8px;">
                            Donateurs actifs
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportPDF() {
            window.print();
        }
    </script>

    <style>
        @media print {
            .admin-sidebar,
            .content-header button,
            .nav-item {
                display: none !important;
            }
            .admin-content {
                margin-left: 0 !important;
            }
        }
    </style>

<?php include __DIR__ . '/components/admin_footer.php'; ?>