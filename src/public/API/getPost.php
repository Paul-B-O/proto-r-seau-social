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

$postId   = $_GET['postId'] ?? null;
$username = $_GET['username'] ?? null;
$before   = $_GET['before'] ?? null;
$after    = $_GET['after'] ?? null;

$params = [
    'current_user_id' => $_SESSION['user_id']
];

$sql = "
SELECT
    p.*,
    u.username,
    u.nickname,
    u.profile_picture,

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
INNER JOIN users u ON u.id = p.user_id
WHERE 1=1
";

// postId
if ($postId) {
    $sql .= " AND p.id = :postId ";
    $params['postId'] = $postId;
}

// username
if ($username) {
    $sql .= " AND u.username = :username ";
    $params['username'] = $username;
}

// before (posts avant une date)
if ($before) {
    $sql .= " AND p.created_at < :before ";
    $params['before'] = $before;
}

// after (posts après une date)
if ($after) {
    $sql .= " AND p.created_at > :after ";
    $params['after'] = $after;
}

$sql .= " ORDER BY p.created_at DESC ";

try {

    $posts = $db->select($sql, $params);

    foreach ($posts as &$post) {
        $post['isMyPost'] = ($post['user_id'] == $_SESSION['user_id']);
    }

    $isAdmin = $db->select("
        SELECT EXISTS (
            SELECT 1
            FROM user_have_role uhr
            INNER JOIN roles r ON r.id = uhr.role_id
            WHERE r.role_id = 'admin'
            AND uhr.user_id = :user_id
        ) AS is_admin
    ", [
        'user_id' => $_SESSION['user_id']
    ])[0]['is_admin'];

    $response = [
        'success' => true,
        'posts' => $posts,
    ];
    $response['isAdmin'] = $isAdmin;

    if ($isAdmin) {
        $response['isAdmin'] = $isAdmin;
    }

    echo json_encode($response);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}