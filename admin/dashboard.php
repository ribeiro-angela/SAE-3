<?php
session_start();

// Vérification de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

// Connexion à la base de données
try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Récupération des statistiques
$stats = [];

// Nombre total de bénévoles
$stmt = $db->query("SELECT COUNT(*) as total FROM BENEVOLES WHERE Actif = 1");
$stats['benevoles_actifs'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre de missions planifiées
$stmt = $db->query("SELECT COUNT(*) as total FROM MISSIONS WHERE Statut = 'Planifiée'");
$stats['missions_planifiees'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'événements à venir
$stmt = $db->query("SELECT COUNT(*) as total FROM EVENEMENT WHERE DateEvenement >= date('now')");
$stats['evenements_avenir'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre de partenaires actifs
$stmt = $db->query("SELECT COUNT(*) as total FROM PARTENAIRE WHERE Actif = 1");
$stats['partenaires_actifs'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Missions récentes
$stmt = $db->query("
    SELECT m.*, c.NomCategorie, u.Nom || ' ' || u.Prenom as Responsable
    FROM MISSIONS m
    LEFT JOIN CATEGORIE c ON m.IDCategorie = c.IDCategorie
    LEFT JOIN UTILISATEUR u ON m.IDResponsable = u.IDUtilisateur
    ORDER BY m.DateCreation DESC
    LIMIT 5
");
$missions_recentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../components/admin_header.php';
?>

    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <img src="/assets/image/logo.png" alt="Logo" class="sidebar-logo">
                <h3>Administration</h3>
            </div>

            <nav class="sidebar-nav">
                <a href="/admin/dashboard.php" class="nav-item active">
                    <i class="fas fa-chart-line"></i> Tableau de bord
                </a>
                <a href="/admin/benevoles.php" class="nav-item">
                    <i class="fas fa-users"></i> Bénévoles
                </a>
                <a href="/admin/missions.php" class="nav-item">
                    <i class="fas fa-tasks"></i> Missions
                </a>
                <a href="/admin/evenements.php" class="nav-item">
                    <i class="fas fa-calendar"></i> Événements
                </a>
                <a href="/admin/partenaires.php" class="nav-item">
                    <i class="fas fa-handshake"></i> Partenaires
                </a>
                <a href="/admin/donateurs.php" class="nav-item">
                    <i class="fas fa-heart"></i> Donateurs
                </a>
                <a href="/admin/statistiques.php" class="nav-item">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </a>
                <a href="/admin/contenus.php" class="nav-item">
                    <i class="fas fa-edit"></i> Contenus
                </a>
                <a href="/admin/logout.php" class="nav-item logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </nav>
        </div>

        <div class="admin-content">
            <div class="content-header">
                <h1>Tableau de bord</h1>
                <div class="user-info">
                    <span>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                </div>
            </div>

            <!-- Cartes de statistiques -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $stats['benevoles_actifs']; ?></h3>
                        <p>Bénévoles actifs</p>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $stats['missions_planifiees']; ?></h3>
                        <p>Missions planifiées</p>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $stats['evenements_avenir']; ?></h3>
                        <p>Événements à venir</p>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="stat-details">
                        <h3><?php echo $stats['partenaires_actifs']; ?></h3>
                        <p>Partenaires actifs</p>
                    </div>
                </div>
            </div>

            <!-- Missions récentes -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Missions récentes</h2>
                    <a href="/admin/missions.php" class="btn-primary">Voir tout</a>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Responsable</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($missions_recentes as $mission): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($mission['Titre']); ?></td>
                                <td>
                                    <span class="badge"><?php echo htmlspecialchars($mission['NomCategorie']); ?></span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($mission['DateMission'])); ?></td>
                                <td><?php echo htmlspecialchars($mission['Responsable']); ?></td>
                                <td>
                                <span class="status-badge status-<?php echo strtolower($mission['Statut']); ?>">
                                    <?php echo htmlspecialchars($mission['Statut']); ?>
                                </span>
                                </td>
                                <td>
                                    <a href="/admin/missions_detail.php?id=<?php echo $mission['IDMission']; ?>" class="btn-icon" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/missions_edit.php?id=<?php echo $mission['IDMission']; ?>" class="btn-icon" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="quick-actions">
                <h2>Actions rapides</h2>
                <div class="actions-grid">
                    <a href="/admin/benevoles_add.php" class="action-card">
                        <i class="fas fa-user-plus"></i>
                        <span>Ajouter un bénévole</span>
                    </a>
                    <a href="/admin/missions_add.php" class="action-card">
                        <i class="fas fa-plus-circle"></i>
                        <span>Créer une mission</span>
                    </a>
                    <a href="/admin/evenements_add.php" class="action-card">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Organiser un événement</span>
                    </a>
                    <a href="/admin/contenus_add.php" class="action-card">
                        <i class="fas fa-newspaper"></i>
                        <span>Publier une actualité</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --admin-primary: #c23331;
            --admin-secondary: #2c3e50;
            --admin-success: #2ecc71;
            --admin-warning: #f39c12;
            --admin-info: #3498db;
            --admin-bg: #f5f6fa;
            --admin-sidebar: #212f3c;
            --admin-text: #2c3e50;
            --admin-border: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: var(--admin-sidebar);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .sidebar-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            gap: 12px;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid var(--admin-primary);
        }

        .nav-item.logout {
            margin-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .nav-item i {
            width: 20px;
        }

        /* Content */
        .admin-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .content-header h1 {
            font-size: 28px;
            color: var(--admin-text);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            background: white;
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-primary .stat-icon {
            background: rgba(194, 51, 49, 0.1);
            color: var(--admin-primary);
        }

        .stat-success .stat-icon {
            background: rgba(46, 204, 113, 0.1);
            color: var(--admin-success);
        }

        .stat-warning .stat-icon {
            background: rgba(243, 156, 18, 0.1);
            color: var(--admin-warning);
        }

        .stat-info .stat-icon {
            background: rgba(52, 152, 219, 0.1);
            color: var(--admin-info);
        }

        .stat-details h3 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-details p {
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Content Section */
        .content-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 20px;
            color: var(--admin-text);
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table thead {
            background: var(--admin-bg);
        }

        .admin-table th,
        .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--admin-border);
        }

        .admin-table th {
            font-weight: 600;
            color: var(--admin-text);
            font-size: 14px;
            text-transform: uppercase;
        }

        .admin-table tbody tr:hover {
            background: rgba(0,0,0,0.02);
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            background: var(--admin-info);
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-planifiée {
            background: rgba(52, 152, 219, 0.1);
            color: var(--admin-info);
        }

        .status-terminée {
            background: rgba(46, 204, 113, 0.1);
            color: var(--admin-success);
        }

        .status-annulée {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        /* Buttons */
        .btn-primary,
        .btn-success,
        .btn-danger,
        .btn-secondary {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--admin-primary);
            color: white;
        }

        .btn-primary:hover {
            background: #a02826;
        }

        .btn-success {
            background: var(--admin-success);
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 6px;
            background: var(--admin-bg);
            color: var(--admin-text);
            text-decoration: none;
            margin: 0 3px;
            transition: all 0.3s;
        }

        .btn-icon:hover {
            background: var(--admin-primary);
            color: white;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .quick-actions h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            background: var(--admin-bg);
            border-radius: 12px;
            text-decoration: none;
            color: var(--admin-text);
            transition: all 0.3s;
            gap: 10px;
        }

        .action-card:hover {
            background: var(--admin-primary);
            color: white;
            transform: translateY(-5px);
        }

        .action-card i {
            font-size: 32px;
        }

        .action-card span {
            font-weight: 500;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                width: 80px;
            }

            .sidebar-header h3,
            .nav-item span {
                display: none;
            }

            .nav-item {
                justify-content: center;
            }

            .admin-content {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

<?php include __DIR__ . '/../components/admin_footer.php'; ?>