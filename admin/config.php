<?php
// admin/config.php
// Fichier de configuration centralisé pour la base de données

// Chemin vers la base de données
define('DB_PATH', __DIR__ . '/../database/arme_du_salut.db');

// Fonction pour obtenir une connexion à la base
function getDatabase() {
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    } catch(PDOException $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}

// Fonction pour vérifier l'authentification
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}
?>