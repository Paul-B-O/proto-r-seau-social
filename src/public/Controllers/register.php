<?php



use Database\Database;

session_start();

// Exemple : instanciation de ta DB (à adapter)
$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ======================
    // 1. Récupération données
    // ======================
    $nickname  = trim($_POST['nickname'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';

    // ======================
    // 2. VALIDATION
    // ======================

    // pseudo
    if (strlen($nickname) < 3 || strlen($nickname) > 30) {
        $errors[] = "Le pseudo doit contenir entre 3 et 30 caractères.";
    }

    // username
    if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $errors[] = "Identifiant invalide (3-30 caractères, alphanumérique + underscore).";
    }

    // password
    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    if (empty($errors)) {

        $existing = $db->select(
            "SELECT id FROM users WHERE username = :username",
            ['username' => $username]
        );

        if (!empty($existing)) {
            $errors[] = "Cet identifiant est déjà utilisé.";
        }
    }

    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $userId = $db->insert(
            "INSERT INTO users (username, nickname, password)
             VALUES (:username, :nickname, :password)",
            [
                'username' => $username,
                'nickname' => $nickname,
                'password' => $hashedPassword
            ]
        );


        $_SESSION['user_id'] = $userId;

        header("Location: /home");
        exit;
    }
}


require_once ROOT."/src/public/Views/register.view.php";