<?php

use Database\Database;

session_start();

header('Content-Type: application/json');

// ======================
// 1. Vérification login
// ======================
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Non authentifié'
    ]);
    exit;
}

$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$data = json_decode(file_get_contents("php://input"), true);

$postId = trim($data['postId'] ?? '');

if ($postId === '') {
    echo json_encode([
        'success' => false,
        'error' => 'post id vide'
    ]);
    exit;
}

$exists = $db->select("SELECT EXISTS (SELECT 1 FROM posts WHERE id = :postId) as exist", ['postId' => $postId])[0]["exist"];

if (!$exists) {
    echo json_encode([
        'success' => false,
        'error' => "Ce post n'existe pas"
    ]);
    exit;
}

try {

    $hasLiked = $db->select("SELECT EXISTS (SELECT 1 FROM user_likes ul WHERE
        ul.post_id = :post_id AND ul.user_id = :user_id) as exist",
    ['post_id' => $postId, 'user_id' => $_SESSION['user_id']])[0]["exist"];

    if ($hasLiked) {
        $db->delete("DELETE FROM user_likes WHERE post_id = :post_id AND user_id = :user_id",
            [
                'user_id' => $_SESSION['user_id'],
                'post_id' => $postId
            ]
        );

    } else {
        $db->insert(
            "INSERT INTO user_likes (user_id, post_id)
             VALUES (:user_id, :post_id)",
            [
                'user_id' => $_SESSION['user_id'],
                'post_id' => $postId
            ]
        );
    }


    $like_count = $db->select("SELECT COUNT(*) as like_count FROM user_likes WHERE post_id = :postId", ['postId' => $postId])[0]["like_count"];

    echo json_encode([
        'success' => true,
        'like_count' => $like_count
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur'
    ]);
}