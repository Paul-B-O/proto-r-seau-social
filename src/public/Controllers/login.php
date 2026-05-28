<?php

use Database\Database;

session_start();

$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (empty($errors)) {

        $user = $db->select(
            "SELECT * FROM users WHERE username = :identifier LIMIT 1",
            ['identifier' => $identifier]
        );

        if (!$user) {
            $errors[] = "Identifiant ou mot de passe incorrect.";
        } else {

            $user = $user[0];

            if (!password_verify($password, $user['password'])) {
                $errors[] = "Identifiant ou mot de passe incorrect.";
            } else {

                $_SESSION['user_id'] = $user["id"];

                header("Location: /home");
                exit;
            }
        }
    }
}


require_once ROOT."/src/public/Views/login.view.php";

