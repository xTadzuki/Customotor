<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

require_once APP_ROOT . '/app/models/service.php';
require_once APP_ROOT . '/app/models/servicecategory.php';

class ServicesController extends Controller
{
    private function guardAdmin(): void
    {
        
        Middleware::requireAdmin();
    }

    public function index(): void
    {
        $this->guardAdmin();

        $services = Service::allForAdminWithCategory();

        $this->view('admin/services/index', [
            'title'    => 'Admin — Services',
            'active'   => 'admin',
            'services' => $services,
            'created'  => !empty($_GET['created']),
            'updated'  => !empty($_GET['updated']),
            'deleted'  => !empty($_GET['deleted']),
        ]);
    }

    public function create(): void
    {
        $this->guardAdmin();

        $categories = ServiceCategory::all();

        $this->view('admin/services/create', [
            'title'      => 'Admin — Nouveau service',
            'active'     => 'admin',
            'categories' => $categories,
            'service'    => [
                'category_id' => null,
                'name'        => '',
                'description' => '',
                'price_from'  => '',
                'is_active'   => 1,
                'sort_order'  => 0,
            ],
            'errors'     => [],
        ]);
    }

    public function store(): void
    {
        $this->guardAdmin();
        csrf_verify();

        try {
            Service::create($_POST);

            header('Location: ' . BASE_URL . '/admin/services?created=1');
            exit;
        } catch (Throwable $e) {
            $categories = ServiceCategory::all();

            $this->view('admin/services/create', [
                'title'      => 'Admin — Nouveau service',
                'active'     => 'admin',
                'categories' => $categories,
                'service'    => $_POST,
                'errors'     => [$e->getMessage()],
            ]);
        }
    }

    public function edit(array $params): void
    {
        $this->guardAdmin();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        $service = Service::findForAdmin($id);
        if (!$service) {
            http_response_code(404);
            echo '404 - service introuvable';
            return;
        }

        $categories = ServiceCategory::all();

        $this->view('admin/services/edit', [
            'title'      => 'Admin — Modifier service',
            'active'     => 'admin',
            'categories' => $categories,
            'service'    => $service,
            'errors'     => [],
        ]);
    }

    public function update(array $params): void
    {
        $this->guardAdmin();
        csrf_verify();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        try {
            Service::update($id, $_POST);

            header('Location: ' . BASE_URL . '/admin/services?updated=1');
            exit;
        } catch (Throwable $e) {
            $categories = ServiceCategory::all();

            $service = Service::findForAdmin($id) ?: ['id' => $id];
            $service = array_merge($service, $_POST);

            $this->view('admin/services/edit', [
                'title'      => 'Admin — Modifier service',
                'active'     => 'admin',
                'categories' => $categories,
                'service'    => $service,
                'errors'     => [$e->getMessage()],
            ]);
        }
    }

    public function delete(array $params): void
    {
        $this->guardAdmin();
        csrf_verify();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        Service::softDelete($id);

        header('Location: ' . BASE_URL . '/admin/services?deleted=1');
        exit;
    }
}
