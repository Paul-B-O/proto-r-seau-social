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

    echo json_encode([
        'success' => true,
        'post_id' => $postId
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur'
    ]);
}