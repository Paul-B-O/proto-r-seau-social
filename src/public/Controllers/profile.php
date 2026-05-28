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

$posts = $db->select(
    "SELECT
        p.id,
        p.content,
        p.created_at,

        COUNT(ul.user_id) AS like_count

     FROM posts p

     LEFT JOIN user_likes ul
        ON ul.post_id = p.id

     WHERE p.user_id = :id

     GROUP BY
        p.id,
        p.content,
        p.created_at

     ORDER BY p.created_at DESC LIMIT 20",
    [
        'id' => $id
    ]
);


require_once ROOT . "/src/public/Views/profile.view.php";