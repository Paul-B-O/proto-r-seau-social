<?php

namespace core\Controller;

class Controller
{
    public function view($path, $data = []) {
        extract($data);
        require ROOT . '/src/public/Controllers/' . $path . '.php';
    }

    public function api($path, $data = []) {
        extract($data);
        require ROOT . '/src/public/API/' . $path . '.php';
    }

    public function image($path, $data = []) {
        $file = ROOT . '/src/public/uploads/' . $path;
        if (!file_exists($file)) {
            http_response_code(404);
            exit("Image introuvable");
        }

        // Détection du type MIME
        $mime = mime_content_type($file);

        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($file));

        readfile($file);
        exit;
    }

}