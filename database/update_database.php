<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== MISE À JOUR COMPLÈTE DE LA BASE DE DONNÉES ===\n\n";

try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base réussie\n\n";

    // CRÉATION DES TABLES MANQUANTES RIPPPPP CA FINIT JAMAIS ALAIDE

    echo "Création des nouvelles tables...\n";

    $sql = "
    -- Table MATERIEL
    CREATE TABLE IF NOT EXISTS MATERIEL (
        IDMateriel INTEGER PRIMARY KEY AUTOINCREMENT,
        NomMateriel TEXT NOT NULL,
        DescriptionMateriel TEXT,
        QuantiteDisponible INTEGER DEFAULT 0
    );

    -- Table TACHE_SPECIFIQUE  
    CREATE TABLE IF NOT EXISTS TACHE_SPECIFIQUE (
        IDTache INTEGER PRIMARY KEY AUTOINCREMENT,
        NomTache TEXT NOT NULL,
        DescriptionTache TEXT
    );

    -- Table de liaison NECESSITER (Missions - Matériel)
    CREATE TABLE IF NOT EXISTS NECESSITER (
        IDMission INTEGER,
        IDMateriel INTEGER,
        QuantiteNecessaire INTEGER DEFAULT 1,
        PRIMARY KEY (IDMission, IDMateriel),
        FOREIGN KEY (IDMission) REFERENCES MISSIONS(IDMission) ON DELETE CASCADE,
        FOREIGN KEY (IDMateriel) REFERENCES MATERIEL(IDMateriel) ON DELETE CASCADE
    );

    -- Table de liaison CONTENIR (Missions - Tâches)
    CREATE TABLE IF NOT EXISTS CONTENIR (
        IDMission INTEGER,
        IDTache INTEGER,
        PRIMARY KEY (IDMission, IDTache),
        FOREIGN KEY (IDMission) REFERENCES MISSIONS(IDMission) ON DELETE CASCADE,
        FOREIGN KEY (IDTache) REFERENCES TACHE_SPECIFIQUE(IDTache) ON DELETE CASCADE
    );

    -- Table TYPE_EVENEMENT (pour standardiser)
    CREATE TABLE IF NOT EXISTS TYPE_EVENEMENT (
        IDTypeEvenement INTEGER PRIMARY KEY AUTOINCREMENT,
        NomTypeEvenement TEXT NOT NULL UNIQUE,
        DescriptionTypeEvenement TEXT
    );

    -- Table TYPE_PARTENAIRE
    CREATE TABLE IF NOT EXISTS TYPE_PARTENAIRE (
        IDTypePartenaire INTEGER PRIMARY KEY AUTOINCREMENT,
        NomTypePartenaire TEXT NOT NULL UNIQUE,
        DescriptionTypePartenaire TEXT
    );

    -- Table TYPE_SOUTIEN
    CREATE TABLE IF NOT EXISTS TYPE_SOUTIEN (
        IDTypeSoutien INTEGER PRIMARY KEY AUTOINCREMENT,
        NomTypeSoutien TEXT NOT NULL UNIQUE
    );

    -- Table CONTACT (contacts des partenaires)
    CREATE TABLE IF NOT EXISTS CONTACT (
        IDContact INTEGER PRIMARY KEY AUTOINCREMENT,
        NomContact TEXT NOT NULL,
        PrenomContact TEXT,
        TelephoneContact TEXT,
        MailContact TEXT,
        FonctionContact TEXT,
        IDPartenaire INTEGER,
        FOREIGN KEY (IDPartenaire) REFERENCES PARTENAIRE(IDPartenaire) ON DELETE CASCADE
    );

    -- Table CONVENTION
    CREATE TABLE IF NOT EXISTS CONVENTION (
        IDConvention INTEGER PRIMARY KEY AUTOINCREMENT,
        NomConvention TEXT NOT NULL,
        DateSignature DATE,
        DateFin DATE,
        DescriptionConvention TEXT,
        Statut TEXT DEFAULT 'Active',
        IDPartenaire INTEGER,
        FOREIGN KEY (IDPartenaire) REFERENCES PARTENAIRE(IDPartenaire)
    );

    -- Table AIDE (lien partenaires - missions/événements)
    CREATE TABLE IF NOT EXISTS AIDE (
        IDAide INTEGER PRIMARY KEY AUTOINCREMENT,
        TypeAide TEXT NOT NULL,
        Montant REAL,
        DateAide DATE,
        IDPartenaire INTEGER,
        IDMission INTEGER,
        IDEvenement INTEGER,
        FOREIGN KEY (IDPartenaire) REFERENCES PARTENAIRE(IDPartenaire),
        FOREIGN KEY (IDMission) REFERENCES MISSIONS(IDMission) ON DELETE CASCADE,
        FOREIGN KEY (IDEvenement) REFERENCES EVENEMENT(IDEvenement) ON DELETE CASCADE
    );

    -- Table ACTUALITE (pour la gestion des contenus)
    CREATE TABLE IF NOT EXISTS ACTUALITE (
        IDActualite INTEGER PRIMARY KEY AUTOINCREMENT,
        Titre TEXT NOT NULL,
        Contenu TEXT,
        DatePublication DATE DEFAULT CURRENT_DATE,
        Statut TEXT DEFAULT 'Brouillon',
        ImageURL TEXT,
        IDAuteur INTEGER,
        FOREIGN KEY (IDAuteur) REFERENCES UTILISATEUR(IDUtilisateur)
    );

    -- Table MEDIA (gestion des médias)
    CREATE TABLE IF NOT EXISTS MEDIA (
        IDMedia INTEGER PRIMARY KEY AUTOINCREMENT,
        NomFichier TEXT NOT NULL,
        CheminFichier TEXT NOT NULL,
        TypeMedia TEXT NOT NULL,
        DateAjout DATETIME DEFAULT CURRENT_TIMESTAMP,
        IDMission INTEGER,
        IDEvenement INTEGER,
        IDActualite INTEGER,
        FOREIGN KEY (IDMission) REFERENCES MISSIONS(IDMission) ON DELETE CASCADE,
        FOREIGN KEY (IDEvenement) REFERENCES EVENEMENT(IDEvenement) ON DELETE CASCADE,
        FOREIGN KEY (IDActualite) REFERENCES ACTUALITE(IDActualite) ON DELETE CASCADE
    );

    -- Mise à jour de la table PARTENAIRE avec les nouvelles colonnes
    ALTER TABLE PARTENAIRE ADD COLUMN Adresse TEXT;
    ALTER TABLE PARTENAIRE ADD COLUMN Ville TEXT;
    ALTER TABLE PARTENAIRE ADD COLUMN CodePostal TEXT;
    ALTER TABLE PARTENAIRE ADD COLUMN IDTypePartenaire INTEGER REFERENCES TYPE_PARTENAIRE(IDTypePartenaire);
    
    -- Mise à jour de la table EVENEMENT avec IDTypeEvenement
    ALTER TABLE EVENEMENT ADD COLUMN IDTypeEvenement INTEGER REFERENCES TYPE_EVENEMENT(IDTypeEvenement);
    ";

    $db->exec($sql);
    echo "✅ Nouvelles tables créées\n\n";

    // INSERTION DES DONNÉES DE RÉFÉRENCE
    echo "Insertion des données de référence...\n";

    $db->exec("
        -- Matériel standard
        INSERT OR IGNORE INTO MATERIEL (NomMateriel, DescriptionMateriel, QuantiteDisponible) VALUES
        ('Cartons de collecte', 'Cartons pour collecter les denrées', 100),
        ('Tables pliantes', 'Tables pour distributions', 20),
        ('Chaises pliantes', 'Chaises pour événements', 50),
        ('Vaisselle jetable', 'Assiettes, couverts, gobelets', 500),
        ('Nappes', 'Nappes jetables ou réutilisables', 30),
        ('Microphone', 'Microphone sans fil', 5),
        ('Enceintes portables', 'Enceintes pour sonorisation', 4),
        ('Banderoles', 'Banderoles de signalisation', 10);

        -- Tâches spécifiques
        INSERT OR IGNORE INTO TACHE_SPECIFIQUE (NomTache, DescriptionTache) VALUES
        ('Tri des denrées', 'Trier les aliments par catégorie'),
        ('Transport', 'Transporter le matériel et les denrées'),
        ('Stockage', 'Ranger et stocker les produits'),
        ('Distribution', 'Distribuer aux bénéficiaires'),
        ('Accueil', 'Accueillir les participants'),
        ('Animation', 'Animer les activités'),
        ('Cuisine', 'Préparer les repas'),
        ('Nettoyage', 'Nettoyer les espaces après événement');

        -- Types d'événements
        INSERT OR IGNORE INTO TYPE_EVENEMENT (NomTypeEvenement, DescriptionTypeEvenement) VALUES
        ('Pot de bienvenue', 'Événement d''accueil des nouveaux bénévoles'),
        ('Réunion', 'Réunion d''équipe ou de coordination'),
        ('Formation', 'Formation pour les bénévoles'),
        ('Collecte', 'Événement de collecte de dons'),
        ('Fête', 'Événement festif'),
        ('Distribution', 'Distribution alimentaire ou vestimentaire');

        -- Types de partenaires
        INSERT OR IGNORE INTO TYPE_PARTENAIRE (NomTypePartenaire, DescriptionTypePartenaire) VALUES
        ('Entreprise', 'Partenaire privé - entreprise'),
        ('Fondation', 'Fondation philanthropique'),
        ('Institution publique', 'Collectivité ou organisme public'),
        ('Association', 'Association partenaire'),
        ('Particulier', 'Grand donateur individuel');

        -- Types de soutien
        INSERT OR IGNORE INTO TYPE_SOUTIEN (NomTypeSoutien) VALUES
        ('Financier'),
        ('Matériel'),
        ('Logistique'),
        ('Compétences'),
        ('Mise à disposition de locaux');
    ");

    echo "✅ Données de référence insérées\n\n";

    echo "=== MISE À JOUR TERMINÉE AVEC SUCCÈS ===\n";

} catch(PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    // Si erreur sur ALTER TABLE (colonne déjà existante), on continue
    if (strpos($e->getMessage(), 'duplicate column name') !== false) {
        echo "⚠️ Certaines colonnes existaient déjà (normal)\n";
    }
}
?>