<?php
/**
 * PAGE DE CONNEXION SÉCURISÉE
 * Avec protection CSRF, rate limiting et hachage des mots de passe
 */

require_once __DIR__ . '/security.php';

Security::startSecureSession();

// Si déjà connecté, rediriger vers le dashboard
if (Security::isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$rateLimitError = false;

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Vérifier le token CSRF
        if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
            throw new Exception('Token de sécurité invalide. Veuillez réessayer.');
        }

        $email = Security::cleanInput($_POST['email']);
        $password = $_POST['password'];

        // Valider l'email
        if (!Security::validateEmail($email)) {
            throw new Exception('Email invalide');
        }

        // Vérifier le rate limiting (5 tentatives max en 15 minutes)
        Security::checkRateLimit('login_' . $email);

        // Connexion à la base de données
        $db = new PDO('sqlite:' . __DIR__ . '/../database/arme_du_salut.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Rechercher l'utilisateur
        $stmt = $db->prepare("
            SELECT u.*, r.Role, r.NiveauAcces 
            FROM UTILISATEUR u
            LEFT JOIN RESPONSABLE r ON u.IDUtilisateur = r.IDUtilisateur
            WHERE u.Mail = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Log de tentative suspecte
            Security::logSuspiciousActivity('Tentative de connexion avec email inexistant', ['email' => $email]);
            throw new Exception('Email ou mot de passe incorrect');
        }

        // Vérifier le mot de passe
        if (!Security::verifyPassword($password, $user['MotDePasse'])) {
            // Log de tentative suspecte
            Security::logSuspiciousActivity('Mot de passe incorrect', ['email' => $email]);
            throw new Exception('Email ou mot de passe incorrect');
        }

        // Connexion réussie - Réinitialiser le rate limit
        Security::resetRateLimit('login_' . $email);

        // Régénérer l'ID de session pour éviter le session fixation
        session_regenerate_id(true);

        // Stocker les informations de l'utilisateur en session
        $_SESSION['user_id'] = $user['IDUtilisateur'];
        $_SESSION['user_name'] = $user['Prenom'] . ' ' . $user['Nom'];
        $_SESSION['user_email'] = $user['Mail'];
        $_SESSION['user_role'] = $user['Role'] ?? 'Bénévole';
        $_SESSION['user_niveau'] = $user['NiveauAcces'] ?? 'Utilisateur';
        $_SESSION['login_time'] = time();

        // Log de connexion réussie
        $logStmt = $db->prepare("
            INSERT INTO LOGS_CONNEXION (IDUtilisateur, DateConnexion, IPAddress, UserAgent) 
            VALUES (?, ?, ?, ?)
        ");

        // Créer la table des logs si elle n'existe pas
        $db->exec("
            CREATE TABLE IF NOT EXISTS LOGS_CONNEXION (
                IDLog INTEGER PRIMARY KEY AUTOINCREMENT,
                IDUtilisateur INTEGER,
                DateConnexion DATETIME DEFAULT CURRENT_TIMESTAMP,
                IPAddress TEXT,
                UserAgent TEXT,
                FOREIGN KEY (IDUtilisateur) REFERENCES UTILISATEUR(IDUtilisateur)
            )
        ");

        $logStmt->execute([
            $user['IDUtilisateur'],
            date('Y-m-d H:i:s'),
            $_SESSION['user_ip'],
            $_SESSION['user_agent']
        ]);

        // Redirection vers le dashboard
        header('Location: dashboard.php');
        exit;

    } catch(Exception $e) {
        $error = $e->getMessage();
        if (strpos($error, 'Trop de tentatives') !== false) {
            $rateLimitError = true;
        }
    }
}

// Générer un nouveau token CSRF
$csrfToken = Security::generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Sécurisée - Administration Armée du Salut</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="/assets/image/logo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #212f3c 0%, #2c3e50 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            padding: 50px 40px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }

        .logo-container h1 {
            font-size: 24px;
            color: #212f3c;
            margin-bottom: 5px;
        }

        .logo-container p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .security-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 12px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .error-message {
            background: #fee;
            color: #c23331;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c23331;
        }

        .error-message.rate-limit {
            background: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: #c23331;
            box-shadow: 0 0 0 3px rgba(194, 51, 49, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #7f8c8d;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #c23331, #e74c3c);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(194, 51, 49, 0.3);
        }

        .btn-login:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #7f8c8d;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #c23331;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="logo-container">
        <img src="/assets/image/logo.png" alt="Logo Armée du Salut">
        <h1>Administration Sécurisée</h1>
        <p>Armée du Salut</p>
    </div>

    <div class="security-badge">
        <i class="fas fa-shield-alt"></i>
        Connexion protégée par chiffrement
    </div>

    <?php if ($error): ?>
        <div class="error-message <?php echo $rateLimitError ? 'rate-limit' : ''; ?>">
            <i class="fas fa-exclamation-circle"></i> <?php echo Security::escape($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" id="loginForm">
        <?php echo Security::getCSRFField(); ?>

        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> Adresse email
            </label>
            <input type="email" id="email" name="email" required autofocus
                   placeholder="votre.email@armeedusalut.fr"
                   value="<?php echo isset($_POST['email']) ? Security::escape($_POST['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock"></i> Mot de passe
            </label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required
                       placeholder="Votre mot de passe">
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            </div>
        </div>

        <button type="submit" class="btn-login" id="submitBtn">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>
    </form>

    <div class="back-link">
        <a href="/pages/accueil.php">
            <i class="fas fa-arrow-left"></i> Retour au site
        </a>
    </div>
</div>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    // Désactiver le bouton pendant la soumission
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion en cours...';
    });
</script>
</body>
</html>