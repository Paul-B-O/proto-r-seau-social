<?php

use core\Controller\Controller;
use reseau_iia\Autoloader;
use Database\Database;

define("ROOT", dirname(__DIR__));

require_once ROOT . "/src/config/database.php";
require_once ROOT . "/src/Utils/Autoload.php";

Autoloader::addPath("Database", ROOT . "/Database");
Autoloader::addPath("core", ROOT . "/core");

Autoloader::register();

$db = new Database(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PASS,
    ROOT."/src/Database/schema.sql"
);

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $uri);

$controller = new Controller();

$type = $parts[0] ?? 'home';
$path = implode('/', array_slice($parts, 1));

if ($type === "favicon.ico")
    exit;

switch ($type) {
    case "api":
        $controller->api($type, $path);
        break;
    case "image":
        $controller->image($path ?: 'index');
        break;
    default:
        $fullPath = $uri ?: 'home';
        $controller->view($fullPath);
}
