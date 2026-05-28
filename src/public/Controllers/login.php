<?php

use Database\Database;

session_start();

// Instanciation DB (à adapter à ton projet)
$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ======================
    // 1. Récupération données
    // ======================
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    // ======================
    // 2. VALIDATION SIMPLE
    // ======================
    if ($identifier === '' || $password === '') {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    // ======================
    // 3. SI PAS D'ERREUR → CHECK BDD
    // ======================
    if (empty($errors)) {

        $user = $db->select(
            "SELECT * FROM users WHERE username = :identifier LIMIT 1",
            ['identifier' => $identifier]
        );

        if (!$user) {
            $errors[] = "Identifiant ou mot de passe incorrect.";
        } else {

            $user = $user[0];

            // ======================
            // 4. CHECK PASSWORD
            // ======================
            if (!password_verify($password, $user['password'])) {
                $errors[] = "Identifiant ou mot de passe incorrect.";
            } else {

                // ======================
                // 5. LOGIN OK → SESSION
                // ======================
                $_SESSION['user_id'] = $user["id"];

                header("Location: /home");
                exit;
            }
        }
    }
}


require_once ROOT."/src/public/Views/login.view.php";

