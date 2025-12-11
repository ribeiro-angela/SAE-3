<?php
/**
 * Page de connexion au back-office
 */

require_once 'config/config.php';

// Si déjà connecté, rediriger vers le tableau de bord
if (isLoggedIn()) {
    header('Location: index.php');
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

            // Vérifier les identifiants
            $stmt = $db->prepare("
                SELECT u.*, b.IDUtilisateur as IsBenev, r.IDUtilisateur as IsResp
                FROM UTILISATEUR u
                LEFT JOIN BENEVOLES b ON u.IDUtilisateur = b.IDUtilisateur
                LEFT JOIN RESPONSABLE r ON b.IDUtilisateur = r.IDUtilisateur
                WHERE u.Mail = ? AND b.Actif = TRUE
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['MotDePasse'])) {
                // Connexion réussie
                $_SESSION['admin_id'] = $user['IDUtilisateur'];
                $_SESSION['admin_nom'] = $user['Nom'];
                $_SESSION['admin_prenom'] = $user['Prenom'];
                $_SESSION['admin_email'] = $user['Mail'];
                $_SESSION['is_responsable'] = !empty($user['IsResp']);

                setFlashMessage('success', 'Connexion réussie ! Bienvenue ' . $user['Prenom']);
                header('Location: index.php');
                exit();
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        } catch (Exception $e) {
            $error = 'Erreur de connexion : ' . $e->getMessage();
        }
    }
}
?>