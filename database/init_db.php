<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== INITIALISATION DE LA BASE DE DONNÉES ===\n\n";

try {
    $db = new PDO('sqlite:' . __DIR__ . '/arme_du_salut.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base réussie\n\n";

    // CRÉATION DES TABLES
    echo "Création des tables...\n";

    $sql = "



    CREATE TABLE IF NOT EXISTS VILLE (
        IDVille INTEGER PRIMARY KEY AUTOINCREMENT,
        NomVille TEXT NOT NULL,
        CodePostal TEXT NOT NULL,
        Pays TEXT DEFAULT 'France'
    );

    CREATE TABLE IF NOT EXISTS REGIME_ALIMENTAIRE (
        IDRegime INTEGER PRIMARY KEY AUTOINCREMENT,
        NomRegime TEXT NOT NULL UNIQUE
    );

    CREATE TABLE IF NOT EXISTS HANDICAP (
        IDHandicap INTEGER PRIMARY KEY AUTOINCREMENT,
        NomHandicap TEXT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS COMPETENCE (
        IDCompetence INTEGER PRIMARY KEY AUTOINCREMENT,
        NomCompetence TEXT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS CATEGORIE (
        IDCategorie INTEGER PRIMARY KEY AUTOINCREMENT,
        NomCategorie TEXT NOT NULL UNIQUE,
        Couleur TEXT DEFAULT '#3498db'
    );

    CREATE TABLE IF NOT EXISTS UTILISATEUR (
        IDUtilisateur INTEGER PRIMARY KEY AUTOINCREMENT,
        Nom TEXT NOT NULL,
        Prenom TEXT NOT NULL,
        Mail TEXT NOT NULL UNIQUE,
        Telephone TEXT,
        MotDePasse TEXT NOT NULL,
        DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS BENEVOLES (
        IDUtilisateur INTEGER PRIMARY KEY,
        DateNaissance DATE NOT NULL,
        Profession TEXT,
        Actif INTEGER DEFAULT 1,
        Permis INTEGER DEFAULT 0,
        DateCreationBenevole DATE DEFAULT CURRENT_DATE,
        IDVille INTEGER,
        IDRegime INTEGER,
        IDHandicap INTEGER,
        IDCompetence INTEGER,
        FOREIGN KEY (IDUtilisateur) REFERENCES UTILISATEUR(IDUtilisateur) ON DELETE CASCADE,
        FOREIGN KEY (IDVille) REFERENCES VILLE(IDVille),
        FOREIGN KEY (IDRegime) REFERENCES REGIME_ALIMENTAIRE(IDRegime),
        FOREIGN KEY (IDHandicap) REFERENCES HANDICAP(IDHandicap),
        FOREIGN KEY (IDCompetence) REFERENCES COMPETENCE(IDCompetence)
    );

    CREATE TABLE IF NOT EXISTS RESPONSABLE (
        IDUtilisateur INTEGER PRIMARY KEY,
        Role TEXT NOT NULL,
        NiveauAcces TEXT DEFAULT 'Coordinateur',
        DateNomination DATE DEFAULT CURRENT_DATE,
        FOREIGN KEY (IDUtilisateur) REFERENCES BENEVOLES(IDUtilisateur) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS MISSIONS (
        IDMission INTEGER PRIMARY KEY AUTOINCREMENT,
        Titre TEXT NOT NULL,
        Description TEXT,
        DateMission DATE NOT NULL,
        HeureMission TIME NOT NULL,
        Lieu TEXT NOT NULL,
        NbBenevolesAttendu INTEGER DEFAULT 1,
        Statut TEXT DEFAULT 'Planifiée',
        DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP,
        IDCategorie INTEGER NOT NULL,
        IDResponsable INTEGER NOT NULL,
        FOREIGN KEY (IDCategorie) REFERENCES CATEGORIE(IDCategorie),
        FOREIGN KEY (IDResponsable) REFERENCES RESPONSABLE(IDUtilisateur)
    );

    CREATE TABLE IF NOT EXISTS PARTICIPE_MISSION (
        IDUtilisateur INTEGER,
        IDMission INTEGER,
        DateInscription DATETIME DEFAULT CURRENT_TIMESTAMP,
        Statut TEXT DEFAULT 'Inscrit',
        PRIMARY KEY (IDUtilisateur, IDMission),
        FOREIGN KEY (IDUtilisateur) REFERENCES BENEVOLES(IDUtilisateur) ON DELETE CASCADE,
        FOREIGN KEY (IDMission) REFERENCES MISSIONS(IDMission) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS EVENEMENT (
        IDEvenement INTEGER PRIMARY KEY AUTOINCREMENT,
        NomEvenement TEXT NOT NULL,
        Description TEXT,
        DateEvenement DATE NOT NULL,
        HeureEvenement TIME,
        Lieu TEXT,
        TypeEvenement TEXT NOT NULL,
        Statut TEXT DEFAULT 'Planifié',
        IDOrganisateur INTEGER NOT NULL,
        IDTypeEvenement INTEGER REFERENCES TYPE_EVENEMENT(IDTypeEvenement),
        FOREIGN KEY (IDOrganisateur) REFERENCES RESPONSABLE(IDUtilisateur)
    );

    CREATE TABLE IF NOT EXISTS PARTICIPE_EVENEMENT (
        IDUtilisateur INTEGER,
        IDEvenement INTEGER,
        DateInscription DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (IDUtilisateur, IDEvenement),
        FOREIGN KEY (IDUtilisateur) REFERENCES BENEVOLES(IDUtilisateur) ON DELETE CASCADE,
        FOREIGN KEY (IDEvenement) REFERENCES EVENEMENT(IDEvenement) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS PARTENAIRE (
        IDPartenaire INTEGER PRIMARY KEY AUTOINCREMENT,
        NomPartenaire TEXT NOT NULL,
        TypePartenaire TEXT NOT NULL,
        Telephone TEXT,
        Mail TEXT,
        Actif INTEGER DEFAULT 1,
        DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP,
        Adresse TEXT,
        Ville TEXT,
        CodePostal TEXT,
        IDTypePartenaire INTEGER REFERENCES TYPE_PARTENAIRE(IDTypePartenaire)
    );

    CREATE TABLE IF NOT EXISTS SUBVENTION (
        IDSubvention INTEGER PRIMARY KEY AUTOINCREMENT,
        OrganismeFinanceur TEXT NOT NULL,
        Montant REAL NOT NULL,
        Annee INTEGER NOT NULL,
        Statut TEXT DEFAULT 'Demandée',
        IDPartenaire INTEGER,
        FOREIGN KEY (IDPartenaire) REFERENCES PARTENAIRE(IDPartenaire)
    );

    CREATE TABLE IF NOT EXISTS DONATEUR (
        IDDonateur INTEGER PRIMARY KEY AUTOINCREMENT,
        Nom TEXT NOT NULL,
        Prenom TEXT,
        Mail TEXT,
        Telephone TEXT,
        Actif INTEGER DEFAULT 1
    );

    CREATE TABLE IF NOT EXISTS DON (
        IDDon INTEGER PRIMARY KEY AUTOINCREMENT,
        Montant REAL NOT NULL,
        DateDon DATE NOT NULL,
        TypeDon TEXT DEFAULT 'Ponctuel',
        IDDonateur INTEGER,
        FOREIGN KEY (IDDonateur) REFERENCES DONATEUR(IDDonateur)
    );

    CREATE TABLE IF NOT EXISTS MATERIEL (
        IDMateriel INTEGER PRIMARY KEY AUTOINCREMENT,
        NomMateriel TEXT NOT NULL,
        DescriptionMateriel TEXT,
        QuantiteDisponible INTEGER DEFAULT 0
    );
 
    CREATE TABLE IF NOT EXISTS TACHE_SPECIFIQUE (
        IDTache INTEGER PRIMARY KEY AUTOINCREMENT,
        NomTache TEXT NOT NULL,
        DescriptionTache TEXT
    );

    CREATE TABLE IF NOT EXISTS NECESSITER (
        IDMission INTEGER,
        IDMateriel INTEGER,
        QuantiteNecessaire INTEGER DEFAULT 1,
        PRIMARY KEY (IDMission, IDMateriel),
        FOREIGN KEY (IDMission) REFERENCES MISSIONS(IDMission) ON DELETE CASCADE,
        FOREIGN KEY (IDMateriel) REFERENCES MATERIEL(IDMateriel) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS CONTENIR (
        IDMission INTEGER,
        IDTache INTEGER,
        PRIMARY KEY (IDMission, IDTache),
        FOREIGN KEY (IDMission) REFERENCES MISSIONS(IDMission) ON DELETE CASCADE,
        FOREIGN KEY (IDTache) REFERENCES TACHE_SPECIFIQUE(IDTache) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS TYPE_EVENEMENT (
        IDTypeEvenement INTEGER PRIMARY KEY AUTOINCREMENT,
        NomTypeEvenement TEXT NOT NULL UNIQUE,
        DescriptionTypeEvenement TEXT
    );

    CREATE TABLE IF NOT EXISTS TYPE_PARTENAIRE (
        IDTypePartenaire INTEGER PRIMARY KEY AUTOINCREMENT,
        NomTypePartenaire TEXT NOT NULL UNIQUE,
        DescriptionTypePartenaire TEXT
    );

    CREATE TABLE IF NOT EXISTS TYPE_SOUTIEN (
        IDTypeSoutien INTEGER PRIMARY KEY AUTOINCREMENT,
        NomTypeSoutien TEXT NOT NULL UNIQUE
    );

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

    CREATE TABLE IF NOT EXISTS COTISATION (
            IDCotisation INTEGER PRIMARY KEY AUTOINCREMENT,
            MontantCotisation REAL NOT NULL,
            DateCotisation DATE NOT NULL,
            AnneeValidite INTEGER NOT NULL,
            Statut TEXT DEFAULT 'Payée',
            IDUtilisateur INTEGER NOT NULL,
            FOREIGN KEY (IDUtilisateur) REFERENCES BENEVOLES(IDUtilisateur) ON DELETE CASCADE
        );

    ";

    $db->exec($sql);
    echo "✅ Tables créées\n\n";

    // INSERTION DES DONNÉES DE RÉFÉRENCE
    echo "Insertion des données de référence...\n";

    $db->exec("
        INSERT INTO VILLE (NomVille, CodePostal) VALUES
        ('Paris', '75001'),
        ('Lyon', '69001'),
        ('Marseille', '13001'),
        ('Meaux', '77100'),
        ('Lille', '59000');

        INSERT INTO REGIME_ALIMENTAIRE (NomRegime) VALUES
        ('Aucun'),
        ('Végétarien'),
        ('Végan'),
        ('Halal'),
        ('Casher'),
        ('Sans gluten');

        INSERT INTO HANDICAP (NomHandicap) VALUES
        ('Aucun'),
        ('Mobilité réduite'),
        ('Problèmes de dos'),
        ('Malvoyant'),
        ('Malentendant');

        INSERT INTO COMPETENCE (NomCompetence) VALUES
        ('Informatique'),
        ('Cuisine'),
        ('Logistique'),
        ('Communication'),
        ('Administratif'),
        ('Bricolage'),
        ('Animation'),
        ('Conduite');

        INSERT INTO CATEGORIE (NomCategorie, Couleur) VALUES
        ('Distribution alimentaire', '#e74c3c'),
        ('Collecte', '#3498db'),
        ('Accompagnement', '#2ecc71'),
        ('Logistique', '#f39c12'),
        ('Animation', '#9b59b6'),
        ('Maraude', '#1abc9c');

        INSERT OR IGNORE INTO MATERIEL (NomMateriel, DescriptionMateriel, QuantiteDisponible) VALUES
        ('Cartons de collecte', 'Cartons pour collecter les denrées', 100),
        ('Tables pliantes', 'Tables pour distributions', 20),
        ('Chaises pliantes', 'Chaises pour événements', 50),
        ('Vaisselle jetable', 'Assiettes, couverts, gobelets', 500),
        ('Nappes', 'Nappes jetables ou réutilisables', 30),
        ('Microphone', 'Microphone sans fil', 5),
        ('Enceintes portables', 'Enceintes pour sonorisation', 4),
        ('Banderoles', 'Banderoles de signalisation', 10);

        INSERT OR IGNORE INTO TACHE_SPECIFIQUE (NomTache, DescriptionTache) VALUES
        ('Tri des denrées', 'Trier les aliments par catégorie'),
        ('Transport', 'Transporter le matériel et les denrées'),
        ('Stockage', 'Ranger et stocker les produits'),
        ('Distribution', 'Distribuer aux bénéficiaires'),
        ('Accueil', 'Accueillir les participants'),
        ('Animation', 'Animer les activités'),
        ('Cuisine', 'Préparer les repas'),
        ('Nettoyage', 'Nettoyer les espaces après événement');

        INSERT OR IGNORE INTO TYPE_EVENEMENT (NomTypeEvenement, DescriptionTypeEvenement) VALUES
        ('Pot de bienvenue', 'Événement d''accueil des nouveaux bénévoles'),
        ('Réunion', 'Réunion d''équipe ou de coordination'),
        ('Formation', 'Formation pour les bénévoles'),
        ('Collecte', 'Événement de collecte de dons'),
        ('Fête', 'Événement festif'),
        ('Distribution', 'Distribution alimentaire ou vestimentaire');

        INSERT OR IGNORE INTO TYPE_PARTENAIRE (NomTypePartenaire, DescriptionTypePartenaire) VALUES
        ('Entreprise', 'Partenaire privé - entreprise'),
        ('Fondation', 'Fondation philanthropique'),
        ('Institution publique', 'Collectivité ou organisme public'),
        ('Association', 'Association partenaire'),
        ('Particulier', 'Grand donateur individuel');

        INSERT OR IGNORE INTO TYPE_SOUTIEN (NomTypeSoutien) VALUES
        ('Financier'),
        ('Matériel'),
        ('Logistique'),
        ('Compétences'),
        ('Mise à disposition de locaux');

    ");

    echo "✅ Données de référence insérées\n\n";

    // CRÉATION DU COMPTE ADMIN
    echo "Création du compte administrateur...\n";

    $password = password_hash('admin123', PASSWORD_DEFAULT);

    $db->exec("
        INSERT INTO UTILISATEUR (Nom, Prenom, Mail, Telephone, MotDePasse) VALUES
        ('Admin', 'Super', 'admin@armeedusalut.fr', '0123456789', '$password');
    ");

    $adminId = $db->lastInsertId();

    $db->exec("
        INSERT INTO BENEVOLES (IDUtilisateur, DateNaissance, Profession, Actif, Permis, IDVille, IDRegime, IDHandicap, IDCompetence) VALUES
        ($adminId, '1980-01-15', 'Responsable association', 1, 1, 1, 1, 1, 1);

        INSERT INTO RESPONSABLE (IDUtilisateur, Role, NiveauAcces) VALUES
        ($adminId, 'Administrateur', 'Admin');
    ");

    echo "✅ Compte admin créé\n";
    echo "   Email: admin@armeedusalut.fr\n";
    echo "   Mot de passe: admin123\n\n";


    echo "=== INITIALISATION TERMINÉE AVEC SUCCÈS ===\n";

} catch(PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}
?>