<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header('location: /register');
}

use Database\Database;

if (!isset($_GET['username'])) {
    http_response_code(404);
    exit("Utilisateur introuvable");
}

$username = $_GET['username'];

$db = new Database(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PASS
);

$result = $db->select(
    "SELECT 
        id,
        username,
        nickname,
        bio,
        profile_picture,
        created_at
     FROM users
     WHERE username = :username
     LIMIT 1",
    [
        'username' => $username
    ]
);

if (empty($result)) {
    header('location: /noProfile');
    exit("Utilisateur introuvable");
}

$user = $result[0];

$id = $user['id'];
$username = $user['username'];
$nickname = $user['nickname'];
$bio = $user['bio'];
$profilePicture = $user['profile_picture'];
$createdAt = $user['created_at'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nickname = trim($_POST['nickname'] ?? '');
    $newUsername = trim($_POST['username'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    // Validation
    if (strlen($nickname) < 3 || strlen($nickname) > 30) {
        $errors[] = "Le pseudo doit contenir entre 3 et 30 caractères.";
    }

    if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $newUsername)) {
        $errors[] = "Nom d'utilisateur invalide.";
    }

    if (strlen($bio) > 280) {
        $errors[] = "La biographie est trop longue.";
    }

    // Vérification unicité username
    $existing = $db->select(
        "SELECT id
         FROM users
         WHERE username = :username
         AND id != :id",
        [
            'username' => $newUsername,
            'id' => $user['id']
        ]
    );

    if (!empty($existing)) {
        $errors[] = "Ce nom d'utilisateur est déjà utilisé.";
    }

    $profilePicturePath = $user['profile_picture'];

    // Upload image
    if (
        isset($_FILES['profile_picture']) &&
        $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK
    ) {

        $tmpFile = $_FILES['profile_picture']['tmp_name'];

        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        $mime = mime_content_type($tmpFile);

        if (!isset($allowedMimeTypes[$mime])) {
            $errors[] = "Format d'image non autorisé.";
        } else {

            $extension = $allowedMimeTypes[$mime];

            $uploadDir = ROOT . '/src/public/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!empty($user['profile_picture'])) {

                $oldFile = ROOT . '/src/public/uploads/' . basename($user['profile_picture']);

                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $filename = $user['id'] . '.' . $extension;

            $destination = $uploadDir . $filename;

            if (move_uploaded_file($tmpFile, $destination)) {

                // chemin relatif stocké en BDD
                $profilePicturePath = '/image/' . $filename;

            } else {
                $errors[] = "Impossible d'enregistrer l'image.";
            }
        }
    }

    // Changement mot de passe
    if (
        !empty($_POST['old_password']) ||
        !empty($_POST['new_password'])
    ) {

        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';

        $passwordData = $db->select(
            "SELECT password
             FROM users
             WHERE id = :id",
            [
                'id' => $user['id']
            ]
        );

        if (
            empty($passwordData) ||
            !password_verify(
                $oldPassword,
                $passwordData[0]['password']
            )
        ) {
            $errors[] = "Ancien mot de passe incorrect.";
        }

        if (strlen($newPassword) < 6) {
            $errors[] = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
        }
    }

    // Sauvegarde
    if (empty($errors)) {

        $params = [
            'nickname' => $nickname,
            'username' => $newUsername,
            'bio' => $bio,
            'profile_picture' => $profilePicturePath,
            'id' => $user['id']
        ];

        $query = "
            UPDATE users
            SET
                nickname = :nickname,
                username = :username,
                bio = :bio,
                profile_picture = :profile_picture
        ";

        if (!empty($_POST['new_password'])) {

            $query .= ",
                password = :password
            ";

            $params['password'] = password_hash(
                $_POST['new_password'],
                PASSWORD_BCRYPT
            );
        }

        $query .= "
            WHERE id = :id
        ";

        $db->update($query, $params);

        header('Location: /profile?username=' . urlencode($newUsername));
        exit;
    }
}

require_once ROOT . "/src/public/Views/editProfile/editProfile.view.php";