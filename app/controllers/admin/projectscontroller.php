<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/models/project.php';
require_once APP_ROOT . '/app/models/projectimage.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

class ProjectsController extends Controller
{
    public function index(): void
    {
        Middleware::requireAdmin();

        $projects = Project::all();

        $this->view('admin/projects/index', [
            'title'    => 'admin - lookbook',
            'projects' => $projects,
            'created'  => (isset($_GET['created']) && $_GET['created'] === '1'),
            'updated'  => (isset($_GET['updated']) && $_GET['updated'] === '1'),
            'deleted'  => (isset($_GET['deleted']) && $_GET['deleted'] === '1'),
        ]);
    }

    public function create(): void
    {
        Middleware::requireAdmin();

        $this->view('admin/projects/create', [
            'title'  => 'admin - nouveau projet',
            'errors' => [],
            'old'    => [],
        ]);
    }

    public function store(): void
    {
        Middleware::requireAdmin();
        csrf_verify();

        $title       = trim((string)($_POST['title'] ?? ''));
        $subtitle    = trim((string)($_POST['subtitle'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        $errors = [];
        if ($title === '' || mb_strlen($title) < 2) $errors['title'] = '2 caractères minimum';
        if ($subtitle !== '' && mb_strlen($subtitle) < 2) $errors['subtitle'] = '2 caractères minimum';
        if ($description !== '' && mb_strlen($description) < 10) $errors['description'] = '10 caractères minimum';

        if ($errors) {
            $this->view('admin/projects/create', [
                'title'  => 'admin - nouveau projet',
                'errors' => $errors,
                'old'    => [
                    'title'       => $title,
                    'subtitle'    => $subtitle,
                    'description' => $description,
                ],
            ]);
            return;
        }

        $newId = Project::create([
            'title'       => $title,
            'subtitle'    => $subtitle,
            'description' => $description,
        ]);

        
        header('Location: ' . BASE_URL . '/admin/projects/' . (int)$newId . '/edit?created=1');
        exit;
    }

    public function edit(array $params): void
    {
        Middleware::requireAdmin();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) { http_response_code(404); echo '404'; return; }

        $project = Project::find($id);
        if (!$project) { http_response_code(404); echo '404'; return; }

        $images = ProjectImage::forProject($id);

        $this->view('admin/projects/edit', [
            'title'   => 'admin - modifier projet',
            'project' => $project,
            'errors'  => [],
            'images'  => $images,
            'img_added'   => (isset($_GET['img_added']) && $_GET['img_added'] === '1'),
            'img_deleted' => (isset($_GET['img_deleted']) && $_GET['img_deleted'] === '1'),
            'img_err'     => (string)($_GET['img_err'] ?? ''),
        ]);
    }

    public function update(array $params): void
    {
        Middleware::requireAdmin();
        csrf_verify();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) { http_response_code(404); echo '404'; return; }

        $project = Project::find($id);
        if (!$project) { http_response_code(404); echo '404'; return; }

        $title       = trim((string)($_POST['title'] ?? ''));
        $subtitle    = trim((string)($_POST['subtitle'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        $errors = [];
        if ($title === '' || mb_strlen($title) < 2) $errors['title'] = '2 caractères minimum';
        if ($subtitle !== '' && mb_strlen($subtitle) < 2) $errors['subtitle'] = '2 caractères minimum';
        if ($description !== '' && mb_strlen($description) < 10) $errors['description'] = '10 caractères minimum';

        if ($errors) {
            $project['title']       = $title;
            $project['subtitle']    = $subtitle;
            $project['description'] = $description;

            $images = ProjectImage::forProject($id);

            $this->view('admin/projects/edit', [
                'title'   => 'admin - modifier projet',
                'project' => $project,
                'errors'  => $errors,
                'images'  => $images,
                'img_added'   => false,
                'img_deleted' => false,
                'img_err'     => '',
            ]);
            return;
        }

        Project::update($id, [
            'title'       => $title,
            'subtitle'    => $subtitle,
            'description' => $description,
        ]);

        header('Location: ' . BASE_URL . '/admin/projects?updated=1');
        exit;
    }

    public function delete(array $params): void
    {
        Middleware::requireAdmin();
        csrf_verify();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) { http_response_code(404); echo '404'; return; }

        Project::delete($id);

        header('Location: ' . BASE_URL . '/admin/projects?deleted=1');
        exit;
    }

    // ---------------------------
    // IMAGES
    // ---------------------------

    public function addImage(array $params): void
    {
        Middleware::requireAdmin();
        csrf_verify();

        $projectId = (int)($params['id'] ?? 0);
        if ($projectId <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        // Validate upload
        if (empty($_FILES['image']) || !isset($_FILES['image']['error']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            header('Location: ' . BASE_URL . "/admin/projects/$projectId/edit?img_err=upload");
            exit;
        }

        $alt  = trim((string)($_POST['alt_text'] ?? ''));
        $alt  = $alt !== '' ? $alt : null;
        $sort = (int)($_POST['sort_order'] ?? 0);
        if ($sort < 0) $sort = 0;

        $tmp  = (string)$_FILES['image']['tmp_name'];
        $name = (string)($_FILES['image']['name'] ?? 'image');

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (!in_array($ext, $allowed, true)) {
            header('Location: ' . BASE_URL . "/admin/projects/$projectId/edit?img_err=type");
            exit;
        }

        // Ensure dir
        $destDirFs = rtrim(APP_ROOT, '/\\') . '/public/assets/uploads/lookbook';
        if (!is_dir($destDirFs)) {
            @mkdir($destDirFs, 0777, true);
        }

        // Unique filename
        $newName = 'p' . $projectId . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destFs  = $destDirFs . '/' . $newName;

        if (!move_uploaded_file($tmp, $destFs)) {
            header('Location: ' . BASE_URL . "/admin/projects/$projectId/edit?img_err=move");
            exit;
        }

        // Path saved in DB (public URL path)
        $pathForDb = '/assets/uploads/lookbook/' . $newName;

        ProjectImage::create($projectId, $pathForDb, $alt, $sort);

        header('Location: ' . BASE_URL . "/admin/projects/$projectId/edit?img_added=1");
        exit;
    }

    public function deleteImage(array $params): void
    {
        Middleware::requireAdmin();
        csrf_verify();

        $imgId = (int)($params['id'] ?? 0);
        if ($imgId <= 0) { http_response_code(404); echo '404'; return; }

        $img = ProjectImage::find($imgId);
        if (!$img) { http_response_code(404); echo '404'; return; }

        $projectId = (int)($img['project_id'] ?? 0);
        $path = (string)($img['image_path'] ?? '');

        // delete file if exists
        if ($path !== '') {
            $fs = rtrim(APP_ROOT, '/\\') . '/public' . $path;
            if (is_file($fs)) {
                @unlink($fs);
            }
        }

        ProjectImage::delete($imgId);

        header('Location: ' . BASE_URL . "/admin/projects/$projectId/edit?img_deleted=1");
        exit;
    }
}
