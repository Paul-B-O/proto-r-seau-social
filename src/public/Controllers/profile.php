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


require_once ROOT . "/src/public/Views/profile/profile.view.php";