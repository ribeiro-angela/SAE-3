<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "MAJVR DE LA BASE DE DONNÉES \n\n";

try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base réussie\n\n";

    // CRÉATION DE LA TABLE COTISATION (manquante)
    echo "Création de la table COTISATION...\n";

    $db->exec("
        CREATE TABLE IF NOT EXISTS COTISATION (
            IDCotisation INTEGER PRIMARY KEY AUTOINCREMENT,
            MontantCotisation REAL NOT NULL,
            DateCotisation DATE NOT NULL,
            AnneeValidite INTEGER NOT NULL,
            Statut TEXT DEFAULT 'Payée',
            IDUtilisateur INTEGER NOT NULL,
            FOREIGN KEY (IDUtilisateur) REFERENCES BENEVOLES(IDUtilisateur) ON DELETE CASCADE
        );
    ");

    echo "✅ Table COTISATION créée\n\n";

    // Vérification et ajout des champs manquants dans PARTENAIRE
    echo "Vérification de la table PARTENAIRE...\n";

    $columns = $db->query("PRAGMA table_info(PARTENAIRE)")->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'name');

    if (!in_array('Adresse', $columnNames)) {
        $db->exec("ALTER TABLE PARTENAIRE ADD COLUMN Adresse TEXT");
        echo "  ✅ Colonne 'Adresse' ajoutée\n";
    }

    if (!in_array('Ville', $columnNames)) {
        $db->exec("ALTER TABLE PARTENAIRE ADD COLUMN Ville TEXT");
        echo "  ✅ Colonne 'Ville' ajoutée\n";
    }

    if (!in_array('CodePostal', $columnNames)) {
        $db->exec("ALTER TABLE PARTENAIRE ADD COLUMN CodePostal TEXT");
        echo "  ✅ Colonne 'CodePostal' ajoutée\n";
    }

    if (!in_array('IDTypePartenaire', $columnNames)) {
        $db->exec("ALTER TABLE PARTENAIRE ADD COLUMN IDTypePartenaire INTEGER REFERENCES TYPE_PARTENAIRE(IDTypePartenaire)");
        echo "  ✅ Colonne 'IDTypePartenaire' ajoutée\n";
    }

    // Vérification et ajout du champ IDTypeEvenement dans EVENEMENT
    echo "Vérification de la table EVENEMENT...\n";

    $columns = $db->query("PRAGMA table_info(EVENEMENT)")->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'name');

    if (!in_array('IDTypeEvenement', $columnNames)) {
        $db->exec("ALTER TABLE EVENEMENT ADD COLUMN IDTypeEvenement INTEGER REFERENCES TYPE_EVENEMENT(IDTypeEvenement)");
        echo "  ✅ Colonne 'IDTypeEvenement' ajoutée\n";
    }

    echo "\n=== MISE À JOUR TERMINÉE AVEC SUCCÈS ===\n";

} catch(PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}
?>