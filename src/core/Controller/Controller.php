<?php

namespace core\Controller;

class Controller
{
    public function view(string $type, string $path, $data = [])
    {
        extract($data);
        require ROOT . '/src/public/Controllers/' . $path . '.php';
    }

    public function api(string $type, string $path, $data = [])
    {
        extract($data);
        require ROOT . '/src/public/API/' . $path . '.php';
    }

    public function image($path, $data = [])
    {
        $file = ROOT . '/src/public/uploads/' . $path;

        if (!file_exists($file)) {
            http_response_code(404);
            exit("Image introuvable");
        }

        $mime = mime_content_type($file);

        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($file));

        readfile($file);
        exit;
    }

    public function css(string $path): void
    {
        $file = ROOT . '/src/public/Views/' . $path . "/" . $path . '.css';

        if (!file_exists($file)) {
            http_response_code(404);
            exit('CSS introuvable');
        }

        header('Content-Type: text/css; charset=UTF-8');
        readfile($file);
        exit;
    }

    public function js(string $path): void
    {
        $file = ROOT . '/src/public/Views/' . $path . "/" . $path . '.js';

        if (!file_exists($file)) {
            http_response_code(404);
            exit('Javascript introuvable');
        }

        header('Content-Type: application/javascript; charset=UTF-8');
        readfile($file);
        exit;
    }

    public function common(string $path): void
    {
        $file = ROOT . '/src/public/common/' . $path;

        if (!file_exists($file)) {
            http_response_code(404);
            exit('Javascript introuvable');
        }

        if (str_ends_with($path, ".js")) {
            header('Content-Type: application/javascript; charset=UTF-8');
        } else if (str_ends_with($path, ".css")) {
            header('Content-Type: text/css; charset=UTF-8');
        }

        readfile($file);
        exit;
    }
}