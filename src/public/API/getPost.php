<?php

use Database\Database;

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Non authentifié'
    ]);
    exit;
}

$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$data = json_decode(file_get_contents("php://input"), true);


try {
    $posts = $db->select("SELECT p.*, u.username, u.nickname, u.profile_picture FROM posts p
        INNER JOIN users u ON u.id = p.user_id
        ORDER BY p.created_at DESC LIMIT 10");

    echo json_encode([
        'success' => true,
        'posts' => $posts
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur'
    ]);
}