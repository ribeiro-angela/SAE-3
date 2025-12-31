<?php
session_start();

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new PDO('sqlite:' . __DIR__ . '/../database/arme_du_salut.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Rechercher l'utilisateur
        $stmt = $db->prepare("
            SELECT u.*, r.Role, r.NiveauAcces 
            FROM UTILISATEUR u
            LEFT JOIN RESPONSABLE r ON u.IDUtilisateur = r.IDUtilisateur
            WHERE u.Mail = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['MotDePasse'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['IDUtilisateur'];
            $_SESSION['user_name'] = $user['Prenom'] . ' ' . $user['Nom'];
            $_SESSION['user_email'] = $user['Mail'];
            $_SESSION['user_role'] = $user['Role'] ?? 'Bénévole';
            $_SESSION['user_niveau'] = $user['NiveauAcces'] ?? 'Utilisateur';

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect';
        }

    } catch(PDOException $e) {
        $error = 'Erreur de connexion à la base de données';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Administration Armée du Salut</title>
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

        .error-message {
            background: #fee;
            color: #c23331;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c23331;
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
        <h1>Administration</h1>
        <p>Armée du Salut</p>
    </div>

    <?php if ($error): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> Adresse email
            </label>
            <input type="email" id="email" name="email" required autofocus
                   placeholder="votre.email@armeedusalut.fr">
        </div>

        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock"></i> Mot de passe
            </label>
            <input type="password" id="password" name="password" required
                   placeholder="Votre mot de passe">
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>
    </form>

    <div class="back-link">
        <a href="/pages/accueil.php">
            <i class="fas fa-arrow-left"></i> Retour au site
        </a>
    </div>
</div>
</body>
</html>