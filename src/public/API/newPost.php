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

$content = trim($data['content'] ?? '');

if ($content === '') {
    echo json_encode([
        'success' => false,
        'error' => 'Contenu vide'
    ]);
    exit;
}

if (strlen($content) > 280) {
    echo json_encode([
        'success' => false,
        'error' => 'Tweet trop long (280 caractères max)'
    ]);
    exit;
}

try {

    $postId = $db->insert(
        "INSERT INTO posts (content, user_id)
         VALUES (:content, :user_id)",
        [
            'content' => $content,
            'user_id' => $_SESSION['user_id']
        ]
    );

    $post = $db->select("SELECT p.*, u.nickname, u.username, u.profile_picture,
       (
            SELECT COUNT(*)
            FROM user_likes ul
            WHERE ul.post_id = p.id
        ) AS like_count,

        EXISTS(
            SELECT 1
            FROM user_likes ul2
            WHERE ul2.post_id = p.id
            AND ul2.user_id = :current_user_id
        ) AS liked_by_me

        FROM posts p
        INNER JOIN users u ON p.user_id = u.id
        WHERE p.id = :id", ["id" => $postId, "current_user_id" => $_SESSION['user_id']]);

    echo json_encode([
        'success' => true,
        'post' => $post[0]
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur'
    ]);
}