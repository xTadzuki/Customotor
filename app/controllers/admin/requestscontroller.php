<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/models/contactrequest.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

class RequestsController extends Controller
{
    public function index(): void
    {
        Middleware::requireAdmin();

        $requests = ContactRequest::all();

        $this->view('admin/requests/index', [
            'title'    => 'admin - demandes',
            'requests' => $requests,
        ]);
    }

    public function show(array $params): void
    {
        Middleware::requireAdmin();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        $request = ContactRequest::find($id);
        if (!$request) {
            http_response_code(404);
            echo '404 - demande introuvable';
            return;
        }

        $this->view('admin/requests/show', [
            'title'   => 'admin - demande #' . $id,
            'request' => $request,
            'updated' => (isset($_GET['updated']) && $_GET['updated'] === '1'),
        ]);
    }

    public function update(array $params): void
    {
        Middleware::requireAdmin();
        csrf_verify();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        $status = (string)($_POST['status'] ?? 'new');

        
        $allowed = ['new', 'in_progress', 'done', 'archived'];
        if (!in_array($status, $allowed, true)) {
            http_response_code(400);
            echo 'statut invalide';
            return;
        }

        try {
            ContactRequest::updateStatus($id, $status);
        } catch (Throwable $e) {
            http_response_code(400);
            echo 'statut invalide';
            return;
        }

        // PRG (compatible sous-dossier)
        header('Location: ' . BASE_URL . '/admin/requests/' . $id . '?updated=1');
        exit;
    }
}