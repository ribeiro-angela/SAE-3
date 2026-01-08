<?php
/**
 * SCRIPT DE MIGRATION - HACHAGE DES MOTS DE PASSE
 * À exécuter une seule fois pour mettre à jour les mots de passe existants
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== MIGRATION DES MOTS DE PASSE ===\n\n";

// Sécurité : confirmation requise
if (!isset($argv[1]) || $argv[1] !== '--confirm') {
    echo "⚠️  ATTENTION: Ce script va hacher tous les mots de passe en base !\n\n";
    echo "Pour confirmer, exécutez :\n";
    echo "php migrate_passwords.php --confirm\n\n";
    exit;
}

require_once __DIR__ . '/../admin/security.php';

try {
    $db = new PDO('sqlite:' . __DIR__ . '/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base réussie\n\n";

    // Récupérer tous les utilisateurs
    $stmt = $db->query("SELECT IDUtilisateur, Mail, MotDePasse FROM UTILISATEUR");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "📋 Nombre d'utilisateurs trouvés : " . count($users) . "\n\n";

    $updated = 0;
    $alreadyHashed = 0;
    $errors = 0;

    foreach ($users as $user) {
        try {
            // Vérifier si le mot de passe est déjà haché
            if (password_get_info($user['MotDePasse'])['algo'] !== null) {
                echo "⏭️  " . $user['Mail'] . " - Déjà haché\n";
                $alreadyHashed++;
                continue;
            }

            // Hacher le mot de passe
            $hashedPassword = Security::hashPassword($user['MotDePasse']);

            // Mettre à jour en base
            $updateStmt = $db->prepare("UPDATE UTILISATEUR SET MotDePasse = ? WHERE IDUtilisateur = ?");
            $updateStmt->execute([$hashedPassword, $user['IDUtilisateur']]);

            echo "✅ " . $user['Mail'] . " - Mot de passe haché avec succès\n";
            $updated++;

        } catch (Exception $e) {
            echo "❌ " . $user['Mail'] . " - ERREUR: " . $e->getMessage() . "\n";
            $errors++;
        }
    }

    echo "\n=== RÉSUMÉ ===\n";
    echo "✅ Mots de passe hachés : $updated\n";
    echo "⏭️  Déjà hachés : $alreadyHashed\n";
    echo "❌ Erreurs : $errors\n\n";

    if ($errors === 0) {
        echo "🎉 Migration réussie !\n";
    } else {
        echo "⚠️  Migration terminée avec des erreurs\n";
    }

} catch(PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}
?>