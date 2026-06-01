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
    $post = $db->select("SELECT * FROM posts WHERE id = :postId AND user_id = :user_id", ['postId' => $postId, "user_id" => $_SESSION["user_id"]])[0];
    $isAdmin = $db->select("SELECT EXISTS (
                SELECT 1 FROM user_have_role uhr
                INNER JOIN users u ON u.id = uhr.user_id
                INNER JOIN roles r ON r.id = uhr.role_id
                WHERE r.role_id = 'admin' AND u.id = :user_id
            ) as is_admin",
    ["user_id" => $_SESSION["user_id"]]
    )[0]["is_admin"];

    if (!$post && !$isAdmin) {
        echo json_encode([
            'success' => false,
            'error' => "Vous n'êtes pas l'auteur de ce post"
        ]);
        exit;
    }

    $db->delete("DELETE FROM posts WHERE id = :postId", ['postId' => $postId]);

    echo json_encode([
        'success' => true,
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur'
    ]);
}