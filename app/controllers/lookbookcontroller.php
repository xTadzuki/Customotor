<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/models/project.php';

class LookbookController extends Controller
{
    public function index(): void
    {
        $projects = Project::all();

        $this->view('lookbook/index', [
            'title'    => 'customotor - lookbook',
            'projects' => $projects,
        ]);
    }

    public function show(array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        $project = Project::findWithImages($id);
        if (!$project) {
            http_response_code(404);
            echo '404';
            return;
        }

        $this->view('lookbook/show', [
            'title'   => 'customotor - lookbook',
            'project' => $project,
        ]);
    }
}