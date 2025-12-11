<?php
/**
 * Liste des bénévoles
 */

require_once '../config/config.php';
require_once '../includes/header.php';

$db = Database::getInstance()->getConnection();

// Gestion de la recherche et des filtres
$search = $_GET['search'] ?? '';
$filtre_actif = $_GET['actif'] ?? 'all';
$filtre_ville = $_GET['ville'] ?? 'all';

// Construction de la requête
$sql = "
    SELECT 
        u.IDUtilisateur, u.Nom, u.Prenom, u.Mail, u.Telephone,
        b.DateCreationBenevole, b.DateNaissance, b.Profession, b.Actif, b.Permis,
        v.NomVille, v.CodePostal,
        r.NomRegime,
        h.NomHandicap,
        c.NomCompetence,
        (SELECT COUNT(*) FROM ParticipeMission pm WHERE pm.IDUtilisateur = b.IDUtilisateur) as NbMissions
    FROM BENEVOLES b
    JOIN UTILISATEUR u ON b.IDUtilisateur = u.IDUtilisateur
    LEFT JOIN VILLE v ON b.IDVille = v.IDVille
    LEFT JOIN REGIME_ALIMENTAIRE r ON b.IDRegime = r.IDRegime
    LEFT JOIN HANDICAP h ON b.IDHandicap = h.IDHandicap
    LEFT JOIN COMPETENCE c ON b.IDCompetence = c.IDCompetence
    WHERE 1=1
";

$params = [];

// Filtre de recherche
if (!empty($search)) {
    $sql .= " AND (u.Nom LIKE ? OR u.Prenom LIKE ? OR u.Mail LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

// Filtre actif/inactif
if ($filtre_actif !== 'all') {
    $sql .= " AND b.Actif = ?";
    $params[] = ($filtre_actif === 'actif') ? 1 : 0;
}

// Filtre par ville
if ($filtre_ville !== 'all') {
    $sql .= " AND b.IDVille = ?";
    $params[] = $filtre_ville;
}

$sql .= " ORDER BY b.DateCreationBenevole DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$benevoles = $stmt->fetchAll();

// Liste des villes pour le filtre
$villes = $db->query("SELECT IDVille, NomVille FROM VILLE ORDER BY NomVille")->fetchAll();

// Statistiques
$nbTotal = count($benevoles);
$nbActifs = count(array_filter($benevoles, fn($b) => $b['Actif']));
$nbInactifs = $nbTotal - $nbActifs;
?>

