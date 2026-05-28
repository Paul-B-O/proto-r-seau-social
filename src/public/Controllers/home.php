<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header('location: /login');
}


require_once ROOT."/src/public/Views/home.view.php";

