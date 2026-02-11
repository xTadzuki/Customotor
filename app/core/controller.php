<?php



class Controller
{
    protected function view(string $view, array $data = []): void
    {
        if (!defined('APP_ROOT')) {
            http_response_code(500);
            exit('APP_ROOT non défini');
        }

        // évite les chemins du type "../"
        $view = trim($view, '/');
        if (str_contains($view, '..')) {
            http_response_code(400);
            exit('chemin de vue invalide');
        }

        $viewFile = APP_ROOT . '/app/views/' . $view . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            exit("Vue introuvable : {$view}");
        }

        // variables disponibles dans la vue
        extract($data, EXTR_SKIP);

        // la vue réelle à inclure dans le layout
        $contentView = $viewFile;

        require APP_ROOT . '/app/views/layouts/main.php';
    }

    protected function redirect(string $to): void
    {
        header('Location: ' . $to);
        exit;
    }
}