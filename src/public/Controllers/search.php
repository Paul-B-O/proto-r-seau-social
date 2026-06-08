<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header('location: /register');
}


require_once __DIR__ . "/../Views/search/search.view.php";