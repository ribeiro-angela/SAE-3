<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== SUPPRESSION DE TOUTES LES TABLES ===\n\n";

// Sécurité : confirmation requise
if (!isset($argv[1]) || $argv[1] !== '--confirm') {
    echo "⚠️  ATTENTION: Cette commande va supprimer TOUTES les données !\n\n";
    echo "Pour confirmer, exécutez :\n";
    echo "php drop_tables.php --confirm\n\n";
    exit;
}

try {
    $db = new PDO('sqlite:' . __DIR__ . '/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base réussie\n\n";

    // Récupération de toutes les tables (sauf les tables système SQLite)
    $tables = $db->query("
        SELECT name 
        FROM sqlite_master 
        WHERE type='table' 
        AND name NOT LIKE 'sqlite_%'
    ")->fetchAll(PDO::FETCH_COLUMN);

    echo "Tables trouvées : " . count($tables) . "\n\n";

    // Désactivation des contraintes de clés étrangères
    $db->exec("PRAGMA foreign_keys = OFF");

    // Suppression de chaque table
    foreach ($tables as $table) {
        echo "🗑️  Suppression de la table : $table\n";
        $db->exec("DROP TABLE IF EXISTS `$table`");
    }

    // Réactivation des contraintes
    $db->exec("PRAGMA foreign_keys = ON");

    echo "\n✅ Toutes les tables ont été supprimées avec succès\n";
    echo "\n💡 Vous pouvez maintenant relancer init_db.php pour recréer la structure\n";

} catch(PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}
?>