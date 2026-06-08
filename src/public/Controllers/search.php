<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header('location: /register');
}

use Database\Database;

$username = $_GET['search'];


$db = new Database(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PASS
);

