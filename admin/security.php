<?php
/**
 * CONFIGURATION DE SÉCURITÉ
 * Gestion centralisée de la sécurité de l'application
 */

// Démarrage sécurisé de session
class Security {

    /**
     * Démarre une session sécurisée
     */
    public static function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Configuration sécurisée de la session
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', 1); // HTTPS uniquement en production
            ini_set('session.cookie_samesite', 'Strict');

            session_start();

            // Régénérer l'ID de session pour éviter le session fixation
            if (!isset($_SESSION['initiated'])) {
                session_regenerate_id(true);
                $_SESSION['initiated'] = true;
                $_SESSION['created_at'] = time();
            }

            // Vérifier le timeout de session (30 minutes d'inactivité)
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
                self::destroySession();
                return false;
            }
            $_SESSION['last_activity'] = time();

            // Vérifier l'IP et le User-Agent pour détecter le hijacking
            if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] !== self::getClientIP()) {
                self::destroySession();
                return false;
            }
            if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
                self::destroySession();
                return false;
            }

            $_SESSION['user_ip'] = self::getClientIP();
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }
        return true;
    }

    /**
     * Détruit la session en toute sécurité
     */
    public static function destroySession() {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    /**
     * Génère un token CSRF
     */
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie un token CSRF
     */
    public static function verifyCSRFToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Crée un champ CSRF caché pour les formulaires
     */
    public static function getCSRFField() {
        $token = self::generateCSRFToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Nettoie et valide les entrées
     */
    public static function cleanInput($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::cleanInput($value);
            }
            return $data;
        }
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
    }

    /**
     * Échappe les données pour l'affichage HTML
     */
    public static function escape($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valide un email
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide un numéro de téléphone français
     */
    public static function validatePhone($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return preg_match('/^0[1-9][0-9]{8}$/', $phone);
    }

    /**
     * Valide un code postal français
     */
    public static function validatePostalCode($code) {
        return preg_match('/^[0-9]{5}$/', $code);
    }

    /**
     * Hash un mot de passe de manière sécurisée
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }

    /**
     * Vérifie un mot de passe
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Vérifie la force d'un mot de passe
     */
    public static function validatePasswordStrength($password) {
        // Au moins 8 caractères, une majuscule, une minuscule, un chiffre
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins une majuscule";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins une minuscule";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins un chiffre";
        }

        return $errors;
    }

    /**
     * Génère un mot de passe aléatoire sécurisé
     */
    public static function generateSecurePassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        $password = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $max)];
        }

        return $password;
    }

    /**
     * Limite le nombre de tentatives de connexion
     */
    public static function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 900) {
        $cacheFile = __DIR__ . '/../temp/rate_limit_' . md5($identifier) . '.json';

        // Créer le dossier temp s'il n'existe pas
        if (!is_dir(__DIR__ . '/../temp')) {
            mkdir(__DIR__ . '/../temp', 0755, true);
        }

        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);

            // Réinitialiser si la fenêtre de temps est dépassée
            if (time() - $data['first_attempt'] > $timeWindow) {
                unlink($cacheFile);
                return true;
            }

            // Bloquer si trop de tentatives
            if ($data['attempts'] >= $maxAttempts) {
                $remainingTime = $timeWindow - (time() - $data['first_attempt']);
                throw new Exception("Trop de tentatives. Réessayez dans " . ceil($remainingTime / 60) . " minutes.");
            }

            // Incrémenter le compteur
            $data['attempts']++;
            file_put_contents($cacheFile, json_encode($data));
        } else {
            // Première tentative
            $data = [
                'attempts' => 1,
                'first_attempt' => time()
            ];
            file_put_contents($cacheFile, json_encode($data));
        }

        return true;
    }

    /**
     * Réinitialise le compteur de tentatives
     */
    public static function resetRateLimit($identifier) {
        $cacheFile = __DIR__ . '/../temp/rate_limit_' . md5($identifier) . '.json';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    /**
     * Obtient l'IP réelle du client
     */
    private static function getClientIP() {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        }
        return $ip;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Vérifie si l'utilisateur a un certain rôle
     */
    public static function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    /**
     * Redirige si non connecté
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /admin/login.php');
            exit;
        }
    }

    /**
     * Sécurise l'upload de fichiers
     */
    public static function secureFileUpload($file, $allowedTypes, $maxSize = 5242880) {
        $errors = [];

        // Vérifier les erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors de l'upload du fichier";
            return ['success' => false, 'errors' => $errors];
        }

        // Vérifier la taille
        if ($file['size'] > $maxSize) {
            $errors[] = "Le fichier est trop volumineux (max: " . ($maxSize / 1024 / 1024) . " MB)";
            return ['success' => false, 'errors' => $errors];
        }

        // Vérifier le type MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $errors[] = "Type de fichier non autorisé";
            return ['success' => false, 'errors' => $errors];
        }

        // Vérifier l'extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/gif' => ['gif'],
            'application/pdf' => ['pdf']
        ];

        if (!isset($allowedExtensions[$mimeType]) || !in_array($extension, $allowedExtensions[$mimeType])) {
            $errors[] = "Extension de fichier non autorisée";
            return ['success' => false, 'errors' => $errors];
        }

        return ['success' => true, 'mime_type' => $mimeType, 'extension' => $extension];
    }

    /**
     * Log une tentative suspecte
     */
    public static function logSuspiciousActivity($message, $data = []) {
        $logFile = __DIR__ . '/../logs/security.log';

        if (!is_dir(__DIR__ . '/../logs')) {
            mkdir(__DIR__ . '/../logs', 0755, true);
        }

        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => self::getClientIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'message' => $message,
            'data' => $data
        ];

        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND);
    }
}