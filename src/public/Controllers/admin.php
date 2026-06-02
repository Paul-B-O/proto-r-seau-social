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

$isAdmin = $db->select("SELECT EXISTS (
                SELECT 1 FROM user_have_role uhr
                INNER JOIN users u ON u.id = uhr.user_id
                INNER JOIN roles r ON r.id = uhr.role_id
                WHERE r.role_id = 'admin' AND u.id = :user_id
            ) as is_admin",
    ["user_id" => $_SESSION["user_id"]]
)[0]["is_admin"];


if (!$isAdmin) {
    header('location: /home');
    exit("Permission non suffisante");
}

require_once ROOT . "/src/public/Views/admin/admin.view.php";