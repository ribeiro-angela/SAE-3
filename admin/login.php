<?php
require_once 'config/config.php';

// Si déjà connecté, rediriger
if (isLoggedIn()) {
    header('Location: pages/dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        try {
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("
                SELECT u.*, r.IDUtilisateur as IsResp
                FROM UTILISATEUR u
                JOIN BENEVOLES b ON u.IDUtilisateur = b.IDUtilisateur
                LEFT JOIN RESPONSABLE r ON b.IDUtilisateur = r.IDUtilisateur
                WHERE u.Mail = ? AND b.Actif = 1
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['MotDePasse'])) {
                $_SESSION['admin_id'] = $user['IDUtilisateur'];
                $_SESSION['admin_nom'] = $user['Nom'];
                $_SESSION['admin_prenom'] = $user['Prenom'];
                $_SESSION['admin_email'] = $user['Mail'];
                $_SESSION['is_responsable'] = !empty($user['IsResp']);

                header('Location: pages/dashboard.php');
                exit();
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        } catch (Exception $e) {
            $error = 'Erreur de connexion';
        }
    }
}
?>