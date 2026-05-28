<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header('location: /register');
}


require_once ROOT."/src/public/Views/editProfile.view.php";

