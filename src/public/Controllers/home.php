<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header('location: /register');
}

use Database\Database;

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
     WHERE id = :user_id
     LIMIT 1",
    [
        'user_id' => $_SESSION['user_id']
    ]
);


if (empty($result)) {
    header('location: /register');
    exit;
}

$user = $result[0];

$id = $user['id'];
$username = $user['username'];
$nickname = $user['nickname'];
$bio = $user['bio'];
$profilePicture = $user['profile_picture'];
$createdAt = $user['created_at'];


require_once ROOT."/src/public/Views/home.view.php";

